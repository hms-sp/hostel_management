<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../include.php');


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
else if ($request['type'] == 'update'){
   
}
else{

  get();
   
}

function getAll(){

  global $request,$hostel,$Repo,$hostelRepo,$admin;
  
  if(isset($request['admin'])){
    $admin = $request['admin'];
  }

  $data = $hostelRepo->fetchAll(["admin" => $admin])['data'];

  $log = ob_get_clean();
  $data = ['isSuccessfull' => true , 'status' => 'success', 'data'=>json_encode($data),'log'=>$log];
  echo json_encode($data);
  exit;

}

function get(){

    global $request,$hostel,$Repo,$hostelRepo,$admin;
    
    if(isset($request['admin'])){
      $admin = $request['admin'];
    }
    if(isset($request['hostel'])){
        $hostel = $request['hostel'];
        $data = $hostelRepo->fetch(["hostel" => $hostel])['data'];
    }
    else{
        $data = $hostelRepo->fetch(["admin" => $admin])['data'];    
    }
    $log = ob_get_clean();
    $data = ['isSuccessfull' => true , 'status' => 'success', 'data'=>json_encode($data),'log'=>$log];
    echo json_encode($data);
    exit;
  
  }


?>