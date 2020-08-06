/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    formSubmit( 'rank', () => {
        message( true, 'Thank you!', 'The rank was successfully added.', 'ranks' );
        redirect( 'ranks' );
    } );
} );
