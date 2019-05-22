<?php
defined("ABSPATH") or die("");
// New encryption class

class DUP_WPEngine_Host {
    public static function init() {
        add_filter('duplicator_installer_file_path', array('DUP_WPEngine_Host', 'installerFilePath'), 10, 1);
    }

    public static function installerFilePath($path) {
        $path_info = pathinfo($path);
        $newPath = $path;
        if ('php' == $path_info['extension']) {
            $newPath = substr_replace($path, '.txt', -4);            
        }
        return $newPath;
    }
}

DUP_WPEngine_Host::init();