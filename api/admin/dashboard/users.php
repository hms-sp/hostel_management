<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../include.php');

$Class = 'user';
$Table = 'users';
$PK = 'user';

$hostelRepo=new repository($Class,$Table,$PK,$conn);
$userRepo=new repository("user","users","username",$conn);

$input_data = file_get_contents("php://input");

$request = json_decode($input_data, true);

if ($request === null) {
    // JSON decoding failed
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON', 'request'=>$request]);
    exit;
}


if ($request['type'] == 'all') {

  getAll();
  
}
else{
   
}

function getAll(){

    global $request,$hostel,$Repo,$userRepo;
    
    $data = $userRepo->fetch(["hostel" => $hostel])['data'];

    $log = ob_get_clean();
    $data = ['isSuccessfull' => true , 'status' => 'login successfull', 'request'=>json_encode($request),'log'=>$log];
    echo json_encode($data);
    exit;

}


?>