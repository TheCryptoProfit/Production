<?php 
$ShownAds = 0;
$AdsId = array();
$beginend = 0;
$wpvcomp = (bool)(version_compare($wp_version, '3.1', '>='));

if($wpvcomp) {
	add_action('admin_print_footer_scripts', 'quick_adsense_2_admin_print_footer_scripts');
}else{
	add_action('admin_head', 'quick_adsense_2_admin_print_footer_scripts');
}
function quick_adsense_2_admin_print_footer_scripts() { 
	global $QData;
	global $wpvcomp; 
	$options = quick_adsense_2_get_options_with_defaults();
	if (isset($options['QckTags']) && $options['QckTags']) { ?>
	<script type="text/javascript">
	wpvcomp = <?php echo(($wpvcomp==1)?"true":"false"); ?>;
	edaddID = new Array();
	edaddNm = new Array();
	if(typeof(edButtons)!='undefined') {
		edadd = edButtons.length;	
		var dynads={"all":[
			<?php for ($i=1;$i<=$QData['Ads'];$i++) { if($options['AdsCode'.$i]!=''){echo('"1",');}else{echo('"0",');}; } ?>
		"0"]};
		for(i=1;i<=<?php echo($QData['Ads']) ?>;i++) {
			if(dynads.all[i-1]=="1") {
				edButtons[edButtons.length]=new edButton("ads"+i.toString(),"Ads"+i.toString(),"\n<!--Ads"+i.toString()+"-->\n","","",-1);
				edaddID[edaddID.length] = "ads"+i.toString();
				edaddNm[edaddNm.length] = "Ads"+i.toString();
			}	
		}
		<?php if(!$options['QckRnds']){ ?>
			edButtons[edButtons.length]=new edButton("random_ads","RndAds","\n<!--RndAds-->\n","","",-1);
			edaddID[edaddID.length] = "random_ads";
			edaddNm[edaddNm.length] = "RndAds";
		<?php } ?>	
		<?php if(!$options['QckOffs']){ ?>
			edButtons[edButtons.length]=new edButton("no_ads","NoAds","\n<!--NoAds-->\n","","",-1);
			edaddID[edaddID.length] = "no_ads";
			edaddNm[edaddNm.length] = "NoAds";
			edButtons[edButtons.length]=new edButton("off_def","OffDef","\n<!--OffDef-->\n","","",-1);	
			edaddID[edaddID.length] = "off_def";
			edaddNm[edaddNm.length] = "OffDef";
			edButtons[edButtons.length]=new edButton("off_wid","OffWidget","\n<!--OffWidget-->\n","","",-1);	
			edaddID[edaddID.length] = "off_wid";
			edaddNm[edaddNm.length] = "OffWidget";				
		<?php } ?>
		<?php if(!$options['QckOfPs']){ ?>
			edButtons[edButtons.length]=new edButton("off_bgn","OffBegin","\n<!--OffBegin-->\n","","",-1);
			edaddID[edaddID.length] = "off_bgn";
			edaddNm[edaddNm.length] = "OffBegin";
			edButtons[edButtons.length]=new edButton("off_mid","OffMiddle","\n<!--OffMiddle-->\n","","",-1);
			edaddID[edaddID.length] = "off_mid";
			edaddNm[edaddNm.length] = "OffMiddle";
			edButtons[edButtons.length]=new edButton("off_end","OffEnd","\n<!--OffEnd-->\n","","",-1);
			edaddID[edaddID.length] = "off_end";
			edaddNm[edaddNm.length] = "OffEnd";				
			edButtons[edButtons.length]=new edButton("off_more","OffAfMore","\n<!--OffAfMore-->\n","","",-1);
			edaddID[edaddID.length] = "off_more";
			edaddNm[edaddNm.length] = "OffAfMore";				
			edButtons[edButtons.length]=new edButton("off_last","OffBfLastPara","\n<!--OffBfLastPara-->\n","","",-1);
			edaddID[edaddID.length] = "off_last";
			edaddNm[edaddNm.length] = "OffBfLastPara";								
		<?php } ?>			
	};
	(function(){
		if(typeof(edButtons)!='undefined' && typeof(jQuery)!='undefined' && wpvcomp){
			jQuery(document).ready(function(){
				for(i=0;i<edaddID.length;i++) {
					jQuery("#ed_toolbar").append('<input type="button" value="' + edaddNm[i] +'" id="' + edaddID[i] +'" class="ed_button" onclick="edInsertTag(edCanvas, ' + (edadd+i) + ');" title="' + edaddNm[i] +'" />');
				}
			});
		}
	}());	
	</script> 
	<?php	}
}

add_filter('the_content', 'quick_adsense_2_the_content');
function quick_adsense_2_the_content($content) {
	global $QData;
	global $ShownAds;
	global $AdsId;
	global $beginend;
	$options = quick_adsense_2_get_options_with_defaults();
	
	/* verifying */ 
	if(	(is_feed()) ||
			(strpos($content,'<!--NoAds-->')!==false) ||
			(strpos($content,'<!--OffAds-->')!==false) ||
			(is_single() && !(isset($options['AppPost']))) ||
			(is_page() && !(isset($options['AppPage']))) ||
			(is_home() && !(isset($options['AppHome']))) ||			
			(is_category() && !(isset($options['AppCate']))) ||
			(is_archive() && !(isset($options['AppArch']))) ||
			(is_tag() && !(isset($options['AppTags']))) ||
			(is_user_logged_in() && (isset($options['AppLogg']))) ) { 
		$content = quick_adsense_2_clean_tags($content); return $content; 
	}
	
	$AdsToShow = $options['AdsDisp'];
	if (strpos($content,'<!--OffWidget-->')===false) {
		for($i=1;$i<=$QData['AdsWid'];$i++) {
			$wadsid = sanitize_title(str_replace(array('(',')'),'',sprintf($QData['AdsWidName'],$i)));
			$AdsToShow -= (is_active_widget(true, $wadsid)) ? 1 : 0 ;
		}		
	}
	if( $ShownAds >= $AdsToShow ) { $content = quick_adsense_2_clean_tags($content); return $content; };

	if( !count($AdsId) ) {  
		for($i=1;$i<=$QData['Ads'];$i++) { 
			$tmp = trim(((isset($options['AdsCode'.$i]))?$options['AdsCode'.$i]:''));
			if( !empty($tmp) ) {
				array_push($AdsId, $i);
			}
		}
	}	
	if( !count($AdsId) ) { $content = quick_adsense_2_clean_tags($content); return $content; };

	/* ... Tidy up content ... */
	$content = str_replace("<p></p>", "##QA-TP1##", $content);
	$content = str_replace("<p>&nbsp;</p>", "##QA-TP2##", $content);	
	$offdef = (strpos($content,'<!--OffDef-->')!==false);
	if( !$offdef ) {
		$AdsIdCus = array();
		$cusads = 'CusAds'; $cusrnd = 'CusRnd';
		$more1 = ((isset($options['MoreAds']))?$options['MoreAds']:''); $more2 = ((isset($options['MoreRnd']))?$options['MoreRnd']:'');	
		$lapa1 = ((isset($options['LapaAds']))?$options['LapaAds']:''); $lapa2 = ((isset($options['LapaRnd']))?$options['LapaRnd']:'');		
		$begn1 = ((isset($options['BegnAds']))?$options['BegnAds']:''); $begn2 = ((isset($options['BegnRnd']))?$options['BegnRnd']:'');
		$midd1 = ((isset($options['MiddAds']))?$options['MiddAds']:''); $midd2 = ((isset($options['MiddRnd']))?$options['MiddRnd']:'');
		$endi1 = ((isset($options['EndiAds']))?$options['EndiAds']:'');	$endi2 = ((isset($options['EndiRnd']))?$options['EndiRnd']:'');
		$rc=3;
		for($i=1;$i<=$rc;$i++) { 
			$para1[$i] = ((isset($options['Par'.$i.'Ads']))?$options['Par'.$i.'Ads']:'');
			$para2[$i] = ((isset($options['Par'.$i.'Rnd']))?$options['Par'.$i.'Rnd']:'');
			$para3[$i] = ((isset($options['Par'.$i.'Nup']))?$options['Par'.$i.'Nup']:'');
			$para4[$i] = ((isset($options['Par'.$i.'Con']))?$options['Par'.$i.'Con']:'');
		}
		$imge1 = ((isset($options['Img1Ads']))?$options['Img1Ads']:'');
		$imge2 = ((isset($options['Img1Rnd']))?$options['Img1Rnd']:'');
		$imge3 = ((isset($options['Img1Nup']))?$options['Img1Nup']:'');
		$imge4 = ((isset($options['Img1Con']))?$options['Img1Con']:'');		
		if ( $begn2 == 0 ) { $b1 = $cusrnd; } else { $b1 = $cusads.$begn2; array_push($AdsIdCus, $begn2); };
		if ( $more2 == 0 ) { $r1 = $cusrnd; } else { $r1 = $cusads.$more2; array_push($AdsIdCus, $more2); };		
		if ( $midd2 == 0 ) { $m1 = $cusrnd; } else { $m1 = $cusads.$midd2; array_push($AdsIdCus, $midd2); };
		if ( $lapa2 == 0 ) { $g1 = $cusrnd; } else { $g1 = $cusads.$lapa2; array_push($AdsIdCus, $lapa2); };
		if ( $endi2 == 0 ) { $b2 = $cusrnd; } else { $b2 = $cusads.$endi2; array_push($AdsIdCus, $endi2); };	
		for($i=1;$i<=$rc;$i++) { 
			if ( $para2[$i] == 0 ) { $b3[$i] = $cusrnd; } else { $b3[$i] = $cusads.$para2[$i]; array_push($AdsIdCus, $para2[$i]); };	
		}	
		if ( $imge2 == 0 ) { $b4 = $cusrnd; } else { $b4 = $cusads.$imge2; array_push($AdsIdCus, $imge2); };	
		if( $midd1 && strpos($content,'<!--OffMiddle-->')===false) {
			if( substr_count(strtolower($content), '</p>')>=2 ) {
				$sch = "</p>";
				$content = str_replace("</P>", $sch, $content);
				$arr = explode($sch, $content);			
				$nn = 0; $mm = strlen($content)/2;
				for($i=0;$i<count($arr);$i++) {
					$nn += strlen($arr[$i]) + 4;
					if($nn>$mm) {
						if( ($mm - ($nn - strlen($arr[$i]))) > ($nn - $mm) && $i+1<count($arr) ) {
							$arr[$i+1] = '<!--'.$m1.'-->'.$arr[$i+1];							
						} else {
							$arr[$i] = '<!--'.$m1.'-->'.$arr[$i];
						}
						break;
					}
				}
				$content = implode($sch, $arr);
			}	
		}
		if( $more1 && strpos($content,'<!--OffAfMore-->')===false) {
			$mmr = '<!--'.$r1.'-->';
			$postid = get_the_ID();
			$content = str_replace('<span id="more-'.$postid.'"></span>', $mmr, $content);		
		}		
		if( $begn1 && strpos($content,'<!--OffBegin-->')===false) {
			$content = '<!--'.$b1.'-->'.$content;
		}
		if( $endi1 && strpos($content,'<!--OffEnd-->')===false) {
			$content = $content.'<!--'.$b2.'-->';
		}
		if( $lapa1 && strpos($content,'<!--OffBfLastPara-->')===false){
			$sch = "<p>";
			$content = str_replace("<P>", $sch, $content);
			$arr = explode($sch, $content);
			if ( count($arr) > 2 ) {
				$content = implode($sch, array_slice($arr, 0, count($arr)-1)) .'<!--'.$g1.'-->'. $sch. $arr[count($arr)-1];
			}
		}
		for($i=$rc;$i>=1;$i--) { 
			if ( $para1[$i] ){
				$sch = "</p>";
				$content = str_replace("</P>", $sch, $content);
				$arr = explode($sch, $content);
				if ( (int)$para3[$i] < count($arr) ) {
					$content = implode($sch, array_slice($arr, 0, $para3[$i])).$sch .'<!--'.$b3[$i].'-->'. implode($sch, array_slice($arr, $para3[$i]));
				}	elseif ($para4[$i]) {
					$content = implode($sch, $arr).'<!--'.$b3[$i].'-->';
				}
			}
		}	
		if ( $imge1 ){
			$sch = "<img"; $bch = ">"; $cph = "[/caption]"; $csa = "</a>";			
			$content = str_replace("<IMG", $sch, $content);
			$content = str_replace("</A>", $csa, $content);			
			$arr = explode($sch, $content);
			if ( (int)$imge3 < count($arr) ) {
				$trr = explode($bch, $arr[$imge3]);
				if ( count($trr) > 1 ) {
					$tss = explode($cph, $arr[$imge3]);
					$ccp = ( count($tss) > 1 ) ? strpos(strtolower($tss[0]),'[caption ')===false : false ;
					$tuu = explode($csa, $arr[$imge3]);
					$cdu = ( count($tuu) > 1 ) ? strpos(strtolower($tuu[0]),'<a href')===false : false ;					
					if ( $imge4 && $ccp ) {
						$arr[$imge3] = implode($cph, array_slice($tss, 0, 1)).$cph. "\r\n".'<!--'.$b4.'-->'."\r\n". implode($cph, array_slice($tss, 1));
					}else if ( $cdu ) {	
						$arr[$imge3] = implode($csa, array_slice($tuu, 0, 1)).$csa. "\r\n".'<!--'.$b4.'-->'."\r\n". implode($csa, array_slice($tuu, 1));
					}else{
						$arr[$imge3] = implode($bch, array_slice($trr, 0, 1)).$bch. "\r\n".'<!--'.$b4.'-->'."\r\n". implode($bch, array_slice($trr, 1));
					}
				}
				$content = implode($sch, $arr);
			}	
		}		
	}
	
	/* ... Tidy up content ... */
	$content = '<!--EmptyClear-->'.$content."\n".'<div style="font-size:0px;height:0px;line-height:0px;margin:0;padding:0;clear:both"></div>';
	$content = quick_adsense_2_clean_tags($content, true);	
	$ismany = (!is_single() && !is_page());
	$showall = ((isset($options['AppMaxA']))?$options['AppMaxA']:'');
	
	/* ... Replace Beginning/Middle/End Ads1-10 ... */
	if( !$offdef ) {
		for( $i=1; $i<=count($AdsIdCus); $i++ ) {
			if( $showall || !$ismany || $beginend != $i ) {
				if( strpos($content,'<!--'.$cusads.$AdsIdCus[$i-1].'-->')!==false && in_array($AdsIdCus[$i-1], $AdsId)) {
					$content = quick_adsense_2_replace_ads( $content, $cusads.$AdsIdCus[$i-1], $AdsIdCus[$i-1] ); $AdsId = quick_adsense_2_del_element($AdsId, array_search($AdsIdCus[$i-1], $AdsId)) ;
					$ShownAds += 1; if( $ShownAds >= $AdsToShow || !count($AdsId) ){ $content = quick_adsense_2_clean_tags($content); return $content; };
					$beginend = $i; if(!$showall && $ismany){break;} 
				}
			}	
		}	
	}
	
	/* ... Replace Ads1 to Ads10 ... */
	if( $showall || !$ismany ) {
		$tcn = count($AdsId); $tt = 0;
		for( $i=1; $i<=$tcn; $i++ ) {
			if( strpos($content, '<!--Ads'.$AdsId[$tt].'-->')!==false ) {
				$content = quick_adsense_2_replace_ads( $content, 'Ads'.$AdsId[$tt], $AdsId[$tt] ); $AdsId = quick_adsense_2_del_element($AdsId, $tt) ;
				$ShownAds += 1; if( $ShownAds >= $AdsToShow || !count($AdsId) ){ $content = quick_adsense_2_clean_tags($content); return $content; };
			} else {
				$tt += 1;
			}
		}	
	}	

	/* ... Replace Beginning/Middle/End random Ads ... */
	if( strpos($content, '<!--'.$cusrnd.'-->')!==false && ($showall || !$ismany) ) {
		$tcx = count($AdsId);
		$tcy = substr_count($content, '<!--'.$cusrnd.'-->');
		for( $i=$tcx; $i<=$tcy-1; $i++ ) {
			array_push($AdsId, -1);
		}
		shuffle($AdsId);
		for( $i=1; $i<=$tcy; $i++ ) {
			$content = quick_adsense_2_replace_ads( $content, $cusrnd, $AdsId[0] ); $AdsId = quick_adsense_2_del_element($AdsId, 0) ;
			$ShownAds += 1; if( $ShownAds >= $AdsToShow || !count($AdsId) ){ $content = quick_adsense_2_clean_tags($content); return $content; };
		}
	}
	
	/* ... Replace RndAds ... */
	if( strpos($content, '<!--RndAds-->')!==false && ($showall || !$ismany) ) {
		$AdsIdTmp = array();
		shuffle($AdsId);
		for( $i=1; $i<=$AdsToShow-$ShownAds; $i++ ) {
			if( $i <= count($AdsId) ) {
				array_push($AdsIdTmp, $AdsId[$i-1]);
			}
		}
		$tcx = count($AdsIdTmp);
		$tcy = substr_count($content, '<!--RndAds-->');
 		for( $i=$tcx; $i<=$tcy-1; $i++ ) {
			array_push($AdsIdTmp, -1);
		}
		shuffle($AdsIdTmp);
		for( $i=1; $i<=$tcy; $i++ ) {
			$tmp = $AdsIdTmp[0];
			$content = quick_adsense_2_replace_ads( $content, 'RndAds', $AdsIdTmp[0] ); $AdsIdTmp = quick_adsense_2_del_element($AdsIdTmp, 0) ;
			if($tmp != -1){$ShownAds += 1;}; if( $ShownAds >= $AdsToShow || !count($AdsIdTmp) ){ $content = quick_adsense_2_clean_tags($content); return $content; };
		}
	}	

	/* ... That's it. DONE :) ... */
	$content = quick_adsense_2_clean_tags($content); return $content;
}

function quick_adsense_2_clean_tags($content, $trimonly = false) {
	global $QData;
	global $ShownAds;
	global $AdsId;
	global $beginend;
	$tagnames = array('EmptyClear','RndAds','NoAds','OffDef','OffAds','OffWidget','OffBegin','OffMiddle','OffEnd','OffBfMore','OffAfLastPara','CusRnd');
	for($i=1;$i<=$QData['Ads'];$i++) { array_push($tagnames, 'CusAds'.$i); array_push($tagnames, 'Ads'.$i); };
	foreach ($tagnames as $tgn) {
		if(strpos($content,'<!--'.$tgn.'-->')!==false || $tgn=='EmptyClear') {
			if($trimonly) {
				$content = str_replace('<p><!--'.$tgn.'--></p>', '<!--'.$tgn.'-->', $content);	
			}else{
				$content = str_replace(array('<p><!--'.$tgn.'--></p>','<!--'.$tgn.'-->'), '', $content);	
				$content = str_replace("##QA-TP1##", "<p></p>", $content);
				$content = str_replace("##QA-TP2##", "<p>&nbsp;</p>", $content);
			}
		}
	}
	if(!$trimonly && (is_single() || is_page()) ) {
		$ShownAds = 0;
		$AdsId = array();
		$beginend = 0;
	}	
	return $content;
}

function quick_adsense_2_replace_ads($content, $nme, $adn) {
	if( strpos($content,'<!--'.$nme.'-->')===false ) { return $content; }	
	global $QData;
	$options = quick_adsense_2_get_options_with_defaults();
	if ($adn != -1) {
		$arr = array('',
			'float:left;margin:%1$dpx %1$dpx %1$dpx 0;',
			'float:none;margin:%1$dpx 0 %1$dpx 0;text-align:center;',
			'float:right;margin:%1$dpx 0 %1$dpx %1$dpx;',
			'float:none;margin:0px;');
		$adsalign = ((isset($options['AdsAlign'.$adn]))?$options['AdsAlign'.$adn]:'');
		$adsmargin = ((isset($options['AdsMargin'.$adn]))?$options['AdsMargin'.$adn]:'');
		$style = sprintf($arr[(int)$adsalign], $adsmargin);
		$adscode = ((isset($options['AdsCode'.$adn]))?$options['AdsCode'.$adn]:'');
		$adscode =
			"\n".'<!-- '.$QData['Name'].' Wordpress Plugin: '.$QData['URI'].' -->'."\n".
			'<div style="'.$style.'">'."\n".
			$adscode."\n".
			'</div>'."\n";
	} else {
		$adscode ='';
	}	
	$cont = explode('<!--'.$nme.'-->', $content, 2);	
	return $cont[0].$adscode.$cont[1];
}

function quick_adsense_2_del_element($array, $idx) {
	$copy = array();
	for( $i=0; $i<count($array) ;$i++) {
		if ( $idx != $i ) {
			array_push($copy, $array[$i]);
		}
	}	
	return $copy;
}

add_action('init', 'quick_adsense_2_ads_widget_register');
function quick_adsense_2_ads_widget_register() {
	global $QData;
	$options = quick_adsense_2_get_options_with_defaults();
	if (!function_exists('wp_register_sidebar_widget')) { return; };
	for($i=1;$i<=$QData['AdsWid'];$i++) {
		if(isset($options['WidCode'.$i]) && ($options['WidCode'.$i]!='')) {
			$displaystr =
				'$cont = get_the_content();'.
				'$options = quick_adsense_2_get_options_with_defaults();'.
				'if( strpos($cont,"<!--OffAds-->")===false && strpos($cont,"<!--OffWidget-->")===false && !(is_home()&&$options["AppSide"]) ) {'.
				'extract($args);'.
				'$title = ((isset($options["WidCode-title-'.$i.'"]))?$options["WidCode-title-'.$i.'"]:"");'.
				'$codetxt = $options["WidCode'.$i.'"];'.
				'echo "\n"."<!-- Quick Adsense Wordpress Plugin: http://quickadsense.com/ -->"."\n";'.
				'echo $before_widget."\n";'.
				'if (!empty($title)) { '.
				'echo $before_title.$title.$after_title."\n"; '.
				'};'.
				'echo $codetxt;'.
				'echo "\n".$after_widget;'.
				'}';
			$displaycall[$i] = create_function('$args', $displaystr);
			$wadnam = sprintf($QData['AdsWidName'],$i);
			$wadsid = sanitize_title(str_replace(array('(',')'),'',$wadnam));
			wp_register_sidebar_widget($wadsid, $wadnam, $displaycall[$i], array('description' => 'Quick Adsense on Sidebar Widget'));
		}			
	}
}
?>