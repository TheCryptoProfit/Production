( function( api ) {

	// Extends our custom "editorialmag" section.
	api.sectionConstructor['editorialmag'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
