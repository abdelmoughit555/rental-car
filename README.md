# 🚗 Autorockin

Autorockin is a car rental marketplace built with modern Laravel + Vue tooling. Think of it like Airbnb for cars, designed to support both individual owners and agencies in Morocco and beyond.

---

## ✨ Key Features (WIP)

- Car listings with rich details (make, model, features, images, availability)
- Availability calendar (block-out dates, mark unavailable)
- Multi-language support (English, French, Arabic; polymorphic translations planned)
- Location & maps (Google Maps search and address autocomplete)
- Image management by section (front view, interior, trunk, etc.)
- Smart recommendations (planned)
- Insights & transparency (price history, comparisons, views per hour)

---

## 🛠️ Tech Stack

- Backend: Laravel 12 (PHP 8.4), Sanctum, Fortify, Jetstream
- Frontend: Vue 3 + Inertia.js v2, Tailwind CSS 3
- Storage: MinIO (S3-compatible) locally, AWS S3 in production
- Database: SQLite for local dev by default; MySQL compatible
- Tooling: Vite, PHPUnit, Laravel Pint, Laravel Valet/Herd

---

## 🚀 Getting Started (Local)

Prereqs: PHP 8.4, Composer, Node 20+, npm, SQLite (or MySQL), and optionally MinIO.

1) Install dependencies

```bash
composer install
npm install
```

2) Environment

```bash
cp .env.example .env
php artisan key:generate
```

Default local DB is SQLite. Create the file if not present:

```bash
touch database/database.sqlite
```

3) Configure storage (MinIO / S3)

Set these in .env (for MinIO example):

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=minioadmin
AWS_SECRET_ACCESS_KEY=minioadmin
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=autorockin-local
AWS_ENDPOINT=http://127.0.0.1:9000
```

4) Migrate and seed

```bash
php artisan migrate --seed
```

This seeds fuel types, gearboxes, features catalog, etc. (see seeders in database/seeders).

5) Run dev servers

```bash
npm run dev
php artisan serve
```

If using Valet/Herd, you can skip `artisan serve` and visit the Valet URL.

---

## 🔐 Authentication & Authorization

- Fortify handles auth; Sanctum for API auth.
- Email verification is enforced at login.
- Dedicated login page at `/login` (Inertia) mirrors the previous modal UI.
- Policies are used for authorizing updates (e.g., cars).

---

## 🧱 Architecture & Conventions

- Validation in Form Requests; custom rules for cross-field logic.
  - Example: `App\Rules\AtLeastDaysAfter` enforces a min day gap between dates.
- Controllers are thin; business logic in Actions/Services.
  - Example: `App\Actions\Cars\UpdateCarAction` orchestrates car updates, features, and images in a transaction.
- Events & Jobs for side effects
  - `CarUpdated`, `CarSubmitted` events
  - Queued jobs: `ProcessImageUploadedMedia`, `ProcessCarImageDerivatives`
- Resources shape API responses (e.g., `CarResource`).

---

## 🖼️ Image Uploads (S3 Presign Flow)

1) Request a presigned PUT URL:

```http
POST /s3/presign
{
  "key": "car_images/front_view/filename.jpg",
  "content_type": "image/jpeg"
}
```

2) Use returned `url` and send a direct PUT to S3/MinIO, including the exact `Content-Type` header returned.

Response includes a convenience `object_url` (if public) and the required headers to send with the PUT.

---

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

Filter by file or test name:

```bash
php artisan test tests/Feature/Cars/CarControllerTest.php
php artisan test --filter=AtLeastDaysAfterTest
```

The suite covers feature endpoints for cars and unit tests for custom rules.

---

## 🧹 Developer UX

- Format PHP code:

```bash
vendor/bin/pint --dirty
```

- Useful Artisan commands:

```bash
php artisan route:list
php artisan migrate:fresh --seed
```

---

## 🌍 Environment Variables (Quick Reference)

Auth & App

```env
APP_URL=http://localhost
APP_ENV=local
```

Database (SQLite default)

```env
DB_CONNECTION=sqlite
```

S3 / MinIO

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=...
AWS_ENDPOINT=http://127.0.0.1:9000
```

---

## 📌 Roadmap

1. Core booking and rental flow
2. Payments integration (Morocco-friendly gateways)
3. AI-powered recommendations
4. Advanced analytics for owners (price trends, demand heatmaps)

---

## 👤 Author

**Abdelmoughit Fouham**  
[GitHub](https://github.com/abdelmoughit555) • [LinkedIn](https://www.linkedin.com/in/abdelmoughit-fouham/)

---

## ⚖️ License

This project is open-sourced under the [MIT License](LICENSE).
