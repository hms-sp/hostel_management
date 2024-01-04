<?php
$admin = security::getCurrentUser();
$roles = security::getRoles();
if(!$admin){
    echo "session not found";
    exit;
}
else if(!in_array("admin",$roles)){
    echo "authorization failed .";
    exit;
}
else{
    echo "login success";
    
}
?>