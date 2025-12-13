# 🐳 Complete Docker Setup - AProsecutor Case Tracker

## ✅ Your docker-compose.yml Configuration

**Location**: `C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker\docker-compose.yml`

**Status**: ✅ File exists and is properly configured

## 🚀 Quick Start - Copy & Paste These Commands

### Step 1: Navigate to Project Directory
```powershell
cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"
```

### Step 2: Start All Services
```powershell
docker-compose up -d
```

### Step 3: Verify Services Are Running
```powershell
docker-compose ps
```

**Expected Output:**
```
NAME                     IMAGE                    STATUS
aprosecutor-mysql        mysql:8.0                Up (healthy)
aprosecutor-mailhog      mailhog/mailhog:latest   Up
aprosecutor-phpmyadmin   phpmyadmin:5.2-apache    Up
```

## 🌐 Access Your Services

Once running, open these URLs in your browser:

| Service | URL | Purpose |
|---------|-----|---------|
| **Laravel App** | http://localhost:8000 | Your application |
| **Mailhog** | http://localhost:8025 | Email testing/monitoring |
| **phpMyAdmin** | http://localhost:8080 | Database management |

## 🔑 Credentials

### MySQL Database
```
Host: 127.0.0.1
Port: 3306
Database: laravel
Username: laraveluser
Password: laravel_password
Root Password: root
```

### phpMyAdmin Login
```
Username: laraveluser
Password: laravel_password

OR

Username: root
Password: root
```

### Mailhog
```
No login required - just open http://localhost:8025
```

## 📋 Common Docker Commands

### View Container Status
```powershell
docker-compose ps
```

### View Container Logs
```powershell
# View specific service
docker-compose logs mysql
docker-compose logs mailhog
docker-compose logs phpmyadmin

# View all logs and follow live
docker-compose logs -f

# View last 50 lines
docker-compose logs --tail=50
```

### Start/Stop Services

```powershell
# Start all services
docker-compose up -d

# Start specific service
docker-compose start mysql
docker-compose start mailhog

# Stop all services (keeps containers)
docker-compose stop

# Stop specific service
docker-compose stop mysql

# Restart all services
docker-compose restart

# Restart specific service
docker-compose restart mysql
```

### Remove Services

```powershell
# Remove containers but keep volumes (data persists)
docker-compose down

# Remove everything including volumes (WARNING: deletes data)
docker-compose down -v

# Remove everything and start fresh
docker-compose down -v
docker-compose up -d
```

## 🔧 Laravel Setup Commands

After starting Docker services, run these in a new terminal:

```powershell
# 1. Navigate to project
cd "C:\Users\HP User\Desktop\Aprosecutor website\prosecutor-case-tracker"

# 2. Install PHP dependencies (if not done)
composer install

# 3. Generate app key (if not done)
php artisan key:generate

# 4. Run migrations and seeders
php artisan migrate:fresh --seed

# 5. Start Laravel development server
php artisan serve
```

## 📊 Docker Container Details

### MySQL Service
```yaml
Container Name: aprosecutor-mysql
Image: mysql:8.0
Ports: 3306:3306
Volumes: mysql_data (persistent storage)
Network: aprosecutor-network
Auto-restart: Yes
Health Check: Enabled
```

### Mailhog Service
```yaml
Container Name: aprosecutor-mailhog
Image: mailhog/mailhog:latest
Ports: 1025:1025 (SMTP), 8025:8025 (Web UI)
Network: aprosecutor-network
Auto-restart: Yes
```

### phpMyAdmin Service
```yaml
Container Name: aprosecutor-phpmyadmin
Image: phpmyadmin:5.2-apache
Ports: 8080:80
Network: aprosecutor-network
Auto-restart: Yes
Depends on: MySQL service
```

## 🌐 Network Connectivity

All three services are connected via a bridge network called `aprosecutor-network`:

```
┌──────────────────────────────────────────┐
│  aprosecutor-network (Bridge Network)   │
├──────────────────────────────────────────┤
│                                          │
│  MySQL ←→ phpMyAdmin                    │
│  MySQL ←→ Laravel App                   │
│  Mailhog ←→ Laravel App                 │
│                                          │
└──────────────────────────────────────────┘
        ↓ (Exposed to Host)
   127.0.0.1 / localhost
   Port 3306, 1025, 8025, 8080
```

## ✅ Verification Checklist

After running `docker-compose up -d`, verify:

- [ ] MySQL is running and healthy
  ```powershell
  docker-compose ps mysql  # Should show "healthy"
  ```

- [ ] Can connect to MySQL
  ```powershell
  mysql -h 127.0.0.1 -u laraveluser -plaravel_password laravel
  ```

- [ ] phpMyAdmin is accessible
  ```
  http://localhost:8080 - Should load login page
  ```

- [ ] Mailhog is accessible
  ```
  http://localhost:8025 - Should show inbox interface
  ```

- [ ] Laravel .env is configured
  ```
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=laraveluser
  DB_PASSWORD=laravel_password
  ```

## 🚨 Troubleshooting

### "docker-compose: command not found"
**Solution**: Install Docker Desktop for Windows
- Download: https://www.docker.com/products/docker-desktop
- Restart your terminal after installation

### "Ports already in use"
**Solution**: Find and stop conflicting services
```powershell
# Find what's using port 3306
netstat -ano | findstr :3306

# Kill the process (replace PID)
taskkill /PID 12345 /F

# Or change port in docker-compose.yml
# Change "3306:3306" to "3307:3306"
```

### "Cannot connect to MySQL"
**Solution**: Check MySQL container status
```powershell
docker-compose logs mysql
docker-compose restart mysql
```

### "phpMyAdmin shows blank page"
**Solution**: Restart phpMyAdmin
```powershell
docker-compose restart phpmyadmin
```

### "Mailhog not capturing emails"
**Check .env settings**:
```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_ENCRYPTION=null
```

Then restart Laravel server and test email sending.

## 📚 Documentation Files

Refer to these files in your project:

- **`docker-compose.yml`** - Docker configuration (this file)
- **`DOCKER_QUICK_START.md`** - Quick reference guide
- **`DOCKER_COMPOSE_SETUP.md`** - Detailed setup guide
- **`README.md`** - Project overview with docker commands

## 🎯 Next Steps

1. ✅ **Verify all services are running**
   ```powershell
   docker-compose ps
   ```

2. ✅ **Access phpMyAdmin**
   ```
   http://localhost:8080
   Login: laraveluser / laravel_password
   ```

3. ✅ **Check Mailhog**
   ```
   http://localhost:8025
   ```

4. ✅ **Setup Laravel**
   ```powershell
   php artisan migrate:fresh --seed
   php artisan serve
   ```

5. ✅ **Access Laravel**
   ```
   http://localhost:8000
   ```

## 📞 Support

For Docker issues:
- Docker Docs: https://docs.docker.com/
- Docker Compose: https://docs.docker.com/compose/

For Laravel issues:
- Laravel Docs: https://laravel.com/docs
- Laravel Community: https://laracasts.com

---

**Your Docker setup is complete and ready to use! 🎉**

Run: `docker-compose up -d` to start all services anytime.
