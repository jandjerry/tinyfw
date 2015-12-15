<?php
namespace TinyFw;
use Zend\Http\Header\HeaderInterface;

class Output
{
    /*
     * Data
     */
    private $data = null;

    /*
     * Content Type
     */
    private $contentType = null;

    /**
     * Headers
     */
    private $headers = array();


    /**
     * Constructor
     * @param mixed $data
     * @param string $contentType
     */
    function __construct($data = null, $contentType = null)
    {
        $this->setOutputData($data, $contentType);
    }

    /**
     * Set output data
     * @param unknown $data
     * @param null $contentType
     */
    public function setOutputData($data, $contentType = null)
    {
        $this->data = $data;
        $this->contentType = $contentType;
    }

    /**
     * Add header to output
     * @param unknown $header
     * @param null $replace
     * @param null $code
     */
    public function addHeader($header, $replace = null, $code = null)
    {
        if( $replace != null && $code != null ){
            $header = array($header, $replace, $code);
        }

        $this->headers[] = $header;
    }


    public function send($exit = true)
    {
        switch ($this->contentType) {
            case 'application/json':
            case 'text/javascript':
            case 'application/x-javascript':
            case 'application/javascript':
            case 'application/jsonp': {
                $this->processJSON();
            }
                break;
            default:
                break;
        }

        //Send headers
        $this->sendHeaders();

        echo $this->data;

        if ($exit == true) {
            exit(0);
        }
    }

    /**
     * Send headers
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header) {
            if( is_array($header) && count($header) > 3){
                Header($header[0], $header[1], $header[2]);
            } else {
                Header($header);
            }
        }
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    public function processJSON()
    {
        if (is_array($this->data)) {
            $this->data = json_encode(array('data' => $this->data));
        }
    }

}

?>