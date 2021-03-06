/**
 * On document ready, get the data
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
        $( '#name' ).val( data.name );
        spinner();
        message();
    } );
} );
