jQuery('#tblUtility').bootstrapTable({
    toggle:"table",
    height:"460",
    ajax:"fetchRecords", 
    buttonsClass: 'success',
    showColumns: true,  showRefresh: true, showFullscreen: true, showToggle: true,
    pagination: true,  search: true, customSort: "customSort",
    columns: [
        { field: 'state', checkbox: true },
        { field: "Key", title: "ApiName" },
        { field: "TableName", title: "Table" },
        { field: "MethodName", title: "Http Method(s)" },
        { field: "PrimaryKey", title: "Primary Key" },
        { 
            field: "Url", title: "Url",
            formatter: function (value, row, index) {
              return "<a href='" + ajdt.rest.root + value +"' target='_blank'>"+ value +'</a>';
            },
        },
        {
            field: 'action',
            title: 'Actions',
            align: 'center',
            formatter: function () {
              return '<a href="javascript:" class="viewData" title="View Data"><i class="fa fa-eye"></i></a> | '+
              '<a href="javascript:" class="delData" title="Remove"><i class="fa fa-trash"></i></a>';
            },
            events: {
              'click .viewData': function (e, value, row) {
                alert(JSON.stringify(row))
              },
              'click .delData': function (e, value, row) {
                var toDelete = confirm("Do you want to delete API: " + row.Key + "?");
                if(toDelete){
                    new AJDT_API('utility')
                        .delete(row.Key)
                        .done(( res, status, xhr ) => {
                            jQuery('#tblUtility').bootstrapTable('refresh');
                        })
                        .fail( response => {
                            alert(JSON.stringify(response));
                        });
                }
              }
            }
          }
    ]         
});

function fetchRecords(params) { 
    tableData = [];
    new AJDT_API('utility')
        .get()
        .done(( resp, status, xhr ) => {
                jQuery.each( resp, function( key, val ) {
                tableData.push({Key: key, TableName: val.TableName, MethodName: val.MethodName, 
                    PrimaryKey: val.PrimaryKey, Url: val.Url});
            });
            params.success(tableData);
        })
        .fail( resp => {
            alert(JSON.stringify(resp));
        });
}

function fetchBsApiList(ctrl){
    var button = jQuery(ctrl);
    var buttonText = button.html();
    button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="false"></span>Loading...');
    jQuery('#tblUtility').bootstrapTable('refresh');
    button.html(buttonText);
}

jQuery('#table-name').change(function() {
    var table = jQuery(this).val();
    new AJDT_API('utility/schema/' + table)
        .get()
        .done(( resp, status, xhr ) => {
            jQuery('#primary-key').val(resp.COLUMN_NAME);    
        })
        .fail( resp => {
            alert(resp.status + ' : ' + resp.responseJSON.message);
            jQuery('#primary-key').val('');
        });
});

jQuery('#api-name').change(function() {
    var apiName = jQuery(this).val();
    if(apiName == 'all')
        apiName = '<API-NAME>';

    jQuery('#lblShortCode').text("[AJDT api='" + apiName + "']");
    jQuery('#lblFromCode').text("echo do_shortcode(\"[AJDT api='" + apiName + "']\");");
});

jQuery( "#btnSaveApi" ).click(function() {
    var modalPopup = jQuery(this).closest('.modal');
    var apiname = modalPopup.find('#api-name').val();
    var primaryKey = modalPopup.find('#primary-key').val();
    var tableName = modalPopup.find('#table-name').val();

    var httpMethodValues = new Array();
    jQuery.each(modalPopup.find("input[name='http-method[]']:checked"), function() {
        httpMethodValues.push(jQuery(this).val());
    });

    if(apiname == ''){
        alert("Api Name is required..!");
        return;
    }

    if(primaryKey == ''){
        alert("The selected table doesn't contain Primary Key..! Please select different table.");
        return;
    }

    if (httpMethodValues === undefined || httpMethodValues.length == 0) {
        alert("HTTP Method is required..!");
        return;
    }

    new AJDT_API('utility')
        .post({
            'name' : apiname,
            'table' : tableName,
            'method' : httpMethodValues.join(),
            'primarykey': primaryKey,
        })
        .done(( res, status, xhr ) => {
            modalPopup.modal('hide');
            jQuery('#tblUtility').bootstrapTable('refresh');
        })
        .fail( response => {
            alert(JSON.stringify(response));
        });
});