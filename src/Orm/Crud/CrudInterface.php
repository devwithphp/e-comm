<?php


namespace Src\Orm\Crud;

use PDO;


interface  CrudInterface {


    
    public function create(array $fields = [])  : bool ;



    public function read(array $selectors=[],array $conditions = [],array $optional = [],$fetchMode =PDO::FETCH_ASSOC );



    public function update(array $fields = [],$where =[],$limit =1) : bool;




    public function delete(array $conditions = [],$limit = 1) : bool ;
    

    public function findOne(array $selectors=[],array $conditions = [],array $optional = [],$fetchMode =PDO::FETCH_ASSOC);
    
}