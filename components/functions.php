<?php
/**
 * Get or display the error based on given parameters
 *
 * @param string $act - the act during which the error occurred
 * @param string $text - error description (default: Bad Request)
 * @param int $code - http response code (default: 400)
 * @param int|false $db - error code in case of database error (default: FALSE)
 * @param bool $die - should the function die and output nothing but the error? (default: TRUE)
 * @param string|false $soft - if string, displays the error in the desired box? (default: FALSE)
 *
 * @return array|void
 */
function getError( $act, $text = 'Bad Request', $code = 400, $db = FALSE, $die = TRUE, $soft = FALSE ) {
	$error = [
		'db'   => ! empty( $db ),
		'act'  => $act,
		'code' => $db ? $db : $code,
		'text' => $text,
		'soft' => $soft,
	];

	if ( $die ) {
		http_response_code( $code );

		die( json_encode( $error ) );
	} else {
		return $error;
	}
}

/**
 * Send an email
 *
 * @param $data
 * @param $subject
 * @param $message (optional)
 *
 * @return boolean
 */
function sendMail( $data, $subject, $message = FALSE ) {
	error_reporting( 0 );

	$to = is_string( $data ) ? $data : $data[ 'email' ];

	if ( ! $message ) {
		$username = is_array( $data ) && isset( $data[ 'username' ] ) ? ', ' . $data[ 'username' ] . ', ' : ' ';
		$message = "Thank you$username for registering to our portal!";
	}

	$headers = "MIME-Version: 1.0\r\n" . 'From: <webmaster@seo1click.com>' . "\r\nContent-type:text/html;charset=UTF-8\r\n";
	$message = "
		<html>
			<head>
				<title>$subject</title>
			</head>
		<body>
		<p>$message</p>
		</body>
		</html>
	";

	$data[ 'mail' ] = mail( $to, $subject, $message, $headers );

	return $data;
}