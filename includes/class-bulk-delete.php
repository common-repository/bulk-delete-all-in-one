<?php
if (!class_exists('IFBDP_bulk_delete')) {

    class IFBDP_bulk_delete {

        public function __construct() {
            global $wpdb;
            $this->wpdb = $wpdb;
            add_action('admin_menu', array($this, 'ifbdp_add_admin_menu'));
        }

        public function ifbdp_init(){
            add_action('admin_enqueue_scripts', array($this, 'ifbdp_enqueue_scripts'));
            add_action('wp_ajax_ifbdp_custom_delete_all_pages', array($this, 'ifbdp_custom_delete_all_pages'));             
            add_action('wp_ajax_ifbdp_delete_post_types', array($this,'ifbdp_delete_post_types_callback'));
            add_action( 'wp_ajax_ifbdp_delete_all_media', array($this ,'ifbdp_delete_all_media'));
            add_action( 'wp_ajax_ifbdp_delete_all_comments', array($this ,'ifbdp_delete_all_comments'));
            add_action('wp_ajax_ifbdp_get_image_count_by_date', array($this, 'ifbdp_get_image_count_by_date_callback'));            
            add_action('wp_ajax_ifbdp_delete_images', array($this, 'ifbdp_delete_images_callback'));
            add_action('wp_ajax_ifbdp_fetch_images_by_month', array($this, 'ifbdp_fetch_images_by_month'));           
            add_action('wp_ajax_ifbdp_get_image_count_by_year', array($this,'ifbdp_get_image_count_by_year') );            
            add_action('wp_ajax_ifbdp_get_images_by_author', array($this ,'ifbdp_get_images_by_author' ) );            
            add_action('wp_ajax_ifbdp_get_images_by_month_year', array($this, 'ifbdp_get_images_by_month_year'));            
            add_action('wp_ajax_ifbdp_delete_all_wp_options_images', array($this,'ifbdp_delete_all_wp_options_images_callback' ) );
            add_action('wp_ajax_ifbdp_delete_media_by_author', array ($this , 'ifbdp_delete_media_by_author_callback') );
            add_action('wp_ajax_ifbdp_delete_media_by_month_year', array($this, 'ifbdp_delete_media_by_month_year_callback'));
            add_action('wp_ajax_ifbdp_delete_images_between_dates', array($this ,'ifbdp_delete_images_between_dates_callback') );
            add_action('wp_ajax_ifbdp_delete_all_unattached_images', array($this ,'ifbdp_delete_all_unattached_images_callback'));
            add_action('wp_ajax_ifbdp_delete_all_attached_images', array ($this ,'ifbdp_delete_all_attached_images_callback') );
            add_action('wp_ajax_ifbdp_delete_media_by_year', array($this , 'ifbdp_delete_media_by_year_callback') );
            add_action('wp_ajax_ifbdp_delete_all_images', array($this, 'ifbdp_delete_all_images_callback'));
            add_action( 'wp_ajax_ifbdp_download_all_images', array($this, 'ifbdp_download_all_images')); // For logged-in users 
            add_action( 'wp_ajax_ifbdp_download_attached_images', array($this, 'ifbdp_download_attached_images' ) ); // For logged-in users
            add_action( 'wp_ajax_ifbdp_download_unattached_images', array($this,'ifbdp_download_unattached_images' ) ); // For logged-in users
            add_action('wp_ajax_ifbdp_download_media_by_author', array($this, 'ifbdp_download_media_by_author'));
            add_action('wp_ajax_ifbdp_download_images_between_dates', array($this, 'ifbdp_download_images_between_dates_callback'));
            add_action('wp_ajax_ifbdp_download_images_by_month_year', array($this, 'ifbdp_download_images_by_month_year'));
            add_action('wp_ajax_ifbdp_download_media_by_years', array($this, 'ifbdp_download_media_by_years'));
            add_action('wp_ajax_ifbdp_download_author_images_callback', array($this, 'ifbdp_download_author_images_callback'));
            add_action('wp_ajax_ifbdp_get_image_urls', array($this,'ifbdp_get_image_urls_callback'));
            add_action('wp_ajax_ifbdp_get_attached_image_urls', array($this,'ifbdp_get_attached_image_urls_callback'));
            add_action('wp_ajax_ifbdp_get_unattached_image_urls', array($this,'ifbdp_get_unattached_image_urls_callback'));
            add_action('wp_ajax_ifbdp_get_dates_image_urls', array($this, 'ifbdp_get_dates_image_urls_callback'));
            add_action('wp_ajax_ifbdp_get_monthswise_image_urls', array($this, 'ifbdp_get_monthswise_image_urls'));           
            add_action('wp_ajax_ifbdp_show_media_urls_by_year', array($this, 'ifbdp_show_media_urls_by_year'));
            add_action('wp_ajax_ifbdp_show_media_by_author_callback', array($this, 'ifbdp_show_media_by_author_callback'));
            add_action('wp_ajax_ifbdp_delete_selected_files', array($this, 'ifbdp_delete_selected_files_callback'));
            add_action('wp_ajax_ifbdp_download_selected_files', array($this ,'ifbdp_download_selected_files_callback'));                   
        } 

        public function ifbdp_enqueue_scripts() {
            // Check if jQuery is not already enqueued
            if (!wp_script_is('jquery', 'enqueued')) {
                // Enqueue jQuery
                wp_enqueue_script('jquery');
            }
            // Check if jQuery UI Datepicker is not already enqueued
            if (!wp_script_is('jquery-ui-datepicker', 'enqueued')) {
                // Enqueue jQuery UI Datepicker
                wp_enqueue_script('jquery-ui-datepicker');
            }
            wp_enqueue_script(
                'ifbdp-validation-js',
                esc_url(IFBDP_PLUGIN_DIR . 'assets/jquery.validate.min.js'),
                array('jquery'),
                IFBDP_VERSION,
                true
            );
            wp_enqueue_script(
                'ifbdp-custom-js',
                esc_url(IFBDP_PLUGIN_DIR . 'assets/custom.js'),
                array('jquery'),
                IFBDP_VERSION,
                true
            );
            wp_localize_script(
                'ifbdp-custom-js',
                'ajax_object',
                array('ajaxurl' => esc_url(admin_url('admin-ajax.php')))
            );
            wp_enqueue_style(
                'ifbdp-custom-css',
                esc_url(IFBDP_PLUGIN_DIR . 'assets/custom.css')
            );
        }

        public function ifbdp_add_admin_menu() {
            add_menu_page(
                esc_html__('iFlair Bulk Delete Settings', 'bulk-delete-all-in-one'), // Page title, escaped for HTML output
                esc_html__('iFlair Bulk Delete', 'bulk-delete-all-in-one'), // Menu title, escaped for HTML output
                'manage_options', // Capability
                'iflair-bulk-delete-settings', // Menu slug
                array($this, 'ifbdp_render_admin_page'), // Callback function to render page content
                'dashicons-images-alt2', // Icon
                5 // Position
            );
        }

        private function ifbdp_calculate_directory_size($dir) {
            $size = 0;
            $dir_handle = opendir($dir);
            if ($dir_handle) {
                while (($file = readdir($dir_handle)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $path = $dir . '/' . $file;
                        if (is_file($path)) {
                            $size += filesize($path);
                        } elseif (is_dir($path)) {
                            $size += $this->ifbdp_calculate_directory_size($path);
                        }
                    }
                }
                closedir($dir_handle);
            }
            return $size;
        }

        // Function to format size based on appropriate unit
        private function ifbdp_format_size($size) {
            if ($size < 1024) {
                return number_format($size, 2) . ' ' . esc_html__('bytes', 'bulk-delete-all-in-one');
            } elseif ($size < 1048576) {
                return number_format($size / 1024, 2) . ' ' . esc_html__('KB', 'bulk-delete-all-in-one');
            } elseif ($size < 1073741824) {
                return number_format($size / 1048576, 2) . ' ' . esc_html__('MB', 'bulk-delete-all-in-one');
            } else {
                return number_format($size / 1073741824, 2) . ' ' . esc_html__('GB', 'bulk-delete-all-in-one');
            }
        }

        // Function to get total size of all images
        public function ifbdp_get_total_image_size() {
            // Get the uploads directory path
            $uploads_dir = wp_upload_dir();
            $uploads_path = $uploads_dir['basedir'];
            // Calculate size of uploads directory
            $total_size = $this->ifbdp_calculate_directory_size($uploads_path);
            // Format and return total size
            return $this->ifbdp_format_size($total_size);
        }

        // Function to retrieve attached image IDs
        private function ifbdp_get_attached_image_ids() {
            global $wpdb;
            // Query the database to retrieve attachment IDs of images with a parent post
            $attachment_ids = $wpdb->get_col("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'attachment'
                AND post_parent > 0
                AND post_mime_type LIKE 'image%'
            ");
            return $attachment_ids;
        }

        // Function to calculate the size of an image file and all its sizes
        private function ifbdp_calculate_image_size($attachment_id) {
            $total_size = 0;

            // Get the path to the original image file
            $file_path = get_attached_file($attachment_id);
            if ($file_path && is_file($file_path)) {
                $total_size += filesize($file_path); // Size of the original image
            }

            // Get the metadata for the image
            $metadata = wp_get_attachment_metadata($attachment_id);
            if ($metadata && isset($metadata['sizes'])) {
                // Iterate through all image sizes
                foreach ($metadata['sizes'] as $size) {
                    $size_file = str_replace(basename($file_path), $size['file'], $file_path);
                    if (is_file($size_file)) {
                        $total_size += filesize($size_file);
                    }
                }
            }

            return $total_size;
        }

        // Function to calculate total size of all attached image files
        private function ifbdp_calculate_attached_image_size() {
            // Get attached image IDs
            $attached_image_ids = $this->ifbdp_get_attached_image_ids();
            // Initialize total size variable
            $total_size = 0;
            // Calculate size of each attached image file and add to total size
            foreach ($attached_image_ids as $attachment_id) {
                $total_size += $this->ifbdp_calculate_image_size($attachment_id);
            }
            return $total_size;
        }

        // Function to get total size of all attached images
        public function ifbdp_get_total_attached_image_size() {
            // Calculate total size of attached image files
            $total_size = $this->ifbdp_calculate_attached_image_size();
            // Format and return total size
            return $this->ifbdp_format_size($total_size);
        }

        // Function to retrieve unattached image IDs
        private function ifbdp_get_unattached_image_ids() {
            global $wpdb;
            // Query the database to retrieve attachment IDs of unattached images
            $attachment_ids = $wpdb->get_col("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'attachment'
                AND post_parent = 0
                AND post_mime_type LIKE 'image%'
            ");
            return $attachment_ids;
        }

        // Function to calculate the size of an unattached image file and all its sizes
        private function ifbdp_calculate_unattached_images_size($attachment_id) {
            $total_size = 0;

            // Get the path to the original image file
            $file_path = get_attached_file($attachment_id);
            if ($file_path && is_file($file_path)) {
                $total_size += filesize($file_path); // Size of the original image
            }

            // Get the metadata for the image
            $metadata = wp_get_attachment_metadata($attachment_id);
            if ($metadata && isset($metadata['sizes'])) {
                // Iterate through all image sizes
                foreach ($metadata['sizes'] as $size) {
                    $size_file = str_replace(basename($file_path), $size['file'], $file_path);
                    if (is_file($size_file)) {
                        $total_size += filesize($size_file);
                    }
                }
            }
            return $total_size;
        }

        // Function to calculate size of unattached image files
        private function ifbdp_calculate_unattached_image_size() {
            // Get unattached image IDs
            $unattached_image_ids = $this->ifbdp_get_unattached_image_ids();
            // Initialize total size variable
            $total_size = 0;
            // Calculate size of each unattached image file and add to total size
            foreach ($unattached_image_ids as $attachment_id) {
                $total_size += $this->ifbdp_calculate_unattached_images_size($attachment_id);
            }
            return $total_size;
        }

        // Function to get total size of all unattached images
        public function ifbdp_get_total_unattached_image_size() {
            // Calculate total size of unattached image files
            $total_size = $this->ifbdp_calculate_unattached_image_size();
            // Format and return total size
            return $this->ifbdp_format_size($total_size);
        }

        public function ifbdp_render_admin_page() {
            // Render your plugin's settings page here
            ?>
            <div class="wrap">
                <h2><?php echo esc_html__('iFlair Bulk Delete Settings','bulk-delete-all-in-one');?></h2>          
                <div class="ifbdp_loader" style="display: none;">
                    <img src="<?php echo esc_url(IFBDP_PLUGIN_DIR . 'assets/images/loader.svg'); ?>">
                </div>
                <?php
                $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'page';
                ?>
                <h2 class="nav-tab-wrapper">
                    <a href="?page=iflair-bulk-delete-settings&tab=page" class="nav-tab<?php echo ($tab === 'page') ? ' nav-tab-active' : ''; ?>"><?php echo esc_html__('Page Settings','bulk-delete-all-in-one'); ?></a>
                    <a href="?page=iflair-bulk-delete-settings&tab=post" class="nav-tab<?php echo ($tab === 'post') ? ' nav-tab-active' : ''; ?>"><?php echo esc_html__('Post Settings','bulk-delete-all-in-one'); ?></a>
                    <a href="?page=iflair-bulk-delete-settings&tab=media" class="nav-tab<?php echo ($tab === 'media') ? ' nav-tab-active' : ''; ?>"><?php echo esc_html__('Media Settings','bulk-delete-all-in-one'); ?></a>
                    <a href="?page=iflair-bulk-delete-settings&tab=comment" class="nav-tab<?php echo ($tab === 'comment') ? ' nav-tab-active' : ''; ?>"><?php echo esc_html__('Comments Settings','bulk-delete-all-in-one'); ?></a>
                </h2>
            <?php 
            // Output the content based on the selected tab
            if (empty($tab) || $tab === 'page') { ?> 
                <div id="ifbdp-page-settings" class="tab-content">
                    <div class="ifbdp-delete-pages">
                        <form method="post">
                            <?php 
                            wp_nonce_field( 'custom_delete_all_pages', 'custom_delete_all_pages_nonce' );
                                $pages_count = wp_count_posts('page')->publish; 
                            ?>
                            <table class="form-table">
                                <tr>
                                    <th>
                                        <label for="ifbdp_delete_all_pages">
                                            <?php echo esc_html__('Delete all pages?', 'bulk-delete-all-in-one'); ?>
                                            <?php echo esc_html__('(', 'bulk-delete-all-in-one'); ?>
                                            <?php echo esc_html($pages_count); ?> <?php echo esc_html__('pages )', 'bulk-delete-all-in-one'); ?>
                                                
                                            </label>
                                    </th>
                                    <td>
                                        <label class="ifbdp_switch" for="ifbdp_delete_all_pages">
                                            <input type="checkbox" id="ifbdp_delete_all_pages" name="ifbdp_delete_all_pages" />
                                            <span class="ifbdp_slider ifbdp_round"></span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            <input type="submit" name="ifbdp_submit_delete_all_pages" value="Delete Pages" class="button-primary"/>
                        </form>
                    </div>         
                </div>
            <?php } elseif ($tab === 'post') { ?>
                <div id="ifbdp-post-settings" class="tab-content">
                    <div class="ifbdp-delete-post-types">
                        <form id="ifbdp-delete-post-types-form" method="post" action="">
                        <?php
                        wp_nonce_field('custom_delete_post_types', 'custom_delete_post_types_nonce');              
                        // Get all post types including default post type ('post') and excluding attachment and pages
                        $post_types = get_post_types(array('public' => true), 'names', 'and');
                        ?>
                        <table class="form-table">
                            <?php 
                        foreach ($post_types as $post_type) {
                            // Skip the 'attachment' and 'page' post types
                            if ($post_type === 'attachment' || $post_type === 'page') {
                                continue;
                            }
                            $post_count = wp_count_posts($post_type)->publish; // Get count of published posts for the current post type
                            ?>                            
                            <tr>
                                <th>
                                    <label for="delete_<?php echo esc_attr($post_type); ?>">
                                        <?php echo esc_html(ucfirst($post_type)); ?> <?php echo esc_html__('(', 'bulk-delete-all-in-one'); ?><?php echo esc_html($post_count); ?> <?php echo esc_html__('posts)', 'bulk-delete-all-in-one'); ?>
                                    </label>
                                </th>
                                <td>
                                    <label class="ifbdp_switch" for="delete_<?php echo esc_attr($post_type); ?>">
                                        <input type="checkbox" name="post_types[]" id="delete_<?php echo esc_attr($post_type); ?>" value="<?php echo esc_attr($post_type); ?>">
                                        <span class="ifbdp_slider ifbdp_round"></span>
                                    </label>
                                </td>
                            </tr>                   
                            <?php
                        }
                        ?>
                        </table>
                        <button type="button" id="ifbdp_delete_post_types_button" class="button-primary"><?php echo esc_html__('Delete Post Type Data','bulk-delete-all-in-one'); ?></button>
                    </form>
                    </div>
                </div>
            <?php } elseif ($tab === 'media') { ?>
                <div id="ifbdp-media-settings" class="tab-content">
                <div class="ifbdp-media-delete">
                    <form id="ifbdp-delete-post-types-form">
                        <?php wp_nonce_field('delete_media_nonce', 'delete_media_nonce'); ?>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="ifbdp_deleteAllMedia">
                                        <?php echo esc_html__('Delete all media', 'bulk-delete-all-in-one'); ?>
                                        <?php 
                                        global $wpdb;
                                        // Query to get the count of all attachments
                                        $media_count = $wpdb->get_var("
                                            SELECT COUNT(ID) 
                                            FROM {$wpdb->posts} 
                                            WHERE post_type = 'attachment' 
                                            AND post_mime_type LIKE 'image%'
                                        ");
                                        echo esc_html__('(', 'bulk-delete-all-in-one'); ?>
                                        <?php echo esc_html($media_count); ?> <?php echo esc_html__('media)', 'bulk-delete-all-in-one'); ?>
                                    </label>
                                </th>
                                <td>
                                    <label class="ifbdp_switch" for="ifbdp_deleteAllMedia">
                                        <input type="checkbox" id="ifbdp_deleteAllMedia">
                                        <span class="ifbdp_slider ifbdp_round"></span>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <button type="button" id="ifbdp_delete_media_button" class="button-primary"><?php echo esc_html__('Delete Media','bulk-delete-all-in-one'); ?></button>
                    </form>
                    <?php
                    $this->ifbdp_display_uploads_with_checkboxes();
                        // Get the global $wpdb object
                        global $wpdb;
                        // Query to get the count of all attachments
                        $total_attachments_count = $wpdb->get_var("
                            SELECT COUNT(ID) 
                            FROM {$wpdb->posts} 
                            WHERE post_type = 'attachment' 
                            AND post_mime_type LIKE 'image%'
                        ");
                        // Query to get the count of attached images
                        $attached_images_count = $wpdb->get_var("
                            SELECT COUNT(ID) 
                            FROM {$wpdb->posts} 
                            WHERE post_type = 'attachment' 
                            AND post_parent > 0
                        ");
                        // Query to get the sum of sizes of all attachments
                        $total_images_size_bytes = $wpdb->get_var("
                            SELECT SUM(meta_value) 
                            FROM {$wpdb->postmeta} 
                            WHERE meta_key = '_wp_attached_file'
                        ");
                        // Query to get the sum of sizes of attached images
                        $attached_images_size_bytes = $wpdb->get_var("
                            SELECT SUM(pm.meta_value) 
                            FROM {$wpdb->posts} p
                            JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                            WHERE p.post_type = 'attachment'
                            AND p.post_parent > 0
                            AND pm.meta_key = '_wp_attached_file'
                        ");
                        // Calculate the count of unattached images
                        $unattached_images_count = $total_attachments_count - $attached_images_count;
                        // Calculate the size of unattached images
                        $unattached_images_size_bytes = $total_images_size_bytes - $attached_images_size_bytes;
                        // Convert bytes to gigabytes
                        $total_images_size_gb = $total_images_size_bytes / (1024 * 1024 * 1024);
                        $attached_images_size_gb = $attached_images_size_bytes / (1024 * 1024 * 1024);
                        $unattached_images_size_gb = $unattached_images_size_bytes / (1024 * 1024 * 1024);
                        ?>
                        <div class="ifbdp-images-main">
                        <?php
                        // Output the counts                        
                        if($total_attachments_count > 0) { ?>
                        <div class="ifbdp-total-images-wrapper">
                            <div class="ifbdp-total-images">
                                <?php 
                                $ifbdp_bulk_delete = new IFBDP_bulk_delete();
                                $total_image_size = $ifbdp_bulk_delete->ifbdp_get_total_image_size();
                                ?>
                                <table class="form-table">
                                    <tr>
                                        <th><?php echo esc_html__('#Total number of images:', 'bulk-delete-all-in-one'); ?></th>
                                        <td><?php echo esc_html($total_attachments_count); ?></td>
                                    </tr>                                
                                    <tr>
                                        <th><?php echo esc_html__('Total size of all images: ','bulk-delete-all-in-one'); ?></th>
                                        <td><?php echo esc_html($total_image_size); ?></td>
                                    </tr>
                                </table>
                                <input type="submit" value="Download" id="ifbdp-download-all-images" class="button-primary">
                                <input type="submit" value="Show Images" id="ifbdp-show-all-images" class="button-primary">
                                <input type="submit" value="Delete all images" id="ifbdp-delete-all-images" class="button-primary"> <?php wp_nonce_field('delete_all_images_nonce', 'delete_all_images_nonce_field'); ?>
                            </div>
                            <!-- Popup container -->
                            <div id="ifbdp-show-all-image-popup" class="image_popup" style="display: none;">
                                <button id="ifbdp-all-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                                <div id="ifbdp-image-list"></div>
                            </div>
                        </div>
                            <?php
                        }                        
                        if($attached_images_count > 0){ ?>
                        <div class="ifbdp-attached-images-wrapper">
                            <div class="ifbdp-attached-images">
                                <?php 
                                $ifbdp_bulk_delete = new IFBDP_bulk_delete();
                                $total_attached_size = $ifbdp_bulk_delete->ifbdp_get_total_attached_image_size();
                                ?>
                                <table class="form-table">
                                    <tr>
                                        <th><?php echo esc_html__('#Number of attached images: ','bulk-delete-all-in-one'); ?></th>
                                        <td><?php echo esc_html($attached_images_count); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo esc_html__('Total size of attached images: ','bulk-delete-all-in-one'); ?></th>
                                        <td><?php echo esc_html($total_attached_size);?></td>
                                    </tr>
                                </table>
                                <input type="submit" value="Download" id="ifbdp-download-all-attached-images" class="button-primary">
                                <input type="submit" value="Show Images" id="ifbdp-show-attached-images" class="button-primary">
                                <input type="submit" value="Delete all attached images" id="ifbdp-delete-all-attached-images" class="button-primary">                  
                                <?php 
                                wp_nonce_field('delete_all_attached_images_nonce', 'delete_all_attached_images_nonce_field');
                                ?>
                            </div>
                            <!-- Popup container -->
                            <div id="ifbdp-show-attached-image-popup" class="image_popup" style="display: none;">
                                <button id="ifbdp-attached-image-close-popup" class="close-icon"></button>
                                <div id="ifbdp-attached-image-list"></div>
                            </div>
                        </div>
                            <?php
                        }
                        if($unattached_images_count > 0){ ?>
                            <div class="ifbdp-unattached-images-wrapper">
                                <div class="ifbdp-unattached-images">
                                    <table class="form-table">
                                        <tr>
                                            <th><?php echo esc_html__('#Number of unattached images: ','bulk-delete-all-in-one'); ?></th>
                                            <td><?php echo esc_html($unattached_images_count);?></td>
                                        </tr>
                                        <tr>
                                        <?php 
                                        $ifbdp_bulk_delete = new IFBDP_bulk_delete();
                                        $total_unattached_size = $ifbdp_bulk_delete->ifbdp_get_total_unattached_image_size();
                                        ?>
                                        <tr>
                                            <th><?php echo esc_html__('Total size of unattached images: ','bulk-delete-all-in-one'); ?></th>
                                            <td><?php echo esc_html($total_unattached_size);?></td>
                                        </tr>
                                    </table>
                                    <input type="submit" value="Download" id="ifbdp-download-all-unattached-images" class="button-primary">
                                    <input type="submit" value="Show Images" id="ifbdp-show-unattached-images" class="button-primary">
                                    <input type="submit" value="Delete all unattached images" id="ifbdp-delete-all-unattached-images" class="button-primary">                              
                                    <?php 
                                    wp_nonce_field('delete_all_unattached_images_nonce', 'delete_all_unattached_images_nonce_field');?>
                                </div>
                                <?php } ?>
                                <!-- Popup container -->
                                <div id="ifbdp-show-unattached-image-popup" class="image_popup" style="display: none;">
                                    <button id="ifbdp-unattached-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                                    <div id="ifbdp-unattached-image-list"></div>
                                </div>
                            </div>
                            <?php 
                            if($unattached_images_count > 0){ ?>
                        </div>
                    <?php } ?>
                    <div class="filter-date-wrapper">
                    <div class="filter-date-inner date-selector-wrapper">
                        <h2><?php echo esc_html__('Fetch and delete images between dates','bulk-delete-all-in-one'); ?></h2>
                        <form id="ifbdp-date-range-form">
                            <?php wp_nonce_field( 'date_images_nonce', 'date_images_nonce_field' ); ?>
                            <div class="ifbdp-from-date-wrap">
                                <label for="ifbdp-from-date"><?php echo esc_html__('From:','bulk-delete-all-in-one'); ?></label>
                                <input type="date" id="ifbdp-from-date" name="bulk-delete-all-in-one">
                            </div>
                            <div class="ifbdp-ifbdp-to-date-wrap">
                                <label for="ifbdp-ifbdp-to-date"><?php echo esc_html__('To:','bulk-delete-all-in-one'); ?></label>
                                <input type="date" id="ifbdp-to-date" name="ifbdp-to-date">
                            </div>
                            <input type="submit" value="Submit" id="ifbdp-submit-dates" class="button-primary">
                            <?php //wp_nonce_field( 'delete_images_nonce', 'delete_images_nonce_field' ); ?>
                        </form>
                        <div id="ifbdp-image-count-result"><p></p></div>
                        <!-- Popup container -->
                        <div id="ifbdp-show-dates-image-popup" class="image_popup" style="display: none;">
                            <button id="ifbdp-dates-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                            <div id="ifbdp-dates-image-list"></div>
                        </div>
                    </div>                     
                    <?php 
                    $this->ifbdp_custom_month_year_dropdown(); 
                    $this->ifbdp_custom_year_dropdown();
                    $this->ifbdp_display_authors_list();
                    $this->ifbdp_get_options_images();
                    ?>
                </div>
            </div>           
            <?php    
            } elseif ($tab === 'comment') { 
                ?>
                <div id="ifbdp-comment-settings" class="tab-content">           
                    <div class="ifbdp-comments-delete">
                        <form id="ifbdp-comments-post-types-form">
                            <?php wp_nonce_field('delete_comments_nonce', 'delete_comments_nonce');
                            $comment_count = wp_count_comments()->total_comments; ?>
                            <table class="form-table">
                                <tr>
                                    <th>
                                        <label for="ifbdp_deleteAllComments">
                                            <?php echo esc_html('Delete all Comments?', 'bulk-delete-all-in-one'); ?>
                                            <?php echo esc_html__('(', 'bulk-delete-all-in-one'); ?><?php echo esc_html($comment_count); ?> <?php echo esc_html__('comments)', 'bulk-delete-all-in-one'); ?>
                                        </label>
                                    </th>
                                    <td>
                                        <label class="ifbdp_switch" for="ifbdp_deleteAllComments">
                                            <input type="checkbox" id="ifbdp_deleteAllComments">
                                            <span class="ifbdp_slider ifbdp_round"></span>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            <button type="button" id="ifbdp_delete_comments_button" class="button-primary"><?php echo esc_html('Delete Comments','bulk-delete-all-in-one'); ?></button>
                        </form>
                    </div>                  
                </div>
                <?php 
            } ?>
            </div>  
            <!-- Close wrap -->
            <?php 
        }

        public function ifbdp_custom_delete_all_pages() {
            // Check nonce
            if ( ! isset( $_POST['custom_delete_all_pages_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['custom_delete_all_pages_nonce'] ) ) , 'custom_delete_all_pages' ) ) {
                wp_send_json_error( esc_html__('Nonce verification failed.', 'bulk-delete-all-in-one') );
                wp_die();
            }
            
            // Check if user has permission to delete pages
            if (!current_user_can('delete_pages')) {                
                wp_send_json_error( esc_html__('You do not have permission to delete pages.', 'bulk-delete-all-in-one') );
                wp_die();
            }
            // Get all pages including those in trash and draft states
            $args = array(
                'post_type'      => 'page',
                'post_status'    => array('publish', 'draft', 'trash'),
                'posts_per_page' => -1,
            );
            $pages_query = new WP_Query($args);
            // Check if there are any pages
            if ( ! $pages_query->have_posts() ) {
                wp_send_json_error( esc_html__('No pages found to delete.', 'bulk-delete-all-in-one') );
                wp_die();
            }
            // Loop through each page and delete it
            if ($pages_query->have_posts()) {
                while ($pages_query->have_posts()) {
                    $pages_query->the_post();
                    wp_delete_post(get_the_ID(), true); // Set second parameter to true to permanently delete the page
                }
            }
            // Reset post data
            wp_reset_postdata();
            // Send success response
            wp_send_json_success( esc_html__('All pages and associated database records deleted successfully.', 'bulk-delete-all-in-one') );
            wp_die();
        }

        function ifbdp_delete_post_types_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die();
            }

            // Verify nonce
            if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['security'])), 'custom_delete_post_types')) {
                wp_send_json_error('Nonce failed');
                wp_die();
            }

            if (isset($_POST['post_types'])) {
                $post_types = array_map('sanitize_text_field', $_POST['post_types']);
                $response = [];

                foreach ($post_types as $post_type) {
                    $args = [
                        'post_type'      => $post_type,
                        'posts_per_page' => -1,
                        'post_status'    => 'any', // Consider all post statuses
                    ];
                    $posts = get_posts($args);

                    if (empty($posts)) {
                        // No posts found for this post type
                        $response[$post_type] = sprintf(__('Post type "%s" does not have any data.', 'bulk-delete-all-in-one'), $post_type);
                    } else {
                        // Delete posts
                        foreach ($posts as $post) {
                            wp_delete_post($post->ID, true); // Delete post permanently
                        }
                        $response[$post_type] = sprintf(__('Post type "%s" deleted successfully!', 'bulk-delete-all-in-one'), $post_type);
                    }
                }
                wp_send_json_success($response);
            } else {
                wp_send_json_error(esc_html__('No post types selected.', 'bulk-delete-all-in-one'));
            }
            wp_die();
        }
        
        function ifbdp_delete_all_media() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die();
            }
            // Verify nonce
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'delete_media_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                wp_die();
            }
            global $wpdb;
            $media_count = $wpdb->get_var("SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image%'
                ");
            if($media_count == 0){
                wp_send_json_error( esc_html__('No media found for delete.', 'bulk-delete-all-in-one') );
                wp_die();
            } else {
                // Delete all media files from the upload folder
                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir['basedir'];
                // Get all files in the uploads directory
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($upload_path, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );
                foreach ($files as $fileinfo) {
                    $file = $fileinfo->getPathname();
                    if ($fileinfo->isFile()) {
                        // Delete the file
                        unlink($file);
                    } elseif ($fileinfo->isDir()) {
                        // Remove directory if empty
                        rmdir($file);
                    }
                }
                // Delete all media entries from the database
                global $wpdb;
                $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'attachment'");
                wp_send_json_success( esc_html__('All media files deleted successfully.', 'bulk-delete-all-in-one') );
                wp_die();
            }
        }

        function ifbdp_delete_all_comments() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die();
            }
            // Verify nonce
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'delete_comments_nonce' ) ){
                wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                wp_die();
            }
            // Get all comments
            $comments = get_comments(array(
                'number' => 1, // Just to check if there are any comments
                'fields' => 'ids' // Only get the IDs for performance
            ));

            // Check if there are comments to delete
            if (empty($comments)) {
                wp_send_json_error(esc_html__('No comments found to delete.', 'bulk-delete-all-in-one'));
                wp_die();
            }
            // Loop through comments and delete each one
            foreach ($comments as $comment) {
                wp_delete_comment($comment->comment_ID, true); 
            }
            // Send success response
            wp_send_json_success( esc_html__('All comments deleted successfully', 'bulk-delete-all-in-one') );
            wp_die();
        } 

        public function ifbdp_get_image_count_by_date_callback() {
            global $wpdb;
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify the nonce            
            if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['security'])), 'date_images_nonce')) {
                wp_send_json_error(esc_html__('Invalid nonce', 'bulk-delete-all-in-one'));
                wp_die();
            }
            $from_date = isset($_POST['from_date']) ? sanitize_text_field($_POST['from_date']) : null;
            // Check if the 'to_date' parameter is set and sanitize it
            $to_date = isset($_POST['to_date']) ? sanitize_text_field($_POST['to_date']) : null;
            $image_count = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(ID)
                FROM {$wpdb->posts}
                WHERE post_type = 'attachment'
                AND post_mime_type LIKE 'image%%'
                AND post_date BETWEEN %s AND %s
            ", $from_date . ' 00:00:00', $to_date . ' 23:59:59'));
            echo esc_html($image_count);
            wp_die();
        }

        public function ifbdp_delete_images_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die();
            }
            // Verify nonce
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'delete_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                wp_die();
            }
            $from_date = isset($_POST['from_date']) ? sanitize_text_field(wp_unslash($_POST['from_date'])) : '';

            $to_date = isset($_POST['to_date']) ? sanitize_text_field(wp_unslash($_POST['to_date'])) : '';
            // Get image attachments to delete
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'posts_per_page' => -1,
                'date_query' => array(
                    'after' => $from_date,
                    'before' => $to_date,
                ),
                'fields' => 'ids',
            ));
            if (empty($attachments)) {
                wp_send_json_error( esc_html__('No images found for the specified date range.', 'bulk-delete-all-in-one') );
                wp_die();
            }
            $deleted_count = 0;
            foreach ($attachments as $attachment_id) {
                // Delete the attachment from database
                $deleted = wp_delete_attachment($attachment_id, true);
                if ($deleted) {
                    $deleted_count++;
                }
            }
            if ($deleted_count > 0) {
                // Output success message
                wp_send_json_success( esc_html__('Successfully deleted $deleted_count images.', 'bulk-delete-all-in-one') );
            } else {
                // Output error message if no images were deleted
                wp_send_json_error( esc_html__('Failed to delete any images.', 'bulk-delete-all-in-one') );
            }
            wp_die();
        }
    
        public function ifbdp_custom_month_year_dropdown() {
            global $wpdb;
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Get the month and year when WordPress was installed
            $install_date = $wpdb->get_var("SELECT DATE_FORMAT( MIN(post_date), '%Y-%m' ) FROM $wpdb->posts");
            // Get current month and year
            $current_month_year = date('Y-m');
            // Initialize an empty array to store month-year values
            $months_years = array();
            // Generate all months and years between installation date and current date
            $start = new DateTime($install_date);
            $end = new DateTime($current_month_year);
            $interval = new DateInterval('P1M');
            $period = new DatePeriod($start, $interval, $end);
            foreach ($period as $dt) {
                $months_years[] = $dt->format('F Y');
            }
            // Add the current month to the array
            $months_years[] = date('F Y'); // Add current month
            // Reverse the array to show the most recent months first
            $months_years = array_reverse($months_years);
            // Output the dropdown menu
            ?>
            <div class="filter-date-inner month-selector-wrapper">
                <h2><?php echo esc_html__('Fetch and Delete Images by Month-Year','bulk-delete-all-in-one'); ?></h2>
                <form id="ifbdp_search_monthswise_image">
                    <?php wp_nonce_field('monthswise_images_nonce', 'monthswise_images_nonce_field'); ?>
                    <select name="ifbdp_month_year">
                        <option value="0"><?php echo esc_html__('Select Month-Year','bulk-delete-all-in-one'); ?></option>
                        <?php foreach ($months_years as $my) : ?>
                            <option value="<?php echo esc_attr($my); ?>"><?php echo esc_html($my); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php $nonce = wp_create_nonce('delete_media_by_month_year_nonce'); ?>
                    <input type="submit" value="Submit" class="button-primary"> <input type="hidden" name="nonce" value="<?php echo esc_html($nonce); ?>">
                </form>
                <div id="ifbdp_monthswise_images_display"></div>
                <div id="ifbdp-show-month_year-image-popup" class="image_popup" style="display: none;">
                    <button id="ifbdp_month_year-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                    <div id="ifbdp_month_year-image-list"></div>
                </div>
            </div>
            <?php
        }

        // PHP function to handle AJAX request
        function ifbdp_get_images_by_month_year() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if (isset($_POST['ifbdp_month_year'])) {
                global $wpdb;

                // Verify nonce
                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'monthswise_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                    wp_die();
                }

                // Get the month and year from the selected value
                $selected_date = sanitize_text_field($_POST['ifbdp_month_year']);
                $year = date('Y', strtotime($selected_date));
                $month = date('m', strtotime($selected_date));
                // Construct SQL query to retrieve the count of images uploaded in the selected month and year
                $image_count = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) 
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND YEAR(post_date) = %d
                    AND MONTH(post_date) = %d", $year, $month)
                );
                echo esc_html($image_count);
            }
            wp_die(); // This is required to terminate immediately and return a proper response
        }

        public function ifbdp_custom_year_dropdown() { ?>
            <div class="filter-date-inner year-selector-wrapper">
                <h2><?php echo esc_html__('Fetch and Delete Images by Year','bulk-delete-all-in-one'); ?></h2>
                <form id="ifbdp-year-form">
                    <?php wp_nonce_field('year_images_nonce', 'year_images_nonce_field'); ?>
                    <?php 
                    global $wpdb;
                    $install_year = $wpdb->get_var("SELECT DATE_FORMAT(MIN(post_date), '%Y') FROM $wpdb->posts");
                    $current_year = date('Y');
                    ?>
                    <select name="ifbdp-year" id="ifbdp-year">
                        <option value="0"><?php echo esc_html('Select Year','bulk-delete-all-in-one'); ?></option>
                        <?php 
                        for ($year = $install_year; $year <= $current_year; $year++) { ?>
                            <option value="<?php echo esc_html($year);?>"><?php echo esc_html($year);?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" value="Submit" class="button-primary">
                    </form>
                    <div id="ifbdp-image-count"></div>
                    <!-- Popup container -->
                    <div id="ifbdp-show-year-image-popup" class="image_popup" style="display: none;">
                        <button id="ifbdp-year-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                        <div id="ifbdp-year-image-list"></div>
                    </div>
                </div>
            <?php
        }

        function ifbdp_get_image_count_by_year() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if (isset($_POST['year'])) {
                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'year_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                    wp_die();
                }
                $year = intval($_POST['year']);                
                // Assuming your images are stored in a database table called 'images'
                global $wpdb;
                $image_count = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) 
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND YEAR(post_date) = %d", $year));                
                echo esc_html($image_count);
            }
            wp_die(); // This is required to terminate immediately and return a proper response
        }
       
        public function ifbdp_display_authors_list() {
            ?>
            <div class="filter-date-inner author-selector-wrapper">
                <h2><?php echo esc_html__('Fetch and Delete Images by Author','bulk-delete-all-in-one'); ?></h2>
                <form id="ifbdp-author-form">
                    <?php wp_nonce_field('author_images_nonce', 'author_images_nonce_field'); ?>
                    <?php
                   // This function generates the dropdown of authors
                    wp_dropdown_users([
                        'name' => 'author_id',
                        'who' => 'authors',
                        'id' => 'author_id',
                        'show_option_all' => __('Select Author', 'bulk-delete-all-in-one'), // Show a default option
                        'option_none_value' => '0', // Value for the 'no authors' option
                    ]);
                    ?>
                    <input type="submit" name="submit" value="submit" class="button-primary">
                </form>
                <div id="ifbdp-author-result"></div> <!-- Container for displaying result -->
                 <!-- Popup container -->
                <div id="ifbdp-show-author-image-popup" class="image_popup" style="display: none;">
                    <button id="ifbdp-author-image-close-popup" class="close-icon"><?php echo esc_html__('Close','bulk-delete-all-in-one'); ?></button>
                    <div id="ifbdp-author-image-list"></div>
                </div>                
            </div>
            </div>
            <?php
        }

        public function ifbdp_get_images_by_author() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if (isset($_POST['author_id'])) {
                global $wpdb;
                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'author_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                    wp_die();
                }
                $author_id = intval($_POST['author_id']);
                // Initialize the total image count
                $total_image_count = 0;
                // Construct SQL query to retrieve image count by author
                $query = $wpdb->prepare("
                    SELECT COUNT(*) 
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND post_author = %d",
                    $author_id
                );
                // Execute the query
                $image_count = $wpdb->get_var($query);
                // Display the total image count
                echo esc_html($image_count);
            }
            wp_die(); // This is required to terminate immediately and return a proper response
        }

        protected function ifbdp_fetchImageUrls() {
            global $wpdb; // Use global $wpdb instead of $this->wpdb for clarity and consistency
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }

            if (!empty($wpdb)) {
                // Prepare the SQL query with placeholders
                $sql = $wpdb->prepare(
                    "SELECT option_name, option_value 
                    FROM {$wpdb->options}
                    WHERE option_value LIKE %s 
                    OR option_value LIKE %s 
                    OR option_value LIKE %s 
                    OR option_value LIKE %s",
                    '%jpg%',
                    '%jpeg%',
                    '%png%',
                    '%gif%'
                );

                // Execute the query
                $results = $wpdb->get_results($sql);

                return $results;
            } else {
                return null;
            }
        }

        public function ifbdp_displayImages() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            $results = $this->ifbdp_fetchImageUrls();
            $image_urls = array(); // Array to store image URLs
            if (!empty($results)) { ?>
                <h2><?php echo esc_html__('Fetch and Delete Images from WP Options table','bulk-delete-all-in-one'); ?></h2>
                <div class="ifbdp-wp-options-images">
                    <?php 
                foreach ($results as $result) {
                    // For simplicity, assuming the option_value directly contains an image URL
                    if (filter_var($result->option_value, FILTER_VALIDATE_URL)) {
                        $image_url = esc_url($result->option_value);
                        $image_name = esc_attr($result->option_name);
                        $image_urls[] = array(
                            'url' => $image_url,
                            'name' => $image_name
                        );
                    }
                } ?>
                </div>
                <?php 
                $image_count = count($image_urls); // Get the number of images
            } else { ?>
                <p><?php echo esc_html__('No images found in wp_options.','bulk-delete-all-in-one'); ?></p>
                <?php 
                $image_count = 0; // Set image count to 0 if no images found
            }
            return array(
                'image_count' => $image_count,
                'image_urls' => $image_urls
            );
        }

        public function ifbdp_get_options_images() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Instantiate WP_Options_Images class
            $optionsImages = new IFBDP_bulk_delete();
            // Retrieve image count and URLs
            $result = $optionsImages->ifbdp_displayImages();
            $image_count = $result['image_count'];
            $image_urls = $result['image_urls'];
            ?>
            <table class="form-table table-total-number">
                <tr>
                    <th><?php echo esc_html__('Total number images from wp_options table :','bulk-delete-all-in-one'); ?></th>
                    <td><?php echo esc_html($image_count);?></td>     
                    <?php 
                    if($image_urls){ ?>
                        <?php $nonce = wp_create_nonce('delete_all_wp_options_images_nonce'); ?>
                        <input type="hidden" name="delete_all_wp_options_images_nonce" value="<?php echo esc_html($nonce); ?>">
                        <td><input type="submit" name="ifbdp_delete_from_wp_options" id="ifbdp_delete_from_wp_options" value="Delete media" class="button-primary" data-nonce="<?php echo esc_html($nonce);;?>"></td>
                        <?php 
                    } ?>
                </tr>
            </table>
            <div id="ifbdp-wp_options-result"></div>
            <?php 
            if(!empty($image_urls)){ ?>
                <div class="image-listing-wrap ifbdp_options_table">
                    <table border="1" class="wp-list-table widefat striped table-view-list image_listing">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('#','bulk-delete-all-in-one'); ?></th>
                                <th><?php echo esc_html__('Option Name','bulk-delete-all-in-one'); ?></th>
                                <th><?php echo esc_html__('Option Value (URLs)','bulk-delete-all-in-one'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i=1;
                            foreach ($image_urls as $image) { ?>
                                <tr>
                                    <td><?php echo esc_html($i);?></td>
                                    <td><?php echo esc_html($image['name']);?></td>
                                    <td><a href="<?php echo esc_url($image['url']);?>" target="_blank"><?php echo esc_url($image['url']);?></a></td>
                                </tr>
                                <?php
                                $i++; 
                            } ?>
                        </tbody>
                    </table>
                </div>
            <?php }
        } 
        // Add this code to your theme's functions.php file or a custom plugin

        public function ifbdp_delete_all_wp_options_images_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Check nonce for security
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'delete_all_wp_options_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce', 'bulk-delete-all-in-one') );
                wp_die();
            }
            global $wpdb;            
            // Fetch options that likely contain image URLs
            $sql = "SELECT option_name, option_value FROM {$wpdb->options}
                    WHERE option_value LIKE '%.jpg%'
                    OR option_value LIKE '%.jpeg%'
                    OR option_value LIKE '%.png%'
                    OR option_value LIKE '%.gif%'";
            $imageOptions = $wpdb->get_results($sql);
            if (empty($imageOptions)) {
                //wp_send_json_success('No image options found to update.', 200);
                wp_send_json_success( array( 'message' => esc_html__('No image options found to update.', 'bulk-delete-all-in-one') ) );
            }
            foreach ($imageOptions as $option) {
                // Process each option_value to remove image URLs
                // This example assumes the URLs are directly in the text and not serialized
                $cleanedValue = preg_replace('/https?:\/\/[^ ]+\.(jpg|jpeg|png|gif)/', '', $option->option_value);
                // Update the option with the cleaned value
                $wpdb->update(
                    $wpdb->options,
                    ['option_value' => $cleanedValue], // New value
                    ['option_name' => $option->option_name] // Condition
                );
            }
            wp_send_json_success( array( 'message' => esc_html__('All specified images removed from wp_options table.', 'bulk-delete-all-in-one') ) );
            wp_die(); // This is required to terminate immediately and return a proper response
        }
       
        public function ifbdp_delete_media_by_author_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify nonce for security
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'delete_media_nonce' ) ){
                wp_send_json_error( esc_html__('Nonce verification failed!', 'bulk-delete-all-in-one') );
                wp_die();
            }
            global $wpdb;
            // Retrieve author ID from the form submission
            $author_id = isset($_POST['author_id']) ? intval($_POST['author_id']) : 0;
            if ($author_id > 0) {
                // Prepare the SQL query to select attachments by author ID
                $query = $wpdb->prepare("
                    SELECT ID 
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND post_author = %d",
                    $author_id
                );
                // Execute the query
                $attachments = $wpdb->get_results($query);
                // Check if any attachments found for the author
                if (!empty($attachments)) {
                    // Loop through each attachment and delete it
                    foreach ($attachments as $attachment) {
                        if (wp_delete_attachment($attachment->ID, true) === false) {
                            wp_send_json_error( array(
                                'message' => esc_html__('Error while deleting attachment','bulk-delete-all-in-one')
                            ) );
                            wp_die();
                        }
                    }
                    // Send success message if all attachments are deleted successfully
                    wp_send_json_success( esc_html__('All media files deleted successfully.', 'bulk-delete-all-in-one') );
                    wp_die();
                } else {
                    // No attachments found for the selected author
                    wp_send_json_error( esc_html__('No media files found for the selected author.','bulk-delete-all-in-one') );
                    wp_die();
                }
            } else {
                // Invalid author ID
                wp_send_json_error( esc_html__('Invalid author ID.','bulk-delete-all-in-one') );
                wp_die();
            }
        }
       
        public function ifbdp_delete_media_by_month_year_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify nonce for security
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'delete_media_by_month_year_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid Nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Retrieve form data
            $month_year = isset($_POST['ifbdp_month_year']) ? sanitize_text_field($_POST['ifbdp_month_year']) : ''; 
            // Perform deletion logic based on the selected month-year
            if (!empty($month_year)) {
                global $wpdb;
                // Generate the start and end date range for the selected month-year
                $start_date = date('Y-m-01 00:00:00', strtotime($month_year));
                $end_date = date('Y-m-t 23:59:59', strtotime($month_year));
                // Retrieve attachment IDs and file paths for attachments uploaded within the specified month-year
                $attachments = $wpdb->get_results($wpdb->prepare("
                    SELECT ID, meta_value
                    FROM $wpdb->posts 
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE post_type = 'attachment' 
                    AND post_date >= %s 
                    AND post_date <= %s",
                    $start_date,
                    $end_date
                ));
                // Delete attachments and associated files
                foreach ($attachments as $attachment) {
                    // Delete attachment from database
                    wp_delete_attachment($attachment->ID, true);
                    
                    // Retrieve file path
                    $file_path = get_attached_file($attachment->ID);

                    // Delete file from uploads folder
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                // Send success message
                wp_send_json_success( esc_html__('Media for the selected month-year deleted successfully.','bulk-delete-all-in-one') );
            } else {
                // Send error message if month-year is not provided
                wp_send_json_error( esc_html__('Invalid month-year.','bulk-delete-all-in-one') );
            }
            // Terminate the script
            wp_die();
        }

        public function ifbdp_delete_images_between_dates_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'date_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            $from_date = isset($_POST['from_date']) ? sanitize_text_field(wp_unslash($_POST['from_date'])) : '';
            $to_date = isset($_POST['to_date']) ? sanitize_text_field(wp_unslash($_POST['to_date'])) : '';
            global $wpdb;
            // Retrieve attachment IDs and file paths for images between selected dates
            $attachments = $wpdb->get_results($wpdb->prepare("
                SELECT ID, meta_value
                FROM $wpdb->posts 
                LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                WHERE post_type = 'attachment' 
                AND post_date BETWEEN %s AND %s",
                $from_date . ' 00:00:00',
                $to_date . ' 23:59:59'
            ));
            // Delete images and associated files
            foreach ($attachments as $attachment) {
                // Delete attachment from database
                wp_delete_attachment($attachment->ID, true);                
                // Retrieve file path
                $file_path = get_attached_file($attachment->ID);
                // Delete file from uploads folder
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            echo esc_html__('Images between selected dates and associated files deleted successfully.','bulk-delete-all-in-one');
            wp_die();
        }

        public function ifbdp_get_dates_image_urls_callback() {

            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }

            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'date_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }

            $from_date = isset($_POST['from_date']) ? sanitize_text_field(wp_unslash($_POST['from_date'])) : '';
            $to_date = isset($_POST['to_date']) ? sanitize_text_field(wp_unslash($_POST['to_date'])) : '';
            global $wpdb;
            // Retrieve attachment IDs and file paths for images between selected dates
            $attachments = $wpdb->get_results($wpdb->prepare("
                SELECT ID, meta_value
                FROM $wpdb->posts 
                LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                WHERE post_type = 'attachment' 
                AND post_date BETWEEN %s AND %s",
                $from_date . ' 00:00:00',
                $to_date . ' 23:59:59'
            ));
            // Initialize an array to store unique image URLs
            $unique_image_urls = array();
            // Loop through the retrieved attachments and extract unique image URLs
            foreach ($attachments as $attachment) {
                // Get the image URL using attachment ID
                $image_url = wp_get_attachment_url($attachment->ID);
                // Add the image URL to the array if it's not already present
                if (!in_array($image_url, $unique_image_urls)) {
                    $unique_image_urls[] = esc_url($image_url);
                }
            }
            // Send a JSON response with the unique image URLs
            wp_send_json_success($unique_image_urls);
            wp_die();
        }

        public function ifbdp_delete_all_unattached_images_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify nonce for security
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'delete_all_unattached_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            global $wpdb;            
            // Get all attachment IDs that are not attached to any post
            $unattached_attachment_ids = $wpdb->get_col("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'attachment' 
                AND post_parent = 0
            ");            
            // Delete unattached attachments from the database and media library
            foreach ($unattached_attachment_ids as $attachment_id) {
                // Retrieve file path
                $file_path = get_attached_file($attachment_id);                
                // Delete attachment from database
                wp_delete_attachment($attachment_id, true);                
                // Delete file from uploads folder
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }            
            echo esc_html__('All unattached images and associated files deleted successfully.','bulk-delete-all-in-one');
            wp_die();
        }

        public function ifbdp_delete_all_attached_images_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
           
            // Verify nonce for security
            if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'delete_all_attached_images_nonce')) {
                wp_send_json_error(__('Invalid nonce', 'bulk-delete-all-in-one'));
            }

            global $wpdb;            
            // Get all attachment IDs that are attached to any post
            $attached_attachment_ids = $wpdb->get_col("
                SELECT ID
                FROM $wpdb->posts
                WHERE post_type = 'attachment' 
                AND post_parent != 0
            ");            
            // Delete attached attachments from the database and media library
            foreach ($attached_attachment_ids as $attachment_id) {
                // Retrieve file path
                $file_path = get_attached_file($attachment_id);
                // Delete attachment from database
                wp_delete_attachment($attachment_id, true);                
                // Delete file from uploads folder
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }            
            wp_send_json_success( esc_html__('All attached images and associated files deleted successfully.','bulk-delete-all-in-one') );         
            wp_die();
        }

        public function ifbdp_delete_media_by_year_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify nonce for security
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'year_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Retrieve the selected year from the AJAX request
            $selected_year = isset($_POST['year']) ? intval($_POST['year']) : 0;
            if ($selected_year > 0) {
                global $wpdb;                
                // Prepare the start and end date range for the selected year
                $start_date = $selected_year . '-01-01 00:00:00';
                $end_date = $selected_year . '-12-31 23:59:59';                
                // Retrieve file paths of attachments within the specified date range
                $attachments = $wpdb->get_results($wpdb->prepare("
                    SELECT ID, meta_value
                    FROM $wpdb->posts 
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE post_type = 'attachment' 
                    AND post_date >= %s 
                    AND post_date <= %s",
                    $start_date,
                    $end_date
                ));
                if ($attachments) {
                    foreach ($attachments as $attachment) {
                        // Delete attachment from database
                        wp_delete_attachment($attachment->ID, true);
                        // Delete file from uploads folder
                        $file_path = get_attached_file($attachment->ID);
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }                    
                    echo esc_html__('Media for the selected year deleted successfully.','bulk-delete-all-in-one');
                } else {                   
                    echo esc_html__('No media found for the selected year.','bulk-delete-all-in-one');
                }
            } else {                
                echo esc_html__('Invalid year.','bulk-delete-all-in-one'); 
            }
            wp_die();
        }

        public function ifbdp_delete_all_images_callback() {
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'delete_all_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Ensure the user has the capability
            if (!current_user_can('manage_options')) {
                wp_send_json_error( esc_html__('You do not have sufficient permissions to perform this action.','bulk-delete-all-in-one') );
                wp_die();
            }
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => 'any'
            ));
            foreach ($attachments as $attachment) {
                // Delete files from uploads directory
                wp_delete_attachment($attachment->ID, true);
            }
            wp_send_json_success( esc_html__('All images have been successfully deleted.','bulk-delete-all-in-one') );
            wp_die();
        }
      
        // Callback function to get image URLs
        function ifbdp_get_image_urls_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Correct the nonce field name in the verification
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'delete_all_images_nonce' ) ) {
                wp_send_json_error( 'Invalid nonce' );
                wp_die();
            }
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => 'any'
            ));
            $image_urls = array();
            foreach ($attachments as $attachment) {
                $image_urls[] = esc_url(wp_get_attachment_url($attachment->ID));
            }
            // Return JSON response
            wp_send_json_success($image_urls);
            wp_die(); // Always include this at the end of your AJAX callback function.
        }

        // Display settings field when plugin acive
        public function ifbdp_addPluginActionLinks($links) {
            $settingsLink = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=iflair-bulk-delete-settings')) . '">' . esc_html__('Settings', 'bulk-delete-all-in-one') . '</a>';
            array_unshift($links, $settingsLink);
            return $links;
        }

        public function ifbdp_loadTextdomain() {
            load_plugin_textdomain('bulk-delete-all-in-one', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        public function ifbdp_download_all_images() {
            if ( current_user_can( 'manage_options' ) ) { // Check for permission

                // Correct the nonce field name in the verification
                if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'delete_all_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                    wp_die();
                }

                $upload_dir = wp_upload_dir(); // Get upload directory
                $images = get_posts( array( 'post_type' => 'attachment', 'numberposts' => -1 ) ); // Retrieve all attachments
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'Images') . '.zip';
                if ( $zip->open( $zip_filename, ZipArchive::CREATE ) !== TRUE ) {
                    wp_die ( esc_html__('Could not create archive', 'bulk-delete-all-in-one') );
                }
                foreach ( $images as $image ) {
                    $file_path = get_attached_file( $image->ID );
                    if ( file_exists( $file_path ) ) {
                        $relative_path = str_replace( $upload_dir['basedir'] . '/', '', $file_path );
                        $zip->addFile( $file_path, $relative_path );
                    }
                }
                $zip->close();
                // Download the created zip file
                header( 'Content-Type: application/zip' );
                header( 'Content-Disposition: attachment; filename="all_images.zip"' );
                header( 'Content-Length: ' . filesize( $zip_filename ) );
                readfile( $zip_filename );
                unlink( $zip_filename ); // Delete file after sending it to the user
                exit;
            }
        }

        public function ifbdp_download_attached_images() {
            if ( current_user_can( 'manage_options' ) ) { // Check for permission

                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'delete_all_attached_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                    wp_die();
                }

                $args = array(
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'post_status'    => 'inherit',
                    'posts_per_page' => -1,
                    'post_parent__not_in' => array(0) // Ensure attachment is attached to a post/page
                );
                $images = get_posts( $args );
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'Images') . '.zip';
                if ( $zip->open( $zip_filename, ZipArchive::CREATE ) !== TRUE ) {
                    wp_die ( esc_html__('Could not create archive', 'bulk-delete-all-in-one') );
                }
                foreach ( $images as $image ) {
                    $file_path = get_attached_file( $image->ID );
                    $file_name = basename( $file_path );
                    if ( file_exists( $file_path ) ) {
                        $zip->addFile( $file_path, $file_name );
                    }
                }
                $zip->close();
                // Download the created zip file
                header( 'Content-Type: application/zip' );
                header( 'Content-Disposition: attachment; filename="attached_images.zip"' );
                header( 'Content-Length: ' . filesize( $zip_filename ) );
                readfile( $zip_filename );
                unlink( $zip_filename ); // Delete file after sending it to the user
                wp_die();
            }
        }

        function ifbdp_get_attached_image_urls_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }

            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'delete_all_attached_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Define query arguments to retrieve attached images
            $args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent__not_in' => array(0) // Ensure attachment is attached to a post/page
            );
            // Fetch attached images using WP_Query
            $attachments_query = new WP_Query($args);
            // Initialize an array to store image URLs
            $image_urls = array();
            // Loop through each attached image and retrieve its URL
            if ($attachments_query->have_posts()) {
                while ($attachments_query->have_posts()) {
                    $attachments_query->the_post();
                    $image_urls[] = esc_url(wp_get_attachment_url(get_the_ID()));
                }
            }
            // Restore global post data
            wp_reset_postdata();
            // Return JSON response with image URLs
            wp_send_json_success($image_urls);
            wp_die(); // Always include this at the end of your AJAX callback function.
        }

        public function ifbdp_download_unattached_images() {
            if ( current_user_can( 'manage_options' ) ) { // Check for permission
                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'delete_all_unattached_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                    wp_die();
                }
                $args = array(
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'post_status'    => 'inherit',
                    'posts_per_page' => -1,
                    'post_parent'    => 0 // Only retrieve unattached images
                );
                $images = get_posts( $args );
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'UnattachedImages') . '.zip';
                if ( $zip->open( $zip_filename, ZipArchive::CREATE ) !== TRUE ) {
                    wp_die ( esc_html__('Could not create archive', 'bulk-delete-all-in-one') );
                }
                foreach ( $images as $image ) {
                    $file_path = get_attached_file( $image->ID );
                    $file_name = basename( $file_path );
                    if ( file_exists( $file_path ) ) {
                        $zip->addFile( $file_path, $file_name );
                    }
                }
                $zip->close();
                // Download the created zip file
                header( 'Content-Type: application/zip' );
                header( 'Content-Disposition: attachment; filename="unattached_images.zip"' );
                header( 'Content-Length: ' . filesize( $zip_filename ) );
                readfile( $zip_filename );
                unlink( $zip_filename ); // Delete file after sending it to the user
                wp_die();
            }
        }

        function ifbdp_get_unattached_image_urls_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'delete_all_unattached_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                    wp_die();
                }
            // Define query arguments to retrieve unattached images
            $args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0 // Only retrieve unattached images
            );
            // Fetch unattached images using WP_Query
            $attachments_query = new WP_Query($args);
            // Initialize an array to store image URLs
            $image_urls = array();
            // Loop through each unattached image and retrieve its URL
            if ($attachments_query->have_posts()) {
                while ($attachments_query->have_posts()) {
                    $attachments_query->the_post();
                    $image_urls[] = esc_url(wp_get_attachment_url(get_the_ID()));
                }
            }
            // Restore global post data
            wp_reset_postdata();
            // Return JSON response with image URLs
            wp_send_json_success($image_urls);
            wp_die(); // Always include this at the end of your AJAX callback function.
        }

        // Callback function
        public function ifbdp_download_images_between_dates_callback() { 
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die();
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'date_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }  
            global $wpdb;
            $from_date = isset($_POST['from_date']) ? sanitize_text_field(wp_unslash($_POST['from_date'])) : '';

            $to_date = isset($_POST['to_date']) ? sanitize_text_field(wp_unslash($_POST['to_date'])) : '';

            $args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0, // Only retrieve unattached images
                'date_query'     => array(
                    'after'     => $from_date,
                    'before'    => $to_date,
                    'inclusive' => true,
                ),
            );
            $query = new WP_Query($args);
            $images = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $file_path = get_attached_file(get_the_ID());
                    if ($file_path) {
                        $images[] = $file_path;
                    }
                }
                wp_reset_postdata();
            } else {
                // No images found for the specified date range
                wp_send_json_error( esc_html__('No images found for the specified date range.','bulk-delete-all-in-one') );
            }
            if (!empty($images)) {
                // Creating ZIP file
                $zip = new ZipArchive();
                $zip_filename = tempnam(sys_get_temp_dir(), 'images') . '.zip';
                if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
                    foreach ($images as $file) {
                        // Add files to zip if they exist
                        if (file_exists($file)) {
                            $zip->addFile($file, basename($file));
                        } else {
                            //error_log('Error: File not found: ' . $file);
                        }
                    }
                    $zip->close();
                    // Download ZIP file
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="downloaded_images.zip"');
                    header('Content-Length: ' . filesize($zip_filename));
                    readfile($zip_filename);
                    unlink($zip_filename); // Delete file after download
                    wp_die();
                } else {
                    // Failed to create a ZIP file
                    wp_send_json_error( esc_html__('Could not create a zip file.','bulk-delete-all-in-one') );
                }
            } else {
                // No images found
                wp_send_json_error( esc_html__('No images found for the specified date range.','bulk-delete-all-in-one') );
            }
        }
        
        public function ifbdp_download_images_by_month_year() { 

            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'monthswise_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }   
            // Sanitize and extract selected month and year
            $monthYearValue = isset($_POST['monthYearValue']) ? sanitize_text_field(wp_unslash($_POST['monthYearValue'])) : '';
            $year = date( 'Y', strtotime( $monthYearValue ) );
            $month = date( 'm', strtotime( $monthYearValue ) );
            // Set up arguments for the query to retrieve attachments uploaded in the selected month and year
            $args = array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'year'           => $year,
                'monthnum'       => $month,
            );
            // Retrieve attachments uploaded in the selected month and year
            $attachments = get_posts( $args );
            // Create a new zip archive
            $zip = new ZipArchive();
            $zip_filename = tempnam( sys_get_temp_dir(), 'month_year_images' ) . '.zip';
            if ( $zip->open( $zip_filename, ZipArchive::CREATE ) !== TRUE ) {
                wp_send_json_error( esc_html__('Could not create archive','bulk-delete-all-in-one') );
            }
            // Add each attachment to the zip archive
            foreach ( $attachments as $attachment ) {
                $file_path = get_attached_file( $attachment->ID );
                $file_name = basename( $file_path );
                if ( file_exists( $file_path ) ) {
                    $zip->addFile( $file_path, $file_name );
                }
            }
            $zip->close();
            // Send the zip file as response
            header( 'Content-Type: application/zip' );
            header( 'Content-Disposition: attachment; filename="month_year_images.zip"' );
            header( 'Content-Length: ' . filesize( $zip_filename ) );
            readfile( $zip_filename );
            // Delete the zip file after sending it to the user
            unlink( $zip_filename );
            // Exit to prevent any further output
            wp_die();
        }

        public function ifbdp_download_media_by_years() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            // Verify nonce
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'year_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            } 
            // Sanitize and extract selected year
            $yearValue = isset($_POST['yearValue']) ? sanitize_text_field(wp_unslash($_POST['yearValue'])) : '';
            // Set up arguments for the query to retrieve attachments uploaded in the selected year
            $args = array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'year'           => $yearValue,
            );
            // Retrieve attachments uploaded in the selected year
            $attachments = get_posts($args);
            // Create a new zip archive
            $zip = new ZipArchive();
            $zip_filename = tempnam(sys_get_temp_dir(), 'year_images') . '.zip';
            if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
                wp_send_json_error( esc_html__('Could not create archive','bulk-delete-all-in-one') );
                wp_die();
            }
            // Add each attachment to the zip archive
            foreach ($attachments as $attachment) {
                $file_path = get_attached_file($attachment->ID);
                $file_name = basename($file_path);
                if (file_exists($file_path)) {
                    $zip->addFile($file_path, $file_name);
                }
            }
            $zip->close();
            // Send the zip file as response
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="year_images.zip"');
            header('Content-Length: ' . filesize($zip_filename));
            readfile($zip_filename);
            // Delete the zip file after sending it to the user
            unlink($zip_filename);
            // Exit to prevent any further output
            wp_die();
        }

        public function ifbdp_download_author_images_callback() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'author_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            } 
            // Sanitize and extract author ID
            $author_id = isset($_POST['author_id']) ? intval($_POST['author_id']) : 0;
            // Prepare SQL query to count attachments uploaded by the specified author
            global $wpdb;
            $count_query = $wpdb->prepare("
                SELECT COUNT(*) 
                FROM $wpdb->posts 
                WHERE post_type = 'attachment' 
                AND post_author = %d",
                $author_id
            );
            // Retrieve the count of attachments uploaded by the specified author
            $attachment_count = $wpdb->get_var($count_query);
            // Prepare SQL query to fetch attachments uploaded by the specified author
            $attachments_query = $wpdb->prepare("
                SELECT ID
                FROM $wpdb->posts 
                WHERE post_type = 'attachment' 
                AND post_author = %d",
                $author_id
            );
            // Retrieve attachments uploaded by the specified author
            $attachments = $wpdb->get_results($attachments_query);
            // Create a new zip archive
            $zip = new ZipArchive();
            $zip_filename = tempnam(sys_get_temp_dir(), 'author_images') . '.zip';
            if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
                wp_send_json_error( esc_html__('Could not create archive','bulk-delete-all-in-one') );
            }
            // Add each attachment to the zip archive
            foreach ($attachments as $attachment) {
                $file_path = get_attached_file($attachment->ID);
                $file_name = basename($file_path);
                if (file_exists($file_path)) {
                    $zip->addFile($file_path, $file_name);
                }
            }
            $zip->close();
            // Send the zip file as response along with the attachment count
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="author_images.zip"');
            header('Content-Length: ' . filesize($zip_filename));
            header('Attachment-Count: ' . $attachment_count); // Add attachment count as a custom header
            readfile($zip_filename);
            // Delete the zip file after sending it to the user
            unlink($zip_filename);
            // Exit to prevent any further output
            wp_die();
        }

        // Function to display files and folders with checkboxes and nested structure
        public function ifbdp_display_uploads_with_checkboxes() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'];            
            // Get list of files and directories
            $files = scandir($upload_path);            
            // Display form
            ?>
            <form id="ifbdp-uploads-form">
                <?php wp_nonce_field('ifbdp_folders_nonce', 'ifbdp_folders_nonce'); ?>
                <p class="ifbdp-sucess_msg" style="display:none;"><?php echo esc_html__('Selected folders images are deleted.', 'bulk-delete-all-in-one'); ?></p>
                <p class="ifbdp-error_msg" style="display:none;"><?php echo esc_html__('Error while images delete.', 'bulk-delete-all-in-one'); ?></p>
                <div class="ifbdp-checkbox-main">
                <?php
                foreach ($files as $file) { 
                    // Skip . and ..
                    if ($file == '.' || $file == '..') continue;                
                    // If directory, add trailing slash
                    $file_path = $upload_path . '/' . $file;
                    if (is_dir($file_path)) {
                        $has_inner_folders = count(glob($file_path . '/*', GLOB_ONLYDIR)) > 0;
                    } else {
                        $has_inner_folders = false;
                    }                    
                    // Remove trailing slash if it's a directory
                    $file_value = rtrim($file, '/');                    
                    // Display checkbox
                    ?>
                    <div class="ifbdp-checkbox-wrap">
                    <label>
                        <input type="checkbox" name="selected_files[]" value="<?php echo esc_attr($file_value); ?>" class="ifbdp-file-checkbox">
                        <?php echo esc_html($file_value); ?>
                    </label>
                    <?php
                    // Display inner folders with checkboxes (initially hidden)
                    if ($has_inner_folders) { ?>
                        <div class="ifbdp-inner-folders" style="display: none;">
                            <?php 
                            $inner_folders = scandir($file_path);
                            foreach ($inner_folders as $inner_folder) {
                                if ($inner_folder != '.' && $inner_folder != '..' && is_dir($file_path . '/' . $inner_folder)) {
                                    ?>
                                    <label>
                                        <input type="checkbox" name="selected_files[]" value="<?php echo esc_attr($file_value . '/' . $inner_folder); ?>" class="ifbdp-file-checkbox">
                                        <?php echo esc_html($inner_folder); ?>
                                    </label>
                                <?php
                                }
                            } ?>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                } ?>
                </div>
                <input type="submit" value="Delete" id="ifbdp_chk_delete_btn" class="button-primary">
            </form>
            <?php
        }

        public function ifbdp_get_monthswise_image_urls() {
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if (isset($_POST['ifbdp_month_year'])) {

                if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'monthswise_images_nonce' ) ) {
                    wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                    wp_die();
                } 
                global $wpdb;                
                // Get the month and year from the selected value
                $selected_date = isset($_POST['ifbdp_month_year']) ? sanitize_text_field(wp_unslash($_POST['ifbdp_month_year'])) : '';
                $year = date('Y', strtotime($selected_date));
                $month = date('m', strtotime($selected_date));
                // Construct SQL query to retrieve attachment IDs for images uploaded in the selected month and year
                $attachment_ids = $wpdb->get_col($wpdb->prepare("
                    SELECT ID
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND YEAR(post_date) = %d
                    AND MONTH(post_date) = %d", $year, $month)
                );
                // Initialize an array to store image URLs
                $image_urls = array();
                // Loop through the retrieved attachment IDs and get the image URLs
                foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_url($attachment_id);
                    if ($image_url) {
                        $image_urls[] = esc_url($image_url);
                    }
                }
                // Send a JSON response with the image URLs
                wp_send_json_success($image_urls);
            }
            wp_die();
        }

        function ifbdp_show_media_urls_by_year() {   
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'year_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Retrieve the selected year from the AJAX request
            $selected_year = isset($_POST['year']) ? intval($_POST['year']) : 0;
            if ($selected_year > 0) {
                global $wpdb;              
                // Prepare the start and end date range for the selected year
                $start_date = $selected_year . '-01-01 00:00:00';
                $end_date = $selected_year . '-12-31 23:59:59';       
                // Retrieve image URLs of attachments within the specified date range
                $attachments = $wpdb->get_results($wpdb->prepare("
                    SELECT guid
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND post_mime_type LIKE 'image%'
                    AND post_date >= %s 
                    AND post_date <= %s",
                    $start_date,
                    $end_date
                ));
                // Initialize an array to store image URLs
                $image_urls = array();
                // Loop through the retrieved attachments and extract image URLs
                foreach ($attachments as $attachment) {
                    $image_urls[] = esc_url($attachment->guid);
                }
                // Send a JSON response with the image URLs
                wp_send_json_success($image_urls);
            } else {
                // Send error response for invalid year
                wp_send_json_error( esc_html__('Invalid year.', 'bulk-delete-all-in-one') );
            }
            // Always include wp_die() at the end of the AJAX callback function
            wp_die();
        }

        public function ifbdp_show_media_by_author_callback() {   
            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }
            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'author_images_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Retrieve author ID from the form submission
            $author_id = isset($_POST['authorId']) ? intval($_POST['authorId']) : 0;            
            if ($author_id > 0) {
                global $wpdb;
                // Prepare the SQL query to select attachments by author ID
                $query = $wpdb->prepare("
                    SELECT guid 
                    FROM $wpdb->posts 
                    WHERE post_type = 'attachment' 
                    AND post_author = %d",
                    $author_id
                );
                // Execute the query to get media URLs
                $media_urls = $wpdb->get_col($query);
                // Send a JSON response with the media URLs
                // Check if any media URLs were found
                if ( empty($media_urls) ) {
                    wp_send_json_error( esc_html__('No media found for the specified author.', 'bulk-delete-all-in-one') );
                }

                // Escape URLs for safety
                $media_urls = array_map('esc_url', $media_urls);

                // Send a JSON response with the media URLs
                wp_send_json_success($media_urls);
            } else {
                // Send an error response for invalid author ID
                wp_send_json_error( esc_html__('Invalid author ID.', 'bulk-delete-all-in-one') );
            }
        } 

        public function ifbdp_delete_selected_files_callback() {

            // Ensure the user has the necessary permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(esc_html__('You do not have sufficient permissions to perform this action.', 'bulk-delete-all-in-one'));
                wp_die(); // Terminate script execution
            }

            if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'ifbdp_folders_nonce' ) ) {
                wp_send_json_error( esc_html__('Invalid nonce','bulk-delete-all-in-one') );
                wp_die();
            }
            // Retrieve selected files
            $selectedFiles = isset($_POST['files']) ? $_POST['files'] : array();// Arrays to store selected years and folders
            $sanitizedFiles = array_map('sanitize_text_field', $selectedFiles);
            $selectedYears = array();
            $selectedFolders = array();
            $filesDeleted = false; // Flag to track if any files were deleted
            // Categorize selected files into years and folders
            foreach ($selectedFiles as $file) {
                if (preg_match('/^\d{4}\/\d{2}$/', $file)) {
                    // File is in the format 'yyyy/mm', consider it as a month
                    $selectedFolders[] = $file;
                } elseif (preg_match('/^\d{4}$/', $file)) {
                    // File is in the format 'yyyy', consider it as a year
                    $selectedYears[] = $file;
                }
            }
            // Loop through each selected year and delete files and folders accordingly
            foreach ($selectedYears as $year) {
                // Define the directory path for the selected year
                $yearDirectory = WP_CONTENT_DIR . '/uploads/' . $year;
                // Check if the year directory exists
                if (is_dir($yearDirectory)) {
                    // Loop through each month directory within the year
                    $months = glob($yearDirectory . '/*', GLOB_ONLYDIR);
                    foreach ($months as $monthDirectory) {
                        // Open the month directory
                        $monthDirHandle = opendir($monthDirectory);
                        // Loop through each file in the month directory
                        while (($monthFile = readdir($monthDirHandle)) !== false) {
                            // Exclude . and .. directories
                            if ($monthFile != '.' && $monthFile != '..') {
                                // Construct the full path of the file
                                $filePath = $monthDirectory . '/' . $monthFile;
                                // Attempt to delete the file from the file system
                                if (unlink($filePath)) {
                                    $filesDeleted = true;
                                    // Convert file path to URL
                                    $fileUrl = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $filePath);
                                    // Get attachment ID
                                    $attachment_id = attachment_url_to_postid($fileUrl);
                                    if ($attachment_id) {
                                        // Delete from the media library
                                        wp_delete_attachment($attachment_id, true); // True parameter also deletes media file permanently
                                        //echo "Deleted from media library and file system: $filePath\n";
                                    } else {
                                        //echo "Attachment ID not found for file: $filePath\n";
                                    }
                                } else {
                                    // File deletion from the file system failed
                                    //echo "Failed to delete from file system: $filePath\n";
                                }
                            }
                        }
                        // Close the month directory handle
                        closedir($monthDirHandle);
                        // Check if the month directory is empty and delete it
                        if (count(glob($monthDirectory . '/*')) === 0) {
                            rmdir($monthDirectory);
                            //echo "Deleted empty month directory: $monthDirectory\n";
                        }
                    }
                    // Check if the year directory is empty and delete it
                    if (count(glob($yearDirectory . '/*')) === 0) {
                        rmdir($yearDirectory);
                       // echo "Deleted empty year directory: $yearDirectory\n";
                    }
                }
            }
            // Loop through each selected folder and delete files accordingly
            foreach ($selectedFolders as $folder) {
                // Define the directory path where images are stored
                $directory = WP_CONTENT_DIR . '/uploads/' . $folder;
                // Check if the directory exists
                if (is_dir($directory)) {
                    // Open the directory
                    $dirHandle = opendir($directory);
                    // Loop through each file in the directory
                    while (($file = readdir($dirHandle)) !== false) {
                        // Exclude . and .. directories
                        if ($file != '.' && $file != '..') {
                            // Construct the full path of the file
                            $filePath = $directory . '/' . $file;
                            // Check if the file is an image
                            if (wp_check_filetype($filePath)['type']) {
                                // Attempt to delete the file from the file system
                                if (unlink($filePath)) {
                                    $filesDeleted = true;
                                    // Convert file path to URL
                                    $fileUrl = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $filePath);
                                    // Get attachment ID
                                    $attachment_id = attachment_url_to_postid($fileUrl);
                                    if ($attachment_id) {
                                        // Delete from the media library
                                        wp_delete_attachment($attachment_id, true); // True parameter also deletes media file permanently
                                        //echo "Deleted from media library and file system: $filePath\n";
                                    } else {
                                        //echo "Attachment ID not found for file: $filePath\n";
                                    }
                                } else {
                                    // File deletion from the file system failed
                                    //echo "Failed to delete from file system: $filePath\n";
                                }
                            }
                        }
                    }
                    // Close the directory handle
                    closedir($dirHandle);
                    // Check if the directory is empty and delete it
                    if (count(glob($directory . '/*')) === 0) {
                        rmdir($directory);
                        //echo "Deleted empty directory: $directory\n";
                    }
                } else {
                    // Directory does not exist
                    //echo "Directory not found: $directory\n";
                }
            }
            // If no files were deleted, echo the message
            if (!empty($filesDeleted)) {
                wp_send_json_success( esc_html__('Your selected images are deleted.', 'bulk-delete-all-in-one') );
            } else {
                wp_send_json_error(esc_html__('No images were deleted.', 'bulk-delete-all-in-one'));
            }
            // It's good practice to exit after echoing the response
            wp_die();
        }   
    }
}