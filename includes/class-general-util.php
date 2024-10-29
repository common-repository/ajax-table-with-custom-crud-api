<?php

    /**
     * Retrieves all the tables 
     * @return void
     */
    function ajdt_get_tables(){
        global $wpdb;
        $sql = "SHOW TABLES LIKE '%%'";
        return $wpdb->get_results($sql);
    }

    /**
     * Retrieves keys of the table 
     * @return void
     */
    function ajdt_get_table_Key($table){
        global $wpdb;
        $sql = "SELECT COLUMN_NAME FROM   information_schema.key_column_usage 
        WHERE  table_schema = schema() AND constraint_name = 'PRIMARY' AND table_name = '$table'";
        return $wpdb->get_row($sql);
    }

    /**
     * Retrieves keys of the table 
     * @return void
     */
    function ajdt_get_table_columns($table){
        global $wpdb;
        $sql = "SHOW COLUMNS FROM $table WHERE Extra <> 'auto_increment'";
        return $wpdb->get_results($sql);
    }

    /**
     * Checks for wordpress shortcode and renders it based on number of Api List 
     * Example: [AJDT api="sha1"]
     * @return void
     */
    function ajdt_render_shortcode(){
    ?>
<div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
                <br>
                <div class="bd-container-body">
                    <div class="row"> <div class="col-md-6">
                    <form action="admin.php?page=ajdtshortcodes" method="post">
                        <div class="input-group">
                        <label for="table-name" class="col-form-label">API Name:</label><br>
                        <select class="custom-select" name="api-name" id="api-name">
                            <option value='all'>All</option>
                        <?php $apiList = get_option(AJDT_APILISTNAME);
                            foreach($apiList as $k => $v){
                                if(isset($_POST['api-name']) && $_POST['api-name'] == $k)
                                    echo "<option value='$k' selected>$k</option>";
                                else
                                    echo "<option value='$k'>$k</option>";
                            } ?>
                        </select>
                        <div class="input-group-append">
                            <input type="submit" class="btn btn-success" id="btnRenderApi" value="Render API Data" />
                        </div>
                        </div>
                    </form>
                    </div>
                    <div class="col-md-6">
                        <div class="usageBox">
                        <label>Short Code:</label><br>
                        <label class="code" id="lblShortCode">[AJDT api='apiname']</label><br>
                        <label>From Code:</label><br>
                        <label class="code" id="lblFromCode">echo do_shortcode("[AJDT api='apiname']");</label>
                        </div>
                    </div>
                    </div>
              </div>   <!--bd-container-body -->    
            </div> <!--class col-md-12 -->
          </div> <!--class row -->
          <div class="row">
            <div class="col-md-12">
                   <div id="dvApiDataContainer">
                   <?php 
                    if (isset($_POST['api-name'])) {
                        $apiName = $_POST['api-name'];
                        if ($apiName == 'all') {
                            foreach (get_option(AJDT_APILISTNAME) as $key => $Api) {
                                echo do_shortcode("[AJDT api='$key']");
                            }
                        }else{
                            echo do_shortcode("[AJDT api='$apiName']");
                        }
                    }
                    ?> </div>
            </div>
          </div>
        </div>

<?php
    }

    /**
     * Handels shortcode request by rendering HTML component 
     * @return html component which mounts the data table
     */
    function ajdt_handle_shortcode($atts) { 
        $api = $atts['api'];
        $allapi = ajdt_get_allapi_names();  //AjdtTable\ApiCache()::init()->get_allapi_names(); 
        $colNames = ajdt_get_column_names($api);
        if(empty($colNames))
            return '';

        return "<div id='mount_$api'>
                    <input type='hidden' name='apiNames' value='$allapi'></input>
                    <input type='hidden' name='colNames' value='$colNames'></input>
                    <table id='table_$api' ></table>
                </div>"; 
    }

    /**
     * Gets the columns names from API
     * @return comma seperated string
     */
    function ajdt_get_column_names($api) { 
        $cols = '';
        if(ajdt_is_api_exists($api)){
            $tableColumns = ajdt_get_table_columns(get_option(AJDT_APILISTNAME)[$api]['TableName']);
            $cols ='';
            foreach($tableColumns as $column){
                $key = $column->Field;
                $cols = empty($cols) ? $key: "$cols,$key";
            }
        }

        if(empty($cols)){
            echo ajdt_show_error("The requested API(name: $api) doesn't exists or http method 'GET' might be missing...!. 
                        Please check for valid APIs in 'AJDT Settings' page");
            return $cols;
        }

        return $cols; 
    }

    /**
     * Gets API names
     * @return comma seperated string
     */
    function ajdt_get_allapi_names() { 
        $apiList = get_option(AJDT_APILISTNAME);
        return implode(',', array_keys($apiList));
    }

    /**
     * Checks if the requested API exists or not
     * @return boolean value
     */
    function ajdt_is_api_exists($api) { 
        $apiList = get_option(AJDT_APILISTNAME);
        $isExist = false;
        foreach($apiList as $k => $v){
            if($k==$api){
                $httpMethods = $v['MethodName'];
                if(strpos($httpMethods, 'GET') !== false){
                    $isExist = true;
                    break;
                }
            }
        }
        return $isExist;
    }

    /**
     * Displays error message
     * @return error component
     */
    function ajdt_show_error($msg){
        return "<div id='notice' class='error'><p>$msg</p></div>";
    }