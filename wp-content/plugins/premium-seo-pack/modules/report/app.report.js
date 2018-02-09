/*
Document   :  Report
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
pspReport = (function ($) {
    "use strict";

    // public
    var debug_level = 0;
    var maincontainer = null;
    var loaded_page = 0;
    
    var lang = null;
    
    var mainsync = null;
    var synctable = null;
    var $form_settings = null;
    //var module = 'report';
    
    var load_is_running = false; // load rows is already running;

    
    // init function, autoload
    (function init() {
        // load the triggers
        $(document).ready(function(){
            
            maincontainer = $("#psp");

            lang = maincontainer.find('#psp-lang-translation').html();
            //lang = JSON.stringify(lang);
            lang = JSON && JSON.parse(lang) || $.parseJSON(lang);

            mainsync = maincontainer.find("#psp-report");
            synctable = mainsync.find('.psp-sync-table');
            $form_settings = mainsync.find('form#psp-sync-settings');
            //module = mainsync.data('module');
            
            triggers();
            
            jQuery('i, a').tipsy({live: true, gravity: 'w'});
        });
    })();
    
    // Load list
    function loadRows( pms ) {
        var pms             = typeof pms == 'object' ? pms : {},
            box             = misc.hasOwnProperty(pms, 'callback') ? pms.callback : null,
            subaction       = misc.hasOwnProperty(pms, 'subaction') ? pms.subaction : 'load_logs',
            filter          = misc.hasOwnProperty(pms, 'filter') ? pms.filter : '';

        var data = [];
        
        // already loading...
        if ( load_is_running ) {
            if ( typeof callback != 'undefined' && $.isFunction(callback) ) {
                callback();
            }
            return false;
        }
        load_is_running = true;
		
        pspFreamwork.to_ajax_loader( lang.loading );

        data.push({name: 'action', value: 'psp_report'});
        data.push({name: 'subaction', value: subaction});
        data.push({name: 'filter', value: filter});

        data.push({name: 'debug_level', value: debug_level});
        
        data = $.param( data ); // turn the result into a query string
        
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {

            if( response.status == 'valid' ){
                synctable.find('> table > tbody').html( response.html );
                mainsync.find('.psp-sync-filters > span span.count').html( response.nb );
            }
            
            pspFreamwork.to_ajax_loader_close();
            
            if ( typeof callback != 'undefined' && $.isFunction(callback) ) {
                callback();
            }
            load_is_running = false;
        }, 'json');
    }
    
    // view log
    function view_log( row )
    {
        var data = [];

        pspFreamwork.to_ajax_loader( lang.loading );
  
        data.push({name: 'action', value: 'psp_report'});
        data.push({name: 'subaction', value: 'view_log'});
        data.push({name: 'debug_level', value: debug_level});
        
        data.push({name: 'id', value: row.data('id')});
        
        data = $.param( data ); // turn the result into a query string
 
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {

            if( response.status == 'valid' ){
                $(".psp-report-log").append( response.html );
            }
            
            pspFreamwork.to_ajax_loader_close();
        }, 'json');
    }

    // save pdf
    function download_pdf( row )
    {
        var data = [];
        
        data.push({name: 'action', value: 'psp_report'});
        data.push({name: 'subaction', value: 'download_pdf'});
        data.push({name: 'debug_level', value: debug_level});
        
        data.push({name: 'id', value: row.data('id')});
        
        data = $.param( data ); // turn the result into a query string
        //console.log( data  ); return false;

        window.location.href = ajaxurl + '?' + data;
        return false;
    }

    function send_email( row )
    {
        var data = [];

        pspFreamwork.to_ajax_loader( lang.loading );
  
        data.push({name: 'action', value: 'psp_report'});
        data.push({name: 'subaction', value: 'send_email'});
        data.push({name: 'debug_level', value: debug_level});
        
        data.push({name: 'id', value: row.data('id')});
        
        data = $.param( data ); // turn the result into a query string
 
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {

            if( response.status == 'valid' ){
                swal('Email was sent successfully!');
            }
            else {
                swal('Email cound not be sent!', '', 'error');
            }
            
            pspFreamwork.to_ajax_loader_close();
        }, 'json');
    }
    
    function triggers()
    {
        maincontainer.on("click", '.psp-report-log-lightbox, a#psp-close-btn', function(e){
        	var that = $(this);
        	
            if( that.is('#psp-close-btn') || $(e.target).is('.psp-report-log-lightbox') ) {
            	e.preventDefault();
            	
            	$(".psp-report-log-lightbox").remove();
            }
        });
        
		$(document).keyup(function(e) {
			if (e.keyCode == 27) $('a#psp-close-btn').click();
		});
		
        // load rows
        maincontainer.on('click', '.psp-sync-filters span.right button.load_rows', function(e){
            e.preventDefault();

            loadRows();
        });
        loadRows(); // default page load
        
        // view log
        synctable.on('click', 'td.psp-sync-now button.view_log', function(e){
            e.preventDefault();
 
            var that    = $(this),
                row     = that.parents("tr").eq(0);

            view_log( row );
        });

        // save pdf
        synctable.on('click', 'td.psp-sync-now button.download_pdf', function(e){
            e.preventDefault();
 
            var that    = $(this),
                row     = that.parents("tr").eq(0);
     
            download_pdf( row );
        });

        // send email
        synctable.on('click', 'td.psp-sync-now button.send_email', function(e){
            e.preventDefault();
 
            var that    = $(this),
                row     = that.parents("tr").eq(0);
     
            send_email( row );
        });
        
        // filters
        maincontainer.on('change', 'select[name=psp-filter-log_id]', function(e){
            e.preventDefault();

            loadRows({
                'subaction' : 'log_id',
                'filter'    : $(this).val()
            });
        })
        .on('change', 'select[name=psp-filter-log_action]', function(e){
            e.preventDefault();

            loadRows({
                'subaction' : 'log_action',
                'filter'    : $(this).val()
            });
        });
    }
    
    var misc = {
    
        hasOwnProperty: function(obj, prop) {
            var proto = obj.__proto__ || obj.constructor.prototype;
            return (prop in obj) &&
            (!(prop in proto) || proto[prop] !== obj[prop]);
        },
    
        size: function(obj) {
            var size = 0;
            for (var key in obj) {
                if (misc.hasOwnProperty(obj, key)) size++;
            }
            return size;
        },
        
        format: function() {
            // The string containing the format items (e.g. "{field}")
            // will and always has to be the first argument.
            var args = arguments,
                str = args[0];
 
            return str.replace(/{(\d+)}/g, function(match, number) {
                return typeof args[number] !== 'undefined' ? args[number] : match;
            });
        },
        
        is_browser: function() {
            if(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())){
                return 'chrome';
            }
            return 'default';
        },
        
        // preserve = choose yes if you want to preserve html tags
        decodeEntities: (function() {
            var preserve = false;
   
            // create a new html document (doesn't execute script tags in child elements)
            // this also prevents any overhead from creating the object each time
            var doc = document.implementation.createHTMLDocument("");
            var element = doc.createElement('div');
                
            // regular expression matching HTML entities
            var entity = /&(?:#x[a-f0-9]+|#[0-9]+|[a-z0-9]+);?/ig;
        
            function getText(str) {
                if ( preserve ) {
                    // find and replace all the html entities
                    str = str.replace(entity, function(m) {
                        element.innerHTML = m;
                        return element.textContent;
                    });
                } else {
                    element.innerHTML = str;
                    str = element.textContent;
                }
                element.textContent = ''; // reset the value
                return str;
            }
        
            function decodeHTMLEntities(str, _preserve) {
                preserve = _preserve || false;
                if (str && typeof str === 'string') {

                    str = getText(str);
                    if ( preserve ) {
                        return str;
                    } else {
                        // called twice because initially text might be encoded like this: &lt;img src=fake onerror=&quot;prompt(1)&quot;&gt;
                        return getText(str);
                    }
                }
            }
            return decodeHTMLEntities;
        })(),
        decodeEntities2: function(str, preserve) {
            var preserve = preserve || false;

            if ( preserve )
                return $("<textarea/>").html(str).text();
            else
                return $("<div/>").html(str).text();
        }
    
    };

    // external usage
    return {
    }
})(jQuery);
