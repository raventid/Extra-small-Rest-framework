<?php
namespace DB;

class DB {
    private $container = null;

    public function __construct(Container $_container){
        $this->container = $_container;
    }

    public function save_all(array $input){
        $this->container->save_all($input);
    }

    public function load_all(){
        return $this->container->load_all(); 
    }
}
