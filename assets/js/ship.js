/**
 * Show the retrieved data on the page
 *
 * @param data
 */
const assignData = ( data ) => {
    $( '#page' ).html( `
        <img src="${data.image_url}" alt="${data.name}" />
        <h2>${data.name}</h2>
        <h4>Serial Number: ${data.serial_number}</h4>
        <hr />
        <h4>Crew</h4>
    ` );
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
        page: 'ships',
        id: window.id,
        crew: true
    }, ( response ) => {
        assignData( response.data );

        spinner();
        message();
    } );
} );