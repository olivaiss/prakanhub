#!/usr/bin/env python3
"""
Scrape Allianz Thailand General Insurance pages (Motor, Property, Travel, Business)
Output: JSON file with all page data
"""

import json
import re
import requests
from bs4 import BeautifulSoup, Tag

OUTPUT_PATH = "D:/prakanhub/prakanhub.com/assets/data/allianz-general-raw.json"

URLS = [
    "https://www.allianz.co.th/th_TH/general-insurance.html",
    "https://www.allianz.co.th/th_TH/motor.html",
    "https://www.allianz.co.th/th_TH/motor/motor-type-1.html",
    "https://www.allianz.co.th/th_TH/motor/motor-type-2-plus.html",
    "https://www.allianz.co.th/th_TH/motor/motor-type-3-plus.html",
    "https://www.allianz.co.th/th_TH/motor/motor-type-3.html",
    "https://www.allianz.co.th/th_TH/motor/motor-ev.html",
    "https://www.allianz.co.th/th_TH/motor/insurance-for-SUVs.html",
    "https://www.allianz.co.th/th_TH/motor/aagi-motor-value-proposition.html",
    "https://www.allianz.co.th/th_TH/property.html",
    "https://www.allianz.co.th/th_TH/property/basic-home.html",
    "https://www.allianz.co.th/th_TH/property/master-home.html",
    "https://www.allianz.co.th/th_TH/property/perfect-home.html",
    "https://www.allianz.co.th/th_TH/travel-insurance.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/single-plan.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/annual-plan.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/schengen-visa-insurance.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/overseas-student-insurance.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/world-tour-insurance.html",
    "https://www.allianz.co.th/th_TH/travel-insurance/travel-plus-health.html",
    "https://www.allianz.co.th/th_TH/business.html",
    "https://www.allianz.co.th/th_TH/business/group-health.html",
]

KEYWORDS = [
    "คุ้มครอง", "เบี้ย", "วงเงิน", "ความเสียหาย", "รถ", "บ้าน", "เดินทาง",
    "กลุ่ม", "องค์กร", "สูงสุด", "เริ่มต้น", "ชั้น", "ซ่อม", "อู่", "ศูนย์",
    "ไฟไหม้", "น้ำ", "ภัย", "อุบัติเหตุ"
]

session = requests.Session()
session.headers.update({
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
})


def extract_headings(soup):
    """Extract all h1-h6 tags."""
    headings = {}
    for level in range(1, 7):
        tags = soup.find_all(f"h{level}")
        if tags:
            headings[f"h{level}"] = [
                {"text": h.get_text(strip=True), "html": str(h)[:500]}
                for h in tags
            ]
    return headings


def extract_lists(soup):
    """Extract all ul/ol list items."""
    lists = []
    for lst in soup.find_all(["ul", "ol"]):
        items = [li.get_text(strip=True) for li in lst.find_all("li")]
        if items:
            parent_text = ""
            prev = lst.find_previous(["h1", "h2", "h3", "h4", "h5", "h6", "p", "strong"])
            if prev:
                parent_text = prev.get_text(strip=True)[:200]
            lists.append({
                "parent_context": parent_text,
                "items": items
            })
    return lists


def extract_tabs_panels(soup):
    """Extract tab/panel/accordion content sections."""
    sections = []

    # Find tab-panes
    panes = soup.find_all(class_=re.compile("tab-pane|panel|pane", re.I))
    for pane in panes:
        pane_id = pane.get("id", "")
        pane_class = pane.get("class", [])
        pane_text = pane.get_text(strip=True)[:500]
        sections.append({
            "type": "tab-pane",
            "id": pane_id,
            "classes": pane_class,
            "text_preview": pane_text
        })

    # Find accordion items
    accordions = soup.find_all(class_=re.compile("accordion", re.I))
    for acc in accordions:
        items = acc.find_all(class_=re.compile("accordion-item|card", re.I))
        for item in items:
            header = item.find(class_=re.compile("accordion-header|card-header", re.I))
            body = item.find(class_=re.compile("accordion-body|card-body|collapse", re.I))
            sections.append({
                "type": "accordion-item",
                "header": header.get_text(strip=True) if header else "",
                "body_preview": body.get_text(strip=True)[:500] if body else ""
            })

    # Find content sections with headings
    for section_tag in soup.find_all(["section", "div"], class_=re.compile("section|content|block|module", re.I)):
        heading = section_tag.find(["h1", "h2", "h3", "h4", "h5", "h6"])
        if heading:
            sections.append({
                "type": "content-section",
                "heading": heading.get_text(strip=True),
                "text_preview": section_tag.get_text(strip=True)[:500]
            })

    return sections


def extract_keyword_context(soup):
    """Extract context around each keyword."""
    keyword_contexts = {}
    for kw in KEYWORDS:
        contexts = []
        for elem in soup.find_all(string=re.compile(re.escape(kw), re.I)):
            parent = elem.parent
            if parent and parent.name not in ["script", "style", "meta"]:
                context_text = parent.get_text(strip=True)[:300]
                siblings_text = ""
                for sib in parent.find_previous_siblings():
                    if sib.name in ["p", "div", "li"]:
                        siblings_text = sib.get_text(strip=True)[:200] + " | " + siblings_text
                    if len(siblings_text) > 300:
                        break
                contexts.append({
                    "parent_tag": parent.name,
                    "parent_text": context_text,
                    "sibling_context": siblings_text[:300]
                })
        if contexts:
            keyword_contexts[kw] = contexts
    return keyword_contexts


def extract_links(soup):
    """Extract relevant internal links."""
    links = []
    for a in soup.find_all("a", href=True):
        href = a["href"]
        text = a.get_text(strip=True)
        if text and "allianz.co.th" in href:
            links.append({
                "text": text[:200],
                "href": href
            })
    return links


def scrape_page(url):
    """Scrape a single page and return structured data."""
    print(f"Scraping: {url}")
    try:
        resp = session.get(url, timeout=30)
        resp.raise_for_status()
    except Exception as e:
        return {"url": url, "error": str(e)}

    resp.encoding = resp.apparent_encoding or "utf-8"
    soup = BeautifulSoup(resp.text, "html.parser")

    # Remove script/style elements
    for tag in soup(["script", "style", "noscript", "iframe", "meta", "link"]):
        tag.decompose()

    title = soup.title.get_text(strip=True) if soup.title else ""

    data = {
        "url": url,
        "title": title,
        "headings": extract_headings(soup),
        "lists": extract_lists(soup),
        "tabs_panels": extract_tabs_panels(soup),
        "keyword_contexts": extract_keyword_context(soup),
        "raw_text": soup.get_text(separator="\n", strip=True)[:10000],
        "links": extract_links(soup),
        "meta_tags": [
            {"name": m.get("name", ""), "content": m.get("content", "")[:500]}
            for m in soup.find_all("meta") if m.get("name") or m.get("property")
        ],
        "status_code": resp.status_code,
    }

    # Extract structured tables
    tables = []
    for table in soup.find_all("table"):
        rows = []
        for tr in table.find_all("tr"):
            cells = [td.get_text(strip=True) for td in tr.find_all(["td", "th"])]
            if cells:
                rows.append(cells)
        if rows:
            caption = table.find("caption")
            tables.append({
                "caption": caption.get_text(strip=True) if caption else "",
                "rows": rows
            })
    data["tables"] = tables

    print(f"  -> OK: {len(data['raw_text'])} chars raw text, {len(data['headings'])} heading groups, {len(data['lists'])} lists")
    return data


def main():
    results = []
    failed = []

    for i, url in enumerate(URLS, 1):
        print(f"\n[{i}/{len(URLS)}] {url}")
        data = scrape_page(url)
        if "error" in data:
            failed.append(url)
            print(f"  -> FAILED: {data['error']}")
        results.append(data)

    output = {
        "meta": {
            "total_urls": len(URLS),
            "success": len(URLS) - len(failed),
            "failed": len(failed),
            "failed_urls": failed,
            "scraped_at": __import__("datetime").datetime.now().isoformat()
        },
        "pages": results
    }

    with open(OUTPUT_PATH, "w", encoding="utf-8") as f:
        json.dump(output, f, ensure_ascii=False, indent=2)

    print(f"\n\nDone! {len(results)} pages scraped. Output: {OUTPUT_PATH}")
    if failed:
        print(f"Failed URLs: {failed}")


if __name__ == "__main__":
    main()
