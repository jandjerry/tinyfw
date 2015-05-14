<?php
namespace TinyFw;
class Input
{
	/*
	 * Instance holder
	 */
	private static $instance = null;
	
	/**
	 * Create instance
	 * @return \TinyFw\Input
	 */
	public static function & instance()
	{
		if( self::$instance == null ){
			self::$instance = new self();
            self::$instance->processRouting();
		}
		return self::$instance;
	}
	
	private $get, $post, $cookie;
	
	/**
	 * Constructor
	 * @param array $get
	 * @param array $post
	 * @param array $cookie
	 */
	function __construct( $get = null, $post = null, $cookie = null )
	{
		$this->get = $get == null ? $_GET : $get ;
		$this->post = $post == null ? $_POST : $post ;
		$this->cookie = $cookie == null ? $_COOKIE : $cookie ;


	}

    private function processRouting()
    {
        //For nginx or cgi applications
        if( !$this->getVariable('_contr_') ){
            $tmp = explode( "/", trim( $_SERVER['REQUEST_URI'], " /") );
            if( isset( $tmp[0] ) ){
                $_GET['_contr_'] = $tmp[0];

                if( isset( $tmp[1]) ){
                    $_GET['_act_'] = $tmp[1];
                }

                for( $i = 2; $i < count( $tmp ); $i ++ ){
                    if( isset( $tmp[$i] ) ){
                        $_GET['_params_'][] = $tmp[$i];
                    }
                }
            }
        }
    }
	
	/**
	 * Set custom $_GET variable
	 * @param unknown $get
	 */
	public function setGet( $get ) 
	{
		$this->get = $get;
	}
	
	
	
	/**
	 * $_POST
	 * @param string $key
	 * @return mixed
	 */
	public function postVariable( $key )
	{
		if( isset( $this->post[ $key ] ) ){
			return $this->post[ $key ];
		}
		return null;
	}
	
	/**
	 * $_GET
	 * @param string $key
	 * @return mixed
	 */
	public function getVariable( $key )
	{
		if( isset( $this->get[ $key ] ) ){
			
			return $this->get[ $key ];
		}
		return null;
	}
	
	/**
	 * $_COOKIE
	 * @param string $key
	 * @return mixed
	 */
	public function cookieVarialbe( $key )
	{
		if( isset( $this->cookie[ $key ] ) ){
				
			return $this->cookie[ $key ];
		}
		return null;
	}
	
	
	/**
	 * Get controller name
	 * @return string
	 */
	public function controllerName()
	{
		$controllerName =  $this->getVariable('_contr_');
		$controllerName = $controllerName == null ? 'default' : $controllerName;
		return $controllerName;
	}
	
	/**
	 * Get action name
	 * @return string
	 */
	public function actionName()
	{
		$actionName = $this->getVariable('_act_');
		$actionName = $actionName == null ? 'index' : $actionName;
		return $actionName;
	}

    /**
     * @return array
     */
    public function getParams()
    {
        return isset( $_GET['_params_'] ) ? $_GET['_params_'] : array();
    }

    /**
     * @param $index
     * @return null
     */
    public function getParam( $index )
    {
        $params = $this->getParams();
        return isset( $params[ $index ]) ? $params[ $index ] : null;
    }
} 
?>