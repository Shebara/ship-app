/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'index', {}, ( response ) => {
        console.log( response );
    } );
} );
