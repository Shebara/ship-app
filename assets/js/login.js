/**
 * On document ready, request the appropriate data
 */
$( document ).ready( () => {
    const name = 'login';
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
} );
