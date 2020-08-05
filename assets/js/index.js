/**
 * On document ready, redirect the user to login if not logged in, otherwise request the notification data
 */
$( document ).ready( () => {
    if ( getUser() === null ) {
        redirect( 'login', 0 );

        return;
    }

    post( 'notifications', {}, ( response ) => {
        console.log( response );
    } );
} );
