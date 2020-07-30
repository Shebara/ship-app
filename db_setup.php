<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'on' );

require_once 'components/functions.php';
require_once 'classes/Database.php';

// Setup the database through constructor
$db = new Database();

if ( $db->checkTables( SERVER_DB ) ) {
	echo "The application was successfully installed.";
} else {
	if ( isset( $_GET[ 'param' ] ) ) {
		echo "Error! Please try again.";
	} else {
		$s = isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? "s" : "";
		$host = $_SERVER[ 'HTTP_HOST' ];
		$url = $_SERVER[ 'REQUEST_URI' ];
		$link = "http$s://$host$url/1";

		header( "Location: $link" );
	}
}
