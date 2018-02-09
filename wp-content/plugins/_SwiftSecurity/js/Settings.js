//Set messages object
try{
	window.SwiftsecurityMessages = JSON.parse(ajax_object.SwiftsecurityMessages);
}
catch (e){
	window.SwiftsecurityMessages = {};
}

var SwiftSecurityTroubleshooting = function(){

	var _that = this;
	
	this.StartTest = function(url, step){
		jQuery('#swiftsecurity_at_start').prop('disabled');
		jQuery('#swiftsecurity_at_container').empty();
		this.ShowRawMessage(SwiftsecurityMessages['TEST_START'],'info');
		this.Test(url, step);
	}
	
	this.EndTest = function(url, step){
		this.ShowRawMessage(SwiftsecurityMessages['DONE'], 'info');
		jQuery('#swiftsecurity_at_start').removeProp('disabled');
	}
	
	this.Test = function(url, step){
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityTroubleshooting', 'step' : step, 'url': url, 'wp-nonce': ajax_object.wp_nonce}, function(response){
			try{
				response = JSON.parse(response);				
				if (response.nextstep != false){
					_that.ShowRawMessage(response.msg, response.type);
					_that.Test(url, response.nextstep);
				}
				else{
					_that.ShowRawMessage(response.msg, response.type);
					_that.EndTest();
				}
			}
			catch(e){
				_that.ShowError('UNKWOWN_ERROR');
			}			
		})
		.error(function(xhr){
			_that.ShowError('UNKWOWN_ERROR');
		});
	}
	
	this.ShowError = function(msg){
		this.ShowRawMessage(SwiftsecurityMessages[msg], 'error');
	}
	
	this.ShowRawMessage = function(msg, type){
		jQuery('#swiftsecurity_at_container').append(
			jQuery('<div>',{
				'class' : 'sft-' + type +'-inline-message',
				'text'	: msg 
			})
		);
	}
}

var SwiftSecuritySettings = function(){
	this.renameEnquedSortable = function(){
		jQuery('.enqueued-scripts-header input[type="hidden"]').each(function(){
			jQuery(this).attr('name','settings[HideWP][enqueuedScriptsHeader][]');
		});
		jQuery('.enqueued-scripts-footer input[type="hidden"]').each(function(){
			jQuery(this).attr('name','settings[HideWP][enqueuedScriptsFooter][]');
		});
	}
}

jQuery(function(){
	//Base64 encode to prevent magic_quote_strings problems
 	jQuery(document).on('submit','.ss-base64-armored',function(e){
 		//Handle numeric inputs
 		jQuery('input[type=number]').attr('type','text');
 		
 		var form = jQuery(this);
 		jQuery(form).removeClass('ss-base64-armored');
 	 	jQuery(this).find('input, option, textarea').each(function(){
 	 		jQuery(this).val(B64.encode(jQuery(this).val()));
 	 	});
 	 	jQuery(form).append(jQuery('<input>',{
 	 		'type' : 'hidden',
 	 		'name' : 'base64',
 	 		'value': '1'
 	 	}));
 	});
	
	//Confirmation for restore defaults
	jQuery(document).on('click','.restore-defaults',function(){
 	 	if (confirm(SwiftsecurityMessages['ARE_YOU_SURE'])){
 	 		return true;
 	 	}
 	 	return false;
	});
	
	//Select all for ss-multiple selects
 	jQuery(document).on('click','.select-all',function(e){
 	 	e.preventDefault();
 	 	jQuery(this).parent().find('.ss-cblist input[type=checkbox]').each(function(){
 	 		jQuery(this).prop('checked', 'true');
 	 	});
 	});
 	
 	//Unselect all for ss-multiple selects
 	jQuery(document).on('click','.deselect-all',function(e){
 	 	e.preventDefault();
 	 	jQuery(this).parent().find('.ss-cblist input[type=checkbox]').each(function(){
 	 		jQuery(this).removeProp('checked');
 	 	});
 	});
	
	// Firewall logs
	jQuery('#swift-security-log tbody tr').click(function(){
		jQuery(this).next().toggleClass('hidden-row colored-row');
		jQuery(this).toggleClass('colored-row');
	});
	
	//Load current settings
	jQuery('.firewall-settings').each(function(){
		var set = jQuery(this).attr('data-set');
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityFirewallAjaxHandler', 'set' : set, 'wp-nonce': ajax_object.wp_nonce}, function(response){
			jQuery('#' + set + '-settings').empty().append(response);
		})
	})
	
	//
	// JS files for better performance
	//
	
	//Manage javascripts
	if (jQuery('#onoff-sm-managejs').prop('checked')){
		jQuery('.enqueued-scripts-header').load(ajax_object.home_url + '?SwiftSecurity=manage-assets&mode=script&location=header&wp-nonce=' + ajax_object.wp_nonce);
		jQuery('.enqueued-scripts-footer').load(ajax_object.home_url + '?SwiftSecurity=manage-assets&mode=script&location=footer&wp-nonce=' + ajax_object.wp_nonce);
		jQuery('.enqueued-scripts-header, .enqueued-scripts-footer').sortable({connectWith:['.enqueued-scripts'], forcePlaceholderSize: true, dropOnEmpty: true, stop: SwiftSecuritySettings.renameEnquedSortable});
	}
	{connectWith:['.enqueued-scripts']}
	jQuery(document).on('change','#onoff-sm-managejs', function(){
		jQuery('.manage-scripts-container').toggleClass('hidden');
		if (jQuery(this).prop('checked')){
			jQuery('.enqueued-scripts-header').load(ajax_object.home_url + '?SwiftSecurity=manage-assets&mode=script&location=header&wp-nonce=' + ajax_object.wp_nonce);
			jQuery('.enqueued-scripts-footer').load(ajax_object.home_url + '?SwiftSecurity=manage-assets&mode=script&location=footer&wp-nonce=' + ajax_object.wp_nonce);
			jQuery('.enqueued-scripts-header, .enqueued-scripts-footer').sortable({connectWith:['.enqueued-scripts'], forcePlaceholderSize: true, dropOnEmpty: true, stop: SwiftSecuritySettings.renameEnquedSortable});
		}
		else{
			jQuery('.enqueued-scripts-header, .enqueued-scripts-footer').empty();
		}
	})
	
	//Combine CSS
	jQuery(document).on('change','#onoff-sm-combinecss',function(){
		if (jQuery(this).prop('checked') == true){
			jQuery('.combined-css-fake-name').removeClass('ss-hidden');
		}
		else{
			jQuery('.combined-css-fake-name').addClass('ss-hidden');
		}
	});
	
	//Combine header JS
	jQuery(document).on('change','#onoff-sm-combine-header-js',function(){
		if (jQuery(this).prop('checked') == true){
			jQuery('.combined-header-js-fake-name').removeClass('ss-hidden');
		}
		else{
			jQuery('.combined-header-js-fake-name').addClass('ss-hidden');
		}
	});
	
	//Combine footer JS
	jQuery(document).on('change','#onoff-sm-combine-footer-js',function(){
		if (jQuery(this).prop('checked') == true){
			jQuery('.combined-footer-js-fake-name').removeClass('ss-hidden');
		}
		else{
			jQuery('.combined-footer-js-fake-name').addClass('ss-hidden');
		}
	});

	
	//
	// Slider for security presets
	//
	
	jQuery(function(){
		jQuery('.fw-slider').each(function(){
			var slider = jQuery(this);
			jQuery(this).noUiSlider({
				start: [ slider.attr('data-current') ],
				step: 1,
				range: {
					'min': parseInt(slider.attr('data-min')),
					'max': parseInt(slider.attr('data-max'))
				},	
			});
		
			jQuery(this).Link('lower').to(jQuery('#preset-value-' + jQuery(this).attr('data-set')), null, wNumb({
				decimals: 0
			}));
		});
	});

	//
	// Open/Hide all function
	//
	jQuery('.swift-security-row > h4, .custom-settings > h4').click(function(){
		jQuery(this).parent().toggleClass('opened');
	});
	
	//
	// Toggle button function
	//
	jQuery('.open-hide').click(function(e){
		e.preventDefault();
		if(jQuery(this).text() == 'Hide All') {
			jQuery(this).text('Open All');
			var action = 'close';
		} else {
			jQuery(this).text('Hide All');
			var action = 'open';
		}
		jQuery('.swift-security-row').each(function(){
			if (action == 'open'){
				jQuery(this).addClass('opened');	
			}
			else if (action == 'close'){
				jQuery(this).removeClass('opened');	
			}
		});
		
	});
	
	//Select autoselect input
	jQuery(document).on('click','.autoselect',function(){
		jQuery(this).select();
	});
	
	//Start automated troubleshooting
	jQuery(document).on('click','#swiftsecurity_at_start',function(){
		SwiftSecurityTroubleshooting.StartTest(jQuery('#swiftsecurity_at_url').val(), 'hardcoded_upload_dir');
	});
	
	jQuery(document).on('focus','.input-sample',function(){
		if(jQuery(this).parent().prev().find('.cloned').val() == ''){
			jQuery(this).parent().prev().find('.cloned').focus();
			return false;
		}
	 	var clone = jQuery(this).parent().clone();
	 	jQuery(clone).removeClass('sample-container');
	 	jQuery(clone).find('input').attr('name', jQuery(this).attr('data-name'));
	 	jQuery(clone).find('.input-sample').removeAttr('data-name');
	 	jQuery(clone).find('.input-sample').addClass('cloned');
	 	jQuery(clone).find('.input-sample').removeClass('input-sample');
	 	jQuery(clone).insertBefore(jQuery(this).parent());
	 	jQuery(clone).find('input:first').focus();
	});

 	jQuery(document).on('focus','.input-sample-kv',function(){
		if(jQuery(this).parent().prev().find('.cloned-kv-key').val() == '' || jQuery(this).parent().prev().find('.cloned-kv-value').val() == ''){
			jQuery(this).parent().prev().find('.cloned-kv-key').focus();
			return false;
		}
 	 	var clone = jQuery(this).parent().clone();
 	 	jQuery(clone).removeClass('sample-container');
 	 	jQuery(clone).find('.input-sample-kv-key').addClass('cloned-kv-key');
 	 	jQuery(clone).find('.input-sample-kv-value').addClass('cloned-kv-value');
 	 	jQuery(clone).find('.input-sample-kv').removeClass('input-sample-kv');
 	 	jQuery(clone).insertBefore(jQuery(this).parent());
 	 	jQuery(clone).find('.input-sample-kv-key').focus();
 	});

 	jQuery(document).on('keyup','.input-sample-kv-key',function(){
 	 	jQuery(this).parent().find('.input-sample-kv-value').attr('name',jQuery(this).parent().find('.input-sample-kv-value').attr('name').replace(/settings\[(.*)\]\[(.*)\]\[(.*)\]/,'settings[$1][$2][' + jQuery(this).val() + ']'));
 	});
 	
 	jQuery(document).on('click','.generate-plugin-name',function(e){
 	 	e.preventDefault();
 	 	jQuery(this).parent().find('input').val(jQuery(this).attr('data-plugindir').shuffle());
 	});
 	
 	jQuery(document).on('click','.quick-preset',function(e){
 		e.preventDefault();
 		jQuery("#QuickPreset").val(jQuery(this).attr('data-plugin'));
 		jQuery("button[name='swift-security-settings-save']").click();
 	});
 	
 	jQuery(document).on('click','.remove-input',function(e){
 	 	e.preventDefault();
 	 	jQuery(this).parent().remove();
 	});

 	jQuery(document).on('change','.fw-slider',function(){
 		var value = parseInt(jQuery(this).val());
 		var set = jQuery(this).attr('data-set');
 		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityFirewallAjaxHandler', 'set' : set, 'selected': value, 'wp-nonce': ajax_object.wp_nonce}, function(response){
			jQuery('#' + set + '-settings').empty().append(response);
		})
 	});

 	
 	String.prototype.shuffle = function () {
 	    var a = this.split(""),
 	        n = a.length;

 	    for(var i = n - 1; i > 0; i--) {
 	        var j = Math.floor(Math.random() * (i + 1));
 	        var tmp = a[i];
 	        a[i] = a[j];
 	        a[j] = tmp;
 	    }
 	    return a.join("");
 	}
});

SwiftSecurityTroubleshooting = new SwiftSecurityTroubleshooting();
SwiftSecuritySettings = new SwiftSecuritySettings();