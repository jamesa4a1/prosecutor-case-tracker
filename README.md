
## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Features](#features)
4. [Quick Start (Docker - Recommended)](#quick-start-docker--recommended)
5. [Manual Setup (No Docker)](#manual-setup-no-docker)
6. [Project Structure](#project-structure)
7. [Database Architecture](#database-architecture)
8. [API Endpoints](#api-endpoints)
9. [Development](#development)
10. [Troubleshooting](#troubleshooting)

---

## 📖 Project Overview

**AProsecutor Case Tracker** is a full-stack web application designed to streamline case management for prosecutors. The system tracks cases, associated hearings, parties involved, notes, and status history with a modern, user-friendly interface.

**Key Objectives:**
- Centralized case repository
- Real-time case status tracking
- Organized hearing and party management
- Comprehensive case notes and history
- Email notifications via integrated mail system

---

## 🛠️ Tech Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| **Backend** | Laravel | 12.x |
| **Database** | MySQL | 8.0 |
| **Frontend** | Blade Templates + Tailwind CSS | Latest |
| **Node Tools** | NPM | 10.x+ |
| **PHP** | PHP | 8.2+ |
| **Email Testing** | Mailhog | Latest |
| **Containerization** | Docker & Docker Compose | Latest |

---

## ✨ Features

### Case Management
- ✅ Create, read, update, delete (CRUD) cases
- ✅ Track case number, title, type, offense, filing date
- ✅ Assign to prosecutors and investigating officers
- ✅ Organize by station/agency

### Structured Organization
- ✅ Link multiple hearings to cases
- ✅ Manage case parties (defendants, witnesses, etc.)
- ✅ Add case notes and comments
- ✅ Track status changes with history

### Development Tools
- ✅ Local email capture with Mailhog
- ✅ Modern admin interface with Tailwind CSS
- ✅ Database migrations for version control
- ✅ Model factories for testing data

---

## 🚀 Quick Start (Docker - Recommended)

### Prerequisites
- ✅ Docker Desktop installed and running
- ✅ Git installed
- ✅ Windows PowerShell or Command Prompt

### Steps

#### 1. Clone & Setup

```powershell
git clone <repo-url> AProsecutor-website
cd AProsecutor-website\prosecutor-case-tracker
copy .env.example .env
Copy-Item .env.docker.example .env.docker
```

#### 2. Start Containers and Install Dependencies

```powershell
docker-compose up -d --build
docker-compose exec app composer install --no-interaction --prefer-dist
docker-compose exec app php artisan key:generate
docker-compose exec app npm ci
docker-compose exec app npm run build
```

#### 3. Database Setup

```powershell
docker-compose exec app php artisan migrate --seed
```

#### 4. Generate Storage Link

```powershell
docker-compose exec app php artisan storage:link
```

#### 5. Open in Browser

| Service | URL | Credentials |
|---------|-----|-------------|
| **App** | http://127.0.0.1:8000/login | (Create user) |
| **Mailhog UI** | http://localhost:8025 | No login needed |
| **phpMyAdmin** | http://localhost:8080 | Username: `laraveluser` / Password: `laravel_password` |

---

## run for local host
docker-compose up -d

docker-compose stop

## 🔧 Manual Setup (No Docker - Minimal)

### Prerequisites
- ✅ PHP 8.2+ with extensions (mysqli, pdo_mysql, zip, curl)
- ✅ Composer installed
- ✅ Node.js & NPM installed
- ✅ MySQL 8.0 running locally
- ✅ XAMPP (Apache + PHP + MySQL) recommended

### Steps

#### 1. Clone Repository

```powershell
git clone <repo-url> AProsecutor-website
cd AProsecutor-website\prosecutor-case-tracker
copy .env.example .env
```

#### 2. Install PHP Dependencies

```powershell
composer install
```

#### 3. Generate Application Key

```powershell
php artisan key:generate
```

#### 4. Install Frontend Dependencies

```powershell
npm ci
npm run build
```

#### 5. Configure Database in `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

#### 6. Run Migrations and Seeders

```powershell
php artisan migrate --seed
```

#### 7. Create Storage Link

```powershell
php artisan storage:link
```

#### 8. Start Development Server

```powershell
php artisan serve
```

Visit: http://localhost:8000

---

## 📁 Project Structure

```
prosecutor-case-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CaseController.php      # Case CRUD operations
│   │   │   ├── HearingController.php   # Hearing management
│   │   │   ├── ProsecutorController.php # Prosecutor management
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/
│   │   ├── CaseModel.php               # Case Eloquent model
│   │   ├── Prosecutor.php              # Prosecutor model
│   │   ├── Hearing.php                 # Hearing model
│   │   ├── CaseParty.php               # Party model
│   │   ├── Note.php                    # Notes model
│   │   └── StatusHistory.php           # Status tracking
│   └── Providers/
├── bootstrap/                           # Bootstrap files
├── config/                              # Configuration files
├── database/
│   ├── migrations/                      # Schema migrations
│   │   ├── 0001_*_create_users_table
│   │   ├── 0001_*_create_cache_table
│   │   ├── 0001_*_create_jobs_table
│   │   ├── 2025_*_create_prosecutors_table
│   │   ├── 2025_*_create_cases_table
│   │   ├── 2025_*_create_hearings_table
│   │   ├── 2025_*_create_case_parties_table
│   │   ├── 2025_*_create_notes_table
│   │   └── 2025_*_create_status_histories_table
│   ├── factories/                       # Model factories
│   └── seeders/                         # Database seeders
├── public/
│   ├── index.php                        # Application entry point
│   └── robots.txt
├── resources/
│   ├── css/                             # Stylesheets
│   │   └── app.css
│   ├── js/                              # JavaScript
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/                           # Blade templates
│       ├── layouts/
│       │   └── app.blade.php            # Master layout
│       ├── cases/
│       │   ├── index.blade.php          # Cases list
│       │   ├── create.blade.php         # Create case form
│       │   ├── show.blade.php           # Case detail
│       │   └── edit.blade.php           # Edit case form
│       └── welcome.blade.php
├── routes/
│   ├── web.php                          # Web routes
│   └── console.php                      # Console routes
├── storage/                             # File uploads & logs
├── tests/                               # Unit & feature tests
├── vendor/                              # Composer dependencies
├── .env.example                         # Environment template
├── docker-compose.yml                   # Docker compose configuration
├── vite.config.js                       # Vite build configuration
├── tailwind.config.js                   # Tailwind configuration
├── composer.json                        # PHP dependencies
├── package.json                         # Node dependencies
└── README.md                            # This file
```

---

## 🗄️ Database Architecture

### Entity Relationship Diagram

```
users (Laravel default)
  ├── id, name, email, password...

prosecutors
  ├── id (PK)
  ├── name
  ├── email
  ├── phone
  ├── office_address
  └── specialization

cases
  ├── id (PK)
  ├── case_number (unique)
  ├── title
  ├── type (Criminal/Civil/Special)
  ├── offense
  ├── date_filed
  ├── status
  ├── prosecutor_id (FK → prosecutors.id)
  ├── investigating_officer_id
  ├── agency_station
  └── notes (text)

hearings
  ├── id (PK)
  ├── case_id (FK → cases.id)
  ├── hearing_date
  ├── hearing_type
  ├── location
  └── notes (text)

case_parties
  ├── id (PK)
  ├── case_id (FK → cases.id)
  ├── party_name
  ├── party_type (Defendant/Witness/Plaintiff)
  ├── contact_info
  └── role_description

notes
  ├── id (PK)
  ├── case_id (FK → cases.id)
  ├── user_id (FK → users.id)
  ├── content (text)
  └── created_at, updated_at

status_histories
  ├── id (PK)
  ├── case_id (FK → cases.id)
  ├── old_status
  ├── new_status
  ├── changed_at
  └── reason (text)
```

### Relationships

| Model | Relationship | Target | Type |
|-------|-------------|--------|------|
| Case | belongsTo | Prosecutor | 1-to-Many |
| Case | hasMany | Hearing | 1-to-Many |
| Case | hasMany | CaseParty | 1-to-Many |
| Case | hasMany | Note | 1-to-Many |
| Case | hasMany | StatusHistory | 1-to-Many |
| Hearing | belongsTo | Case | Many-to-1 |
| CaseParty | belongsTo | Case | Many-to-1 |
| Note | belongsTo | Case | Many-to-1 |
| Note | belongsTo | User | Many-to-1 |
| StatusHistory | belongsTo | Case | Many-to-1 |

---

## 🔌 API Endpoints

### Cases Resource
```
GET    /cases              → List all cases
GET    /cases/create       → Show case creation form
POST   /cases              → Store new case
GET    /cases/{id}         → Show case details
GET    /cases/{id}/edit    → Show edit form
PUT    /cases/{id}         → Update case
DELETE /cases/{id}         → Delete case
```

### Additional Routes (To Be Implemented)
```
GET    /prosecutors        → List prosecutors
POST   /prosecutors        → Create prosecutor
GET    /hearings           → List hearings
POST   /hearings           → Create hearing
GET    /notes              → List case notes
POST   /notes              → Add note
```

---

## 🎨 Development

### Running the Development Server

#### Docker
```powershell
docker-compose up
php artisan serve
```

#### Local
```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

### Building Frontend Assets

#### Watch for Changes
```powershell
npm run dev
```

#### Production Build
```powershell
npm run build
```

### Creating New Models & Migrations

```powershell
php artisan make:model ModelName -m
php artisan make:controller ModelNameController --resource
php artisan make:migration create_table_name_table
```

### Running Tests

```powershell
php artisan test
php artisan test --filter=TestClassName
```

### Artisan Commands Reference

```powershell
php artisan migrate              # Run pending migrations
php artisan migrate:fresh        # Reset and re-run migrations
php artisan migrate:reset        # Rollback all migrations
php artisan migrate:rollback     # Rollback last batch
php artisan db:seed              # Seed database
php artisan tinker               # Interactive shell
php artisan cache:clear          # Clear cache
php artisan config:clear         # Clear config
php artisan view:clear           # Clear compiled views
```

---

## 🔍 Troubleshooting

### Common Issues & Solutions

#### ❌ Port 3306 Already in Use
```powershell
netstat -ano | findstr :3306
taskkill /PID <PID> /F
```

#### ❌ Docker Container Won't Start
```powershell
docker-compose down -v
docker-compose up -d --build
```

#### ❌ Migration Errors
```powershell
php artisan migrate:reset
php artisan migrate --seed
```

#### ❌ Permission Denied on Storage
```powershell
php artisan storage:link
chmod -R 775 storage
```

#### ❌ Composer Dependencies Conflict
```powershell
composer update
composer dump-autoload
```

#### ❌ Node Modules Issues
```powershell
rm -r node_modules package-lock.json
npm install
npm run build
```

#### ❌ Database Connection Failed
**Check .env:**
- `DB_HOST=127.0.0.1` (not `localhost`)
- `DB_PORT=3306`
- `DB_USERNAME` and `DB_PASSWORD` match MySQL credentials
- MySQL service is running

#### ❌ Mailhog Not Receiving Emails
- Verify `MAIL_HOST=127.0.0.1` and `MAIL_PORT=1025` in `.env`
- Check Mailhog UI: http://localhost:8025
- Restart Mailhog: `docker-compose restart mailhog`

---

## 🐳 Docker Commands Reference

### Start All Services
```powershell
cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"
docker-compose up -d
```

### View Running Services
```powershell
docker-compose ps
```

### Start Specific Service
```powershell
docker-compose start mysql
docker-compose start mailhog
docker-compose start phpmyadmin
```

### Stop All Services
```powershell
docker-compose stop
```

### Stop and Remove All Services
```powershell
docker-compose down
```

### View Logs
```powershell
docker-compose logs -f mysql       # MySQL logs
docker-compose logs -f mailhog     # Mailhog logs
docker-compose logs -f phpmyadmin  # phpMyAdmin logs
docker-compose logs -f             # All logs
```

### Restart Services
```powershell
docker-compose restart
docker-compose restart mysql
```

### Remove Everything and Start Fresh
```powershell
docker-compose down -v  # -v removes volumes
docker-compose up -d    # Start fresh
```

---

- **Laravel Docs**: [laravel.com/docs](https://laravel.com/docs)
- **Tailwind CSS**: [tailwindcss.com](https://tailwindcss.com)
- **Docker Docs**: [docker.com/resources/what-is-docker](https://docker.com/resources/what-is-docker)
- **MySQL Docs**: [dev.mysql.com](https://dev.mysql.com)

---

## 📄 License

The AProsecutor Case Tracker is open-source software licensed under the MIT license.

## to docker compose start and stop in powershell

cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"; docker compose start 

cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"; docker compose stop