jQuery( document ).ready(function ($) {
  $("#ws-ls-tabs").zozoTabs({
      rounded: false,
       multiline: true,
       theme: "silver",
       size: "medium",
       responsive: true,
       animation: {
           effects: "slideH",
           easing: "easeInOutCirc",
           type: "jquery"
       }
  });
  // Disable all inputs for Pro rows
  $(".ws-ls-disabled input").prop('disabled', true);
  $(".ws-ls-disabled select").prop('disabled', true);

  ws_table = $('.ws-ls-advanced-data-table').DataTable( {
          responsive: true,
          // / "order": [[ 3, "desc" ]]
          "lengthMenu": [[10, 25, 50, 100, 250, 500 -1], [10, 25, 50, 100, 250, 500, "All"]],
          "processing": true,
          "serverSide": true,
          "ajax": {
            "url" : ws_ls_user_data['ajax-url'],
            "data": {
                "action": "ws_ls_user_data"
            }
          },
          "order": [[ 1, "desc" ]],
          "columns": [
              { "name": "user_nicename" },
              { "name": "weight_date", "bSearchable": false  },
              { "name" : "weight_weight", "bSearchable": false },
              { "bSearchable": true, "bSortable": false },
              { "bSearchable": false, "bSortable": false }
          ],
          rowId: 'row-id'

  });

  $('.ws-ls-advanced-data-table tbody').on( 'click', 'img.ws-ls-admin-delete-weight', function () {

    var row_id = $(this).data('row-id');
    var tr = $(this).closest('tr');
    var table = $(this).closest('table');

    $post_data = {};

    $post_data['action'] = 'ws_ls_admin_delete_entry';
    $post_data['security'] = ws_ls_user_data['security'];
    $post_data['table-row-id'] = tr.attr('id');
    $post_data['table-id'] = table.attr('id');
    $post_data['row-id'] = $(this).data('row-id');
    $post_data['user-id'] = $(this).data('user-id');

    ws_ls_post_data($post_data, ws_ls_delete_row_callback);
    //
    //
    // ws_table
    //     .row( $(this).parents('tr') )
    //     .remove()
    //     .draw();

  });

  function ws_ls_post_data(data, callback)
  {
    var ajaxurl = ws_ls_user_data['ajax-url'];

    jQuery.post(ajaxurl, data, function(response) {

      var response = JSON.parse(response);
      callback(data, response);
    });
  }

    // $('.ws-ls-delete-row').click(function( event ) {
    //     event.preventDefault();
    //
    //     // TODO: Prompt confirm before delete
    //
    //     var tr = $(this).closest('tr');
    //     var table = $(this).closest('table');
    //
    //     $post_data = {};
    //
    //     $post_data['action'] = 'ws_ls_delete_entry';
    //     $post_data['security'] = ws_ls_config['ajax-security-nonce'];
    //     $post_data['user-id'] = ws_ls_config['user-id'];
    //     $post_data['table-row-id'] = tr.attr('id');
    //     $post_data['table-id'] = table.attr('id');
    //     $post_data['row-id'] = $(this).data('row-id');
    //
    //     ws_ls_post_data($post_data, ws_ls_delete_row_callback);
    // });
    //
    function ws_ls_delete_row_callback(data, response)
    { console.log(response);
      if (response == 1) {

        var table = $('#' + data['table-id']).DataTable();
        var tr = $('#' + data['table-row-id']);

        table
            .row(tr)
            .remove()
            .draw();

      }
      else
      {
        console.log('Error deleting entry :(', data, response);
      }
    }



});
