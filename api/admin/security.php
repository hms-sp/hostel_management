<?php
session_start();

class security{

   static $userId;

   static $sessionId;

   static $token;

   static $roles;

   static $userData;

   static $userIdColumn;


   public static function setUser($userId,$password,$userClass="admin",$userTable="admins",$userIdColumn="username")
   {
    global $conn;
    $userRepo=new repository($userClass,$userTable,$userIdColumn,$conn);

    self::$userData=$userRepo->fetch(["$userIdColumn" => $userId])['data'];

    if(isset(self::$userData->password) && self::$userData->password == $password){
       self::$userIdColumn=$userIdColumn;
       $_SESSION[self::$userIdColumn]=$userId;
       self::$userId=$userId;
       self::$sessionId=session_id();
       if(isset(self::$userData->roles)){
           self::$roles=explode(',',self::$userData->roles);
       }
       return true;
    }
    return false;

   }


   public static function getRoles(){
       
        $userId=$_SESSION[self::$userIdColumn];
        if($userId===NULL || !isset($_SESSION[self::$userIdColumn])){
        return NULL;
        }
        return self::$roles;
   }

   public static function getCurrentUser(){
     
        $userId=$_SESSION[self::$userIdColumn];
        if($userId===NULL || !isset($_SESSION[self::$userIdColumn])){
            return NULL;
        }
        return self::$userId;
   }

   public static function getCurrentUserData(){
     
        $userId=$_SESSION[self::$userIdColumn];
        if($userId===NULL || !isset($_SESSION[self::$userIdColumn])){
        return NULL;
        }
        return self::$userData;
   }

   public static function logout(){
     
    $_SESSION[self::$userIdColumn]=NULL;
    self::$userId=NULL;
    self::$sessionId=NULL;
    self::$userData=NULL;
    self::$roles=NULL;
    session_destroy();
    return [];
   }

}