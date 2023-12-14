<?php
$folderPath = '../../models/';

$files = glob($folderPath . '*.php');

foreach ($files as $file) {
    include_once $file;
}
?>