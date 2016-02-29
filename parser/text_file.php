<?php
namespace DB;

interface Container {
    public function create(array $params);
    public function read(array $params);
    public function update($id = NULL);
    public function delete($id = NULL);
}

namespace DB\TextFile;

require_once "question.php";
use \Base;

class TextBase implements \DB\Container {
    const DB_FILE = "db.txt";
    private $file_content;

    public function __construct(){
        $this->file_content = file_get_contents(self::DB_FILE); 
    }

    public function create(array $params){
        $params['id'] = $this->last_id() + 1;
        $params['title'];
        $params['answers'];
        $params['correct_answers']; 
    }

    public function read(array $params){
        if($params['id']){}
        $params['title'];
        $params['answers'];
        $params['correct_answers'];
    }

    public function update($id = NULL){
    }

    public function delete($id = NULL){
    }

    public function load_all(){
        if(empty($this->file_content)){
            throw new Exception("Content of file is empty!");
        }
        return $this->to_array($this->file_content);
    }

    public function save_all(array $input){
        file_put_contents(self::DB_FILE, $this->to_string($input));
    }

    private function last_id(){
    }

    private function to_array($input){
        $storage = new \SplObjectStorage;
        $storage->unserialize($input);
        $output = [];
        foreach ($storage as $key)
        {
            $output [] = $key;
        }
        return $output;
    }

    private function to_string(array $input){
        $storage = new \SplObjectStorage;
        foreach($input as $question){
            $storage->attach($question);
        }
        return $storage->serialize();
    }
}
