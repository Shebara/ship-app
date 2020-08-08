/**
 * Show the retrieved data on the page
 *
 * @param data
 */
const assignData = ( data ) => {
    console.log( data );

    listCrew( data.crew );
}

/**
 * On document ready, get the data
 */
$( document ).ready( () => {
    if ( ! window.id ) {
        spinner();
        message( true, 'Error!', 'Missing ID.' );

        return;
    }

    post( 'admin', {
        page: 'ranks',
        id: window.id,
        crew: true
    }, ( response ) => {
        assignData( response.data );

        spinner();
        message();
    } );
} );
