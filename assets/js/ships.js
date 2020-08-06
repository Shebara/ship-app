/**
 * Return the HTML-formatted string for the data row
 *
 * @param row - data object
 *
 * @returns {string}
 */
const appendData = ( row ) => {
    const classes = 'text-truncate border-bottom border-right row' + row.id;
    const image = row.image_url ? row.image_url : 'assets/images/placeholder.png';

    return `
        <div class="col-1 p-2 border-left ${classes}">${row.id}</div>
        <div class="col-4 p-2 ${classes}">${row.name}</div>
        <div class="col-2 p-2 ${classes}">${row.serial_number}</div>
        <div class="col-3 p-2 ${classes}">
            <div class="image-wrap"><img src="${image}" alt="${row.name}" /></div>
        </div>
        <div class="col-2 p-2 ${classes}">
            <a class="visit-ship" href="ship/${row.id}" title="Ship Page">
                <img src="assets/images/globe.svg" alt="Visit">
            </a>
            <a class="edit-ship" href="editShip/${row.id}" title="Edit">
                <img src="assets/images/pencil.svg" alt="Edit">
            </a>
            <a class="delete-ship" href="#" title="Delete" data-id="${row.id}">
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
        page: 'ships'
    }, ( response ) => {
        const data = response.data;
        const classes = 'text-truncate border-top border-bottom border-right font-weight-bold';

        let html = `
            <div class="row">
                <div class="col-1 p-2 border-left ${classes}">ID</div>
                <div class="col-4 p-2 ${classes}">Name</div>
                <div class="col-2 p-2 ${classes}">S/N</div>
                <div class="col-3 p-2 ${classes}">Image</div>
                <div class="col-2 p-2 ${classes}">Actions</div>
        `;

        data.map( ( row ) => {
            html += appendData( row );
        } );

        html += '</div><div class="pt-5"><a class="btn btn-primary text-white" href="newShip">New Ship</a></div>';

        message();
        spinner();
        $( '#page' ).html( html );

        console.log( response );
    } );
} );
