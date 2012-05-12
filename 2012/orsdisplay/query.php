#!/usr/bin/php
<?php
require '../functions.php';
while(true){
	$process = "\033[31m#".$argv[1]."\033[30m";
	echo "[$process] Getting New batch of 100\n";
	$update_query = '';
	$data = R::find('queries','1 ORDER BY RAND() LIMIT 0,100');
	$count=1;
	foreach($data as $query){
		$iit = substr($query['form_no'],2,1);
		$site_root = $config['jee_sites_root'][$iit];
  		$marks = explode(' ',shell_exec("php ors_get_data.php {$query['form_no']} {$query['reg_no']} {$query['date_of_birth']} '$site_root'"));
  		$update_query.="UPDATE candidate SET marks_phy={$marks[0]},marks_chem={$marks[1]},marks_maths={$marks[2]} WHERE reg_no={$query['reg_no']}; ";
		echo $process ." ".$count++."/100. ".$query['form_no']. " ".$query['reg_no']. " " .$query['date_of_birth']. " => PCM = ".$marks[0]." ".$marks[1]." ".$marks[2]."\n";
	}
	//once this has been done
	echo "[$process] Updating candidate table - ";
	R::exec($update_query);
	echo "Done\n";
	echo "[$process] Deleting records - ";
	foreach($data as $query){
		R::trash($query);
	}
	echo "Done\n";
}
