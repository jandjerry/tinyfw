<?php
namespace TinyFw;
class FileMonit
{
	private static $instance = null;
	
	public static function &instance()
	{
		if( self::$instance == null ){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	
	private $interval;
	private $timerun;
	private $timeout;
	
	private $sendOut = false;
	private $content = null;
	
	private $isTimeout = false;
	
	
	public function reset()
	{
		$this->interval = 1000;
		$this->timerun = 0;
		$this->timeout = 20000000; //20 sec
		
		$this->sendOut = false;
		$this->isTimeout = false;
	}
	
	public function isTimeout()
	{
		return $this->isTimeout;
	}
	
	/**
	 * 
	 * @param unknown $file
	 * @param string $clear
	 * @return Ambigous <multitype:, string, null>
	 */
	public function monitor( $file, $clear = true  )
	{
		$this->reset();
		
		while( true ){
			if( file_exists( $file ) ) {
				$this->sendOut = true;
				
				$this->content = file_get_contents( $file );
				if( $clear == true ){
					@unlink(  $file  );
				}
			}
			
			if( $this->timerun >= $this->timeout ){
				$this->isTimeout = true;
			}
		
			if( $this->isTimeout == true || $this->sendOut == true  ){
				return $this->content;
			}
		
			//This part control the timeout and save cpu usage
			$this->timerun += $this->interval;
			usleep( $this->interval );
		}
	}
	
	/**
	 * Mornitor any file with extension an return array of content if possible
	 * @param string $dir
	 * @param string $ext
	 * @param string $clear
	 * @return Ambigous <multitype:, string>
	 */
	public function monitorAny( $dir, $ext, $clear = true )
	{
	    $this->reset();
	    
	    while( true ){
	        $files = glob($dir.'*'.$ext);
	        $hasFile = is_array( $files ) && count( $files ) > 0;
	        if( $hasFile ) {
	            $this->sendOut = true;
	    
	            $this->content = array();
	            foreach( $files as $file ){
	                $this->content[] = file_get_contents( $file );
	                if( $clear == true ){
	                    @unlink(  $file  );
	                }
	            }
	            
	        }
	        	
	        if( $this->timerun >= $this->timeout ){
	            $this->isTimeout = true;
	        }
	    
	        if( $this->isTimeout == true || $this->sendOut == true  ){
	            return $this->content;
	        }
	    
	        //This part control the timeout and save cpu usage
	        $this->timerun += $this->interval;
	        usleep( $this->interval );
	    }
	}
	
    public function notifyAny( $dir, $ext, $content = '' )
    {
        $name = md5(rand().time());
        $fileName = $dir.'/'.$name.$ext;
        return file_put_contents($fileName, $content);
    }
    
    
    public static function writeFile( $filePath, $content )
    {
        $dir = dirname( $filePath );
        if( !isset( $dir ) ){
            //Create dir
            mkdir( $dir, 0777, true );
        }
        return file_put_contents( $filePath, $content);
    }
    
    public static function writeRandomFile( $dir, $ext, $content )
    {
        $fileName = md5(rand().time()).$ext;
        $filePath = $dir.'/'.$fileName;
        return self::writeFile($filePath, $content);
    }
    
	
}