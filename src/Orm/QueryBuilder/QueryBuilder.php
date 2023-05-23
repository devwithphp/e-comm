<?php


namespace Src\Orm\QueryBuilder;

use Exception;



class QueryBuilder {



    protected string $queryString = '';




    protected const DEFAULT_SQL = [
        'fields'      => [],
        'selectors'   => [],
        'conditions'  => [],
        'table'       => '',
        'primary_key' => '',
        'where'       => [],
        'order_by'    => [],
        'limit'       =>  1,
    ];



    protected array $args = [];

    





    public function __construct()
    {}





    public function insert(){
        $fields =  implode(', ',array_keys($this->args['fields'])); 
        $placeholders  =   implode(', ',array_map(fn($key)=> ':' .$key ,array_keys($this->args['fields'])));
        $this->queryString = "INSERT INTO  {$this->args['table']} ($fields)
                              VALUES ($placeholders)";
        return $this->queryString;
    }




    
    public function select(){
        $selectors = (!empty($this->args['selectors'])) ?  implode(',', $this->args['selectors']) : '*';
        $this->queryString = "SELECT $selectors FROM {$this->args['table']} ";  
        if(count($this->args['conditions']) > 0){
            $this->queryString .=  ' WHERE ';   
            $conditions = array_map(function($k){
                return $k . ' = :'. $k ;
            }, array_keys($this->args['conditions'])); 
            $this->queryString .= implode(' AND ', $conditions);
        }
        if(!empty($this->args['order_by'])){
            $this->queryString .= ' ORDER BY ' . implode(', ',$this->args['order_by']) . ' ';
        }
        return $this->queryString  . ';';
    }




    public function update() : string {
        $fields = array_map(function($k){
            return $k . ' = :'. $k ;
        }, array_keys($this->args['fields'])); 
        $this->queryString = "UPDATE {$this->args['table']} 
                              SET " . implode(', ', $fields);  
                              $this->queryString .= ' WHERE ';   
        if(!isset($this->args['where']) || empty($this->args['where']) ){
            $this->queryString .= $this->args['primary_key'] . '= :' 
            . $this->args['primary_key'] .  ' LIMIT ' . $this->args['limit'];
        }else{
            $where = array_map(function($k){
                return $k . ' = :'. $k ;
            }, array_keys($this->args['where'])); 
            $this->queryString .= implode(' AND ', $where);
            
        }
        return $this->queryString  . ';';
    }






    public function delete()   : string{
        $index = array_keys($this->args['conditions']);
        $this->queryString = "DELETE  FROM {$this->args['table']} 
                              WHERE {$index[0]} = :{$index[0]}
                              LIMIT {$this->args['limit']} ";  
        return $this->queryString  . ';';
    }




    
    
    public function fields(array $fields) : self {
        $this->merge(['fields' => $fields]);
        return $this;
    }   






    public function conditions(array $conditions) : self {
        $this->merge(['conditions' =>  $conditions]);
        return $this;
    }





    public function selectors(array $selectors) : self {
        $this->merge(['selectors' => $selectors]);
        return $this;
    }





    public function table(string $table)  : self {
        if(isset($table) && $table !== ''){
            $this->merge(['table' => $table]);
        }
        return $this;
    }


    
    
    
    public function where($value)   : self {
        if(isset($value) && $value !== ''){
            $this->merge(['where' =>$value]);
        }
        return $this;
    }





    public function limit(int $total)  : self {
        if(isset($total) &&$total !== ''){
            $this->merge(['limit' =>$total]);
        }
        return $this;
    }





    
    
    public function primaryKey(string $key) : self {
        if(isset($key) && $key!== ''){
            $this->merge(['primary_key' => $key]);
        }
        return $this;
    }







    private  function merge(array $args) {
        if(!empty($args)){
            foreach($args as $key => $value){
                if(!array_key_exists($key,self::DEFAULT_SQL)){
                    throw new Exception($key .' is not allowed');
                }
                $this->args[$key] = empty($value) ? [] : $value;
            }
        }
    }

    



    public function getQueryString() : string {
        return $this->queryString;
    }



 



}