<?php




namespace  Src\Orm\DataMapper;

use Exception;



class DataMapperFactory {


    public function create(string $dataBaseCoonectionString,string $dataMapperString ){
        $dataBaseCoonectionObject = new $dataBaseCoonectionString([
            'DB_DRIVER'  =>  env('db_driver'),
            'DB_HOST'    =>  env('db_host'),
            'DB_NAME'    =>  env('db_name'),
            'DB_USERNAME' =>  env('db_username'),
            'DB_PASSWORD'=>  env('db_password'),
        ]);
        $dataMapperObject = new $dataMapperString($dataBaseCoonectionObject);
        return $dataMapperObject;
    }
}