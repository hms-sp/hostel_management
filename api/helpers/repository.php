<?php
$folderPath = '../models/';

$files = glob($folderPath . '*.php');

foreach ($files as $file) {
    echo "<br> included - $file";
    include_once $file;
}

class repository{

    public $className;
    public $tableName;
    public $pk;
    public $conn;
    public function __construct($className,$tableName,$pk,$conn)
    {
        $this->className=$className;
        $this->tableName=$tableName;
        $this->pk=$pk;
        $this->conn=$conn;
    }

    // method to fetch all 
    public function fetchAll($filter,$sort="",$sortType="ASC",$max=500,$advFilter=[]){

        $sort=$sort==""?$this->pk:$sort;
        $query="select * from $this->tableName";

        if(gettype($filter)==gettype(['array']) and count($filter)!=0){
            $query.=" where ";
            foreach($filter as $column => $value){
                $query.= "$column = $value and";
            }
            $query = substr($query,0,strlen($query)-4);
        }

        else if(gettype($filter)==gettype('string') and strlen($filter)!=0){
           
             $query.= "where $filter";
        }
        else{
            return ["isSuccessfull" => false , "msg" => "invalid filter" , "data" =>NULL];
        }   

        $query.= "order by $sort $sortType limit $max ";
        $query=mysqli_query($this->conn,$query);
        if(!$query){
            return ["isSuccessfull" => false , "msg" => "invalid query" , "data" =>NULL];
      
        }
        $data=mysqli_fetch_all($query);
        $final=[];
        foreach($data as $d){
            $classInstance = new $this->className;
            foreach ($d as $key => $value) {
                if (!property_exists($classInstance, $key)) continue;
    
                $classInstance->{$key} = $value;
            }
    
            $final[]= $classInstance;
        }
        return ["isSuccessfull" => true , "msg" => "data success" , "data" =>$final ];


    }


     //method to fetch single object in reference to other fields
     public function fetch($filter="",$expression=""){

        $query="select * from $this->tableName";
        

        if($filter){
            if(gettype($filter)!=gettype(['array'])){
                $id=$filter;
                $filter=[$this->pk => $id];
            }
            $query.=" where ";
            foreach($filter as $column => $value){
                $query.= "`$column`='$value' and";  
            }
            $query = substr($query,0,strlen($query)-4);
        }
        else if(strlen($expression)!=0){

            $query.="where $expression";

        }
        else{
            return ["isSuccessfull" => false , "msg" => "invalid arguments" , "data" =>NULL];
        }
        echo "<br> query - $query";
        $query=mysqli_query($this->conn,$query);
        
        if(!$query){
            echo "<br> query failed - $query";
            return ["isSuccessfull" => false , "msg" => "invalid query" , "data" =>NULL];
      
        }
        $data=mysqli_fetch_array($query);
        
        echo "<br> data - ".json_encode($data);
        $classInstance = new $this->className;
        foreach ($data as $key => $value) {
            if (!property_exists($classInstance, $key)) continue;

            $classInstance->{$key} = $value;
        }

        
        return ["isSuccessfull" => true , "msg" => "success" , "data" =>$classInstance];


    }

     //method to save single object in reference to other fields
     public function save($data){

        $data=gettype($data)=="object"?json_decode(json_encode($data)):$data;

        $exsistingData=$this->fetch($data->{$this->pk});

        if($exsistingData['data']!==NULL){

            return ["isSuccessfull" => false , "msg" => "data already exists" , "data" => NULL ];
        }

        $leftQuery="insert into $this->tableName (";

        $rightQuery="values (";

        $classInstance = new $this->className;
        foreach ($data as $key => $value) {
            if (!property_exists($classInstance, $key)) continue;

            $classInstance->{$key} = $value;
            $leftQuery.= "`$key`,";
            $rightQuery.="'$value',";
        }
        $leftQuery=substr($leftQuery,strlen($leftQuery)-1);
        $leftQuery.=") ";
        $rightQuery=substr($rightQuery,strlen($rightQuery)-1);
        $rightQuery.=")";
        if(mysqli_query($this->conn,$leftQuery.$rightQuery)){

            return ["isSuccessfull" => true , "msg" => "success" , "data" => $classInstance ];
        }
        return ["isSuccessfull" => false ,  "msg" => "error while saving" , "data" => $classInstance ];


    }

     //method to update single object in reference to other fields
     public function update($data,$filter="",$expression=""){

        $exsistingData=$this->fetch($data->{$this->pk});

        if($exsistingData['data']===NULL){

            return ["isSuccessfull" => false , "msg" => "data doesnt exists" , "data" => NULL ];
        }

        $leftQuery="update $this->tableName set ";

        
        $rightQuery="";
        if($filter){
            if(gettype($filter)!=gettype(['array'])){
                $id=$filter;
                $filter=[$this->pk => $id];
            }
            $rightQuery=" where ";
            foreach($filter as $column => $value){
                $rightQuery="`$column`='$value' and";  
            }
            $rightQuery=substr($rightQuery,strlen($rightQuery)-4);
        }
        else if($expression!=0){

            $rightQuery.=" where $expression";

        }
        else{
            $rightQuery.=" where `$this->pk` = '$data->{$this->pk}' ";
        }
        $data=mysqli_fetch_all(mysqli_query($this->conn,"select * from $this->tableName $rightQuery"));
    
        if(count($data)>1){

            return ["isSuccessfull" => false , "msg" => "cannot update many" , "data" => NULL ];
        }

        $classInstance = new $this->className;
        foreach ($data as $key => $value) {
            if (!property_exists($classInstance, $key)) continue;

            if($exsistingData['data'][$key]==$value) continue;

            $classInstance->{$key} = $value;
            
            $leftQuery.= "SET `$key` = '$value' ,";
        }
        $leftQuery=substr($leftQuery,strlen($leftQuery)-1);
        
        $query=mysqli_query($this->conn,$leftQuery.$rightQuery);
        if(!$query){
            return ["isSuccessfull" => false , "msg" => "invalid query" , "data" =>NULL];
      
        }
        return ["isSuccessfull" => true , "msg" => "success" , "data" =>$classInstance];
     
    }

     // method for aggregate functions 
     public function aggregate($type="sum",$column="",$expression="",$filter=[]){

        $column=$column==""?$this->pk:$column;
        $query="select $type(`$column`) as result from $this->tableName";

        if(count($filter)!=0){
            $query.=" where ";
            foreach($filter as $column => $value){
                $query.="$column = $value and";
            }
            $query=substr($query,strlen($query)-4);
        }

        else if(strlen($expression)!=0){
           
             $query.="where $expression";
        }
        else{
            return ["isSuccessfull" => false , "msg" => "invalid filter or expression" , "data" =>NULL];
        }   

        $data = mysqli_fetch_array(mysqli_query($this->conn,$query));
        
        $result = $data['result']===NULL || $data['result']===0 ?0:$data['result'];

        return ["isSuccessfull" => true , "msg" => "success" , "data" =>$result ];


    }


     //  //method to fetch Multiple object in reference to other fields
    //  public function fetchByColumn($filter){

    //     foreach($filter as $column => $value){
    //         $query="select * from $this->tableName where `$column`='$value'";
    //         break;
    //     }
    //     $data=mysqli_fetch_array(mysqli_query($this->conn,$query));
    
    //     $classInstance = new $this->className;
    //     foreach ($data as $key => $value) {
    //         if (!property_exists($classInstance, $key)) continue;

    //         $classInstance->{$key} = $value;
    //     }

        
    //     return $classInstance;


    // }




}



?>