<?php
/**
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

/**
 * View functions
 */
class DUPX_View_Funcs
{
    public static function installerLogLink()
    {
        $log_url =  $GLOBALS['DUPX_ROOT_URL'].'/'.$GLOBALS["LOG_FILE_NAME"].'?now='.DUPX_U::esc_attr($GLOBALS['NOW_TIME']);
        DUPX_U_Html::getLightBoxIframe('installer-log.txt', 'installer-log.txt', $log_url, true, true);
    }
}