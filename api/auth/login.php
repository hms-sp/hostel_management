<?php

include_once('helpers/db.php');

$required_fields = ['userName', 'password'];

$validated = true;

$request = $_REQUEST;

foreach($required_fields as $required_field){

    if(!isset($request[$required_field]) || trim($request[$required_field])==""){
        $validated = false;
    }

}

if ($validated) {

  $userName = trim($request['userName']);
  $password = trim($request['password']);

  login($userName, $password);
  
}
else{
    $data = ['isSuccessfull' => false , 'status' => 'please enter username and password'];
    echo json_encode($data);
    // redirect($dashboardUrl,['status' => 'please enter username and password']);
}


function login($userName,$password){

    global $conn,$loginUrl,$dashboardUrl;

    $query = mysqli_query($conn,"SELECT password FROM `userData` WHERE `userName`='$userName'");
    $data=mysqli_fetch_array($query);

    if ($data && isset($data['password']) && $data['password'] == $password){

        $_SESSION['session_id']=session_id();
        $_SESSION['userName']=$userName;

        $data = ['isSuccessfull' => true , 'status' => 'please enter username and password'];
        echo json_encode($data);
        // redirect($dashboardUrl,['status' => 'Incorrect username or password']);
    }
    else{
        $data = ['isSuccessfull' => false , 'status' => 'please enter username and password'];
        echo json_encode($data);
        // redirect($loginUrl,['status' => 'Incorrect username or password']);
    }

}

?>