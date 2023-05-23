<?php



namespace Src\Orm\DataBaseConnection;


use Src\Orm\DataBaseConnection\DataBaseConnectionInterface;
use PDOException;
use PDO;

class DataBaseConnection  implements DataBaseConnectionInterface
{


    private \PDO $dbh;


    private array $credentials = [];



    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }
    


    public function open() : PDO {
        try{
            $this->dbh = new PDO($this->credentials['DB_DRIVER'] . 
                ':host=' . $this->credentials['DB_HOST']
            .   ';dbname=' . $this->credentials['DB_NAME'] ,
                $this->credentials['DB_USERNAME'],
                $this->credentials['DB_PASSWORD'],
                [
                    //
                ]
            );
            return $this->dbh;
        }catch(PDOException $e){   
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }




    public function close() : void{
        $this->dbh = null;
    }



    public static  function getDb() : DataBaseConnectionInterface {
        return  (new static([
            'DB_DRIVER'  =>  env('db_driver'),
            'DB_HOST'    =>  env('db_host'),
            'DB_NAME'    =>  env('db_name'),
            'DB_USERNAME' =>  env('db_username'),
            'DB_PASSWORD'=>  env('db_password'),
        ]));
    }

}