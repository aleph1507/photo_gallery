<?php 

	// define core paths as absolute paths
	// DIRECTORY_SEPARATOR is a php predefined constant
	// \ for win32, / for *nix

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	defined('SITE_ROOT') ? null : define('SITE_ROOT', 'd:' . DS . 'apache' . DS . 'htdocs' . DS . 'pbb' . DS . 'Exercise Files' . DS . 'photo_gallery');

	defined('LOG_FOLDER') ? null : define('LOG_FOLDER', SITE_ROOT . DS . 'log');

	defined('LOG_FILE') ? null : define('LOG_FILE', LOG_FOLDER . DS . 'log.log');

	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

	//load config file first
	require_once(LIB_PATH.DS.'config.php');

	//load basic functions
	require_once(LIB_PATH.DS.'functions.php');

	//load core objects
	require_once(LIB_PATH.DS.'session.php');
	require_once(LIB_PATH.DS.'database.php');
	require_once(LIB_PATH.DS.'database_object.php');


	//load database-related classes
	require_once(LIB_PATH.DS.'user.php');
	require_once(LIB_PATH.DS.'photograph.php');

	// Load logger
	require_once(LIB_PATH.DS.'logger.php');


?>