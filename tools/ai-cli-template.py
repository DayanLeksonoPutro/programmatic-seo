#!/usr/bin/env python3
"""
Programmatic SEO - AI CLI Template
Python script for generating AI content and posting to WordPress

Requirements:
    pip install requests openai

Usage:
    python ai-cli-template.py --city bondowoso --api-key YOUR_API_KEY
    python ai-cli-template.py --all-cities --api-key YOUR_API_KEY --delay 5
"""

import requests
import argparse
import time
import os
from typing import List, Dict, Optional

# Configuration
SITE_URL = "https://your-site.com"  # Change this
API_ENDPOINT = f"{SITE_URL}/wp-json/pseo/v1"

# AI Configuration (Choose one)
OPENAI_API_KEY = os.getenv("OPENAI_API_KEY", "")
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY", "")


class PSEO_AI_Generator:
    def __init__(self, api_key: str, site_url: str = SITE_URL):
        self.api_key = api_key
        self.site_url = site_url
        self.api_endpoint = f"{site_url}/wp-json/pseo/v1"
        self.headers = {
            "Content-Type": "application/json",
            "X-PSEO-API-Key": api_key
        }
    
    def get_cities(self) -> List[Dict]:
        """Get all cities from WordPress"""
        response = requests.get(f"{self.api_endpoint}/cities")
        return response.json()
    
    def generate_opening(self, city_name: str, service_name: str, ai_provider: str = "openai") -> str:
        """Generate opening paragraph using AI"""
        prompt = f"""Buat paragraf pembuka untuk artikel SEO tentang "{service_name} di {city_name}".

Requirements:
- Panjang: 150-200 kata
- Gaya: Ramah, informatif, profesional
- Sertakan: Masalah umum, solusi, dan mengapa artikel ini penting
- Bahasa: Indonesia
- Target: Masyarakat {city_name} yang mencari jasa {service_name}

Tulis paragraf yang engaging dan SEO-friendly."""

        if ai_provider == "openai":
            return self._generate_openai(prompt)
        elif ai_provider == "gemini":
            return self._generate_gemini(prompt)
        else:
            return self._generate_template_opening(city_name, service_name)
    
    def generate_closing(self, city_name: str, service_name: str, ai_provider: str = "openai") -> str:
        """Generate closing paragraph using AI"""
        prompt = f"""Buat paragraf penutup untuk artikel "{service_name} di {city_name}".

Requirements:
- Panjang: 100-150 kata
- Sertakan: Ringkasan, rekomendasi, dan Call-to-Action (CTA)
- CTA: Ajak pembaca menghubungi penyedia jasa dari daftar
- Bahasa: Indonesia
- Tone: Meyakinkan dan membantu

Tulis paragraf penutup yang mendorong action."""

        if ai_provider == "openai":
            return self._generate_openai(prompt)
        elif ai_provider == "gemini":
            return self._generate_gemini(prompt)
        else:
            return self._generate_template_closing(city_name, service_name)
    
    def _generate_openai(self, prompt: str) -> str:
        """Generate using OpenAI API"""
        try:
            import openai
            openai.api_key = OPENAI_API_KEY
            
            response = openai.ChatCompletion.create(
                model="gpt-3.5-turbo",
                messages=[
                    {"role": "system", "content": "Kamu adalah penulis konten SEO profesional untuk website jasa lokal Indonesia."},
                    {"role": "user", "content": prompt}
                ],
                max_tokens=500,
                temperature=0.7
            )
            return response.choices[0].message.content.strip()
        except Exception as e:
            print(f"OpenAI Error: {e}")
            return ""
    
    def _generate_gemini(self, prompt: str) -> str:
        """Generate using Google Gemini API"""
        try:
            url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={GEMINI_API_KEY}"
            
            data = {
                "contents": [{
                    "parts": [{"text": prompt}]
                }]
            }
            
            response = requests.post(url, json=data)
            result = response.json()
            
            if "candidates" in result:
                return result["candidates"][0]["content"]["parts"][0]["text"].strip()
            return ""
        except Exception as e:
            print(f"Gemini Error: {e}")
            return ""
    
    def _generate_template_opening(self, city_name: str, service_name: str) -> str:
        """Fallback template for opening"""
        templates = [
            f"Mencari {service_name} terpercaya di {city_name}? Kami hadir untuk membantu Anda menemukan penyedia jasa terbaik di kota ini. Dengan berbagai pilihan layanan berkualitas, Anda dapat dengan mudah menemukan solusi yang sesuai dengan kebutuhan Anda.",
            f"{city_name} merupakan salah satu kota yang memiliki banyak penyedia {service_name} profesional. Dalam artikel ini, kami akan membantu Anda menemukan layanan terbaik dengan harga kompetitif.",
        ]
        import random
        return random.choice(templates)
    
    def _generate_template_closing(self, city_name: str, service_name: str) -> str:
        """Fallback template for closing"""
        templates = [
            f"Demikian informasi tentang {service_name} di {city_name}. Semoga daftar di atas dapat membantu Anda menemukan layanan terbaik. Jangan ragu untuk menghubungi penyedia jasa yang terdaftar untuk mendapatkan penawaran terbaik.",
            f"Pilihan {service_name} di {city_name} sangat beragam. Pilih yang paling sesuai dengan kriteria Anda dan pastikan untuk selalu memeriksa review sebelum memutuskan. Selamat mencoba!",
        ]
        import random
        return random.choice(templates)
    
    def create_post(self, city_slug: str, opening: str, closing: str, status: str = "draft") -> Dict:
        """Create post via REST API"""
        data = {
            "city_slug": city_slug,
            "status": status,
            "opening_content": opening,
            "closing_content": closing
        }
        
        response = requests.post(
            f"{self.api_endpoint}/posts",
            headers=self.headers,
            json=data
        )
        
        return response.json()
    
    def create_batch_posts(self, city_slugs: List[str], status: str = "draft", delay: int = 0) -> Dict:
        """Create multiple posts in batch"""
        data = {
            "city_slugs": city_slugs,
            "status": status
        }
        
        response = requests.post(
            f"{self.api_endpoint}/posts/batch",
            headers=self.headers,
            json=data
        )
        
        return response.json()
    
    def process_city(self, city: Dict, service_name: str, ai_provider: str, status: str) -> Dict:
        """Process single city: generate content and create post"""
        city_name = city["city_name"]
        city_slug = city["city_slug"]
        
        print(f"\n🔄 Processing: {city_name}")
        
        # Generate content
        print("  📝 Generating opening...")
        opening = self.generate_opening(city_name, service_name, ai_provider)
        
        print("  📝 Generating closing...")
        closing = self.generate_closing(city_name, service_name, ai_provider)
        
        # Create post
        print("  📤 Creating post...")
        result = self.create_post(city_slug, opening, closing, status)
        
        if result.get("success"):
            print(f"  ✅ Success! Post ID: {result['post_id']}")
            print(f"  🔗 URL: {result['url']}")
        else:
            print(f"  ❌ Failed: {result.get('message', 'Unknown error')}")
        
        return result


def main():
    parser = argparse.ArgumentParser(description="Programmatic SEO - AI Post Generator")
    parser.add_argument("--api-key", required=True, help="PSEO API Key")
    parser.add_argument("--site-url", default=SITE_URL, help="WordPress site URL")
    parser.add_argument("--city", help="Generate for single city slug")
    parser.add_argument("--cities", help="Generate for multiple cities (comma-separated)")
    parser.add_argument("--all-cities", action="store_true", help="Generate for all cities")
    parser.add_argument("--ai", choices=["openai", "gemini", "template"], default="template",
                       help="AI provider for content generation")
    parser.add_argument("--status", choices=["draft", "publish", "pending"], default="draft",
                       help="Post status")
    parser.add_argument("--delay", type=int, default=0, help="Delay between posts (seconds)")
    parser.add_argument("--limit", type=int, help="Limit number of cities to process")
    
    args = parser.parse_args()
    
    # Initialize generator
    generator = PSEO_AI_Generator(args.api_key, args.site_url)
    
    # Get cities
    print("📡 Fetching cities from WordPress...")
    all_cities = generator.get_cities()
    print(f"✅ Found {len(all_cities)} cities")
    
    # Filter cities
    if args.city:
        cities = [c for c in all_cities if c["city_slug"] == args.city]
    elif args.cities:
        city_slugs = [s.strip() for s in args.cities.split(",")]
        cities = [c for c in all_cities if c["city_slug"] in city_slugs]
    elif args.all_cities:
        cities = all_cities
    else:
        print("❌ Error: Please specify --city, --cities, or --all-cities")
        return
    
    if args.limit:
        cities = cities[:args.limit]
    
    print(f"\n🎯 Will process {len(cities)} cities")
    print(f"🤖 AI Provider: {args.ai}")
    print(f"📝 Post Status: {args.status}")
    print("=" * 50)
    
    # Get service name (you might want to fetch this from API)
    service_name = "Jasa AC"  # Change this or fetch from settings API
    
    # Process cities
    success_count = 0
    failed_count = 0
    
    for i, city in enumerate(cities):
        print(f"\n[{i+1}/{len(cities)}] ", end="")
        
        result = generator.process_city(city, service_name, args.ai, args.status)
        
        if result.get("success"):
            success_count += 1
        else:
            failed_count += 1
        
        if args.delay > 0 and i < len(cities) - 1:
            print(f"  ⏳ Waiting {args.delay}s...")
            time.sleep(args.delay)
    
    # Summary
    print("\n" + "=" * 50)
    print("📊 SUMMARY")
    print(f"✅ Success: {success_count}")
    print(f"❌ Failed: {failed_count}")
    print(f"📈 Total: {len(cities)}")


if __name__ == "__main__":
    main()
