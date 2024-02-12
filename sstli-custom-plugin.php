<?php
/*
Plugin Name: SStli Custom Plugin
Description: Custom plugin to create a Edits to sstli website.
Version: 2.0
Author: SwarmsAgency
Author URI: https://swarmsagency.com
Text Domain: sstlicustom
*/



// Enqueue CSS file
function sstli_custom_enqueue_styles() {
    wp_enqueue_style('sstli-custom-style', plugin_dir_url(__FILE__) . 'sstli-custom-style.css');
}
add_action('wp_enqueue_scripts', 'sstli_custom_enqueue_styles');

// Enqueue JS file
function sstli_custom_enqueue_scripts() {
    wp_enqueue_script('sstli-custom-script', plugin_dir_url(__FILE__) . 'sstli-custom-script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'sstli_custom_enqueue_scripts');



/*******************************
 * *****************************
 *  Register Custom Post Types
 * *****************************
 *******************************/

// Register FAQ Custom Post Type
function sstli_custom_register_faq_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'FAQs',
            'singular_name' => 'FAQ',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'faqs'),
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('faq', $args);
}
add_action('init', 'sstli_custom_register_faq_post_type');

// Register Training Unit Custom Post Type
function sstli_custom_register_training_unit_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Training Units',
            'singular_name' => 'Training Unit',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'training-units'),
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('training_unit', $args);
}
add_action('init', 'sstli_custom_register_training_unit_post_type');

// Register SStli Slider Custom Post Type
function sstli_custom_register_slider_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'SStli Slider',
            'singular_name' => 'Slider',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'sstli-slider'),
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('sstli_slider', $args);
}
add_action('init', 'sstli_custom_register_slider_post_type');



// Register Branches Custom Post Type
function sstli_custom_register_branches_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Branches',
            'singular_name' => 'Branch',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'branches'),
        'supports' => array('title', 'thumbnail', 'custom-fields'), // Add support for custom fields
    );
    register_post_type('branches', $args);
}
add_action('init', 'sstli_custom_register_branches_post_type');

// Include custom template file for 'Branches' archive
function sstli_custom_include_branches_archive_template($template) {
    if (is_post_type_archive('branches') && !is_admin()) {
        $custom_template = plugin_dir_path(__FILE__) . 'archive-branches.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'sstli_custom_include_branches_archive_template');



// Register Testimonial Custom Post Type
function sstli_custom_register_testimonial_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'testimonials'),
        'supports' => array('title', 'custom-fields'), // Add support for custom fields
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'sstli_custom_register_testimonial_post_type');


// Register Notifications Custom Post Type
function sstli_custom_register_notifications_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Notifications',
            'singular_name' => 'Notification',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'notifications'),
        'supports' => array('title', 'custom-fields'),
    );
    register_post_type('notifications', $args);
}
add_action('init', 'sstli_custom_register_notifications_post_type');


// Register Social Links Custom Post Type
function sstli_custom_register_social_links_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Social Links',
            'singular_name' => 'Social Link',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'social-links'),
        'supports' => array('title', 'custom-fields'),
    );
    register_post_type('social_links', $args);
}
add_action('init', 'sstli_custom_register_social_links_post_type');


// Register About Us Custom Post Type
function sstli_custom_register_about_us_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'About Us',
            'singular_name' => 'About Us',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'about-us'),
        'supports' => array('title', 'custom-fields'),
    );
    register_post_type('about_us', $args);
}
add_action('init', 'sstli_custom_register_about_us_post_type');



// Register REST API Endpoint for User Profile Image Upload
function sstli_custom_upload_profile_image($data) {
    $user_id = $data['user_id']; // Assume user ID is passed in the request data

    if (empty($user_id)) {
        return new WP_Error('missing_user_id', 'User ID is required.', array('status' => 400));
    }

    if (!isset($_FILES['file'])) {
        return new WP_Error('missing_file', 'Image file is required.', array('status' => 400));
    }

    $file = $_FILES['file'];

    $attachment_id = media_handle_upload('file', 0); // 0 means it will be attached to no particular post

    if (is_wp_error($attachment_id)) {
        return new WP_Error('upload_failed', $attachment_id->get_error_message(), array('status' => 500));
    }

    // Update user profile with the uploaded image
    update_user_meta($user_id, 'avatar_media_id', $attachment_id);

    return array('status' => 'success', 'message' => 'Profile image uploaded successfully.');
}



/*******************************
 * *****************************
 *  Register REST API Endpoints
 * *****************************
 *******************************/


function sstli_custom_register_rest_routes() {
    // Register REST API Endpoint for FAQs
    register_rest_route('sstli-custom/v1', '/faqs', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_faqs',
        'permission_callback' => '__return_true', 
    ));

    // Register REST API Endpoint for Training Units
    register_rest_route('sstli-custom/v1', '/training-units', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_training_units',
        'permission_callback' => '__return_true',
    ));

    // Add a new route for fetching a single Training Unit by ID
    register_rest_route('sstli-custom/v1', '/training-units/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_training_unit_by_id',
        'permission_callback' => '__return_true',
    ));

    // Register REST API Endpoint for SStli Slider
    register_rest_route('sstli-custom/v1', '/slider', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_slider',
        'permission_callback' => '__return_true',
    ));

    // Register REST API Endpoint for Branches
    register_rest_route('sstli-custom/v1', '/branches', array(
        'methods'  => 'GET',
        'callback' => 'sstli_get_branches',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('sstli-custom/v1', '/testimonials', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_testimonials',
        'permission_callback' => '__return_true',
    ));

    // Register REST API Endpoint for Notifications
    register_rest_route('sstli-custom/v1', '/notifications', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_notifications',
        'permission_callback' => '__return_true',
    ));

    // Register REST API Endpoint for Social Links
    register_rest_route('sstli-custom/v1', '/social-links', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_social_links',
        'permission_callback' => '__return_true',
    ));
    // Add a new route for fetching About Us data
    register_rest_route('sstli-custom/v1', '/about-us', array(
        'methods' => 'GET',
        'callback' => 'sstli_custom_get_about_us',
        'permission_callback' => '__return_true',
    ));

    // Use the init hook to register the user profile image upload endpoint
    register_rest_route('sstli-custom/v1', '/upload-profile-image/', array(
        'methods'  => 'POST',
        'callback' => 'sstli_custom_upload_profile_image',
        'permission_callback' => function () {
            return current_user_can('edit_users'); // Adjust the capability as needed
        },
    ));

    // Add a custom endpoint for checking username and email availability
    register_rest_route('sstli-custom/v1', '/check-user/', array(
        'methods'  => 'POST',
        'callback' => 'check_user_availability',
    ));
}

// Use the init hook to register REST routes
add_action('rest_api_init', 'sstli_custom_register_rest_routes');
 





/*******************************
 * *****************************
 *  Callback functions
 * *****************************
 *******************************/

// Callback function to get FAQ data
function sstli_custom_get_faqs() {
    $faqs = get_posts(array(
        'post_type' => 'faq',
        'posts_per_page' => -1,
    ));

    $formatted_faqs = array();

    foreach ($faqs as $faq) {
        $formatted_faqs[] = array(
            'title' => get_the_title($faq),
            'content' => apply_filters('the_content', $faq->post_content),
        );
    }

    return rest_ensure_response($formatted_faqs);
}

// Callback function to get Training Unit data
function sstli_custom_get_training_units() {
    $training_units = get_posts(array(
        'post_type' => 'training_unit',
        'posts_per_page' => -1,
    ));

    $formatted_training_units = array();

    foreach ($training_units as $training_unit) {
        $formatted_training_units[] = array(
            'id' => $training_unit->ID, // Add the ID field
            'title' => get_the_title($training_unit),
            'content' => apply_filters('the_content', $training_unit->post_content),
        );
    }

    return rest_ensure_response($formatted_training_units);
}

// Callback function to get a single Training Unit by ID
function sstli_custom_get_training_unit_by_id($data) {
    $training_unit_id = $data['id'];

    $training_unit = get_post($training_unit_id);

    if (!$training_unit || $training_unit->post_type !== 'training_unit') {
        return new WP_Error('training_unit_not_found', 'Training Unit not found', array('status' => 404));
    }

    $formatted_training_unit = array(
        'id' => $training_unit->ID,
        'title' => get_the_title($training_unit),
        'content' => apply_filters('the_content', $training_unit->post_content),
    );

    return rest_ensure_response($formatted_training_unit);
}


// Callback function to get Slider data
function sstli_custom_get_slider() {
    $slider_items = get_posts(array(
        'post_type' => 'sstli_slider',
        'posts_per_page' => -1,
    ));

    $formatted_slider_items = array();

    foreach ($slider_items as $slider_item) {
        $formatted_slider_items[] = array(
            'title' => get_the_title($slider_item),
            'description' => get_field('slide_description', $slider_item->ID),
            'background' => get_field('slide_background', $slider_item->ID),
        );
    }

    return rest_ensure_response($formatted_slider_items);
}


// Callback function to get Branches
function sstli_get_branches() {
    $args = array(
        'post_type' => 'branches',
        'posts_per_page' => -1, // Get all branches
    );

    $branches = get_posts($args);

    $response = array();

    foreach ($branches as $branch) {
        // Retrieve ACF fields using the `get_field` function
        $branch_image = get_field('branch_image', $branch->ID);
        $branch_name = get_field('branch_name', $branch->ID);
        $branch_hotline = get_field('branch_hotline', $branch->ID);
        $branch_email = get_field('branch_email', $branch->ID);
        $branch_number = get_field('branch_number', $branch->ID);
        $branch_location = get_field('branch_location', $branch->ID);

        $response[] = array(
            'id' => $branch->ID,
            'title' => $branch->post_title,
            // 'content' => $branch->post_content,
            // 'thumbnail' => get_the_post_thumbnail_url($branch->ID, 'thumbnail'),
            'branch_name' => $branch_name,
            'branch_hotline' => $branch_hotline,
            'branch_email' => $branch_email,
            'branch_number' => $branch_number,
            'branch_location' => $branch_location,
            'branch_image' => $branch_image,
        );
    }

    return rest_ensure_response($response);
}

// Callback function to get Testimonial data with ACF fields
function sstli_custom_get_testimonials() {
    $testimonials = get_posts(array(
        'post_type' => 'testimonial',
        'posts_per_page' => -1,
    ));

    $formatted_testimonials = array();

    foreach ($testimonials as $testimonial) {
        $testimonial_image = get_field('testimonial_image', $testimonial->ID);
        $testimonial_title = get_field('testimonial_title', $testimonial->ID);
        $testimonial_name = get_field('testimonial_name', $testimonial->ID);
        $testimonial_description = get_field('testimonial_description', $testimonial->ID);

        $formatted_testimonials[] = array(
            'id' => $testimonial->ID,
            'title' => $testimonial_title ? $testimonial_title : get_the_title($testimonial),
            'content' => apply_filters('the_content', $testimonial_description ? $testimonial_description : $testimonial->post_content),
            'author' => $testimonial_name,
            'image' => $testimonial_image,
            'date' => get_the_date('Y-m-d', $testimonial->ID), // Format the date as needed
        );
    }

    return rest_ensure_response($formatted_testimonials);
}

// Callback function to get Notifications data with ACF fields
function sstli_custom_get_notifications() {
    $notifications = get_posts(array(
        'post_type' => 'notifications',
        'posts_per_page' => -1,
    ));

    $formatted_notifications = array();

    foreach ($notifications as $notification) {
        $notification_image = get_field('notification_image', $notification->ID);
        $notification_title = get_field('notification_title', $notification->ID);
        $notification_description = get_field('notification_description', $notification->ID);

        $formatted_notifications[] = array(
            'id' => $notification->ID,
            'title' => $notification_title ? $notification_title : get_the_title($notification),
            'content' => apply_filters('the_content', $notification_description ? $notification_description : $notification->post_content),
            'date' => get_the_date('Y-m-d', $notification->ID), 
            'image' => $notification_image,
        );
    }

    return rest_ensure_response($formatted_notifications);
}


// Callback function to get Social Links data with dynamic social platforms
function sstli_custom_get_social_links() {
    $social_links = get_posts(array(
        'post_type' => 'social_links',
        'posts_per_page' => -1,
    ));

    $formatted_social_links = array();

    foreach ($social_links as $social_link) {
        $social_platforms = get_field('social_platforms', $social_link->ID);

        if ($social_platforms && is_array($social_platforms)) {
            $formatted_social_platforms = array();

            foreach ($social_platforms as $platform) {
                $social_name = $platform['social_name'];
                $social_link = $platform['social_link'];
                $social_icon = $platform['social_icon'];

                $formatted_social_platforms[] = array(
                    'name' => $social_name,
                    'link' => $social_link,
                    'icon' => $social_icon,
                );
            }

            $formatted_social_links[] = array(
                'platforms' => $formatted_social_platforms,
            );
        }
    }

    return rest_ensure_response($formatted_social_links);
}



// Callback function to check username and email availability
function check_user_availability($data) {
    $username_to_check = $data['username'];
    $email_to_check = $data['email'];

    $response = array();

    // Check if username is available
    if (username_exists($username_to_check)) {
        $response['username_status'] = 'خطأ';
        $response['username_message'] = 'عفوا ،هذا الاسم غير متاح.';
    } else {
        $response['username_status'] = 'نجاح';
        $response['username_message'] = 'اسم المستخدم متاح للأستخدام.';
    }

    // Check if email is available
    if (email_exists($email_to_check)) {
        $response['email_status'] = 'خطأ';
        $response['email_message'] = 'عفوا ،هذا الايميل غير متاح.';
    } else {
        $response['email_status'] = 'نجاح';
        $response['email_message'] = 'اسم البريد متاح للأستخدام.';
    }

    return $response;
}





// Register REST API Endpoint for About Us
function sstli_custom_get_about_us() {
    $about_us_items = get_posts(array(
        'post_type' => 'about_us',
        'posts_per_page' => -1,
    ));

    $formatted_about_us_items = array();

    foreach ($about_us_items as $about_us_item) {
        $first_title = get_field('first_title', $about_us_item->ID);
        $first_description = get_field('first_description', $about_us_item->ID);
        $second_title = get_field('second_title', $about_us_item->ID);
        $second_description = get_field('second_description', $about_us_item->ID);
        $certificates = get_field('certificates', $about_us_item->ID);

        $formatted_about_us_items[] = array(
            'id' => $about_us_item->ID,
            'title' => get_the_title($about_us_item),
            'first_title' => $first_title,
            'first_description' => $first_description,
            'second_title' => $second_title,
            'second_description' => $second_description,
            'certificates' => $certificates,
        );
    }

    return rest_ensure_response($formatted_about_us_items);
}



/*******************************
 * *****************************
 *  shortcode functions
 * *****************************
 *******************************/


// Shortcode to display FAQs or Training Units on a page with custom accordion
function sstli_custom_shortcode($atts) {
    ob_start(); // Start output buffering

    $atts = shortcode_atts(array(
        'type' => 'faq', // Default to FAQs if not specified
    ), $atts, 'sstli_custom');

    $post_type = $atts['type'];

    ?>
    <div class="accordion">
        <?php
        $items = sstli_custom_get_items($post_type);

        if (!is_wp_error($items)) {
            foreach ($items->data as $index => $item) {
                ?>
                <div class="accordion-item">
                    <div class="accordion-item-header">
                        <?php echo esc_html($item['title']); ?>
                    </div>
                    <div class="accordion-item-body">
                        <div class="accordion-item-body-content">
                            <?php echo wpautop(wp_kses_post($item['content'])); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo 'Error fetching items.';
        }
        ?>
    </div>

    <?php

    return ob_get_clean(); // End output buffering and return the content
}
add_shortcode('sstli_custom', 'sstli_custom_shortcode');

// Utility function to get either FAQs or Training Units
function sstli_custom_get_items($post_type) {
    switch ($post_type) {
        case 'faq':
            return sstli_custom_get_faqs();
        case 'training_unit':
            return sstli_custom_get_training_units();
        default:
            return new WP_Error('invalid_post_type', 'Invalid post type specified.');
    }
}
