<?php
/**
 * This is a helper class for the SS installer.
 * 
 * It does all the specific checking for MSSQLDatabase
 * to ensure that the configuration is setup correctly.
 * 
 * @package mssql
 */
class PDOMySQLDatabaseConfigurationHelper implements DatabaseConfigurationHelper {

	/**
	 * Ensure that the database function for connectivity is available.
	 * If it is, we assume the PHP module for this database has been setup correctly.
	 *
	 * @param array $databaseConfig Associative array of db configuration, e.g. "server", "username" etc
	 * @return boolean
	 */
	public function requireDatabaseFunctions($databaseConfig) {
		return class_exists('PDO');
	}

	/**
	 * Ensure that the database server exists.
	 * @param array $databaseConfig Associative array of db configuration, e.g. "server", "username" etc
	 * @return array Result - e.g. array('success' => true, 'error' => 'details of error')
	 */
	public function requireDatabaseServer($databaseConfig) {
		if(!empty($databaseConfig['port'])) {
			$dsn = "mysql:host={$databaseConfig['server']};port={$databaseConfig['port']}";
		} else {
			$dsn = "mysql:host={$databaseConfig['server']}";
		}
		$success = false;
		$error = '';
		try {
			$conn = new PDO($dsn, $databaseConfig['username'], $databaseConfig['password']);
			if($conn && $conn->errorCode()) {
				$success = false;
				$error = $conn->errorCode();
			} else {
				$success = true;
			}
		} catch (PDOException $e) {
			$error = $e->getMessage();
		}
		

		return array(
			'success' => $success,
			'error' => $error
		);
	}

	/**
	 * Get the database version for the MySQL connection, given the
	 * database parameters.
	 * @return mixed string Version number as string | boolean FALSE on failure
	 */
	public function getDatabaseVersion($databaseConfig) {
		if(!empty($databaseConfig['port'])) {
			$dsn = "mysql:host={$databaseConfig['server']};port={$databaseConfig['port']}";
		} else {
			$dsn = "mysql:host={$databaseConfig['server']}";
		}
		
		$version = false;
		try {
			$conn = new PDO($dsn, $databaseConfig['username'], $databaseConfig['password']);
			$version = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
			if (!$version) {
				// fallback to trying a query
				$result = $conn->query('SELECT VERSION()');
				$row = $result->fetch();
				if($row && isset($row[0])) {
					$version = trim($row[0]);
				}
			}
		} catch (PDOException $e) {
			return false;
		}

		return $version;
	}

	/**
	 * Ensure that the MySQL server version is at least 5.0.
	 * @param array $databaseConfig Associative array of db configuration, e.g. "server", "username" etc
	 * @return array Result - e.g. array('success' => true, 'error' => 'details of error')
	 */
	public function requireDatabaseVersion($databaseConfig) {
		$version = $this->getDatabaseVersion($databaseConfig);
		$success = false;
		$error = '';
		if($version) {
			$success = version_compare($version, '5.0', '>=');
			if(!$success) {
				$error = "Your MySQL server version is $version. It's recommended you use at least MySQL 5.0.";
			}
		} else {
			$error = "Could not determine your MySQL version.";
		}
		return array(
			'success' => $success,
			'error' => $error
		);
	}

	/**
	 * Ensure a database connection is possible using credentials provided.
	 * @param array $databaseConfig Associative array of db configuration, e.g. "server", "username" etc
	 * @return array Result - e.g. array('success' => true, 'error' => 'details of error')
	 */
	public function requireDatabaseConnection($databaseConfig) {
		if(!empty($databaseConfig['port'])) {
			$dsn = "mysql:host={$databaseConfig['server']};port={$databaseConfig['port']}";
		} else {
			$dsn = "mysql:host={$databaseConfig['server']}";
		}
		
		$success = false;
		$error = '';
		
		try {
			$conn = new PDO($dsn, $databaseConfig['username'], $databaseConfig['password']);
			$version = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
			if ($conn) {
				$success = true;
			} else {
				$error = 'Unable to connect to database.';
			}
		} catch (PDOException $e) {
			return $error = $e->getMessage();
		}
		
		return array(
			'success' => $success,
			'error' => $error
		);
	}

	/**
	 * Ensure that the database connection is able to use an existing database,
	 * or be able to create one if it doesn't exist.
	 *
	 * @param array $databaseConfig Associative array of db configuration, e.g. "server", "username" etc
	 * @return array Result - e.g. array('success' => true, 'alreadyExists' => 'true')
	 */
	public function requireDatabaseOrCreatePermissions($databaseConfig) {
		if(!empty($databaseConfig['port'])) {
			$dsn = "mysql:host={$databaseConfig['server']};port={$databaseConfig['port']}";
		} else {
			$dsn = "mysql:host={$databaseConfig['server']}";
		}
		$success = false;
		$alreadyExists = false;
		
		$exists = null;
		$conn = null;
		try {
			$conn = new PDO($dsn, $databaseConfig['username'], $databaseConfig['password']);
			$exists = $conn->query('USE `' . $databaseConfig['database'] . '`');
		} catch (PDOException $e) {
			;
		}
		
		if($conn && $exists) {
			$success = true;
			$alreadyExists = true;
		} else {
			$test = null;
			$conn = new PDO($dsn, $databaseConfig['username'], $databaseConfig['password']);
			try {
				$test = $conn->query('CREATE DATABASE `testing123`');
			} catch (PDOException $e) {
				;
			}
			
			if($conn && $test) {
				$test2 = $conn->query('DROP DATABASE `testing123`');
				$success = true;
				$alreadyExists = false;
			}
		}
		
		return array(
			'success' => $success,
			'alreadyExists' => $alreadyExists
		);
	}

}
