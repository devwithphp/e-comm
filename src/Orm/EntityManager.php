<?php


namespace Src\Orm;

use Src\Orm\Crud\Crud;
use Src\Orm\Crud\CrudFactory;
use Src\Orm\Crud\CrudInterface;

class EntityManager {


    private CrudInterface $crud;



    public  function initiliaze($tableName ,$tableId) : void {
        $crudFactory = new CrudFactory();
        $this->crud  = $crudFactory->create(Crud::class,$tableName,$tableId);
    }



    public function getCrud(string $tableName,string $tableId) : CrudInterface {
        $this->initiliaze($tableName,$tableId);
        return $this->crud;
    }



}