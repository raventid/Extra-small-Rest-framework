<?php header('Content-type: text/html; charset=utf-8'); ?>
<html>
  <head>
  </head>
  <body>
    <form method="post">
        <label for"search">
            Question
        </label>
        <input id="search" name="search" type="text" placeholder="Enter your question">
    </form>
    <div>
       <?php
         require_once("parser_zce.php");
         $container = new DB\TextFile\TextBase();
         $db = new DB\DB($container);
         
         $array = $db->load_all();
         
         //if(isset($_POST['search']))
         //{
             $number = 1; 
             foreach ($array as $question) {
                 echo '<p>' . $number . ') ' . $question->text . '</p>';
                 foreach($question->answers as $answer){
                     if(count(array_intersect($question->correct_answers, [$answer]))) 
                     {
                         print '<span style="color:green;">' . $answer . '</span> <br>';
                     } else {
                         print $answer . "<br>";
                     }
                 }
                 $number++;
             }
         //}
      ?>
    </div>
  </body>
</html>


