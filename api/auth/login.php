<?php


$required_fields = ['username', 'password'];

$validated = true;

$request = $_REQUEST;

foreach($required_fields as $required_field){

    if(!isset($request[$required_field]) || trim($request[$required_field])==""){
        $validated = false;
    }

}

if ($validated) {

  $userName = trim($request['username']);
  $password = trim($request['password']);

  login($userName, $password);
  
}
else{
    $data = ['isSuccessfull' => false , 'status' => 'please enter username and password', 'request'=>json_encode($request)];
    echo json_encode($data);
    // redirect($dashboardUrl,['status' => 'please enter username and password.']);
}


function login($userName,$password){

    global $request;

    if(security::setUser($userName,$password)){

        $data = ['isSuccessfull' => true , 'status' => 'login successfull', 'request'=>json_encode($request)];
        echo json_encode($data);
        // redirect($dashboardUrl,['status' => 'Incorrect username or password']);
    }
    else{
        $data = ['isSuccessfull' => false , 'status' => 'incorrect username or password', 'request'=>json_encode($request)];
        echo json_encode($data);
        // redirect($loginUrl,['status' => 'Incorrect username or password']);
    }

}

?>