<?php
include '../functions.php';
$result = R::getRow("SELECT COUNT(*) as count FROM queries");
echo $result['count'];
