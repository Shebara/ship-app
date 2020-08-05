/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    formSubmit( 'login', ( response ) => {
        console.log( response );
    } );
} );
