/**
 * Convert time to HH:MM:SS format
 *
 * @param date - date object
 *
 * @returns {string}
 */
const hhMmSs = ( date ) => {
    const twoDigits = ( time ) => {
        if ( time < 10 ) {
            return '0' + time;
        } else {
            return '' + time;
        }
    };

    const hh = twoDigits( date.getHours() );
    const mm = twoDigits( date.getMinutes() );
    const ss = twoDigits( date.getSeconds() );

    return `${hh}:${mm}:${ss}`;
};

/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    const data = window.id ? { id: window.id } : {};

    post( 'profile', data, ( response ) => {
        const data = response.data;

        if ( window.id ) {
            const title = `${data.name}'s Profile`;

            setTitle( title );
        }

        const email = data.email ? `<h3>E-Mail: ${data.email}</h3>` : '';
        const date = new Date( data.registered_at.replace( ' ', 'T' ) + 'Z' );
        const registered  = `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}. ${hhMmSs(date)}`;
        const ship_info = data.ship_name !== null && data.ship_id !== null ? `<h3>
            Ship: <a href="ship/${data.ship_id}">${data.ship_name}</a>
        </h3>` : '';

        spinner();
        message();
        $( '#page' ).html( `
            <h3>First Name: ${data.name}</h3>
            <h3>Last Name: ${data.surname}</h3>
            ${email}
            <h3>Rank: ${data.rank}</h3>
            <h3>Registration Date: ${registered}</h3>
            ${ship_info}
        ` );
    } );
} );
