<?php
namespace AjdtTable;

/** Deals with Api list cache
 * 
 */
class ApiCache {
    
    /**
     * Initializes the ApiCache() class
     *
     * Checks for an existing ApiCache() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new ApiCache();
        }

        return $instance;
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function ajdt_get_cachecolumn_names($api) { 
        
        return "<div id='mount_$api'>
                    <input type='hidden' name='AllApi' value='$allapi'>
                    <table data-toggle='table'>
                        <thead>
                            <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Item Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td>1</td>
                            <td>Item 1</td>
                            <td>$1</td>
                            </tr>
                            <tr>
                            <td>2</td>
                            <td>Item 2</td>
                            <td>$2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>"; 
    }

    public function ajdt_get_cacheapi_names() { 
        $apiList = get_option(AJDT_APILISTNAME);
        return implode(',', array_keys($apiList));
    }
}
