<?php



namespace Src\Orm;

use PDO;


Trait ModelTraits {

    
    public static  function create(array $fields = []) : bool {
        return static::crud()->create($fields);
    }


    public  static function read(array $selectors=[],array $conditions = [],array $optional = [],$fetchMode =PDO::FETCH_ASSOC ):mixed{
        return static::crud()->read($selectors,$conditions,$optional,$fetchMode);
    }


    
    public static function update(array $fields = [],$where =[],$limit =1) : bool {
        return static::crud()->update($fields,$where,$limit);
    }


    public static function delete(array $conditions = [],$limit = 1) : bool{
        return static::crud()->delete($conditions,$limit);
    }


    public static function findOne(array $selectors=[],array $conditions = [],array $optional = [],$fetchMode =PDO::FETCH_ASSOC){
        return static::crud()->findOne($selectors,$conditions,$optional,$fetchMode);
    }


    public static function crud(){
        $entityManger = neW EntityManager();
        return $entityManger->getCrud(static::getTableName(),self::getTableID()); 
    } 
  

}