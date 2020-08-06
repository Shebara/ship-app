/**
 * On document ready, get the data and set the appropriate handler
 */
$( document ).ready( () => {
    if ( ! window.id ) {
        message( true, 'Error!', 'Missing ID.' );
    }

    post( 'admin', {
        page: 'crew',
        id: window.id
    }, ( response ) => {
        const data = response.data;

        console.log( data );

        formSubmit( 'crew', () => {
            message( true, 'Thank you!', 'The crew member was successfully added.' );
            redirect( 'crew' );
        } );
    } );
} );
