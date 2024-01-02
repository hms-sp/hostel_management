<?php
session_start();

class security{

   static $userId;

   static $sessionId;

   static $token;

   static $roles;

   static $userData;

   static $userIdColumn = "username";


   public static function setUser($userId,$password,$userClass="admin",$userTable="admins",$userIdColumn="username")
   {
    global $conn;
    $userRepo=new repository($userClass,$userTable,$userIdColumn,$conn);

    self::$userData=$userRepo->fetch(["$userIdColumn" => $userId])['data'];

    if(isset(self::$userData->password) && self::$userData->password == $password){
       self::$userIdColumn = $userIdColumn;
       $_SESSION[self::$userIdColumn] = $userId;
       $_SESSION["role"] = "admin";
       $_SESSION["userData"] = json_encode(self::$userData);

       self::$userId = $userId;
       self::$sessionId = session_id();
       if(isset(self::$userData->roles)){
           self::$roles=explode(',',self::$userData->roles);
       }
       return true;
    }
    return false;

   }


   public static function getRoles(){
       
        if(!isset($_SESSION['role'])){
            return NULL;
        }
        return [$_SESSION['role']];
   }

   public static function getCurrentUser(){
     
        if(!isset($_SESSION[self::$userIdColumn])){
            return NULL;
        }
        return $_SESSION[self::$userIdColumn];
   }

   public static function getCurrentUserData(){
     
        if(!isset($_SESSION[self::$userIdColumn])){
            return NULL;
        }
        return $_SESSION["userData"];
   }

   public static function setHostels($hostels){
     
        $_SESSION["hostels"] = $hostels;
    
    }

    public static function getHostels(){
    
        if(!isset($_SESSION["hostels"])){
            return NULL;
        }
        return $_SESSION["hostels"];
    }

   public static function logout(){
     
    $_SESSION[self::$userIdColumn] = NULL;
    $_SESSION["userData"] = NULL;
    $_SESSION["role"] = NULL;

    self::$userId=NULL;
    self::$sessionId=NULL;
    self::$userData=NULL;
    self::$roles=NULL;
    session_destroy();
    return [];
   }

}