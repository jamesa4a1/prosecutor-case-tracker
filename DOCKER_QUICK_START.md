# AProsecutor Docker Compose Configuration Guide

## File Location
This configuration should be saved as: `docker-compose.yml` in your project root directory

```yaml
services:
  # MySQL Database Service
  mysql:
    image: mysql:8.0
    container_name: aprosecutor-mysql
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel
      MYSQL_USER: laraveluser
      MYSQL_PASSWORD: laravel_password
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - aprosecutor-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 20s
      retries: 10

  # Mailhog Service for Email Testing
  mailhog:
    image: mailhog/mailhog:latest
    container_name: aprosecutor-mailhog
    restart: unless-stopped
    ports:
      - "1025:1025"  # SMTP Port
      - "8025:8025"  # Web UI Port
    networks:
      - aprosecutor-network

  # phpMyAdmin Service for Database Management
  phpmyadmin:
    image: phpmyadmin:5.2-apache
    container_name: aprosecutor-phpmyadmin
    restart: unless-stopped
    depends_on:
      - mysql
    environment:
      PMA_HOST: aprosecutor-mysql
      PMA_USER: laraveluser
      PMA_PASSWORD: laravel_password
      PMA_ROOT_PASSWORD: root
      PMA_PORT: 3306
    ports:
      - "8080:80"  # phpMyAdmin UI Port
    networks:
      - aprosecutor-network

volumes:
  mysql_data:
    driver: local

networks:
  aprosecutor-network:
    driver: bridge
```

## Quick Start Commands

### 1. Navigate to Project Directory
```powershell
cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"
```

### 2. Start All Services
```powershell
docker-compose up -d
```

### 3. View Running Services
```powershell
docker-compose ps
```

Expected output:
```
NAME                     IMAGE                    COMMAND        STATUS
aprosecutor-mysql        mysql:8.0                docker-php-... Up (healthy)
aprosecutor-mailhog      mailhog/mailhog:latest   MailHog        Up
aprosecutor-phpmyadmin   phpmyadmin:5.2-apache    /docker-e...   Up
```

## Service Details

### MySQL Database
- **Container Name**: aprosecutor-mysql
- **Host**: 127.0.0.1
- **Port**: 3306
- **Root Password**: root
- **Database**: laravel
- **User**: laraveluser
- **Password**: laravel_password
- **Data Volume**: mysql_data (persists between restarts)

### Mailhog (Email Testing)
- **Container Name**: aprosecutor-mailhog
- **SMTP Port**: 1025 (for Laravel to send emails)
- **Web UI Port**: 8025
- **URL**: http://localhost:8025
- **No authentication required**

### phpMyAdmin (Database Management)
- **Container Name**: aprosecutor-phpmyadmin
- **Web UI Port**: 8080
- **URL**: http://localhost:8080
- **Username**: laraveluser
- **Password**: laravel_password
- **Or use root**: root / root

## Common Docker Commands

### Start Services
```powershell
# Start all services
docker-compose up -d

# Start specific service
docker-compose start mysql
docker-compose start mailhog
docker-compose start phpmyadmin
```

### Stop Services
```powershell
# Stop all services (keeps containers)
docker-compose stop

# Stop and remove containers (keeps volumes)
docker-compose down

# Stop and remove everything including volumes
docker-compose down -v
```

### View Status
```powershell
# List running services
docker-compose ps

# View service logs
docker-compose logs -f mysql
docker-compose logs -f mailhog
docker-compose logs mailhog  # Show last logs without following

# View all logs
docker-compose logs -f
```

### Restart Services
```powershell
# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart mysql
```

## Troubleshooting

### Services Won't Start
1. **Check Docker is running**:
   ```powershell
   docker --version
   docker-compose --version
   ```

2. **Verify you're in the correct directory**:
   ```powershell
   pwd  # Shows current directory
   ls docker-compose.yml  # Confirms file exists
   ```

3. **Check if ports are in use**:
   ```powershell
   netstat -ano | findstr :3306   # MySQL port
   netstat -ano | findstr :1025   # Mailhog SMTP
   netstat -ano | findstr :8025   # Mailhog UI
   netstat -ano | findstr :8080   # phpMyAdmin
   ```

4. **Check Docker logs**:
   ```powershell
   docker-compose logs
   docker-compose logs mysql
   ```

### MySQL Won't Connect
- Verify `.env` has correct credentials:
  ```
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=laraveluser
  DB_PASSWORD=laravel_password
  ```

- Test connection from Laravel:
  ```powershell
  php artisan tinker
  DB::connection()->getPdo();  # Should return PDO object
  ```

### phpMyAdmin Shows "Cannot connect to MySQL"
- Ensure MySQL container is running:
  ```powershell
  docker-compose ps mysql  # Check status
  docker-compose logs mysql  # View errors
  ```

- Restart MySQL:
  ```powershell
  docker-compose restart mysql
  ```

### Email Not Being Captured by Mailhog
- Verify `.env` has correct settings:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=127.0.0.1
  MAIL_PORT=1025
  MAIL_ENCRYPTION=null
  ```

- Check Mailhog Web UI:
  ```
  http://localhost:8025
  ```

- Restart Mailhog:
  ```powershell
  docker-compose restart mailhog
  ```

## Environment Variables (.env)

Your `.env` file should include:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laraveluser
DB_PASSWORD=laravel_password

# Mail (Mailhog)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Network Architecture

```
┌─────────────────────────────────────────────────────┐
│         Docker Network: aprosecutor-network         │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────┐ │
│  │   MySQL      │  │   Mailhog    │  │phpMyAdmin│ │
│  │   :3306      │  │ :1025 :8025  │  │  :8080   │ │
│  └──────────────┘  └──────────────┘  └──────────┘ │
│                                                     │
└─────────────────────────────────────────────────────┘
         ↑
    Host Machine (Windows)
    127.0.0.1 or localhost
```

## Useful Links

After starting containers:

| Service | URL |
|---------|-----|
| Laravel App | http://localhost:8000 |
| Mailhog Web UI | http://localhost:8025 |
| phpMyAdmin | http://localhost:8080 |
| MySQL | 127.0.0.1:3306 |

## Next Steps

1. **Start Docker services**:
   ```powershell
   docker-compose up -d
   ```

2. **Run Laravel migrations**:
   ```powershell
   php artisan migrate:fresh --seed
   ```

3. **Access phpMyAdmin**:
   ```
   http://localhost:8080
   Username: laraveluser
   Password: laravel_password
   ```

4. **Monitor email**:
   ```
   http://localhost:8025
   ```

5. **Start Laravel dev server**:
   ```powershell
   php artisan serve
   ```

---

**Remember**: Always run `docker-compose up -d` from the project root directory where `docker-compose.yml` is located.
