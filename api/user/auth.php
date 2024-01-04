<?php
$user = security::getCurrentUser();
$roles = security::getRoles();
if(!$user){
    echo "session not found";
}
else if(!in_array("user",$roles)){
    echo "authorization failed .";
    exit;
}
else{
    echo "login success";
    
}
?>