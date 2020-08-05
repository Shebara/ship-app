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

// Set metas for pages
switch ( $page ) {
	default:
		$title = 'Not Found';
		$desc = 'The requested page was not found.';
		$page = '404';
		$path = './pages/404.php';

		array_push( $keywords, '404' );
		array_push( $keywords, 'Not Found' );
		break;
	case 'index':
		$title = 'Your Notifications';
		$desc = 'Listing of your notifications.';
		$path = FALSE;

		array_push( $keywords, 'Welcome' );
		array_push( $keywords, 'Notifications' );
		break;
	case 'ships':
		$title = 'Ship Control';
		$desc = 'Administration dashboard.';
		$path = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'ranks':
		$title = 'Rank Control';
		$desc = 'Administration dashboard.';
		$path = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'crew':
		$title = 'Crew Control';
		$desc = 'Administration dashboard.';
		$path = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'login':
		$title = 'Log In';
		$desc = 'Log in to our portal.';

		array_push( $keywords, 'User' );
		array_push( $keywords, 'Authentication' );
		array_push( $keywords, 'Login' );
		break;
	case 'logout':
		$title = 'Log Out';
		$desc = 'Log in to our portal.';
		$path = FALSE;

		array_push( $keywords, 'User' );
		array_push( $keywords, 'Authentication' );
		array_push( $keywords, 'Logout' );
		break;
	case 'profile':
		$title = 'Your Profile';
		$desc = 'View your profile.';
		$path = FALSE;

		array_push( $keywords, 'User' );
		array_push( $keywords, 'Profile' );
		break;
}

$metas = [
	'title' => empty( $title ) ? $site : strip_tags( "$title | $site" ),
	'desc' => $desc ? $desc : '',
	'keywords' => implode( ',', $keywords ),
];
