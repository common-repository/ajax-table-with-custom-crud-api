<?php
namespace AjdtTable;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {
        //if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        // } else {
        //     add_action( 'wepos_enqueue_scripts', [ $this, 'register' ], 5 );
        // }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
        $this->enqueue_all_scripts();
        $this->register_localize();
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : null;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : null;
            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            //$deps = isset( $style['deps'] ) ? $style['deps'] : false;
            //wp_register_style( $handle, $style['src'], $deps, AJDT_VERSION );
            wp_register_style( $handle, $style['src'] );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        global $wp_version;

        $scripts = [
            'popper_min_js' => [
                'src'       => AJDT_URL . '/js/popper.min.js',
                'in_footer' => true
            ],
            'bootstrap_min_js' => [
                'src'       => AJDT_URL . '/js/bootstrap.min.js',
                'in_footer' => true
            ],
            'bootstrap_table' => [
                'src'       => AJDT_URL . '/js/bootstrap-table.min.js',
                'in_footer' => true
            ],
            'ajdt_api' => [
                'src'       => AJDT_URL . '/js/api.js',
                'in_footer' => true
            ],
            'ajdt_bs_table' => [
                'src'       => AJDT_URL . '/js/bs_table.js',
                'in_footer' => true
            ],
            'ajdt_api_oldutil' => [
                'src'       => AJDT_URL . '/js/oldapiutil.js',
                'in_footer' => true
            ],
            'ajdt_api_util' => [
                'src'       => AJDT_URL . '/js/api_util.js',
                'in_footer' => true
            ] 
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'bootstrap_min_css' => [
                'src' =>  AJDT_URL . '/css/bootstrap.min.css'
            ],
            'all_css' => [
                'src' =>  AJDT_URL . '/css/all.css'
            ],
            'font_awesome_min_css' => [
                'src' =>  AJDT_URL . '/css/font-awesome.min.css'
            ],
            'bootstrap_table_min_css' => [
                'src' =>  AJDT_URL . '/css/bootstrap-table.min.css'
            ],
            'ajdt_style' => [
                'src' =>  AJDT_URL . '/css/style.css',
            ]
        ];

        return $styles;
    }

    public function enqueue_all_scripts() {
       //if ( ! is_admin() ) {
            // Enqueue all style 
            wp_enqueue_style( 'bootstrap_min_css' );
            wp_enqueue_style( 'all_css' );
            wp_enqueue_style( 'font_awesome_min_css' );
            wp_enqueue_style( 'bootstrap_table_min_css' );
            wp_enqueue_style( 'ajdt_style' );
 
            // Load scripts 
            wp_enqueue_script( 'popper_min_js' );
            wp_enqueue_script( 'bootstrap_min_js' );
            wp_enqueue_script( 'bootstrap_table' );

            do_action( 'ajdt_load_forntend_scripts' );
            wp_enqueue_script( 'ajdt_api' );
            wp_enqueue_script( 'ajdt_bs_table' );
            wp_enqueue_script( 'ajdt_api_util' );
            
       // }
    }

    /**
     * Set localize script data
     * @return void
     */
    public function register_localize() {
        $localize_data = apply_filters( 'adjt_localize_data', [
            'rest' => array(
                'privusername'    => 'shajeebs',
                'root'    => esc_url_raw( get_rest_url() ),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
                'ajdtversion' => AJDT_VERSION,
            ),
            'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
            'nonce'                        => wp_create_nonce( 'ajdt_nonce' ),
            'libs'                         => [],
            'routeComponents'              => array( 'default' => null ),
            'assets_url'                   => AJDT_ASSETS,
            'ajax_loader'                  => AJDT_ASSETS . '/images/spinner-2x.gif',
            'logout_url'                   => wp_logout_url( site_url() )
        ] );
        wp_localize_script( 'ajdt_api_util', 'ajdt', $localize_data );
    }

     /**
     * SPA Routes
     *
     * @return array
     */
    public function get_vue_admin_routes() {
        $routes = array(
            array(
                'path'      => '/settings',
                'name'      => 'Settings',
                'component' => 'Settings'
            ),
        );

        return apply_filters( 'wepos_admin_routes', $routes );
    }
}
