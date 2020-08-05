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
		} else {
			$data = reset( $data );

			if ( ! empty( $data[ 'disabled' ] ) ) {
				getError( $request, 'This profile has been deactivated.', 403, FALSE, TRUE, $softError );
			}
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

		//TODO set session and generate the token

		return $data;
	}
}