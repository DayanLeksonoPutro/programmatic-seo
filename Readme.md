
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

* Provinsi //tidak perlu saya maintenace via mysql aja
* Kabupaten //tidak perlu saya maintenace via mysql aja
* Data bisnis (AC / sesuai niche)  // Bisa buatkan CRUD nya beserta Filter yang dibutuhkan

### 2. Generate Artikel (Manual + AI)
//rencana saya saya buat web nya secara local , dengan setup database dan penjadwalan postingan 1 hari 5 postingan!!
//Berikan ide cata genereate nya ??
Buat Python/Node CLI tool yang: 1) Baca template, 2) Call OpenAI/Gemini API, 3) Output file .md atau langsung insert ke WP via REST API // akan saya lakukan di local gemini cli
Untuk setiap kota:

* Generate konten pakai AI CLI:

  * Opening paragraf
  * Closing paragraf
* Paste ke editor WordPress

Isi artikel:

```
[OPENING AI]

[SHORTCODE BISNIS]

[CLOSING AI]
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

## Cron & Scaling

Tidak pakai auto-generate massal.
Halaman dibuat manual + AI.

Target:

* 10–30 halaman / hari
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
