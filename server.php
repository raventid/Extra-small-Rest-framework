<?php

/**MB better use requires or even autoloading
 */
include 'request.php';
include 'response.php';
include 'safemysql.class.php';
include '../config.php';


/**Options for DB conection
*/
$opts = array(
    'host' => $Host,
    'user' => $User,
    'pass' => $Password,
    'db' => $DB,
    'charset' => $Charset
);


//Objects
$request = new Request($_REQUEST['request']);
$db = new SafeMysql($opts);
$response = new Response();

try {    
    $request->isEndpointExists();
} catch (Exception $e) { // not sure about handling this mistake and mistake status
    $response->content(Array('error' => $e->getMessage()));
    $response->contentType('application/json');
    $response->status('400');
    $response->send();
    exit;
}

//Additional vars

$id = array_shift($request->getArgs());


//add better abstraction with pdo wrapper

//*************************************************************************MAIN LOGIC**********************************************************************************

switch ($request->getRequestMethod()){

case "GET": {
     
    if (!$id){
        $query_result = $db->getAll("SELECT * FROM ?n",$request->getEndpoint());
        if (empty($query_result)) {
            $query_result = null;
        }
    }
    else {
        $query_result = $db->getRow("SELECT * FROM ?n WHERE book_id=?i",$request->getEndpoint(), $id);
    } 

    $response->content($query_result);
    if ($query_result === null){
        $response->status('404');
    }
    $response->contentType('application/json');
    break;
}

case "POST": {

    $inserts = $request->getRequestBody();

    if ($id){
        $response->status('400');
    }
    else{
        $db->query("INSERT INTO ?n SET title=?s, pages=?i, year=?i",$request->getEndpoint(),$inserts['title'],$inserts['pages'],$inserts['year']);
        $response->status('201');
    }
    break;
}

 case "PUT": {

    $updates = $request->getRequestBody();
    
    $query_result = $db->getRow("SELECT * FROM ?n WHERE book_id=?i",$request->getEndpoint(), $id);

    if ($query_result === null){
        $response->status('404');
    }
    elseif (($query_result['title'] == $updates['title']) && ($query_result['pages'] == $updates['pages']) && ($query_result['year'] == $updates['year'])){
        $response->status('304');
    }
    else{
        $db->query("UPDATE ?n SET title=?s, pages=?i, year=?i WHERE book_id=?i",$request->getEndpoint(),$updates['title'],$updates['pages'],$updates['year'],$id);
    }
    break;
}

case "DELETE": {
   
   if ($id) {
       $db->query("DELETE FROM ?n WHERE book_id=?i",$request->getEndpoint(),$id);
       $response->status('204');
   }
   else{
       $db->query("TRUNCATE TABLE ?n",$request->getEndpoint());
       $response->status('204');
   }
   break;
}

default : {
    $response->status('405');
}

}

$response->send();
