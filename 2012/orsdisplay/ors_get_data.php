<?php
require '../functions.php';
/**
 * This submits a application form
 * to validatestatus.php
 * and adds additional data about the candidate
 * REG_NO = C[11-17|21-27][1-3][0-9]{5}
 */

$number = $argv[1];//11-17
$iit= $number%10;
$order = isset($argv[2]) ? $argv[2] : 'ASC';
$results = R::getAll("SELECT form_no,reg_no,date_of_birth from candidate WHERE form_no LIKE 'C$number%' ORDER BY id $order LIMIT 0,5");
foreach($results as $data){
	$form_no = $data['form_no'];
  	$reg_no = $data['reg_no'];
    echo $form_no." - $reg_no";
    if(file_exists("pics/".$reg_no."_2.html")){
		echo " - DONE\n";
		continue;
	}
	$site_root = $config['jee_sites_root'][$iit];
  	$date = explode('/',$data['date_of_birth']);
    if(!$date[0]) continue;
    $cookie = trim(`./get_cookie.sh $reg_no $form_no $date[0] $date[1] $date[2] "$site_root"`);
    echo $cookie;
    die;
  	//Now we have authenticated ourselves
    //PAPER 1
  	if(!file_exists("html/".$reg_no."_2.html")){
    	`./save_paper.sh $cookie $reg_no $site_root`;
	}
    if(!file_exists("pics/".$reg_no."_2.html"))
    	`./save_pic.sh $cookie $reg_no $site_root`;
    echo " - NOWDONE\n";
}
