<?php
namespace TinyFw;
class Kernel 
{
	/**
	 * @var Input
	 */
	private $input = null;
	
	/**
	 * @var Output
	 */
	private $output = null;
	
	private $controller = null;
	
	
	function __construct()
	{
		$this->input = Input::instance();
		$this->output = new Output();
	}
	
	/**
	 * Build class name
	 * @param string $route 
	 */
	public static function buildClassNameFromRoutingParams( $route, $prefix = null, $suffix = null  )
	{
	    $route = strtolower( $route );
	    $tmp = explode( '-', $route );
	    
	    $name = ""; 
	    foreach( $tmp as $part ){
	        $name .= ucfirst( $part );
	    }
	    
	    if( $prefix != null ){
	        $name = $prefix.$name;
	    }
	    
	    if( $suffix != null ){
	        $name .= $suffix;
	    }
	    
	    return $name;
	}
	
	private function &findController()
	{
		$controllerName = $this->input->controllerName();
		$controllerClass = self::buildClassNameFromRoutingParams( $controllerName , null, 'Controller');
		$controllerClassFull = "App\\Controller\\{$controllerClass}";
		
		$this->controller = new $controllerClassFull( $this->input );
		
		return $this->controller;
	}
	
	/**
	 * Find controller & actions depend on input routing
	 */
	public function process()
	{
		$controller = $this->findController();
		
		$controller->process();
		$output = $controller->getOutput();
		
		$layout = $this->getLayoutPath();
		if( $this->getLayoutPath() !== false ){
    		if( !file_exists( $layout ) ){
    		    throw new \Exception("Layout template file {$layout} is not found.");
    		}
    		
    		ob_start();
    		include $layout;
    		$output = ob_get_clean();
		}
		
		$this->output->setOutputData( $output , $controller->getContentType() );
	}
	
	private function getLayoutPath()
	{
	    if( $this->controller->getLayout() !== false ){
	       $layoutName = self::buildClassNameFromRoutingParams( $this->controller->getLayout() );
	       $layout = APP_DIR."/Template/{$layoutName}.layout.html.php";
	       return $layout;
	    }
	    return false;
	}
	
	
	public function render()
	{
		return $this->output->send();
	}
}

?>