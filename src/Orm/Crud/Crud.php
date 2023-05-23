<?php



namespace Src\Orm\Crud;

use PDO;
use Exception;
use Src\Orm\Crud\CrudInterface;
use Src\Orm\DataMapper\DataMapper;
use Src\Orm\QueryBuilder\QueryBuilder;

class Crud  implements CrudInterface {


    private string $tableName ;


    
    private string $tableID ;

    
    
    
    private QueryBuilder $queryBuilder;


    
    
    private DataMapper  $dataMapper;




    public function __construct(QueryBuilder $queryBuilder, DataMapper $dataMapper ,string  $tableName , string $tableID){   
        $this->queryBuilder = $queryBuilder;
        $this->tableName    = $tableName;
        $this->tableID      = $tableID;
        $this->dataMapper = $dataMapper;
    }



    
    public function create(array $fields = [])  : bool {
        try{
            $sql = $this->queryBuilder->fields($fields)
                            ->table($this->tableName)
                            ->primaryKey($this->tableID) 
                            ->insert();    
            $this->dataMapper->presist($sql,$fields);
            if($this->dataMapper->numRows() === 1){
                return true; 
            }   
        }catch(Exception $error){
            throw $error;
        }                 
    }




    public function read(array $selectors=[],array $conditions = [],array $optional = [],$limit =10,$fetchMode =PDO::FETCH_ASSOC ){
        try{
            $sql = $this->queryBuilder->selectors($selectors)
                            ->limit($limit)
                            ->table($this->tableName)
                            ->primaryKey($this->tableID)
                            ->conditions($conditions) 
                            ->select();    
            $this->dataMapper->presist($sql,$conditions);
            if($this->dataMapper->numRows() > 0){
                return $this->dataMapper->setfetchMode($fetchMode)->results();
            } 
        }catch(Exception $error){
            throw $error;
        }   
    }




    public function findOne(array $selectors=[],array $conditions = [],array $optional = [],$fetchMode =PDO::FETCH_ASSOC){
        try{
            $sql = $this->queryBuilder->selectors($selectors)
                            ->table($this->tableName)
                            ->primaryKey($this->tableID)
                            ->conditions($conditions) 
                            ->select();    
            $this->dataMapper->presist($sql,$conditions);
            if($this->dataMapper->numRows() > 0){
                return $this->dataMapper->setfetchMode($fetchMode)->result();
            } 
        }catch(Exception $error){
            throw $error;
        }   
    }






    
    public function update(array $fields = [],$where =[],$limit =1) : bool{
        try{
            $sql = $this->queryBuilder->fields($fields)
                            ->table($this->tableName)
                            ->primaryKey($this->tableID) 
                            ->where($where)
                            ->limit($limit)
                            ->update();    
            $this->dataMapper->presist($sql,$this->dataMapper->buildQUeryParams($fields,$where));
            if($this->dataMapper->numRows() === 1){
                return true; 
            }   
        }catch(Exception $error){
            throw $error;
        }                 
    }





    public function delete(array $conditions = [],$limit = 1) : bool {
        try{
            $sql = $this->queryBuilder->table($this->tableName)
                                      ->primaryKey($this->tableID) 
                                      ->conditions($conditions)
                                      ->limit($limit)
                                      ->delete();    
            $this->dataMapper->presist($sql,$conditions);
            if($this->dataMapper->numRows() === 1){
                return true; 
            }
        }catch(Exception $error){
            throw $error;
        }
    }





    public function getQueryBuilder(){
        return $this->queryBuilder;
    }



    public function getDataMapper(){
        return $this->dataMapper;
    }

}



