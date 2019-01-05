<?php
defined("ABSPATH") or die("");

class DUP_Web_Services
{

    /**
     * init ajax actions
     */
    public static function init()
    {
        add_action('wp_ajax_duplicator_reset_all_settings', array(__CLASS__, 'ajax_reset_all'));
    }

    /**
     * reset all ajax action
     *
     */
    public static function ajax_reset_all()
    {
        ob_start();
        try {
            /** Execute function **/
            $error  = false;
            $result = array(
                'data' => array(),
                'html' => '',
                'message' => ''
            );

            $result['message'] = 'ok';

            //throw new Exception('force error test');
        } catch (Exception $e) {
            $error             = true;
            $result['message'] = $e->getMessage();
        }

        /** Intercept output **/
        $result['html'] = ob_get_clean();

        /** check error and return json **/
        if ($error) {
            wp_send_json_error($result);
        } else {
            wp_send_json_success($result);
        }
    }
}