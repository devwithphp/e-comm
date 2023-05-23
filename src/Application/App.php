<?php



namespace Src\Application;


use Src\Router\Router;

use Src\Router\Exception\RouterException;

class App {


    
    protected Router $router;


    private string $rootDir = '';



    

    public function __construct(string $root, Router $router) {
        $this->rootDir = $root;
        $this->router = $router;
    }


    public function run(){
        try{
           
            $this->constants();
            echo $this->router->handle();
            return $this;
        }catch(RouterException $e){
            echo view('404error/404',[],'auth');
        }

    }




    public function router() : Router {
        return $this->router;
    }




    public  function constants() : void {
        define('ROOT_DIR',$this->rootDir);
    }



   
    
}