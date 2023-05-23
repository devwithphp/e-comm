<?php

use Dotenv\Dotenv;
use Src\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;




if(!function_exists('view') ){
    function view(string $path , array $params = [],$layout = 'main'){
        return (new View(ROOT_DIR))->view($path,$params,$layout);
    }   
}





if(!function_exists('env') ){
    function env(string $key ){
        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->load();
        return $_ENV[strtoupper($key)];
    }
}



if(!function_exists('request') ){
    function request(){
        return Request::createFromGlobals();
    }
}



function redirect(string $path){
    header('Location:'.  '/' . trim($path,'/'));
    exit;
}



function dump(mixed $data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}




function session(string $key =''){
    if(isset($key) && $key !== ""){
        return (new Session())->get($key);
    }
    return new session();

}



