<?php



namespace Src\Base;

use Src\Orm\ModelTraits;



class Model {
    
    use ModelTraits;


    protected static string $tableName = '';


    
    protected static string $tableID    = ''; 



    
    public static function getTableName(){
        return static::$tableName;
    }





    public static function getTableID(){
        return static::$tableID;
    }





}


