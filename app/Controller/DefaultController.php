<?php
namespace Controller;
use TinyFw\Controller;
class DefaultController extends  Controller{
	
	public function indexAction()
	{
		return $this->render( array('file' => __FILE__ ) );
	}
}