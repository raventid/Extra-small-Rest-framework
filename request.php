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


//@todo too complex constructor
    /**
     * Constructor: __construct
     * Assemble and pre-process the data
     */
    public function __construct($request) 
    {
        $this->args = explode('/', rtrim($request, '/'));
       // var_dump($this->args);
        $this->endpoint = array_shift($this->args);
        //var_dump($this->endpoint);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }
       // var_dump($this->verb);

        $this->method = $_SERVER['REQUEST_METHOD'];
        //var_dump($this->method); very verbose
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) 
        {
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
    public function isEndpointExists() 
    {
        //Very bad and ugly way, it should be changed
        //request should not is endpoint exists
        if ($this->endpoint != 'books') 
        {
            // Here we can add some code to test if appropriate tables in DB or API methods exists
            throw new Exception("Endpoint $this->endpoint not found");
        }
    }
    
    public function getRecord()
    {
        //book or anythig else
    }

    /**
    *Get the endpoint
    */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
    /** 
    *Get arguments in our case it will be book(item) id
    */
    public function getArgs()
    {
        return $this->args;
    }
    /** 
    *Get PUT and POST request body
    */
    public function getRequestBody()
    {
        return $this->requestBody;
    }
    /** 
    *Get the request method
    */
    public function getRequestMethod()
    {
        return $this->method;
    }

    /** 
    *Sanitaze input url
    */
    private function _cleanInputs($data) 
    {
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
