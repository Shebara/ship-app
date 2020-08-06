/**
 * Add data to form
 *
 * @param data
 */
const assignData = ( data ) => {
    console.log( data );
};

/**
 * On document ready, get the data and set the appropriate handler
 */
$( document ).ready( () => {
    if ( ! window.id ) {
        message( true, 'Error!', 'Missing ID.' );
    }

    post( 'admin', {
        page: 'ships',
        id: window.id
    }, ( response ) => {
        assignData( response.data );
        spinner();
        message();
        formSubmit( 'ship', () => {
            message( true, 'Thank you!', 'The ship was successfully added.' );
            redirect( 'ships' );
        } );
    } );
} );
