/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'ships'
    }, ( response ) => {
        console.log( response );
    } );
} );
