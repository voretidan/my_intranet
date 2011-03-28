<?php
/**
 * Path to the config directory including trailing DS
 */
define('CONFIG', PATH . DS . 'config' . DS);
/**
 * Include config files
 */
require_once(CONFIG . 'config.php');
require_once(CONFIG . 'database.php');
/**
 * Path to the lib directory including trailing DS
 */
define('LIB', PATH . DS . 'lib' . DS);
/**
 * Include library files
 */
require_once(LIB . 'error.php');
require_once(LIB . 'database.php');
require_once(LIB . 'validation.php');
require_once(LIB . 'html.php');
require_once(LIB . 'form.php');
require_once(LIB . 'utils.php');
require_once(LIB . 'app.php');
require_once(LIB . 'auth.php');
/**
 * Include vendor files
 */
require_once(PATH . DS . 'vendors' . DS . 'htmlpurifier-4.2.0' . DS . 'library' . DS . 'HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();

/**
 * Connect to database;
 */
database_connect();