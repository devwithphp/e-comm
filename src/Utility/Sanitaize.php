<?php


namespace Src\Utility;



use InvalidArgumentException;

class Sanitaize {




    private const FILTERS = [
        'string' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email'  => FILTER_SANITIZE_EMAIL,
        'int'    => FILTER_SANITIZE_NUMBER_INT,
        'float'  => FILTER_SANITIZE_NUMBER_FLOAT,
        'url'    => FILTER_SANITIZE_URL  
    ];


    private static  array $cleanData = [];

    
  

    public static function clean(array $data){
        if(!is_array($data)){
            throw new InvalidArgumentException('please provide the invalid credentials');
        }
        foreach($data as $key => $value ){
            if(is_string($value)){
                self::$cleanData[$key] = filter_var(trim($value),self::FILTERS['string']);
            }
            if($key === 'email'){
                self::$cleanData[$key] = filter_var($value,self::FILTERS['email']);
            }
            if(is_int($value)){
                self::$cleanData[$key] = filter_var($value,self::FILTERS['int']);
            }
            if(is_float($value)){
                self::$cleanData[$key] = filter_var($value,self::FILTERS['float']);
            }
        }
        return self::$cleanData;
    }



}