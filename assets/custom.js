jQuery(document).ready(function(jQuery) {      
    // Start code delete all pages on click
    jQuery('input[name="ifbdp_submit_delete_all_pages"]').click(function(event) {
        event.preventDefault();
        var delete_all_pages = jQuery('#ifbdp_delete_all_pages').prop('checked');

        if(!delete_all_pages){
            jQuery('#ifbdp_delete_all_pages').css('border-color','red');
            alert('Please select button option!');
            return;
        }

        if(delete_all_pages) {
            if (confirm("Are you sure you want to delete all pages?")) {
                jQuery.ajax({
                    url: ajax_object.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ifbdp_custom_delete_all_pages', 
                        custom_delete_all_pages_nonce: jQuery('#custom_delete_all_pages_nonce').val() 
                    },
                    beforeSend: function()
                    {
                        jQuery(".ifbdp_loader").show();
                    },
                    success: function(response) {
                        if(response == true){
                            alert('Pages deleted successfully.');
                        } else {
                            alert('No pages found to delete.');
                        }
                        //alert('Pages deleted successfully.');
                        location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        alert('Error deleting pages: ' + error);
                    },
                    complete:function(response)
                    {
                        jQuery(".ifbdp_loader").hide();
                    }
                });
            }
        } else {
            alert("Please check the 'Delete all pages' checkbox to delete all media files.");
        }
    });
    // End code delete all pages on click

    // Start code delete posts on click
    jQuery('#ifbdp_delete_post_types_button').on('click', function() {
        var postTypes = jQuery('input[name="post_types[]"]:checked').map(function() {
            return jQuery(this).val();
        }).get();

        if (postTypes.length === 0) {
            alert('Please select one of the options from the post types list!');
            jQuery('input[type="checkbox"]').css('border-color', 'red');
            return;
        } else {
            jQuery('input[type="checkbox"]').css('border-color', '');
        }

        if (confirm("Are you sure you want to delete the selected post types?")) {
            jQuery.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ifbdp_delete_post_types',
                    post_types: postTypes,
                    security: jQuery('#custom_delete_post_types_nonce').val()
                },
                beforeSend: function() {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    if (response.success) {
                        let messages = [];
                        for (let postType in response.data) {
                            messages.push(response.data[postType]);
                        }
                        alert(messages.join('\n'));
                        location.reload();
                    } else {
                        alert('Error deleting post types!');
                    }
                },
                complete: function() {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
    // Start code delete posts on click

    // Start code delete all media on click
    jQuery('#ifbdp_delete_media_button').click(function() {
        var deleteAllMedia = jQuery('#ifbdp_deleteAllMedia').prop('checked');
        if(deleteAllMedia == false){
            alert('Please select option!');
            return;
        }
        if(!deleteAllMedia){
            jQuery('#ifbdp_deleteAllMedia').css('border-color','red');
            return;
        }else {
            jQuery('#ifbdp_deleteAllMedia').css('border-color','');
        }
        if (deleteAllMedia) {
            var confirmDelete = confirm("Are you sure you want to delete all media files?");
            if (confirmDelete) {
                var data = {
                    action: 'ifbdp_delete_all_media',
                    security: jQuery('#delete_media_nonce').val() // Corrected nonce value
                };
                jQuery.post(ajax_object.ajaxurl, data, function(response) {                   
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert(response.data);
                        setTimeout(function() {
                            location.reload();
                        }, 100); // delay in milliseconds
                    }
                });
            }
        } else {
            alert("Please check the 'Delete all media' checkbox to delete all media files.");
        }
    });
    // Start code delete all media on click

    // Start code delete all media on click
    jQuery('#ifbdp_delete_comments_button').click(function() {
        var deleteAllComments = jQuery('#ifbdp_deleteAllComments').prop('checked');
        var test = jQuery('#ifbdp_deleteAllComments').val();        
        if(deleteAllComments == false){
            alert('Please select checkbox option!');
            return;
        }
        if(!deleteAllComments){
            jQuery('#ifbdp_deleteAllComments').css('border-color','red');
            return;
        } else{
            jQuery('#ifbdp_deleteAllComments').css('border-color','');
        }
        if (deleteAllComments) {
            var confirmComments = confirm("Are you sure you want to delete all comments?");
            if (confirmComments) {
                var data = {
                    action: 'ifbdp_delete_all_comments',
                    security: jQuery('#delete_comments_nonce').val()
                };
                jQuery.post(ajax_object.ajaxurl, data, function(response) {
                    if (response) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                });
            }
        } else {
            alert("Please check the 'Delete all comments' checkbox to delete all media files.");
        }
    });
    // Start code delete all media on click

    // Get images from 2 selected dates
    jQuery(function($) {
        // Get today's date
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        // Set the max attribute of "from-date" to today
        var selected_from_date = $('#ifbdp-from-date').attr('max', today);
        $('#ifbdp-to-date').attr('max', today);
        // $('#to-date').attr('min', );
    });

    jQuery("#ifbdp-date-range-form").submit(function(event) {
        event.preventDefault();
        var fromDate = jQuery("#ifbdp-from-date").val();
        if(!fromDate){
            jQuery("#ifbdp-from-date").css('border-color','red');
            return;
        } else {
            jQuery('#ifbdp-from-date').css('border-color', '');
        }
        var toDate = jQuery("#ifbdp-to-date").val();
        if(!toDate){
            jQuery("#ifbdp-to-date").css('border-color','red');
            return;
        }else {
            jQuery("#ifbdp-to-date").css('border-color','');
        }
        jQuery.ajax({
            type: "POST",
            url: ajax_object.ajaxurl, 
            data: {
                action: "ifbdp_get_image_count_by_date",
                from_date: fromDate,
                to_date: toDate,
                security: jQuery('#date_images_nonce_field').val() // Ensure nonce matches the one in PHP

            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                console.log(response);
                var count = parseInt(response);
                if (count > 0) {
                    jQuery("#ifbdp-image-count-result").html("<p>Number of images uploaded between " + fromDate + " and " + toDate + ": " + count + "</p><input type='submit' id='ifbdp-download-images-between-dates' value='Download' class='button-primary'>" + "<input type='submit' value='Show Images' id='ifbdp-show-dates-selected-images' class='button-primary'>" + "<input type='submit' value='Delete Images' id='delete-dates-selected-images' class='button-primary'>");
                } else {
                    jQuery("#ifbdp-image-count-result").html("No images uploaded between " + fromDate + " and " + toDate);
                }
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });

});

jQuery(document).ready(function(jQuery) {
    jQuery('#ifbdp-year-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission        
        var year = jQuery('select[name="ifbdp-year"]').val();
        if(year == '0'){
            jQuery('select[name="ifbdp-year"]').css('border-color','red');
            return;
        } else {
            jQuery('select[name="ifbdp-year"]').css('border-color','');
        }
        jQuery.ajax({
            url: ajax_object.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'ifbdp_get_image_count_by_year',
                year: year,
                security : jQuery('#year_images_nonce_field').val()
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                var count = parseInt(response);
                if(count > 0) {
                    jQuery('#ifbdp-image-count').html('<p>Number of images for ' + year + ': ' + response+'</p>');
                    // Add download button after updating the image count
                    var downloadButton = '<input type="button" id="ifbdp_download_media_by_years" name="ifbdp_download_media_by_years" value="Download" class="button-primary">';
                    var deleteButton = '<input type="submit" value="Delete Images" name="ifbdp_delete_images_by_year" id="ifbdp_delete_images_by_year" class="button-primary">';
                    var showButton = '<input type="submit" value="Show Images" name="ifbdp_show_images_by_year" id="ifbdp_show_images_by_year" class="button-primary">';
                    jQuery('#ifbdp-image-count').append(downloadButton);
                    jQuery('#ifbdp-image-count').append(showButton);
                    jQuery('#ifbdp-image-count').append(deleteButton);                    
                } else {
                    jQuery('#ifbdp-image-count').html('No images found for ' + year);
                }               
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

jQuery(document).ready(function(jQuery) {
    jQuery('#ifbdp-author-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission        
        var authorId = jQuery('#author_id').val();
        if(authorId == '0'){
            jQuery('select[name="author_id"]').css('border-color','red');
            return;
        } else {
            jQuery('select[name="author_id"]').css('border-color','');
        }
        jQuery.ajax({
            url: ajax_object.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'ifbdp_get_images_by_author',
                author_id: authorId,
                security : jQuery('#author_images_nonce_field').val()
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                var count = parseInt(response);
                if(count > 0){
                    jQuery('#ifbdp-author-result').html('<p>Total number of images authored by selected author: '+ response + '</p>' + '<input type="submit" value="Download" name="ifbdp_download_media_by_author" id="ifbdp_download_media_by_author" class="button-primary">'+'<input type="submit" value="Show Images" id="ifbdp_show_media_by_author" class="button-primary">'+'<input type="submit" value="Delete Images" id="ifbdp_delete_media_by_author" class="button-primary">');
                } else {
                    jQuery('#ifbdp-author-result').html('<p>No images found!</p>');
                }
                //jQuery('#author-result').html('<p>Total number of images authored by selected author: '+ response + '</p>');                
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('#ifbdp_search_monthswise_image').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        var ifbdp_month_year = $('select[name="ifbdp_month_year"]').val();
        if(ifbdp_month_year == '0'){
            jQuery('select[name="ifbdp_month_year"]').css('border-color','red');
            return;
        }else {
            jQuery('select[name="ifbdp_month_year"]').css('border-color','');
        }
        $.ajax({
            url: ajax_object.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'ifbdp_get_images_by_month_year',
                ifbdp_month_year: ifbdp_month_year,
                security: jQuery('#monthswise_images_nonce_field').val()
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                var count = parseInt(response);
                if(count > 0){               
                    var month_year = jQuery("select[name='ifbdp_month_year']").val();
                    $('#ifbdp_monthswise_images_display').html('<p>Number of images for ' + month_year + ': ' + response + '</p><input type="submit" value="Download" name="ifbdp_download_media_by_month_year" id="ifbdp_download_media_by_month_year" class="button-primary">'+ '<input type="submit" value="Show Images" name="ifbdp_show_media_by_month_year" id="ifbdp_show_media_by_month_year" class="button-primary">' + '<input type="submit" value="Delete Images" name="ifbdp_delete_media_by_month_year" id="ifbdp_delete_media_by_month_year" class="button-primary">');
                } else {
                    var month_year = jQuery("select[name='ifbdp_month_year']").val();
                    $('#ifbdp_monthswise_images_display').html('No images found for ' + month_year);
                }       
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

// Delete media from wp_options table
jQuery(document).ready(function($) {
    $('#ifbdp_delete_from_wp_options').click(function(e) {
        e.preventDefault();
        var confirmDelete = confirm('Are you sure you want to delete all images?');
        if(confirmDelete){
            var nonce = $(this).data('nonce'); // Get nonce value
            $.ajax({
                url: ajax_object.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ifbdp_delete_all_wp_options_images',
                    nonce: nonce // Include nonce in data
                },
                beforeSend: function()
                {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    if (response.success) {
                        $('.ifbdp_options_table').hide();
                        // Update the HTML with the success message
                        $('#ifbdp-wp_options-result').html('<h4>' + response.data.message + '</h4>');
                    } else {
                        // Handle errors if the response is not successful
                        $('#ifbdp-wp_options-result').html('<h4>' + response.data.message + '</h4>');
                    }
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(xhr, status, error) {
                    // alert('An error occurred while deleting images');
                    //console.error(xhr.responseText);
                    $('#ifbdp-wp_options-result').html('<p>An error occurred: ' + xhr.responseText + '</p>');
                }
            });
        }
    });
});

jQuery(document).ready(function($) {
    // Delete media by author
    jQuery(document).on('click', '#ifbdp_delete_media_by_author', function(e) {
        e.preventDefault();
        var authorID = $('#author_id').val();
        var nonce = $('#delete_media_nonce').val();
        var selectedName = $('#author_id option:selected').text();
        if(authorID == "0"){
            jQuery('select[name="author_id"]').css('border-color','red');
            return;
        }else {
            jQuery('select[name="author_id"]').css('border-color','');
        }
        var confirmDelete = confirm('Are you sure you want to delete all media for this author?');
        if(confirmDelete){
            $.ajax({
                url: ajax_object.ajaxurl, // WordPress AJAX
                type: 'POST',
                data: {
                    'action': 'ifbdp_delete_media_by_author', // Your action name
                    'author_id': authorID, // Selected author ID
                    'nonce': nonce // Security nonce
                },
                beforeSend: function()
                {
                    $(".ifbdp_loader").show();
                },
                success: function(response) {
                    $('#ifbdp-author-result').html('All images deleted for '+selectedName+' author.');
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                }
            });
        }
    });

    // Delete media (by monthwsie) button click event handler
    jQuery(document).on('click', '#ifbdp_delete_media_by_month_year', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var formData = jQuery('#ifbdp_search_monthswise_image').serialize(); // Serialize form data
        var formDataObject = {};
        formData.split('&').forEach(function(keyValue) {
            var pair = keyValue.split('=');
            formDataObject[pair[0]] = decodeURIComponent(pair[1].replace(/\+/g, ' '));
        });
        // Extract the value of the 'month_year' field
        var monthYearValue = formDataObject['ifbdp_month_year'];
        if(monthYearValue == '0'){
            jQuery('select[name="ifbdp_month_year"]').css('border-color','red');
            return;
        } else{
            jQuery('select[name="ifbdp_month_year"]').css('border-color','');
        }
        // Confirm deletion
        var confirmDelete = confirm('Are you sure you want to delete media for the selected month-year?');
        if(confirmDelete){
            // AJAX request to delete media for the selected month-year
            jQuery.ajax({
                url: ajax_object.ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: formData + '&action=ifbdp_delete_media_by_month_year', // Append action parameter
                beforeSend: function()
                {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    // console.log(response.data);
                    // alert(response.data); // Display success message
                    jQuery('#ifbdp_monthswise_images_display').html('All images deleted for ' + monthYearValue);
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    });
});

// Delete images between two selected dates
jQuery(document).on('click', '#delete-dates-selected-images', function(e) {
    e.preventDefault(); // Prevent form submission
    var from_date = jQuery('#ifbdp-from-date').val();
    var to_date = jQuery('#ifbdp-to-date').val();
    if(!from_date){
        jQuery("#ifbdp-from-date").css('border-color','red');
        return;
    } else {
        jQuery("#ifbdp-from-date").css('border-color','');
    }
    if(!to_date){
        jQuery("#ifbdp-to-date").css('border-color','red');
        return;
    }else {
        jQuery("#ifbdp-to-date").css('border-color','');
    }
    if (from_date && to_date) {
        // Confirm deletion
        var confirmDelete = confirm('Are you sure you want to delete images between the selected dates?');
        if (confirmDelete) {
            // Send AJAX request to delete images between selected dates
            jQuery.ajax({
                url: ajax_object.ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'ifbdp_delete_images_between_dates',
                    from_date: from_date,
                    to_date: to_date,
                    nonce: jQuery('#date_images_nonce_field').val() // Nonce
                },
                beforeSend: function()
                {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    // alert(response); // Display success message
                    jQuery("#ifbdp-image-count-result").html("All images deleted between " + from_date + " and " + to_date);
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    } else {
        alert('Please select both from and to dates.');
    }
});

// Delete all unattached images
jQuery(document).ready(function($) {
    $('#ifbdp-delete-all-unattached-images').click(function(e) {
        e.preventDefault(); // Prevent default form submission        
        // Confirm deletion
        var confirmDelete = confirm('Are you sure you want to delete all unattached images?');
        if (confirmDelete) {
            // Send AJAX request to delete unattached images
            $.ajax({
                url: ajax_object.ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'ifbdp_delete_all_unattached_images',
                    nonce: $('#delete_all_unattached_images_nonce_field').val() // Nonce
                },
                beforeSend: function()
                {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    alert(response); // Display success message
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    });
});

// Delete all attached images
jQuery(document).ready(function($) {
    $('#ifbdp-delete-all-attached-images').click(function(e) {
        e.preventDefault(); // Prevent default form submission        
        // Confirm deletion
        var confirmDelete = confirm('Are you sure you want to delete all attached images?');
        if (confirmDelete) {
            // Send AJAX request to delete attached images
            $.ajax({
                url: ajax_object.ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'ifbdp_delete_all_attached_images',
                    nonce: $('#delete_all_attached_images_nonce_field').val() // Nonce
                },
                beforeSend: function()
                {
                    jQuery(".ifbdp_loader").show();
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        alert(response.data); // Display success message
                    } else {
                        alert('Error: ' + response.data); // Display error message
                    }
                },
                complete:function(response)
                {
                    jQuery(".ifbdp_loader").hide();
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                }
            });
        }
    });
});

// Delete all images by year code
jQuery(document).on('click', '#ifbdp_delete_images_by_year', function(e) {
    e.preventDefault(); // Prevent default form submission 
    // Get the selected year from the form
    var selectedYear = jQuery('#ifbdp-year-form select[name="ifbdp-year"]').val();  
    if(selectedYear == '0'){
        jQuery('select[name="ifbdp-year"]').css('border-color','red');
        return;
    }else {
        jQuery('select[name="ifbdp-year"]').css('border-color','');
    } 
    // Confirm deletion
    var confirmDelete = confirm('Are you sure you want to delete media for the selected year?');
    if (confirmDelete) {           
        // Send AJAX request to delete media for the selected year
        jQuery.ajax({
            url: ajax_object.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'ifbdp_delete_media_by_year',
                year: selectedYear,
                nonce: jQuery('#year_images_nonce_field').val() // Nonce
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                // alert(response); // Display success message
                jQuery('#ifbdp-image-count').html('All images deleted for ' + selectedYear);
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    }
});

// Delete all images from wordpress code
jQuery(document).ready(function($) {
    $('#ifbdp-delete-all-images').on('click', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete ALL images?')) {
            return;
        }
        var nonce = $('#delete_all_images_nonce_field').val();
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'ifbdp_delete_all_images',
                nonce: nonce
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                alert(response.data);
                if (response.success) {
                    // Perform actions on success, e.g., reload the page or update UI elements
                    //location.reload();
                }
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('#ifbdp-download-all-images').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: ajax_object.ajaxurl,
            data: {
                action: "ifbdp_download_all_images",
                nonce : jQuery('#delete_all_images_nonce_field').val(),
            },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(blob) {
                // Create a link element, use it to download the blob, and then remove it
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = 'all_images.zip';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('#ifbdp-download-all-attached-images').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: ajax_object.ajaxurl,
            data: {
                action: "ifbdp_download_attached_images",
                security : jQuery('#delete_all_attached_images_nonce_field').val(),
            },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(blob) {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'attached_images.zip';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

jQuery(document).ready(function($) {
    // AJAX call to download unattached images
    $('#ifbdp-download-all-unattached-images').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: ajax_object.ajaxurl,
            data: {
                action: "ifbdp_download_unattached_images",
                security: jQuery('#delete_all_unattached_images_nonce_field').val(),
            },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(blob) {
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'unattached_images.zip';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            }
        });
    });
});

jQuery(document).on('click', '#ifbdp-download-images-between-dates', function(e) {
    e.preventDefault();
    var fromDate = jQuery("#ifbdp-from-date").val();
    var toDate = jQuery("#ifbdp-to-date").val();
    jQuery.ajax({
        type: "POST",
        url: ajax_object.ajaxurl,
        data: {
            action: "ifbdp_download_images_between_dates",
            from_date: fromDate,
            to_date: toDate,
            security: jQuery('#date_images_nonce_field').val(),
        },
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            var url = window.URL.createObjectURL(response);
            var a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'download-images-between-' + fromDate + '-to-' + toDate + '-dates.zip';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        }
    });
});

jQuery(document).on('click', '#ifbdp_download_media_by_month_year', function(e) {
    e.preventDefault();
    var formData = jQuery('#ifbdp_search_monthswise_image').serialize(); // Serialize form data
    var formDataObject = {};
    formData.split('&').forEach(function(keyValue) {
        var pair = keyValue.split('=');
        formDataObject[pair[0]] = decodeURIComponent(pair[1].replace(/\+/g, ' '));
    });
    // Extract the value of the 'month_year' field
    var monthYearValue = formDataObject['ifbdp_month_year'];    
    jQuery.ajax({
        type: "POST",
        url: ajax_object.ajaxurl,
        data: {
            action: "ifbdp_download_images_by_month_year",
            monthYearValue: monthYearValue,
            security: jQuery('#monthswise_images_nonce_field').val(),
        },
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            var url = window.URL.createObjectURL(response);
            var a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'download_images_by_'+monthYearValue+'_month_year.zip';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        }
    });
});

jQuery(document).on('click', '#ifbdp_download_media_by_years', function(e) {
    e.preventDefault();
    var yearValue = jQuery('#ifbdp-year').val(); // Get the selected year
    jQuery.ajax({
        type: "POST",
        url: ajax_object.ajaxurl,
        data: {
            action: "ifbdp_download_media_by_years",
            yearValue: yearValue,
            nonce: jQuery('#year_images_nonce_field').val() // Nonce for security check
        },
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            var url = window.URL.createObjectURL(response);
            var a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'download_images_by_'+yearValue+'_year.zip';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // Handle errors if any
            console.error(xhr.responseText);
        }
    });
});

jQuery(document).on('click', '#ifbdp_download_media_by_author', function(e) {
    e.preventDefault();
    var authorId = jQuery('#author_id').val();
    var authorName = jQuery('#author_id option:selected').text();
    jQuery.ajax({
        type: "POST",
        url: ajax_object.ajaxurl,
        data: {
            action: "ifbdp_download_author_images_callback",
            author_id: authorId, // Corrected parameter name
            security:jQuery('#author_images_nonce_field').val(),
        },
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            var url = window.URL.createObjectURL(response);
            var a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'download_'+authorName+'_author_images.zip';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // Handle errors if any
            console.error(xhr.responseText);
        }
    });
});

jQuery(document).ready(function(jQuery) {
    // Toggle visibility of inner folders when checkbox is clicked
    jQuery('.ifbdp-file-checkbox').change(function() {
        var innerFolders = jQuery(this).closest('label').next('.ifbdp-inner-folders');
        if (jQuery(this).is(':checked')) {
            innerFolders.slideDown();
        } else {
            innerFolders.slideUp();
        }
    });
});

jQuery(document).ready(function($) {
    // When the "Show Images" button is clicked
    $('#ifbdp-show-all-images').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        // AJAX request to fetch image URLs
        $.ajax({
            url: ajax_object.ajaxurl, // Use the global AJAX URL provided by WordPress
            method: 'POST',
            data: {
                action: 'ifbdp_get_image_urls', // Action to call our custom AJAX handler
                security: jQuery('#delete_all_images_nonce_field').val(),
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                // If request is successful, display the data in a table
                var imageList = $('#ifbdp-image-list');
                imageList.empty(); // Clear previous content
                // Generate a table to display the data
                var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
                // Loop through the response data and append each row to the table
                $.each(response.data, function(index, imageUrl) {
                    // Append a row with the index and image URL to the table
                    tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
                });
                // Close the table body and table
                tableHTML += '</tbody></table>';
                // Append the table HTML to the popup container
                imageList.append(tableHTML);
                // Show the popup
                $('#ifbdp-show-all-image-popup').show();
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            },
            error: function(xhr, status, error) {
                // If request fails, show an error message
                //console.error(xhr.responseText);
                alert('Failed to fetch data. Please try again later.');
            }
        });
        $('#ifbdp-show-all-image-popup').on('click', '#ifbdp-all-image-close-popup', function() {
        // Hide the popup when the close button is clicked
            $('#ifbdp-show-all-image-popup').hide();
        });
    });
});

jQuery(document).ready(function($) {
    // When the "Show Images" button is clicked
    $('#ifbdp-show-attached-images').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        // AJAX request to fetch image URLs
        $.ajax({
            url: ajax_object.ajaxurl, // Use the global AJAX URL provided by WordPress
            method: 'POST',
            data: {
                action: 'ifbdp_get_attached_image_urls', // Action to call our custom AJAX handler
                security : jQuery('#delete_all_attached_images_nonce_field').val(),
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                // If request is successful, display the data in a table
                var imageList = $('#ifbdp-attached-image-list');
                imageList.empty(); // Clear previous content
                // Generate a table to display the data
                var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
                // Loop through the response data and append each row to the table
                $.each(response.data, function(index, imageUrl) {
                    // Append a row with the index and image URL to the table
                    tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
                });
                // Close the table body and table
                tableHTML += '</tbody></table>';
                // Append the table HTML to the popup container
                imageList.append(tableHTML);
                // Show the popup
                $('#ifbdp-show-attached-image-popup').show();
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            },
            error: function(xhr, status, error) {
                // If request fails, show an error message
                //console.error(xhr.responseText);
                alert('Failed to fetch data. Please try again later.');
            }
        });
        $('#ifbdp-show-attached-image-popup').on('click', '#ifbdp-attached-image-close-popup', function() {
        // Hide the popup when the close button is clicked
            $('#ifbdp-show-attached-image-popup').hide();
        });
    });
});

jQuery(document).ready(function($) {
    // When the "Show Images" button is clicked
    $('#ifbdp-show-unattached-images').click(function(e) {
        e.preventDefault(); // Prevent the default form submission
        // AJAX request to fetch image URLs
        $.ajax({
            url: ajax_object.ajaxurl, // Use the global AJAX URL provided by WordPress
            method: 'POST',
            data: {
                action: 'ifbdp_get_unattached_image_urls', // Action to call our custom AJAX handler
                security : jQuery('#delete_all_unattached_images_nonce_field').val(),
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
                // If request is successful, display the data in a table
                var imageList = $('#ifbdp-unattached-image-list');
                imageList.empty(); // Clear previous content
                // Generate a table to display the data
                var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
                // Loop through the response data and append each row to the table
                $.each(response.data, function(index, imageUrl) {
                    // Append a row with the index and image URL to the table
                    tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
                });
                // Close the table body and table
                tableHTML += '</tbody></table>';
                // Append the table HTML to the popup container
                imageList.append(tableHTML);
                // Show the popup
                $('#ifbdp-show-unattached-image-popup').show();
            },
            complete:function(response)
            {
                jQuery(".ifbdp_loader").hide();
            },
            error: function(xhr, status, error) {
                // If request fails, show an error message
                //console.error(xhr.responseText);
                alert('Failed to fetch data. Please try again later.');
            }
        });
        $('#ifbdp-show-unattached-image-popup').on('click', '#ifbdp-unattached-image-close-popup', function() {
        // Hide the popup when the close button is clicked
            $('#ifbdp-show-unattached-image-popup').hide();
        });
    });
});

jQuery(document).on('click', '#ifbdp-show-dates-selected-images', function(e) {
    // When the "Show Images" button is clicked  
    e.preventDefault(); // Prevent the default form submission
    // Get the selected dates
    var fromDate = jQuery('#ifbdp-from-date').val();
    var toDate = jQuery('#ifbdp-to-date').val();
    // AJAX request to fetch image URLs between selected dates
    jQuery.ajax({
        url: ajax_object.ajaxurl, // Use the global AJAX URL provided by WordPress
        method: 'POST',
        data: {
            action: 'ifbdp_get_dates_image_urls', // Action to call our custom AJAX handler
            from_date: fromDate,
            to_date: toDate,
            security: jQuery('#date_images_nonce_field').val(),
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            // If request is successful, display the data in a table
            var imageList = jQuery('#ifbdp-dates-image-list');
            imageList.empty(); // Clear previous content
            // Generate a table to display the data
            var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
            // Loop through the response data and append each row to the table
            jQuery.each(response.data, function(index, imageUrl) {
                // Append a row with the index and image URL to the table
                tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="_blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
            });
            // Close the table body and table
            tableHTML += '</tbody></table>';
            // Append the table HTML to the popup container
            imageList.append(tableHTML);
            // Show the popup
            jQuery('#ifbdp-show-dates-image-popup').show();
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // If request fails, show an error message
            //console.error(xhr.responseText);
            alert('Failed to fetch data. Please try again later.');
        }
    });
    // Click event handler for the close button
    jQuery('#ifbdp-show-dates-image-popup').on('click', '#ifbdp-dates-image-close-popup', function() {
        // Hide the popup when the close button is clicked
        jQuery('#ifbdp-show-dates-image-popup').hide();
    });
});

jQuery(document).on('click', '#ifbdp_show_media_by_month_year', function(e) {
    e.preventDefault();
    var formData = jQuery('#ifbdp_search_monthswise_image').serialize(); // Serialize form data
    var formDataObject = {};
    formData.split('&').forEach(function(keyValue) {
        var pair = keyValue.split('=');
        formDataObject[pair[0]] = decodeURIComponent(pair[1].replace(/\+/g, ' '));
    });
    // Extract the value of the 'month_year' field
    var monthYearValue = formDataObject['ifbdp_month_year'];    
    jQuery.ajax({
        type: "POST", // Changed to POST method for consistency
        url: ajax_object.ajaxurl,
        data: {
            action: "ifbdp_get_monthswise_image_urls",
            ifbdp_month_year: monthYearValue, // Corrected key name
            security: jQuery('#monthswise_images_nonce_field').val(),
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            // If request is successful, display the data in a table
            var imageList = jQuery('#ifbdp_month_year-image-list');
            imageList.empty(); // Clear previous content
            // Generate a table to display the data
            var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
            // Loop through the response data and append each row to the table
            jQuery.each(response.data, function(index, imageUrl) {
                // Append a row with the index and image URL to the table
                tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="_blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
            });
            // Close the table body and table
            tableHTML += '</tbody></table>';
            // Append the table HTML to the popup container
            imageList.append(tableHTML);
            // Show the popup
            jQuery('#ifbdp-show-month_year-image-popup').show();
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // If request fails, show an error message
            //console.error(xhr.responseText);
            //alert('Failed to fetch data. Please try again later.');
        }
    });
    // Click event handler for the close button
    jQuery('#ifbdp-show-month_year-image-popup').on('click', '#ifbdp_month_year-image-close-popup', function() {
        // Hide the popup when the close button is clicked
        jQuery('#ifbdp-show-month_year-image-popup').hide();
    });
});

jQuery(document).on('click', '#ifbdp_show_images_by_year', function(e) {
        e.preventDefault(); // Prevent default form submission        
        // Get the selected year from the form
        var selectedYear = jQuery('#ifbdp-year-form select[name="ifbdp-year"]').val();  
        if(selectedYear == '0'){
            jQuery('select[name="ifbdp-year"]').css('border-color','red');
            return;
        } else {
            jQuery('select[name="ifbdp-year"]').css('border-color','');
        }                
        // Send AJAX request to get media URLs for the selected year
        jQuery.ajax({
            url: ajax_object.ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'ifbdp_show_media_urls_by_year',
                year: selectedYear,
                security: jQuery('#year_images_nonce_field').val(),
            },
            beforeSend: function()
            {
                jQuery(".ifbdp_loader").show();
            },
            success: function(response) {
            // If request is successful, display the data in a table
            var imageList = jQuery('#ifbdp-year-image-list');
            imageList.empty(); // Clear previous content
            // Generate a table to display the data
            var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
            // Loop through the response data and append each row to the table
            jQuery.each(response.data, function(index, imageUrl) {
                // Append a row with the index and image URL to the table
                tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="_blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
            });
            // Close the table body and table
            tableHTML += '</tbody></table>';
            // Append the table HTML to the popup container
            imageList.append(tableHTML);
            // Show the popup
            jQuery('#ifbdp-show-year-image-popup').show();
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // If request fails, show an error message
            console.error(xhr.responseText);
            alert('Failed to fetch data. Please try again later.');
        }
    });
    // Click event handler for the close button
    jQuery('#ifbdp-show-year-image-popup').on('click', '#ifbdp-year-image-close-popup', function() {
        // Hide the popup when the close button is clicked
        jQuery('#ifbdp-show-year-image-popup').hide();
    });
});

jQuery(document).on('click', '#ifbdp_show_media_by_author', function(e) {
    e.preventDefault(); // Prevent default form submission 
    // Get the selected author ID from the form
    var authorId = jQuery('#author_id').val();    
    if(authorId == '0'){
        jQuery('select[name="author_id"]').css('border-color','red');
        return;
    } else {
        jQuery('select[name="author_id"]').css('border-color','');
    }            
    // Send AJAX request to get media URLs for the selected author
    jQuery.ajax({
        url: ajax_object.ajaxurl, // WordPress AJAX URL
        type: 'POST',
        data: {
            action: 'ifbdp_show_media_by_author_callback',
            authorId: authorId,
            security: jQuery('#author_images_nonce_field').val(),
        },
        beforeSend: function()
        {
            jQuery(".ifbdp_loader").show();
        },
        success: function(response) {
            // If request is successful, display the data in a table
            var imageList = jQuery('#ifbdp-author-image-list');
            imageList.empty(); // Clear previous content
            // Generate a table to display the data
            var tableHTML = '<table><thead><tr><th>#</th><th>Image URL</th></tr></thead><tbody>';
            // Loop through the response data and append each row to the table
            jQuery.each(response.data, function(index, imageUrl) {
                // Append a row with the index and image URL to the table
                tableHTML += '<tr><td>' + (index + 1) + '</td><td><a target="_blank" href="' + imageUrl + '">' + imageUrl + '</a></td></tr>';
            });
            // Close the table body and table
            tableHTML += '</tbody></table>';
            // Append the table HTML to the popup container
            imageList.append(tableHTML);
            // Show the popup
            jQuery('#ifbdp-show-author-image-popup').show();
        },
        complete:function(response)
        {
            jQuery(".ifbdp_loader").hide();
        },
        error: function(xhr, status, error) {
            // If request fails, show an error message
            console.error(xhr.responseText);
            //alert('Failed to fetch data. Please try again later.');
        }
    });    
    // Click event handler for the close button
    jQuery('#ifbdp-show-author-image-popup').on('click', '#ifbdp-author-image-close-popup', function() {
        // Hide the popup when the close button is clicked
        jQuery('#ifbdp-show-author-image-popup').hide();
    });
});

jQuery(document).on('click', '#ifbdp_chk_delete_btn', function(e) {
    e.preventDefault(); // Prevent form submission
    // Array to store the selected file values
    var selectedFiles = [];
    // Loop through each parent checkbox
    jQuery('.ifbdp-file-checkbox').each(function() {
        var parentCheckbox = jQuery(this);
        // Check if the parent checkbox is checked
        if (parentCheckbox.prop('checked')) {
            // Check if there are any checkboxes inside the corresponding .inner-folders div
            var innerChecked = parentCheckbox.closest('label').next('.ifbdp-inner-folders').find('input[type="checkbox"]:checked');
            if (innerChecked.length > 0) {
                // If there are checked checkboxes inside the .inner-folders div, add their values to the selectedFiles array
                innerChecked.each(function() {
                    var value = jQuery(this).val();
                    if (!selectedFiles.includes(value)) {
                        selectedFiles.push(value);
                    }
                });
            } else {
                // If no checkboxes inside the .inner-folders div are checked, add the value of the parent checkbox to the selectedFiles array
                var value = parentCheckbox.val();
                if (!selectedFiles.includes(value)) {
                    selectedFiles.push(value);
                }
            }
        } 
    });
    // Output the selected file values to the console for testing
    if (selectedFiles.length > 0) {
        if (confirm("Are you sure you want to delete selected folder and its images?")) {
                jQuery.ajax({
                    url: ajax_object.ajaxurl, // WordPress AJAX handler URL
                    type: 'POST',
                    data: {
                        action: 'ifbdp_delete_selected_files',
                        files: selectedFiles,
                        security: jQuery('#ifbdp_folders_nonce').val(),
                    },
                    beforeSend: function()
                    {
                        jQuery(".ifbdp_loader").show();
                    },
                    success: function(response) {
                        // Handle success response from server
                        console.log(response);
                        if(response.success) {
                            jQuery('#ifbdp-uploads-form .ifbdp-sucess_msg').css('display','block');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 3 seconds
                            }, 3000); // 3000 milliseconds = 3 seconds
                        } else {
                            alert('Selected folders data not deleted');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 3 seconds
                            }, 3000); // 3000 milliseconds = 3 seconds
                            //alert(response.data); // Show the error message from the response
                        }
                        //alert('Selected files deleted successfully.');
                    },
                    complete:function(response)
                    {
                        jQuery(".ifbdp_loader").hide();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response from server
                        console.error(xhr.responseText);
                        jQuery('#ifbdp-uploads-form .ifbdp-error_msg').css('display','block');
                        //alert('Error deleting files. Please try again later.');
                    }
                });
            } else {
                //alert('Please select checkbox');
                jQuery('input.ifbdp-file-checkbox').css('border','1px solid red');
            }
    } else {
        alert('Please select one of the option.');
    }
});