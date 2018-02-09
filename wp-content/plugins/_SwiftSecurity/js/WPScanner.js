var SwiftSecurityScan = function(){

	var _that = this;
	
	this.Scan = function(type){
		//Empty containers
		jQuery('.swiftsecurity-wpscan-results .scan-results').empty();
		jQuery('.swiftsecurity-wpscan-results .scan-title').empty();
		
		//Add overlay
		jQuery('#codescanner-container').addClass('disabled-page');
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScanner', 'type' : type, 'wp-nonce': ajax_object.wp_nonce}, function(response){
			try{
				response = JSON.parse(response);
				_that.HTMLReport(response.report);
				_that.ShowProgress(response.progress);
				
				//Remove overlay
				jQuery('#codescanner-container').removeClass('disabled-page');
			}
			catch(e){
				_that.Scan('continue');
			}			
		})
		.error(function(xhr){
			_that.Scan('continue');
		});
	}
	
	this.Heartbeat = function(){
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScanner', 'type' : 'heartbeat', 'wp-nonce': ajax_object.wp_nonce}, function(response) {
			try{
				response = JSON.parse(response);
				_that.HTMLReport(response.report);
				if (response.progress > 0){
					_that.ShowProgress(response.progress);
				}
				if (response.progress < 100){
					setTimeout(function(){
						_that.Heartbeat();
					},500);
				}
			}
			catch(e){
				//Silent fail
			}
		});
	}
	
	this.ShowFilteredResults = function(filter){		
		//Empty containers
		jQuery('.swiftsecurity-wpscan-results .scan-results').empty();
		jQuery('.swiftsecurity-wpscan-results .scan-title').empty();
	
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScanner', 'type' : 'filtered', 'filter': filter, 'wp-nonce': ajax_object.wp_nonce}, function(response) {
			response = JSON.parse(response);
			//Set the title
			jQuery('.swiftsecurity-wpscan-results .scan-title').text(response.title);
		
			//Show the report
			_that.HTMLReport(response.report);
			
			if(filter == 'quarantined' || filter == 'whitelisted'){
				jQuery('.scan-results .scan-result-container h4').remove();
			}
			
		});
	}
	
	this.HTMLReport = function(report, show_whitelisted){
		var Report = [];
		var Count = [];
		
		//Clone containers
		Report['php'] = jQuery('#result-sample-container #php-results').clone();
		Report['mysql'] = jQuery('#result-sample-container #mysql-results').clone();
		Report['filescan'] = jQuery('#result-sample-container #filescan-results').clone();
		
		//Set counters
		Count['php'] = 0;
		Count['mysql'] = 0;
		Count['filescan'] = 0;
		
 		//Iterate report groups
		for(var i in report){
			if (i == 'php'){
				for(var j in report[i]){
					var result = jQuery('#result-sample-container .php-result').clone();
					jQuery(result).find('.label').text(report[i][j]['label']);
					jQuery(result).find('.score').text(report[i][j]['result']['score'] == 0 ? 'OK' : report[i][j]['result']['score']);
					jQuery(result).find('.score').addClass(_that.ClassByScore(report[i][j]['result']['score']));
					jQuery(result).find('.text').text(report[i][j]['result']['text']);
					
					Count['php']++;
					Report['php'].append(result);
				}
			}
			if (i == 'mysql'){
				for(var j in report[i]){
					var result = jQuery('#result-sample-container .mysql-result').clone();
					jQuery(result).find('.label').text(report[i][j]['label']);
					jQuery(result).find('.score').text(report[i][j]['result']['score'] == 0 ? 'OK' : report[i][j]['result']['score']);
					jQuery(result).find('.score').addClass(_that.ClassByScore(report[i][j]['result']['score']));
					jQuery(result).find('.text').text(report[i][j]['result']['text']);
					
					Count['mysql']++;
					Report['mysql'].append(result);
				}
			}
			if (i == 'filescan'){
				for(var j in report[i]){
					if (report[i][j]['score'] > 0 && (report[i][j]['whitelisted'] == false || report[i][j]['forceshow'] == true)){
						var result = jQuery('#result-sample-container .filescan-result').clone();
						jQuery(result).find('.filename').text(j);
						jQuery(result).find('.score').text(report[i][j]['score']);
						jQuery(result).find('.score').addClass(_that.ClassByScore(report[i][j]['score']));
						jQuery(result).find('.whitelist').attr('data-filename',j);
						jQuery(result).find('.quarantine').attr('data-filename',j);
						for(var k in report[i][j]['alerts']){
							try {
								var subresult = jQuery('#result-sample-container .filescan-subresults').clone();
								jQuery(subresult).find('.label').text(report[i][j]['alerts'][k]['label']);
								jQuery(subresult).find('.text').text(report[i][j]['alerts'][k]['result']['text']);
								if (typeof report[i][j]['alerts'][k]['result']['match'] !== 'undefined'){
									jQuery(subresult).find('.match').html(report[i][j]['alerts'][k]['result']['match'].replace(report[i][j]['alerts'][k]['result']['highlight'], '<b>' + report[i][j]['alerts'][k]['result']['highlight'] +'</b>'));
								}
								jQuery(result).append(subresult);
							}
							catch(e){
								//Silent fail
							}
						}
						//Change quarantine button if file is already in quarantine
						if(report[i][j]['quarantine'] == true){
							result.addClass('quarantined');
							result.find('.quarantine').text('Remove from quarantine');
							result.find('.quarantine').addClass('unquarantine');
							result.find('.quarantine').removeClass('quarantine');
						}
						
						//Change whitelist button if file is already in whitelist
						if(report[i][j]['whitelisted'] == true){
							result.find('.whitelist').text('Remove from whitelist');
							result.find('.whitelist').addClass('unwhitelist');
							result.find('.whitelist').removeClass('whitelist');
						}
						Count['filescan']++
						Report['filescan'].append(result);
					}
				}
			}
		}
	
		jQuery('.swiftsecurity-wpscan-results .scan-results').empty();
		//Append reports
		for (var x in Report){
			if (Count[x] > 0){
				jQuery('.swiftsecurity-wpscan-results .scan-results').append(Report[x]);
			}
		}

	}
	
	this.ShowProgress = function(progress){
		jQuery('.swiftsecurity-wpscan-progress > span').empty().text(progress+'%');
		jQuery('.swiftsecurity-wpscan-progress > span').width(progress+'%');
	}
	
	this.ClassByScore = function(score){
		if (score < 20){
			return 'score-green';
		}
		else if (score < 60){
			return 'score-yellow';
		}
		else {
			return 'score-red';
		}		
	}
	
	//Load last scan results
	this.ShowFilteredResults();
}

var SwiftSecurityScan = new SwiftSecurityScan();

jQuery(document).ready(function($) {
	//Scan
	jQuery(document).on('click','#swiftsecurity-scan-now',function(e){
		e.preventDefault();
		SwiftSecurityScan.Scan('start');
		SwiftSecurityScan.Heartbeat();
		jQuery('.swiftsecurity-wpscan-progress > span').empty().text(0);	
		jQuery('.swiftsecurity-wpscan-progress > span').width('0px');
	});
	
	jQuery(document).on('click','.swiftsecurity-list-filtered-results',function(e){
		e.preventDefault();
		SwiftSecurityScan.ShowFilteredResults(jQuery(this).attr('data-filter'));
		jQuery('.swiftsecurity-wpscan-progress > span').empty().text(0);	
		jQuery('.swiftsecurity-wpscan-progress > span').width('0px');
	});
		
	//Whitelist
	jQuery(document).on('click','.swiftsecurity-wpscan-results .whitelist',function(){
		var button = jQuery(this);
		
		//Add overlay
		jQuery(button).parents('.filescan-result').addClass('disabled-filescan-container');
		
		//Add whitelisted style
		jQuery(button).parents('.filescan-result').addClass('whitelisted');
		
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScannerFileAction', 'type' : 'whitelist', 'filename' : jQuery(button).attr('data-filename'), 'wp-nonce': ajax_object.wp_nonce}, function(){
			jQuery(button).text('Undo');
			jQuery(button).removeClass('whitelist');
			jQuery(button).addClass('unwhitelist');		
			
			//Remove overlay
			jQuery(button).parents('.filescan-result').removeClass('disabled-filescan-container');
		})
	});
	
	jQuery(document).on('click','.swiftsecurity-wpscan-results .unwhitelist',function(){
		var button = jQuery(this);
		
		//Add overlay
		jQuery(button).parents('.filescan-result').addClass('disabled-filescan-container');
		
		//Add whitelisted style
		jQuery(button).parents('.filescan-result').removeClass('whitelisted');
		
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScannerFileAction', 'type' : 'unwhitelist', 'filename' : jQuery(button).attr('data-filename'), 'wp-nonce': ajax_object.wp_nonce}, function(){
			jQuery(button).text('Add to whitelist');
			jQuery(button).removeClass('unwhitelist');
			jQuery(button).addClass('whitelist');	
			
			//Remove overlay
			jQuery(button).parents('.filescan-result').removeClass('disabled-filescan-container');
		})
	});
	
	//Quarantine
	jQuery(document).on('click','.swiftsecurity-wpscan-results .quarantine',function(){
		var button = jQuery(this);
		
		//Add overlay
		jQuery(button).parents('.filescan-result').addClass('disabled-filescan-container');
		
		//Add whitelisted style
		jQuery(button).parents('.filescan-result').addClass('quarantined');
		
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScannerFileAction', 'type' : 'quarantine', 'filename' : jQuery(button).attr('data-filename'), 'wp-nonce': ajax_object.wp_nonce}, function(){
			jQuery(button).text('Undo');
			jQuery(button).removeClass('quarantine');
			jQuery(button).addClass('unquarantine');	
			
			//Remove overlay
			jQuery(button).parents('.filescan-result').removeClass('disabled-filescan-container');
		})
	});
	
	jQuery(document).on('click','.swiftsecurity-wpscan-results .unquarantine',function(){
		var button = jQuery(this);

		//Add overlay
		jQuery(button).parents('.filescan-result').addClass('disabled-filescan-container');
		
		//Add whitelisted style
		jQuery(button).parents('.filescan-result').removeClass('quarantined');
		
		jQuery.post(ajax_object.ajax_url, {'action': 'SwiftSecurityWPScannerFileAction', 'type' : 'unquarantine', 'filename' : jQuery(button).attr('data-filename'), 'wp-nonce': ajax_object.wp_nonce}, function(){
			jQuery(button).text('Quarantine');
			jQuery(button).removeClass('unquarantine');
			jQuery(button).addClass('quarantine');		
			
			//Remove overlay
			jQuery(button).parents('.filescan-result').removeClass('disabled-filescan-container');
		})
	});
	
});