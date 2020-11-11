<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionService extends Model
{
    protected $url = "https://opentdb.com/api.php?amount=10";

    public function get(){

    	$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_URL => $this->url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => "",
		    CURLOPT_TIMEOUT => 30000,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => "GET",
		    CURLOPT_HTTPHEADER => array(
		    	// Set Here Your Requesred Headers
		        'Content-Type: application/json',
		    ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

    	$response = json_decode($response, true);
        $result = $response['results'];
        return($result);
    }
}
