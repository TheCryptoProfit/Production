/**
 * global WITHEMES_ADMIN
 *
 * @since 1.0
 */
(function( window, WITHEMES_ADMIN, $ ) {
"use strict";
    
    // cache element to hold reusable elements
    WITHEMES_ADMIN.cache = {
        $document : {},
        $window   : {}
    }
    
    // Create cross browser requestAnimationFrame method:
    window.requestAnimationFrame = window.requestAnimationFrame
    || window.mozRequestAnimationFrame
    || window.webkitRequestAnimationFrame
    || window.msRequestAnimationFrame
    || function(f){setTimeout(f, 1000/60)}
    
    /**
     * Init functions
     *
     * @since 1.0
     */
    WITHEMES_ADMIN.init = function() {
        
        /**
         * cache elements for faster access
         *
         * @since 1.0
         */
        WITHEMES_ADMIN.cache.$document = $(document);
        WITHEMES_ADMIN.cache.$window = $(window);
        
        WITHEMES_ADMIN.cache.$document.ready(function() {
        
            WITHEMES_ADMIN.reInit();
            
        });
        
    }
    
    /**
     * Initialize functions
     *
     * And can be used as a callback function for ajax events to reInit
     *
     * This can be used as a table of contents as well
     *
     * @since 1.0
     */
    WITHEMES_ADMIN.reInit = function() {
        
        // Conditional Metabox
        WITHEMES_ADMIN.conditionalMetabox();
     
        // image Upload
        WITHEMES_ADMIN.imageUpload();
        
        // multiple-image Upload
        WITHEMES_ADMIN.imagesUpload();
        
        // colorpicker
        WITHEMES_ADMIN.colorpicker();
        
        // Review
        WITHEMES_ADMIN.review();
        
    }
    
    // Conditional metabox
    // ========================
    WITHEMES_ADMIN.conditionalMetabox = function() {
        
        // lib required
        if ( ! $().metabox_conditionize ) {
            return;
        }
    
        $( '.wi-metabox-field[data-cond-option]' ).metabox_conditionize();
        
    }
    
    // Thickbox Image Upload
    // ========================
    WITHEMES_ADMIN.imageUpload = function() {
        
        var mediaUploader
    
        // Append Image Action
        WITHEMES_ADMIN.cache.$document.on( 'click', '.upload-image-button', function( e ) {
            
            e.preventDefault();
            
            var button = $( this ),
                uploadWrapper = button.closest( '.wi-upload-wrapper' ),
                holder = uploadWrapper.find( '.image-holder' ),
                input = uploadWrapper.find( '.media-result' );
            
            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: WITHEMES_ADMIN.l10n.choose_image,
                button: {
                    text: WITHEMES_ADMIN.l10n.choose_image,
                }, 
                multiple: false,
                library : {
                    type : 'image',
                    // HERE IS THE MAGIC. Set your own post ID var
                    // uploadedTo : wp.media.view.settings.post.id
                },
            });

            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on('select', function() {
                
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                if ( attachment.type == 'image' ) {

                    input.val(attachment.id);
                    holder.find('img').remove();
                    if ( attachment.sizes.medium ) {
                        holder.prepend( '<img src="' + attachment.sizes.medium.url + '" />' );
                    } else {
                        holder.prepend( '<img src="' + attachment.url + '" />' );
                    }
                    
                    button.val( WITHEMES_ADMIN.l10n.change_image );
                    
                }

            });
            // Open the uploader dialog
            mediaUploader.open();
        
        });
        
        // Remove Image Action
        WITHEMES_ADMIN.cache.$document.on( 'click', '.remove-image-button', function( e ) {
            
            e.preventDefault();
            
            var remove = $( this ),
                uploadWrapper = remove.closest( '.wi-upload-wrapper' ),
                holder = uploadWrapper.find( '.image-holder' ),
                input = uploadWrapper.find( '.media-result' ),
                button = uploadWrapper.find( '.upload-image-button' );
            
            input.val('');
            holder.find( 'img' ).remove();
            button.val( WITHEMES_ADMIN.l10n.upload_image );
            
        });
    
    }
    
    // Upload Multiplage Images
    // ========================
    WITHEMES_ADMIN.imagesUpload = function() {
        
        var mediaUploader,
        
            sortableCall = function() {
            
                // sortable required
                if ( !$().sortable ) {
                    return;
                }

                $( '.images-holder' ).each(function() {

                    var $this = $( this );
                    $this.sortable({

                        placeholder: 'image-unit-placeholder', 

                        update: function(event, ui) {

                            // trigger event changed
                            var uploadWrapper = $this.closest( '.wi-upload-wrapper' );
                            uploadWrapper.trigger( 'changed' );

                        }

                    }); // sortable

                    $this.disableSelection();

                });

            },
            
            refine = function() {
            
                var uploadWrapper = $( this ),
                    holder = uploadWrapper.find( '.images-holder' ),
                    input = uploadWrapper.find( '.media-result' ),
                    id_string = [];

                // not images type
                if ( !holder.length ) {
                    return;
                }

                // otherwise, we rearrange everythings
                holder.find( '.image-unit' ).each(function() {

                    var unit = $( this ),
                        id = unit.data( 'id' );

                    id_string.push( id );

                } );

                input.val( id_string.join() );
            
            }
        
        // call sortable
        sortableCall();
        
        // refine the input the get result
        $( '.wi-upload-wrapper' ).on( 'changed', refine );
    
        // Append Image Action
        WITHEMES_ADMIN.cache.$document.on( 'click', '.upload-images-button', function( e ) {
            
            e.preventDefault();
            
            var button = $( this ),
                uploadWrapper = button.closest( '.wi-upload-wrapper' ),
                holder = uploadWrapper.find( '.images-holder' ),
                input = uploadWrapper.find( '.media-result' );
            
            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: WITHEMES_ADMIN.l10n.choose_images,
                button: {
                    text: WITHEMES_ADMIN.l10n.choose_images,
                }, 
                multiple: true,
                library : {
                    type : 'image',
                    // HERE IS THE MAGIC. Set your own post ID var
                    // uploadedTo : wp.media.view.settings.post.id
                },
            });

            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on( 'select' , function() {
                
                var attachments = mediaUploader.state().get('selection').toJSON();
                
                var remaining_attachments = [],
                    existing_ids = [];
                if ( input.val() ) {
                    existing_ids = input.val().split(',');
                }

                // remove duplicated images
                for ( var i in attachments ) {
                    var attachment = attachments[i],
                        item = '';
                    if ( existing_ids.indexOf( attachment.id.toString() ) < 0 ) {
                        
                        item += '<figure class="image-unit" data-id="' + attachment.id + '">';
                        item += '<img src="' + attachment.sizes.thumbnail.url +'" />';
                        item += '<a href="#" class="remove-image-unit" title="' + WITHEMES_ADMIN.l10n.remove_image + '">&times;</a>';
                        item += '</figure>';
                        holder.append( item );
                        
                    }
                }
                
                uploadWrapper.trigger( 'changed' );

            });
            
            // Open the uploader dialog
            mediaUploader.open();
        
        });
        
        // Remove Image Action
        WITHEMES_ADMIN.cache.$document.on( 'click', '.remove-image-unit', function( e ) {
            
            e.preventDefault();
            
            var remove = $( this ),
                uploadWrapper = remove.closest( '.wi-upload-wrapper' ),
                item = remove.closest( '.image-unit' );

            item.remove();
            uploadWrapper.trigger( 'changed' );
            
        });
    
    }
    
    // Color picker
    // ========================
    WITHEMES_ADMIN.colorpicker = function() {
        
        // wpColorPicker required
        if ( ! $().wpColorPicker ) {
            return;
        }
    
        $( '.colorpicker-input' ).wpColorPicker();
    
    }
    
    // Review
    // ========================
    WITHEMES_ADMIN.review = function() {
        
        var running = false;
        
        // Reorder all criteria
        var refine = function() {
            
            var list = $( this )
        
            list.find( '.review' ).each(function( i ) {
                
                var $this = $( this ),
                    criterion = $this.find( '.review-criterion' ),
                    score = $this.find( '.review-score' );
                
                $this.attr( 'data-order', i );
            
                criterion.find( 'input' ).attr({
                    id : $this.data( 'id' ) + '[' + i + ']' + '[' + criterion.data( 'property' ) + ']',
                    name : $this.data( 'id' ) + '[' + i + ']' + '[' + criterion.data( 'property' ) + ']',
                });
                
                score.find( 'input' ).attr({
                    id : $this.data( 'id' ) + '[' + i + ']' + '[' + score.data( 'property' ) + ']',
                    name : $this.data( 'id' ) + '[' + i + ']' + '[' + score.data( 'property' ) + ']',
                });
            
            } )
            
        }
        
        // refine each time the list being changed
        $( '.review-list' ).on( 'changed', refine );
    
        // Add New Event
        WITHEMES_ADMIN.cache.$document.on( 'click', '.new-review', function( e ) {
            
            // don't do anything when running
            if ( running ) {
                return;
            }
        
            e.preventDefault();
            
            var button = $( this ),
                wrapper = button.closest( '.review-wrapper' ),
                list = wrapper.find( '.review-list' ),
                clone = list.find( '.review' ).first().clone();
            
            clone.find( 'input' ).val( '' );
            
            clone.appendTo( list );
            
            list.trigger( 'changed' );
        
        } );
        
        // Remove Event
        WITHEMES_ADMIN.cache.$document.on( 'click', '.remove-review', function( e ) {
            
            // don't do anything when running
            if ( running ) {
                return;
            }
        
            e.preventDefault();
            
            var remove = $( this ),
                list = remove.closest( '.review-list' ),
                item = remove.closest( '.review' );
            
            if ( list.find( '.review' ).length > 1 ) {
            
                item.slideUp( 200, function() {
                    item.remove();
                    list.trigger( 'changed' );
                    running = false;
                } );
            
            // DO NOT REMOVE THE LAST ITEM
            } else {
            
                // We just need to reset all inputs
                item.find( 'input' ).val( '' );
                list.trigger( 'changed' );
                running = false;
            
            }
        
        } );
        
        // Sortable
        if ( $().sortable ) {
            
            $( '.review-list' ).each( function() {
                
                var list = $( this );
                
                list.sortable({
                
                    placeholder : 'review-placeholder',
                    start : function( event, ui ) {
                        running = true;
                    },
                    update: function( event , ui ) {
                        
                        list.trigger( 'changed' );
                        running = false;
                        
                    }
                
                })
            
            });
        
        }
    
    }
    
    WITHEMES_ADMIN.init();
    
})( window, WITHEMES_ADMIN, jQuery );

// Library Show Hide Conditional
// =================================================================

(function($) {
  $.fn.metabox_conditionize = function(options) {

    var settings = $.extend({
        hideJS : true,
        repeat : true,
    }, options );

    $.fn.eval = function(valueIs, valueShould, operator) {
      switch(operator) {
        case '==':
            return valueIs == valueShould;
            break;
        case '!=':
            return valueIs != valueShould;
            break;  
        case '<=':
            return valueIs <= valueShould;
            break;  
        case '<':
            return valueIs < valueShould;
            break;  
        case '>=':
            return valueIs >= valueShould;
            break;  
        case '>':
            return valueIs > valueShould;
            break;  
        case 'in':
            valueShould = valueShould.split( ',' );
            return ( typeof( valueShould ) == 'object' && $.inArray( valueIs, valueShould ) >= 0 ) ;
            break;
      }
    }

    $.fn.showOrHide = function(listenTo, listenFor, operator, $section) {
      if ($(listenTo).is('select, input[type=text]') && $.fn.eval($(listenTo).val(), listenFor, operator)) {
        $section.show(0,function(){$(this).trigger('show');});
      }
      else if ($(listenTo + ":checked").filter(function(idx, elem){return $.fn.eval(elem.value, listenFor, operator);}).length > 0) {
          $section.show(0,function(){$(this).trigger('show');});
      }
      else {
          $section.hide(0,function(){$(this).trigger('hide');});
      }
    }

    return this.each( function() {
      var listenTo = "[data-id=" + $(this).data('cond-option').replace( /(:|\.|\[|\]|,)/g, "\\$1" ) + "] input";
      var listenFor = $(this).data('cond-value');
      var operator = $(this).data('cond-operator') ? $(this).data('cond-operator') : '==';
      var $section = $(this);

      //Set up event listener
      $(listenTo).on('change', function() {
        $.fn.showOrHide(listenTo, listenFor, operator, $section);
      });
        
        // if process repeated
        if ( settings.repeat ) {
            $(listenTo).closest('.wi-metabox-field').on('show', function() {
                $section.show(0,function(){$(this).trigger('show');});
                $.fn.showOrHide(listenTo, listenFor, operator, $section);
          });
            $(listenTo).closest('.wi-metabox-field').on('hide', function() {
            $section.hide(0,function(){$(this).trigger('hide');});
          });
        }
        
      //If setting was chosen, hide everything first...
      if (settings.hideJS) {
        $(this).hide();
      }
      //Show based on current value on page load
      $.fn.showOrHide(listenTo, listenFor, operator, $section);
    });
  }
}(jQuery));