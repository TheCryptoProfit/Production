<?php 
$settings = $this->settings;
$cmeta = $this->cmeta;
$cmeta_cat = $this->cmeta_cat;
$form = $this->getformbyid(isset($cmeta['Addon']['form'])?$cmeta['Addon']['form'] : (isset($cmeta_cat['Addon']['form'])?$cmeta_cat['Addon']['form'] : $settings['addon_form']));
$form['fields'] = array_filter($form['fields'] ,array($this , 'myArrFilter'));
global $wpmc_font;
$theme= $this->getthemebyid(isset($cmeta['Addon']['theme'])?$cmeta['Addon']['theme'] : (isset($cmeta_cat['Addon']['theme'])?$cmeta_cat['Addon']['theme'] :$settings['addon_theme']));
$theme=$theme['options'];
include( 'addon'.$theme['tpl'].'.php' );
?>