<?php
namespace DB;

interface Container {
    public function create();
    public function read($id = NULL);
    public function update($id = NULL);
    public function delete($id = NULL);
}

namespace DB\TextFile;

class TextBase implements \DB\Container {
    private $file_content;

    public function __construct() {
       $this->file_content = file_get_contents("db.txt"); 
    }
    public function create(){}
    public function read($id = NULL){}
    public function update($id = NULL){}
    public function delete($id = NULL){}

    public function __sleep(){
        print "Serialization";
    }
    public function __wakeup(){
        print "Unserialization"; 
    }
}
