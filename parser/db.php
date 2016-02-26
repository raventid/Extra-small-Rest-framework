<?php
namespace DB;

class DB {
    private $container = null;

    public function __construct(Container $_container){
        $this->container = $_container;
    }
}
