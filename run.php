<?php declare(strict_types=1);

/** 
 * Start the client.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 * @license MIT
 */

/**
 * Defines the absolute path to the main directory of the application.
 * 
 * This allows referencing the application root directory from anywhere in the codebase.
 */
define('MAIN_DIR', __DIR__);

/**
 * Defines the absolute path to the main script file.
 *
 * This allows referencing the main script file from anywhere in the codebase.
 */
define('MAIN_FILE', __FILE__);

/**
 * Defines the absolute path to the source code directory.
 * 
 * This allows referencing the source code directory from anywhere in the codebase.
 */
define('SRC_DIR', MAIN_DIR . DIRECTORY_SEPARATOR . 'src');

/**
 * Requires the bootstrap script that initializes the application.
 */
require_once SRC_DIR . DIRECTORY_SEPARATOR . 'bootstrap.php';
