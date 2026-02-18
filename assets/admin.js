/* =====================================================
   Programmatic SEO - Admin Scripts
   ===================================================== */

jQuery(document).ready(function($) {
    
    // Auto-generate slug from city name
    $('#city_name').on('blur', function() {
        var name = $(this).val();
        var slug = $('#city_slug').val();
        
        if (!slug && name) {
            // Simple slug generation
            var generated = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            $('#city_slug').val(generated);
        }
    });
    
    // Confirm delete actions
    $('.pseo-delete-btn').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
    
    // AJAX: Load cities for business form
    if ($('#city_id').length && $('#city_id option').length <= 1) {
        $.ajax({
            url: pseo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'pseo_get_cities',
                nonce: pseo_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var select = $('#city_id');
                    select.empty();
                    select.append('<option value="">Select City</option>');
                    
                    $.each(response.data, function(index, city) {
                        select.append('<option value="' + city.id + '">' + city.city_name + '</option>');
                    });
                }
            }
        });
    }
    
    // Filter form enhancement
    $('.pseo-filter-form select').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Rating input validation
    $('input[name="rating"]').on('input', function() {
        var val = parseFloat($(this).val());
        if (val < 0) $(this).val(0);
        if (val > 5) $(this).val(5);
    });
    
    // Phone number formatting helper
    $('input[name="whatsapp"]').on('blur', function() {
        var val = $(this).val().trim();
        // Remove any non-numeric characters
        val = val.replace(/\D/g, '');
        // Ensure it starts with 62
        if (val && !val.startsWith('62')) {
            if (val.startsWith('0')) {
                val = '62' + val.substring(1);
            } else {
                val = '62' + val;
            }
        }
        $(this).val(val);
    });
    
    // Check for duplicate posts when generating
    $('#pseo-check-duplicate').on('click', function(e) {
        e.preventDefault();
        
        var citySlug = $('#city_slug').val();
        
        if (!citySlug) {
            alert('Please enter a city slug first');
            return;
        }
        
        $.ajax({
            url: pseo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'pseo_check_duplicate_post',
                nonce: pseo_ajax.nonce,
                city_slug: citySlug
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.exists) {
                        var message = 'Post already exists!\n\n';
                        message += 'Title: ' + response.data.post_title + '\n';
                        message += 'Status: ' + response.data.post_status + '\n\n';
                        message += 'What would you like to do?';
                        
                        if (confirm(message)) {
                            window.open(response.data.edit_url, '_blank');
                        }
                    } else {
                        alert('✓ No duplicate found. You can safely create this post.');
                    }
                }
            }
        });
    });
    
    // CSV import validation
    $('input[name="csv_file"]').on('change', function() {
        var file = this.files[0];
        if (file && file.size > 2 * 1024 * 1024) {
            alert('File size too large. Maximum 2MB allowed.');
            this.value = '';
        }
    });
    
});
