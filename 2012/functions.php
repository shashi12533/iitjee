<?php
/**
 * Common functions
 */
$config = parse_ini_file('config.ini',true);
require('rb.php');
R::setup('mysql:host='.$config['db']['host'].';dbname='.$config['db']['dbname'],$config['db']['user'],$config['db']['password']);
R::freeze( true ); //will freeze redbeanphp
function cut_str($string,$left,$right){
	$start = strpos($string,$left) + strlen($left);
	if($start!==false){
		$length = strpos($string,$right,$start) - $start;
		return substr($string,$start,$length);
	}
	else
		return '';
}
