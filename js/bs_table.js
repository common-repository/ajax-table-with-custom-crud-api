jQuery( document ).ready(function() {

  jQuery("div[id^='mount_']").each(function( i, elem ) {
    var apis = jQuery(elem).find('input').val().split(',');
    var defColumns = [];
    jQuery(elem).find('input[name=colNames]').val().split(',').forEach(function(col){
      defColumns.push({ field: col, title: col.toUpperCase() });
    });
    defColumns.push({ field: 'operate', title: 'Action',
            formatter: function () {
              return '<a href="javascript:" class="viewData" title="View Data"><i class="fa fa-eye"></i></a>';
            },
            events: {
              'click .viewData': function (e, value, row) {
                alert(JSON.stringify(row))
              }
            }
          });

    jQuery(elem).find('table').bootstrapTable({
      toggle:"table",
      height:"460",
      ajax:"fetchBsRecords",
      buttonsClass: 'warning',
      showColumns: true, pagination: true, search: true,
      columns: defColumns         
    });
  });
}); //$( document ).ready()

function fetchBsRecords(params) {
      var apiname = this.$el.attr('id').split('_')[1]; 
      new AJDT_API(apiname)
          .get()
          .done(( resp, status, xhr ) => {
              params.success(resp);
          })
          .fail( resp => {
              alert(JSON.stringify(resp));
          });
  }