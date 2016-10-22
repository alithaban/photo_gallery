/**
 * Created by root on 21/10/16.
 */
/*
 private function getURLS($photos){
 // Get the URLS from the collection
 $photosURL = '';

 foreach ($photos['photo'] as $photo){
 $photosURL[$photo['id']] = 'https://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_q.jpg';
 }

 return $photosURL;
 }
 */

function getURL(){
    return null;
}

$(document).ready(function() {
    $('a.menu--link').click(function (event) {
        // Get the category name
        $category = $(this).data('category-name');
        $('#photos').html('');
        event.preventDefault();

        $.ajax({
            type:"POST",
            cache:false,
            url:"/get-photos",
            data:{category_name: $category},
            success: function (data) {
                $.each(data, function(k,v) {

                    $('#photos').append('<a href="#" class="photo-item" data-photo-id="'+k+'"><img class="col-xs-6 col-sm-4 col-md-3 col-lg-2" img src="'+v+'"></a>');
                    $('.title .panel-body h2').html($category + ' Photo Gallery');
                });

                $('.gallery').show();
            }
        });
    })

    // Popup section
    $('#photo-popup').popup({
        color: 'white',
        opacity: 1,
        transition: '0.3s',
        scrolllock: true
    });

    // Get the photo description
    $('body').on('click','.photo-item', function (event) {
        event.preventDefault();

        // Get the photo id
        photoId = $(this).data('photo-id');

        $.ajax({
            type:"POST",
            cache:false,
            url:"/get-photo",
            data:{photo_id: photoId},
            success: function (data) {
                console.log(data.photo);

                // Set the title
                $('#photo-popup #title h3').html(data.photo.title._content);

                // Set the description
                $('#photo-popup #description').html(data.photo.description._content);

                // Set the image
                $('#photo-popup #photo-image img').attr('src','https://farm'+data.photo.farm+'.staticflickr.com/'+data.photo.server+'/'+data.photo.id+'_'+data.photo.secret+'_z.jpg');

                $('#photo-popup').popup('toggle',{
                    background:true
                });
            }
        });
    })

    $('.close-popup').click(function () {
        $('#photo-popup').popup('toggle');

        // Clear the content of the pop up
        // Set the title
        $('#photo-popup #title h3').html('');

        // Set the description
        $('#photo-popup #description').html('');

        // Set the image
        $('#photo-popup #photo-image img').attr('src','');

    })


})