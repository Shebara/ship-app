/**
 * Return the HTML-formatted string for the data row
 *
 * @param row - data object
 *
 * @returns {string}
 */
const appendData = ( row ) => {
    console.log( row );

    return '';
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
