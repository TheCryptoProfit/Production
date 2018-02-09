<?php 
$settings = $this->settings;
$cmeta = $this->cmeta;
$cmeta_cat = $this->cmeta_cat;
$form = $this->getformbyid(isset($cmeta['Flipbox']['form'])?$cmeta['Flipbox']['form'] : (isset($cmeta_cat['Flipbox']['form'])?$cmeta_cat['Flipbox']['form'] : $settings['flipbox_form']));
$form['fields'] = array_filter($form['fields'] ,array($this , 'myArrFilter'));
global $wpmc_font;
$theme= $this->getthemebyid(isset($cmeta['Flipbox']['theme'])?$cmeta['Flipbox']['theme'] : (isset($cmeta_cat['Flipbox']['theme'])?$cmeta_cat['Flipbox']['theme'] :$settings['flipbox_theme']));
$theme=$theme['options'];
include( 'flipbox'.$theme['tpl'].'.php' );
?>