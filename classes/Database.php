<?php
require_once 'config.php';

/**
 * Class Database
 */
class Database
{

	//MySQLi object
	private $conn;

	/**
	 * Stop the script execution and return the appropriate error
	 *
	 * @param $act - the act during which the error occurred
	 * @param $errno - error code to output (default: mysqli error code)
	 * @param $error - error text to output (default: mysqli error)
	 *
	 * @return void
	 */
	private function errorOutput( $act, $errno = FALSE, $error = FALSE ) {
		if ( !$errno ) {
			$errno = $this->conn->errno;
		}
		if ( !$error ) {
			$error = $this->conn->error;
		}

		getError( $act, $error, 503, $errno );
	}

	/**
	 * Execute MySQL query and check for errors
	 *
	 * @param $sql
	 * @param $request - request name for error tracking
	 *
	 * @return mysqli_result
	 */
	private function dbQuery( $sql, $request ) {
		while ( $this->conn->more_results() ) {
			$this->conn->next_result();

			if ( $res = $this->conn->store_result() ) {
				$res->free();
			}
		}

		$query = $this->conn->query( $sql );

		if ( $query === FALSE ) {
			$this->errorOutput( $request );
		}

		return $query;
	}

	/**
	 * Execute MySQL SELECT query and return the results
	 *
	 * @param $request - request name for error tracking
	 * @param $select - SELECT MySQL parameter
	 * @param $from - table to select from
	 * @param $where (optional) - comparison condition(s)
	 * @param $join (optional) - full JOIN/ON command
	 * @param $orderBy (optional) - full JOIN/ON command
	 *
	 * @return array|integer - integer if count, otherwise associative array
	 */
	private function dbSelect( $request, $select, $from, $where = FALSE, $join = FALSE, $orderBy = FALSE ) {
		$sql = "SELECT $select FROM $from ";

		if ( $join ) {
			$sql .= $join;
		}
		if ( $where ) {
			$sql .= " WHERE $where";
		}
		if ( $orderBy ) {
			$sql .= " ORDER BY $orderBy";
		}

		$query = $this->dbQuery( $sql, $request );
		$result = array();

		while ( $row = $query->fetch_assoc() ) {
			if ( isset( $row[ 'COUNT(*)' ] ) ) {
				return intval( $row[ 'COUNT(*)' ] );
			}

			array_push( $result, $row );
		}

		return $result;
	}

	/**
	 * Execute the given SQL query
	 *
	 * @param $request - request name for error tracking
	 * @param $sql - SQL with placeholders
	 * @param $values - values to bind
	 * @param $type - request variable types
	 *
	 * @return void
	 */
	private function dbExecute( $request, $sql, $values, $type ) {
		try {
			$stmt = $this->conn->prepare( $sql );

			if ( $stmt === FALSE ) {
				$this->errorOutput( $request . '_prepare' );
			}

			$stmt->bind_param( $type, ...$values );
			$stmt->execute();
			$stmt->close();
		} catch ( Exception $e ) {
			$this->errorOutput( $request . '_execute' );
		}
	}

	/**
	 * Insert the data row into the given table
	 *
	 * @param $request - request name for error tracking
	 * @param $table - table to insert to
	 * @param $data - data to insert
	 *
	 * @return void
	 */
	private function dbInsert( $request, $table, $data ) {
		$keys = [];
		$values = [];
		$marks = [];
		$type = '';

		foreach ( $data as $key => $value ) {
			$mark = $key === 'password' ? 'PASSWORD(?)' : '?';

			array_push( $keys, $key );
			array_push( $values, $value );
			array_push( $marks, $mark );

			if ( is_numeric( $value ) ) {
				$type .= 'i';
			} else {
				$type .= 's';
			}
		}

		$keys = implode( ', ', $keys );
		$marks = implode( ', ', $marks );
		$sql = "INSERT INTO $table ($keys) VALUES ($marks)";

		$this->dbExecute( $request, $sql, $values, $type );
	}

	/**
	 * Update the data row from the given table
	 *
	 * @param $request - request name for error tracking
	 * @param $table - table to update
	 * @param $data - data to update
	 * @param $where - condition to find the appropriate row
	 *
	 * @return void
	 */
	private function dbUpdate( $request, $table, $data, $where ) {
		$keys = [];
		$values = [];
		$type = '';

		foreach ( $data as $key => $value ) {
			$mark = $key === 'password' ? 'PASSWORD(?)' : '?';

			array_push( $keys, "$key = $mark" );
			array_push( $values, $value );

			if ( is_numeric( $value ) ) {
				$type .= 'i';
			} else {
				$type .= 's';
			}
		}

		$keys = implode( ', ', $keys );
		$sql = "UPDATE $table SET $keys WHERE $where";

		$this->dbExecute( $request, $sql, $values, $type );
	}

	/**
	 * Delete row from DB based on given condition
	 *
	 * @param $request - request name for error tracking
	 * @param $table - name of the table to delete from
	 * @param $column - name of the column to delete from
	 * @param $value - column value in the row for deletion
	 * @param $type - type of the value data
	 *
	 * @return void
	 */
	private function dbDelete( $request, $table, $column, $value, $type ) {
		$sql = "DELETE FROM $table WHERE $column = ?";

		$this->dbExecute( $request, $sql, array( $value ), $type );
	}

	/**
	 * Generate a token
	 *
	 * @param $id
	 * @param string $salt (optional)
	 *
	 * @return string
	 */
	private function generateToken( $id, $salt = '' ) {
		$time = microtime();
		$random = uniqid();

		return hash( 'sha256', "$time|$id|$random$salt" );
	}

	/**
	 * Save session to DB, generate token and return the modified data array
	 *
	 * @param array $id - user ID
	 * @param boolean $remember - should the user stay logged in?
	 *
	 * @return string
	 */
	private function setSession( $id, $remember ) {
		$token = $this->generateToken( $id );
		$data = [
			'id' => intval( $id ),
			'token' => $token,
			'remember' => $remember ? 1 : 0,
		];

		$this->dbInsert( 'set_session', 'user_sessions', $data );

		return $token;
	}

	/**
	 * Get the login data and check for the requested user
	 *
	 * @param $email
	 * @param $password (optional)
	 * @param $params (optional) - additional parameters to add to the request
	 *
	 * @return array
	 */
	private function getLoginData( $email, $password = FALSE, $params = [] ) {
		if ( $password ) {
			$password = $this->conn->real_escape_string( $password );
		}

		$softError = empty( $params[ 'hardError' ] );
		$email = $this->conn->real_escape_string( $email );
		$extendedWhere = $password ? " AND password = PASSWORD('$password')" : "";
		$extendedJoin = isset( $params[ 'join' ] ) ? ' ' . $params[ 'join' ] : '';
		$request = $password ? 'login_user_data' : 'user_data';
		$data = $this->dbSelect(
			$request,
			isset( $params[ 'select' ] ) ? $params[ 'select' ] : "users.id, email, name, surname, rank, disabled, ship",
			'users',
			"email = '$email'$extendedWhere",
			'INNER JOIN user_settings ON users.id = user_settings.id' . $extendedJoin
		);

		if ( count( $data ) === 0 ) {
			$password
				? getError( $request, 'Incorrect username or password.', 403, FALSE, TRUE, $softError )
				: getError( $request, 'User not found.', 403, FALSE, TRUE, $softError );
		}

		$data = reset( $data );

		if ( ! empty( $data[ 'disabled' ] ) ) {
			getError( $request, 'This profile has been deactivated.', 403, FALSE, TRUE, $softError );
		}

		unset( $data[ 'disabled' ] );

		return $data;
	}

	/**
	 * Check if tables already exist, import SQL file if they don't
	 *
	 * @param $db - database name
	 *
	 * @return integer
	 */
	public function checkTables( $db ) {
		$sql = "SHOW TABLES FROM $db LIKE 'user%'";
		$query = $this->dbQuery( $sql, 'Exists check' );

		if ( is_null( $query->fetch_object() ) || $query->num_rows < 4 ) {
			if ( ! is_file( 'db.sql' ) ) {
				getError( 'SQL import', 'No SQL File.', 404 );
			}

			$file = file_get_contents( 'db.sql' );

			$this->conn->multi_query( $file );

			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Database class constructor:
	 *  - create database if it doesn't exist
	 *  - import SQL if the profiles table doesn't exist
	 *  - get default values from config
	 *
	 * @param $host - Database server
	 * @param $username - Database username
	 * @param $password - Database password
	 * @param $db - Database name
	 *
	 * @return void
	 */
	public function __construct( $host = SERVER_HOST, $username = SERVER_USERNAME, $password = SERVER_PASSWORD, $db = SERVER_DB ) {
		$this->conn = @new mysqli( $host, $username, $password, $db );

		//Error output on connection failure, create database if it doesn't exist
		if ( $this->conn->connect_error ) {
			if ( $this->conn->connect_errno === 1049 ) {
				$this->conn = new mysqli( $host, $username, $password );
				$sql = "CREATE DATABASE $db";

				if ( $this->conn->connect_error ) {
					$this->errorOutput( 'DB reconnect', $this->conn->connect_errno, $this->conn->connect_error );
				}

				$this->dbQuery( $sql, 'DB create' );
			} else {
				$this->errorOutput( 'DB connect', $this->conn->connect_errno, $this->conn->connect_error );
			}
		}

		$this->checkTables( $db );
	}

	/**
	 * Log the user in, generate and return the token
	 *
	 * @param $login
	 *
	 * @return array
	 */
	public function logIn( $login ) {
		$email = $login[ 'email' ];
		$password = $login[ 'password' ];
		$remember = ! empty( $login[ 'remember' ] );

		$data = $this->getLoginData( $email, $password );
		$token = $this->setSession( $data[ 'id' ], $remember );

		return [
			'id' => intval( $data[ 'id' ] ),
			'email' => $data[ 'email' ],
			'name' => $data[ 'name' ],
			'surname' => $data[ 'surname' ],
			'admin' => $data[ 'rank' ] == 1,
			'token' => $token,
			'remember' => $remember,
		];
	}

	/**
	 * Get the user's profile
	 *
	 * @param $id
	 *
	 * @return array
	 */
	public function getProfile( $id ) {
		$id = intval( $id );
		$act = "get_user_$id";
		$data = $this->dbSelect(
			$act,
			'email, users.name AS name, surname, ranks.name AS rank, ships.id AS ship_id, ships.name AS ship_name, registered_at',
			'users',
			"user_settings.disabled = 0 AND users.id = $id",
			"INNER JOIN user_settings ON users.id = user_settings.id LEFT JOIN ranks ON ranks.id =
			user_settings.rank LEFT JOIN ships ON ships.id = user_settings.ship"
		);

		if ( count( $data ) === 0 ) {
			getError( $act, 'No such user is available.', 404 );
		}

		return reset( $data );
	}

	/**
	 * Remove session from DB
	 *
	 * @param $token - token of the session to be removed
	 */
	public function logOut( $token ) {
		$this->dbDelete( 'delete_session', 'user_sessions', 'token', $token, 's' );
	}

	/**
	 * Get the list of all ships
	 *
	 * @return array
	 */
	public function getAllShips() {
		$act = "get_all_ships";

		return $this->dbSelect(
			$act,
			'id, name, serial_number, image_url',
			'ships'
		);
	}

	/**
	 * Get ship data by ID
	 *
	 * @param $id
	 * @param $crew - if true, also adds a crew list to the object (default: false)
	 *
	 * @return array
	 */
	public function getShip( $id, $crew = FALSE ) {
		$id = intval( $id );
		$act = "get_ship_$id";
		$data = $this->dbSelect(
			$act,
			'name, serial_number, image_url',
			'ships',
			"id = $id"
		);

		if ( count( $data ) === 0 ) {
			getError( $act, 'No such ship is available.', 404 );
		}

		$data = reset( $data );

		if ( $crew ) {
			$data[ 'crew' ] = $this->getAllCrewMembers( $id );
		}

		return $data;
	}

	/**
	 * Get the list of all ranks
	 *
	 * @return array
	 */
	public function getAllRanks() {
		$act = "get_all_ranks";

		return $this->dbSelect(
			$act,
			'id, name',
			'ranks'
		);
	}

	/**
	 * Get rank data by ID
	 *
	 * @param $id
	 * @param $crew - if true, also adds a crew list to the object (default: false)
	 *
	 * @return array
	 */
	public function getRank( $id, $crew = FALSE ) {
		$id = intval( $id );
		$act = "get_rank_$id";
		$data = $this->dbSelect(
			$act,
			'name',
			'ranks',
			"id = $id"
		);

		if ( count( $data ) === 0 ) {
			getError( $act, 'No such rank is available.', 404 );
		}

		$data = reset( $data );

		if ( $crew ) {
			$data[ 'crew' ] = $this->getAllCrewMembers( FALSE, $id );
			$data[ 'notifications' ] = $this->getRankNotifications( $id );
		}

		return $data;
	}

	/**
	 * Get the list of all crew members
	 *
	 * @param bool|int $ship - ID of the ship the crew members belong to (optional)
	 * @param bool|int $rank - ID of the rank the crew members have (optional)
	 *
	 * @return array
	 */
	public function getAllCrewMembers( $ship = FALSE, $rank = FALSE ) {
		$act = "get_all_crew";
		$where = FALSE;

		if ( $ship ) {
			$where = 'user_settings.ship = ' . intval( $ship );
		}
		if ( $rank ) {
			$where = 'user_settings.rank = ' . intval( $rank );
		}

		return $this->dbSelect(
			$act,
			'users.id, email, users.name, surname, ranks.id AS rank_id, ranks.name AS rank_name, ships.id AS ships_id, ships.name AS ships_name, disabled',
			'users',
			$where,
			"INNER JOIN user_settings ON users.id = user_settings.id LEFT JOIN ranks ON ranks.id = user_settings.rank LEFT JOIN ships ON ships.id = user_settings.ship"
		);
	}

	/**
	 * Get crew member data by ID
	 *
	 * @param $id
	 *
	 * @return array
	 */
	public function getCrewMember( $id ) {
		$id = intval( $id );
		$act = "get_crew_$id";
		$data = $this->dbSelect(
			$act,
			'email, name, surname, rank, ship',
			'users',
			"users.id = $id",
			"INNER JOIN user_settings ON users.id = user_settings.id"
		);

		if ( count( $data ) === 0 ) {
			getError( $act, 'No such rank is available.', 404 );
		}

		return reset( $data );
	}

	/**
	 * Insert or update the ship
	 *
	 * @param $data
	 */
	public function saveShip( $data ) {
		if ( empty( $data[ 'id' ] ) ) {
			unset( $data[ 'id' ] );
			$this->dbInsert( 'insert_ship', 'ships', $data );
		} else {
			$id = intval( $data[ 'id' ] );

			unset( $data[ 'id' ] );
			$this->dbUpdate( 'update_ship', 'ships', $data, "id = $id" );
		}
	}

	/**
	 * Insert or update the rank
	 *
	 * @param $data
	 */
	public function saveRank( $data ) {
		if ( empty( $data[ 'id' ] ) ) {
			unset( $data[ 'id' ] );
			$this->dbInsert( 'insert_rank', 'ranks', $data );
		} else {
			$id = intval( $data[ 'id' ] );

			unset( $data[ 'id' ] );
			$this->dbUpdate( 'update_rank', 'ranks', $data, "id = $id" );
		}
	}

	/**
	 * Insert the new crew member
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function addCrew( $data ) {
		$this->dbInsert( 'insert_user', 'users', [
			'name' => $data[ 'name' ],
			'surname' => $data[ 'surname' ],
			'email' => $data[ 'email' ],
		] );

		$id = $this->dbSelect( 'get_inserted_id', 'id', 'users',
			"email = '{$this->conn->real_escape_string( $data[ 'email' ] )}'" );
		$id = reset( $id );
		$id = $id[ 'id' ];
		$token = $this->generateToken( $id, 'crew' );

		$this->dbInsert( 'insert_user_settings', 'user_settings', [
			'id' => $id,
			'rank' => $data[ 'rank' ],
			'ship' => $data[ 'ship' ],
		] );
		$this->dbInsert( 'insert_password_request', 'password_requests', [
			'user_id' => $id,
			'token' => $token,
		] );

		return $token;
	}

	/**
	 * Update the crew member
	 *
	 * @param $data
	 *
	 * @return void|string - return token if just registered
	 */
	public function saveCrew( $data ) {
		$id = intval( $data[ 'id' ] );

		unset( $data[ 'id' ] );
		$this->dbUpdate( 'update_user', 'users', [
			'name' => $data[ 'name' ],
			'surname' => $data[ 'surname' ],
		], "id = $id" );
		$this->dbUpdate( 'update_user_settings', 'user_settings', [
			'rank' => $data[ 'rank' ],
			'ship' => $data[ 'ship' ],
		], "id = $id" );
	}

	/**
	 * Set or reset the user's password
	 *
	 * @param $data
	 */
	public function setPassword( $data ) {
		if ( $data[ 'password' ] !== $data[ 'password2' ] ) {
			getError( 'set_password', 'The passwords are not equal!', 409 );
		}

		$id = intval( $data[ 'id' ] );

		$this->dbUpdate( 'set_password', 'users', [ 'password' => $data[ 'password' ] ], "id = $id" );
	}

	/**
	 * Get email from reset token
	 *
	 * @param $token
	 *
	 * @return string
	 */
	public function checkToken( $token ) {
		$token = $this->conn->real_escape_string( $token );
		$id = $this->dbSelect(
			'get_set_password_id',
			'user_id',
			'password_requests',
			"token = '$token' AND date >= DATE_SUB(NOW(),INTERVAL 3 HOUR)"
		);

		if ( count( $id ) === 0 ) {
			getError( 'get_set_password_id', 'This token is invalid.', 409 );
		} else {
			$id = reset( $id );
		}

		return $id[ 'user_id' ];
	}

	/**
	 * Delete the chosen item from the database
	 *
	 * @param $type
	 * @param $id
	 * @param bool $restore (default: FALSE) - should the item be restored instead of deleted?
	 */
	public function deleteItem( $type, $id, $restore = FALSE ) {
		$id = intval( $id );

		if ( $type !== 'crew' ) {
			$this->dbDelete( "delete_$type", $type . 's', 'id', $id, 'i' );
		} else {
			$data = [
				'disabled' => $restore ? 0 : 1
			];

			$this->dbUpdate( 'disable_user', 'user_settings', $data, "id = $id" );
		}

	}

	/**
	 * Get all notifications for the current user
	 *
	 * @param $id - user ID
	 *
	 * @return void|array
	 */
	public function getUserNotifications( $id ) {
		$id = intval( $id );

		return $this->dbSelect(
			'get_user_notifications',
			'seen, notifications.id, title, content',
			'user_settings',
			"user_settings.id = $id AND user_notification.hidden = 0",
			"INNER JOIN user_notification ON user_notification.user_id = user_settings.id INNER JOIN notifications ON notifications.id = user_notification.notification_id"
		);
	}

	/**
	 * Get all notifications for the current rank
	 *
	 * @param $id - rank ID
	 *
	 * @return void|array
	 */
	public function getRankNotifications( $id ) {
		$id = intval( $id );

		return $this->dbSelect(
			'get_user_notifications',
			'id, title, content',
			'notifications',
			"ranks LIKE '%$id,%'"
		);
	}
}