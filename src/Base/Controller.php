<?php


namespace Src\Base;

use Src\Router\MiddlewareStack;
use Symfony\Component\HttpFoundation\Request;

class Controller  extends BaseController{


    

 /*    public function before(){
        $request = Request::createFromGlobals();
        $path = ROOT_DIR . '/app/Middlewares';
        $nameSpace = 'App\\Middlewares\\';
        foreach(glob($path .'/*.php') as $file){
            $midllewareClassName  = $nameSpace . pathinfo($file,PATHINFO_FILENAME);
                //(new MiddlewareStack());
        }
    }
 */


    public function after(){

    }


    




    
}