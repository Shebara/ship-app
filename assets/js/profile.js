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

        //TODO show profile
    } );
} );
