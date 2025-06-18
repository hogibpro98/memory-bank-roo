# Docker Context Documentation

## Service Configuration

### 🌐 Web Service (PHP 7.4 + Apache)
- **Container**: `web`
- **Image**: `src-web` (custom build)
- **Port**: `8080:80`
- **PHP Version**: `PHP 7.4.33 (cli)`
- **Web Server**: `Apache/2.4.54 (Debian)`
- **Document Root**: `/var/www/html`

### 🗄️ Database Service (MySQL 8.0)
- **Container**: `db`
- **Image**: `mysql:8.0`
- **Port**: `3306:3306`
- **Database**: `kantaki_dev`
- **User**: `kantaki` (legacy)
- **Root Password**: `rootpassword` (recommended for UTF-8)
- **Character Set**: `utf8mb4` (cho tiếng Nhật)
- **Collation**: `utf8mb4_unicode_ci`

### 🔧 Build Service (ESBuild Watcher)
- **Container**: `web_esbuild`
- **Purpose**: Asset compilation & watching
- **Command**: `npm install && npm run watch`
- **Status**: Watching for changes...

### 🌐 Reverse Proxy (Caddy)
- **Container**: `caddy`
- **Image**: `caddy:latest`
- **Port**: `80:80`
- **Purpose**: HTTP reverse proxy

## Container Management Commands

### 🚀 Start/Stop Services
```bash
# Start all services
docker compose up -d

# Stop all services
docker compose down

# Restart specific service
docker compose restart web

# View service status
docker compose ps
```

### 🔍 Database Access

#### ⚠️ UTF-8 Encoding cho tiếng Nhật
**Quan trọng**: Luôn sử dụng UTF-8 encoding để hiển thị tiếng Nhật đúng cách
```bash
# Connect to MySQL với UTF-8 encoding
docker exec db mysql -u root -prootpassword kantaki_dev --default-character-set=utf8mb4

# Show tables với UTF-8
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SHOW TABLES;" --default-character-set=utf8mb4

# Run SQL queries với UTF-8 (ví dụ: truy vấn mst_user)
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT * FROM mst_user LIMIT 10;" --default-character-set=utf8mb4

# Query với format table đẹp
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT unique_id, last_name, first_name, last_kana, first_kana FROM mst_user LIMIT 5;" --default-character-set=utf8mb4 -t

# Check character set configuration
docker exec db mysql -u root -prootpassword kantaki_dev -e "SHOW VARIABLES LIKE 'character_set%';" --default-character-set=utf8mb4
```

#### 📋 Lệnh Database Cơ bản
```bash
# Database backup với UTF-8
docker exec db mysqldump -u root -prootpassword --default-character-set=utf8mb4 kantaki_dev > backup.sql

# Database restore với UTF-8
docker exec -i db mysql -u root -prootpassword --default-character-set=utf8mb4 kantaki_dev < backup.sql

# Legacy commands (có thể không hiển thị tiếng Nhật đúng)
docker exec db mysql -u kantaki -pkantaki kantaki_dev -e "SELECT COUNT(*) FROM mst_staff;"
```

### 🧪 PHP Testing
```bash
# PHP version check
docker exec web php -v

# Run PHP commands
docker exec web php -r "echo 'PHP Test: ' . date('Y-m-d H:i:s') . PHP_EOL;"

# Execute PHP files
docker exec web php /var/www/html/test/some_test.php

# Check PHP modules
docker exec web php -m

# PHP configuration
docker exec web php --ini
```

### 📝 Log Monitoring

#### Web Server Logs
```bash
# Real-time web logs
docker logs -f web

# Last 50 lines
docker logs web --tail 50

# Logs with timestamps
docker logs web -t
```

#### Database Logs
```bash
# MySQL logs
docker logs db --tail 20
```

#### ESBuild Logs
```bash
# Asset compilation logs
docker logs web_esbuild -f
```

## Development Workflow

### 🔄 Asset Development
```bash
# Watch for asset changes
docker logs web_esbuild -f

# Manual asset build
docker exec web_esbuild npm run build

# Install new npm packages
docker exec web_esbuild npm install package-name
```

### 🧹 Maintenance Commands
```bash
# Clean up containers
docker system prune

# Remove volumes (careful!)
docker volume prune

# Update images
docker compose pull
docker compose up -d --build

# Container resource usage
docker stats
```

## Troubleshooting

### 🚨 Common Issues

#### Container Won't Start
```bash
# Check container status
docker ps -a

# View container logs
docker logs [container_name]

# Restart services
docker compose restart
```

#### Database Connection Issues
```bash
# Test connection với UTF-8
docker exec db mysql -u root -prootpassword --default-character-set=utf8mb4 -e "SELECT 1;"

# Check MySQL status
docker exec db mysqladmin -u root -prootpassword status

# Test tiếng Nhật display
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT unique_id, last_name, first_name FROM mst_user LIMIT 3;" --default-character-set=utf8mb4

# Check character set configuration
docker exec db mysql -u root -prootpassword -e "SHOW VARIABLES LIKE 'character_set%';"

# Reset database (nếu cần)
docker compose down
docker volume rm src_db_data
docker compose up -d
```

#### PHP Errors
```bash
# Check PHP error log
docker exec web tail -f /var/log/apache2/error.log

# Test PHP syntax
docker exec web php -l /var/www/html/index.php

# Check loaded extensions
docker exec web php -m | grep extension_name
```

### 📊 Performance Monitoring
```bash
# Container resource usage
docker stats

# Database performance với UTF-8
docker exec db mysql -u root -prootpassword --default-character-set=utf8mb4 -e "SET NAMES utf8mb4; SHOW PROCESSLIST;"

# Database size và tables info
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; 
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_ROWS as 'Số Dòng',
    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) as 'Dung Lượng (MB)',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev' 
ORDER BY 
    (DATA_LENGTH + INDEX_LENGTH) DESC 
LIMIT 10;" --default-character-set=utf8mb4

# Disk usage
docker system df
```

## Database Character Set Configuration

### 🔤 UTF-8 Configuration cho Tiếng Nhật

#### Kiểm tra Character Set hiện tại
```bash
# Check tất cả character set variables
docker exec db mysql -u root -prootpassword -e "SHOW VARIABLES LIKE 'character_set%';"

# Check collation settings
docker exec db mysql -u root -prootpassword -e "SHOW VARIABLES LIKE 'collation%';"

# Check specific database charset
docker exec db mysql -u root -prootpassword -e "SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'kantaki_dev';"
```

#### Database Access Best Practices
```bash
# Template command cho UTF-8 (khuyến nghị sử dụng)
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; [YOUR_QUERY]" --default-character-set=utf8mb4

# Ví dụ: Truy vấn với tiếng Nhật
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT unique_id, last_name, first_name, last_kana, first_kana, sex FROM mst_user WHERE last_name LIKE '%テスト%';" --default-character-set=utf8mb4 -t
```

#### Character Set Problems & Solutions
```bash
# Nếu thấy ??? thay vì tiếng Nhật:
# 1. Kiểm tra client charset
docker exec db mysql -u root -prootpassword -e "SHOW VARIABLES LIKE 'character_set_client';"

# 2. Sử dụng đúng command template
docker exec db mysql -u root -prootpassword kantaki_dev --default-character-set=utf8mb4 -e "SET NAMES utf8mb4; SELECT * FROM mst_user LIMIT 3;"

# 3. Kiểm tra table charset
docker exec db mysql -u root -prootpassword kantaki_dev -e "SELECT TABLE_NAME, TABLE_COLLATION FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'kantaki_dev' AND TABLE_NAME = 'mst_user';"
```