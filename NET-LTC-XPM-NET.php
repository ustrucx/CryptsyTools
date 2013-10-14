<?php
// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
// The JSON standard MIME header.
//header('Content-type: application/json');
include 'cryptsy.php';

//this onwards must be a function that receive the $balance and spits the safe volume to check if the chain is profitable
$result=volumeFinder(0, 108, 106, 104);
if($result['restart']==1){
	volumeFinder($result['result'], 108, 106, 104);
	echo "<br>2 run*<br>creating orders to profit: ".$result['result']." .";
}else{
	echo "<br>creating orders to profit: ".$result['result']." .";
}