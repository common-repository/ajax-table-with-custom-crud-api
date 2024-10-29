<?php

function ajdt_list_api() { ?>
  <section class="ajdt-message-wrapper">
    <div class="ajdt-inbox-message">
        <div >
          <h5 class="ajdt-message-title">Ajax Table creates REST API for the selected database tables. 
            It enables user to perform CRUD operations in the table using generated REST API</h5>
          <div class="woocommerce-inbox-message__text">
          </div>
        </div>
    </div>
  </section>
<div id='toolbar'>
  <div class="form-inline" role="form">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#saveApiModal" id="btnCreate"><i class='fa fa-plus-square'></i> Create</button>
    <button type="button" class="btn btn-info" onclick='fetchBsApiList(this)'><i class="fa fa-retweet"></i> Refresh</button>
  </div>
  <table id='tblUtility' ></table>
</div>

<!-- Modal -->
<div class="modal fade" id="saveApiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New API</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="api-name" class="col-form-label">Api Name:</label>
                <input type="text" class="form-control" id="api-name" placeholder="Enter API name" required="" oninvalid="this.setCustomValidity('Please Enter valid API Name')">
              </div>
            </div>
            <div class="col-md-6 ml-auto">
              <div class="form-group">
                <label for="table-name" class="col-form-label">Table Name:</label>
                <select class="form-control" id="table-name">
                <?php
                  $tables = ajdt_get_tables();
                  foreach ($tables as $index => $tableSet) {
                      foreach ($tableSet as $table) {
                          echo "<option value='$table'>$table</option>";
                      }
                  }
                  ?>
                </select>
              </div>      
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-form-label">Http Method:</label>
                <br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="http-method[]" value="GET">
                  <label class="form-check-label" for="inlineCheckbox1">GET</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="http-method[]" value="POST">
                  <label class="form-check-label" for="inlineCheckbox2">POST</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="http-method[]" value="DELETE">
                  <label class="form-check-label" for="inlineCheckbox2">DELETE</label>
                </div>
              </div>            
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="primary-key" class="col-form-label">Primary Key:</label>
                <input type="text" class="form-control" id="primary-key" disabled> </input>
              </div>
            </div>
          </div>          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id="btnSaveApi" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<?php }
function ajdt_list_api1() {  ?>
 <div class="wrap">
  <?php 
  // print "Checking SCRIPT_DEBUG: ".defined( 'SCRIPT_DEBUG' ).", Value: ".SCRIPT_DEBUG;
  // print "Checking AJDT_INCLUDES: ".defined( 'AJDT_INCLUDES' ).", Value: ".AJDT_INCLUDES;
  // echo do_shortcode("[AJDT api='sha1']");
   ?>
    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>
    <div  class="postbox"> 
      <div class="rowLayout">
        <p> Display the details about APIs </p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#saveApiModal" id="btnCreate">Create</button>
        <button type="button" class="btn btn-info" onclick='fetchApiList(this)'>Refresh</button>
        <div class="form-horizontal">
            <div class="table-wrapper">
              <table id="tbApiList" class="widefat fixed striped">
                <thead><tr><th>ShortCode/Name</th><th>Method</th><th>Table</th>
                <th>Columns</th><th>URL</th><th>Action</th></tr></thead>
                <tbody>
                <?php //data-backdrop="" 
                foreach (get_option(AJDT_APILISTNAME) as $key => $Api) {
                    $method = $Api['MethodName'];
                    $table = $Api['TableName'];
                    $cols = $Api['SelectedColumn'];
                    $url = $Api['Url'];
                    $fullURL = get_site_url().'/wp-json/'.$url;
                    echo "<tr><td class='apiname'>[$key]</td>
                    <td class='method'>$method</td>
                    <td class='table'>$table</td>
                    <td class='cols'>$cols</td>
                    <td class='url'><a class='fas fa-user-edit' href='$fullURL' target='_blank'>$url</a></td>
                    <td><button class='btn btn-warning' style='font-size: 12px;' onclick='deleteApi(this)'>Delete</button></td></tr>";
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>
</div>
<?php 
ajdt_list_api1();
} ?>





