<?php
header( 'Content-Type: application/json' );

require_once 'classes/Auth.php';
require_once 'classes/Database.php';
require_once 'includes/functions.php';

if ( ! function_exists( 'getallheaders' ) ) {
	/**
	 * Get all headers alternative for servers other than Apache
	 *
	 * @return array
	 */
	function getallheaders() {
		$headers = [];
		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
				$key = str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) );

				$headers[ $key ] = $value;
			}
		}
		return $headers;
	}
}

/**
 * Get root link based on current URL
 *
 * @param $path
 *
 * @return string
 */
function getLink( $path ) {
	$s = isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? "s" : "";
	$host = $_SERVER[ 'HTTP_HOST' ];
	$url = $_SERVER[ 'REQUEST_URI' ];
	$link = explode( '/request/', "http$s://$host$url" );

	return reset( $link ) . $path;
}

/**
 * Get the ID parameter with all necessary checks
 *
 * @param $data - array to get the ID from
 * @param $auth Auth|false - instance of Auth() (optional)
 *
 * @return integer|string
 */
function getId( $data = FALSE, $auth = FALSE ) {
	if ( ! is_array( $data ) ) {
		$data = $_POST;
	}

	$id = empty( $data[ 'id' ] ) ? FALSE : $data[ 'id' ];
	$act = 'get_id_' . $_GET[ 'req' ];

	if ( ! $id ) {
		if ( $auth ) {
			$user = $auth->whoIs();
			$id = $user[ 'id' ];
		}
		if ( ! $id ) {
			getError( $act, 'Missing `id` parameter.' );
		}
	}
	if ( ! is_numeric( $id ) ) {
		getError( $act, 'Invalid `id` parameter (must be an integer).' );
	}

	return $id;
}

$headers = getallheaders();
$token = isset( $headers[ 'Authorization' ] ) ? $headers[ 'Authorization' ] : FALSE;
$result = [
	'code' => 200,
	'req'  => $_GET[ 'req' ],
	'data' => FALSE,
];

// Based on the parameter, call the adequate function/method
if ( empty( $_GET[ 'req' ] ) ) {
	getError( 'request_init' ,'Missing `req` parameter.' );
}

$db = new Database();
$auth = new Auth();

switch ( $_GET[ 'req' ] ) {
	default:
		getError( 'request_init', 'Invalid `req` parameter.' );
		break;
	case 'usercheck':
		$data = $auth->whoIs();
		break;
	case 'notifications':
		$data = 'INDEX PAGE';
		break;
	case 'admin':
		$data = 'ADMIN PAGE';
		break;
	case 'login':
		$data = $db->logIn( $_POST );

		$auth->logIn( $data );
		break;
	case 'profile':
		$id = getId( FALSE, $auth );
		$data = $db->getProfile( $id );

		if ( isset( $_POST[ 'id' ] ) ) {
			unset( $data[ 'email' ] );
		}
		break;
}

if ( isset( $data ) ) {
	$result[ 'data' ] = $data;
}

echo json_encode( $result );