/**
 * On document ready, get the data and set the appropriate handler
 */
$( document ).ready( () => {
    formSubmit( 'rank', () => {
        message( true, 'Thank you!', 'The rank was successfully added.' );
        redirect( 'ranks' );
    } );
} );
