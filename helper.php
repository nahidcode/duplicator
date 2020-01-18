<?php
defined('ABSPATH') || exit;

if (!function_exists('duplicator_get_home_path')) {
    function duplicator_get_home_path() {
        static $homePath = null;
        if (is_null($homePath)) {
            if (!function_exists('get_home_path')) {
                require_once(ABSPATH.'wp-admin/includes/file.php');
            }
            $homePath = wp_normalize_path(get_home_path());
            if ($homePath == '//' || $homePath == '') {
                $homePath = '/';
            } else {
                $homePath = rtrim($homePath, '/');
            }
        }
        return $homePath;
    }
}

if (!function_exists('duplicator_get_abs_path')) {
    function duplicator_get_abs_path() {
        static $absPath = null;
        if (is_null($absPath)) {
            $absPath = wp_normalize_path(ABSPATH);
            if ($absPath == '//' || $absPath == '') {
                $absPath = '/';
            } else {
                $absPath = rtrim($absPath, '/');
            }
        }
        return $absPath;
    }
}