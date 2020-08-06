/**
 * Add data to form
 *
 * @param data
 */
const assignData = ( data ) => {
    $( '#name' ).val( data.name );
    $( '#surname' ).val( data.surname );
    $( '#email' ).val( data.email );
    $( '#rank' ).val( data.rank );

    if ( data.ship !== null ) {
        $( '#ship' ).val( data.ship );
    }
};

/**
 * On document ready, get the data and set the appropriate handler
 */
$( document ).ready( () => {
    if ( ! window.id ) {
        spinner();
        message( true, 'Error!', 'Missing ID.' );

        return;
    }

    post( 'admin', {
        page: 'crew',
        id: window.id
    }, ( response ) => {
        assignData( response.data );
        spinner();
        message();
    } );
} );
