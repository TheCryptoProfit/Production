/**
 * @since 2.3
 */
( function( $ , api ) {
        
    /**
	 * Multicheckbox
     *
     * @since 2.3
	 */
	api.controlConstructor.multicheckbox = api.Control.extend({
		ready: function() {
            var control = this,
                hidden = this.container.find( '.checkbox-result' ),
				inputs = this.container.find( 'input[type="checkbox"]' ),
                values = control.setting();
            
            if ( 'string' === typeof values ) values = values.split( ',' );
            
            inputs.each(function(){
                var checked = values.indexOf( $(this).attr( 'value' ) ) > -1;
                $( this ).prop( 'checked', checked );
            });
            
            // set deafult
            if ( 'string' !== typeof values ) values = values.join( ',' );
            hidden.val( values );
            
            // input changes
            inputs.change(function(){
                
                var checkbox_values = control.container.find( 'input[type="checkbox"]:checked' ).map(
                    function(){
                        return this.value;
                    }
                ).get().join( ',' );
                
                control.setting.set( checkbox_values );
                
            });
            
		}
	});
    
} )( jQuery, wp.customize );