<?php
require '../functions.php';
/**
 * This submits a application form
 * to validatestatus.php
 * and adds additional data about the candidate
 * REG_NO = C[11-17|21-27][1-3][0-9]{5}
 */
$filename = $argv[1];
$reg_no = substr($filename,5,7);
$papers = array(file($filename),file("html/".$reg_no."_2.html"));
$marks = array(0,0,0);//P,C,M
foreach($papers as $p){
	$marks[0]+=cut_str($p[183],":","<");
	$marks[1]+=cut_str($p[325],":","<");
	$marks[2]+=cut_str($p[468],":","<");
}
if($marks[0]==$marks[1]&& $marks[1]==$marks[2]&&$marks[0]==0)
	die("all 3 are zero for $reg_no");
$candidate = R::findOne('candidate','reg_no = ?',array($reg_no));//find the corresponding id
if($candidate->marks_chem!=0 || $candidate->marks_phy!=0 || $candidate->marks_maths!=0){
    echo "Already done - Deleting\n";
    unlink($filename);
    unlink("html/".$reg_no."_2.html");
    exit;
}
$candidate = R::load('candidate',$candidate->id);//create the bean
$candidate->marks_phy = $marks[0];
$candidate->marks_chem = $marks[1];
$candidate->marks_maths = $marks[2];
R::store($candidate);
echo $candidate->reg_no." ".$marks[0]." ".$marks[1]. " ".$marks[2]."\n";
