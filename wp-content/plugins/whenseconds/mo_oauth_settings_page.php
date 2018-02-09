<?php


function mo_register() {

	$currenttab = "";
	if(isset($_GET['tab']))
		$currenttab = $_GET['tab'];
	?>
	<?php
		
		mo_oauth_client_menu($currenttab);
	?>

<div id="mo_oauth_settings">

	<div class="Social_Integration_container">
		<table style="width:100%;">
		<tr>
		<td style="vertical-align:top;width:65%;" class="mo_oauth_content">
		<?php

	

		if($currenttab == 'customization')
			mo_oauth_app_customization();
		else
			mo_oauth_apps_config();
	
	?>
			</td>
			
			</tr>
			</table>
		</div>
		<?php
}



function mo_oauth_client_menu($currenttab){
	?>
	
			
<?php }


function mo_oauth_app_customization(){
	
	$custom_css = get_option('mo_oauth_icon_configure_css');
	function format_custom_css_value( $textarea ){ 
		$lines = explode(";", $textarea);
		for($i=0;$i<count($lines);$i++)
		{if($i<count($lines)-1)
			echo $lines[$i].";\r\n";
		
		else if($i==count($lines)-1)
			echo $lines[$i]."\r\n";
		}
	}
	
	?>
	<div class="mo_table_layout">
	<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_settings&tab=customization">
		<input type="hidden" name="option" value="mo_oauth_app_customization" />
		<h2>Customize Icons</h2>
		<table class="mo_settings_table">
			<tr>
				<td><strong>Icon Width:</strong></td>
				<td><input type="text" id="mo_oauth_icon_width" name="mo_oauth_icon_width" value="<?php echo get_option('mo_oauth_icon_width');?>"> e.g. 200px or 100%</td>
			</tr>
			<tr>
				<td><strong>Icon Height:</strong></td>
				<td><input  type="text" id="mo_oauth_icon_height" name="mo_oauth_icon_height" value="<?php echo get_option('mo_oauth_icon_height');?>"> e.g. 50px or auto</td>
			</tr>
			<tr>
				<td><strong>Icon Margins:</strong></td>
				<td><input  type="text" id="mo_oauth_icon_margin" name="mo_oauth_icon_margin" value="<?php echo get_option('mo_oauth_icon_margin');?>"> e.g. 2px 0px or auto</td>
			</tr>
			<tr>
				<td><strong>Custom CSS:</strong></td>
				<td><textarea type="text" id="mo_oauth_icon_configure_css" style="resize: vertical; width:400px; height:180px;  margin:5% auto;" rows="6" name="mo_oauth_icon_configure_css"><?php echo rtrim(trim(format_custom_css_value( $custom_css )),';');?></textarea><br/><b>Example CSS:</b> 
<pre>.oauthloginbutton{
	background: #7272dc;
	height:40px;
	padding:8px;
	text-align:center;
	color:#fff;
}</pre>
			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Save settings"
					class="button button-primary button-large" /></td>
			</tr>
		</table>
	</form>
	</div>
	<?php
}

function mo_oauth_apps_config() {
	?>

	<div class="mo_table_layout">
	<?php

		if(isset($_GET['action']) && $_GET['action']=='delete'){
			if(isset($_GET['app']))
				delete_app($_GET['app']);
		}

		if(isset($_GET['action']) && $_GET['action']=='add'){
			add_app();
		}
		else if(isset($_GET['action']) && $_GET['action']=='update'){
			if(isset($_GET['app']))
				update_app($_GET['app']);
		}
		else if(get_option('mo_oauth_apps_list'))
		{
			$appslist = get_option('mo_oauth_apps_list');
			echo "<br><br><table style=width:100%>";
			foreach($appslist as $key => $app){
				echo "<tr><td>".$key."</td><td><a href='admin.php?page=mo_oauth_settings&action=update&app=".$key."'>Edit Application</a> | <a href='admin.php?page=mo_oauth_settings&action=delete&app=".$key."'>Delete</a> </td></tr>";
			}
			echo "</table>";
			echo "<a href='admin.php?page=mo_oauth_settings&action=add'><button style=display:none>Add Application</button></a>";

		} else {
			add_app();
		 } ?>
		</div>
<?php

}

function add_app(){


		$appslist = get_option('mo_oauth_apps_list');
		

	?>

		<script>
			function selectapp() {
				var appname = document.getElementById("mo_oauth_app").value;
				document.getElementById("instructions").innerHTML  = "";
				
					jQuery("#mo_oauth_custom_app_name_div").show();
					jQuery("#mo_oauth_authorizeurl_div").show();
					jQuery("#mo_oauth_accesstokenurl_div").show();
					jQuery("#mo_oauth_resourceownerdetailsurl_div").show();
					jQuery("#mo_oauth_email_attr_div").show();
					jQuery("#mo_oauth_name_attr_div").show();
					jQuery("#mo_oauth_custom_app_name").attr('required','true');
					jQuery("#mo_oauth_authorizeurl").attr('required','true');
					jQuery("#mo_oauth_accesstokenurl").attr('required','true');
					jQuery("#mo_oauth_resourceownerdetailsurl").attr('required','true');
					jQuery("#mo_oauth_email_attr").attr('required','true');
					jQuery("#mo_oauth_name_attr").attr('required','true');
				

			}

		</script>
		<div id="toggle2" class="panel_toggle">
			<h3>Add Application</h3>
		</div>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_settings">
		<input type="hidden" name="option" value="mo_oauth_add_app" />
		<table class="mo_settings_table">
			<tr>
			<td><strong><font color="#FF0000">*</font>Select Application:</strong></td>
			<td>
				<select class="mo_table_textbox" required="true" name="mo_oauth_app_name" id="mo_oauth_app" onchange="selectapp()">
				  <option value="">Select Application</option>
				  <option value="other">App</option>
				</select>
			</td>
			</tr>
			<tr  style="display:none" id="mo_oauth_custom_app_name_div">
				<td><strong><font color="#FF0000">*</font>Custom App Name:</strong></td>
				<td><input class="mo_table_textbox" type="text" id="mo_oauth_custom_app_name" name="mo_oauth_custom_app_name" value=""></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client ID:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" name="mo_oauth_client_id" value=""></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client Secret:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text"  name="mo_oauth_client_secret" value=""></td>
			</tr>
			<tr>
				<td><strong>Scope:</strong></td>
				<td><input class="mo_table_textbox" type="text" name="mo_oauth_scope" value="email"></td>
			</tr>
			<tr style="display:none" id="mo_oauth_authorizeurl_div">
				<td><strong><font color="#FF0000">*</font>Authorize Endpoint:</strong></td>
				<td><input class="mo_table_textbox" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value=""></td>
			</tr>
			<tr style="display:none" id="mo_oauth_accesstokenurl_div">
				<td><strong><font color="#FF0000">*</font>Access Token Endpoint:</strong></td>
				<td><input class="mo_table_textbox" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value=""></td>
			</tr>
			<tr style="display:none" id="mo_oauth_resourceownerdetailsurl_div">
				<td><strong><font color="#FF0000">*</font>Get User Info Endpoint:</strong></td>
				<td><input class="mo_table_textbox" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value=""></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Save settings"
					class="button button-primary button-large" /></td>
			</tr>
			</table>
		</form>

		<div id="instructions">

		</div>

		<?php
}

function update_app($appname){

	$appslist = get_option('mo_oauth_apps_list');
	foreach($appslist as $key => $app){
		if($appname == $key){
			$currentappname = $appname;
			$currentapp = $app;
			break;
		}
	}

	if(!$currentapp)
		return;

	$is_other_app = false;
	if(!in_array($currentappname, array("facebook","google","eveonline","windows")))
		$is_other_app = true;

	?>

		<div id="toggle2" class="panel_toggle">
			<h3>Update Application</h3>
		</div>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_settings">
		<input type="hidden" name="option" value="mo_oauth_add_app" />
		<table class="mo_settings_table">
			<tr>
			<td><strong><font color="#FF0000">*</font>Application:</strong></td>
			<td>
				<input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_app_name" value="<?php echo $currentappname;?>">
				<input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_custom_app_name" value="<?php echo $currentappname;?>">
				<?php echo $currentappname;?><br><br>
			</td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client ID:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" name="mo_oauth_client_id" value="<?php echo $currentapp['clientid'];?>"></td>
			</tr>
			<tr>
				<td><strong><font color="#FF0000">*</font>Client Secret:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" name="mo_oauth_client_secret" value="<?php echo $currentapp['clientsecret'];?>"></td>
			</tr>
			<tr>
				<td><strong>Scope:</strong></td>
				<td><input class="mo_table_textbox" type="text" name="mo_oauth_scope" value="<?php echo $currentapp['scope'];?>"></td>
			</tr>
			<?php if($is_other_app){ ?>
			<tr  id="mo_oauth_authorizeurl_div">
				<td><strong><font color="#FF0000">*</font>Authorize Endpoint:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value="<?php echo $currentapp['authorizeurl'];?>"></td>
			</tr>
			<tr id="mo_oauth_accesstokenurl_div">
				<td><strong><font color="#FF0000">*</font>Access Token Endpoint:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value="<?php echo $currentapp['accesstokenurl'];?>"></td>
			</tr>
			<tr id="mo_oauth_resourceownerdetailsurl_div">
				<td><strong><font color="#FF0000">*</font>Get User Info Endpoint:</strong></td>
				<td><input class="mo_table_textbox" required="" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value="<?php echo $currentapp['resourceownerdetailsurl'];?>"></td>
			</tr>
			<?php } ?>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="submit" value="Save settings" class="button button-primary button-large" />
					<?php if($is_other_app){?><input type="submit" name="button" value="Test Configuration" class="button button-primary button-large" onclick="testConfiguration()" /><?php } ?>
				</td>
			</tr>
		</table>
		</form>
		</div>

		<div class="mo_table_layout" id="attribute-mapping">
		<?php if($is_other_app){ ?>
		<form id="form-common" name="form-common" method="post" action="admin.php?page=mo_oauth_settings">
		<h3>Attribute Mapping</h3>

		<input type="hidden" name="option" value="mo_oauth_attribute_mapping" />
		<input class="mo_table_textbox" required="" type="hidden" id="mo_oauth_app_name" name="mo_oauth_app_name" value="<?php echo $currentappname;?>">
		<input class="mo_table_textbox" required="" type="hidden" name="mo_oauth_custom_app_name" value="<?php echo $currentappname;?>">
		<table class="mo_settings_table">
			<tr id="mo_oauth_email_attr_div">
				<td><strong><font color="#FF0000">*</font>Email:</strong></td>
				<td><input class="mo_table_textbox" required="" placeholder="Enter attribute name for Email" type="text" id="mo_oauth_email_attr" name="mo_oauth_email_attr" value="<?php if(isset( $currentapp['email_attr']))echo $currentapp['email_attr'];?>"></td>
			</tr>
			<tr id="mo_oauth_name_attr_div">
				<td><strong><font color="#FF0000">*</font>First Name:</strong></td>
				<td><input class="mo_table_textbox" required="" placeholder="Enter attribute name for First Name" type="text" id="mo_oauth_name_attr" name="mo_oauth_name_attr" value="<?php if(isset( $currentapp['name_attr'])) echo $currentapp['name_attr'];?>"></td>
			</tr>
			
			
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Save settings"
					class="button button-primary button-large" /></td>
			</tr>
			</table>
		</form>
		</div>

				
		<script>
		function testConfiguration(){
			var mo_oauth_app_name = jQuery("#mo_oauth_app_name").val();
			var myWindow = window.open('<?php echo site_url(); ?>' + '/?option=testattrmappingconfig&app='+mo_oauth_app_name, "Test Attribute Configuration", "width=600, height=600");
		}
		</script>
		<?php }
}

function delete_app($appname){
	$appslist = get_option('mo_oauth_apps_list');
	foreach($appslist as $key => $app){
		if($appname == $key){
			unset($appslist[$key]);
			if($appname=="eveonline")
				update_option( 'mo_oauth_eveonline_enable', 0);
		}
	}
	update_option('mo_oauth_apps_list', $appslist);
}





?>