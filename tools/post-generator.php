<?php
/**
 * Programmatic SEO - Post Generator CLI Tool
 * 
 * Usage:
 * php post-generator.php --city=bondowoso
 * php post-generator.php --all-cities --status=publish
 * php post-generator.php --cities=bondowoso,jember,malang --delay=5
 */

// WordPress bootstrap
require_once __DIR__ . '/../../../../wp-load.php';

class PSEO_Post_Generator_CLI {
    
    private $api_url;
    private $api_key;
    private $username;
    private $password;
    
    public function __construct() {
        $this->api_url = get_rest_url(null, 'pseo/v1');;
        $this->api_key = get_option('pseo_api_key');
        
        // Generate API key if not exists
        if (!$this->api_key) {
            $this->api_key = wp_generate_password(32, false);
            update_option('pseo_api_key', $this->api_key);
            echo "API Key generated: {$this->api_key}\n";
            echo "Save this key for future use!\n\n";
        }
    }
    
    public function run($args) {
        $options = getopt('', array(
            'city:',
            'cities:',
            'all-cities',
            'status:',
            'delay:',
            'opening:',
            'closing:',
            'help'
        ));
        
        if (isset($options['help']) || empty($options)) {
            $this->show_help();
            return;
        }
        
        $status = isset($options['status']) ? $options['status'] : 'draft';
        $delay = isset($options['delay']) ? intval($options['delay']) : 0;
        $opening = isset($options['opening']) ? file_get_contents($options['opening']) : '';
        $closing = isset($options['closing']) ? file_get_contents($options['closing']) : '';
        
        $cities = array();
        
        if (isset($options['city'])) {
            $cities = array($options['city']);
        } elseif (isset($options['cities'])) {
            $cities = array_map('trim', explode(',', $options['cities']));
        } elseif (isset($options['all-cities'])) {
            global $wpdb;
            $cities = $wpdb->get_col("SELECT city_slug FROM {$wpdb->prefix}cities ORDER BY city_name ASC");
        }
        
        if (empty($cities)) {
            echo "Error: No cities specified\n";
            $this->show_help();
            return;
        }
        
        echo "Generating posts for " . count($cities) . " cities...\n";
        echo "Status: {$status}\n";
        echo "Delay: {$delay} seconds\n";
        echo str_repeat('-', 50) . "\n";
        
        $success = 0;
        $failed = 0;
        
        foreach ($cities as $index => $city_slug) {
            echo "[" . ($index + 1) . "/" . count($cities) . "] Creating post for {$city_slug}... ";
            
            $result = $this->create_post(array(
                'city_slug' => $city_slug,
                'status' => $status,
                'opening_content' => $opening,
                'closing_content' => $closing
            ));
            
            if ($result['success']) {
                echo "✓ Done (ID: {$result['post_id']})\n";
                echo "   URL: {$result['url']}\n";
                $success++;
            } else {
                echo "✗ Failed: {$result['error']}\n";
                $failed++;
            }
            
            if ($delay > 0 && $index < count($cities) - 1) {
                sleep($delay);
            }
        }
        
        echo str_repeat('-', 50) . "\n";
        echo "Complete! Success: {$success}, Failed: {$failed}\n";
    }
    
    private function create_post($data) {
        // Use internal function instead of HTTP request
        $pseo = Programmatic_SEO::get_instance();
        
        // Temporarily bypass permission check
        add_filter('pseo_bypass_permission', '__return_true');
        
        $result = $pseo->create_city_post_internal($data);
        
        remove_filter('pseo_bypass_permission', '__return_true');
        
        if (is_wp_error($result)) {
            return array(
                'success' => false,
                'error' => $result->get_error_message()
            );
        }
        
        return array(
            'success' => true,
            'post_id' => $result['post_id'],
            'url' => $result['url']
        );
    }
    
    private function show_help() {
        echo <<<HELP
Programmatic SEO - Post Generator CLI

Usage:
  php post-generator.php [options]

Options:
  --city=SLUG         Create post for single city
  --cities=a,b,c      Create posts for multiple cities (comma-separated)
  --all-cities        Create posts for all cities
  --status=STATUS     Post status: draft|publish|pending (default: draft)
  --delay=SECONDS     Delay between requests (default: 0)
  --opening=FILE      Path to file containing opening content
  --closing=FILE      Path to file containing closing content
  --help              Show this help message

Examples:
  # Create single post
  php post-generator.php --city=bondowoso

  # Create posts for multiple cities
  php post-generator.php --cities=bondowoso,jember,malang --status=publish

  # Create posts for all cities with delay
  php post-generator.php --all-cities --delay=5 --status=draft

  # Create with custom content
  php post-generator.php --city=surabaya --opening=opening.txt --closing=closing.txt

API Key: 
  Your API key is stored in WordPress options.
  To regenerate: delete 'pseo_api_key' option and run this script again.

HELP;
    }
}

// Add internal method to main class
if (!method_exists('Programmatic_SEO', 'create_city_post_internal')) {
    add_action('init', function() {
        // This will be added via the main plugin file
    });
}

// Run CLI
if (php_sapi_name() === 'cli') {
    $generator = new PSEO_Post_Generator_CLI();
    $generator->run($argv);
} else {
    echo "This script must be run from command line\n";
}
