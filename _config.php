<?php
/**
 *	To install (install Silverstripe), copy the following (uncommented) code to the bottom 
 *	of /framework/_register_database.php
 */
/*

DatabaseAdapterRegistry::register(
	array(
		'class' => 'PDOMySQLDatabase',
		'title' => 'PDO MySQL 5.0+',
		'helperPath' => 'pdomysql/code/PDOMySQLDatabaseConfigurationHelper.php',
		'supported' => (class_exists('PDO')),
		'missingExtensionText' => 'The <a href="http://php.net/manual/en/book.pdo.php">PDO</a> ' . 
			'PHP extension is not available. Please install or enable one of them and refresh this page.',
		'fields' => array(
			'server' => array(
				'title' => 'Database server',
				'default' => 'localhost'
			),
			'port' => array(
				'title' => 'Database port:<br /><small>leave blank if using localhost</small>',
				'default' => ''
			),
			'username' => array(
				'title' => 'Database username',
				'default' => 'root'
			),
			'password' => array(
				'title' => 'Database password',
				'default' => '',
				'type' => 'password'
			),
			'database' => array(
				'title' => 'Database name',
				'default' => 'ss_mysite'
			),
		),
	)
);

*/