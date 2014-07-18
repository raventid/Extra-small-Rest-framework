<?php

class Request 
{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    private $method = '';
    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files In our case it is the name of the table
     */
    private $endpoint = '';
    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    private $verb = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    private $args = Array();
    /**
     * Property: requestBody
     * Stores the body of the PUT and POST request
     */
     private $requestBody = Null;

    /**
     * Constructor: __construct
     * Assemble and pre-process the data
     */
    public function __construct($request) {
        $this->args = explode('/', rtrim($request, '/'));
       // var_dump($this->args);
        $this->endpoint = array_shift($this->args);
        //var_dump($this->endpoint);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }
       // var_dump($this->verb);

        $this->method = $_SERVER['REQUEST_METHOD'];
        //var_dump($this->method);
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
        case 'DELETE':
            $this->request = $this->_cleanInputs($_POST);
        case 'POST':
            $this->request = $this->_cleanInputs($_POST);
            $this->requestBody = json_decode(file_get_contents("php://input"), true);
            break;
        case 'GET':
            $this->request = $this->_cleanInputs($_GET);
            break;
        case 'PUT':
            $this->request = $this->_cleanInputs($_GET);
            $this->requestBody = json_decode(file_get_contents("php://input"), true);
            break;
        }
    }
    public function isEndpointExists() {
        //Very bad and ugly way, it should be changed
        if ($this->endpoint != 'books') {
            // Here we can add some code to test if appropriate tables in DB or API methods exists
            throw new Exception("Endpoint $this->endpoint not found");
        }
    }

    /**
    *Get the endpoint
    */
    public function getEndpoint(){
        return $this->endpoint;
    }
    /** 
    *Get arguments in our case it will be book(item) id
    */
    public function getArgs(){
        return $this->args;
    }
    /** 
    *Get PUT and POST request body
    */
    public function getRequestBody(){
        return $this->requestBody;
    }
    /** 
    *Get the request method
    */
    public function getRequestMethod(){
        return $this->method;
    }

    /** 
    *Sanitaze input url
    */
    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

}
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476
/*&&&&&&&&&&&&&&&&*&*&@*^#%@^##################*%^#@%&^*%$^@%&*%^@%#^&@%^$%&^%#%&^#%^*@#%@^&%#^%&^*%$6351427634634653264531945324726172618476*/

class Response
{
    /**
    * Property: status
    * Response status 
    */
    protected $status = 200;
    /**
    * Property: content
    * Response body
    */
    protected $content;
    /**
    * Property: encoding
    * Response encoding
    */
    protected $encoding = "UTF-8";
    /**
    * Property: contentType
    * Response type of content
    */
    protected $contentType = "text/plain";
    /**
    * Property: protocol
    * Protocol using for data transfer in our case HTTP
    */
    protected $protocol = "HTTP/1.1";
    /**
    * Property: headers
    * All of the headers set for response (can clear with clearHeaders() and add with header())
    */
    protected $headers = array();


    /**
* Constructor Function
*/
    public function __construct($content = null, $status = 200)
    {
        /** Allow composition of response objects
        $class = __CLASS__;
        if($content instanceof $class) {
            $this->content = $content->content();
            $this->status = $content->status();
        } else {
            $this->content = $content;
            $this->status = $status;
        }
        $this->protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'http';
    }


    /**
* Set HTTP header
*
*/
    public function header($type, $content = null)
    {
        if($content === null) {
            if(isset($this->headers[$type])) {
                return $this->headers[$type];
            }
            return false;
        }

        // Normalize headers to ensure proper case
        for($tmp = explode("-", $type), $i=0;$i<count($tmp);$i++) {
            $tmp[$i] = ucfirst($tmp[$i]);
        }

        $type = implode("-", $tmp);
        if($type == 'Content-Type') {
            if (preg_match('/^(.*);\w*charset\w*=\w*(.*)/', $content, $matches)) {
                $this->contentType = $matches[1];
                $this->encoding = $matches[2];
            } else {
                $this->contentType = $content;
            }
        } else {
            $this->headers[$type] = $content;
        }
        return $this;
    }


    /**
* Get array of all HTTP headers
*/
    public function headers()
    {
        return $this->headers;
    }


    /**
* Set HTTP status to return
*/
    public function status($status = null)
    {
        if(null === $status) {
            return $this->status;
        }
        $this->status = $status;
        return $this;
    }


    /**
* Set HTTP encoding to use
*/
    public function encoding($encoding = null)
    {
        if(null === $encoding) {
            return $this->encoding;
        }
        $this->encoding = $encoding;
        return $this;
    }
   

    /**
* Set HTTP response body
*/
    public function content($content = null)
    {
        if(null === $content) {
            return $this->content;
        }
        $this->content = $content;
    }

    /**
* Append new content to HTTP response body
*/
    public function appendContent($content)
    {
        $this->content .= $content;
    }

    /**
* Set HTTP content type
*/
    public function contentType($contentType = null)
    {
        if(null == $contentType) {
            return $this->contentType;
        }
        $this->contentType = $contentType;
        return $this;
    }


    /**
* Clear any previously set HTTP headers
*/
    public function clearHeaders()
    {
        $this->headers = array();
        return $this;
    }


    /**
* Send HTTP status header
*/
    protected function sendStatus()
    {
        // Send HTTP Header
        header($this->protocol . " " . $this->status . " " . $this->statusText($this->status));
    }


    /**
* Get HTTP header response text from status code
*/
    public function statusText($statusCode)
    {
        $responses = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',

            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            226 => 'IM Used',

            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Reserved',
            307 => 'Temporary Redirect',

            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',

            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            510 => 'Not Extended',
            511 => 'Network Authentication Required'
        );

        $statusText = false;
        if(isset($responses[$statusCode])) {
            $statusText = $responses[$statusCode];
        }

        return $statusText;
    }


    /**
* Send all set HTTP headers
*/
    public function sendHeaders()
    {
        if(isset($this->contentType)) {
            header('Content-Type: '.$this->contentType."; charset=".$this->encoding);
        }

        // Send all headers
        foreach($this->headers as $key => $value) {
            if(!is_null($value)) {
                header($key . ": " . $value);
            }
        }
    }

    /**
* Send HTTP body content
*/
    public function sendBody()
    {
        echo $this->content;
    }


    /**
* Send HTTP response - headers and body executing __toString below
*/
    public function send()
    {
        echo $this; 
    }


    /**
* Send HTTP response on string conversion
*/
    public function __toString()
    {
        // Get body content to return
        try {
            $content = $this->formatNeeded($this->content());
        } catch(\Exception $e) {
            $content =  $this->formatNeeded($e);
            $this->status(500);
        }

        // Send headers if not already sent
        if(!headers_sent()) {
            $this->sendStatus();
            $this->sendHeaders();
        }

        return $content;
    }
/**
* Transform response body into appropriate format
*/
    private function formatNeeded($content){
        return json_encode($content);
    }
}

//Includes

include 'safemysql.class.php';
include '../config.php';


//Options for DB conection

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