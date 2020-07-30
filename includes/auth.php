<?php
session_start();

$loggedIn = FALSE;
$admin = FALSE;

if ( ! empty( $_SESSION[ 'active_user' ] ) ) {
	$loggedIn = $_SESSION[ 'active_user' ];

	if ( ! empty( $loggedIn[ 'admin' ] ) ) {
		$admin = TRUE;
	}
}
