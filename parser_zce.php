<?php

define('URL', 'http://www.aiopass4sure.com/zend-exams/200-530-exam-questions/page/');

function next_page_exists($url){
  if(!$page = file_get_contents($url))
  {
    return false;
  }
  return true; 
}

function get_simplexml($url){
  $page = file_get_contents($url);
  
  $dom_document = new DomDocument();
  $dom_document->strictErrorChecking = FALSE;
  libxml_use_internal_errors(true);
  $dom_document->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));
  libxml_use_internal_errors(false);
  
  $simple_xml = simplexml_import_dom($dom_document);
  return $simple_xml;
}

function parse_page($xml){
  $links_to_questions = $xml
       ->xpath(
       './/div[@class="container"]/
         div[@class="row"]/
          div[@class="col-md-10 col-sm-12 col-xs-12"]/
           p/
           a[@href]');
  
  $result = []; 
  foreach($links_to_questions as $link){
      $result []= $link->attributes()->href;
  }

  return $result;
}

function parse_question($url){
  $page = file_get_contents($url);
  if(!$page){
      throw "Cannot view the question content!";
  }
  
  $dom_document = new DomDocument();
  $dom_document->strictErrorChecking = FALSE;
  libxml_use_internal_errors(true);
  $dom_document->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));
  libxml_use_internal_errors(false);
  
  $simple_xml = simplexml_import_dom($dom_document);
  $question_text = $simple_xml
      ->xpath('
      .//div[@class="col-md-10 col-lg-10 col-sm-12 col-xs-12 pull-right"]/
         div[@class="content"]');
  var_dump($question_text);
  return $simple_xml;
}

class Question {
  private $text;
  private $answers;
  private $correct_answers;
  public function __construct($_text, array $_answers, $_correct_answers){
    $text = $_text;
    $answers = $_answers;
    $correct_answers = $_correct_answers; 
  }
}

$a = 1;
$questions = [];

while(next_page_exists(URL . $a) && $a < 3){
  $simple_xml = get_simplexml(URL . $a);

  print PHP_EOL . "PAGE NUMBER $a" . PHP_EOL;
  print str_repeat('=', 40) . PHP_EOL;
  foreach(parse_page($simple_xml) as $link){
      $questions []= (string)$link;
      echo $link . PHP_EOL;
  }
  print str_repeat(PHP_EOL, 2);

  $a++;
}

parse_question($questions[0]);
