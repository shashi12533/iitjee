<?php
//Checks the availability of the 
//validate.php file and reports back
//200 = OK
//404 = MISSING
$config = parse_ini_file('../config.ini',true);
$jee_sites_root = $config['jee_sites_root'];
foreach($jee_sites_root as $insti=>$i){
    $validation_url = $i."validatestatus.php";
    $headers = (get_headers($validation_url,true));
    echo $insti. " - ".substr($headers[0],9,3)."\n";
}
