<?php
$admin = security::getCurrentUser();
$role = security::getRoles();
if($admin && $role == "admin"){
    echo "logged in ".$admin;
}
else if($role != "admin"){
    echo "authorization failed";
    exit;
}
else{
    echo "authentication failed";
    exit;
}
?>