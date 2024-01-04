<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('allow_url_fopen',1);

include_once('../include.php');
include_once('../auth.php');


$input_data = file_get_contents("php://input");

$request = json_decode($input_data, true);

if ($request === null || !isset($request['route'])) {
    // JSON decoding failed
    http_response_code(400);
    $log = ob_get_clean();
    echo json_encode(['error' => 'Invalid JSON', 'request'=>$request,'log'=>$log]);
    exit;
}

$route = $request['route'];


$routes = [

    "getUsersByHostelId" =>[
        "requestType" => "GET",
        "request" => [
            ["name"=>"id","type"=>"integer"]
        ],
        "response" => [
            "class"=>"users",
            "filter"=> ["id" => $request['id']],
            "condition" => "req.name == 'bhaskar'",
            "multiple"=>true,
        ], 
    ]

];

$repos = [
    "users" => $userRepo
];

if (!isset($routes[$route])) {
    // JSON decoding failed
    http_response_code(400);
    $log = ob_get_clean();
    echo json_encode(['error' => 'Invalid route', 'request'=>$request,'log'=>$log]);
    exit;
}


$data = $routes[$route];

$requestType = $data['requestType'];


if($requestType == 'GET'){

    $requestFormat = $data['request'];

    //request validations
    if(!validateRequest($request,$requestFormat)){

        $log = ob_get_clean();
        $data = ['isSuccessfull' => false , 'status' => 'invalid request', 'data'=>json_encode($data),'log'=>$log];
        echo json_encode($data);
        exit;

    }

    //return data
    fetchResult($data['response'],$filter);

    // //format filter

    // $filter = $responseFormat['filter'];

    // foreach($filter as $key=>$value){

    //     $formattedValue = 1;
    //     $filter[$key] = $formattedValue;

    // }

    
}



function fetchResult($responseFormat,$filter){

    global $repos;

    $repo = $repos[$responseFormat['class']];

    if($responseFormat['multiple']){
        $data = $repo->fetchAll($filter)['data'];
    }
    else{
        $data = $repo->fetch($filter)['data'];
    }

    $log = ob_get_clean();
    $data = ['isSuccessfull' => true , 'status' => 'success', 'data'=>json_encode($data),'log'=>$log];
    echo json_encode($data);
    exit;
}
  
function validateRequest($requestData,$requestFormat) {

    foreach ($requestFormat as $field) {
        // Check if the field is present in the request data
        if (!isset($requestData[$field['name']])) {
            return false;
        }

        // Check if the data type matches the expected type
        // $expectedType = $field['type'];

        // $actualType = gettype($requestData[$field['name']]);

        // if ($expectedType !== $actualType) {
        //     return false;
        // }
    }

    return true;
}


?>