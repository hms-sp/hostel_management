<?php
$folderPath = '../../models/';

$files = glob($folderPath . '*.php');

foreach ($files as $file) {
    include_once $file;
}
include_once('../../helpers/db.php');
include_once('../../helpers/constants.php');
include_once('../../helpers/repository.php');
?>