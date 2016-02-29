<?php
namespace Base;

class Question {
  public $text = '';
  public $answers = [];
  public $correct_answers = [];
  public function __construct($_text = '', $_answers = array(), $_correct_answers = array()){
    $this->text = $_text;
    $this->answers = $_answers;
    $this->correct_answers = $_correct_answers; 
  }
}

