/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'ranks'
    }, ( response ) => {
        console.log( response );
    } );
} );
