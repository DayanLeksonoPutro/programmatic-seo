<?php
/**
 * Example: Using Programmatic SEO REST API
 * 
 * This file demonstrates how to create posts via REST API
 * from external scripts or AI CLI tools.
 */

// =====================================================
// CONFIGURATION
// =====================================================
$site_url = 'https://your-site.com';  // Change to your site URL
$api_key = 'YOUR_API_KEY_HERE';        // Get from WordPress admin or generated

// =====================================================
// FUNCTION: Create Single Post
// =====================================================
function create_post($site_url, $api_key, $data) {
    $url = $site_url . '/wp-json/pseo/v1/posts';
    
    $headers = array(
        'Content-Type: application/json',
        'X-PSEO-API-Key: ' . $api_key
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return array(
        'code' => $http_code,
        'response' => json_decode($response, true)
    );
}

// =====================================================
// FUNCTION: Create Batch Posts
// =====================================================
function create_posts_batch($site_url, $api_key, $data) {
    $url = $site_url . '/wp-json/pseo/v1/posts/batch';
    
    $headers = array(
        'Content-Type: application/json',
        'X-PSEO-API-Key: ' . $api_key
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return array(
        'code' => $http_code,
        'response' => json_decode($response, true)
    );
}

// =====================================================
// FUNCTION: Get Cities
// =====================================================
function get_cities($site_url) {
    $url = $site_url . '/wp-json/pseo/v1/cities';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =====================================================
// FUNCTION: Get Template
// =====================================================
function get_template($site_url, $api_key) {
    $url = $site_url . '/wp-json/pseo/v1/template';
    
    $headers = array(
        'X-PSEO-API-Key: ' . $api_key
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// =====================================================
// EXAMPLE 1: Create Single Post
// =====================================================
echo "=== EXAMPLE 1: Create Single Post ===\n";

$post_data = array(
    'city_slug' => 'bondowoso',
    'status' => 'draft',  // or 'publish'
    'opening_content' => 'Konten pembuka dari AI...',
    'closing_content' => 'Konten penutup dari AI...'
);

$result = create_post($site_url, $api_key, $post_data);
echo "HTTP Code: " . $result['code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// =====================================================
// EXAMPLE 2: Create Batch Posts
// =====================================================
echo "=== EXAMPLE 2: Create Batch Posts ===\n";

$batch_data = array(
    'city_slugs' => array('bondowoso', 'jember', 'banyuwangi'),
    'status' => 'draft',
    'opening_content' => 'Template opening...',
    'closing_content' => 'Template closing...'
);

$result = create_posts_batch($site_url, $api_key, $batch_data);
echo "HTTP Code: " . $result['code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// =====================================================
// EXAMPLE 3: Create All Cities
// =====================================================
echo "=== EXAMPLE 3: Create All Cities ===\n";

$all_cities_data = array(
    'all_cities' => true,
    'status' => 'draft',
    'delay' => 2  // Optional: delay between posts
);

// $result = create_posts_batch($site_url, $api_key, $all_cities_data);
// echo "Created: " . $result['response']['created'] . " posts\n\n";

// =====================================================
// EXAMPLE 4: Get Cities List
// =====================================================
echo "=== EXAMPLE 4: Get Cities List ===\n";

$cities = get_cities($site_url);
echo "Total cities: " . count($cities) . "\n";
echo "First 5 cities:\n";
for ($i = 0; $i < min(5, count($cities)); $i++) {
    echo "  - " . $cities[$i]['city_name'] . " (" . $cities[$i]['city_slug'] . ")\n";
}
echo "\n";

// =====================================================
// EXAMPLE 5: Get Template
// =====================================================
echo "=== EXAMPLE 5: Get Template ===\n";

$template = get_template($site_url, $api_key);
echo "Available placeholders:\n";
foreach ($template['placeholders'] as $placeholder) {
    echo "  " . $placeholder . "\n";
}
echo "\n";

// =====================================================
// EXAMPLE 6: Integration with AI CLI
// =====================================================
echo "=== EXAMPLE 6: AI CLI Integration Flow ===\n";
echo <<<FLOW

Recommended workflow for AI CLI integration:

1. GET /wp-json/pseo/v1/cities
   → Get list of cities without posts

2. For each city:
   a. Generate opening content with AI
      Prompt: "Buat paragraf pembuka untuk artikel 
      'Jasa AC di [CITY_NAME]' dengan gaya ramah 
      dan informatif, 150-200 kata"
   
   b. Generate closing content with AI
      Prompt: "Buat paragraf penutup untuk artikel
      'Jasa AC di [CITY_NAME]' dengan CTA hubungi
      penyedia jasa, 100-150 kata"
   
   c. POST /wp-json/pseo/v1/posts
      → Create post with AI-generated content

3. Schedule posts with WordPress native scheduling
   or use 'status' => 'future' with 'date' parameter

FLOW;

echo "\n\nDone!\n";
