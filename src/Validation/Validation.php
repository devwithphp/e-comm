<?php




namespace Src\Validation;

use Exception;
use Src\Utility\Sanitaize;
use Src\Orm\DataBaseConnection\DataBaseConnection;

class Validation 
{

    
    private static array $data = [];

    
    
    private static $length;


    private static array $errorMessages = [];






    protected const PATTERNS = [
        'email'        =>  '/^([a-zA-Z\d\.-]+)@([a-z\d-]+)\.([a-z]{2,8})(\.[a-z]{2,8})?$/',
        'password'     =>  '/^([a-zA-Z\d\._]{8,24})+$/',
        'phone_number' =>  '/^[0-9+]{10}$/',
        'string'       => '/^[a-zA-Z+]{255}$/',

    ];


    public  function __construct()
    {}

    
    public static function make(array $params , $data) : array {
        self::setAndClean($data);
        foreach($params as $key => $rules){
            if(static::key($key)){
                if(is_array($rules)){
                    foreach($rules as $rule){
                        if(strpos($rule,'|')){
                            list($method,$param) = explode('|',$rule);
                            call_user_func(get_class(). '::' . $method,$key,$param);    
                        }else if(strpos($rule,':')){
                            list($method,self::$length) = explode(':',$rule);
                            call_user_func(get_class(). '::' . $method,$key);  
                        }else{
                            call_user_func(get_class(). '::' . $rule,$key);
                        }
                            
                    }
                }
            }    
        }
        session()->getFlashBag()->add('errors',static::$errorMessages);
        return static::$data;
    }
                            



   
          

    public  static function setAndClean(array $data) : void {
        static::$data = Sanitaize::clean($data);
    }
         



    public static function string($key) : void {
        if(!is_string(self::$data[$key])){
            $key = self::label($key);
            self::$errorMessages[$key] = $key . ' must be string';
        }
    }
  


    public static function required($key) :   void {
        if(empty(self::$data[$key])&& self::$data[$key] ===  ""){
            $key = self::label($key);
            self::$errorMessages[$key] = 'field ' .  $key  . ' is required';  
        }
    }
    


    public static function email(string $key) : void {
        if(!preg_match(self::PATTERNS['email'],self::$data[$key])){
            $key = self::label($key);
            self::$errorMessages[$key] = 'field ' . $key  . ' is  incorrect ';  
        }
    }


    
    public static  function unique($key,$table) : void {
        $stmt = self::getDB()->open()->prepare('SELECT * FROM ' . $table
        . ' WHERE ' . $key . ' =:' . $key);
        $stmt->execute([$key => self::$data[$key]]);
        $user = $stmt->fetch();
        if(!empty($user)){
            self::$errorMessages[$key] = ' a user with this email already exists';  
        }
    }



    public static function confirmed(string $key): void {
        if(self::key($key . '_confirmation')){
            if(self::$data[$key]  !== self::$data[$key . '_confirmation'] ){
                $key = self::label($key);
                self::$errorMessages[$key] = 'field ' .  $key  . ' unmatched  with  ' . $key . '_confirmation';  
            }
            static::unsetField($key . '_confirmation');
        }
    }






    public static  function min($key){
        if(strlen(self::$data[$key]) < self::$length){
            $key = self::label($key);
            self::$errorMessages[$key] = 'field ' .  $key  . ' length must be  ' . self::$length . ' chars and +';  
        }
    }

    

   


   public static function key($key) : bool {
        if(empty(self::$data[$key])){
            $key = self::label($key);
            self::$errorMessages[$key] = 'field ' . $key . ' is required';
        }
        return true;
   }



   
    public static function unsetField($key) : void {
        unset(self::$data[$key]);
    } 



    public static function getErrors(){
        return self::$errorMessages;
    }




    /**
     * 
    */
    public static  function label(string $key){
        if(strpos($key,'_')){
            $key = str_replace('_',' ',$key);
        }
        return $key;
    }



    public static function getDB(){    
        return  DataBaseConnection::getDb();
    }
    


    public static function setError($key,$errorMessages){
        self::$errorMessages[$key]  = $errorMessages;
    }


    public static function hasError(): bool {
        return empty(self::$errorMessages);
    }




    public static function validated(){
        return  static::$data;
    }



}