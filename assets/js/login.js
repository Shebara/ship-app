/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    formSubmit( 'login', ( response ) => {
        const data = response.data;
        const title = `Welcome, ${data.name}!`;

        setTitle( title );
        saveUser( data );
        message( true, title, 'You have successfully logged in.' );
        redirect( '' );
    } );
} );
