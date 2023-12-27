<?php
$admin = security::getCurrentUser();
if($admin){
    echo "logged in ".$admin;
}
else{
    echo "authentication failed";
    exit;
}
?>