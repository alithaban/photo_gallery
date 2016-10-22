/**
 * Created by root on 19/10/16.
 */
var type = '';

$.ajaxSetup( { cache : false } );

$(document).ready(function() {
    var categoryTable = $('#categories-list').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        "searching"    : false,
        "lengthChange": false,
        "ajax": "/admin/get-categories",
        "createdRow": function ( row, data, index ) {
            $('td', row).eq(4).html('<a title="Edit Category" href="#" class="fa fa-pencil-square-o modal-link" aria-hidden="true" data-category-name="'+$('td', row).eq(1).html()+'" data-category-id="'+$('td', row).eq(0).html()+'"></a>&nbsp;&nbsp;<a title="Delete Category" href="#" data-category-id="'+$('td', row).eq(0).html()+'" class="fa fa-times delete-category" aria-hidden="true"></a>');

        }
    });
    
    $('body').on('click','a.modal-link',function (e) {
        e.preventDefault();

        // populate the id and name inputs.
        $('#category-updated-name').val($(this).data('category-name'));
        $('#category-id').val($(this).data('category-id'));

        $('#update-category-modal').modal('show');
    })

    $('body').on('click','a.delete-category',function (e) {
        e.preventDefault();

        // populate the id and name inputs.
        $('#confirm-delete-category').attr('data-category-id',$(this).data('category-id'));

        $('#confirmDelete').modal('show');
    })

    $('#confirm-delete-category').click(function (e) {
        e.preventDefault();
        id = $(this).attr('data-category-id');


        console.log(id);
        $.ajax({
            type:"POST",
            cache:false,
            data:{category_id:id},
            url:"/admin/delete-category?nocache="+Math.random(),
            success: function (data) {
                // Redraw the table

                if(data.success == 'true')
                {
                    categoryTable.ajax.reload();

                    // SHow a message regarding adding the new category
                    showMessage(data.message);

                    // Clear the id in the modal
                    $(this).attr('data_category-id','');

                    // Close the modal

                    $('#confirmDelete').modal('toggle');


                }
                else{
                    showMessage(data.message,true);
                }
            }
        });
    })
    
    $('#add-category').click(function (event) {
        event.preventDefault();

        $.ajax({
            type:"POST",
            cache:false,
            url:"/admin/add-category",
            data:{category_name:$('#category-name').val()},
            success: function (data) {
               // Redraw the table

                if(data.success == 'true')
                {
                    categoryTable.ajax.reload();

                    // SHow a message regarding adding the new category
                    showMessage(data.message);

                    // Clear the text field
                    $('#category-name').val('');

                }
                else{
                    showMessage(data.message,true);
                }
            }
        });
    })

    $('#update-category').click(function (event) {
        event.preventDefault();

        $.ajax({
            type:"POST",
            cache:false,
            url:"/admin/update-category",
            data:{category_name:$('#category-updated-name').val(),category_id:$('#category-id').val()},
            success: function (data) {
                // Redraw the table

                if(data.success == 'true')
                {
                    categoryTable.ajax.reload();

                    // SHow a message regarding adding the new category
                    showMessage(data.message);

                    // Clear the text field
                    $('#category-updated-name').val('');
                    $('#category-id').val('');

                    // Close the modal
                    $('#update-category-modal').modal('toggle');

                }
                else{
                    showMessage(data.message,true);
                }
            }
        });
    })
} );

function showMessage(message,error){
    if(error === undefined)
        error = false;

    if(error){
        type = 'danger';
    }
    else {
        type = 'success';
    }
    Msg.show(message, type, '5000');
}