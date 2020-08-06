/**
 * Add rank delete handlers
 */
const addHandlers = () => {
    $( '.delete-rank' ).click( ( e ) => {
        e.preventDefault();

        const $tgt = e.target.tagName === 'A' ? $( e.target ) : $( e.target ).parent();
        const id = $tgt.data( 'id' );

        post( 'delete', {
            type: 'rank',
            id: id
        }, () => {
            $( '.row' + id ).remove();
        } );
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
    const classes = 'text-truncate border-bottom border-right row' + row.id;

    return `
        <div class="col-2 p-2 border-left ${classes}">${row.id}</div>
        <div class="col-7 p-2 ${classes}">${row.name}</div>
        <div class="col-3 p-2 ${classes}">
            <a class="visit-rank" href="rank/${row.id}" title="Rank Page">
                <img src="assets/images/globe.svg" alt="Visit">
            </a>
            <a class="edit-rank" href="editRank/${row.id}" title="Edit">
                <img src="assets/images/pencil.svg" alt="Edit">
            </a>
            <a class="delete-rank" href="#" title="Delete" data-id="${row.id}">
                <img src="assets/images/trash.svg" alt="Delete">
            </a>
        </div>
    `;
};

/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'ranks'
    }, ( response ) => {
        const data = response.data;
        const classes = 'text-truncate border-top border-bottom border-right font-weight-bold';

        let html = `
            <div class="row">
                <div class="col-2 p-2 border-left ${classes}">ID</div>
                <div class="col-7 p-2 ${classes}">Name</div>
                <div class="col-3 p-2 ${classes}">Actions</div>
        `;

        data.map( ( row ) => {
            html += appendData( row );
        } );

        html += '</div><div class="pt-5"><a class="btn btn-primary text-white" href="newRank">New Rank</a></div>';

        message();
        spinner();
        $( '#page' ).html( html );
        addHandlers();
    } );
} );
