/**
 * Add crew delete/restore handlers
 */
const addHandlers = () => {
    $( '.delete-crew, .restore-crew' ).click( ( e ) => {
        e.preventDefault();

        const $tgt = e.target.tagName === 'A' ? $( e.target ) : $( e.target ).parent();
        const id = $tgt.data( 'id' );

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
const appendData = ( row ) => {
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
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'crew'
    }, ( response ) => {
        const data = response.data;
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
            html += appendData( row );
        } );

        html += '</div><div class="pt-5"><a class="btn btn-primary text-white" href="newCrew">New Crew Member</a></div>';

        message();
        spinner();
        $( '#page' ).html( html );
        addHandlers();
    } );
} );
