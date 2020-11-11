<?php

namespace App\Http\Controllers;

use App\Question;
use App\QuestionsOption;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionsRequest;
use App\Http\Requests\UpdateQuestionsRequest;

use App\QuestionService;
use App\Topic;

use DB;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('cancallapi', ['only' => ['storeUsingApi']]);
    }

    /**
     * Display a listing of Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::all();

        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating new Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $relations = [
            'topics' => \App\Topic::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        $correct_options = [
            'option1' => 'Option #1',
            'option2' => 'Option #2',
            'option3' => 'Option #3',
            'option4' => 'Option #4',
            'option5' => 'Option #5'
        ];

        return view('questions.create', compact('correct_options') + $relations);
    }

    /**
     * Store a newly created Question in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionsRequest $request)
    {
        dd($request->all());
        $question = Question::create($request->all());

        foreach ($request->input() as $key => $value) {
            if(strpos($key, 'option') !== false && $value != '') {
                $status = $request->input('correct') == $key ? 1 : 0;
                QuestionsOption::create([
                    'question_id' => $question->id,
                    'option'      => $value,
                    'correct'     => $status
                ]);
            }
        }

        return redirect()->route('questions.index');
    }


    /**
     * Show the form for editing Question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $relations = [
            'topics' => \App\Topic::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        $question = Question::findOrFail($id);

        return view('questions.edit', compact('question') + $relations);
    }

    /**
     * Update Question in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionsRequest $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());

        return redirect()->route('questions.index');
    }


    /**
     * Display Question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relations = [
            'topics' => \App\Topic::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        $question = Question::findOrFail($id);

        return view('questions.show', compact('question') + $relations);
    }


    /**
     * Remove Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.index');
    }

    /**
     * Delete all selected Question at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Question::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    /**
     * Store Questions Using 3rd party API.
     *
     * 
     */
    public function storeUsingApi(){
        $obj = new QuestionService;
        $results = $obj->get();
        $allTopics = Topic::pluck("title","id")->all();
        foreach ($results as $key => $result) {
            /*
            * Checking if topic already exists or not.
            * If not exists then adding that topic in local DB
            */
            if (!in_array($result['category'], $allTopics)) {
                $requestTopic = ["_token" => csrf_token(), "title" => $result['category']];
                $storeTopic = Topic::create($requestTopic);
            }
        }
        

        $allTopics = Topic::pluck("title","id")->all();
        try{
            DB::beginTransaction();
            foreach ($results as $key => $result) {
                /*
                * Checking if topic/question combination already exists or not.
                * If not exists then adding that question & options in local DB. 
                */
                $question = Question::where(["topic_id"=>array_search($result['category'],$allTopics), "question_text" => $result['question']])->get();
                if($question->isEmpty()){

                    $requestQuestion = ["_token" => csrf_token(), "question_text" => $result['question'], "answer_explanation" => "N/A", "topic_id" => array_search($result['category'],$allTopics)];

                    //  Storing question
                    $storeQuestion = Question::create($requestQuestion);

                    // Storing correct options/option
                    if(is_array($result['correct_answer'])){
                        foreach ($result['correct_answer'] as $key => $correctAnswer) {
                            $correctAnswerRequest = ["_token" => csrf_token(), "option" => $correctAnswer, "correct" => 1, "question_id" => $storeQuestion->id];
                            $storeCorrectAnswer = QuestionsOption::create($correctAnswerRequest);
                        }
                    } else {
                        $correctAnswerRequest = ["_token" => csrf_token(), "option" => $result['correct_answer'], "correct" => 1, "question_id" => $storeQuestion->id];
                        $storeCorrectAnswer = QuestionsOption::create($correctAnswerRequest);
                    }

                    // Storing incorrect options
                    foreach ($result['incorrect_answers'] as $key => $incorrectAnswer) {
                        $incorrectAnswerRequest = ["_token" => csrf_token(), "option" => $incorrectAnswer, "correct" => 0, "question_id" => $storeQuestion->id];
                        $storeIncorrectAnswer = QuestionsOption::create($incorrectAnswerRequest);
                    }
                }
            }
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            $notification = array(
                'message'    => 'Some problem has been occurred !!',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }


        $notification = array(
            'message'    => 'Questions inserted successfully !!',
            'alert-type' => 'success',
        );
        return back()->with($notification);
    }

}
