<?php
// Default page vars
$site = 'Test Portal';
$title = FALSE;
$index = TRUE;
$desc = FALSE;
$id = empty( $_GET[ 'id' ] ) ? FALSE : $_GET[ 'id' ];
$keywords = [ 'ship-app', 'ships', 'test', 'portal' ];

//Get page parameter and set path
$page = empty( $_GET[ 'page' ] ) ? 'index' : $_GET[ 'page' ];
$path = "./pages/$page.php";

// Set 404 metas and path if page doesn't exist
if ( ! is_file( $path ) ) {
	$title            = 'Not Found';
	$page             = '404';
	$path             = './pages/404.php';
} else {
	// Set metas for other pages
	switch ( $page ) {
		default:
			break;
		case 'index':
			$title = 'Main Page';
			$desc = 'Listing of public user profiles.';
			$path = FALSE;

			array_push( $keywords, 'Welcome' );
			break;
		case 'admin':
			$title = 'Administration Panel';
			$desc = 'Administration dashboard.';
			$path = FALSE;

			array_push( $keywords, 'Administration' );
			break;
		case 'login':
			$title = 'Log in';
			$desc = 'Log in to our portal.';

			array_push( $keywords, 'Authentication' );
			array_push( $keywords, 'Login' );
			break;
	}
}

$metas = [
	'title' => empty( $title ) ? $site : strip_tags( "$title | $site" ),
	'desc' => $desc ? $desc : '',
	'keywords' => implode( ',', $keywords ),
];
