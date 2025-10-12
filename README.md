# Digital Edge Test

Concise technical overview with only the requested sections.

## SYSTEM ARCHITECTURE

- Backend: Laravel 12 (PHP 8.2), service layer in `app/Services`, data via Eloquent ORM.
- Auth: Web + API via Sanctum; roles/permissions via Spatie Permission.
- Layers: Controllers → Services → Repositories → Models.
- Jobs/Queues: Optional for email and heavy processing.
- Caching/Broadcast: Redis/Pusher when needed.
- Frontend: Blade + Tailwind + Vite; admin UI JS in `resources/js`.

Simple flow:
1) Request → 2) `Controller` → 3) `Service` (business rules) → 4) `Repository`/`Model` → 5) Response (Resource/View)


## DATABASE DESIGN

Core tables and relationships:

- users
  - Fields: id, first_name, last_name, email, phone, country_id, city_id, email_verified_at, password, deleted_at, timestamps
  - Relations: `belongsTo(country)`, `belongsTo(city)`, `belongsToMany(products)`

- countries
  - Fields: id, name_en, name_ar, timestamps
  - Relations: `hasMany(cities)`, `hasMany(users)`

- cities
  - Fields: id, country_id, name_en, name_ar, timestamps
  - Relations: `belongsTo(country)`, `hasMany(users)`

- products
  - Fields: id, title_en, title_ar, description_en, description_ar, price, primary_image, other_images(json), timestamps
  - Relations: `belongsToMany(users)`

- product_user (pivot)
  - Fields: id, product_id, user_id, assigned_at, unassigned_at, timestamps
  - Relations: `belongsTo(product)`, `belongsTo(user)`

- personal_access_tokens (Sanctum)

- roles/permissions (Spatie)
  - Tables: roles, permissions, model_has_roles, model_has_permissions, role_has_permissions

- notifications
  - Fields: id, type, notifiable_type, notifiable_id, data(json), read_at, timestamps

- activity_log (Spatie Activitylog)
  - Main fields: id, log_name, description, subject_type/id, causer_type/id, event, properties(json), batch_uuid, created_at

Design notes:
- Foreign keys and indexes on `country_id`, `city_id`, `product_id`, `user_id` for performance.
- Soft deletes for users.
- JSON columns for composite fields such as `other_images` and `properties`.

## DEFAULT CREDENTIALS

Admin
- Email: `admin@example.com`
- Password: `p@ssw0rd.123`

User
- Email: `user@example.com`
- Password: `p@ssw0rd.123`

## QUICK START

1. Clone and install dependencies
```bash
git clone <repository-url>
cd digital-edge-test
composer install
npm install
```

2. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

3. Database (SQLite by default)
```bash
touch database/database.sqlite
php artisan migrate --seed
```

4. Storage symlink (for public files)
```bash
php artisan storage:link
```

5. Run the app
```bash
php artisan serve
npm run dev
```

6. Login
- Admin: `admin@example.com` / `p@ssw0rd.123`

## GITHUB REPOSITORY

Replace with your repository URL:
`https://github.com/ahmadalalony/digital-edge-test`

## EMAIL (SANDBOX)

Emails in development/staging were sent to a sandbox using [Mailtrap](https://mailtrap.io).

- SMTP/API provider: Mailtrap
- Purpose: capture and inspect emails safely in non-production
- Notes: Configure SMTP/API credentials in `.env` and use the sandbox inbox for verification and QA