<?php



namespace Src\Base;

use BadMethodCallException;
use Symfony\Component\HttpFoundation\Request;

class BaseController {




    public function __call($name, $arguments){
        $method = $name; 
        if(method_exists($this,$method)){
            if($this->before() !== null){
                call_user_func_array([$this,$method],$arguments);
                $this->after();
            }
        }else{
            throw new BadMethodCallException('Method doesnt exist in this class ' .
            get_class($this));
        }
    }


    public function before(){
        
    }


    public function after(){}




}