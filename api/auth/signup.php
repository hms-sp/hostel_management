<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../helpers/security.php');

$required_fields = ['username', 'password' , 'name'];

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

    global $request;

    if(security::setUser($userName,$password)){
        $log = ob_get_clean();
        $data = ['isSuccessfull' => true , 'status' => 'signup successfull', 'request'=>json_encode($request),'log'=>$log];
        echo json_encode($data);
        exit;
    }
    else{
        $log = ob_get_clean();
        $data = ['isSuccessfull' => false , 'status' => 'incorrect username or password', 'request'=>json_encode($request),'log'=>$log];
        echo json_encode($data);
        exit;
    }

}

?>