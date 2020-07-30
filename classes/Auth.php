<?php
/**
 * Class Auth
 */
class Auth
{
	private $user;

	/**
	 * Auth constructor:
	 *  - start a session and get the data from it if it exists
	 */
	public function __construct() {
		session_start();

		if ( ! empty( $_SESSION[ 'active_user' ] ) ) {
			$this->user = $_SESSION[ 'active_user' ];
		} else {
			$this->user = FALSE;
		}
	}

	/**
	 * Save user to session and user property
	 *
	 * @param $user - user data
	 */
	public function logIn( $user ) {
		$_SESSION[ 'active_user' ] = $user;

		$this->user = $user;
	}

	/**
	 * Get the current user info
	 *
	 * @return false|array
	 */
	public function whoIs() {
		return $this->user;
	}

	/**
	 * Remove all session data
	 */
	public function logOut() {
		session_destroy();
	}
}