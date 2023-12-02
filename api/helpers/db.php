<?php

$folderPath = '';

$files = glob($folderPath . '*.php');

foreach ($files as $file) {
    include_once $file;
}

$db_host = "localhost";
$db_user = $db_user."".$db;
$db_name = $db_user."".$db;

$conn=mysqli_connect($db_host,$db_user,$db_pass,$db_name);
if (!$conn) {
  echo "DB Not Connected";
}

function redirect($path,$params){

    $url = "Location:".$path.".php?";

    foreach($params as $param=>$value) {
        $url = $url."$param=$value";
    }

    header($url);

}
?>