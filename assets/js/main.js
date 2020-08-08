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
        spinner();
        message( true, code, text );
    }

    console.log( error );
};

/**
 * Add crew delete/restore handlers
 *
 * @param notifications - is this for notifications? (default: false)
 */
const addDeleteHandlers = ( notifications ) => {
    notifications = notifications || false;
    const $tgt = e.target.tagName === 'A' ? $( e.target ) : $( e.target ).parent();
    const id = $tgt.data( 'id' );

    if ( notifications ) {
        $( '.delete-notification' ).click( ( e ) => {
            e.preventDefault();

            post( 'delete', {
                type: 'notification',
                id: id
            }, () => {
                $('.row' + id).remove();
            } );
        } );

        return;
    }
    $( '.delete-crew, .restore-crew' ).click( ( e ) => {
        e.preventDefault();

        if ( $tgt.hasClass( 'delete-crew' ) ) {
            post( 'delete', {
                type: 'crew',
                id: id
            }, () => {
                $tgt.removeClass( 'delete-crew' ).addClass( 'restore-crew' ).attr( 'title', 'Restore' );
                $tgt.children( 'img' ).attr( 'src', 'assets/images/restore.svg' ).attr( 'alt', 'Restore' );
            } );
        } else {
            post( 'restore', { id: id }, () => {
                $tgt.removeClass( 'restore-crew' ).addClass( 'delete-crew' ).attr( 'title', 'Delete' );
                $tgt.children( 'img' ).attr( 'src', 'assets/images/trash.svg' ).attr( 'alt', 'Delete' );
            } );
        }
    } );
}

/**
 * Return the HTML-formatted string for the data row
 *
 * @param row - data object
 *
 * @returns {string}
 */
const appendHTML = ( row ) => {
    const id = parseInt( row.id );
    const classes = 'text-truncate border-bottom border-right row' + id;
    const ship = row.ship_name ? `<a href="ship/${id}">${row.ship_name}</a>` : 'N/A';
    const deleted = row.disabled === '1'
        ? `<a class="restore-crew" href="#" title="Restore" data-id="${id}"><img src="assets/images/restore.svg" alt="Restore"></a>`
        : `<a class="delete-crew" href="#" title="Delete" data-id="${id}"><img src="assets/images/trash.svg" alt="Delete"></a>`

    return `
        <div class="col-1 p-2 border-left ${classes}" title="${id}">${id}</div>
        <div class="col-3 p-2 ${classes}" title="${row.name} ${row.surname}">${row.name} ${row.surname}</div>
        <div class="col-2 p-2 ${classes}" title="${row.email}">${row.email}</div>
        <div class="col-2 p-2 ${classes}" title="${row.rank_name}"><a href="rank/${id}">${row.rank_name}</a></div>
        <div class="col-2 p-2 ${classes}" title="${ship}">${ship}</div>
        <div class="col-2 p-2 ${classes}">
            <a class="edit-crew" href="editCrew/${id}" title="Edit">
                <img src="assets/images/pencil.svg" alt="Edit">
            </a>
            ${id !== 1 ? deleted : ''}
        </div>
    `;
};

/**
 * List notifications
 *
 * @param data - data to append
 * @param rank - rank name (optional)
 */
function listNotifications( data, rank ) {
    rank = rank || false
    const classes = 'text-truncate border-top border-bottom border-right font-weight-bold';
    let html = `
        <div class="row">
            <div class="col-1 p-2 border-left ${classes}">ID</div>
            <div class="col-3 p-2 ${classes}">Title</div>
            <div class="col-${rank ? '5' : '3'} p-2 ${classes}">Content</div>
            ${rank ? '' : `<div class="col-3 p-2 ${classes}">Rank</div>`}
            <div class="col-2 p-2 ${classes}">Actions</div>
    `;

    data.map( ( row ) => {
        html += `
            <div class="col-1 p-2 border-left ${classes}">${row.id}</div>
            <div class="col-3 p-2 ${classes}">${row.title}</div>
            <div class="col-${rank ? '5' : '3'} p-2 ${classes}">${row.content}</div>
            ${rank ? '' : `<div class="col-3 p-2 ${classes}">${row.rank_name}</div>`}
            <div class="col-2 p-2 ${classes}">
                <a class="edit-notification" href="editNotification/${row.id}" title="Edit">
                    <img src="assets/images/pencil.svg" alt="Edit">
                </a>
                <a class="delete-notification" href="#" title="Delete" data-id="${row.id}">
                    <img src="assets/images/trash.svg" alt="Delete">
                </a>
            </div>
        `;
    } );

    html += `</div><div class="pt-5">
        <a class="btn btn-primary text-white" href="addNotification${rank ? '/' + rank : ''}">Add Notification</a>
    </div>`;
    ( '#page' ).append( html );
    addDeleteHandlers( true );
}

/**
 * Generate HTML from data and append to page
 *
 * @param data - data to append
 * @param addedHtml - HTML to append (optional)
 */
function listCrew( data, addedHtml ) {
    addedHtml = addedHtml || false
    const classes = 'text-truncate border-top border-bottom border-right font-weight-bold';
    let html = `
        <div class="row">
            <div class="col-1 p-2 border-left ${classes}">ID</div>
            <div class="col-3 p-2 ${classes}">Name</div>
            <div class="col-2 p-2 ${classes}">Email</div>
            <div class="col-2 p-2 ${classes}">Rank</div>
            <div class="col-2 p-2 ${classes}">Ship</div>
            <div class="col-2 p-2 ${classes}">Actions</div>
    `;

    data.map( ( row ) => {
        html += appendHTML( row );
    } );

    html += '</div>';

    if ( addedHtml ) {
        html += addedHtml;
    }


    $( '#page' ).append( html );
    addDeleteHandlers();
}

/**
 * Redirection function
 *
 * @param link - link to redirect to
 * @param time - time to wait before redirection
 */
function redirect( link, time ) {
    time = typeof time === 'undefined' ? 5 : 0;

    setTimeout( () => {
        window.location.href = link;
    }, time * 1000 );
}

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
 * @param back - link to the listing (optional)
 */
function message( show, title, description, back ) {
    show  = show || false;
    title = title || false;
    description = description || false;
    back = back || false;

    const $message = $( '#message' );
    const $page = $( '#page' );
    const $h1 = $( 'h1' );

    if ( back ) {
        const added = `<p class="mt-3"><a href="${back}">Back to Listing</a></p>`;
        description = description ? description + added : added;
    }
    if ( title ) {
        $message.children( 'h3' ).html( title );
    }
    if ( description ) {
        $message.children( 'p' ).html( description );
    }

    if ( show === false ) {
        $h1.removeClass( 'd-none' );
        $message.addClass( 'd-none' );
        $page.removeClass( 'd-none' );
    } else {
        $h1.addClass( 'd-none' );
        $page.addClass( 'd-none' );
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
 * @param callback
 */
function formSubmit( name, callback ) {
    const $form = $( '#' + name );

    $form.submit( ( e ) => {
        e.preventDefault();

        $( '#soft-error' ).addClass( 'd-none' );
        $form.addClass( 'was-validated' );

        if ( $form[ 0 ].checkValidity() ) {
            post( name, $form.serializeArray(), callback, $form );
        }
    } );
}

/**
 * On document ready, verify login
 */
$( document ).ready( () => {
    post( 'usercheck', {}, ( response ) => {
        const user = response.data;

        if ( user ) {
            saveUser( user );
        } else {
            deleteUser();
        }
    } );
} ) ;
