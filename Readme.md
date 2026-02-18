
# Programmatic SEO – WordPress Niche Engine
## 1 Domain = 1 Jasa, Multi Kota (Kab/Kota)

## Tujuan
Membangun sistem WordPress programmatic SEO dengan pola:
- 1 domain = 1 jasa (hardcoded)
- Multi provinsi & kabupaten/kota
- Konten halaman dibuat semi-manual (AI CLI)
- Data bisnis ditampilkan dinamis via shortcode plugin

Target:
- SEO-friendly
- Lolos AdSense / Adstera
- Scalable
- Bisa dikloning ke banyak domain

---

## Arsitektur Sistem

### Stack
- WordPress (frontend + CMS)
- Custom WordPress Plugin (core engine) - Yang akan di develop
- MySQL (custom table)
- AI CLI (Gemini / GPT) untuk generate opening & closing konten

---

## Struktur URL

Format utama: -> Kalau bisa ada setingan di custom plugin untuk custom ini ,siap cloning
```
//Berikan ide struktur URL yang SEO
/jasa-ac-bondowoso/
/jasa-ac-jember/
/jasa-ac-banyuwangi/

```

Slug pattern:
```
//Berikan ide struktur URL yang SEO??
{service-slug}-{city-slug}

```

Contoh:
```

service = "jasa-ac"
city = "bondowoso"
=> jasa-ac-bondowoso

````

---

## Konsep Data

### Service (Hardcoded)
//bagian ini bisa di buatkan setingan aja dihalaman admin,cukup simpan setingan ini!!
Diset di plugin:
```php
define("SERVICE_NAME", "Jasa AC");
define("SERVICE_SLUG", "jasa-ac");
````

Tidak ada table `services`.

---

## Database
//Tolong carikan kota dan jatim sebagai data dasar simpan di @database.sql!!
### Table: wp_cities

```sql
id
province
city_name
city_slug
```

//carikan ide untuk crawler data ini???
Gunakan Google Places API atau serp scraping (brightdata/serpapi). Atau manual input via CSV import yang saya sediakan di plugin
### Table: wp_businesses

```sql
id
city_id
name
address
phone
rating
lat
lng
description
```

---

## Flow Produksi Konten

### 1. Input Data Manual

Admin input:

* Provinsi (via MySQL)
* Kabupaten (via MySQL)
* Data bisnis via Admin Panel (CRUD dengan filter)

### 2. Generate Artikel (AI CLI + REST API)

**Workflow:**
1. AI CLI (Python/PHP) generate opening & closing content
2. Kirim ke WordPress via REST API
3. Post otomatis dibuat dengan shortcode

**Metode:**

#### A. PHP CLI (Server)
```bash
php tools/post-generator.php --city=bondowoso --status=draft
```

#### B. Python AI CLI (Local)
```bash
python tools/ai-cli-template.py \
  --api-key YOUR_KEY \
  --city bondowoso \
  --ai gemini
```

#### C. Direct API Call
```bash
curl -X POST /wp-json/pseo/v1/posts \
  -H "X-PSEO-API-Key: KEY" \
  -d '{"city_slug":"bondowoso","opening":"...","closing":"..."}'
```

**Struktur Artikel Otomatis:**
```
[OPENING AI]

[local_business city="bondowoso"]
[business_map city="bondowoso"]

## Kenapa memilih jasa AC di Bondowoso?
(Template SEO)

## Tips memilih jasa AC terpercaya
(Template SEO)

[CLOSING AI]

[city_links exclude="bondowoso"]
```

---

## Shortcode Plugin

### Shortcode utama

```
[local_business city="bondowoso"]
```

### Output shortcode

Menampilkan:

* List bisnis di kota tsb
* Nama
* Alamat
* Telepon
* Rating
* Tombol WhatsApp / Call
* open Google map


* Embed map dari daftar list bisnis di kota tersebut

---

## Contoh Implementasi Shortcode

```php
add_shortcode('local_business', function($atts) {
    $city = $atts['city'];
    $data = get_business_by_city($city);
    return render_business_list($data);
});
```

---

## Struktur Konten Halaman

Minimal struktur:

H1: Jasa AC di Bondowoso
Intro (AI)

[Shortcode bisnis]

## Kenapa memilih jasa AC di Bondowoso?

Text AI

## Tips memilih jasa AC terpercaya

Text AI

FAQ (optional, AI)

---

## SEO Requirement

Wajib:

* 600–900 kata per halaman
* Schema:

  * LocalBusiness
  * FAQPage
  * Breadcrumb
* Internal linking:

  * Antar kota
* Sitemap:

  * Auto generate semua kota

---

## Internal Linking Logic

Auto di plugin:

* Footer:

  * "Jasa AC di kota lain:"
  * Loop 5–10 kota random

---

## Admin Panel Plugin

Menu:

* Cities
* Businesses
* Generate Sitemap
* Settings (service name)

---

## REST API

Plugin menyediakan REST API untuk integrasi dengan AI CLI tools dan external scripts.

### Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/wp-json/pseo/v1/posts` | POST | API Key | Create single post |
| `/wp-json/pseo/v1/posts/batch` | POST | API Key | Create multiple posts |
| `/wp-json/pseo/v1/cities` | GET | Public | Get all cities |
| `/wp-json/pseo/v1/template` | GET | API Key | Get post template |

### Authentication

Gunakan API Key via header:
```
X-PSEO-API-Key: your_api_key_here
```

API Key tersimpan di WordPress options (`pseo_api_key`).

### Contoh: Create Single Post

```bash
curl -X POST https://your-site.com/wp-json/pseo/v1/posts \
  -H "Content-Type: application/json" \
  -H "X-PSEO-API-Key: YOUR_API_KEY" \
  -d '{
    "city_slug": "bondowoso",
    "status": "draft",
    "opening_content": "Konten pembuka dari AI...",
    "closing_content": "Konten penutup dari AI..."
  }'
```

### Contoh: Batch Create

```bash
curl -X POST https://your-site.com/wp-json/pseo/v1/posts/batch \
  -H "Content-Type: application/json" \
  -H "X-PSEO-API-Key: YOUR_API_KEY" \
  -d '{
    "all_cities": true,
    "status": "draft"
  }'
```

Response:
```json
{
  "success": true,
  "total": 38,
  "created": 38,
  "failed": 0,
  "results": [...]
}
```

---

## CLI Tools

### PHP CLI (Server-side)

```bash
# Single city
php wp-content/plugins/programmaticseo/tools/post-generator.php --city=bondowoso

# Multiple cities
php post-generator.php --cities=bondowoso,jember,malang --status=publish

# All cities dengan delay
php post-generator.php --all-cities --delay=5 --status=draft
```

### Python AI CLI (Local/External)

```bash
# Setup
pip install requests openai

# Generate dengan AI (OpenAI)
python tools/ai-cli-template.py \
  --api-key YOUR_API_KEY \
  --site-url https://your-site.com \
  --city bondowoso \
  --ai openai

# Batch semua kota dengan AI
python tools/ai-cli-template.py \
  --api-key YOUR_API_KEY \
  --all-cities \
  --ai gemini \
  --delay 5
```

Environment variables:
```bash
export OPENAI_API_KEY="sk-..."
export GEMINI_API_KEY="..."
```

---

## AI Integration Workflow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   AI CLI    │────▶│  REST API   │────▶│  WordPress  │
│ (Python/PHP)│     │  (PSEO)     │     │   (Posts)   │
└─────────────┘     └─────────────┘     └─────────────┘
```

1. **Get Cities** → `/wp-json/pseo/v1/cities`
2. **Generate Content** → OpenAI/Gemini API
3. **Create Posts** → `/wp-json/pseo/v1/posts/batch`

---

## Admin Panel Plugin

Menu:

* Cities
* Businesses
* Generate Sitemap
* Settings (service name)

---

## Cron & Scaling

**Rekomendasi:**
- Gunakan CLI tools dengan delay untuk natural growth
- Schedule via cron: `5 postingan/hari`
- Status: draft → review → publish

**Cron Job Example:**
```bash
# 5 posts per day at random times
0 9,12,15,18,21 * * * /usr/bin/php /path/to/post-generator.php --city=$(shuf -n1 cities.txt) --status=draft
```

**Target:**
* 5–10 halaman / hari (via API)
* Aman indexing
* Natural growth

---

## Monetisasi

### Web:

* Google AdSense
* Adstera
* Lead form WhatsApp
* Affiliate lokal

### Future:

* App WebView
* AdMob
* Premium listing

---

## Prinsip SEO Utama

* 1 domain = 1 jasa
* Data bisnis real
* Konten tebal
* Tidak auto-spin
* Tidak doorway page

---

## Goal Final

Sistem ini harus bisa:

* Dikloning ke domain lain
* Ganti:
  SERVICE_NAME
  SERVICE_SLUG diubah dengan setingan di plugin yang sedang di buat
* Langsung jalan untuk niche baru

Contoh kloning:

* jasaacjatim.com
* sewamobiljatim.com
* kursusinggrisjatim.com

Core engine tetap sama.

---

## Mental Model

Service = Brand
City = Market
Business = Product

Jangan campur brand dalam satu domain.

---

## Success Metric

Per domain:

* 100+ halaman
* Tembus AdSense
* Traffic organik stabil
* Bisa di-scale ke 5–10 domain

```

---

Ini sudah level **engineering spec**, bukan sekadar ide.

Kalau agentic coding kamu kuat, dari README ini dia bisa langsung:
- generate plugin
- generate schema
- generate admin panel
- generate shortcode engine

Dan kamu tinggal fokus ke:
**input data + generate konten AI + scale domain.**
```
