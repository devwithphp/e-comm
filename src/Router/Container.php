<?php



namespace Src\Router;

use ReflectionClass;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Src\Router\Exception\ContainerException;




class Container 
{

    
    
    protected array $entries = [];


    
    
    public function __construct()
    {}


    
    
    public function has(string $key) : bool {
        return isset($this->entries[$key]);
    }
    



    
    public function get($key){
        if(!$this->has($key)){
            return $this->resolve($key);
        }
        $entry =  $this->entries[$key];
        return $entry($this);
    }
    
    


    
    public function set($key,callable $value){
        $this->entries[$key] = $value;
    }
   



    public function resolve($id){

        $reflectionClass = new ReflectionClass($id);
        if(!$reflectionClass->isInstantiable()){
            throw new ContainerException(sprintf('Cotainer failed to resolve %s  
            please check if this class is instantiable', $id));
        }
        $contructor  = $reflectionClass->getConstructor();
        if(!$contructor){
            return $reflectionClass->newInstance();
        }     
        $parameters = $contructor->getParameters();
        if(!$parameters){
            return $reflectionClass->newInstance();
        }


        $dependecies = array_map(function( ReflectionParameter $param)use($id){
            $name = $param->getName();
            $type = $param->getType();

            if(!$type){
                throw new ContainerException('failed to resolve ' . $id . ' becouse params ' . $name .
             ' is missing ');
            }

            if( $type  instanceof ReflectionUnionType){
                throw new ContainerException('failed to resolve ' . $id . ' becouse params ' . $name .
                ' is  a union type ');
            }


            if($type instanceof ReflectionType && !$type->isBuiltin()){
                return $this->get($type->getName());
            }

            throw new ContainerException('failed to resolve ' . $id . ' becouse params ' . $name .
            ' is missing ');

        },$parameters);

        return $reflectionClass->newInstanceArgs($dependecies);
    }




    
}