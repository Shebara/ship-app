/**
 * Set user cookie
 *
 * @param user
 */
const saveUser = ( user ) => {
    if ( user.admin ) {
        $( '#admin-link' ).removeClass( 'd-none' );
    }
    $( '#loggedInMenu' ).removeClass( 'd-none' );
    $( '#loggedOutMenu' ).addClass( 'd-none' );

    user = JSON.stringify( user );
    user = btoa( user );
    const date = new Date();

    date.setTime( date.getTime() + 1000000000000 );

    const expires = user.remember ? '; expires=' + date.toUTCString() : '';
    document.cookie = `user=${user + expires}; path=/;`;
};

/**
 * Delete user cookie
 */
const deleteUser = () => {
    $( '#admin-link' ).addClass( 'd-none' );
    $( '#loggedInMenu' ).addClass( 'd-none' );
    $( '#loggedOutMenu' ).removeClass( 'd-none' );

    document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;';
};

/**
 * Universal message display/hide function
 *
 * @param show - show if true, hide if false (default: false)
 * @param title - message title (show default if not set)
 * @param description - message description (show default if not set)
 */
function message( show, title, description ) {
    show  = show || false;
    title = title || false;
    description = description || false;

    const $message = $( '#message' );

    if ( title ) {
        $message.children( 'h3' ).html( title );
    }
    if ( description ) {
        $message.children( 'p' ).html( description );
    }

    if ( show === false ) {
        $message.addClass( 'd-none' );
    } else {
        $message.removeClass( 'd-none' );
    }
}

/**
 * Universal spinner show/hide function
 *
 * @param show - show if true, hide if false (default: false)
 */
function spinner( show ) {
    show = show || false;

    const $spin = $( '#spinner' );

    if ( show === false ) {
        $spin.addClass( 'd-none' );
    } else {
        $spin.removeClass( 'd-none' );
    }
}

/**
 * Get user cookie
 *
 * @returns null|object
 */
function getUser() {
    const value = `; ${document.cookie}`;
    const parts = value.split( `; user=` );

    if ( parts.length === 2 ) {
        const user = parts.pop().split( ';' ).shift();

        return JSON.parse( atob( user ) );
    }

    return null;
}
