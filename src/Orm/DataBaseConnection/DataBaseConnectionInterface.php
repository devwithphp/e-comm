<?php



namespace Src\Orm\DataBaseConnection;



interface DataBaseConnectionInterface {


    public function open() : \PDO;


    public function close() : void;




}