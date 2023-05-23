<?php



namespace Src\Orm\Crud;

use Exception;
use Src\Orm\Crud\CrudInterface;
use Src\Orm\DataMapper\DataMapper;
use Src\Orm\QueryBuilder\QueryBuilder;
use Src\Orm\DataMapper\DataMapperFactory;
use Src\Orm\QueryBuilder\QueryBuilderFactory;
use Src\Orm\DataBaseConnection\DataBaseConnection;

class CrudFactory 
{



    public function create(string $crudString,string $tableName,string $tableId){
        $dataMapperFactory   = new DataMapperFactory();
        $dataMapper          = $dataMapperFactory->create(DataBaseConnection::class,
        DataMapper::class);
        $queryBuilderFactory = new QueryBuilderFactory();
        $queryBuilder        = $queryBuilderFactory->create(QueryBuilder::class); 
        $crudObject          = new $crudString($queryBuilder,$dataMapper,$tableName,$tableId); 
        if(!$crudObject instanceof CrudInterface){
            throw new Exception($crudString . ' must implement the crudInterface');
        }
        return $crudObject;
    }



}