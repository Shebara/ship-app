/**
 * Show the retrieved data on the page
 *
 * @param data
 */
const assignData = ( data ) => {
    const $page = $( '#page' );

    $page.html( `
        <h2>${data.name}</h2>
        <hr />
        <h4>Crew with this Rank</h4>
    ` );
    listCrew( data.crew, data.notifications.length > 0 ? `
        <hr/>
        <h4>Notifications for this Rank</h4>
    ` : '' );
    listNotifications( data.notifications );
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
