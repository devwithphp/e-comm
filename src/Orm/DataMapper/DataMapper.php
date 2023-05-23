<?php


namespace Src\Orm\DataMapper;

use PDO;
use Throwable;
use PDOStatement;
use Src\Orm\DataBaseConnection\DataBaseConnection;
use Src\Orm\DataBaseConnection\DataBaseConnectionInterface;

class DataMapper 
{


    private  DataBaseConnection $dbh;


    private PDOStatement $stmt ;



    private   $defaultFetchMOde = PDO::FETCH_ASSOC;





    public function __construct(DataBaseConnectionInterface $dbh){
        $this->dbh = $dbh;
    }



    
    public function prepare($sql) : self {
        $this->stmt = $this->dbh->open()->prepare($sql);
        return $this;
    }






    public function getParamsType($data) : mixed {
        $dataType = null;
        switch($data){
            case  is_null($data) :
                $dataType = PDO::PARAM_NULL;
                break;  
            case  is_bool($data) :
                $dataType = PDO::PARAM_BOOL;
                break;  
            case  is_integer($data) :
                $dataType = 1;
                break;  
            default : 
                $data = PDO::PARAM_STR;
                break;
        }
        return  $dataType;
    }





    public function bindValues(array $params) : self{
        foreach($params as $key => $value){
            $this->stmt->bindValue(':'.$key,$value);
        }
        return $this;
    }



    public function bindParameters(array $params, bool $isSearch = false) : self {
        $type = ($isSearch === false) ? $this->bindValues($params) :
        $this->bindSearchValues($params);
        if($type){
            return $this;
        }  
    }




    
    
    public function bindSearchValues(array $params) : self {
        foreach($params as $key => $value){
            $this->stmt->bindValue(':'.$key,'%' . $value . '%',$this->getParamsType($value));
        }
        return $this;
    }






    public function execute() : bool {
        return $this->stmt->execute();
    }




    public function getLastId() : string|false {
        if($this->dbh->open()){
            return $this->dbh->open()->lastInsertId();
        }
        return false;
    }




    
    public function presist($sql,$params = []) : bool {
        try{
            return $this->prepare($sql)
            ->bindParameters($params)
            ->execute();
        }catch(Throwable $throwable){
            throw $throwable;
        }
    }



    
    
    public function buildQUeryParams(array $conditions,array $params = []){
        return (!empty($params) || !empty($conditions)) ? array_merge($conditions,$params) :
        $params;
    }




    public function results() : mixed {
        return $this->stmt->fetchAll($this->defaultFetchMOde);
    }



    public function result(){
        return $this->stmt->fetch($this->defaultFetchMOde);
    }


    
    
    public function setfetchMode($fetchMOde) : self {
        $this->defaultFetchMOde = $fetchMOde;
        return $this;
    }



    
    public function numRows() : int {
        if($this->stmt){
            return $this->stmt->rowCount();
        }
    }



    public function getDbh(){
        return $this->dbh;
    }


}