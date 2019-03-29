<?php
if ( ! defined( 'ABSPATH' ) )
    exit;


function duplicator_deactivation_enqueue_scripts($hook) {
    if ('plugins.php' == $hook && ! defined('DOING_AJAX')) {
        wp_enqueue_style('duplicator-deactivation-modal', DUPLICATOR_PLUGIN_URL . 'assets/css/modal.css', array(), '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'duplicator_deactivation_enqueue_scripts');



if ( ! function_exists ( 'duplicator_plugins_admin_footer' ) ) {
    function duplicator_plugins_admin_footer() {
        global $hook_suffix;

        if ( 'plugins.php' == $hook_suffix && ! defined( 'DOING_AJAX' ) ) {
            duplicator_add_deactivation_feedback_dialog_box();
        }
    }
}
add_action( 'admin_footer', 'duplicator_plugins_admin_footer' );

/**
 * Displays a confirmation and feedback dialog box when the user clicks on the "Deactivate" link on the plugins
 * page.
 *
 * @since  2.1.3
 */
if ( ! function_exists( 'duplicator_add_deactivation_feedback_dialog_box' ) ) {
	function duplicator_add_deactivation_feedback_dialog_box() {
        $basename = 'duplicator/duplicator.php';
        /*
        $slug = dirname( $basename );
        $plugin_id = sanitize_title( $plugin_data['Name'] );
        */
        $slug = 'duplicator';
        $plugin_id = 'duplicator';

        $contact_support_template = __( 'Need help? We are ready to answer your questions.', 'duplicator' ) . ' <a href="https://snapcreek.com/ticket/" target="_blank">' . __( 'Contact Support', 'duplicator' ) . '</a>';

        $reasons = array(
            array(
                'id'                => 'NOT_WORKING',
                'text'              => __( 'The plugin is not working', 'duplicator' ),
                'input_type'        => 'textarea',
                'input_placeholder' => __( "Kindly share what didn't work so we can fix it in future updates...", 'duplicator' )
            ),
            array(
                'id'                => 'DIDNT_WORK_AS_EXPECTED',
                'text'              => __( "The plugin didn't work as expected", 'duplicator' ),
                'input_type'        => 'textarea',
                'input_placeholder' => __( 'What did you expect?', 'duplicator' )
            ),
            array(
                'id'                => 'SUDDENLY_STOPPED_WORKING',
                'text'              => __( 'The plugin suddenly stopped working', 'duplicator' ),
                'input_type'        => '',
                'input_placeholder' => '',
                'internal_message'  => $contact_support_template
            ),
            array(
                'id'                => 'BROKE_MY_SITE',
                'text'              => __( 'The plugin broke my site', 'duplicator' ),
                'input_type'        => '',
                'input_placeholder' => '',
                'internal_message'  => $contact_support_template
            ),
            array(
                'id'                => 'COULDNT_MAKE_IT_WORK',
                'text'              => __( "I couldn't understand how to get it work", 'duplicator' ),
                'input_type'        => '',
                'input_placeholder' => '',
                'internal_message'  => $contact_support_template
            ),
            array(
                'id'                => 'FOUND_A_BETTER_PLUGIN',
                'text'              => __( 'I found a better plugin', 'duplicator' ),
                'input_type'        => 'textfield',
                'input_placeholder' => __( "What's the plugin name?", 'duplicator' )
            ),
            array(
                'id'                => 'GREAT_BUT_NEED_SPECIFIC_FEATURE',
                'text'              => __( "The plugin is great, but I need specific feature that you don't support", 'duplicator' ),
                'input_type'        => 'textarea',
                'input_placeholder' => __( 'What feature?', 'duplicator' )
            ),
            array(
                'id'                => 'NO_LONGER_NEEDED',
                'text'              => __( 'I no longer need the plugin', 'duplicator' ),
                'input_type'        => '',
                'input_placeholder' => ''
            ),
            array(
                'id'                => 'TEMPORARY_DEACTIVATION',
                'text'              => __( "It's a temporary deactivation, I'm just debugging an issue", 'duplicator' ),
                'input_type'        => '',
                'input_placeholder' => ''
            ),
            array(
                'id'                => 'OTHER',
                'text'              => __( 'Other', 'duplicator' ),
                'input_type'        => 'textfield',
                'input_placeholder' => ''
            )				
        );

        $reasons_list_items_html = '';

        foreach ( $reasons as $reason ) {
            $list_item_classes = 'duplicator-modal-reason' . ( ! empty( $reason['input_type'] ) ? ' has-input' : '' );

            if ( ! empty( $reason['internal_message'] ) ) {
                $list_item_classes .= ' has-internal-message';
                $reason_internal_message = $reason['internal_message'];
            } else {
                $reason_internal_message = '';
            }

            $reasons_list_items_html .= '<li class="' . $list_item_classes . '" data-input-type="' . $reason['input_type'] . '" data-input-placeholder="' . $reason['input_placeholder'] . '">
                <label>
                    <span>
                        <input type="radio" name="selected-reason" value="' . $reason['id'] . '"/>
                    </span>
                    <span>' . $reason['text'] . '</span>
                </label>
                <div class="duplicator-modal-internal-message">' . $reason_internal_message . '</div>
            </li>';
        } ?>
        <script type="text/javascript">
            (function($) {
                var modalHtml =
                        '<div class="duplicator-modal duplicator-modal-deactivation-feedback">'
                        + '	<div class="duplicator-modal-dialog">'
                        + '		<div class="duplicator-modal-body">'
                        + '		    <h2><?php _e( 'Quick Feedback', 'duplicator' ); ?></h2>'
                        + '			<div class="duplicator-modal-panel active"><p><?php _e( 'If you have a moment, please let us know why you are deactivating', 'duplicator' ); ?>:</p><ul>' + <?php echo json_encode( $reasons_list_items_html ); ?> + '</ul>'
                        + '         <label class="duplicator-modal-anonymous-label">'
                        + '				<input type="checkbox" checked="checked" />'
                        + '				<?php _e( 'Send website data', 'duplicator' ); ?>'
                        + '			</label>'
                        + '			</div>'
                        + '		</div>'
                        + '		<div class="duplicator-modal-footer">'
                        + '			<a href="#" class="button button-secondary duplicator-modal-button-close"><?php _e(  'Cancel', 'duplicator' ); ?></a>'
                        + '			<a href="#" class="button button-primary duplicator-modal-button-deactivate"></a>'
                        + '			<div class="clear"></div>'
                        + '		</div>'
                        + '	</div>'
                        + '</div>',
                    $modal                = $( modalHtml ),
                    $deactivateLink       = $( '#the-list .active[data-plugin="<?php echo $basename; ?>"] .deactivate a' ),
                    $anonymousFeedback    = $modal.find( '.duplicator-modal-anonymous-label' ),
                    selectedReasonID      = false;

                /* WP added data-plugin attr after 4.5 version/ In prev version was id attr */
                if ( 0 == $deactivateLink.length )
                    $deactivateLink = $( '#the-list .active#<?php echo $plugin_id; ?> .deactivate a' );

                $modal.appendTo( $( 'body' ) );

                DuplicatorModalRegisterEventHandlers();
                
                function DuplicatorModalRegisterEventHandlers() {
                    $deactivateLink.click( function( evt ) {
                        evt.preventDefault();

                        /* Display the dialog box.*/
                        DuplicatorModalReset();
                        $modal.addClass( 'active' );
                        $( 'body' ).addClass( 'has-duplicator-modal' );
                    });

                    $modal.on( 'input propertychange', '.duplicator-modal-reason-input input', function() {
                        if ( ! DuplicatorModalIsReasonSelected( 'OTHER' ) ) {
                            return;
                        }

                        var reason = $( this ).val().trim();

                        /* If reason is not empty, remove the error-message class of the message container to change the message color back to default. */
                        if ( reason.length > 0 ) {
                            $modal.find( '.message' ).removeClass( 'error-message' );
                            DuplicatorModalEnableDeactivateButton();
                        }
                    });

                    $modal.on( 'blur', '.duplicator-modal-reason-input input', function() {
                        var $userReason = $( this );

                        setTimeout( function() {
                            if ( ! DuplicatorModalIsReasonSelected( 'OTHER' ) ) {
                                return;
                            }

                            /* If reason is empty, add the error-message class to the message container to change the message color to red. */
                            if ( 0 === $userReason.val().trim().length ) {
                                $modal.find( '.message' ).addClass( 'error-message' );
                                DuplicatorModalDisableDeactivateButton();
                            }
                        }, 150 );
                    });

                    $modal.on( 'click', '.duplicator-modal-footer .button', function( evt ) {
                        evt.preventDefault();

                        if ( $( this ).hasClass( 'disabled' ) ) {
                            return;
                        }

                        var _parent = $( this ).parents( '.duplicator-modal:first' ),
                            _this = $( this );

                        if ( _this.hasClass( 'allow-deactivate' ) ) {
                            var $radio = $modal.find( 'input[type="radio"]:checked' );

                            if ( 0 === $radio.length ) {
                                /* If no selected reason, just deactivate the plugin. */
                                window.location.href = $deactivateLink.attr( 'href' );
                                return;
                            }

                            var $selected_reason = $radio.parents( 'li:first' ),
                                $input = $selected_reason.find( 'textarea, input[type="text"]' ),
                                userReason = ( 0 !== $input.length ) ? $input.val().trim() : '';

                            if ( DuplicatorModalIsReasonSelected( 'OTHER' ) && '' === userReason ) {
                                return;
                            }

                            var is_anonymous = ( $anonymousFeedback.find( 'input' ).is( ':checked' ) ) ? 0 : 1;

                            $.ajax({
                                url       : ajaxurl,
                                method    : 'POST',
                                data      : {
                                    'action'			: 'duplicator_submit_uninstall_reason_action',
                                    'plugin'			: '<?php echo $basename; ?>',
                                    'reason_id'			: $radio.val(),
                                    'reason_info'		: userReason,
                                    'is_anonymous'		: is_anonymous,
                                    'duplicator_ajax_nonce'	: '<?php echo wp_create_nonce( 'duplicator_ajax_nonce' ); ?>'
                                },
                                beforeSend: function() {
                                    _parent.find( '.duplicator-modal-footer .button' ).addClass( 'disabled' );
                                    _parent.find( '.duplicator-modal-footer .button-secondary' ).text( '<?php _e( 'Processing', 'duplicator' ); ?>' + '...' );
                                },
                                complete  : function( message ) {
                                    /* Do not show the dialog box, deactivate the plugin. */
                                    window.location.href = $deactivateLink.attr( 'href' );
                                }
                            });
                        } else if ( _this.hasClass( 'duplicator-modal-button-deactivate' ) ) {
                            /* Change the Deactivate button's text and show the reasons panel. */
                            _parent.find( '.duplicator-modal-button-deactivate' ).addClass( 'allow-deactivate' );
                            DuplicatorModalShowPanel();
                        }
                    });

                    $modal.on( 'click', 'input[type="radio"]', function() {
                        var $selectedReasonOption = $( this );

                        /* If the selection has not changed, do not proceed. */
                        if ( selectedReasonID === $selectedReasonOption.val() )
                            return;

                        selectedReasonID = $selectedReasonOption.val();

                        $anonymousFeedback.show();

                        var _parent = $( this ).parents( 'li:first' );

                        $modal.find( '.duplicator-modal-reason-input' ).remove();
                        $modal.find( '.duplicator-modal-internal-message' ).hide();
                        $modal.find( '.duplicator-modal-button-deactivate' ).text( '<?php _e( 'Submit & Deactivate', 'duplicator' ); ?>' );

                        DuplicatorModalEnableDeactivateButton();

                        if ( _parent.hasClass( 'has-internal-message' ) ) {
                            _parent.find( '.duplicator-modal-internal-message' ).show();
                        }

                        if (_parent.hasClass('has-input')) {
                            var reasonInputHtml = '<div class="duplicator-modal-reason-input"><span class="message"></span>' + ( ( 'textfield' === _parent.data( 'input-type' ) ) ? '<input type="text" />' : '<textarea rows="5" maxlength="200"></textarea>' ) + '</div>';

                            _parent.append( $( reasonInputHtml ) );
                            _parent.find( 'input, textarea' ).attr( 'placeholder', _parent.data( 'input-placeholder' ) ).focus();

                            if ( DuplicatorModalIsReasonSelected( 'OTHER' ) ) {
                                $modal.find( '.message' ).text( '<?php _e( 'Please tell us the reason so we can improve it.', 'duplicator' ); ?>' ).show();
                                DuplicatorModalDisableDeactivateButton();
                            }
                        }
                    });

                    /* If the user has clicked outside the window, cancel it. */
                    $modal.on( 'click', function( evt ) {
                        var $target = $( evt.target );

                        /* If the user has clicked anywhere in the modal dialog, just return. */
                        if ( $target.hasClass( 'duplicator-modal-body' ) || $target.hasClass( 'duplicator-modal-footer' ) ) {
                            return;
                        }

                        /* If the user has not clicked the close button and the clicked element is inside the modal dialog, just return. */
                        if ( ! $target.hasClass( 'duplicator-modal-button-close' ) && ( $target.parents( '.duplicator-modal-body' ).length > 0 || $target.parents( '.duplicator-modal-footer' ).length > 0 ) ) {
                            return;
                        }

                        /* Close the modal dialog */
                        $modal.removeClass( 'active' );
                        $( 'body' ).removeClass( 'has-duplicator-modal' );

                        return false;
                    });
                }

                function DuplicatorModalIsReasonSelected( reasonID ) {
                    /* Get the selected radio input element.*/
                    return ( reasonID == $modal.find('input[type="radio"]:checked').val() );
                }

                function DuplicatorModalReset() {
                    selectedReasonID = false;

                    DuplicatorModalEnableDeactivateButton();

                    /* Uncheck all radio buttons.*/
                    $modal.find( 'input[type="radio"]' ).prop( 'checked', false );

                    /* Remove all input fields ( textfield, textarea ).*/
                    $modal.find( '.duplicator-modal-reason-input' ).remove();

                    $modal.find( '.message' ).hide();

                    $anonymousFeedback.find( 'input' ).prop( 'checked', true );

                    /* Hide, since by default there is no selected reason.*/
                    $anonymousFeedback.hide();

                    var $deactivateButton = $modal.find( '.duplicator-modal-button-deactivate' );

                    $deactivateButton.addClass( 'allow-deactivate' );
                    DuplicatorModalShowPanel();
                }

                function DuplicatorModalEnableDeactivateButton() {
                    $modal.find( '.duplicator-modal-button-deactivate' ).removeClass( 'disabled' );
                }

                function DuplicatorModalDisableDeactivateButton() {
                    $modal.find( '.duplicator-modal-button-deactivate' ).addClass( 'disabled' );
                }

                function DuplicatorModalShowPanel() {
                    $modal.find( '.duplicator-modal-panel' ).addClass( 'active' );
                    /* Update the deactivate button's text */
                    $modal.find( '.duplicator-modal-button-deactivate' ).text( '<?php _e( 'Deactivate', 'duplicator' ); ?>' );
                }
            })(jQuery);
        </script>
	<?php
	}
}

/**
 * Called after the user has submitted his reason for deactivating the plugin.
 *
 */
if ( ! function_exists( 'duplicator_submit_uninstall_reason_action' ) ) {
	function duplicator_submit_uninstall_reason_action() {
		global $wp_version, $current_user;

		wp_verify_nonce( $_REQUEST['duplicator_ajax_nonce'], 'duplicator_ajax_nonce' );

		$reason_id = isset( $_REQUEST['reason_id'] ) ? stripcslashes( esc_html( $_REQUEST['reason_id'] ) ) : '';
		$basename = isset( $_REQUEST['plugin'] ) ? stripcslashes( esc_html( $_REQUEST['plugin'] ) ) : '';

		if ( empty( $reason_id ) || empty( $basename ) ) {
			exit;
		}

		$reason_info = isset( $_REQUEST['reason_info'] ) ? stripcslashes( esc_html( $_REQUEST['reason_info'] ) ) : '';
		if ( ! empty( $reason_info ) ) {
			$reason_info = substr( $reason_info, 0, 255 );
		}
		$is_anonymous = isset( $_REQUEST['is_anonymous'] ) && 1 == $_REQUEST['is_anonymous'];

		$options = array(
			'product'		=> $basename,
			'reason_id'		=> $reason_id,
			'reason_info'	=> $reason_info,
        );
        
        $duplicator_usage_id = false;

		if ( ! $is_anonymous ) {
			$duplicator_usage_id = ( is_multisite() ) ? get_site_option( 'duplicator_usage_id', false ) : get_option( 'duplicator_usage_id', false );

			if ( false !== $duplicator_usage_id ) {
				$options['usage_id'] = $duplicator_usage_id;
            } /*else {*/
            $options['url'] = get_bloginfo( 'url' );
            $options['wp_version'] = $wp_version;
            $options['php_version'] = PHP_VERSION;
            $options['plugin_version'] = DUPLICATOR_VERSION;
            $options['sapi_name'] = php_sapi_name();
            $options['server'] = $_SERVER['SERVER_SOFTWARE'];
            $options['email'] = $current_user->data->user_email;
            //}
		}

		/* send data */
		$raw_response = wp_remote_post('http://snap.local/wp-content/plugins/plugins-statistics/deactivation-feedback/', array(
			'method'  => 'POST',
			'body'    => $options,
			'timeout' => 15,
        ) );
        
		if ( ! is_wp_error( $raw_response ) && 200 == wp_remote_retrieve_response_code( $raw_response ) ) {
			if ( ! $is_anonymous ) {
				$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );			
				if ( is_array( $response ) && ! empty( $response['usage_id'] ) && $response['usage_id'] != $duplicator_usage_id) {
					if ( is_multisite() )
						update_site_option( 'duplicator_usage_id', $response['usage_id'] );
					else
						update_option( 'duplicator_usage_id', $response['usage_id'] );
				}
			}			

			echo 'done';
		} else {
			echo $response->get_error_code() . ': ' . $response->get_error_message();
		}
		exit;
	}
}

add_action( 'wp_ajax_duplicator_submit_uninstall_reason_action', 'duplicator_submit_uninstall_reason_action' );