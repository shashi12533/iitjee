<?php
//require __DIR__.'/../functions.php';
/**
 * This submits a application form
 * to validatestatus.php
 * and adds additional data about the candidate
 * REG_NO = C[11-17|21-27][1-3][0-9]{5}
 */

;//11-17 This is the form_no
$form_no = $argv[1];
$reg_no = $argv[2];
//echo $form_no." - $reg_no";
$date = explode('/',$argv[3]);
$site_root = $argv[4];
if(!$date[0]) exit;
$url  = $site_root."orsdisplay/index.php";
`curl -c $form_no.txt -q -s -X POST -D- -d'appno=$reg_no&formno=$form_no&DateOfBirth_Day=$date[0]&DateOfBirth_Month=$date[1]&DateOfBirth_Year=$date[2]&submit=Submit'  '$url'`;
//Now we have authenticated ourselves
`curl -s -b $form_no.txt "$site_root"orsdisplay/orsdisplayp1.php -o html/$reg_no.1.html`;
`curl -s -b $form_no.txt "$site_root"orsdisplay/orsdisplayp2.php -o html/$reg_no.2.html`;
if(file_exists($form_no.".txt"))
	unlink($form_no.".txt");
$papers = array(file("html/".$reg_no.".1.html"),file("html/".$reg_no.".2.html"));
$marks = array(0,0,0);//P,C,M
try{
	foreach($papers as $p){
		$marks[0]+=cut_str($p[183],":","<");
		$marks[1]+=cut_str($p[325],":","<");
		$marks[2]+=cut_str($p[468],":","<");
	}
}
catch(Exception $e){
	$marks = array(0,0,0);
}
echo $marks[0]." ".$marks[1]." ".$marks[2]."\n";

function cut_str($string,$left,$right){
	$start = strpos($string,$left) + strlen($left);
	if($start!==false){
		$length = strpos($string,$right,$start) - $start;
		return substr($string,$start,$length);
	}
	else
		return '';
}
