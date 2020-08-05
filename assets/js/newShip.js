/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    formSubmit( 'ship', () => {
        message( true, 'Thank you!', 'The ship was successfully added.' );
        redirect( 'ships' );
    } );
} );
