<?php
// RFC4627-compliant header
header('Content-type: application/json');
// Encode data
$msg=isset($msg)?$msg:'';
$outputarray = array('status' => 'OK', 'data' => $jsonArray,'msg'=>$msg);
if(isset($merr))
{
    $outputarray=array_merge($outputarray,$merr);
}

$result=json_encode($outputarray);
echo $result;