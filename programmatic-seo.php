<?php
/**
 * Plugin Name: Programmatic SEO
 * Description: WordPress Plugin for Programmatic SEO - 1 Domain 1 Service Multi Kota
 * Version: 1.0.0
 * Author: SEO Developer
 * License: GPL v2 or later
 * Text Domain: programmatic-seo
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PSEO_VERSION', '1.0.0');
define('PSEO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PSEO_PLUGIN_URL', plugin_dir_url(__FILE__));

// =====================================================
// CLASS: Main Plugin
// =====================================================
class Programmatic_SEO {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init();
    }
    
    private function init() {
        // Activation/Deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Shortcodes
        add_shortcode('local_business', array($this, 'shortcode_local_business'));
        add_shortcode('business_map', array($this, 'shortcode_business_map'));
        add_shortcode('city_links', array($this, 'shortcode_city_links'));
        add_shortcode('faq_schema', array($this, 'shortcode_faq_schema'));
        
        // Frontend hooks
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        add_action('wp_footer', array($this, 'internal_linking_footer'));
        
        // Schema markup
        add_action('wp_head', array($this, 'schema_markup'));
        
        // AJAX handlers
        add_action('wp_ajax_pseo_get_cities', array($this, 'ajax_get_cities'));
        add_action('wp_ajax_pseo_get_businesses', array($this, 'ajax_get_businesses'));
        add_action('wp_ajax_pseo_import_businesses_csv', array($this, 'ajax_import_businesses_csv'));
        add_action('wp_ajax_pseo_check_duplicate_post', array($this, 'ajax_check_duplicate_post'));
        add_action('wp_ajax_pseo_save_template', array($this, 'ajax_save_template'));
        
        // REST API
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }
    
    // =====================================================
    // ACTIVATION
    // =====================================================
    public function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Cities table
        $sql_cities = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cities (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            province varchar(100) NOT NULL DEFAULT 'Jawa Timur',
            city_name varchar(100) NOT NULL,
            city_slug varchar(100) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY city_slug (city_slug),
            KEY province (province)
        ) $charset_collate;";
        
        // Businesses table
        $sql_businesses = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}businesses (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            city_id bigint(20) UNSIGNED NOT NULL,
            name varchar(255) NOT NULL,
            address text,
            phone varchar(50) DEFAULT NULL,
            whatsapp varchar(50) DEFAULT NULL,
            rating decimal(2,1) DEFAULT 0.0,
            lat decimal(10,8) DEFAULT NULL,
            lng decimal(11,8) DEFAULT NULL,
            description text,
            website varchar(255) DEFAULT NULL,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY city_id (city_id),
            KEY is_active (is_active)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_cities);
        dbDelta($sql_businesses);
        
        // Default options
        add_option('pseo_service_name', 'Jasa AC');
        add_option('pseo_service_slug', 'jasa-ac');
        add_option('pseo_province', 'Jawa Timur');
        add_option('pseo_contact_whatsapp', '');
        add_option('pseo_google_maps_api', '');
        
        // Insert default cities (Jawa Timur)
        $this->insert_default_cities();
        
        flush_rewrite_rules();
    }
    
    private function insert_default_cities() {
        global $wpdb;
        
        $cities = array(
            array('Surabaya', 'surabaya'),
            array('Malang', 'malang'),
            array('Sidoarjo', 'sidoarjo'),
            array('Gresik', 'gresik'),
            array('Mojokerto', 'mojokerto'),
            array('Pasuruan', 'pasuruan'),
            array('Batu', 'batu'),
            array('Blitar', 'blitar'),
            array('Kediri', 'kediri'),
            array('Madiun', 'madiun'),
            array('Probolinggo', 'probolinggo'),
            array('Bondowoso', 'bondowoso'),
            array('Jember', 'jember'),
            array('Banyuwangi', 'banyuwangi'),
            array('Situbondo', 'situbondo'),
            array('Lumajang', 'lumajang'),
            array('Bangkalan', 'bangkalan'),
            array('Sampang', 'sampang'),
            array('Pamekasan', 'pamekasan'),
            array('Sumenep', 'sumenep'),
            array('Tuban', 'tuban'),
            array('Lamongan', 'lamongan'),
            array('Bojonegoro', 'bojonegoro'),
            array('Ngawi', 'ngawi'),
            array('Magetan', 'magetan'),
            array('Ponorogo', 'ponorogo'),
            array('Pacitan', 'pacitan'),
            array('Trenggalek', 'trenggalek'),
            array('Tulungagung', 'tulungagung'),
            array('Nganjuk', 'nganjuk'),
            array('Jombang', 'jombang'),
        );
        
        $province = 'Jawa Timur';
        
        foreach ($cities as $city) {
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}cities WHERE city_slug = %s",
                $city[1]
            ));
            
            if (!$exists) {
                $wpdb->insert(
                    $wpdb->prefix . 'cities',
                    array(
                        'province' => $province,
                        'city_name' => $city[0],
                        'city_slug' => $city[1]
                    )
                );
            }
        }
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    // =====================================================
    // ADMIN MENU
    // =====================================================
    public function admin_menu() {
        add_menu_page(
            'Programmatic SEO',
            'Programmatic SEO',
            'manage_options',
            'programmatic-seo',
            array($this, 'page_dashboard'),
            'dashicons-location-alt',
            25
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'programmatic-seo',
            array($this, 'page_dashboard')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Cities',
            'Cities',
            'manage_options',
            'pseo-cities',
            array($this, 'page_cities')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Businesses',
            'Businesses',
            'manage_options',
            'pseo-businesses',
            array($this, 'page_businesses')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Sitemap',
            'Sitemap Generator',
            'manage_options',
            'pseo-sitemap',
            array($this, 'page_sitemap')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Settings',
            'Settings',
            'manage_options',
            'pseo-settings',
            array($this, 'page_settings')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Import CSV',
            'Import CSV',
            'manage_options',
            'pseo-import',
            array($this, 'page_import_csv')
        );
        
        add_submenu_page(
            'programmatic-seo',
            'Templates',
            'Content Templates',
            'manage_options',
            'pseo-templates',
            array($this, 'page_templates')
        );
    }
    
    // =====================================================
    // ADMIN SCRIPTS
    // =====================================================
    public function admin_scripts($hook) {
        if (strpos($hook, 'pseo') === false && strpos($hook, 'programmatic-seo') === false) {
            return;
        }
        
        wp_enqueue_style('pseo-admin-css', PSEO_PLUGIN_URL . 'assets/admin.css', array(), PSEO_VERSION);
        wp_enqueue_script('pseo-admin-js', PSEO_PLUGIN_URL . 'assets/admin.js', array('jquery'), PSEO_VERSION, true);
        
        wp_localize_script('pseo-admin-js', 'pseo_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pseo_nonce')
        ));
    }
    
    // =====================================================
    // FRONTEND SCRIPTS
    // =====================================================
    public function frontend_scripts() {
        wp_enqueue_style('pseo-frontend-css', PSEO_PLUGIN_URL . 'assets/frontend.css', array(), PSEO_VERSION);
        
        $api_key = get_option('pseo_google_maps_api');
        if ($api_key) {
            wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr($api_key), array(), null, true);
        }
    }
    
    // =====================================================
    // PAGE: Dashboard
    // =====================================================
    public function page_dashboard() {
        global $wpdb;
        
        $total_cities = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}cities");
        $total_businesses = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}businesses");
        $active_businesses = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}businesses WHERE is_active = 1");
        
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        ?>
        <div class="wrap">
            <h1>Programmatic SEO - Dashboard</h1>
            
            <div class="pseo-dashboard-stats">
                <div class="pseo-stat-box">
                    <h3>Total Cities</h3>
                    <p class="pseo-stat-number"><?php echo esc_html($total_cities); ?></p>
                </div>
                <div class="pseo-stat-box">
                    <h3>Total Businesses</h3>
                    <p class="pseo-stat-number"><?php echo esc_html($total_businesses); ?></p>
                </div>
                <div class="pseo-stat-box">
                    <h3>Active Businesses</h3>
                    <p class="pseo-stat-number"><?php echo esc_html($active_businesses); ?></p>
                </div>
            </div>
            
            <div class="pseo-service-info">
                <h2>Current Service Configuration</h2>
                <table class="widefat">
                    <tr>
                        <th>Service Name</th>
                        <td><?php echo esc_html($service_name); ?></td>
                    </tr>
                    <tr>
                        <th>Service Slug</th>
                        <td><?php echo esc_html($service_slug); ?></td>
                    </tr>
                    <tr>
                        <th>Province</th>
                        <td><?php echo esc_html(get_option('pseo_province', 'Jawa Timur')); ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="pseo-shortcode-info">
                <h2>Available Shortcodes</h2>
                <table class="widefat">
                    <tr>
                        <th>Shortcode</th>
                        <th>Description</th>
                        <th>Example</th>
                    </tr>
                    <tr>
                        <td><code>[local_business city="bondowoso"]</code></td>
                        <td>Display business list for a city</td>
                        <td>Shows all businesses in Bondowoso</td>
                    </tr>
                    <tr>
                        <td><code>[business_map city="bondowoso"]</code></td>
                        <td>Display Google Map with business markers</td>
                        <td>Interactive map for the city</td>
                    </tr>
                    <tr>
                        <td><code>[city_links]</code></td>
                        <td>Display links to other cities</td>
                        <td>Auto-generated internal links</td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Settings
    // =====================================================
    public function page_settings() {
        if (isset($_POST['pseo_save_settings'])) {
            check_admin_referer('pseo_settings');
            
            update_option('pseo_service_name', sanitize_text_field($_POST['service_name']));
            update_option('pseo_service_slug', sanitize_title($_POST['service_slug']));
            update_option('pseo_province', sanitize_text_field($_POST['province']));
            update_option('pseo_contact_whatsapp', sanitize_text_field($_POST['contact_whatsapp']));
            update_option('pseo_google_maps_api', sanitize_text_field($_POST['google_maps_api']));
            
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
        
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $province = get_option('pseo_province', 'Jawa Timur');
        $contact_whatsapp = get_option('pseo_contact_whatsapp', '');
        $google_maps_api = get_option('pseo_google_maps_api', '');
        ?>
        <div class="wrap">
            <h1>Programmatic SEO - Settings</h1>
            
            <form method="post">
                <?php wp_nonce_field('pseo_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="service_name">Service Name</label></th>
                        <td>
                            <input type="text" id="service_name" name="service_name" 
                                   value="<?php echo esc_attr($service_name); ?>" class="regular-text">
                            <p class="description">Example: Jasa AC, Jasa Kulkas, Sewa Mobil</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="service_slug">Service Slug</label></th>
                        <td>
                            <input type="text" id="service_slug" name="service_slug" 
                                   value="<?php echo esc_attr($service_slug); ?>" class="regular-text">
                            <p class="description">Used for URL structure. Example: jasa-ac, sewa-mobil</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="province">Province</label></th>
                        <td>
                            <input type="text" id="province" name="province" 
                                   value="<?php echo esc_attr($province); ?>" class="regular-text">
                            <p class="description">Target province for this domain</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="contact_whatsapp">Contact WhatsApp</label></th>
                        <td>
                            <input type="text" id="contact_whatsapp" name="contact_whatsapp" 
                                   value="<?php echo esc_attr($contact_whatsapp); ?>" class="regular-text">
                            <p class="description">Default WhatsApp number for leads (format: 628123456789)</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="google_maps_api">Google Maps API Key</label></th>
                        <td>
                            <input type="text" id="google_maps_api" name="google_maps_api" 
                                   value="<?php echo esc_attr($google_maps_api); ?>" class="regular-text">
                            <p class="description">Required for map embed feature</p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Save Settings', 'primary', 'pseo_save_settings'); ?>
            </form>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Cities (CRUD)
    // =====================================================
    public function page_cities() {
        global $wpdb;
        
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $city_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Handle delete
        if ($action === 'delete' && $city_id) {
            check_admin_referer('delete_city_' . $city_id);
            $wpdb->delete($wpdb->prefix . 'cities', array('id' => $city_id));
            echo '<div class="notice notice-success"><p>City deleted successfully!</p></div>';
            $action = 'list';
        }
        
        // Handle save
        if (isset($_POST['pseo_save_city'])) {
            check_admin_referer('pseo_city');
            
            $data = array(
                'province' => sanitize_text_field($_POST['province']),
                'city_name' => sanitize_text_field($_POST['city_name']),
                'city_slug' => sanitize_title($_POST['city_slug'])
            );
            
            if ($city_id) {
                $wpdb->update($wpdb->prefix . 'cities', $data, array('id' => $city_id));
                echo '<div class="notice notice-success"><p>City updated successfully!</p></div>';
            } else {
                $wpdb->insert($wpdb->prefix . 'cities', $data);
                echo '<div class="notice notice-success"><p>City added successfully!</p></div>';
            }
            $action = 'list';
        }
        
        // Edit/Add form
        if ($action === 'edit' || $action === 'add') {
            $city = $city_id ? $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}cities WHERE id = %d",
                $city_id
            )) : null;
            ?>
            <div class="wrap">
                <h1><?php echo $city_id ? 'Edit City' : 'Add New City'; ?></h1>
                
                <form method="post">
                    <?php wp_nonce_field('pseo_city'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="province">Province</label></th>
                            <td>
                                <input type="text" id="province" name="province" 
                                       value="<?php echo $city ? esc_attr($city->province) : 'Jawa Timur'; ?>" 
                                       class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="city_name">City Name</label></th>
                            <td>
                                <input type="text" id="city_name" name="city_name" 
                                       value="<?php echo $city ? esc_attr($city->city_name) : ''; ?>" 
                                       class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="city_slug">City Slug</label></th>
                            <td>
                                <input type="text" id="city_slug" name="city_slug" 
                                       value="<?php echo $city ? esc_attr($city->city_slug) : ''; ?>" 
                                       class="regular-text" required>
                                <p class="description">URL-friendly name (e.g., surabaya, malang)</p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save City', 'primary', 'pseo_save_city'); ?>
                    <a href="<?php echo admin_url('admin.php?page=pseo-cities'); ?>" class="button">Cancel</a>
                </form>
            </div>
            <?php
            return;
        }
        
        // List view
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        ?>
        <div class="wrap">
            <h1>Cities <a href="<?php echo admin_url('admin.php?page=pseo-cities&action=add'); ?>" class="page-title-action">Add New</a></h1>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Province</th>
                        <th>City Name</th>
                        <th>City Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cities as $city) : ?>
                    <tr>
                        <td><?php echo esc_html($city->id); ?></td>
                        <td><?php echo esc_html($city->province); ?></td>
                        <td><?php echo esc_html($city->city_name); ?></td>
                        <td><?php echo esc_html($city->city_slug); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=pseo-cities&action=edit&id=' . $city->id); ?>">Edit</a> | 
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=pseo-cities&action=delete&id=' . $city->id), 'delete_city_' . $city->id); ?>" 
                               onclick="return confirm('Are you sure? This will also delete all businesses in this city.')" 
                               style="color: #a00;">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Businesses (CRUD with Filter)
    // =====================================================
    public function page_businesses() {
        global $wpdb;
        
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $business_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $filter_city = isset($_GET['filter_city']) ? sanitize_text_field($_GET['filter_city']) : '';
        
        // Handle delete
        if ($action === 'delete' && $business_id) {
            check_admin_referer('delete_business_' . $business_id);
            $wpdb->delete($wpdb->prefix . 'businesses', array('id' => $business_id));
            echo '<div class="notice notice-success"><p>Business deleted successfully!</p></div>';
            $action = 'list';
        }
        
        // Handle save
        if (isset($_POST['pseo_save_business'])) {
            check_admin_referer('pseo_business');
            
            $data = array(
                'city_id' => intval($_POST['city_id']),
                'name' => sanitize_text_field($_POST['name']),
                'address' => sanitize_textarea_field($_POST['address']),
                'phone' => sanitize_text_field($_POST['phone']),
                'whatsapp' => sanitize_text_field($_POST['whatsapp']),
                'rating' => floatval($_POST['rating']),
                'lat' => sanitize_text_field($_POST['lat']),
                'lng' => sanitize_text_field($_POST['lng']),
                'description' => sanitize_textarea_field($_POST['description']),
                'website' => esc_url_raw($_POST['website']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            );
            
            if ($business_id) {
                $wpdb->update($wpdb->prefix . 'businesses', $data, array('id' => $business_id));
                echo '<div class="notice notice-success"><p>Business updated successfully!</p></div>';
            } else {
                $wpdb->insert($wpdb->prefix . 'businesses', $data);
                echo '<div class="notice notice-success"><p>Business added successfully!</p></div>';
            }
            $action = 'list';
        }
        
        // Edit/Add form
        if ($action === 'edit' || $action === 'add') {
            $business = $business_id ? $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}businesses WHERE id = %d",
                $business_id
            )) : null;
            
            $cities = $wpdb->get_results("SELECT id, city_name FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
            ?>
            <div class="wrap">
                <h1><?php echo $business_id ? 'Edit Business' : 'Add New Business'; ?></h1>
                
                <form method="post">
                    <?php wp_nonce_field('pseo_business'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="city_id">City</label></th>
                            <td>
                                <select id="city_id" name="city_id" required>
                                    <option value="">Select City</option>
                                    <?php foreach ($cities as $city) : ?>
                                    <option value="<?php echo esc_attr($city->id); ?>" 
                                        <?php selected($business && $business->city_id == $city->id); ?>>
                                        <?php echo esc_html($city->city_name); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="name">Business Name</label></th>
                            <td>
                                <input type="text" id="name" name="name" 
                                       value="<?php echo $business ? esc_attr($business->name) : ''; ?>" 
                                       class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="address">Address</label></th>
                            <td>
                                <textarea id="address" name="address" rows="3" class="large-text"><?php echo $business ? esc_textarea($business->address) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="phone">Phone</label></th>
                            <td>
                                <input type="text" id="phone" name="phone" 
                                       value="<?php echo $business ? esc_attr($business->phone) : ''; ?>" 
                                       class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="whatsapp">WhatsApp</label></th>
                            <td>
                                <input type="text" id="whatsapp" name="whatsapp" 
                                       value="<?php echo $business ? esc_attr($business->whatsapp) : ''; ?>" 
                                       class="regular-text">
                                <p class="description">Format: 628123456789</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="rating">Rating</label></th>
                            <td>
                                <input type="number" id="rating" name="rating" step="0.1" min="0" max="5"
                                       value="<?php echo $business ? esc_attr($business->rating) : '0'; ?>" 
                                       class="small-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="lat">Latitude</label></th>
                            <td>
                                <input type="text" id="lat" name="lat" 
                                       value="<?php echo $business ? esc_attr($business->lat) : ''; ?>" 
                                       class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="lng">Longitude</label></th>
                            <td>
                                <input type="text" id="lng" name="lng" 
                                       value="<?php echo $business ? esc_attr($business->lng) : ''; ?>" 
                                       class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="description">Description</label></th>
                            <td>
                                <textarea id="description" name="description" rows="5" class="large-text"><?php echo $business ? esc_textarea($business->description) : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="website">Website</label></th>
                            <td>
                                <input type="url" id="website" name="website" 
                                       value="<?php echo $business ? esc_attr($business->website) : ''; ?>" 
                                       class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="is_active">Active</label></th>
                            <td>
                                <input type="checkbox" id="is_active" name="is_active" 
                                       <?php checked(!$business || $business->is_active); ?>>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save Business', 'primary', 'pseo_save_business'); ?>
                    <a href="<?php echo admin_url('admin.php?page=pseo-businesses'); ?>" class="button">Cancel</a>
                </form>
            </div>
            <?php
            return;
        }
        
        // List view with filter
        $cities = $wpdb->get_results("SELECT id, city_name, city_slug FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        
        $where = '';
        if ($filter_city) {
            $city = $wpdb->get_row($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}cities WHERE city_slug = %s",
                $filter_city
            ));
            if ($city) {
                $where = $wpdb->prepare("WHERE b.city_id = %d", $city->id);
            }
        }
        
        $businesses = $wpdb->get_results("SELECT b.*, c.city_name 
            FROM {$wpdb->prefix}businesses b 
            LEFT JOIN {$wpdb->prefix}cities c ON b.city_id = c.id 
            $where 
            ORDER BY b.id DESC");
        ?>
        <div class="wrap">
            <h1>Businesses <a href="<?php echo admin_url('admin.php?page=pseo-businesses&action=add'); ?>" class="page-title-action">Add New</a></h1>
            
            <form method="get" class="pseo-filter-form">
                <input type="hidden" name="page" value="pseo-businesses">
                <select name="filter_city">
                    <option value="">All Cities</option>
                    <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->city_slug); ?>" 
                        <?php selected($filter_city === $city->city_slug); ?>>
                        <?php echo esc_html($city->city_name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php submit_button('Filter', 'secondary', '', false); ?>
            </form>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Business Name</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($businesses as $business) : ?>
                    <tr>
                        <td><?php echo esc_html($business->id); ?></td>
                        <td><?php echo esc_html($business->name); ?></td>
                        <td><?php echo esc_html($business->city_name); ?></td>
                        <td><?php echo esc_html($business->phone); ?></td>
                        <td><?php echo esc_html($business->rating); ?></td>
                        <td><?php echo $business->is_active ? '<span style="color:green">Active</span>' : '<span style="color:red">Inactive</span>'; ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=pseo-businesses&action=edit&id=' . $business->id); ?>">Edit</a> | 
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=pseo-businesses&action=delete&id=' . $business->id), 'delete_business_' . $business->id); ?>" 
                               onclick="return confirm('Are you sure?')" 
                               style="color: #a00;">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Sitemap Generator
    // =====================================================
    public function page_sitemap() {
        global $wpdb;
        
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        
        // Generate sitemap XML
        $sitemap_xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap_xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($cities as $city) {
            $url = home_url('/' . $service_slug . '-' . $city->city_slug . '/');
            $sitemap_xml .= "  <url>\n";
            $sitemap_xml .= "    <loc>" . esc_url($url) . "</loc>\n";
            $sitemap_xml .= "    <changefreq>weekly</changefreq>\n";
            $sitemap_xml .= "    <priority>0.8</priority>\n";
            $sitemap_xml .= "  </url>\n";
        }
        
        $sitemap_xml .= '</urlset>';
        
        // Save to file
        $upload_dir = wp_upload_dir();
        $sitemap_path = $upload_dir['basedir'] . '/pseo-sitemap.xml';
        file_put_contents($sitemap_path, $sitemap_xml);
        ?>
        <div class="wrap">
            <h1>Sitemap Generator</h1>
            
            <div class="notice notice-info">
                <p>Sitemap generated successfully!</p>
                <p>File location: <code><?php echo esc_html($sitemap_path); ?></code></p>
                <p>Sitemap URL: <a href="<?php echo esc_url($upload_dir['baseurl'] . '/pseo-sitemap.xml'); ?>" target="_blank">View Sitemap</a></p>
            </div>
            
            <h2>Generated URLs (<?php echo count($cities); ?> cities)</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>URL Structure</th>
                        <th>Full URL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cities as $city) : 
                        $url = home_url('/' . $service_slug . '-' . $city->city_slug . '/');
                    ?>
                    <tr>
                        <td><?php echo esc_html($city->city_name); ?></td>
                        <td><code><?php echo esc_html($service_slug . '-' . $city->city_slug); ?></code></td>
                        <td><a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo esc_url($url); ?></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h2>Robots.txt Entry</h2>
            <p>Add this to your robots.txt file:</p>
            <pre>Sitemap: <?php echo esc_url($upload_dir['baseurl'] . '/pseo-sitemap.xml'); ?></pre>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Import CSV
    // =====================================================
    public function page_import_csv() {
        global $wpdb;
        
        $message = '';
        $error = '';
        
        if (isset($_POST['pseo_import_csv']) && !empty($_FILES['csv_file']['tmp_name'])) {
            check_admin_referer('pseo_import_csv');
            
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            
            if ($handle) {
                $header = fgetcsv($handle, 0, ',');
                $imported = 0;
                $failed = 0;
                $duplicates = 0;
                
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    $row = array_combine($header, $data);
                    
                    // Validate required fields
                    if (empty($row['city_slug']) || empty($row['name'])) {
                        $failed++;
                        continue;
                    }
                    
                    // Get city_id
                    $city = $wpdb->get_row($wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}cities WHERE city_slug = %s",
                        sanitize_title($row['city_slug'])
                    ));
                    
                    if (!$city) {
                        $failed++;
                        continue;
                    }
                    
                    // Check for duplicate (same name + city)
                    $existing = $wpdb->get_var($wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}businesses 
                         WHERE city_id = %d AND name = %s",
                        $city->id,
                        sanitize_text_field($row['name'])
                    ));
                    
                    if ($existing) {
                        $duplicates++;
                        continue;
                    }
                    
                    // Insert business
                    $result = $wpdb->insert(
                        $wpdb->prefix . 'businesses',
                        array(
                            'city_id' => $city->id,
                            'name' => sanitize_text_field($row['name']),
                            'address' => sanitize_textarea_field($row['address'] ?? ''),
                            'phone' => sanitize_text_field($row['phone'] ?? ''),
                            'whatsapp' => sanitize_text_field($row['whatsapp'] ?? ''),
                            'rating' => floatval($row['rating'] ?? 0),
                            'lat' => sanitize_text_field($row['lat'] ?? ''),
                            'lng' => sanitize_text_field($row['lng'] ?? ''),
                            'description' => sanitize_textarea_field($row['description'] ?? ''),
                            'website' => esc_url_raw($row['website'] ?? ''),
                            'is_active' => isset($row['is_active']) ? intval($row['is_active']) : 1
                        )
                    );
                    
                    if ($result) {
                        $imported++;
                    } else {
                        $failed++;
                    }
                }
                
                fclose($handle);
                $message = "Import complete: {$imported} imported, {$duplicates} duplicates skipped, {$failed} failed.";
            }
        }
        
        // Generate sample CSV
        $sample_csv = "city_slug,name,address,phone,whatsapp,rating,lat,lng,description,website,is_active\n";
        $sample_csv .= "bondowoso,AC Bondowoso Sejahtera,Jl. A Yani No. 45,0332-123456,6281234567890,4.5,-7.913459,113.821059,Service AC profesional,https://example.com,1\n";
        $sample_csv .= "jember,Service AC Jember Utama,Jl. Gajah Mada No. 123,0331-345678,6281234567892,4.7,-8.172119,113.699323,Service AC terpercaya,,1";
        ?>
        <div class="wrap">
            <h1>Import Businesses from CSV</h1>
            
            <?php if ($message) : ?>
            <div class="notice notice-success"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>
            
            <?php if ($error) : ?>
            <div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div>
            <?php endif; ?>
            
            <div class="pseo-import-section">
                <h2>Upload CSV File</h2>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('pseo_import_csv'); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="csv_file">CSV File</label></th>
                            <td>
                                <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                <p class="description">Upload CSV file with business data. Maximum file size: <?php echo ini_get('upload_max_filesize'); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Import CSV', 'primary', 'pseo_import_csv'); ?>
                </form>
            </div>
            
            <div class="pseo-csv-template">
                <h2>CSV Template</h2>
                <p>Required columns: <code>city_slug</code>, <code>name</code></p>
                <p>Optional columns: <code>address</code>, <code>phone</code>, <code>whatsapp</code>, <code>rating</code>, <code>lat</code>, <code>lng</code>, <code>description</code>, <code>website</code>, <code>is_active</code></p>
                
                <h3>Sample CSV Format:</h3>
                <pre style="background: #f0f0f1; padding: 15px; overflow-x: auto;"><?php echo esc_html($sample_csv); ?></pre>
                
                <p>
                    <a href="data:text/csv;charset=utf-8,<?php echo urlencode($sample_csv); ?>" 
                       download="pseo_businesses_template.csv" 
                       class="button">Download Sample CSV</a>
                </p>
            </div>
            
            <div class="pseo-csv-instructions">
                <h2>Instructions</h2>
                <ol>
                    <li>Download the sample CSV template above</li>
                    <li>Fill in your business data following the format</li>
                    <li>Save as CSV (Comma Separated Values)</li>
                    <li>Upload using the form above</li>
                </ol>
                <p><strong>Note:</strong> Duplicate entries (same name in same city) will be automatically skipped.</p>
            </div>
        </div>
        <?php
    }
    
    // =====================================================
    // PAGE: Content Templates
    // =====================================================
    public function page_templates() {
        if (isset($_POST['pseo_save_template'])) {
            check_admin_referer('pseo_templates');
            
            update_option('pseo_template_opening', wp_kses_post($_POST['template_opening']));
            update_option('pseo_template_why_section', wp_kses_post($_POST['template_why_section']));
            update_option('pseo_template_tips_section', wp_kses_post($_POST['template_tips_section']));
            update_option('pseo_template_closing', wp_kses_post($_POST['template_closing']));
            update_option('pseo_template_faq_enabled', isset($_POST['faq_enabled']) ? 1 : 0);
            
            echo '<div class="notice notice-success"><p>Templates saved successfully!</p></div>';
        }
        
        $template_opening = get_option('pseo_template_opening', $this->get_default_template_opening());
        $template_why = get_option('pseo_template_why_section', $this->get_default_template_why());
        $template_tips = get_option('pseo_template_tips_section', $this->get_default_template_tips());
        $template_closing = get_option('pseo_template_closing', $this->get_default_template_closing());
        $faq_enabled = get_option('pseo_template_faq_enabled', true);
        ?>
        <div class="wrap">
            <h1>Content Templates</h1>
            <p>Customize the content structure for auto-generated posts. Use placeholders: <code>{city_name}</code>, <code>{service_name}</code>, <code>{province}</code></p>
            
            <form method="post">
                <?php wp_nonce_field('pseo_templates'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="template_opening">Opening Paragraph Template</label></th>
                        <td>
                            <textarea id="template_opening" name="template_opening" rows="5" class="large-text"><?php echo esc_textarea($template_opening); ?></textarea>
                            <p class="description">This appears at the beginning of each post.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="template_why_section">"Why Choose" Section</label></th>
                        <td>
                            <textarea id="template_why_section" name="template_why_section" rows="5" class="large-text"><?php echo esc_textarea($template_why); ?></textarea>
                            <p class="description">Content for "Kenapa memilih {service_name} di {city_name}?" section.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="template_tips_section">"Tips" Section</label></th>
                        <td>
                            <textarea id="template_tips_section" name="template_tips_section" rows="5" class="large-text"><?php echo esc_textarea($template_tips); ?></textarea>
                            <p class="description">Content for "Tips memilih {service_name} terpercaya" section.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="template_closing">Closing Paragraph Template</label></th>
                        <td>
                            <textarea id="template_closing" name="template_closing" rows="5" class="large-text"><?php echo esc_textarea($template_closing); ?></textarea>
                            <p class="description">This appears at the end of each post.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="faq_enabled">Enable FAQ Section</label></th>
                        <td>
                            <input type="checkbox" id="faq_enabled" name="faq_enabled" <?php checked($faq_enabled); ?>>
                            <span class="description">Automatically add FAQ schema to generated posts</span>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Save Templates', 'primary', 'pseo_save_template'); ?>
            </form>
        </div>
        <?php
    }
    
    private function get_default_template_opening() {
        return 'Mencari {service_name} terpercaya di {city_name}? Kami hadir untuk membantu Anda menemukan penyedia jasa terbaik di kota ini. Dengan berbagai pilihan layanan berkualitas, Anda dapat dengan mudah menemukan solusi yang sesuai dengan kebutuhan Anda.';
    }
    
    private function get_default_template_why() {
        return '{city_name} memiliki berbagai penyedia jasa {service_name} yang berkualitas. Berikut adalah beberapa alasan mengapa Anda harus memilih layanan di kota ini:

Teknisi berpengalaman dan tersertifikasi
Harga kompetitif dan transparan
Layanan cepat dan responsif
Garansi pelayanan terbaik
Tersedia layanan darurat 24 jam';
    }
    
    private function get_default_template_tips() {
        return 'Untuk mendapatkan layanan terbaik, perhatikan tips berikut saat memilih penyedia jasa:

Periksa review dan rating dari pelanggan sebelumnya
Pastikan teknisi memiliki sertifikasi resmi
Tanyakan tentang garansi yang diberikan
Bandingkan harga dari beberapa penyedia jasa
Pilih yang menawarkan layanan purna jual';
    }
    
    private function get_default_template_closing() {
        return 'Demikian informasi tentang {service_name} di {city_name}. Semoga daftar di atas dapat membantu Anda menemukan layanan terbaik. Jangan ragu untuk menghubungi penyedia jasa yang terdaftar untuk mendapatkan penawaran terbaik.';
    }
    
    // =====================================================
    // SHORTCODE: [local_business]
    // =====================================================
    public function shortcode_local_business($atts) {
        global $wpdb;
        
        $atts = shortcode_atts(array(
            'city' => '',
            'limit' => 10
        ), $atts, 'local_business');
        
        $city_slug = sanitize_title($atts['city']);
        $limit = intval($atts['limit']);
        
        if (!$city_slug) {
            return '<p>Please specify a city.</p>';
        }
        
        $city = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}cities WHERE city_slug = %s",
            $city_slug
        ));
        
        if (!$city) {
            return '<p>City not found.</p>';
        }
        
        $businesses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}businesses 
             WHERE city_id = %d AND is_active = 1 
             ORDER BY rating DESC 
             LIMIT %d",
            $city->id,
            $limit
        ));
        
        if (empty($businesses)) {
            return '<p>No businesses found in this city.</p>';
        }
        
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        $contact_wa = get_option('pseo_contact_whatsapp', '');
        
        ob_start();
        ?>
        <div class="pseo-business-list">
            <?php foreach ($businesses as $business) : 
                $wa_number = $business->whatsapp ? $business->whatsapp : $contact_wa;
                $wa_message = urlencode("Halo, saya tertarik dengan " . $service_name . " di " . $city->city_name);
            ?>
            <div class="pseo-business-item" itemscope itemtype="https://schema.org/LocalBusiness">
                <meta itemprop="@id" content="<?php echo esc_url(home_url('/#business-' . $business->id)); ?>">
                <h3 itemprop="name"><?php echo esc_html($business->name); ?></h3>
                
                <?php if ($business->rating > 0) : ?>
                <div class="pseo-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                    <span class="pseo-stars"><?php echo str_repeat('★', round($business->rating)); ?></span>
                    <span itemprop="ratingValue"><?php echo esc_html($business->rating); ?></span>/5
                </div>
                <?php endif; ?>
                
                <div class="pseo-address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <span itemprop="streetAddress"><?php echo esc_html($business->address); ?></span>
                    <span itemprop="addressLocality"><?php echo esc_html($city->city_name); ?></span>
                    <span itemprop="addressRegion"><?php echo esc_html($city->province); ?></span>
                </div>
                
                <?php if ($business->phone) : ?>
                <div class="pseo-phone">
                    <strong>Telp:</strong> <a href="tel:<?php echo esc_attr($business->phone); ?>" itemprop="telephone"><?php echo esc_html($business->phone); ?></a>
                </div>
                <?php endif; ?>
                
                <?php if ($business->description) : ?>
                <div class="pseo-description" itemprop="description">
                    <?php echo esc_html($business->description); ?>
                </div>
                <?php endif; ?>
                
                <div class="pseo-actions">
                    <?php if ($wa_number) : ?>
                    <a href="https://wa.me/<?php echo esc_attr($wa_number); ?>?text=<?php echo esc_attr($wa_message); ?>" 
                       class="pseo-btn pseo-btn-whatsapp" target="_blank">
                        Chat WhatsApp
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($business->phone) : ?>
                    <a href="tel:<?php echo esc_attr($business->phone); ?>" class="pseo-btn pseo-btn-call">
                        Telepon
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($business->lat && $business->lng) : ?>
                    <a href="https://www.google.com/maps?q=<?php echo esc_attr($business->lat); ?>,<?php echo esc_attr($business->lng); ?>" 
                       class="pseo-btn pseo-btn-map" target="_blank">
                        Lihat Peta
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    // =====================================================
    // SHORTCODE: [business_map]
    // =====================================================
    public function shortcode_business_map($atts) {
        global $wpdb;
        
        $atts = shortcode_atts(array(
            'city' => '',
            'height' => '400px'
        ), $atts, 'business_map');
        
        $city_slug = sanitize_title($atts['city']);
        $height = sanitize_text_field($atts['height']);
        
        if (!$city_slug) {
            return '<p>Please specify a city.</p>';
        }
        
        $city = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}cities WHERE city_slug = %s",
            $city_slug
        ));
        
        if (!$city) {
            return '<p>City not found.</p>';
        }
        
        $businesses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}businesses 
             WHERE city_id = %d AND is_active = 1 AND lat IS NOT NULL AND lng IS NOT NULL",
            $city->id
        ));
        
        if (empty($businesses)) {
            return '<p>No businesses with location data found.</p>';
        }
        
        $api_key = get_option('pseo_google_maps_api');
        $map_id = 'pseo-map-' . uniqid();
        
        ob_start();
        
        // FALLBACK: If no API key, show static map links
        if (empty($api_key)) {
            ?>
            <div class="pseo-map-fallback" style="height: <?php echo esc_attr($height); ?>; background: #f5f5f5; border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center;">
                <h4>Peta Lokasi Bisnis di <?php echo esc_html($city->city_name); ?></h4>
                <div class="pseo-map-businesses" style="margin-top: 15px;">
                    <?php foreach ($businesses as $business) : ?>
                    <div class="pseo-map-business-item" style="background: #fff; padding: 15px; margin: 10px 0; border-radius: 5px; text-align: left;">
                        <strong><?php echo esc_html($business->name); ?></strong><br>
                        <?php if ($business->address) : ?>
                        <small><?php echo esc_html($business->address); ?></small><br>
                        <?php endif; ?>
                        <a href="https://www.google.com/maps?q=<?php echo esc_attr($business->lat); ?>,<?php echo esc_attr($business->lng); ?>" 
                           target="_blank" class="pseo-btn pseo-btn-map" style="margin-top: 8px; display: inline-block;">
                            Lihat di Google Maps
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p style="margin-top: 15px; font-size: 12px; color: #666;">
                    <em>Untuk tampilan peta interaktif, silakan tambahkan Google Maps API Key di <a href="<?php echo admin_url('admin.php?page=pseo-settings'); ?>">Settings</a></em>
                </p>
            </div>
            <?php
            return ob_get_clean();
        }
        
        // Google Maps with API Key
        ?>
        <div id="<?php echo esc_attr($map_id); ?>" class="pseo-map" style="height: <?php echo esc_attr($height); ?>"></div>
        
        <script>
        (function() {
            var map = new google.maps.Map(document.getElementById('<?php echo esc_js($map_id); ?>'), {
                zoom: 12,
                center: { lat: <?php echo esc_js($businesses[0]->lat); ?>, lng: <?php echo esc_js($businesses[0]->lng); ?> }
            });
            
            var businesses = <?php echo json_encode($businesses); ?>;
            var bounds = new google.maps.LatLngBounds();
            
            businesses.forEach(function(business) {
                if (business.lat && business.lng) {
                    var position = { lat: parseFloat(business.lat), lng: parseFloat(business.lng) };
                    var marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: business.name
                    });
                    
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<strong>' + business.name + '</strong><br>' + 
                                 (business.address ? business.address : '') +
                                 (business.phone ? '<br>Telp: ' + business.phone : '')
                    });
                    
                    marker.addListener('click', function() {
                        infoWindow.open(map, marker);
                    });
                    
                    bounds.extend(position);
                }
            });
            
            if (businesses.length > 1) {
                map.fitBounds(bounds);
            }
        })();
        </script>
        <?php
        return ob_get_clean();
    }
    
    // =====================================================
    // SHORTCODE: [city_links]
    // =====================================================
    public function shortcode_city_links($atts) {
        global $wpdb;
        
        $atts = shortcode_atts(array(
            'count' => 10,
            'exclude' => '',
            'title' => 'Layanan di kota lain:'
        ), $atts, 'city_links');
        
        $count = intval($atts['count']);
        $exclude = array_map('trim', explode(',', $atts['exclude']));
        $title = sanitize_text_field($atts['title']);
        
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY RAND() LIMIT " . ($count + count($exclude)));
        
        ob_start();
        ?>
        <div class="pseo-city-links">
            <h4><?php echo esc_html($title); ?></h4>
            <ul>
                <?php 
                $shown = 0;
                foreach ($cities as $city) : 
                    if (in_array($city->city_slug, $exclude)) continue;
                    if ($shown >= $count) break;
                    $url = home_url('/' . $service_slug . '-' . $city->city_slug . '/');
                    $shown++;
                ?>
                <li>
                    <a href="<?php echo esc_url($url); ?>">
                        <?php echo esc_html($service_name); ?> di <?php echo esc_html($city->city_name); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    // =====================================================
    // SHORTCODE: [faq_schema]
    // =====================================================
    public function shortcode_faq_schema($atts, $content = null) {
        $atts = shortcode_atts(array(
            'title' => 'Pertanyaan yang Sering Diajukan',
            'city' => ''
        ), $atts, 'faq_schema');
        
        $city_slug = sanitize_title($atts['city']);
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        
        // Get city name if provided
        $city_name = '';
        if ($city_slug) {
            global $wpdb;
            $city = $wpdb->get_row($wpdb->prepare(
                "SELECT city_name FROM {$wpdb->prefix}cities WHERE city_slug = %s",
                $city_slug
            ));
            if ($city) {
                $city_name = $city->city_name;
            }
        }
        
        // Default FAQs
        $faqs = array(
            array(
                'question' => "Berapa harga {$service_name} di " . ($city_name ?: 'kota ini') . "?",
                'answer' => "Harga {$service_name} bervariasi tergantung jenis layanan dan kerusakan. Rata-rata harga mulai dari Rp 50.000 - Rp 500.000. Silakan hubungi penyedia jasa untuk estimasi harga yang lebih akurat."
            ),
            array(
                'question' => "Berapa lama waktu pengerjaan {$service_name}?",
                'answer' => "Waktu pengerjaan umumnya 1-3 jam tergantung tingkat kerumitan. Untuk perbaikan ringan bisa selesai dalam 30 menit, sedangkan untuk instalasi baru atau perbaikan berat bisa memakan waktu lebih lama."
            ),
            array(
                'question' => "Apakah ada garansi untuk layanan {$service_name}?",
                'answer' => "Ya, sebagian besar penyedia jasa di " . ($city_name ?: 'kota ini') . " memberikan garansi 7-30 hari tergantung jenis layanan. Pastikan untuk menanyakan ketentuan garansi sebelum menggunakan jasa."
            ),
            array(
                'question' => "Bagaimana cara memesan layanan {$service_name}?",
                'answer' => "Anda bisa memesan layanan dengan menghubungi nomor WhatsApp atau telepon yang tertera di daftar penyedia jasa di atas. Beberapa penyedia juga menerima pemesanan melalui aplikasi atau website."
            ),
            array(
                'question' => "Apakah tersedia layanan darurat 24 jam?",
                'answer' => "Ya, beberapa penyedia jasa di " . ($city_name ?: 'kota ini') . " menyediakan layanan darurat 24 jam. Silakan cek informasi masing-masing penyedia atau hubungi mereka langsung untuk konfirmasi ketersediaan."
            )
        );
        
        // Allow custom FAQs via content
        if ($content) {
            $custom_faqs = $this->parse_faq_content($content);
            if (!empty($custom_faqs)) {
                $faqs = $custom_faqs;
            }
        }
        
        // Generate Schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array()
        );
        
        foreach ($faqs as $faq) {
            $schema['mainEntity'][] = array(
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                )
            );
        }
        
        // Output HTML + Schema
        ob_start();
        ?>
        <div class="pseo-faq-section">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            <div class="pseo-faq-list">
                <?php foreach ($faqs as $index => $faq) : ?>
                <div class="pseo-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <h3 itemprop="name"><?php echo esc_html($faq['question']); ?></h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <?php echo esc_html($faq['answer']); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script type="application/ld+json">
        <?php echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
        </script>
        <?php
        return ob_get_clean();
    }
    
    private function parse_faq_content($content) {
        $faqs = array();
        $lines = explode("\n", trim($content));
        $current_q = '';
        $current_a = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            if (strpos($line, 'Q:') === 0 || strpos($line, 'Question:') === 0) {
                if ($current_q && $current_a) {
                    $faqs[] = array('question' => $current_q, 'answer' => $current_a);
                }
                $current_q = trim(substr($line, strpos($line, ':') + 1));
                $current_a = '';
            } elseif (strpos($line, 'A:') === 0 || strpos($line, 'Answer:') === 0) {
                $current_a = trim(substr($line, strpos($line, ':') + 1));
            } elseif ($current_a !== '') {
                $current_a .= ' ' . $line;
            }
        }
        
        if ($current_q && $current_a) {
            $faqs[] = array('question' => $current_q, 'answer' => $current_a);
        }
        
        return $faqs;
    }
    
    // =====================================================
    // INTERNAL LINKING FOOTER
    // =====================================================
    public function internal_linking_footer() {
        if (!is_singular('post') && !is_singular('page')) {
            return;
        }
        
        global $wpdb;
        
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY RAND() LIMIT 8");
        
        if (empty($cities)) return;
        ?>
        <div class="pseo-footer-links">
            <div class="pseo-footer-container">
                <h4><?php echo esc_html($service_name); ?> di kota lain:</h4>
                <div class="pseo-footer-cities">
                    <?php foreach ($cities as $city) : 
                        $url = home_url('/' . $service_slug . '-' . $city->city_slug . '/');
                    ?>
                    <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($city->city_name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    // =====================================================
    // SCHEMA MARKUP
    // =====================================================
    public function schema_markup() {
        if (!is_singular('post') && !is_singular('page')) {
            return;
        }
        
        global $post, $wpdb;
        
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        
        // Detect city from URL or content
        $city_slug = '';
        $city = null;
        
        // Try to extract city from URL
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (preg_match('/' . preg_quote($service_slug, '/') . '-([^\/]+)/', $url_path, $matches)) {
            $city_slug = $matches[1];
            $city = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}cities WHERE city_slug = %s",
                $city_slug
            ));
        }
        
        if (!$city) return;
        
        // Get businesses for this city
        $businesses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}businesses 
             WHERE city_id = %d AND is_active = 1",
            $city->id
        ));
        
        $schemas = array();
        
        // Breadcrumb Schema
        $schemas[] = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => home_url('/')
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $service_name . ' di ' . $city->city_name,
                    'item' => get_permalink()
                )
            )
        );
        
        // LocalBusiness Schema for each business
        foreach ($businesses as $business) {
            $schemas[] = array(
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                '@id' => home_url('/#business-' . $business->id),
                'name' => $business->name,
                'description' => $business->description,
                'address' => array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => $business->address,
                    'addressLocality' => $city->city_name,
                    'addressRegion' => $city->province,
                    'addressCountry' => 'ID'
                ),
                'telephone' => $business->phone,
                'geo' => array(
                    '@type' => 'GeoCoordinates',
                    'latitude' => $business->lat,
                    'longitude' => $business->lng
                ),
                'aggregateRating' => array(
                    '@type' => 'AggregateRating',
                    'ratingValue' => $business->rating,
                    'bestRating' => 5
                )
            );
        }
        
        // WebPage Schema
        $schemas[] = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => get_permalink(),
            'url' => get_permalink(),
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'isPartOf' => array(
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url('/')
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schemas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
    }
    
    // =====================================================
    // AJAX HANDLERS
    // =====================================================
    public function ajax_get_cities() {
        check_ajax_referer('pseo_nonce', 'nonce');
        
        global $wpdb;
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        
        wp_send_json_success($cities);
    }
    
    public function ajax_get_businesses() {
        check_ajax_referer('pseo_nonce', 'nonce');
        
        global $wpdb;
        $city_id = intval($_POST['city_id']);
        
        $businesses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}businesses WHERE city_id = %d AND is_active = 1",
            $city_id
        ));
        
        wp_send_json_success($businesses);
    }
    
    // =====================================================
    // REST API ROUTES
    // =====================================================
    public function register_rest_routes() {
        register_rest_route('pseo/v1', '/posts', array(
            'methods' => 'POST',
            'callback' => array($this, 'api_create_post'),
            'permission_callback' => array($this, 'api_permission_check')
        ));
        
        register_rest_route('pseo/v1', '/posts/batch', array(
            'methods' => 'POST',
            'callback' => array($this, 'api_create_posts_batch'),
            'permission_callback' => array($this, 'api_permission_check')
        ));
        
        register_rest_route('pseo/v1', '/cities', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_cities'),
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('pseo/v1', '/template', array(
            'methods' => 'GET',
            'callback' => array($this, 'api_get_template'),
            'permission_callback' => array($this, 'api_permission_check')
        ));
    }
    
    public function api_permission_check($request) {
        // Check for Application Password or logged-in user
        if (current_user_can('publish_posts')) {
            return true;
        }
        
        // Check for API key in header
        $api_key = $request->get_header('X-PSEO-API-Key');
        $stored_key = get_option('pseo_api_key');
        
        if ($api_key && $stored_key && hash_equals($stored_key, $api_key)) {
            return true;
        }
        
        return new WP_Error('rest_forbidden', 'Invalid API credentials', array('status' => 403));
    }
    
    public function api_get_cities($request) {
        global $wpdb;
        $cities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        return rest_ensure_response($cities);
    }
    
    public function api_get_template($request) {
        $template = $this->get_post_template();
        return rest_ensure_response(array(
            'template' => $template,
            'placeholders' => array(
                '{city_name}',
                '{city_slug}',
                '{service_name}',
                '{service_slug}',
                '{province}',
                '{opening_content}',
                '{closing_content}'
            )
        ));
    }
    
    public function api_create_post($request) {
        $params = $request->get_json_params();
        
        if (empty($params['city_slug'])) {
            return new WP_Error('missing_param', 'city_slug is required', array('status' => 400));
        }
        
        $result = $this->create_city_post($params);
        
        if (is_wp_error($result)) {
            return $result;
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'post_id' => $result['post_id'],
            'url' => $result['url'],
            'message' => 'Post created successfully'
        ));
    }
    
    public function api_create_posts_batch($request) {
        $params = $request->get_json_params();
        
        $city_slugs = !empty($params['city_slugs']) ? $params['city_slugs'] : array();
        $all_cities = !empty($params['all_cities']) ? $params['all_cities'] : false;
        
        if ($all_cities) {
            global $wpdb;
            $cities = $wpdb->get_results("SELECT city_slug FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
            $city_slugs = wp_list_pluck($cities, 'city_slug');
        }
        
        if (empty($city_slugs)) {
            return new WP_Error('missing_param', 'city_slugs or all_cities required', array('status' => 400));
        }
        
        $results = array();
        $success_count = 0;
        $error_count = 0;
        
        foreach ($city_slugs as $city_slug) {
            $result = $this->create_city_post(array_merge($params, array('city_slug' => $city_slug)));
            
            if (is_wp_error($result)) {
                $results[] = array(
                    'city_slug' => $city_slug,
                    'success' => false,
                    'error' => $result->get_error_message()
                );
                $error_count++;
            } else {
                $results[] = array(
                    'city_slug' => $city_slug,
                    'success' => true,
                    'post_id' => $result['post_id'],
                    'url' => $result['url']
                );
                $success_count++;
            }
        }
        
        return rest_ensure_response(array(
            'success' => true,
            'total' => count($city_slugs),
            'created' => $success_count,
            'failed' => $error_count,
            'results' => $results
        ));
    }
    
    public function create_city_post_internal($params) {
        return $this->create_city_post($params);
    }
    
    private function create_city_post($params) {
        global $wpdb;
        
        $city_slug = sanitize_title($params['city_slug']);
        $city = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}cities WHERE city_slug = %s",
            $city_slug
        ));
        
        if (!$city) {
            return new WP_Error('city_not_found', 'City not found: ' . $city_slug, array('status' => 404));
        }
        
        $service_name = get_option('pseo_service_name', 'Jasa AC');
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        $province = get_option('pseo_province', 'Jawa Timur');
        
        // ADVANCED DUPLICATE DETECTION
        $existing = get_page_by_path($service_slug . '-' . $city_slug, OBJECT, 'page');
        if ($existing) {
            return new WP_Error(
                'post_exists', 
                'Post already exists for ' . $city_slug . ' (ID: ' . $existing->ID . ', Status: ' . $existing->post_status . ')', 
                array(
                    'status' => 409,
                    'post_id' => $existing->ID,
                    'post_status' => $existing->post_status,
                    'edit_url' => get_edit_post_link($existing->ID, 'raw'),
                    'view_url' => get_permalink($existing->ID)
                )
            );
        }
        
        // Also check by meta
        $existing_by_meta = get_posts(array(
            'post_type' => 'page',
            'meta_key' => '_pseo_city_slug',
            'meta_value' => $city_slug,
            'posts_per_page' => 1,
            'post_status' => array('publish', 'draft', 'pending', 'future')
        ));
        
        if (!empty($existing_by_meta)) {
            $existing = $existing_by_meta[0];
            return new WP_Error(
                'post_exists_meta', 
                'Post already exists for ' . $city_slug . ' (ID: ' . $existing->ID . ')', 
                array(
                    'status' => 409,
                    'post_id' => $existing->ID,
                    'post_status' => $existing->post_status,
                    'edit_url' => get_edit_post_link($existing->ID, 'raw'),
                    'view_url' => get_permalink($existing->ID)
                )
            );
        }
        
        // Build content
        $opening = !empty($params['opening_content']) ? $params['opening_content'] : $this->generate_opening($city, $service_name);
        $closing = !empty($params['closing_content']) ? $params['closing_content'] : $this->generate_closing($city, $service_name);
        
        $content = $this->build_post_content(array(
            'opening' => $opening,
            'closing' => $closing,
            'city' => $city,
            'service_name' => $service_name,
            'service_slug' => $service_slug
        ));
        
        // Create post
        $post_data = array(
            'post_title'   => $service_name . ' di ' . $city->city_name,
            'post_name'    => $service_slug . '-' . $city_slug,
            'post_content' => $content,
            'post_status'  => !empty($params['status']) ? sanitize_key($params['status']) : 'draft',
            'post_type'    => !empty($params['post_type']) ? sanitize_key($params['post_type']) : 'page',
            'post_author'  => !empty($params['author_id']) ? intval($params['author_id']) : get_current_user_id(),
        );
        
        $post_id = wp_insert_post($post_data, true);
        
        if (is_wp_error($post_id)) {
            return $post_id;
        }
        
        // Add meta
        update_post_meta($post_id, '_pseo_city_id', $city->id);
        update_post_meta($post_id, '_pseo_city_slug', $city_slug);
        update_post_meta($post_id, '_pseo_is_generated', true);
        update_post_meta($post_id, '_pseo_generated_at', current_time('mysql'));
        update_post_meta($post_id, '_pseo_service_slug', $service_slug);
        
        return array(
            'post_id' => $post_id,
            'url' => get_permalink($post_id)
        );
    }
    
    // =====================================================
    // AJAX HANDLERS
    // =====================================================
    public function ajax_check_duplicate_post() {
        check_ajax_referer('pseo_nonce', 'nonce');
        
        $city_slug = sanitize_title($_POST['city_slug']);
        $service_slug = get_option('pseo_service_slug', 'jasa-ac');
        
        // Check if post exists
        $existing = get_page_by_path($service_slug . '-' . $city_slug, OBJECT, 'page');
        
        if ($existing) {
            wp_send_json_success(array(
                'exists' => true,
                'post_id' => $existing->ID,
                'post_title' => $existing->post_title,
                'post_status' => $existing->post_status,
                'edit_url' => get_edit_post_link($existing->ID, 'raw'),
                'view_url' => get_permalink($existing->ID)
            ));
        } else {
            wp_send_json_success(array('exists' => false));
        }
    }
    
    public function ajax_import_businesses_csv() {
        check_ajax_referer('pseo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        wp_send_json_success(array('message' => 'Use the Import CSV page for bulk upload'));
    }
    
    public function ajax_save_template() {
        check_ajax_referer('pseo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        wp_send_json_success(array('message' => 'Use the Templates page to save templates'));
    }
    
    private function build_post_content($data) {
        $city = $data['city'];
        $service_name = $data['service_name'];
        $service_slug = $data['service_slug'];
        
        // Get custom templates or use defaults
        $opening_template = get_option('pseo_template_opening', $this->get_default_template_opening());
        $why_template = get_option('pseo_template_why_section', $this->get_default_template_why());
        $tips_template = get_option('pseo_template_tips_section', $this->get_default_template_tips());
        $closing_template = get_option('pseo_template_closing', $this->get_default_template_closing());
        $faq_enabled = get_option('pseo_template_faq_enabled', true);
        
        // Replace placeholders
        $placeholders = array(
            '{city_name}' => $city->city_name,
            '{city_slug}' => $city->city_slug,
            '{service_name}' => $service_name,
            '{service_slug}' => $service_slug,
            '{province}' => $city->province
        );
        
        $opening = !empty($data['opening']) ? $data['opening'] : strtr($opening_template, $placeholders);
        $closing = !empty($data['closing']) ? $data['closing'] : strtr($closing_template, $placeholders);
        $why_content = strtr($why_template, $placeholders);
        $tips_content = strtr($tips_template, $placeholders);
        
        $content = '';
        
        // Opening
        $content .= '<!-- wp:paragraph -->';
        $content .= '<p>' . wp_kses_post($opening) . '</p>';
        $content .= '<!-- /wp:paragraph -->';
        
        $content .= "\n\n";
        
        // Business list shortcode
        $content .= '<!-- wp:shortcode -->';
        $content .= '[local_business city="' . esc_attr($city->city_slug) . '"]';
        $content .= '<!-- /wp:shortcode -->';
        
        $content .= "\n\n";
        
        // Map
        $content .= '<!-- wp:shortcode -->';
        $content .= '[business_map city="' . esc_attr($city->city_slug) . '"]';
        $content .= '<!-- /wp:shortcode -->';
        
        $content .= "\n\n";
        
        // Why choose section
        $content .= '<!-- wp:heading -->';
        $content .= '<h2>Kenapa memilih ' . esc_html($service_name) . ' di ' . esc_html($city->city_name) . '?</h2>';
        $content .= '<!-- /wp:heading -->';
        
        $content .= "\n\n";
        
        $content .= wp_kses_post($why_content);
        
        $content .= "\n\n";
        
        // Tips section
        $content .= '<!-- wp:heading -->';
        $content .= '<h2>Tips memilih ' . esc_html($service_name) . ' terpercaya</h2>';
        $content .= '<!-- /wp:heading -->';
        
        $content .= "\n\n";
        
        $content .= wp_kses_post($tips_content);
        
        $content .= "\n\n";
        
        // FAQ Section (if enabled)
        if ($faq_enabled) {
            $content .= '<!-- wp:shortcode -->';
            $content .= '[faq_schema city="' . esc_attr($city->city_slug) . '"]';
            $content .= '<!-- /wp:shortcode -->';
            
            $content .= "\n\n";
        }
        
        // Closing
        $content .= '<!-- wp:paragraph -->';
        $content .= '<p>' . wp_kses_post($closing) . '</p>';
        $content .= '<!-- /wp:paragraph -->';
        
        $content .= "\n\n";
        
        // City links
        $content .= '<!-- wp:shortcode -->';
        $content .= '[city_links exclude="' . esc_attr($city->city_slug) . '"]';
        $content .= '<!-- /wp:shortcode -->';
        
        return $content;
    }
    
    private function generate_opening($city, $service_name) {
        $templates = array(
            "Mencari {$service_name} terpercaya di {$city->city_name}? Kami hadir untuk membantu Anda menemukan penyedia jasa terbaik di kota ini. Dengan berbagai pilihan layanan berkualitas, Anda dapat dengan mudah menemukan solusi yang sesuai dengan kebutuhan Anda.",
            "{$city->city_name} merupakan salah satu kota di {$city->province} yang memiliki banyak penyedia {$service_name} profesional. Dalam artikel ini, kami akan membantu Anda menemukan layanan terbaik dengan harga kompetitif.",
            "Butuh {$service_name} di {$city->city_name}? Jangan khawatir! Kami telah mengumpulkan daftar penyedia jasa terpercaya yang siap membantu Anda. Dari layanan darurat hingga perawatan rutin, semua tersedia di sini."
        );
        
        return $templates[array_rand($templates)];
    }
    
    private function generate_closing($city, $service_name) {
        $templates = array(
            "Demikian informasi tentang {$service_name} di {$city->city_name}. Semoga daftar di atas dapat membantu Anda menemukan layanan terbaik. Jangan ragu untuk menghubungi penyedia jasa yang terdaftar untuk mendapatkan penawaran terbaik.",
            "Itulah berbagai pilihan {$service_name} di {$city->city_name}. Pastikan Anda memilih penyedia jasa yang sesuai dengan kebutuhan dan budget Anda. Dengan layanan profesional yang tersedia, Anda dapat tenang menyerahkan perbaikan kepada ahlinya.",
            "Pilihan {$service_name} di {$city->city_name} sangat beragam. Pilih yang paling sesuai dengan kriteria Anda dan pastikan untuk selalu memeriksa review sebelum memutuskan. Selamat mencoba!"
        );
        
        return $templates[array_rand($templates)];
    }
    
    private function get_post_template() {
        return '<!-- wp:paragraph -->
<p>{opening_content}</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[local_business city="{city_slug}"]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[business_map city="{city_slug}"]
<!-- /wp:shortcode -->

<!-- wp:heading -->
<h2>Kenapa memilih {service_name} di {city_name}?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Content here...</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Tips memilih {service_name} terpercaya</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{closing_content}</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[city_links exclude="{city_slug}"]
<!-- /wp:shortcode -->';
    }
}

// Initialize
Programmatic_SEO::get_instance();
