/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    const token = $( '#setPassword' ).data( 'token' );

    if ( typeof token !== 'string' || token.length === 0 ) {
        spinner();
        message( true, 'Error!', 'Token not available.' );
    }
    post( 'checkToken', {
        token: token
    }, ( response ) => {
        if ( response.data ) {
            $( '#id' ).val( response.data );
            spinner();
            message();
        }
    } );
    formSubmit( 'setPassword', ( response ) => {
        const data = response.data;
        const title = `Welcome, ${data.name}!`;

        saveUser( data );
        message( true, title, 'Your password has been successfully set and you are now logged in.' );
        redirect( '' );
    } );
} );
