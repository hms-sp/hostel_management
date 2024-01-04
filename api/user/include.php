<?php
$folderPath = '../../models/';

$files = glob($folderPath . '*.php');

foreach ($files as $file) {
    include_once $file;
}
include_once('../../helpers/db.php');
include_once('../../helpers/constants.php');
include_once('../../helpers/repository.php');
include_once('../../helpers/security.php');

$Class = 'user';
$Table = 'users';
$PK = 'user';


$admin = "admin";
$hostel  = "1212";

$hostelRepo=new repository("hostel","hostels","id",$conn);
$userRepo=new repository("user","users","username",$conn);

?>