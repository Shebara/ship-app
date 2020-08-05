/**
 * On document ready, redirect the user to login if not logged in, otherwise request the notification data
 */
$( document ).ready( () => {
    post( 'logout', {}, () => {
        deleteUser();
        spinner();
        message( true, 'Goodbye!', 'You have successfully logged out.' );
        redirect( 'login' );
    } );
} );
