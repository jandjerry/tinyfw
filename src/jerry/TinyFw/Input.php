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
} 
?>