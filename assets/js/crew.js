const appendData = ( row ) => {
    const classes = 'text-truncate border-bottom border-right row' + row.id;
    const ship = row.ship_name ? `<a class="edit-ship" href="editShip/${row.id}">${row.ship_name}</a>` : 'N/A';
    const deleted = row.disabled === '1'
        ? `<a class="restore-crew" href="#" title="Restore" data-id="${row.id}"><img src="assets/images/restore.svg" alt="Restore"></a>`
        : `<a class="delete-crew" href="#" title="Delete" data-id="${row.id}"><img src="assets/images/trash.svg" alt="Delete"></a>`

    return `
        <div class="col-1 p-2 border-left ${classes}" title="${row.id}">${row.id}</div>
        <div class="col-3 p-2 ${classes}" title="${row.name} ${row.surname}">${row.name} ${row.surname}</div>
        <div class="col-2 p-2 ${classes}" title="${row.email}">${row.email}</div>
        <div class="col-2 p-2 ${classes}" title="${row.rank_name}">
            <a class="edit-rank" href="editRank/${row.id}">
                ${row.rank_name}
            </a>
        </div>
        <div class="col-2 p-2 ${classes}" title="${ship}">${ship}</div>
        <div class="col-2 p-2 ${classes}">
            <a class="edit-crew" href="editCrew/${row.id}" title="Edit">
                <img src="assets/images/pencil.svg" alt="Edit">
            </a>
            ${deleted}
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

        html += '</div>';

        message();
        spinner();
        $( '#page' ).html( html );
    } );
} );
