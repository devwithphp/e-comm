<?php


namespace Src\Orm\QueryBuilder;

use Exception;

class QueryBuilderFactory
{


    public function create(string $querybuilderClass){
        if(class_exists($querybuilderClass)){
            return new $querybuilderClass();
        }
        throw new Exception($querybuilderClass . ' is not found');
    }




}