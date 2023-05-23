<?php



namespace Src\View;

use Exception;
use DirectoryIterator;



class View {


    private $rootDirPath;


    public function __construct(string $rootDirPath){
        $this->rootDirPath = $rootDirPath;
    }



    public  function  view(string $path , array $params = [],$layout = 'main'){
        $content = $this->htmlView($path,$params);
        $layout  =  $this->layout($layout);
        $layout  = str_replace('{{content}}',$content,$layout);
        return $layout;
    }   




    private  function htmlView(string $path , array $params = []){
        $dirpath = $this->rootDirPath . '/view/';
        $dir     = new DirectoryIterator($dirpath);
        foreach($dir as $file){
            if(is_file($file)){
                if(strcmp($file,$path)){
                    $extension = '.' .pathinfo($file, PATHINFO_EXTENSION);
                    ob_start();
                    extract($params);
                    include_once $dirpath .$path .$extension;
                    return  ob_get_clean();
                } 
            }
        }
        throw new Exception($path .' is not found');
       
    }
      


    private function layout( $default= 'main'){
        $path = $this->rootDirPath . '/view/layout/' . $default . '.php';
        if(!file_exists($path)){
            throw new Exception($path . ' file not found');   
        }
        ob_start();
        include_once $path;
        return  ob_get_clean();
    }




    
}