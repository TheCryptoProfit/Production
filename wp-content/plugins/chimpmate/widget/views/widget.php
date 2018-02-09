<?php
$plugin = ChimpMate_WPMC_Assistant::get_instance();
$settings = $plugin->settings;
if($plugin->isload['w']){
	$plugin->loadscripts();
	$cmeta = $plugin->cmeta;
	$cmeta_cat = $plugin->cmeta_cat;
	$form = $plugin->getformbyid(isset($cmeta['Widget']['form'])?$cmeta['Widget']['form'] : (isset($cmeta_cat['Widget']['form'])?$cmeta_cat['Widget']['form'] : $settings['widget_form']));
	$form['fields'] = array_filter($form['fields'] ,array($plugin , 'myArrFilter'));
	global $wpmc_font;
	$theme= $plugin->getthemebyid(isset($cmeta['Widget']['theme'])?$cmeta['Widget']['theme'] : (isset($cmeta_cat['Widget']['theme'])?$cmeta_cat['Widget']['theme'] :$settings['widget_theme']));
	$theme=$theme['options'];
	if(isset($this->wid_count))$this->wid_count++;
	else $this->wid_count = 1;
	$wpmcw_id = "wpmchimpaw-".$this->wid_count;
		include( 'widget'.$theme['tpl'].'.php' );
}?>