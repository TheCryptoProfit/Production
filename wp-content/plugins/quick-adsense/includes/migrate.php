<?php 
global $QData;
$QData['AdsWid'] = 10;
$QData['Ads'] = 10;
$QData['Name'] = 'Quick Adsense';
$QData['Version'] = '2.0';
$QData['URI'] = 'http://quickadsense.com/';
$QData['AdsWidName'] = 'AdsWidget%d (Quick Adsense)';
$QData['Default'] = array(
	'AdsDisp' => '3',
	'BegnAds' => true,
	'BegnRnd' => '1',
	'EndiAds' => true,
	'EndiRnd' => '0',
	'MiddAds' => false,
	'MiddRnd' => '0',
	'MoreAds' => false,
	'MoreRnd' => '0',
	'LapaAds' => false,
	'LapaRnd' => '0',
	'Par1Ads' => false,
	'Par1Rnd' => '0',
	'Par1Nup' => '0',
	'Par1Con' => false,
	'Par2Ads' => false,
	'Par2Rnd' => '0',
	'Par2Nup' => '0',
	'Par2Con' => false,
	'Par3Ads' => false,
	'Par3Rnd' => '0',
	'Par3Nup' => '0',
	'Par3Con' => false,
	'Img1Ads' => false,
	'Img1Rnd' => '0',
	'Img1Nup' => '0',
	'Img1Con' => true,
	'AppPost' => true,
	'AppPage' => true,
	'AppHome' => false,
	'AppCate' => false,
	'AppArch' => false,
	'AppTags' => false,
	'AppMaxA' => false,
	'AppSide' => false,
	'AppLogg' => false,
	'QckTags' => true,
	'QckRnds' => false,
	'QckOffs' => false,
	'QckOfPs' => false
);

$QData['DefaultAdsOpt'] = array(
	'AdsMargin' => '10',
	'AdsAlign' => '2'
);
$QData['DefaultAdsName'] = array();

for($i = 1; $i <= $QData['Ads']; $i++) { 
	array_push($QData['DefaultAdsName'], 'AdsCode'.$i);
	array_push($QData['DefaultAdsName'], 'AdsAlign'.$i);
	array_push($QData['DefaultAdsName'], 'AdsMargin'.$i);
};

for($i = 1; $i <= $QData['AdsWid']; $i++) { 
	array_push($QData['DefaultAdsName'], 'WidCode'.$i);	
};


function quick_adsense_2_get_options_with_defaults() {
	global $QData;
	//delete_option('quick_adsense_2_options');
	$options = get_option('quick_adsense_2_options');
	if(!is_array($options) || (count($options) < 1)) {
		$oldData = get_option('AdsDisp');
		if(isset($oldData) && in_array($oldData, array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'))) {
			$options = array ();
			$temp = get_option('AdsDisp'); if(isset($temp) && ($temp != '')) { $options['AdsDisp'] = $temp; }
			$temp = get_option('BegnAds'); if(isset($temp) && ($temp != '')) { $options['BegnAds'] = $temp; }
			$temp = get_option('BegnRnd'); if(isset($temp) && ($temp != '')) { $options['BegnRnd'] = $temp; }
			$temp = get_option('MiddAds'); if(isset($temp) && ($temp != '')) { $options['MiddAds'] = $temp; }
			$temp = get_option('MiddRnd'); if(isset($temp) && ($temp != '')) { $options['MiddRnd'] = $temp; }
			$temp = get_option('EndiAds'); if(isset($temp) && ($temp != '')) { $options['EndiAds'] = $temp; }
			$temp = get_option('EndiRnd'); if(isset($temp) && ($temp != '')) { $options['EndiRnd'] = $temp; }
			$temp = get_option('MoreAds'); if(isset($temp) && ($temp != '')) { $options['MoreAds'] = $temp; }
			$temp = get_option('MoreRnd'); if(isset($temp) && ($temp != '')) { $options['MoreRnd'] = $temp; }
			$temp = get_option('LapaAds'); if(isset($temp) && ($temp != '')) { $options['LapaAds'] = $temp; }
			$temp = get_option('LapaRnd'); if(isset($temp) && ($temp != '')) { $options['LapaRnd'] = $temp; }
			$temp = get_option('Par1Ads'); if(isset($temp) && ($temp != '')) { $options['Par1Ads'] = $temp; }
			$temp = get_option('Par1Rnd'); if(isset($temp) && ($temp != '')) { $options['Par1Rnd'] = $temp; }
			$temp = get_option('Par1Nup'); if(isset($temp) && ($temp != '')) { $options['Par1Nup'] = $temp; }
			$temp = get_option('Par1Con'); if(isset($temp) && ($temp != '')) { $options['Par1Con'] = $temp; }
			$temp = get_option('Par2Ads'); if(isset($temp) && ($temp != '')) { $options['Par2Ads'] = $temp; }
			$temp = get_option('Par2Rnd'); if(isset($temp) && ($temp != '')) { $options['Par2Rnd'] = $temp; }
			$temp = get_option('Par2Nup'); if(isset($temp) && ($temp != '')) { $options['Par2Nup'] = $temp; }
			$temp = get_option('Par2Con'); if(isset($temp) && ($temp != '')) { $options['Par2Con'] = $temp; }
			$temp = get_option('Par3Ads'); if(isset($temp) && ($temp != '')) { $options['Par3Ads'] = $temp; }
			$temp = get_option('Par3Rnd'); if(isset($temp) && ($temp != '')) { $options['Par3Rnd'] = $temp; }
			$temp = get_option('Par3Nup'); if(isset($temp) && ($temp != '')) { $options['Par3Nup'] = $temp; }
			$temp = get_option('Par3Con'); if(isset($temp) && ($temp != '')) { $options['Par3Con'] = $temp; }
			$temp = get_option('Img1Ads'); if(isset($temp) && ($temp != '')) { $options['Img1Ads'] = $temp; }
			$temp = get_option('Img1Rnd'); if(isset($temp) && ($temp != '')) { $options['Img1Rnd'] = $temp; }
			$temp = get_option('Img1Nup'); if(isset($temp) && ($temp != '')) { $options['Img1Nup'] = $temp; }
			$temp = get_option('Img1Con'); if(isset($temp) && ($temp != '')) { $options['Img1Con'] = $temp; }
			$temp = get_option('AppPost'); if(isset($temp) && ($temp != '')) { $options['AppPost'] = $temp; }
			$temp = get_option('AppPage'); if(isset($temp) && ($temp != '')) { $options['AppPage'] = $temp; }
			$temp = get_option('AppHome'); if(isset($temp) && ($temp != '')) { $options['AppHome'] = $temp; }
			$temp = get_option('AppCate'); if(isset($temp) && ($temp != '')) { $options['AppCate'] = $temp; }
			$temp = get_option('AppArch'); if(isset($temp) && ($temp != '')) { $options['AppArch'] = $temp; }
			$temp = get_option('AppTags'); if(isset($temp) && ($temp != '')) { $options['AppTags'] = $temp; }
			$temp = get_option('AppMaxA'); if(isset($temp) && ($temp != '')) { $options['AppMaxA'] = $temp; }
			$temp = get_option('AppSide'); if(isset($temp) && ($temp != '')) { $options['AppSide'] = $temp; }
			$temp = get_option('AppLogg'); if(isset($temp) && ($temp != '')) { $options['AppLogg'] = $temp; }
			$temp = get_option('QckTags'); if(isset($temp) && ($temp != '')) { $options['QckTags'] = $temp; }
			$temp = get_option('QckRnds'); if(isset($temp) && ($temp != '')) { $options['QckRnds'] = $temp; }
			$temp = get_option('QckOffs'); if(isset($temp) && ($temp != '')) { $options['QckOffs'] = $temp; }
			$temp = get_option('QckOfPs'); if(isset($temp) && ($temp != '')) { $options['QckOfPs'] = $temp; }
			$temp = get_option('AdsCode1'); if(isset($temp)) { $options['AdsCode1'] = $temp; }
			$temp = get_option('AdsAlign1'); if(isset($temp) && ($temp != '')) { $options['AdsAlign1'] = $temp; }
			$temp = get_option('AdsMargin1'); if(isset($temp) && ($temp != '')) { $options['AdsMargin1'] = $temp; }
			$temp = get_option('AdsCode2'); if(isset($temp)) { $options['AdsCode2'] = $temp; }
			$temp = get_option('AdsAlign2'); if(isset($temp) && ($temp != '')) { $options['AdsAlign2'] = $temp; }
			$temp = get_option('AdsMargin2'); if(isset($temp) && ($temp != '')) { $options['AdsMargin2'] = $temp; }
			$temp = get_option('AdsCode3'); if(isset($temp)) { $options['AdsCode3'] = $temp; }
			$temp = get_option('AdsAlign3'); if(isset($temp) && ($temp != '')) { $options['AdsAlign3'] = $temp; }
			$temp = get_option('AdsMargin3'); if(isset($temp) && ($temp != '')) { $options['AdsMargin3'] = $temp; }
			$temp = get_option('AdsCode4'); if(isset($temp)) { $options['AdsCode4'] = $temp; }
			$temp = get_option('AdsAlign4'); if(isset($temp) && ($temp != '')) { $options['AdsAlign4'] = $temp; }
			$temp = get_option('AdsMargin4'); if(isset($temp) && ($temp != '')) { $options['AdsMargin4'] = $temp; }
			$temp = get_option('AdsCode5'); if(isset($temp)) { $options['AdsCode5'] = $temp; }
			$temp = get_option('AdsAlign5'); if(isset($temp) && ($temp != '')) { $options['AdsAlign5'] = $temp; }
			$temp = get_option('AdsMargin5'); if(isset($temp) && ($temp != '')) { $options['AdsMargin5'] = $temp; }
			$temp = get_option('AdsCode6'); if(isset($temp)) { $options['AdsCode6'] = $temp; }
			$temp = get_option('AdsAlign6'); if(isset($temp) && ($temp != '')) { $options['AdsAlign6'] = $temp; }
			$temp = get_option('AdsMargin6'); if(isset($temp) && ($temp != '')) { $options['AdsMargin6'] = $temp; }
			$temp = get_option('AdsCode7'); if(isset($temp)) { $options['AdsCode7'] = $temp; }
			$temp = get_option('AdsAlign7'); if(isset($temp) && ($temp != '')) { $options['AdsAlign7'] = $temp; }
			$temp = get_option('AdsMargin7'); if(isset($temp) && ($temp != '')) { $options['AdsMargin7'] = $temp; }
			$temp = get_option('AdsCode8'); if(isset($temp)) { $options['AdsCode8'] = $temp; }
			$temp = get_option('AdsAlign8'); if(isset($temp) && ($temp != '')) { $options['AdsAlign8'] = $temp; }
			$temp = get_option('AdsMargin8'); if(isset($temp) && ($temp != '')) { $options['AdsMargin8'] = $temp; }
			$temp = get_option('AdsCode9'); if(isset($temp)) { $options['AdsCode9'] = $temp; }
			$temp = get_option('AdsAlign9'); if(isset($temp) && ($temp != '')) { $options['AdsAlign9'] = $temp; }
			$temp = get_option('AdsMargin9'); if(isset($temp) && ($temp != '')) { $options['AdsMargin9'] = $temp; }
			$temp = get_option('AdsCode10'); if(isset($temp)) { $options['AdsCode10'] = $temp; }
			$temp = get_option('AdsAlign10'); if(isset($temp) && ($temp != '')) { $options['AdsAlign10'] = $temp; }
			$temp = get_option('AdsMargin10'); if(isset($temp) && ($temp != '')) { $options['AdsMargin10'] = $temp; }
			$temp = get_option('WidCode1'); if(isset($temp)) { $options['WidCode1'] = $temp; }
			$temp = get_option('WidCode2'); if(isset($temp)) { $options['WidCode2'] = $temp; }
			$temp = get_option('WidCode3'); if(isset($temp)) { $options['WidCode3'] = $temp; }
			$temp = get_option('WidCode4'); if(isset($temp)) { $options['WidCode4'] = $temp; }
			$temp = get_option('WidCode5'); if(isset($temp)) { $options['WidCode5'] = $temp; }
			$temp = get_option('WidCode6'); if(isset($temp)) { $options['WidCode6'] = $temp; }
			$temp = get_option('WidCode7'); if(isset($temp)) { $options['WidCode7'] = $temp; }
			$temp = get_option('WidCode8'); if(isset($temp)) { $options['WidCode8'] = $temp; }
			$temp = get_option('WidCode9'); if(isset($temp)) { $options['WidCode9'] = $temp; }
			$temp = get_option('WidCode10'); if(isset($temp)) { $options['WidCode10'] = $temp; }
			delete_option('AdsDisp');
		} else {
			$options = array (
				'AdsDisp' => $QData['Default']['AdsDisp'],
				'BegnAds' => $QData['Default']['BegnAds'],
				'BegnRnd' => $QData['Default']['BegnRnd'],
				'MiddAds' => $QData['Default']['MiddAds'],
				'MiddRnd' => $QData['Default']['MiddRnd'],
				'EndiAds' => $QData['Default']['EndiAds'],
				'EndiRnd' => $QData['Default']['EndiRnd'],
				'MoreAds' => $QData['Default']['MoreAds'],
				'MoreRnd' => $QData['Default']['MoreRnd'],
				'LapaAds' => $QData['Default']['LapaAds'],
				'LapaRnd' => $QData['Default']['LapaRnd'],
				'Par1Ads' => $QData['Default']['Par1Ads'],
				'Par1Rnd' => $QData['Default']['Par1Rnd'],
				'Par1Nup' => $QData['Default']['Par1Nup'],
				'Par1Con' => $QData['Default']['Par1Con'],
				'Par2Ads' => $QData['Default']['Par2Ads'],
				'Par2Rnd' => $QData['Default']['Par2Rnd'],
				'Par2Nup' => $QData['Default']['Par2Nup'],
				'Par2Con' => $QData['Default']['Par2Con'],
				'Par3Ads' => $QData['Default']['Par3Ads'],
				'Par3Rnd' => $QData['Default']['Par3Rnd'],
				'Par3Nup' => $QData['Default']['Par3Nup'],
				'Par3Con' => $QData['Default']['Par3Con'],
				'Img1Ads' => $QData['Default']['Img1Ads'],
				'Img1Rnd' => $QData['Default']['Img1Rnd'],
				'Img1Nup' => $QData['Default']['Img1Nup'],
				'Img1Con' => $QData['Default']['Img1Con'],
				'AppPost' => $QData['Default']['AppPost'],
				'AppPage' => $QData['Default']['AppPage'],
				'AppHome' => $QData['Default']['AppHome'],
				'AppCate' => $QData['Default']['AppCate'],
				'AppArch' => $QData['Default']['AppArch'],
				'AppTags' => $QData['Default']['AppTags'],
				'AppMaxA' => $QData['Default']['AppMaxA'],
				'AppSide' => $QData['Default']['AppSide'],
				'AppLogg' => $QData['Default']['AppLogg'],
				'QckTags' => $QData['Default']['QckTags'],
				'QckRnds' => $QData['Default']['QckRnds'],
				'QckOffs' => $QData['Default']['QckOffs'],
				'QckOfPs' => $QData['Default']['QckOfPs'],
				'AdsCode1' => '',
				'AdsAlign1' => 2,
				'AdsMargin1' => 10,
				'AdsCode2' => '',
				'AdsAlign2' => 2,
				'AdsMargin2' => 10,
				'AdsCode3' => '',
				'AdsAlign3' => 2,
				'AdsMargin3' => 10,
				'AdsCode4' => '',
				'AdsAlign4' => 2,
				'AdsMargin4' => 10,
				'AdsCode5' => '',
				'AdsAlign5' => 2,
				'AdsMargin5' => 10,
				'AdsCode6' => '',
				'AdsAlign6' => 2,
				'AdsMargin6' => 10,
				'AdsCode7' => '',
				'AdsAlign7' => 2,
				'AdsMargin7' => 10,
				'AdsCode8' => '',
				'AdsAlign8' => 2,
				'AdsMargin8' => 10,
				'AdsCode9' => '',
				'AdsAlign9' => 2,
				'AdsMargin9' => 10,
				'AdsCode10' => '',
				'AdsAlign10' => 2,
				'AdsMargin10' => 10,
				'WidCode1' => '',
				'WidCode2' => '',
				'WidCode3' => '',
				'WidCode4' => '',
				'WidCode5' => '',
				'WidCode6' => '',
				'WidCode7' => '',
				'WidCode8' => '',
				'WidCode9' => '',
				'WidCode10' => ''
			);
		}
		update_option('quick_adsense_2_options', $options);
	}
	return $options;
}
?>