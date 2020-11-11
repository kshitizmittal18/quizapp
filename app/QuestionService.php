<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionService extends Model
{
    protected $url = "https://opentdb.com/api.php?amount=10";

    public function get(){
    	$response = json_decode('{"response_code":0,"results":[{"category":"History","type":"multiple","difficulty":"easy","question":"How many manned moon landings have there been?","correct_answer":"6","incorrect_answers":["1","3","7"]},{"category":"Entertainment: Video Games","type":"multiple","difficulty":"medium","question":"Which of these features was added in the 1994 game &quot;Heretic&quot; that the original &quot;DOOM&quot; could not add due to limitations?","correct_answer":"Looking up and down","incorrect_answers":["Increased room sizes","Unlimited weapons","Highly-detailed textures"]},{"category":"Geography","type":"multiple","difficulty":"hard","question":"Where is the fast food chain &quot;Panda Express&quot; headquartered?","correct_answer":"Rosemead, California","incorrect_answers":["Sacramento, California","Fresno, California","San Diego, California"]},{"category":"Entertainment: Cartoon & Animations","type":"multiple","difficulty":"easy","question":"Who created the Cartoon Network series &quot;Adventure Time&quot;?","correct_answer":"Pendleton Ward","incorrect_answers":["J. G. Quintel","Ben Bocquelet","Rebecca Sugar"]},{"category":"Animals","type":"boolean","difficulty":"medium","question":"The Platypus is a mammal.","correct_answer":"True","incorrect_answers":["False"]},{"category":"History","type":"multiple","difficulty":"medium","question":"What was the transfer of disease, crops, and people across the Atlantic shortly after the discovery of the Americas called?","correct_answer":"The Columbian Exchange","incorrect_answers":["Triangle Trade","Transatlantic Slave Trade","The Silk Road"]},{"category":"Entertainment: Video Games","type":"multiple","difficulty":"easy","question":"In &quot;Call Of Duty: Zombies&quot;, what is the name of the Pack-A-Punched Crossbow?","correct_answer":"Awful Lawton","incorrect_answers":["Longinus","V-R11","Predator"]},{"category":"Entertainment: Japanese Anime & Manga","type":"boolean","difficulty":"hard","question":"In the &quot;Kagerou Daze&quot; series, Shintaro Kisaragi is prominently shown with the color red.","correct_answer":"True","incorrect_answers":["False"]},{"category":"Sports","type":"multiple","difficulty":"easy","question":"Which boxer was banned for taking a bite out of Evander Holyfield&#039;s ear in 1997?","correct_answer":"Mike Tyson","incorrect_answers":["Roy Jones Jr.","Evander Holyfield","Lennox Lewis"]},{"category":"Entertainment: Television","type":"multiple","difficulty":"medium","question":"What actor portrays Hogan &quot;Wash&quot; Washburne in the TV Show Firefly?","correct_answer":"Alan Tudyk","incorrect_answers":["Adam Baldwin","Nathan Fillion","Sean Maher"]}]}', true);
        $result = $response['results'];
        // $questions = [];
        // foreach ($result as $key => $question) {
        //     if (is_array($question['correct_answer'])) {
        //         $options = array_merge($question['incorrect_answers'],$question['correct_answer']);
        //     } else {
        //         $options= $question['incorrect_answers'];
        //         array_push($options,$question['correct_answer']);
        //     }
        //     $question['options'] = $options;
        //     array_push($questions,$question);
        // }
        return($result);
    }
}
