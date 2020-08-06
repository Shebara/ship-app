/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    //TODO check token
    formSubmit( 'password', ( response ) => {
        const data = response.data;
        const title = `Welcome, ${data.name}!`;

        saveUser( data );
        message( true, title, 'Your password has been successfully set and you are now logged in.' );
        redirect( '' );
    } );
} );
