# Database Commands - KANTAKI-WIZ

## ⚠️ Quan trọng: UTF-8 Encoding cho tiếng Nhật

**Luôn sử dụng các tham số sau để đảm bảo hiển thị tiếng Nhật đúng:**
- `--default-character-set=utf8mb4`: Thiết lập character set cho client
- `SET NAMES utf8mb4;`: Thiết lập encoding trong session MySQL
- Sử dụng user `root` với password `rootpassword` cho quyền truy cập đầy đủ

## Thông tin Kết nối Database
- **Container**: `db` (Docker) - Trạng thái: UP
- **Database**: `kantaki_dev`
- **User/Password**: `kantaki/kantaki`
- **Character Set**: `utf8mb4`
- **Port**: 3306 (mapped to host 0.0.0.0:3306)

## Template Lệnh Cơ bản

### Lệnh Base (Sử dụng làm prefix cho tất cả lệnh)
```bash
# Lệnh cơ bản với UTF-8 encoding
docker exec db mysql -u root -prootpassword kantaki_dev --default-character-set=utf8mb4

# Với SET NAMES để đảm bảo tiếng Nhật hiển thị đúng
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; [YOUR_QUERY]" --default-character-set=utf8mb4
```

## 1. Liệt kê và Tìm kiếm Bảng

### Hiển thị tất cả tên bảng
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SHOW TABLES;" --default-character-set=utf8mb4
```

### Hiển thị tất cả bảng với comment (tiếng Nhật)
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; 
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
ORDER BY 
    TABLE_NAME;" --default-character-set=utf8mb4
```

### Tìm kiếm bảng theo comment (thay 'keyword' bằng từ khóa cần tìm)
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4;
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_COMMENT LIKE '%keyword%'
ORDER BY 
    TABLE_NAME;" --default-character-set=utf8mb4
```

### Tìm kiếm bảng theo tên (thay 'pattern' bằng pattern cần tìm)
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_NAME LIKE '%pattern%'
ORDER BY 
    TABLE_NAME"
```

## 2. Phân tích Cấu trúc Bảng

### Hiển thị cấu trúc đầy đủ của bảng (thay 'table_name')
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SHOW FULL COLUMNS FROM table_name"
```

### Hiển thị CREATE TABLE statement
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SHOW CREATE TABLE table_name"
```

### Hiển thị tất cả indexes của bảng
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SHOW INDEX FROM table_name"
```

### Hiển thị thông tin chi tiết về indexes
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    INDEX_NAME as 'Tên Index',
    COLUMN_NAME as 'Cột',
    SEQ_IN_INDEX as 'Vị trí',
    NON_UNIQUE as 'Non Unique',
    INDEX_TYPE as 'Loại Index'
FROM 
    INFORMATION_SCHEMA.STATISTICS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev' 
    AND TABLE_NAME = 'table_name'
ORDER BY 
    INDEX_NAME, SEQ_IN_INDEX"
```

## 3. Tìm kiếm theo Column

### Tìm kiếm column theo tên trong tất cả bảng
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    COLUMN_NAME as 'Tên Cột',
    DATA_TYPE as 'Kiểu Dữ liệu',
    COLUMN_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND COLUMN_NAME LIKE '%column_keyword%'
ORDER BY 
    TABLE_NAME, ORDINAL_POSITION"
```

### Tìm kiếm column theo comment
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    COLUMN_NAME as 'Tên Cột',
    DATA_TYPE as 'Kiểu Dữ liệu',
    COLUMN_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND COLUMN_COMMENT LIKE '%comment_keyword%'
ORDER BY 
    TABLE_NAME, ORDINAL_POSITION"
```

### Hiển thị tất cả column của một bảng với thông tin chi tiết
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    COLUMN_NAME as 'Tên Cột',
    DATA_TYPE as 'Kiểu',
    IS_NULLABLE as 'Null',
    COLUMN_DEFAULT as 'Mặc định',
    EXTRA as 'Extra',
    COLUMN_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev' 
    AND TABLE_NAME = 'table_name'
ORDER BY 
    ORDINAL_POSITION"
```

## 4. Thống kê Database

### Thống kê số lượng bảng theo prefix
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    SUBSTRING_INDEX(TABLE_NAME, '_', 1) as 'Prefix',
    COUNT(*) as 'Số Bảng'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
GROUP BY 
    SUBSTRING_INDEX(TABLE_NAME, '_', 1)
ORDER BY 
    COUNT(*) DESC"
```

### Thống kê dung lượng bảng
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) as 'Dung lượng (MB)',
    TABLE_ROWS as 'Số dòng',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
ORDER BY 
    (DATA_LENGTH + INDEX_LENGTH) DESC"
```

### Kiểm tra character set và collation
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COLLATION as 'Collation'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
ORDER BY 
    TABLE_NAME"
```

## 5. Kiểm tra Khóa ngoại (Foreign Keys)

### Hiển thị tất cả foreign keys
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    CONSTRAINT_NAME as 'Tên FK',
    TABLE_NAME as 'Bảng',
    COLUMN_NAME as 'Cột',
    REFERENCED_TABLE_NAME as 'Bảng tham chiếu',
    REFERENCED_COLUMN_NAME as 'Cột tham chiếu'
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY 
    TABLE_NAME, CONSTRAINT_NAME"
```

### Kiểm tra foreign keys của một bảng cụ thể
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    CONSTRAINT_NAME as 'Tên FK',
    COLUMN_NAME as 'Cột',
    REFERENCED_TABLE_NAME as 'Bảng tham chiếu',
    REFERENCED_COLUMN_NAME as 'Cột tham chiếu'
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_NAME = 'table_name'
    AND REFERENCED_TABLE_NAME IS NOT NULL"
```

## 6. Lệnh Backup và Utility

### Backup cấu trúc database (schema only)
```bash
docker exec db mysqldump -u kantaki -pkantaki --no-data --single-transaction kantaki_dev > schema_backup.sql
```

### Backup dữ liệu của một bảng
```bash
docker exec db mysqldump -u kantaki -pkantaki --single-transaction kantaki_dev table_name > table_backup.sql
```

### Kiểm tra phiên bản MySQL
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SELECT VERSION()"
```

### Kiểm tra kích thước database
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    ROUND(SUM(DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 1) AS 'Database Size (MB)'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'"
```

## 7. Lệnh Tìm kiếm Nâng cao

### Tìm tất cả bảng chứa từ khóa trong tên hoặc comment
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment',
    'Table Name' as 'Tìm thấy trong'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_NAME LIKE '%keyword%'
UNION ALL
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment',
    'Table Comment' as 'Tìm thấy trong'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_COMMENT LIKE '%keyword%'
ORDER BY 
    1"
```

### Tìm tất cả column chứa từ khóa trong tên hoặc comment
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Bảng',
    COLUMN_NAME as 'Cột',
    COLUMN_COMMENT as 'Comment',
    'Column Name' as 'Tìm thấy trong'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND COLUMN_NAME LIKE '%keyword%'
UNION ALL
SELECT 
    TABLE_NAME as 'Bảng',
    COLUMN_NAME as 'Cột',
    COLUMN_COMMENT as 'Comment',
    'Column Comment' as 'Tìm thấy trong'
FROM 
    INFORMATION_SCHEMA.COLUMNS 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND COLUMN_COMMENT LIKE '%keyword%'
ORDER BY 
    1, 2"
```

## 8. Ví dụ Sử dụng Cụ thể

### Tìm tất cả bảng liên quan đến nhân viên (staff/従業員)
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment'
FROM
    INFORMATION_SCHEMA.TABLES
WHERE
    TABLE_SCHEMA = 'kantaki_dev'
    AND (TABLE_NAME LIKE '%staff%' OR TABLE_COMMENT LIKE '%従業員%')
ORDER BY
    TABLE_NAME"
```

### Tìm place_id của một user cụ thể
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT
    u.unique_id as 'User ID',
    CONCAT(u.last_name, ' ', u.first_name) as 'Họ Tên',
    u.other_id as 'Other ID',
    uo.office_id as 'Office ID',
    uo.office_name as 'Office Name',
    o.place_id as 'Place ID',
    p.name as 'Place Name',
    u.corporate_id as 'Corporate ID'
FROM
    mst_user u
    INNER JOIN mst_user_office1 uo ON u.unique_id = uo.user_id
    INNER JOIN mst_office o ON uo.office_id = o.unique_id
    INNER JOIN mst_place p ON o.place_id = p.unique_id
WHERE
    u.unique_id = 'user00005630'
    AND u.delete_flg = 0
    AND uo.delete_flg = 0
    AND o.delete_flg = 0
    AND p.delete_flg = 0"
```

### Tìm place_id của user (template - thay 'user_id_here')
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT
    u.unique_id as 'User ID',
    CONCAT(u.last_name, ' ', u.first_name) as 'Họ Tên',
    u.other_id as 'Other ID',
    uo.office_id as 'Office ID',
    uo.office_name as 'Office Name',
    o.place_id as 'Place ID',
    p.name as 'Place Name',
    u.corporate_id as 'Corporate ID'
FROM
    mst_user u
    LEFT JOIN mst_user_office1 uo ON u.unique_id = uo.user_id AND uo.delete_flg = 0
    LEFT JOIN mst_office o ON uo.office_id = o.unique_id AND o.delete_flg = 0
    LEFT JOIN mst_place p ON o.place_id = p.unique_id AND p.delete_flg = 0
WHERE
    (u.unique_id = 'user_id_here' OR u.other_id = 'user_id_here')
    AND u.delete_flg = 0"
```

### Ví dụ thực tế: Tìm place_id của user 1224255
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT
    u.unique_id as 'User ID',
    CONCAT(u.last_name, ' ', u.first_name) as 'Họ Tên',
    u.other_id as 'Other ID',
    uo.office_id as 'Office ID',
    uo.office_name as 'Office Name',
    o.place_id as 'Place ID',
    p.name as 'Place Name',
    u.corporate_id as 'Corporate ID'
FROM
    mst_user u
    LEFT JOIN mst_user_office1 uo ON u.unique_id = uo.user_id AND uo.delete_flg = 0
    LEFT JOIN mst_office o ON uo.office_id = o.unique_id AND o.delete_flg = 0
    LEFT JOIN mst_place p ON o.place_id = p.unique_id AND p.delete_flg = 0
WHERE
    (u.unique_id = '1224255' OR u.other_id = '1224255')
    AND u.delete_flg = 0"
```

**Kết quả:**
- User ID: user00005438
- Họ Tên: 矢田貝 豊孝 (Yatagai Toyotaka)
- Other ID: 1224255
- Place ID: **plce0002**
- Place Name: かえりえ南生田 (Kaerie Minami-Ikuta)
- Office ID: ofce0003
- Office Name: 看多機 かえりえ南生田
- Corporate ID: corp0001

### Kiểm tra cấu trúc bảng mst_staff
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SHOW FULL COLUMNS FROM mst_staff"
```

### Kiểm tra index của bảng mst_staff
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "SHOW INDEX FROM mst_staff"
```

### Tìm tất cả bảng có prefix 'mst_'
```bash
docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e "
SELECT 
    TABLE_NAME as 'Tên Bảng',
    TABLE_COMMENT as 'Comment'
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = 'kantaki_dev'
    AND TABLE_NAME LIKE 'mst_%'
ORDER BY 
    TABLE_NAME"
```

## Ghi chú Quan trọng

1. **Character Set**: Luôn sử dụng `--default-character-set=utf8mb4` để đảm bảo hiển thị đúng ký tự tiếng Nhật
2. **Thay thế Keywords**: 
   - `table_name` → tên bảng cụ thể
   - `keyword` → từ khóa tìm kiếm
   - `column_keyword` → từ khóa tìm kiếm column
   - `comment_keyword` → từ khóa tìm kiếm comment
3. **Docker Container**: Đảm bảo container `mysql_db` đang chạy
4. **Permissions**: User `kantaki` có quyền truy cập database `kantaki_dev`

## Shortcuts Hữu ích

### Alias cho terminal (thêm vào ~/.bashrc)
```bash
alias dbcmd="docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4 -e"
alias dbshow="docker exec -i db mysql -u kantaki -pkantaki -D kantaki_dev --default-character-set=utf8mb4"
```

### Sử dụng với alias:
```bash
dbcmd "SHOW TABLES"
dbcmd "SHOW FULL COLUMNS FROM mst_staff"
```

### Import dữ liệu từ file SQL
```bash
docker exec -i db mysql -u root -prootpassword -D kantaki_dev < ~/Documents/Kantaki/db_dev/kantaki-wiz-dev-dump-2025-06-04.sql
```

## 📋 Ví dụ Thực tế - Truy vấn Bảng mst_user

### Truy vấn LIMIT 10 với tiếng Nhật hiển thị đúng
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT * FROM mst_user LIMIT 10;" --default-character-set=utf8mb4
```

### Truy vấn các cột quan trọng với format table
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT unique_id, last_name, first_name, last_kana, first_kana, sex, birthday, prefecture, area, tel1 FROM mst_user LIMIT 10;" --default-character-set=utf8mb4 -t
```

### Kiểm tra cấu trúc bảng mst_user
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; DESCRIBE mst_user;" --default-character-set=utf8mb4 -t
```

### Tìm kiếm user theo tên (ví dụ: tìm 'テスト')
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT unique_id, last_name, first_name, last_kana, first_kana FROM mst_user WHERE last_name LIKE '%テスト%' OR first_name LIKE '%テスト%';" --default-character-set=utf8mb4 -t
```