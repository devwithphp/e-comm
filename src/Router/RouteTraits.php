<?php


namespace Src\Router;


trait RouteTraits {


    public function get(string $path, $action) {
       $this->add('GET', $path, $action);
       return $this;
    }



    public function any(string $path, mixed $action){
        $this->add('GET|POST', $path, $action);
        return $this;
    }


    public function post(string $path, mixed $action){
       $this->add('POST', $path, $action);
       return $this;
    }



    public function put(string $path, mixed $action){
        $this->add('PUT', $path, $action);
        return $this;
    }


    
    
    public function delete(string $path, mixed $action){
        $this->add('DELETE', $path, $action);
        return $this;
    }



}