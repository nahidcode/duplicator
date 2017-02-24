<?php
/**
 * Lightweight abstraction layer for common simple database routines
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package Duplicator
 * @subpackage classes/utilities
 * @copyright (c) 2017, Snapcreek LLC
 * @since 1.1.32
 *
 */

// Exit if accessed directly
if (!defined('DUPLICATOR_VERSION')) {
    exit;
}

class DUP_DB extends wpdb
{

    /**
     * Get the requested MySQL system variable
     *
     * @param string $name The database variable name to lookup
     *
     * @return string the server variable to query for
     */
    public static function getVariable($name)
    {
        global $wpdb;
        $row = $wpdb->get_row("SHOW VARIABLES LIKE '{$name}'", ARRAY_N);
        return isset($row[1]) ? $row[1] : null;
    }

    /**
     * Gets the MySQL database version number
     *
     * @param bool $full    True:  Gets the full version
     *                      False: Gets only the numeric portion i.e. 5.5.6 or 10.1.2 (for MariaDB)
     *
     * @return false|string 0 on failure, version number on success
     */
    public static function getVersion($full = false)
    {
        if ($full) {
            $version = self::getVariable('version');
        } else {
            $version = preg_replace('/[^0-9.].*/', '', self::getVariable('version'));
        }

        return empty($version) ? 0 : $version;
    }
}