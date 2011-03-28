<?php
/**
 * Start the user session so we can remember things
 */
session_start();
/**
 * Define the directory seperator for the OS
 */
define('DS', '/');
/**
 * Path to the lib directory WITHOUT trailing DS
 */
define('PATH', DS . 'home' . DS . 'gregblog' . DS . 'public_html' . DS . 'adam');
/**
 * Path to the webroot from the root of the url NOT on the operating system INCLUDING trailing /
 */
define('BASE', '/adam/public_html/');
/**
 * Include stuff to get started
 */
require_once(PATH . DS . 'lib' . DS . 'bootstrap.php');
/**
 * Start!
 */
dispatch();
/**
 * Delete the flash at the end of every request
 */
unset($_SESSION['flash']);
