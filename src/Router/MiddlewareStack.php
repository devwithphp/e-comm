<?php



namespace Src\Router;

use Symfony\Component\HttpFoundation\Request;

class MiddlewareStack {


    
    
    protected array  $middlewaresStack = [];
    

    protected int $index = 0;


    protected Request  $request;

    
    
    public function __construct($middlewares,$request){
        $this->middlewaresStack [] = $middlewares;   
        $this->request = $request; 
    }

    
    
    public function process(){
        $middleware = ($this->getMiddleware() !== null) ? $this->getMiddleware() : null;
        $this->index++;
        if(null !== $middleware){
            return $middleware->handle($this->request);
        }
    }
        
        


    
    public function getMiddleware(){
        if(!empty($this->middlewaresStack[$this->index])){
            return $this->middlewaresStack[$this->index];
        }
    }






}