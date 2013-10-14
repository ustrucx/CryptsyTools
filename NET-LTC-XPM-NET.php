<?php
// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
// The JSON standard MIME header.
//header('Content-type: application/json');
include 'cryptsy.php';

//this onwards must be a function that receive the $balance and spits the safe volume to check if the chain is profitable
volumeFinder(0, 108, 106, 104);