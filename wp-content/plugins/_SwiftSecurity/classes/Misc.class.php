<?php 

/**
 * Class to bypass/spoof various functions
 *
 */
class Misc{
	
	/**
	 * Spoof WP_Screen in_admin object to get false for is_admin() even we are inside admin 
	 * @return boolean
	 */
	public function in_admin(){
		return false;
	}
}

?>