<?php
/**
 * Admit Cards were dispatched by Speed Post
 * We also get their post office address, just for fun
 * (and possible analytics later)
 */
require '../functions.php';
$speed_post_web_root = 'http://services.ptcmysore.gov.in/Speednettracking/Track.aspx?articlenumber=';
$results = R::find('candidate',' length(tracking) = 13');
foreach($results as $candidate){
	if(!$candidate->speed_post_address){
		$data = file_get_contents($speed_post_web_root.$candidate->tracking);
		//echo $data;
		$address =  cut_str($data,'2012</td><td align="left">','</td>');
		if(strlen($address) > 100){
			continue;
		}
		$candidate->speed_post_address = $address;
		echo $address."\n";
		R::store($candidate);
	}
}
