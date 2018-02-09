<?php
defined('ABSPATH') or die("KEEP CALM AND CARRY ON");

/**
 * Collect enqueued javascripts
 *
 */
class SwiftSecurityManageAssets{

	/**
	 * Already collected dependencies
	 * @var array
	 */
	public $ignore = array();
	
	/**
	 * Collected dependences printed in <li> elements
	 * @var string
	 */
	public $dependences = '';
	
	/**
	 * Scripts already printed
	 * @var array
	 */
	public $printed = array();

	public $output = '';

	/**
	 * Add hooks
	 */
	public function __construct(){
		add_action('wp_loaded', array($this, 'remove_content_start'),0);
		add_action('wp_print_footer_scripts', array($this,'get_enqueued_scripts'), 9999);
	}

	/**
	 * Remove the <head> from the response
	 */
	public function remove_content_start(){
		ob_start(array($this, 'remove_content'));
	}

	public function remove_content($buffer){
		return $this->output;
	}


	/**
	 * Get the enqueued scripts
	 */
	public function get_enqueued_scripts(){	
		global $wp_scripts;
	
		$assets = (isset($_GET['mode']) && $_GET['mode'] == 'script' ? $wp_scripts : $wp_styles);
		foreach( $assets->queue as $handle){
			$this->dependences = '';
			if ($handle != 'admin-bar' && isset($wp_scripts->registered[$handle]) && !in_array($handle, $this->ignore)){
				$object = $wp_scripts->registered[$handle];
				$this->get_dependences($handle);
				if (isset($_GET['location']) && $_GET['location'] == 'footer' && isset($object->extra['group']) && $object->extra['group'] == 1
					|| isset($_GET['location']) && $_GET['location'] == 'header' && !isset($object->extra['group'])){			
						$this->output .= $this->dependences;
						if ($object->handle != 'jquery' && !in_array($object->handle, $this->printed)){
				 			$this->output .= '<li><input type="hidden" name="settings[HideWP][enqueued'.ucfirst($_GET['mode']).'s'.ucfirst($_GET['location']).'][]" value="'.$object->handle.'">'.$object->handle.' ('.($object->handle == 'jquery' ? $wp_scripts->registered['jquery-core']->src : $object->src).')</li>';
				 			$this->printed[] = $object->handle;
				 			$this->ignore[] = $object->handle;
						}
				}
			}
		}
		echo $this->output;
		die();
	}

	/**
	 * Get the dependencies recursively
	 * @param string $handle
	 */
	public function get_dependences($handle){
		global $wp_scripts; 
	
		if (isset($wp_scripts->registered[$handle]->deps) && !in_array($handle, $this->ignore)){
			foreach ((array)$wp_scripts->registered[$handle]->deps as $dep){
				if (isset($wp_scripts->registered[$dep]) && !in_array($dep, $this->ignore)){
					$this->get_dependences($dep);

					$dep_object = $wp_scripts->registered[$dep];
					if ($dep_object->handle != 'jquery'){
						$this->dependences .= '<li><input type="hidden" name="settings[HideWP][enqueued'.ucfirst($_GET['mode']).'s'.ucfirst($_GET['location']).'][]" value="'.$dep_object->handle.'">'.$dep_object->handle.' ('.($dep_object->handle == 'jquery' ? $wp_scripts->registered['jquery-core']->src : $dep_object->src).')</li>';
					}
					$this->ignore[] = $dep;
				}
			}
		}
	}
}
$manage_assets = new SwiftSecurityManageAssets();
?>
