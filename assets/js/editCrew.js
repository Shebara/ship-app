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
        page: 'crew',
        id: window.id
    }, ( response ) => {
        assignData( response.data );
        spinner();
        message();
        formSubmit( 'crew', () => {
            message( true, 'Thank you!', 'The crew member was successfully added.' );
            redirect( 'crew' );
        } );
    } );
} );
