
<?php

$verificacion= rand(10000, 99999);

$post["to"] = array("34650460027"); 
$post["message"] = "Codigo de verificacion:".$verificacion; 
$post["from"] = "UGT";
$user = "comunicacionfes";
$password = "YJeh74?#";
try {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://dashboard.360nrs.com/api/rest/sms"); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post)); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
"Accept: application/json",
"Authorization: Basic " . base64_encode($user . ":" . $password))); $result = curl_exec($ch);
var_dump($result);
} catch (Exception $exc) {
echo $exc->getTraceAsString();
}
?>
