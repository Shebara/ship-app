/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    formSubmit( 'crew', () => {
        message( true, 'Thank you!', 'The crew member was successfully added.' );
        redirect( 'crew' );
    } );
} );
