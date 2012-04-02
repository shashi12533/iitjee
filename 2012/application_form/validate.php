<?php
require 'HTTP/Request2.php';
require '../functions.php';
/**
 * This submits a application form
 * to validatestatus.php
 * and adds additional data about the candidate
 * REG_NO = C[11-17|21-27][1-3][0-9]{5}
 */

$number = $argv[1];//11-17
$order = isset($argv[2]) ? $argv[2] : 'ASC';
//$mod = isset($argv[3])? 1 : 0;//default is odd
/*
 * check the status of validatestatus.php
 */
$iit = $number%10;
if($config['validation_status'][$iit] == 'down'){
	die("The site's validation check is down\n");
}

$results = R::getAll("SELECT form_no from candidate WHERE form_no LIKE 'C$number%' AND category IS NULL ORDER BY id $order");
foreach($results as $data){
	$form_no = $data['form_no'];
	$site_root = $config['jee_sites_root'][$iit];
	$request = new HTTP_Request2($site_root.'validatestatus.php');
	$request->setMethod(HTTP_Request2::METHOD_POST)
			->addPostParameter('appno', $form_no);
	$body = explode("\n",$request->send()->getBody());
	$candidate = R::findOne('candidate','form_no = ?',array($form_no));//find the corresponding id
	$candidate = R::load('candidate',$candidate->id);//create the bean
	$candidate->category = cut_str($body[69],">","<");
	$candidate->nationality = cut_str($body[75],">","<");
	$candidate->gender = cut_str($body[81],">","<");
	$candidate->ds = cut_str($body[87],">","<");//defense service
	$candidate->physically_disabled = cut_str($body[95],">","<");//physically disabled
	$candidate->visually_impaired = cut_str($body[101],">","<");//visually impaired
	$candidate->qualification_year = cut_str($body[109],">","<");//12th passing
	$candidate->date_of_birth = cut_str($body[115],">","<");//date of birth
	$candidate->high_school_year = cut_str($body[122],">","<");//passed high school in
	$candidate->center_code_1 = cut_str($body[133],">","<");//center code one
	$candidate->center_code_2 = cut_str($body[138],">","<");//center code two
	$candidate->first_attempt_year = cut_str($body[144],">","<");//first attempt at jee
	$candidate->question_paper_lang = cut_str($body[152],">","<");//question paper language
	$candidate->parent_or_guardian = trim(cut_str($body[158],">","<"));//name of a parent guardian
	$candidate->high_school_place = cut_str($body[164],">","<");//high school was in town/village/city
	$candidate->preparation = cut_str($body[170],">","<");//type of preparation
	$candidate->board = cut_str($body[176],">","<");//board of 12th
	$candidate->occupation_of_parent = cut_str($body[182],">","<");//
	$candidate->parent_education = cut_str($body[189],">","<");//
	$candidate->annual_income = cut_str($body[195],">","<");//
	$candidate->mother_tongue = cut_str($body[203],">","<");//
	$candidate->pincode = cut_str($body[210],">","<");//
	R::store($candidate);
	echo $form_no. " - SAVED\n";
}
