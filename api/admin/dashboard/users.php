<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../include.php');
include_once('../auth.php');

$input_data = file_get_contents("php://input");

$request = json_decode($input_data, true);

if ($request === null) {
    // JSON decoding failed
    http_response_code(400);
    $log = ob_get_clean();
    echo json_encode(['error' => 'Invalid JSON', 'request'=>$request,'log'=>$log]);
    exit;
}


if ($request['type'] == 'all') {

  getAll();
  
}
else{
   
}

function getAll(){

  global $request,$hostel,$Repo,$userRepo;

  $hostels = security::getHostels();

  $idArray = array_map(function ($hostel) {
    
    return $hostel->id ?? null;
  }, $hostels);
  
  if(isset($request['hostel']) && in_array($request['hostel'], $idArray)){

    $hostel = $request['hostel'];
    
  }

  $data = $userRepo->fetchAll(["hostel" => $hostel])['data'];

  $log = ob_get_clean();
  $data = ['isSuccessfull' => true , 'status' => 'success', 'data'=>json_encode($data),'log'=>$log];
  echo json_encode($data);
  exit;

}


?>