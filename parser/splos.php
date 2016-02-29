<?php
namespace Spl;

require_once "question.php";
use \Base;

$storage = new \SplObjectStorage;
$q = new Base\Question('How much apples?', array(1,2,3,4), array(2));
$storage[$q] = "data";

$str = $storage->serialize();


$storage->unserialize($str);

foreach($storage as $q){
    var_dump($q);
}
