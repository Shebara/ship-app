/**
 * Add image URL as source to displayed image and as value to hidden input
 *
 * @param value
 */
function assignImage( value ) {
    const $output = $( '#output_image' );

    if ( value !== '' ) {
        $output.attr( 'src', value );
    } else {
        $output.attr( 'src', 'assets/images/placeholder.png' );
    }

    $( '#image_url' ).val( value );
}

/**
 * Upload and display image if it is selected
 */
const imageChange = () => {
    const image = $( '#image' ).get( 0 ).files[ 0 ];
    const $form = $( '#ship' );

    if ( typeof image === 'undefined' ) {
        $( '#output_image' ).attr( 'src', 'assets/images/placeholder.png' );
        $( '#image_url' ).val( '' );

        return;
    }

    const formData = new FormData();

    formData.append( 'image', image );

    post( 'upload', formData, ( response ) => {
        const data = response.data;

        if( data ) {
            assignImage( data );
        }
    }, $form, ( error ) => {
        error = error.responseJSON ? error.responseJSON : error;
        softError( error, $form );

        console.log( error );
    }, true );
};

/**
 * On document ready, set the appropriate handler
 */
$( document ).ready( () => {
    const $image = $( '#image' );

    $( '#image_container' ).click( ( e ) => {
        $image.trigger( 'click' );
    } );
    $image.change( imageChange );
    formSubmit( 'ship', () => {
        message( true, 'Thank you!', 'The ship was successfully added.', 'ships' );
        redirect( 'ships' );
    } );
} );
