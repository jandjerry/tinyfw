<?php
namespace TinyFw;
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
     */
    public function setOutputData($data, $contentType = null)
    {
        $this->data = $data;
        $this->contentType = $contentType;
    }

    /**
     * Add header to output
     * @param unknown $header
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
    }


    public function send($exit = true)
    {
        $this->sendHeaders();

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

        echo $this->data;
        if ($exit == true) {
            die();
        }
    }

    /**
     * Send headers
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header) {
            Header($header);
        }
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }


    public function processJSON()
    {
        //Header("Content-Type: ". $this->contentType );
        Header('Content-Type: application/json');
        if (is_array($this->data)) {
            $this->data = json_encode(array('data' => $this->data));
        }
    }

}

?>