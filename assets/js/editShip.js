/**
 * Add data to form
 *
 * @param data
 */
const assignData = ( data ) => {
    assignImage( data.image_url );
    $( '#name' ).val( data.name );
    $( '#serial_number' ).val( data.serial_number );
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
        page: 'ships',
        id: window.id
    }, ( response ) => {
        assignData( response.data );
        spinner();
        message();
    } );
} );
