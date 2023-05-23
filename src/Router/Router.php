<?php



namespace Src\Router;

use Src\Router\RouteTraits;
use Src\Router\Exception\RouterException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router {


    use RouteTraits;
    
   
    protected array $routes = [];



    protected string  $prefix = '';




    protected string $middleware = '';


    
    protected  $action;


    protected  ?MiddlewareStack  $middlewareStack  = null;

    
    
    protected  array $params = [];

    

    
    protected array $paramName = [] ;




    protected Request $request;




    protected Container $container;



    protected Response $response;



    protected string $extrat = '';



    protected string $controller = '';




    
    public function __construct(Request $request,Response $response,Container $container = null) {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
        if($this->request->headers->get('content-type') === 'application/json'){
            $this->extrat  = '/api';
        }
    }
    
    





    public function add(string $methods,string $path, mixed $action) : void {
        $path = (isset($this->prefix) && $this->prefix !== "")  ? 
        $this->extrat . $this->prefix . $path : $path;
        foreach(explode('|',$methods) as $method){
                $this->routes []= [
                    'uri'        =>  $path ?? '',
                    'method'     =>  $method,
                    'action'     =>  $action,
                    'controller' =>  $this->controller,
                    'prefix'     =>  $this->prefix,
                    'extra'      =>  $this->extrat,
                    'middleware' =>  [$this->middleware]
                ]; 
        }
    }
                    
        


  
    

    public function prefix(string $prefix) : self {
        $this->prefix = $prefix;
        return $this;
    }






    public function only($key) : self {
        array_push($this->routes[array_key_last($this->routes)]['middleware'],$key) ;
        return $this;
    }





    public function middleware($middleware) : self{
        $this->middleware = $middleware;
        return $this;
    }






    public function groupe(callable $groupes){
        $default = '';
        if(is_callable($groupes)){
            $this->middleware = $this->getMiddleware();
            $this->prefix     = $this->getPrefix();
            $this->controller = $this->getController();
            call_user_func($groupes,$this);
        } 
        $this->middleware = $default ;
        $this->prefix     = $default;
        $this->controller = $default;
        return $this;
    }



   
    
    
    
    public function controller(string $controller){
        $this->controller = $controller;
        return $this;
    }







    public function match() : bool {
        foreach($this->routes as $route){
            if($route['middleware'][0] === "App\\Middlewares\\TrilingSlashMiddleware"){
               $this->setMiddleware(["App\\Middlewares\\TrilingSlashMiddleware"])->process();
               unset($route['middleware'][0]);
            }
            $path = '#^' . preg_replace('/\/{(.*?)}/','/(.*?)',$route['uri']). '$#';
            preg_match($path,$this->request->getPathInfo(),$matches);
            if(!empty($matches) && $route['method'] === $this->request->getMethod()){
                unset($matches[0]);
                $this->setMiddleware($route['middleware']);
                $this->getParamesName($route['uri']);
                $this->params = array_combine(array_values($this->paramName),$matches);
                $this->action = $route['action'];
                $this->controller = $route['controller'];
                return true;
            }
          
        }
        return false;
    }
              
               
                
                
            
    public function handle(){
        if($this->match() && !empty($this->action)){
                if($this->middlewareStack !== null ){
                    $this->middlewareStack->process();
                }
                if(is_callable($this->action)){
                    return call_user_func($this->action,$this->params);
                }
                if(is_array($this->action)){
                    list($controller,$method) = $this->action;
                    if(class_exists($controller)){
                        if(!isset($this->container)){
                            $controller = new $controller;
                        }else{
                            $controller = $this->container->get($controller);
                        }
                        if(method_exists($controller,$method)){
                            return  call_user_func_array([$controller,$method],[$this->request,$this->response,$this->params]);
                        }
                    }
                }
                if(is_string($this->action)){
                    $controller = $this->container->get($this->controller);
                    return  call_user_func_array([$controller,$this->action],[$this->request,$this->response,$this->params]);
                }
        }
        throw new RouterException('404 : page not found');
    }
                            
        



    public function getParamesName($route) : array {
        foreach(explode('/',$route) as $value){
            if(!str_contains($value,'{') || !isset($value)){
                continue;
            }
            $this->paramName []= trim($value,'{}');
        }
        array_filter($this->paramName);
        return $this->paramName ;
    }




    public function getMiddleware() :string{
        return $this->middleware;
    }



    public function getPrefix() : string{
        return $this->prefix;
    }



    private function getController():string {
        return $this->controller;
    }



    public function getRoutes() : array {
        return $this->routes;
    }


    


    public function setMiddleware(array $middlewares){ 
        foreach($middlewares as $middleware ){
            $path = ROOT_DIR . '/app/Middlewares';
            $nameSpace = 'App\\Middlewares\\';
            foreach(glob($path .'/*.php') as $file){
                $midllewareClassName  = $nameSpace . pathinfo($file,PATHINFO_FILENAME);
                if($midllewareClassName === $middleware){
                    $this->middlewareStack =  new MiddlewareStack($this->container->get($middleware),$this->request);
                }
            }
        }
        return $this->middlewareStack;
    }




    


}


    

    