/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    post( 'admin', {
        page: 'crew'
    }, ( response ) => {
        listCrew( response.data,
            '<div class="pt-5"><a class="btn btn-primary text-white" href="newCrew">New Crew Member</a></div>' );

        message();
        spinner();
    } );
} );
