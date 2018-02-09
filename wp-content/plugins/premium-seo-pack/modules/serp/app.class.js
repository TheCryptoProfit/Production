/*
Document   :  SERP
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/
// Initialization and events code for the app
pspSERP = (function ($) {
	"use strict";

	// public
	var debug_level = 0;
	var maincontainer = null;
	var lightbox = null;


	// init function, autoload
	(function init() {
		// load the triggers
		$(document).ready(function(){
			maincontainer = $(".psp-main");
			lightbox = $("#psp-lightbox-overlay");

			triggers();
		});
	})();


	function triggers()
	{
		jQuery('.psp-last-check-status span, .nb_pages, .psp-serp-competitor-delete a.psp-close-btn').tipsy({live: true, gravity: 'w'});

		// load lightbox by default - useful in developing mode
		/*
		(function() {
			// add keywords - lighbox menu tabs
			$(".psp-keywords-container").tabs();

			lightbox.find('.psp-serp-lbx-sections').hide();
			lightbox.find('.psp-serp-lbx-addkeyword').show();
			lightbox.show();
		})();
		*/

		// set current search engine location
		$('body').on('change', '#select-engine', function(e) {
			e.preventDefault();

			var that 		= $(this);

			engine_select({
				that 		: that,
				engine 		: that.val()
			});
		});

		// choose to view only used search engine locations
		$('body').on('click', '#psp-serp-engine-response .psp-custom-checkbox .checkbox', function(e) {
			e.preventDefault();

			var that 		= $(this),
				only_used 	= that.hasClass('checked') ? 1 : 0;

			engine_only_used({
				that 		: that,
				only_used	: only_used
			});
		});
		$('body').on('click', '#psp-serp-engine-response label#psp-serp-engine-used-only-label', function(e) {
			e.preventDefault();

			var that = $(this),
				parent = that.parent();

			parent.find('.psp-custom-checkbox .checkbox').trigger('click');
		});

		// add keywords - show lightbox
		$('body').on('click', ".psp-do_add_keyword", function(e){
			e.preventDefault();

			// add keywords - lighbox menu tabs
			$(".psp-keywords-container").tabs();

			var that = $(this);
			//var lightbox = $("#psp-lightbox-overlay");

			lightbox.find('.psp-serp-lbx-sections').hide();
			lightbox.find('.psp-serp-lbx-addkeyword').show();
			lightbox.show();
		});

		// add competitor - show lightbox
		$('body').on('click', ".psp-do_add_competitor", function(e){
			e.preventDefault();

			var that = $(this);
			//var lightbox = $("#psp-lightbox-overlay");

			lightbox.find('.psp-serp-lbx-sections').hide();
			lightbox.find('.psp-serp-lbx-addcompetitor').show();
			lightbox.show();
		});

		// close lightbox
		$('body').on('click', "#psp-lightbox-overlay a.psp-close-btn", function(e){
			e.preventDefault();

			//var lightbox = $("#psp-lightbox-overlay");
			lightbox.fadeOut('fast');
			//pspFreamwork.row_loading(current_row, 'hide');
		});

		// save competitor
		$('body').on('click', ".psp-save_competitor", function(e){
			e.preventDefault();

			var that = $(this);
			//var lightbox = $("#psp-lightbox-overlay");
			var $form 		= that.parents('form:first'),
				$competitor = lightbox.find('input#psp-add-competitor-name'),
				c_name 		= $competitor.val();

			c_name = $.trim( c_name );

			// validate
			if ( '' == c_name || c_name.length < 3 ) {
				if ( '' == c_name ) {
					$competitor.val( c_name );
				}
				msg_alert( 'You didn\'t complete the necessary fields!' );
				return false;
			}

			competitor_save({
				that 		: that,
				form 		: $form,
				engine 		: $('body #select-engine').val(),
				competitor 	: c_name
			});
		});

		// save keywords - manual
		$('body').on('click', ".psp-save_keywords_manual", function(e){
			e.preventDefault();

			var that = $(this);
			//var lightbox = $("#psp-lightbox-overlay");
			var $form 		= that.parents('form:first'),
				delimiter 	= lightbox.find('input[type="radio"][name="psp-csv-delimiter"]:checked').val(),
				$kw 		= lightbox.find('textarea#psp-add-keywords-manual'),
				kw 			= $kw.val();

			kw = $.trim( kw );

			// validate
			if ( '' == kw || kw.length < 2 ) {
				if ( '' == kw ) {
					$kw.val( kw );
				}
				msg_alert( 'You didn\'t complete the necessary fields!' );
				return false;
			}

			keywords_save({
				that 		: that,
				form 		: $form,
				engine 		: $('body #select-engine').val(),
				kw 			: kw,
				delimiter 	: delimiter
			});
		});

		// delete competitor
		$('body').on('click', '.psp-serp-competitor-delete', function(e) {
			e.preventDefault();

			var that 	= $(this),
				td 		= that.parents('th:first'),
				id 		= td.data('itemid');

			msg_confirm( 'Are you sure you want to delete the competitor ? This action cannot be rollback !', function() {
				competitor_delete({
					that 		: that,
					engine 		: $('body #select-engine').val(),
					id 			: id
				});
			});
		});

		// delete keywords
		$('body').on('click', '#psp-do_custom_bulk_delete_rows', function(e) {
			e.preventDefault();

			var that 	= $(this);

			var ids = [], __ck = $('.psp-form .psp-table input.psp-item-checkbox:checked');
			__ck.each(function (k, v) {
				ids[k] = $(this).attr('name').replace('psp-item-checkbox-', '');
			});
			ids = ids.join(',');
			//console.log( ids  ); return false;
			if (ids.length<=0) {
				//swal('You didn\'t select any rows!' , '', 'error');
				msg_alert( 'You didn\'t select any rows!' );
				return false;
			}

			msg_confirm( 'Are you sure you want to delete the selected rows ? This action cannot be rollback !', function() {
				keywords_delete({
					that 		: that,
					engine 		: $('body #select-engine').val(),
					ids 		: ids
				});
			});
		});

		// focus keywords: load list
		focus_keywords_load();

		// focus keywords: add to selected
		$('body').on('click', ".psp-fkw-ajax-table .psp-fkw", function(e){
			e.preventDefault();

			var that 		= $(this),
				fkw_text	= that.text(),
				fkw_code 	= md5( fkw_text ),
				fkw_newid	= 'psp-fkw-select-' + fkw_code,
				new_el 		= $( '<label><i></i>' + fkw_text + '</label>' ).attr({ 'id' : fkw_newid });

			//console.log( fkw, fkw_code, new_el );
			if ( ! $(".psp-selected-keywords-list").find('#'+fkw_newid).length ) {
				$(".psp-selected-keywords-list").append( new_el );
				//console.log( fkw, fkw_code, 'fkw selected' );
			}
			else {
				//console.log( fkw, fkw_code, 'fkw exists already' );
			}
		});

		// focus keywords: remove from selected
		$('body').on('click', ".psp-selected-keywords-list > label i", function(e){
			e.preventDefault();

			var that 		= $(this),
				fkw 		= that.parents('label:first');

			fkw.remove();
		});

		// save keywords - selected focus keywords
		$('body').on('click', ".psp-save_keywords_select", function(e){
			e.preventDefault();

			var that = $(this);
			//var lightbox = $("#psp-lightbox-overlay");
			var $form 		= that.parents('form:first'),
				delimiter 	= 'newline',
				$kw 		= $('.psp-selected-keywords-list > label');

			var kw = [];
			$kw.each(function(i) {
				kw.push( $(this).text() );
			});
			kw = kw.join("\n");
			kw = $.trim( kw );
			//console.log( kw ); return false;

			// validate
			if ( '' == kw || kw.length < 2 ) {
				if ( '' == kw ) {
					$kw.val( kw );
				}
				msg_alert( 'You didn\'t complete the necessary fields!' );
				return false;
			}

			keywords_save({
				that 		: that,
				form 		: $form,
				engine 		: $('body #select-engine').val(),
				kw 			: kw,
				delimiter 	: delimiter
			});
		});

		// update rank now
		$('body').on('click', ".psp-serp-action-update-rank", function(e){
			e.preventDefault();

			var that 		= $(this),
				keywordid 	= that.data('keywordid');

			keyword_update_rank({
				that 		: that,
				engine 		: $('body #select-engine').val(),
				kw_id 		: keywordid
			});
		});

		// keyword details: top100 & evolution chart
		$('body').on('click', "a.psp-serp-keyword-details", function(e){
			e.preventDefault();

			var that 		= $(this),
				parent 		= that.parents("tr").eq(0),
				box_details = parent.next('tr'),
				keywordid 	= that.data('keywordid');

			if ( that.hasClass('on') ) {
				box_details.hide();
				that.removeClass('on');
				return true;
			}

			box_details.show();
			that.addClass('on');

			keyword_get_details({
				that 		: that,
				box_details : box_details.find('td').eq(0),
				engine 		: $('body #select-engine').val(),
				kw_id 		: keywordid,
				interval 	: 'last-3-month'
			});
		});

		// keyword details: top100 show all/less rows
		$('body').on('click', ".psp-serp-kw-show-howmany > a", function(e){
			e.preventDefault();

			var that 		= $(this),
				parent 		= that.parents('.psp-serp-kw-show-howmany').eq(0),
				show_all 	= parent.data('show_all_text'),
				show_less 	= parent.data('show_less_text'),
				parent_big	= that.parents(".psp-keyword-details-content").eq(0),
				thetable 	= parent_big.find('table.serp-table-rank tbody');

			if ( that.hasClass('on') ) {
				that.removeClass('on');
				that.html( show_less );
				thetable.find('tr').addClass('on');
			}
			else {
				that.addClass('on');
				that.html( show_all );
				thetable.find('tr:not(.psp-serp-kwdetails-show)').removeClass('on');
			}
		});

		// keyword details: evolution chart
		$('body').on('click', ".psp-kwd-menu a", function(e){
			e.preventDefault();

			var that 		= $(this),
				parent 		= that.parents("tr").eq(0),
				box_details = parent,
				themenu		= that.parents('.psp-kwd-menu').eq(0),
				interval 	= that.data('interval'),
				keywordid 	= themenu.data('keywordid');

			if ( that.hasClass('on') ) {
				return false;
			}
			themenu.find('a').removeClass('on');
			that.addClass('on');

			keyword_get_evolution_chart({
				that 		: that,
				box_details : box_details.find('td').eq(0),
				engine 		: $('body #select-engine').val(),
				kw_id 		: keywordid,
				interval 	: interval
			});
		});

		// suggest competitors
		get_suggest_competitors();

		// save suggested competitor
		$('body').on('click', ".psp-serp-suggest-add", function(e){
			e.preventDefault();

			var that = $(this);
			var c_name = that.parents('.psp-serp-suggest-item:first').find('.psp-serp-suggest-host').text();

			c_name = $.trim( c_name );

			// validate
			if ( '' == c_name || c_name.length < 3 ) {
				if ( '' == c_name ) {
					$competitor.val( c_name );
				}
				msg_alert( 'You didn\'t complete the necessary fields!' );
				return false;
			}

			competitor_save({
				that 		: that,
				form 		: null,
				engine 		: $('body #select-engine').val(),
				competitor 	: c_name
			});
		});

		// load website stats
		load_ws_filters();
		load_ws_stats();

		$('body').on('click', ".psp-serp-ws-filters input[type='button']", function(e){
			e.preventDefault();

			var that 		= $(this),
				date_from 	= $('#date_from').datepicker().val(),
				date_to 	= $('#date_to').datepicker().val();

			date_from = $.trim( date_from );
			date_to = $.trim( date_to );

			// validate
			//if ( '' == date_from || date_from.length != 10 ) {
			//	msg_alert( 'The date from field is invalid!' );
			//	return false;
			//}
			if ( '' == date_to || date_to.length != 10 ) {
				msg_alert( 'The date to field is invalid!' );
				return false;
			}

			load_ws_stats({
				that 		: that
			});
		});
	}


	// :: SEARCH ENGINE
	function engine_select( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'engine_select' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function engine_only_used( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			only_used 	= misc.hasOwnProperty( pms, 'only_used' ) ? pms.only_used : 0;

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'engine_only_used' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "only_used", value: only_used });

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				//this must be first so init_custom_checkbox can work!!!
				refresh_html_engine( response.html_engine );

				refresh_html_main_table( response.html, response );

				// refresh stats;
				cronjob_status.make_request( true );
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}


	// :: COMPETITOR
	function competitor_save( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			competitor 	= misc.hasOwnProperty( pms, 'competitor' ) ? pms.competitor : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'competitor_save' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "competitor", value: competitor });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			if ( misc.hasOwnProperty( response, 'msg' ) ) {
				msg_log( response.msg );
				msg_alert( response.msg, msg_type_from_resp( response ) );
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function competitor_delete( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			id 			= misc.hasOwnProperty( pms, 'id' ) ? pms.id : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'competitor_delete' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "id", value: id });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			if ( misc.hasOwnProperty( response, 'msg' ) ) {
				msg_log( response.msg );
				msg_alert( response.msg, msg_type_from_resp( response ) );
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}


	// :: KEYWORDS
	function keywords_save( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			kw 			= misc.hasOwnProperty( pms, 'kw' ) ? pms.kw : '',
			delimiter	= misc.hasOwnProperty( pms, 'delimiter' ) ? pms.delimiter : 'newline';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'keywords_save' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "kw", value: kw });
		data_save.push({ name: "delimiter", value: delimiter });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			if ( misc.hasOwnProperty( response, 'msg' ) ) {
				msg_log( response.msg );
				msg_alert( response.msg, msg_type_from_resp( response ) );
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function keywords_delete( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			ids			= misc.hasOwnProperty( pms, 'ids' ) ? pms.ids : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'keywords_delete' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "ids", value: ids });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			if ( misc.hasOwnProperty( response, 'msg' ) ) {
				msg_log( response.msg );
				msg_alert( response.msg, msg_type_from_resp( response ) );
			}

			lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function keyword_update_rank( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			kw_id		= misc.hasOwnProperty( pms, 'kw_id' ) ? pms.kw_id : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'keyword_update_rank' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "kw_id", value: kw_id });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );

				get_suggest_competitors();
			}

			if ( misc.hasOwnProperty( response, 'msg' ) ) {
				msg_log( response.msg );
				msg_alert( response.msg, msg_type_from_resp( response ) );
			}

			//lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function keyword_get_details( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			kw_id		= misc.hasOwnProperty( pms, 'kw_id' ) ? pms.kw_id : '',
			interval	= misc.hasOwnProperty( pms, 'interval' ) ? pms.interval : '',
			box_details	= misc.hasOwnProperty( pms, 'box_details' ) ? pms.box_details : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'keyword_get_details' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "kw_id", value: kw_id });
		data_save.push({ name: "interval", value: interval });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				box_details.html( response.html_top100 );

				box_details.find(".psp-keyword-details").tabs();

				keyword_build_evolution_chart( box_details,	response.evolution_data );
			}

			//if ( misc.hasOwnProperty( response, 'msg' ) ) {
			//	msg_log( response.msg );
			//	msg_alert( response.msg, msg_type_from_resp( response ) );
			//}

			//lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function keyword_get_evolution_chart( pms ) {
		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '',
			kw_id		= misc.hasOwnProperty( pms, 'kw_id' ) ? pms.kw_id : '',
			interval	= misc.hasOwnProperty( pms, 'interval' ) ? pms.interval : '',
			box_details	= misc.hasOwnProperty( pms, 'box_details' ) ? pms.box_details : '';

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'keyword_get_evolution_chart' });
		data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "kw_id", value: kw_id });
		data_save.push({ name: "interval", value: interval });
		//console.log( data_save  ); return false;

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				keyword_build_evolution_chart( box_details,	response.evolution_data );
			}

			//if ( misc.hasOwnProperty( response, 'msg' ) ) {
			//	msg_log( response.msg );
			//	msg_alert( response.msg, msg_type_from_resp( response ) );
			//}

			//lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}

	function keyword_build_evolution_chart( box_details, evolution_data )
	{
		//http://www.chartjs.org/docs/latest/axes/cartesian/time.html#time-units

		// chart plugins
		Chart.pluginService.register( chart_plugin_minmax() );

		// chart options
		var options = {
			responsive: true,
			maintainAspectRatio: false,
			elements: {
				line: {
					tension: 0.4
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
				,datalabels: chart_plugin_datalabels()
			},
			scales: {
				xAxes: [{
					type: "time",
					time: {
						//format: 'MM/DD/YYYY',
						minUnit: 'day',
						//unit: 'day',
						//unitStepSize: 1,
						//round: 'day',
						tooltipFormat: 'll' //'ll HH:mm'
					},
					scaleLabel: {
						display: true,
						labelString: 'Date'
					},
					ticks: {
						autoSkip: false,
						maxRotation: 0
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Position'
					},
					ticks: {
						reverse: true
						//,min: 1, max: 100
						,min: evolution_data.min, max: evolution_data.max
					}
				}]
			}
		};

		// build chart - in canvas
		[false, 'origin', 'start', 'end'].forEach(function(boundary, index) {

			var chart = box_details.find("canvas.serp-chart-evolution"),
				charw = box_details.find(".psp-wrapper-serp-chart-evolution");

			charw.width( 
				//box_details.width()
				charw.parent().width()
			);
			charw.height( evolution_data.height );
			//chart.style.height = '128px';
			//console.log( box_details, box_details.width() );

			// create new chart - in canvas
			new Chart( chart[0], {
				type: 'line',

				//data: __keyword_keyword_build_evolution_chart_testing(),
				///*
				data: {
					//labels: evolution_data.labels,
					datasets: evolution_data.datasets
				},
				//*/

				options: $.extend(options, {
					title: {
						text: 'fill: ' + boundary,
						display: false
					},
					legend: {
						position: "bottom",
						labels: {
							padding: 45,
							boxWidth: 25, 
							fontSize: 13,
						}
					},
				})
			});
		});
	}

	// requires momment.js
	function __keyword_keyword_build_evolution_chart_testing() {

		var timeFormat = 'MM/DD/YYYY'; //'MM/DD/YYYY HH:mm';

		function newDate(days) {
			return moment().add(days, 'd').toDate();
		}

		function newDateString(days) {
			return moment().add(days, 'd').format(timeFormat);
		}

		function newTimestamp(days) {
			return moment().add(days, 'd').unix();
		}

		var color = Chart.helpers.color;

		var dataFull = {
				labels: [ // Date Objects
					newDate(0),
					newDate(1),
					/*newDate(2),
					newDate(3),
					newDate(4),
					newDate(5),*/
					newDate(6)
				],
				datasets: [{
					label: "My First dataset",
					backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
					borderColor: window.chartColors.red,
					fill: false,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					],
				}, {
					label: "My Second dataset",
					backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
					borderColor: window.chartColors.blue,
					fill: false,
					data: [
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor(),
						randomScalingFactor()
					],
				}, {
					label: "Dataset with point data",
					backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
					borderColor: window.chartColors.green,
					fill: false,
					data: [{
						x: newDateString(0),
						y: randomScalingFactor()
					}, {
						x: newDateString(5),
						y: randomScalingFactor()
					}, {
						x: newDateString(7),
						y: randomScalingFactor()
					}, {
						x: newDateString(15),
						y: randomScalingFactor()
					}],
				}]
		};
		return dataFull;
	}

	// chart plugin min max
	function chart_plugin_minmax() {
		var plugin_minmax_yaxes = {
			beforeInit: function( chart ) {

				var min = chart.config.options.scales.yAxes[0].ticks.min;
				var max = chart.config.options.scales.yAxes[0].ticks.max;

				var ticks = chart.config.data.labels;

				var idxMin = ticks.indexOf(min);
				var idxMax = ticks.indexOf(max);

				if ( idxMin == -1 || idxMax == -1 ) {
					return;
				}

				var data = chart.config.data.datasets[0].data;

				data.splice(idxMax + 1, ticks.length - idxMax);
				data.splice(0, idxMin);

				ticks.splice(idxMax + 1, ticks.length - idxMax);
				ticks.splice(0, idxMin);
			}
		};
		return plugin_minmax_yaxes;
	}

	// plugin datalabels
	function chart_plugin_datalabels() {
		var plugin_datalabels = {
			align: function(context) {
				var index = context.dataIndex;
				var curr = context.dataset.data[index];
				var prev = context.dataset.data[index - 1];
				var next = context.dataset.data[index + 1];
				return prev < curr && next < curr ? 'end' :
					prev > curr && next > curr ? 'start' :
					'center';
			},
			backgroundColor: 'rgba(255, 255, 255, 0.7)',
			borderColor: 'rgba(128, 128, 128, 0.7)',
			borderRadius: 4,
			borderWidth: 1,
			color: function(context) {
				var i = context.dataIndex;
				var value = context.dataset.data[i];
				var prev = context.dataset.data[i - 1];
				var diff = prev !== undefined ? value - prev : 0;
				return diff < 0 ? chartColors.red :
					diff > 0 ? chartColors.green :
					'gray';
			},
			font: {
				size: 11,
				weight: 600
			},
			offset: 8,
			formatter: function(value, context) {
				var i = context.dataIndex;
				var prev = context.dataset.data[i - 1];
				var diff = prev !== undefined ? prev - value : 0;
				var glyph = diff < 0 ? '\u25B2' : diff > 0 ? '\u25BC' : '\u25C6';
				return glyph + ' ' + Math.round(value);
			}
		};
		return plugin_datalabels;
	}


	// :: FOCUS KEYWORDS
	function focus_keywords_load( pms ) {
		// load it only on main page
		if ( ! $('.psp-fkw-ajax-table').length ) {
			return false;
		}

		//pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {};
			//ajax_id 	= get_main_table_ajaxid(),
			//only_used 	= misc.hasOwnProperty( pms, 'only_used' ) ? pms.only_used : 0;

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'focus_keywords_load' });
		//data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		//data_save.push({ name: "only_used", value: only_used });

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( response.status == 'valid' ) {
				refresh_html_main_table( response.html, response );
				$('.psp-fkw-ajax-table').html( response.html_fkw );
			}

			//lightbox.fadeOut('fast');
			//pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}


	// :: SUGGEST COMPETITORS
	function get_suggest_competitors( pms ) {
		// load it only on main page
		if ( ! $('.psp-serp-suggest').length ) {
			return false;
		}

		//pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {},
			//ajax_id 	= get_main_table_ajaxid(),
			engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '';

		var $box = $('body .psp-serp-suggest');

		if ( '' == engine ) {
			engine = $('body #select-engine').val();
		}

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'get_suggested_competitors' });
		//data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		data_save.push({ name: "engine", value: engine });

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( misc.hasOwnProperty( response, 'status') ) {
				$box.find('.psp-serp-suggest-list').html( response.html );
			}

			if ( response.status == 'valid' ) {
				$box.show();
			}
			else if ( response.status == 'invalid' ) {
				$box.hide();
			}

			//lightbox.fadeOut('fast');
			//pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}


	// :: LOAD SERP STATS
	function load_ws_filters() {
		//new Date() "14/12/2014"
		$('#date_from')
		.datepicker({
			//defaultDate: "-1m",
			maxDate: '0'
		})
		.datepicker( 'setDate', $('#date_from').datepicker().val() );

		$('#date_to')
		.datepicker({
			maxDate: '0'
		})
		.datepicker( 'setDate', $('#date_to').datepicker().val() );
	}

	function load_ws_stats( pms ) {
		// load it only on main page
		if ( ! $('.psp-serp-ws-stats').length ) {
			return false;
		}

		pspFreamwork.to_ajax_loader( "Loading..." );

		var pms = pms || {};
			//ajax_id 	= get_main_table_ajaxid(),
			//engine 		= misc.hasOwnProperty( pms, 'engine' ) ? pms.engine : '';

		var $box = $('body .psp-serp-ws-stats');

		var data_save = [];
		data_save.push({ name: "action", value: "pspSERP" });
		data_save.push({ name: "sub_action", value: 'load_ws_stats' });
		//data_save.push({ name: "ajax_id", value: ajax_id });
		data_save.push({ name: "debug_level", value: debug_level });

		//data_save.push({ name: "engine", value: engine });
		data_save.push({ name: "date_from", value: $('#date_from').datepicker().val() });
		data_save.push({ name: "date_to", value: $('#date_to').datepicker().val() });
		data_save.push({ name: "include_competitors", value: $('#psp-serp-ws-include-competitors').val() });

		// start ajax request
		jQuery.post(ajaxurl, data_save, function(response) {

			if ( misc.hasOwnProperty( response, 'status') ) {
				$box.html( response.html ).show();
			}

			//lightbox.fadeOut('fast');
			pspFreamwork.to_ajax_loader_close();
			return false;

		}, 'json');
		// end ajax request
	}


	// :: UTILS
	function msg_type_from_resp( response ) {
		var stat = misc.hasOwnProperty( response, 'status') ? response.status : 'invalid';
		return 'valid' == stat ? 'success' : 'error';
	}

	function msg_log( msg ) {
		//$("#psp-serp-debug-log").prepend( msg ).show();
	}

	function msg_alert( msg, type ) {
		var type = type || 'error';

		swal({
			type: type,
			html: msg
		});
	}

	function msg_confirm( msg, call_success, call_fail ) {
		var type = 'warning',
			call_success = call_success || null,
			call_fail 	 = call_fail || null;

		swal({
			type: type,
			html: msg,
			showCancelButton: true,
			confirmButtonColor: "#cb3c46",
			confirmButtonText: "Yes, delete it!"
		})
		.then(
			function() {
				if ( $.isFunction( call_success ) ) {
					call_success();
				}
			},
			function( dismiss ) {
				if ( $.isFunction( call_fail ) ) {
					call_fail();
				}
			}
		);
	}

	function get_main_table() {
		return $("#psp-main-ajax-table #psp-table-ajax-response");
	}

	function get_main_table_ajaxid() {
		return get_main_table().find('.psp-ajax-list-table-id').val();
	}

	function refresh_html_main_table( html, response ) {
		var response = response || {};

		get_main_table().html( html );
		pspFreamwork.init_custom_checkbox();

		// refresh stats
		if ( misc.hasOwnProperty( response, 'msg_stats' ) ) {
			$(".psp-main .psp-serp-cron-stats").html( response.msg_stats ).show();
		}
	}

	function refresh_html_engine( html ) {
		$("#psp-serp-engine-response").html( html );
	}


	//:: CRONJOB STATS
	var cronjob_status = (function() {
		
		var DISABLED 			= false; // disable this module!
		var debug_level			= 0,
			reload_timer 		= null,
			reload_interval 	= 30, // reload products interval in seconds
			reload_countdown 	= reload_interval,
			maincontainer 		= null,
			what 				= '',
			current_total 		= 0;

		// Test!
		function __() {};

		// get public vars
		function get_vars() {
			return $.extend( {}, {} );
		};

		// init function, autoload
		(function init() {
			// load the triggers
			$(document).ready(function() {
				maincontainer = $(".psp-main .psp-serp-cron-stats");
				what          = maincontainer.data('what');

				triggers();
			});
		})();

		// Triggers
		function triggers() {
			if ( DISABLED ) return false;
			else {
				make_request(); // initialy run ajax
				reload_();
			}
		}

		// make request
		function make_request( force_load_suggest ) {
			var force_load_suggest = force_load_suggest || false;
			var data = [];

			var ajax_id 	= get_main_table_ajaxid();
			var engine 		= $('body #select-engine').val();
			
			//pspFreamwork.to_ajax_loader( "Loading..." );

			what = $.inArray(what, ['kw_rank']) > -1 ? what : '';
			if ( '' == what ) {
				//pspFreamwork.to_ajax_loader_close();
				return false;
			}

			var sub_action = 'stats_' + what;
			data.push({name: 'action', value: 'pspSERP'});
			data.push({name: 'sub_action', value: sub_action});
			data.push({name: 'debug_level', value: debug_level});

			//data.push({name: "ajax_id", value: ajax_id});
			data.push({name: "engine", value: engine});
			
			data = $.param( data ); // turn the result into a query string
			
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {

				if( response.status == 'valid' || response.status == 'invalid' ){

					maincontainer.html( response.msg ).show();

					if ( misc.hasOwnProperty(response, 'total') ) {
						if ( (current_total != response.total) || force_load_suggest ) {
							get_suggest_competitors();
						}
						current_total = response.total;
					}

					reload_();
				}

				//pspFreamwork.to_ajax_loader_close();
			}, 'json');
		}

		function reset_timer() {
			// delete old timer
			clearTimeout(reload_timer);
			reload_timer = null;
		}

		function stop_reload() {
			return reload_countdown <= 0 ? true : false;
		}

		function reload_() {

			// verify if stopped!
			if ( stop_reload() ) {
				// delete old timer
				reset_timer();
				return false;
			}

			function reload() {
				//console.log( reload_timer, ',', reload_countdown );

				// verify if stopped!
				if ( stop_reload() ) {
					// delete old timer
					reset_timer();
					return false;
				}
	
				reload_countdown--;
				if ( reload_countdown <= 0 ) {
					// delete old timer
					reset_timer();
					
					reload_countdown = reload_interval;
					
					// re-do ajax
					make_request();
				} else {
					reload_timer = setTimeout(reload, 1000);
				}
			};
			reload_timer = setTimeout(reload, 1000);
		}

		// external usage
		return {
			make_request: make_request
		}
	})();

	// :: MISC
	var misc = {

		hasOwnProperty: function(obj, prop) {
			var proto = obj.__proto__ || obj.constructor.prototype;
			return (prop in obj) &&
			(!(prop in proto) || proto[prop] !== obj[prop]);
		},

		isNormalInteger: function(str, positive) {
			//return /^\+?(0|[1-9]\d*)$/.test(str);
			return /^(0|[1-9]\d*)$/.test(str);
		}

	};


	// external usage
	return {
	}
})(jQuery);

/*
if (typeof String.prototype.startsWith != 'function') {
  // see below for better implementation!
  String.prototype.startsWith = function (str){
	return this.indexOf(str) == 0;
  };
}
*/