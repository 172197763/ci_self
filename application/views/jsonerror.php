<?php
// RFC4627-compliant header
header('Content-type: application/json');
$outputarray = array('status' => 'error', 'msg' => $errorMsg);
$result=json_encode($outputarray);
echo $result;