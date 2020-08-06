/**
 * On document ready, get the dropdown data and set the appropriate handler
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'dropdowns'
    }, ( response ) => {
        const data = response.data;
        const $ranks = $( '#rank' );
        const $ships = $( '#ship' );

        data.ranks.map( ( rank ) => {
            $ranks.append( `<option value="${rank.id}">${rank.name}</option>` );
        } );
        data.ships.map( ( ship ) => {
            const text = `${ship.name} (${ship.serial_number})`;

            $ships.append( `<option value="${ship.id}">${text}</option>` );
        } );

        message();
        spinner();
    } );
    formSubmit( 'crew', () => {
        message( true, 'Thank you!', 'The crew member was successfully added.', 'crew' );
        redirect( 'crew' );
    } );
} );
