<?php
$settings = $this->settings;
$cmeta = $this->cmeta;
$cmeta_cat = $this->cmeta_cat;
$form = $this->getformbyid(isset($cmeta['Lightbox']['form'])?$cmeta['Lightbox']['form'] : (isset($cmeta_cat['Lightbox']['form'])?$cmeta_cat['Lightbox']['form'] : $settings['lite_form']));
$form['fields'] = array_filter($form['fields'] ,array($this , 'myArrFilter'));
global $wpmc_font;
$theme= $this->getthemebyid(isset($cmeta['Lightbox']['theme'])?$cmeta['Lightbox']['theme'] : (isset($cmeta_cat['Lightbox']['theme'])?$cmeta_cat['Lightbox']['theme'] :$settings['lite_theme']));
$theme=$theme['options'];

include_once( 'litebox'.$theme['tpl'].'.php' );
?>
