<?php
// Default page vars
$site = 'Test Portal';
$title = FALSE;
$index = TRUE;
$desc = FALSE;
$spin = TRUE;
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
		$spin = FALSE;

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
	case 'newShip':
		$title = 'Add New Ship';
		$desc = 'Administration dashboard.';
		$spin = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'newRank':
		$title = 'Add New Rank';
		$desc = 'Administration dashboard.';
		$spin = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'newCrew':
		$title = 'Add New Crew Member';
		$desc = 'Administration dashboard.';
		$spin = FALSE;

		array_push( $keywords, 'Administration' );
		break;
	case 'editShip':
		$title = 'Edit Ship';
		$desc = 'Administration dashboard.';
		$path = "./pages/newShip.php";

		array_push( $keywords, 'Administration' );
		break;
	case 'editRank':
		$title = 'Edit Rank';
		$desc = 'Administration dashboard.';
		$path = "./pages/newRank.php";

		array_push( $keywords, 'Administration' );
		break;
	case 'editCrew':
		$title = 'Edit Crew Member';
		$desc = 'Administration dashboard.';
		$path = "./pages/newCrew.php";

		array_push( $keywords, 'Administration' );
		break;
	case 'login':
		$title = 'Log In';
		$desc = 'Log in to our portal.';
		$spin = FALSE;

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
