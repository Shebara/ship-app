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
        formSubmit( 'rank', () => {
            message( true, 'Thank you!', 'The rank was successfully added.' );
            redirect( 'ranks' );
        } );
    } );
} );
