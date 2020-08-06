/**
 * On document ready, get the data and set the appropriate handler
 */
$( document ).ready( () => {
    if ( ! window.id ) {
        message( true, 'Error!', 'Missing ID.' );
    }

    post( 'admin', {
        page: 'ranks',
        id: window.id
    }, ( response ) => {
        const data = response.data;

        console.log( data );

        formSubmit( 'rank', () => {
            message( true, 'Thank you!', 'The rank was successfully added.' );
            redirect( 'ranks' );
        } );
    } );
} );
