<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../helpers/db.php');
include_once('../models/user.php');

$required_fields = ['username', 'password' , 'name'];
$unique_fields = ['username'];

$validated = true;

$input_data = file_get_contents("php://input");

$request = json_decode($input_data, true);

if ($request === null) {
    // JSON decoding failed
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON', 'request'=>$request]);
    exit;
}


foreach($required_fields as $required_field){

    if(!isset($request[$required_field]) || trim($request[$required_field])==""){
        $validated = false;
    }

}

if ($validated) {

  $userName = trim($request['username']);
  $password = trim($request['password']);
  $name = trim($request['name']);

  signup($userName, $password);
  
}
else{
    $data = ['isSuccessfull' => false , 'status' => 'please enter username and password', 'request'=>json_encode($request)];
    echo json_encode($data);
    exit;
}


function signup($userName,$password){

    global $request,$unique_fields,$conn;

    $classInstance = new user();
    foreach($_REQUEST as $key=>$input){

        if (!property_exists($classInstance, $key)) continue;
        
        $classInstance->{$key} = $input;
    }

    $uniqueFilter=[];
    foreach($unique_fields as $unique_field){
        $uniqueFilter[$unique_field] = $classInstance->{$unique_field};
    }


    $userRepo=new repository('user','users','username',$conn);
    $existingData=$userRepo->fetchAll($uniqueFilter)['data'];

    if($existingData){
        $log = ob_get_clean();
        $data = ['isSuccessfull' => false , 'status' => 'username already exists', 'request'=>json_encode($request),'log'=>$log];
        echo json_encode($data);
        exit;
    }

    if($userRepo->save($classInstance)['isSuccessfull']){
        $log = ob_get_clean();
        $data = ['isSuccessfull' => true , 'status' => 'signup success', 'request'=>json_encode($request),'log'=>$log];
        echo json_encode($data);
        exit;
    }
    else{
        $log = ob_get_clean();
        $data = ['isSuccessfull' => false , 'status' => 'something went wrong', 'request'=>json_encode($request),'log'=>$log];
        echo json_encode($data);
        exit;
    }

}

?>