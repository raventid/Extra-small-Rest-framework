<?php

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
        // Allow composition of response objects
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
* Transform response body into appropriate format *
*/
    private function formatNeeded($content)
    {
        return json_encode($content);
    }
}

