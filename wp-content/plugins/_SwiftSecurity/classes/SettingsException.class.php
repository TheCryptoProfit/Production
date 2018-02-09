<?php 
/**
 * Exception object to handle settings error
 *
 */
class SwiftSecuritySettingsException extends Exception
{
	
	/**
	 * Error message
	 * @var string
	 */
	public $message;
	
	/**
	 * Represent error level (error|warning|info) default is error
	 * @var string
	 */
	public $level;
	
	/**
	 * Error code default is 100
	 * @var integer
	 */
	public $code;
	
	/**
	 * Previous property of original Exception object
	 * @var unknown
	 */
	public $previous;
	
	/**
	 * Create the object
	 * @param array $error
	 */
	public function __construct($error)
	{		
		$this->message	= (isset($error['message']) ? $error['message'] : 'Unknown error');
		$this->level 	= (isset($error['level']) ? $error['level'] : 'error');
		$this->code 	= (isset($error['code']) ? (int)$error['code'] : 100);
		$this->previous = (isset($error['previous']) ? $error['previous'] : null);
		
		parent::__construct($this->message, $this->code, $this->previous);
	}
	
	/**
	 * Overwrite the original __toString method for tiny error messages
	 * @return string error message
	 */
	public function __toString()
	{
		return ucfirst($this->level) . "{$this->message}\n";
	}
	
	/**
	 * Returns the exception's error level (error, warning, info)
	 * @return $string error level
	 */
	public function getLevel()
	{
		return $this->level;
	}
	
}

?>