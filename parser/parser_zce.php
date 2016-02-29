<?php

require_once("db.php");
require_once("text_file.php");
require_once("question.php");

use \Base\Question;

trait SimpleXMLManager {
  public function openPage($url){
      if(!$page = file_get_contents($url))
      {
          throw new Exception("Cannot open url!");
      }

      $dom_document = new DomDocument();
      $dom_document->strictErrorChecking = FALSE;
      libxml_use_internal_errors(true);
      $dom_document->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));
      libxml_use_internal_errors(false);
      
      $simple_xml = simplexml_import_dom($dom_document);
      return $simple_xml;
  }
}

interface Crowler {
    function returnQuestions();
}

class Aiopass4sure implements Crowler {
    use SimpleXMLManager;

    const URL = 'http://www.aiopass4sure.com/zend-exams/200-530-exam-questions/page/';  

    private function next_page_exists($url){
        //why !!file_get_contents does not work?
        if(!file_get_contents($url)){
            return false;
        } else {
            return true;
        }
    }

    private function parse_page($xml){
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
    
    private function parse_question($url){
        try{
            $simple_xml = $this->openPage($url);
        }catch(Exception $e){
           return; 
        }
      $question_text = $simple_xml
          ->xpath('
          .//div[@class="col-md-10 col-lg-10 col-sm-12 col-xs-12 pull-right"]/
          div[@class="content"]')[0];
      $question_content = $question_text->xpath('.//p');
    
      $question = new Question();
      $params = ['question' => false, 'right_answer' => false];
    
      foreach($question_content as $content){
          foreach($content->attributes() as $k => $v){
              // we need "" here around $v to call __toString()
              if (array_key_exists("$v", $params)){
                  $params["$v"] = true;
              }
          }
    
          if ($params['question'])
              {
                  $question->text = (string) $content;
              } else {
                  $question->answers []= (string) $content;
                  if ($params['right_answer']) {
                      $question->correct_answers []= (string) $content;
                  }
              }
    
              array_walk($params, function(&$v, $k){
                $v = false; 
              });
      }
      return $question;
    }

    private function parse_questions($urls = array()){
        $questions = [];
        foreach($urls as $url){
            try{
                $questions [] = $this->parse_question($url);
            } catch (Exception $e){ // I don't care, page not found so what?
                continue;
            }
        }
        return $questions;
    }

    public function returnQuestions(){
      $a = 26;
      $questions = [];
      while($this->next_page_exists($this::URL . $a) && $a < 28){
        $simple_xml = $this->openPage($this::URL . $a);
        foreach($this->parse_page($simple_xml) as $link){
            $questions []= (string) $link;
        }
        $a++;
      }

      return $this->parse_questions($questions); 
    }
}

class WebParser {
    private $crowler;

    public function __construct(Crowler $c){
        $this->crowler = $c;
    }
    public function printQuestions(){
       print "BEGIN".PHP_EOL;
       print_r($this->crowler->returnQuestions());
       print "END".PHP_EOL; 
    } 
}

$container = new DB\TextFile\TextBase();
$db = new DB\DB($container);

$crowler = new Aiopass4sure();

echo str_repeat(PHP_EOL,3);
$db->save_all($crowler->returnQuestions());

echo str_repeat(PHP_EOL,3);
$arr = $db->load_all();
print_r($arr);
// $parser = new WebParser(new Aiopass4sure());
// $parser->printQuestions();
