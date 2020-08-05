/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {}, ( response ) => {
        console.log( response );
    } );
} );
