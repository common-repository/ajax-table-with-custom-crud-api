jQuery(document).ready(function(){
    jQuery('#saveApiModal').on('show.bs.modal', function (event) {
        var button = jQuery(event.relatedTarget) // Button that triggered the modal
        var _row = button.parents("tr");
        var modal = jQuery(this)
        //modal.find('.modal-title').text('New message to ' + recipient)
        modal.find('.modal-body #api-name').val(_row.find('.apiname').text());
        modal.find('.modal-body #http-method').val(_row.find('.method').text());
        modal.find('.modal-body #table-name').val(_row.find('.table').text());
        //modal.find('.modal-body #cols').val(_row.find('.cols').text());
    });
    
    jQuery( "#btnSaveApi1" ).click(function() {
        var target = jQuery(this).closest('.modal');
        var apiname = target.find('#api-name').val();
        var httpMethod = target.find('#http-method').val();
        var tableName = target.find('#table-name').val();
        //var cols = target.find('#cols').val();
        //alert( "Modal submitted with text: " + apiname + httpMethod);

        jQuery.ajax({
            type: "POST",
            url: window.location.href.split("wp-admin")[0] + 'wp-json/ajdt/v1/utility',
            data: {
                'name' : apiname,
                'table' : tableName,
                'method' : httpMethod,
            },
            success: function(data, textStatus, jqXHR) {
                //alert('Saved successfully');
                target.modal('hide');
                fetchApiList(jQuery("#btnCreate"));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Failed to save API '+jqXHR+textStatus+errorThrown);
            }
        });
    });
    // jQuery('#saveApiModal').on('hide.bs.modal', function (event) {
    //     fetchApiList();
    // });

}); //document).ready
    showSpinner = false;
    function deleteApi(ctrl){
        var apiname = jQuery(ctrl).closest('tr').find('.apiname').text().replace('[','').replace(']','');
        jQuery.ajax({
            type: "DELETE",
            url: window.location.href.split("wp-admin")[0] + 'wp-json/ajdt/v1/utility/' + apiname,
            success: function(data, textStatus, jqXHR) {
                fetchApiList(ctrl);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Failed to delete API '+jqXHR+textStatus+errorThrown);
            }
        });
    }

    function fetchApiList(ctrl){
        var button = jQuery(ctrl);
        var buttonText = button.text();
        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="false"></span>Loading...');
        var baseUrl = ajdt.rest.root + 'ajdt/v1/utility';
        jQuery.ajax({
            type: "GET",
            url: baseUrl,
            // beforeSend: function ( xhr ) {
            //     xhr.setRequestHeader('X-WP-Nonce', ajdt.rest.nonce);
            // },
            success: function(response, textStatus, jqXHR) {
                jQuery("#tbApiList tbody").empty();
                jQuery.each(response, function(key, api){
                    var editButton = "<button type='button' style='font-size: 12px;' class='btn btn-success' data-toggle='modal' data-target='#saveApiModal' >Edit</button>";
                    var delButton = "<button class='btn btn-warning' style='font-size: 12px;' onclick='deleteApi(this)'>Delete</button>";
                    var markup = "<tr><td class='apiname'>[" + key + "]</td><td class='method'>" 
                                    + api.MethodName + "</td><td class='table'>" 
                                    + api.TableName + "</td><td class='cols'>" 
                                    + api.SelectedColumn + "</td><td class='url'><a class='fas fa-user-edit' href='"
                                    +  window.location.href.split("wp-admin")[0] + 'wp-json/' + api.Url +"' target='_blank'>" 
                                    + api.Url + "</a></td><td>" 
                                    + delButton + "</td></tr>";
                    jQuery("#tbApiList tbody").append(markup);
                    button.html(buttonText);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Failed to Fetch APIs '+jqXHR +' | '+ textStatus +' | '+ errorThrown +' | '+ baseUrl);
            }
        });

        //  wepos.api.get( wepos.rest.root + wepos.rest.posversion + '/products?status=publish&per_page=30&page=' + this.page )
        //         .done( ( response, status, xhr ) => {
        //             this.products = this.products.concat( response );
        //             this.page += 1;
        //             this.totalPages = parseInt( xhr.getResponseHeader('X-WP-TotalPages') );
        //             this.productLoading = false;
        //         }).then( ( response, status, xhr ) => {
        //             this.fetchProducts();
        //         });
    }