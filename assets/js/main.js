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
 * Display an error without keeping the form hidden
 *
 * @param error - error content
 * @param $data - original form to be displayed again
 */
const softError = ( error, $data ) => {
    error = typeof error === 'string' ? error : error.text;

    spinner();
    $( '#soft-error' ).removeClass( 'd-none' ).html( error );
    $data.removeClass( 'was-validated' ).removeClass( 'd-none' );
};

/**
 * Error function
 *
 * @param error - error content
 * @param silent - should the spinners and errors not be displayed?
 * @param $element - original form for soft errors
 */
const defaultError = ( error, silent, $element ) => {
    error = error.responseJSON ? error.responseJSON : error;

    if ( error.soft ) {
        softError( error, $element );

        return;
    }

    const prefix = error.db ? 'DB' : 'API';
    const code = error.code ? `${prefix} Error #${error.code}` : false;
    const text = error.text || false;

    if ( ! silent ) {
        $element.addClass( 'd-none' );
        spinner();
        message( true, code, text );
    }

    console.log( error );
};

/**
 * POST request
 *
 * @param param - request parameter
 * @param data - POST data
 * @param success - success callback
 * @param $element - container element (default: page)
 * @param error - error callback (optional)
 * @param file - is it a file? (default: false)
 * @param silent - should the error be displayed "silently"? (default: false)
 */
function post( param, data, success, $element, error, file, silent ) {
    const user = getUser();
    file = file || false;
    silent = silent || false;
    $element = $element || $( '#page' );
    const object = {
        url: 'request/' + param,
        data: data,
        dataType: 'json',
        success: success,
        error: error ? error : ( data ) => { defaultError( data, silent, $element ) }
    };

    if ( file ) {
        object.contentType = false;
        object.processData = false;
    }
    if ( user !== null && user.token ) {
        object.beforeSend = ( xhr ) => {
            xhr.setRequestHeader( 'Authorization', user.token );
        };
    }

    $.post( object );
}

/**
 * Set page title dynamically
 *
 * @param title
 */
function setTitle( title ) {
    const $title = $( 'title' );
    const site = $title.html().split( ' | ' ).slice( -1 ).pop();

    $( 'h1' ).html( title );
    $title.html( `${title} | ${site}` );
}

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

/**
 * Submit handler by form ID and request name
 *
 * @param name
 */
function formSubmit( name ) {
    const $form = $( '#' + name );

    $form.submit( ( e ) => {
        e.preventDefault();

        $( '#soft-error' ).addClass( 'd-none' );
        $form.addClass( 'was-validated' );

        if ( $form[ 0 ].checkValidity() ) {
            post( name, $form.serializeArray(), ( response ) => {
                console.log( response );
            }, $form );
        }
    } );
}

/**
 * On document ready, verify login
 */
$( document ).ready( () => {
    post( 'usercheck', {}, ( response ) => {
        console.log( response );
    } );
} ) ;
