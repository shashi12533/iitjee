<?php
$config = parse_ini_file('config.ini',true);
$iit_code = $argv[1];
if($iit_code >10)
	$iit_code%= 10;
echo $config['jee_sites_root'][$iit_code];
