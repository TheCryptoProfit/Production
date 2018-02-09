<?php
add_action('admin_menu', 'quick_adsense_2_add_menu');
function quick_adsense_2_add_menu() {
	$page = add_options_page('Quick Adsense Options', 'Quick Adsense', 'manage_options', 'quick-adsense-2', 'quick_adsense_2_settings_page');
}

add_action('admin_init', 'quick_adsense_2_admin_init');
function quick_adsense_2_admin_init() {	
	register_setting('quick_adsense_2_options', 'quick_adsense_2_options', 'quick_adsense_2_validate');
    add_settings_section('quick_adsense_2_main', '', 'quick_adsense_2_section_text', 'quick-adsense-2');
}

add_filter('plugin_action_links', 'quick_adsense_2_plugin_action_links', 10, 2);
function quick_adsense_2_plugin_action_links($links, $file) {
	if($file == 'quick-adsense/quick-adsense.php') {
		array_unshift($links,'<a href="options-general.php?page=quick-adsense-2">'.__('Setting').'</a>');
	}
	return $links;
}

function quick_adsense_2_settings_page() { ?>
    <div class="wrap">
		<h2>Quick Adsense Setting <span style="font-size: 9pt; font-style: italic">( Version 2.0.1 )</span></h2>
		<form method="post" action="options.php" name="wp_auto_commenter_form">
			<?php settings_fields('quick_adsense_2_options'); ?>
			<?php do_settings_sections('quick-adsense-2'); ?>
			<?php submit_button(); ?>
		</form>
    </div>
<?php
}

function quick_adsense_2_section_text() {
	global $QData;
	$options = quick_adsense_2_get_options_with_defaults();
?>
	<script type="text/javascript">
	function defaultoptions() {
		document.getElementById("DisTot<?php echo($QData['Default']['AdsDisp']) ?>").selected = true;
		document.getElementById("BegnAds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['BegnAds'])) ?>;
		document.getElementById("BegnRnd").selectedIndex = <?php echo($QData['Default']['BegnRnd']) ?>;
		document.getElementById("MiddAds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['MiddAds'])) ?>;
		document.getElementById("MiddRnd").selectedIndex = <?php echo($QData['Default']['MiddRnd']) ?>;		
		document.getElementById("EndiAds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['EndiAds'])) ?>;
		document.getElementById("EndiRnd").selectedIndex = <?php echo($QData['Default']['EndiRnd']) ?>;		
		document.getElementById("MoreAds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['MoreAds'])) ?>;
		document.getElementById("MoreRnd").selectedIndex = <?php echo($QData['Default']['MoreRnd']) ?>;
		document.getElementById("LapaAds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['LapaAds'])) ?>;
		document.getElementById("LapaRnd").selectedIndex = <?php echo($QData['Default']['LapaRnd']) ?>;			
		<?php for ($j=1;$j<=3;$j++) { ?>	
			document.getElementById("Par<?php echo $j; ?>Ads").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['Par'.$j.'Ads'])) ?>;
			document.getElementById("Par<?php echo $j; ?>Rnd").selectedIndex = <?php echo($QData['Default']['Par'.$j.'Rnd']) ?>;		
			document.getElementById("Par<?php echo $j; ?>Nup").selectedIndex = <?php echo($QData['Default']['Par'.$j.'Nup']) ?>;			
			document.getElementById("Par<?php echo $j; ?>Con").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['Par'.$j.'Con'])) ?>;	
		<?php } ?>
		document.getElementById("Img1Ads").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['Img1Ads'])) ?>;
		document.getElementById("Img1Rnd").selectedIndex = <?php echo($QData['Default']['Img1Rnd']) ?>;		
		document.getElementById("Img1Nup").selectedIndex = <?php echo($QData['Default']['Img1Nup']) ?>;	
		document.getElementById("Img1Con").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['Img1Con'])) ?>;		
		document.getElementById("AppHome").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppHome'])) ?>;
		document.getElementById("AppPost").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppPost'])) ?>;
		document.getElementById("AppPage").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppPage'])) ?>;
		document.getElementById("AppCate").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppCate'])) ?>;
		document.getElementById("AppArch").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppArch'])) ?>;
		document.getElementById("AppTags").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppTags'])) ?>;
		document.getElementById("AppMaxA").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppMaxA'])) ?>;		
		document.getElementById("AppSide").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppSide'])) ?>;		
		document.getElementById("AppLogg").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['AppLogg'])) ?>;		
		document.getElementById("QckTags").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['QckTags'])) ?>;
		document.getElementById("QckRnds").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['QckRnds'])) ?>;
		document.getElementById("QckOffs").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['QckOffs'])) ?>;		
		document.getElementById("QckOfPs").checked = <?php echo(quick_adsense_2_truefalse($QData['Default']['QckOfPs'])) ?>;		
		for(i=1;i<=<?php echo($QData['Ads']) ?>;i++){
			tp=document.getElementById("AdsCode"+i.toString()).innerHTML;
			if(tp==''){
				document.getElementById("AdsMargin"+i.toString()).value = "<?php echo($QData['DefaultAdsOpt']['AdsMargin']) ?>";
				document.getElementById("OptAgn"+i.toString()+"<?php echo($QData['DefaultAdsOpt']['AdsAlign']) ?>").selected = true;
			}
		}		
		deftcheckinfo();
	}
	
	function selectinfo(ts) {
		if (ts.selectedIndex == 0) { return; }
		cek = new Array(
			document.getElementById('BegnRnd'),
			document.getElementById('MiddRnd'),
			document.getElementById('EndiRnd'),
			document.getElementById('MoreRnd'),
			document.getElementById('LapaRnd'),				
			document.getElementById('Par1Rnd'),
			document.getElementById('Par2Rnd'),
			document.getElementById('Par3Rnd'),
			document.getElementById('Img1Rnd') );
		for (i=0;i<cek.length;i++) {
			if (ts != cek[i] && ts.selectedIndex == cek[i].selectedIndex) {
				cek[i].selectedIndex = 0;
			}
		}
	}
	
	function checkinfo1(selnme,ts) {
		document.getElementById(selnme).disabled=!ts.checked;
	}
	
	function checkinfo2(ts,selnm1,selnm2,selnm3,selnm4) {
		if(selnm1){document.getElementById(selnm1).disabled=!ts.checked};
		if(selnm2){document.getElementById(selnm2).disabled=!ts.checked};		
		if(selnm3){document.getElementById(selnm3).disabled=!ts.checked};		
	}
	
	function deftcheckinfo() {	
		checkinfo1('BegnRnd',document.getElementById('BegnAds'));
		checkinfo1('MiddRnd',document.getElementById('MiddAds'));
		checkinfo1('EndiRnd',document.getElementById('EndiAds'));
		checkinfo1('MoreRnd',document.getElementById('MoreAds'));
		checkinfo1('LapaRnd',document.getElementById('LapaAds'));		
		for (i=1;i<=3;i++) {
			checkinfo2(document.getElementById('Par'+i+'Ads'),'Par'+i+'Rnd','Par'+i+'Nup','Par'+i+'Con');		
		}	
		checkinfo2(document.getElementById('Img1Ads'),'Img1Rnd','Img1Nup','Img1Con');				
	}	
	</script>
	<h3 style="font-size: 120%"><?php _e('Options'); ?></h3>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
			<td style="width: 110px"><?php _e('Adsense :'); ?></td>
			<td><?php _e('Place up to '); ?>
				<?php 
				if(!$options['AdsDisp']&&is_bool($options['AdsDisp'])) {
					$options['AdsDisp'] = $QData['Default']['AdsDisp'];
				}
				?>
				<select name="quick_adsense_2_options[AdsDisp]" style="width: 50px; font-weight: bold;">
					<?php for ($i = 0; $i <= (int)$QData['Ads']; $i++) { ?>
						<option id="DisTot<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['AdsDisp']) && $options['AdsDisp']==(string)$i){echo('selected');} ?>><?php echo $i; ?></option>
					<?php } ?>
				</select><?php _e(' Ads on a page.'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td style="width:110px"><?php _e('Position :<br/>(Default)'); ?></td>
			<td>
				<input type="checkbox" id="BegnAds" name="quick_adsense_2_options[BegnAds]" value="true" <?php if(isset($options['BegnAds'])){echo('checked');} ?> onchange="checkinfo1('BegnRnd',this)" /> 
				<?php _e('Assign') ; ?> 
				<select id="BegnRnd" name="quick_adsense_2_options[BegnRnd]" onchange="selectinfo(this)">
					<?php for ($i=0;$i<=$QData['Ads'];$i++) { ?>
						<option id="OptBegn<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['BegnRnd']) && $options['BegnRnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('to <b>Beginning of Post</b>') ?><br/>
				
				<input type="checkbox" id="MiddAds" name="quick_adsense_2_options[MiddAds]" value="false" <?php if(isset($options['MiddAds'])){echo('checked');} ?> onchange="checkinfo1('MiddRnd',this)" /> 
				<?php _e('Assign') ; ?> 
				<select id="MiddRnd" name="quick_adsense_2_options[MiddRnd]" onchange="selectinfo(this)">
					<?php for ($i=0;$i<=$QData['Ads'];$i++) { ?>
						<option id="OptMidd<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['MiddRnd']) && $options['MiddRnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('to <b>Middle of Post</b>') ?><br/>					
					
				<input type="checkbox" id="EndiAds" name="quick_adsense_2_options[EndiAds]" value="false" <?php if(isset($options['EndiAds'])){echo('checked');} ?> onchange="checkinfo1('EndiRnd',this)" /> 
				<?php _e('Assign') ; ?> 
				<select id="EndiRnd" name="quick_adsense_2_options[EndiRnd]" onchange="selectinfo(this)">
					<?php for ($i=0;$i<=$QData['Ads'];$i++) { ?>
						<option id="OptEndi<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['EndiRnd']) && $options['EndiRnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('to <b>End of Post</b>') ?><br/> 
				
				<input type="checkbox" id="MoreAds" name="quick_adsense_2_options[MoreAds]" value="false" <?php if(isset($options['MoreAds'])){echo('checked');} ?> onchange="checkinfo1('MoreRnd',this)" /> 
				<?php _e('Assign') ; ?> 
				<select id="MoreRnd" name="quick_adsense_2_options[MoreRnd]" onchange="selectinfo(this)">
					<?php for ($i=0;$i<=$QData['Ads'];$i++) { ?>
						<option id="OptMore<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['MoreRnd']) && $options['MoreRnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('right after <b>the') ?> 
				<span style="font-family:Courier New,Courier,Fixed;">&lt;!--more--&gt;</span> <?php _e('tag') ?></b><br/> 
				
				<input type="checkbox" id="LapaAds" name="quick_adsense_2_options[LapaAds]" value="false" <?php if(isset($options['LapaAds'])){echo('checked');} ?> onchange="checkinfo1('LapaRnd',this)" /> 
				<?php _e('Assign') ; ?> 
				<select id="LapaRnd" name="quick_adsense_2_options[LapaRnd]" onchange="selectinfo(this)">
					<?php for ($i=0;$i<=$QData['Ads'];$i++) { ?>
						<option id="OptLapa<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['LapaRnd']) && $options['LapaRnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('right before <b>the last Paragraph</b>') ?><span style="color:#a00;"> <b>(New)</b></span><br/> 
					
				<?php for($j = 1; $j <= 3; $j++) { ?>	
					<input type="checkbox" id="Par<?php echo $j; ?>Ads" name="quick_adsense_2_options[Par<?php echo $j; ?>Ads]" value="false" <?php if(isset($options['Par'.$j.'Ads'])){echo('checked');} ?> onchange="checkinfo2(this,'Par<?php echo $j; ?>Rnd','Par<?php echo $j; ?>Nup','Par<?php echo $j; ?>Con')" /> 
					<?php _e('Assign') ; ?> 
					<select id="Par<?php echo $j; ?>Rnd" name="quick_adsense_2_options[Par<?php echo $j; ?>Rnd]" onchange="selectinfo(this)">
						<?php for ($i = 0; $i <= $QData['Ads']; $i++) { ?>
							<option id="OptPar<?php echo $j; ?><?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['Par'.$j.'Rnd']) && $options['Par'.$j.'Rnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
						<?php } ?>
					</select> 
					
					<?php _e('<b>After Paragraph</b> ') ?> 
					<select id="Par<?php echo $j; ?>Nup" name="quick_adsense_2_options[Par<?php echo $j; ?>Nup]">
						<?php for ($i=1;$i<=50;$i++) { ?>
							<option id="Opt<?php echo $j; ?>Nu<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['Par'.$j.'Nup']) && $options['Par'.$j.'Nup']==(string)$i){echo('selected');} ?>><?php echo $i; ?></option>
						<?php } ?>
					</select> &rarr; 
					
					<input type="checkbox" id="Par<?php echo $j; ?>Con" name="quick_adsense_2_options[Par<?php echo $j; ?>Con]" value="false" <?php if(isset($options['Par'.$j.'Con'])){echo('checked');} ?> /> 
					<?php _e('to <b>End of Post</b> if fewer paragraphs are found.') ; ?><br/>
				<?php } ?>
				
				<input type="checkbox" id="Img1Ads" name="quick_adsense_2_options[Img1Ads]" value="false" <?php if(isset($options['Img1Ads'])){echo('checked');} ?> onchange="checkinfo2(this,'Img1Rnd','Img1Nup','Img1Con')" /> 
				<?php _e('Assign') ; ?> 
				<select id="Img1Rnd" name="quick_adsense_2_options[Img1Rnd]" onchange="selectinfo(this)">
					<?php for ($i = 0; $i <= $QData['Ads']; $i++) { ?>
						<option id="OptImg1<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['Img1Rnd']) && $options['Img1Rnd']==(string)$i){echo('selected');} ?>><?php _e(($i==0)?'Random Ads':'Ads'.$i) ; ?></option>
					<?php } ?>
				</select> 
				<?php _e('<b>After Image</b> ') ?> 
				<select id="Img1Nup" name="quick_adsense_2_options[Img1Nup]">
					<?php for ($i = 1; $i <= 50; $i++) { ?>
						<option id="Opt1Im<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if(isset($options['Img1Nup']) && $options['Img1Nup']==(string)$i){echo('selected');} ?>><?php echo $i; ?></option>
					<?php } ?>
				</select> &rarr; 
				<input type="checkbox" id="Img1Con" name="quick_adsense_2_options[Img1Con]" value="false" <?php if(isset($options['Img1Con'])){echo('checked');} ?> /> 
				<?php _e('after <b>Image&#39;s outer</b>'); ?>
				<b><span style="font-family:Courier New,Courier,Fixed;"> &lt;div&gt; wp-caption</span></b> if any.<span style="color:#a00;"> <b>(New)</b></span><br/><br/>
				<script type="text/javascript">deftcheckinfo();</script>
			</td>
		</tr>
		<tr valign="top">
			<td style="width:110px"><?php _e('Appearance :'); ?></td>
			<td>
				<span>[ </span>
				<input type="checkbox" id="AppPost" name="quick_adsense_2_options[AppPost]" value="true" <?php if(isset($options['AppPost'])){echo('checked');} ?> /> <?php _e('Posts'); ?>
				<input type="checkbox" id="AppPage" name="quick_adsense_2_options[AppPage]" value="true" <?php if(isset($options['AppPage'])){echo('checked');} ?> /> <?php _e('Pages'); ?>
				<span> ]</span><br/>
				<span>[ </span>
				<input type="checkbox" id="AppHome" name="quick_adsense_2_options[AppHome]" value="true" <?php if(isset($options['AppHome'])){echo('checked');} ?> /> <?php _e('Homepage'); ?>				
				<input type="checkbox" id="AppCate" name="quick_adsense_2_options[AppCate]" value="true" <?php if(isset($options['AppCate'])){echo('checked');} ?> /> <?php _e('Categories'); ?>
				<input type="checkbox" id="AppArch" name="quick_adsense_2_options[AppArch]" value="true" <?php if(isset($options['AppArch'])){echo('checked');} ?> /> <?php _e('Archives'); ?>
				<input type="checkbox" id="AppTags" name="quick_adsense_2_options[AppTags]" value="true" <?php if(isset($options['AppTags'])){echo('checked');} ?> /> <?php _e('Tags'); ?>
				<span> ] &rarr; </span>
				<input type="checkbox" id="AppMaxA" name="quick_adsense_2_options[AppMaxA]" value="true" <?php if(isset($options['AppMaxA'])){echo('checked');} ?> /> <?php _e('Place all possible Ads on these pages.'); ?><br/>
				<span>[ </span>
				<input type="checkbox" id="AppSide" name="quick_adsense_2_options[AppSide]" value="true" <?php if(isset($options['AppSide'])){echo('checked');} ?> /> <?php _e('Disable AdsWidget on Homepage'); ?>
				<span> ]</span><br/>
				<span>[ </span>				
				<input type="checkbox" id="AppLogg" name="quick_adsense_2_options[AppLogg]" value="true" <?php if(isset($options['AppLogg'])){echo('checked');} ?> /> <?php _e('Hide Ads when user is logged in to Wordpress'); ?>
				<span> ]</span><br/><br/>
			</td>
		</tr>	
		<tr valign="top">
			<td style="width:110px"><?php _e('Quicktag :'); ?></td>
			<td>
				<span style="display:block;font-style:normal;padding-bottom:0px"><?php _e('Insert Ads into a post, on-the-fly :'); ?></span>
				<ol style="margin-top:5px;">
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--Ads1--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--Ads2--&gt;</span>, etc. into a post to show the <b>Particular Ads</b> at specific location.'); ?></li>
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--RndAds--&gt;</span> (or more) into a post to show the <b>Random Ads</b> at specific location.'); ?></li>
				</ol>
				<span style="display:block;font-style:normal;padding-bottom:0px"><?php _e('Disable Ads in a post, on-the-fly :'); ?></span>
				<ol style="margin-top:5px;">				
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--NoAds--&gt;</span> to <b>disable all Ads</b> in a post.'); ?><span class="description" style="font-style:italic"><?php _e(' (does not affect Ads on Sidebar)'); ?></span></li>				
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffDef--&gt;</span> to <b>disable the default positioned Ads</b>, and use <span style="font-family:Courier New,Courier,Fixed;">&lt;!--Ads1--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;">&lt;!--Ads2--&gt;</span>, etc. to insert Ads.'); ?><span class="description" style="font-style:italic"><?php _e(' (does not affect Ads on Sidebar)'); ?></span></li>								
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffWidget--&gt;</span> to <b>disable all Ads on Sidebar</b>.'); ?><span style="color:#a00;"> <b>(New)</b></span></li>								
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffBegin--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffMiddle--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffEnd--&gt;</span> to <b>disable Ads at Beginning, Middle</b> or <b>End of Post</b>.'); ?><span style="color:#a00;"> <b>(New)</b></span></li>								
					<li><?php _e('Insert <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffAfMore--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffBfLastPara--&gt;</span> to <b>disable Ads right after the <span style="font-family:Courier New,Courier,Fixed;">&lt;!--more--&gt;</span> tag</b>, or <b>right before the last Paragraph</b>.'); ?><span style="color:#a00;"> <b>(New)</b></span></li>												
				</ol>
				[ <input type="checkbox" id="QckTags" name="quick_adsense_2_options[QckTags]" value="true" <?php if(isset($options['QckTags'])){echo('checked');} ?> /> <?php _e('Show Quicktag Buttons on the HTML Edit Post SubPanel'); ?> ]<br/>
				[ <input type="checkbox" id="QckRnds" name="quick_adsense_2_options[QckRnds]" value="true" <?php if(isset($options['QckRnds'])){echo('checked');} ?> /> <?php _e('Hide <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--RndAds--&gt;</span> from Quicktag Buttons'); ?> ]<br/>	
				[ <input type="checkbox" id="QckOffs" name="quick_adsense_2_options[QckOffs]" value="true" <?php if(isset($options['QckOffs'])){echo('checked');} ?> /> <?php _e('Hide <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--NoAds--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffDef--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffWidget--&gt;</span> from Quicktag Buttons'); ?> ]<br/>								
				[ <input type="checkbox" id="QckOfPs" name="quick_adsense_2_options[QckOfPs]" value="true" <?php if(isset($options['QckOfPs'])){echo('checked');} ?> /> <?php _e('Hide <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffBegin--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffMiddle--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffEnd--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffAfMore--&gt;</span>, <span style="font-family:Courier New,Courier,Fixed;color:#050">&lt;!--OffBfLastPara--&gt;</span> from Quicktag Buttons'); ?> ]<br/>								
				<span class="description" style="display:block;font-style:italic;padding-top:10px"><?php _e('Tags can be inserted into a post via the additional Quicktag Buttons at the HTML Edit Post SubPanel.'); ?></span><br/>
			</td>
		</tr>	
		<tr valign="top">
			<td style="width:110px"><?php _e('Infomation :'); ?></td>
			<td>
				<span><?php echo(__('A link from your blog to <a href="http://quickadsense.com/" target="_blank">http://quickadsense.com/</a> would be appreciated.')); ?></span>
			</td>	
		</tr>	
	</table>
	<p style="margin-top:20px">( <a href="javascript:defaultoptions()"><?php _e('Load Default Setting') ?></a> )<br/><br/></p>
	
	<h3 style="font-size:120%;margin-bottom:5px"><?php _e('Adsense Codes'); ?></h3>
	<p style="margin-top:0px"><span class="description"><?php _e('Paste up to <b>'.$QData['Ads'].' Ads codes</b> on Post Body as assigned above, and up to <b>'.$QData['AdsWid'].' Ads codes</b> on Sidebar Widget. Ads codes provided must <b>not</b> be identical, repeated codes may result the Ads not being display correctly. Ads will never displays more than once in a page.') ?></span></p>	

	<h4><?php _e('Ads on Post Body :'); ?></h4>		
	<table border="0" cellspacing="0" cellpadding="0">
		<?php for ($i = 1; $i <= $QData['Ads']; $i++) {  ?>
		<tr valign="top">
			<td align="left" style="width:110px">Ads<?php echo $i; ?> :</td>
			<td align="left"><textarea style="margin:0 5px 3px 0" id="AdsCode<?php echo $i; ?>" name="quick_adsense_2_options[AdsCode<?php echo $i; ?>]" rows="3" cols="50"><?php echo htmlentities($options['AdsCode'.$i]); ?></textarea></td>
			<td align="left">
				<select name="quick_adsense_2_options[AdsAlign<?php echo $i; ?>]">
					<option id="OptAgn<?php echo $i; ?>1" value="1" <?php if(isset($options['AdsAlign'.$i]) && $options['AdsAlign'.$i]=="1"){echo('selected');} ?>><?php _e('Left') ; ?></option>
					<option id="OptAgn<?php echo $i; ?>2" value="2" <?php if(isset($options['AdsAlign'.$i]) && $options['AdsAlign'.$i]=="2"){echo('selected');} ?>><?php _e('Center') ; ?></option>
					<option id="OptAgn<?php echo $i; ?>3" value="3" <?php if(isset($options['AdsAlign'.$i]) && $options['AdsAlign'.$i]=="3"){echo('selected');} ?>><?php _e('Right') ; ?></option>
					<option id="OptAgn<?php echo $i; ?>4" value="4" <?php if(isset($options['AdsAlign'.$i]) && $options['AdsAlign'.$i]=="4"){echo('selected');} ?>><?php _e('None') ; ?></option>
				</select> 
				<?php _e('alignment'); ?><br/>
				<input style="width:35px;text-align:right;" id="AdsMargin<?php echo $i; ?>" name="quick_adsense_2_options[AdsMargin<?php echo $i; ?>]" value="<?php echo stripslashes(htmlspecialchars($options['AdsMargin'.$i])); ?>" />px <?php _e('margin'); ?><br/>						
			</td>
		</tr>
	<?php } ?>	
	</table>

	<h4><?php _e('Ads on Sidebar Widget '); ?><span style="font-weight:normal">(<a href="widgets.php"><?php _e('Drag to Sidebar'); ?></a>)</span> :</h4>	
	<table border="0" cellspacing="0" cellpadding="0">
		<?php
		for ($i=1;$i<=$QData['AdsWid'];$i++) { ?>	
			<tr valign="top">
				<td align="left" style="width:110px">AdsWidget<?php echo $i; ?> :</td>
				<td align="left"><textarea style="margin:0 5px 3px 0" id="WidCode<?php echo $i; ?>" name="quick_adsense_2_options[WidCode<?php echo $i; ?>]" rows="3" cols="50"><?php echo htmlentities($options['WidCode'.$i]); ?></textarea></td>
			</tr>
		<?php } ?>	
	</table>
<?php 
}

function quick_adsense_2_truefalse($arg) {
	if($arg){
		return 'true';
	} else {
		return 'false';
	}
}

function quick_adsense_2_validate($input) {
	return $input;
}
?>