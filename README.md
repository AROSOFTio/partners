# BenTech Collaborations Portal

A lightweight PHP 8.2 MVC-style app for YouTube collaboration and partnership requests for BenTech, billed by AROSOFT Innovations Ltd.

## Deployment (Contabo VPS)
1. Clone or upload this folder to the server.
2. Set web server DocumentRoot to `project_root/public`.
3. Create a MySQL database and import `sql/schema.sql`.
4. Copy `config/config.example.php` to `config/config.php` and update:
   - Database credentials
   - `base_url` (e.g., https://partners.bentechs.io)
   - Pesapal keys and callback URL
   - Mail/SMTP settings
5. Ensure `config/` is not publicly accessible and has restrictive permissions.
6. Restart Apache/Nginx/PHP-FPM after configuration changes.

## Notes
- Uses PDO with prepared statements and basic MVC routing via `public/index.php`.
- Admin auth uses `password_hash`/`password_verify`. Default admin: `admin@example.com` / `Admin123!` (seeded via schema and runtime check).
- Payments integrate with Pesapal via helper `config/pesapal.php`; swap the mock URL with the live iFrame endpoint when keys are configured.
- Tailwind CDN + Poppins font for UI; brand colors: #05C069 and #152228.
- Currency toggle in header (UGX base, USD default display, EUR option). Update static rates in `config/config.php`.
