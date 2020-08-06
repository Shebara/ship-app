<?php
if ( ! function_exists( 'getError' ) ) {
	die( 'Improperly included file!' );
}

/**
 * Open MIME type based image resource
 *
 * @param $image - Image path
 *
 * @return void|resource
 */
function imageFromFile( $image ) {
	$fileInfo = finfo_open( FILEINFO_MIME_TYPE );
	$mimeType = finfo_file( $fileInfo, $image );

	finfo_close( $fileInfo );

	switch( $mimeType ) {
		default:
			getError( 'upload_image', 'Incorrect file type! Must be JPG, GIF or PNG.' );
			break;
		case 'image/jpg':
		case 'image/jpeg':
			return imagecreatefromjpeg( $image );
		case 'image/png':
			return imagecreatefrompng( $image );
		case 'image/gif':
			return imagecreatefromgif( $image );
	}
}

/**
 * Generate the new image and return resource
 *
 * @param $image - URL
 * @param $src - original image
 *
 * @return false|resource
 */
function generateNewImage( $image, $src ) {
	list( $width, $height ) = getimagesize( $image );

	if ( $width > $height ) {
		$newWidth = 430;
		$newHeight = ( $newWidth / $width ) * $height;
	} else {
		$newHeight = 430;
		$newWidth = ( $newHeight / $height ) * $width;
	}

	$new = imagecreatetruecolor( $newWidth, $newHeight );

	if ( ! imagecopyresized( $new, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height ) ) {
		getError( 'image_resize', 'Failed to resize image!', 500 );
	}

	return $new;
}

/**
 * Create the JPG file with unique name in the uploads folder
 *
 * @param $new - new image resource
 *
 * @return string - file path
 */
function createNewFile( $new ) {
	$time = microtime( TRUE );
	$time = $time * 10000;
	$random = mt_rand( 1, 9999999 );
	$random = str_pad( $random, 7, '0', STR_PAD_LEFT );
	$file   = "uploads/$time$random.jpg";

	if ( is_file( $file ) ) {
		$file = createNewFile( $new );

		return $file;
	}

	imagejpeg( $new, $file );

	return $file;
}

$image = isset( $_FILES[ 'image' ] ) ? $_FILES[ 'image' ] : FALSE;
$image = $image && isset( $image[ 'tmp_name' ] ) ? $image[ 'tmp_name' ] : FALSE;

if ( ! $image ) {
	getError( 'upload_image', 'No file uploaded!' );
}

$src = imageFromFile( $image );
$new = generateNewImage( $image, $src );
$data = createNewFile( $new );
