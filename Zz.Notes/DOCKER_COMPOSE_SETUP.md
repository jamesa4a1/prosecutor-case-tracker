# AProsecutor Website - Docker Compose Setup

This `docker-compose.yml` file orchestrates the complete local development environment for the AProsecutor Case Tracker application, combining MySQL database and Mailhog email testing services.

## Services Overview

### 1. MySQL Database (`aprosecutor-mysql`)
- **Image**: `mysql:8.0`
- **Container Name**: `aprosecutor-mysql`
- **Port**: `3306`
- **Database**: `laravel`
- **User**: `laraveluser`
- **Password**: `laravel_password`
- **Root Password**: `root`
- **Volume**: `mysql_data` (persists database between container restarts)
- **Health Check**: Enabled to verify database readiness

### 2. Mailhog (`aprosecutor-mailhog`)
- **Image**: `mailhog/mailhog:latest`
- **Container Name**: `aprosecutor-mailhog`
- **SMTP Port**: `1025` (used by Laravel to send emails)
- **Web UI Port**: `8025` (access at http://localhost:8025)
- **Purpose**: Captures all outgoing emails in development for testing

## Quick Start

### Prerequisites
- Docker Desktop installed and running
- Windows PowerShell or command prompt

### Starting Services

```powershell
cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"
docker-compose up -d
```

### Verifying Services Are Running

```powershell
docker-compose ps
```

Expected output: Both `aprosecutor-mysql` and `aprosecutor-mailhog` should show status "Up"

### Stopping Services

```powershell
docker-compose down
```

### Removing All Data (Fresh Start)

```powershell
docker-compose down -v
docker-compose up -d
```

## Laravel Configuration

The `.env` file is already configured to use these services:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laraveluser
DB_PASSWORD=laravel_password

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_ENCRYPTION=null
```

## Running Migrations

After starting the services, run migrations to set up the database:

```powershell
php artisan migrate:fresh
```

## Accessing Services

| Service | URL/Host | Port | Purpose |
|---------|----------|------|---------|
| MySQL | `127.0.0.1` | `3306` | Database connection |
| Mailhog SMTP | `127.0.0.1` | `1025` | Email sending |
| Mailhog UI | `http://localhost:8025` | `8025` | View captured emails |

## Mailhog Features

- **Real-time Email View**: See all sent emails in the web interface
- **Email Details**: View full email content, headers, and raw MIME
- **No Email Actually Sent**: All emails are captured locally; nothing leaves your machine
- **Perfect for Testing**: Test email functionality without a real SMTP server

## Network

Both services are connected via the `aprosecutor-network` bridge network, allowing them to communicate internally using service names:
- MySQL: can be referenced as `aprosecutor-mysql` within Docker
- Mailhog: can be referenced as `aprosecutor-mailhog` within Docker

However, Laravel connects to them via `127.0.0.1` for localhost access.

## Troubleshooting

### Port Already in Use
If you get "port is already allocated" errors:
```powershell
docker-compose down
docker ps -a  # Check for lingering containers
docker rm <container_id>  # Remove old containers
docker-compose up -d
```

### Database Connection Failed
1. Ensure MySQL container is fully started (takes ~5-10 seconds)
2. Verify `.env` credentials match the compose file
3. Check container logs: `docker-compose logs mysql`

### Mailhog Not Receiving Emails
1. Verify MAIL_PORT=1025 in `.env`
2. Check Mailhog is running: `docker-compose ps`
3. View logs: `docker-compose logs mailhog`

## Volume Data

Database data is persisted in the `mysql_data` volume. This means:
- Restarting containers won't lose data
- `docker-compose down -v` will delete all data
- Data is stored at Docker's managed location (not visible in file system)

## Additional Commands

### View Logs
```powershell
docker-compose logs mysql      # View MySQL logs
docker-compose logs mailhog    # View Mailhog logs
docker-compose logs -f         # Follow all logs
```

### Execute Commands in Containers
```powershell
docker-compose exec mysql mysql -ularaveluser -plaravel_password laravel
```

### Restart a Specific Service
```powershell
docker-compose restart mysql
```
