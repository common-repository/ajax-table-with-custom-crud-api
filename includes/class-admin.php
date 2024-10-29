<?php
namespace AjdtTable;

/**
 * Admin Pages Handler
 */
class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_shortcode('AJDT', 'ajdt_handle_shortcode'); 
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page(__('AJDT Settings', 'ajdt'), __('AJDT Settings', 'ajdt'), 
            'activate_plugins', 'ajdtsettings', 'ajdt_list_api');

        add_submenu_page('ajdtsettings', __('AJDT Short Codes', 'fgpt'), __('AJDT Short Codes', 'fgpt'), 
            'activate_plugins', 'ajdtshortcodes', 'ajdt_render_shortcode');

        // foreach (get_option(AJDT_APILISTNAME) as $key => $Api) {
        //     //echo do_shortcode("[AJDT api='$key' allapi='$AllKeys']");
        //     add_submenu_page('ajdtsettings', __('Short Codes - '.$key, 'fgpt'), __('Short Codes - '.$key, 'fgpt'), 
        //     'activate_plugins', 'ajdtshortcodes', 'ajdt_render_shortcode');
        // }
    }
}
