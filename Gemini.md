# Programmatic SEO Plugin Automation with Gemini CLI

This guide outlines how to use the `programmaticseo` WordPress plugin with the Gemini CLI for automating post creation and managing business data.

## 1. Overview

The `programmaticseo` plugin is designed to generate dynamic content (posts/pages) based on services and cities, utilizing a custom database (`wp_cities` and `wp_businesses`) and REST API endpoints. This allows for scalable SEO content generation, targeting multiple locations or services.

## 2. Setting up `pseo_api_key` for API Access

The plugin's REST API endpoints require an API key for authentication (`X-PSEO-API-Key` header). The `post-generator.php` CLI tool can conveniently generate and store this key in your WordPress options table (`pseo_api_key`).

**To generate your API Key:**

Run the `post-generator.php` script from your WordPress root directory (or where `wp-load.php` is accessible). This needs to be done only once.

```bash
# Navigate to your WordPress root (e.g., /Users/dayanleksonoputro/Local Sites/seo/app/public/)
php wp-content/plugins/programmaticseo/tools/post-generator.php --help
```

Upon its first run, if `pseo_api_key` is not set, the tool will generate a new key and display it. Make sure to save this key.

**Example output (first run):**
```
API Key generated: YOUR_GENERATED_API_KEY_HERE
Save this key for future use!
```
*(Replace `YOUR_GENERATED_API_KEY_HERE` with the actual key you receive.)*

## 3. Creating Programmatic SEO Posts

You can create posts using either the `post-generator.php` CLI tool or directly via the REST API.

### 3.1. Using `post-generator.php` (CLI Tool)

This tool provides a convenient way to generate posts from the command line.

**Usage:**
```bash
php wp-content/plugins/programmaticseo/tools/post-generator.php [options]
```

**Options:**

*   `--city=SLUG`: Create post for a single city slug (e.g., `malang`).
*   `--cities=a,b,c`: Create posts for multiple cities (comma-separated slugs).
*   `--all-cities`: Create posts for all cities currently in the `wp_cities` table.
*   `--status=STATUS`: Post status: `draft` (default), `publish`, `pending`.
*   `--delay=SECONDS`: Optional delay between post creation requests (default: `0`).
*   `--opening=FILE`: Path to a file containing custom opening content.
*   `--closing=FILE`: Path to a file containing custom closing content.
*   `--help`: Show help message.

**Examples:**

*   **Create a single post for 'Malang' and publish it:**
    ```bash
    php wp-content/plugins/programmaticseo/tools/post-generator.php --city=malang --status=publish
    ```
*   **Create posts for 'Surabaya' and 'Sidoarjo' as drafts with opening/closing content from files:**
    ```bash
    echo "Welcome to Surabaya, the city of heroes, for AC services!" > opening-surabaya.txt
    echo "For the best AC service in Surabaya, contact us today!" > closing-surabaya.txt
    
    echo "Sidoarjo offers top-notch AC repair. Find your service here." > opening-sidoarjo.txt
    echo "Don't delay your AC repair in Sidoarjo, get a quote now!" > closing-sidoarjo.txt
    
    php wp-content/plugins/programmaticseo/tools/post-generator.php --cities=surabaya,sidoarjo --status=draft --opening=opening-surabaya.txt --closing=closing-surabaya.txt
    ```
    *(Note: The `--opening` and `--closing` options currently only support a single file content for all cities in a batch. For unique content per city in batch, consider using the REST API with dynamic content generation.)*
*   **Create drafts for all cities with a 2-second delay:**
    ```bash
    php wp-content/plugins/programmaticseo/tools/post-generator.php --all-cities --delay=2 --status=draft
    ```

### 3.2. Using REST API (for Advanced Integrations)

For more flexible or programmatic integrations (e.g., with AI content generation or custom scripts), you can directly use the plugin's REST API endpoints.

**API Base URL:** `http://seo.local/wp-json/pseo/v1` (replace `http://seo.local` with your site URL)

**Authentication:** Include your `pseo_api_key` in the `X-PSEO-API-Key` header.

#### Create a Single Post

*   **Endpoint:** `POST /pseo/v1/posts`
*   **Parameters (JSON Body):**
    *   `city_slug` (required): The slug of the city for the post.
    *   `status`: Post status (`draft`, `publish`, `pending`, etc.). Default is `draft`.
    *   `opening_content`: Custom content for the opening paragraph.
    *   `closing_content`: Custom content for the closing paragraph.
    *   `post_type`: (`page`, `post`). Default is `page`.
    *   `author_id`: ID of the post author. Default is current user.
*   **Example (`curl`):**
    ```bash
    curl -X POST 
      http://seo.local/wp-json/pseo/v1/posts 
      -H "Content-Type: application/json" 
      -H "X-PSEO-API-Key: YOUR_GENERATED_API_KEY_HERE" 
      -d '{
        "city_slug": "malang",
        "status": "publish",
        "opening_content": "Selamat datang di Malang, pusat layanan AC terdepan! Temukan berbagai pilihan jasa AC berkualitas tinggi di kota dingin ini.",
        "closing_content": "Pilihlah penyedia jasa AC di Malang yang terbukti profesional dan terpercaya. Jaga kenyamanan ruangan Anda dengan layanan terbaik."
      }'
    ```

#### Create Posts in Batch

*   **Endpoint:** `POST /pseo/v1/posts/batch`
*   **Parameters (JSON Body):**
    *   `city_slugs`: Array of city slugs to create posts for (e.g., `["surabaya", "sidoarjo"]`).
    *   `all_cities`: Set to `true` to create posts for all cities in the database.
    *   `status`: Post status (same as single post).
    *   `opening_content`: Custom content for the opening paragraph (applied to all posts in batch).
    *   `closing_content`: Custom content for the closing paragraph (applied to all posts in batch).
    *   `delay`: Optional delay in seconds between each post creation.
*   **Example (`curl`):**
    ```bash
    curl -X POST 
      http://seo.local/wp-json/pseo/v1/posts/batch 
      -H "Content-Type: application/json" 
      -H "X-PSEO-API-Key: YOUR_GENERATED_API_KEY_HERE" 
      -d '{
        "city_slugs": ["surabaya", "sidoarjo"],
        "status": "draft",
        "opening_content": "Teks pembuka untuk batch...",
        "closing_content": "Teks penutup untuk batch..."
      }'
    ```

#### Get Available Cities and Template Information

*   **Endpoint:** `GET /pseo/v1/cities`
*   **Endpoint:** `GET /pseo/v1/template` (requires `X-PSEO-API-Key` header)
    *   Provides the default post template structure and a list of available placeholders like `{city_name}`, `{city_slug}`, `{service_name}`, `{province}`, `{opening_content}`, `{closing_content}`.

### 3.3. AI CLI Integration Workflow (Recommended)

This workflow leverages AI capabilities for dynamic content generation:

1.  **Get List of Cities:**
    *   Use `GET http://seo.local/wp-json/pseo/v1/cities` to retrieve all available cities.
    *   Filter out cities for which posts already exist if necessary.
2.  **Generate Dynamic Content (using AI):**
    *   For each target city, use an AI model (like Gemini) to generate unique `opening_content` and `closing_content`.
        *   **Prompt Example (Opening):** "Buat paragraf pembuka untuk artikel 'Jasa AC di [CITY_NAME]' dengan gaya ramah dan informatif, 150-200 kata."
        *   **Prompt Example (Closing):** "Buat paragraf penutup untuk artikel 'Jasa AC di [CITY_NAME]' dengan CTA hubungi penyedia jasa, 100-150 kata."
3.  **Create Post:**
    *   Use `POST http://seo.local/wp-json/pseo/v1/posts` for each city, including the AI-generated `opening_content` and `closing_content` in the JSON body.
4.  **Schedule/Publish:**
    *   Set the `status` parameter to `publish` or `future` (with an additional `post_date` parameter) as desired.

## 4. Managing Business Data (Crawling/Importing)

The `programmaticseo` plugin uses the `wp_businesses` database table to store local business information which is then displayed via shortcodes (`[local_business]`, `[business_map]`). The plugin provides admin UI for managing businesses but no direct API for bulk import or "crawling."

### 4.1. Understanding Business Data Structure

The `wp_businesses` table typically contains fields like:
*   `city_id` (foreign key to `wp_cities`)
*   `name`
*   `address`
*   `phone`
*   `whatsapp`
*   `rating`
*   `lat` (latitude)
*   `lng` (longitude)
*   `description`
*   `website`
*   `is_active`

### 4.2. Manual Business Entry (via WordPress Admin)

You can manually add or edit business listings through the WordPress admin area, under "Programmatic SEO" -> "Businesses."

### 4.3. Programmatic Business Import (Suggested Approach)

To "crawl" or import business data programmatically, you would typically need a custom script that gathers data from an external source (e.g., Google Maps API, a CSV file, another directory service) and then inserts or updates records directly into the `wp_businesses` table.

**General Steps for a Custom PHP Import Script:**

1.  **Data Source:** Identify and access your source of business data. This could involve using a web scraping library, an external API, or reading from a local file (CSV, JSON).
2.  **WordPress Bootstrap:** Create a PHP script in your WordPress root directory (or a sub-directory) that includes `wp-load.php` to access WordPress functions and the `$wpdb` object.
    ```php
    <?php
    // custom-business-importer.php
    require_once __DIR__ . '/wp-load.php'; 
    // Or, if in plugin: require_once dirname(__FILE__, 4) . '/wp-load.php'; 

    // Ensure this script is only runnable via CLI for security
    if (php_sapi_name() !== 'cli') {
        die('Access denied.');
    }

    global $wpdb;

    // ... your import logic below ...
    ?>
    ```
3.  **Fetch City IDs:** Before importing businesses, you'll need the corresponding `city_id` from the `wp_cities` table. You can fetch these using `$wpdb`.
    ```php
    // Example: Get city ID for 'malang'
    $malang_city = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}cities WHERE city_slug = %s", 'malang' ) );
    $malang_city_id = $malang_city ? $malang_city->id : 0;
    ```
4.  **Insert/Update Businesses:** Iterate through your gathered business data and use `$wpdb->insert()` or `$wpdb->update()` to add/modify entries in `{$wpdb->prefix}businesses`.
    ```php
    // Example: Insert a new business
    if ($malang_city_id) {
        $wpdb->insert(
            "{$wpdb->prefix}businesses",
            array(
                'city_id' => $malang_city_id,
                'name' => 'Toko AC Jaya Malang',
                'address' => 'Jl. Merdeka No.10, Malang',
                'phone' => '081234567890',
                'whatsapp' => '6281234567890',
                'rating' => 4.5,
                'lat' => -7.983908,
                'lng' => 112.621389,
                'description' => 'Penyedia jasa AC terbaik di Malang.',
                'website' => 'http://acjayamalang.com',
                'is_active' => 1
            )
        );
        echo "Business 'Toko AC Jaya Malang' added.
";
    }
    ```
5.  **Error Handling & Logging:** Implement robust error handling and logging to track the import process.

This custom script would be run via `php custom-business-importer.php` from your command line.
