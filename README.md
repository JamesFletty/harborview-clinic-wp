# Harborview Clinic — custom WordPress theme

**Live demo:** [harborview-clinic-demo.vercel.app](https://harborview-clinic-demo.vercel.app) — a static render of the theme's templates with sample content.

A classic (PHP template) theme written from scratch for a fictional independent behavioral and primary care practice. No parent theme, no page builder, no ACF, no jQuery. The site is built around one rule: **the public website collects no protected health information.**

## Architecture

```
harborview-clinic/
├── style.css                 Theme header (Text Domain: harborview, Requires PHP: 8.1)
├── theme.json                v3: AA-checked palette, self-hosted fonts, fluid type, spacing, shadow
├── functions.php             Thin loader
├── inc/
│   ├── setup.php             Supports, menus, Customizer "Practice details" (HTTPS-only portal URL)
│   ├── assets.php            filemtime() versions, defer strategy, conditional directory script
│   ├── cpt-clinicians.php    hv_clinician CPT + hv_specialty taxonomy + native meta box
│   ├── rest-endpoints.php    GET /wp-json/harborview/v1/clinicians (public, read-only, cached)
│   ├── hardening.php         Head cleanup, XML-RPC off, comments off, REST restrictions, headers
│   └── template-tags.php     Initials, status pill, portal link, menu fallback, brand mark
├── template-parts/           site-header, site-footer, clinician-card, portal-handoff, faq, hero-illustration
├── front-page.php  single-hv_clinician.php  page.php  index.php  404.php
└── assets/
    ├── css/                  base.css · layout.css · components.css
    ├── js/                   main.js (drawer, accordion, reveal) · clinician-filter.js
    └── fonts/                Source Serif 4, Public Sans (woff2, self-hosted)
```

## Content model

- **`hv_clinician`** post type at `/clinicians/<slug>/`, REST-enabled.
- **`hv_specialty`** taxonomy, non-hierarchical, with an admin column.
- **Native meta box** for Credentials, Role, Pronouns, Licensure (one per line), Languages, *Accepting new patients* and *Offers telehealth*. One schema, `harborview_clinician_fields()`, drives `register_post_meta()`, the UI and saving. Saving checks the nonce, skips autosaves and revisions, checks capabilities and sanitizes every field through its schema. Unchecked boxes are stored explicitly as `false`.

## REST endpoint

`GET /wp-json/harborview/v1/clinicians[?specialty=<slug>&accepting=1]`

- `permission_callback => '__return_true'` is set on purpose: the endpoint returns only what is already printed on the site.
- Arguments are declared, typed and sanitized (`sanitize_key`, boolean).
- The payload has public profile fields only: id, name, url, initials, credentials, role, languages, accepting, telehealth and specialties.
- Responses are cached in a transient, and the cache is flushed on clinician save, trash or delete and on specialty changes. The endpoint sends `Cache-Control: public, max-age=300`.

`clinician-filter.js` fetches the list once with `credentials: 'omit'`, indexes it by ID and filters the server-rendered cards client-side by specialty and accepting status. The selected specialty is kept in `?specialty=` and the result count is announced through `aria-live`. If the request fails, the script falls back to the `data-*` attributes already in the markup, so the directory always works, even with JS disabled.

## HIPAA-aware patient portal handoff

- **No forms anywhere** on the public site. Comments and pings are closed site-wide and the comments REST routes are hidden.
- Every "become a patient" and "request an appointment" action is an outbound link to the practice's EHR portal (set in the Customizer). The link uses `rel="noopener"` and `referrerpolicy="no-referrer"`, and the URL is sanitized to **HTTPS only**.
- The portal section explains what belongs on the website and what belongs in the encrypted portal. The footer tells visitors not to email or text health information.
- There is no third-party font CDN, analytics pixel or chat widget, so no outside party learns which pages a patient visited.
- A crisis notice (988 / 911) appears at the top of every page and on the 404 page.

## Accessibility (WCAG 2.2 AA)

- Body text pairs are checked for contrast: pine on alabaster is 12.9:1 and muted text is 6.6:1. Sage and clay are used only for large type and decoration, and clay text uses the darker `#98502F` (5.1:1).
- The FAQ follows the WAI-ARIA APG accordion pattern, with buttons, `aria-expanded`, regions, and Arrow, Home and End keys. Collapsed panels use `hidden="until-found"`, so the browser's find-in-page still reaches the answers.
- The pages also include a skip link, landmarks, a labelled filter group with `aria-pressed`, a native checkbox switch, a drawer that closes on Escape, and `prefers-reduced-motion` support.

## Performance and security

`filemtime()` cache busting, deferred footer scripts (the directory script loads only on the front page), preloaded self-hosted fonts and inline SVG artwork. Emoji, RSD, wlwmanifest, generator and shortlink output is removed, XML-RPC is disabled, user enumeration is blocked, and HSTS, nosniff, frame, referrer and permissions headers are sent. The headers can be filtered via `harborview_security_headers`.

## Install

1. Download this repository as a ZIP (**Code → Download ZIP**), or clone it into `wp-content/themes/`.
2. In WordPress, go to **Appearance → Themes → Add New → Upload**, choose the ZIP and activate the theme.
3. Add Clinicians and Specialties, set a static front page, and fill in Customizer → Practice details (phone, address, hours, portal URL).

## Demo content

Harborview Clinic is a fictional practice created to demonstrate the theme. Names, addresses, phone numbers (555), clinicians, credentials, insurance plans and rates are sample content only.
