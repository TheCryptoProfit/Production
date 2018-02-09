<?php 

class SwiftSecurityCSSMinifier{
	
	public static function minify($css){
		$css = preg_replace('~/\*.*?\*/~s', '', $css);
		$css = preg_replace('~\n~', '', $css);
		$css = preg_replace('~(\s{2,}|\t)~', ' ', $css);
		
		return $css;
	}
	
}

?>