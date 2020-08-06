/**
 * Add data to form
 *
 * @param data
 */
const assignData = ( data ) => {
    $( '#name' ).val( data.name );
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
        page: 'ranks',
        id: window.id
    }, ( response ) => {
        assignData( response.data );
        spinner();
        message();
    } );
} );
