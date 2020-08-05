/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'crew'
    }, ( response ) => {
        console.log( response );
    } );
} );
