# Business Partner List - 取引先マスタ一覧

**Feature ID**: SYS-03  
**Priority**: High  
**Controller Path**: [`system/business_edit/php/business_edit.php`](system/business_edit/php/business_edit.php:1)  
**Screen Path**: [`system/business_list/index.php`](system/business_list/index.php:1)  
**Last Updated**: 2025-01-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- Quản lý danh sách các đối tác kinh doanh (取引先マスタ) trong hệ thống healthcare
- Hỗ trợ tìm kiếm, lọc và hiển thị thông tin các cơ sở y tế và dịch vụ đối tác
- Cung cấp giao diện truy cập nhanh để chỉnh sửa thông tin đối tác
- Quản lý các loại hình kinh doanh: Ika (医科), Shika (歯科), Homon Kango (訪問看護), Kyotaku Shien (居宅支援事業所), Yakkyoku (薬局)

### Target Users:
- [x] Admin/Quản trị viên - Quản lý toàn bộ đối tác
- [x] Staff/Nhân viên - Xem và tìm kiếm thông tin đối tác
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Database Schema - Cấu trúc database

### Primary Tables:
```sql
-- Business Partners Master Table (220 active records)
CREATE TABLE mst_business_partner (
    unique_id VARCHAR(20) PRIMARY KEY,           -- 固有ID
    delete_flg TINYINT(1) NOT NULL DEFAULT 0,   -- 削除フラグ
    create_date DATETIME NOT NULL,              -- 作成日時
    create_user VARCHAR(20) NOT NULL,           -- 作成者
    update_date DATETIME NOT NULL,              -- 更新日時
    update_user VARCHAR(20) NOT NULL,           -- 更新者
    business_category VARCHAR(255),              -- 事業所区分 (1,3,6,7,8)
    medical_facility_code VARCHAR(50),           -- 医療機関コード
    designated_business_code VARCHAR(50),        -- 指定事業所番号
    legal_name VARCHAR(255) NOT NULL,           -- 医療機関名／事業所名
    facility_name VARCHAR(255),                 -- 施設名
    post VARCHAR(10),                           -- 郵便番号
    prefecture_name VARCHAR(20),                -- 都道府県名称
    city_name VARCHAR(20),                      -- 市区町村名
    town_name VARCHAR(100),                     -- 町域名
    address_detail VARCHAR(255),                -- 番地以下（建物名含む）
    phone_number VARCHAR(20),                   -- 電話番号
    fax_number VARCHAR(20),                     -- FAX番号
    email VARCHAR(100),                         -- メールアドレス
    corporate_id VARCHAR(255)                   -- 企業ID (tenant isolation)
);

-- Staff Master Table (スタッフマスタ)
CREATE TABLE mst_staff (
    unique_id VARCHAR(20) PRIMARY KEY,           -- 固有ID
    delete_flg TINYINT(1) NOT NULL,             -- 削除フラグ
    create_date DATETIME NOT NULL,              -- 作成日時
    create_user VARCHAR(20) NOT NULL,           -- 作成者
    update_date DATETIME NOT NULL,              -- 更新日時
    update_user VARCHAR(20) NOT NULL,           -- 更新者
    staff_id VARCHAR(20),                       -- スタッフID
    last_name VARCHAR(30),                      -- 姓
    first_name VARCHAR(30),                     -- 名
    last_kana VARCHAR(30),                      -- 姓カナ
    first_kana VARCHAR(30),                     -- 名カナ
    birthday DATE,                              -- 生年月日
    sex VARCHAR(10),                            -- 性別
    address VARCHAR(512),                       -- 住所
    tel VARCHAR(20),                            -- 電話番号
    emg_contact VARCHAR(20),                    -- 緊急連絡先電話
    mail VARCHAR(100),                          -- メールアドレス
    role1 VARCHAR(30),                          -- 役割1
    role2 VARCHAR(30),                          -- 役割2
    linkage_name VARCHAR(30),                   -- 連携名
    linkage_code VARCHAR(20),                   -- 連携コード
    license1 VARCHAR(20),                       -- 免許1
    job VARCHAR(20),                            -- 職種
    license2 TEXT,                              -- 免許2
    retired VARCHAR(10),                        -- 退職フラグ
    remarks TEXT,                               -- 備考
    emg_name VARCHAR(30),                       -- 緊急連絡先名
    emg_kana VARCHAR(30),                       -- 緊急連絡先カナ
    relation_type VARCHAR(10),                  -- 続柄
    emg_address VARCHAR(512),                   -- 緊急連絡先住所
    emg_mail VARCHAR(100),                      -- 緊急連絡先メール
    emg_tel VARCHAR(20),                        -- 緊急連絡先電話
    emg_phone VARCHAR(20),                      -- 緊急連絡先携帯
    emg_remarks TEXT,                           -- 緊急連絡先備考
    account VARCHAR(256),                       -- アカウント
    hash_password VARCHAR(255),                 -- ハッシュパスワード
    type VARCHAR(10),                           -- タイプ
    employee_type VARCHAR(20),                  -- 従業員タイプ
    driving_license TINYINT(1),                 -- 運転免許
    name VARCHAR(100),                          -- 名前
    manual_updated_permission TINYINT(1) DEFAULT 0, -- 手動更新権限
    corporate_id VARCHAR(20),                   -- 企業ID
    default_password_flg TINYINT(1),            -- デフォルトパスワードフラグ
    token VARCHAR(255),                         -- トークン
    corporate_admin_flg TINYINT(1)              -- 企業管理者フラグ
);

-- Area Master Table (地域マスタ) - 123,885 records
CREATE TABLE mst_area (
    unique_id VARCHAR(20) PRIMARY KEY,           -- 固有ID
    delete_flg TINYINT(1) NOT NULL,             -- 削除フラグ
    create_date DATETIME NOT NULL,              -- 作成日時
    create_user VARCHAR(20) NOT NULL,           -- 作成者
    update_date DATETIME NOT NULL,              -- 更新日時
    update_user VARCHAR(20) NOT NULL,           -- 更新者
    post VARCHAR(10),                           -- 郵便番号
    prefecture_id VARCHAR(2),                   -- 都道府県ID
    prefecture_name VARCHAR(20),                -- 都道府県名
    prefecture_kana VARCHAR(20),                -- 都道府県カナ
    city_id VARCHAR(10),                        -- 市区町村ID
    city_name VARCHAR(20),                      -- 市区町村名
    city_kana VARCHAR(40),                      -- 市区町村カナ
    town_name VARCHAR(20),                      -- 町域名
    remarks TEXT                                -- 備考
);
```

## 3. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **[Bộ lọc tìm kiếm]** |
| 事業所区分 | `<option value="<?= h($key) ?>" <?= $dispSearch['business_category'] == $key ? 'selected' : '' ?>><?= h($val) ?></option>` | business_category | mst_business_partner |
| 医療機関コード/指定事業所番号 | `<input type="text" name="sAry[code]" value="<?= h($dispSearch['code']) ?>">` | medical_facility_code, designated_business_code | mst_business_partner |
| 法人名 | `<input type="text" name="sAry[facility_name]" value="<?= h($dispSearch['facility_name']) ?>">` | facility_name | mst_business_partner |
| 医療機関名 / 事業所名 | `<input type="text" name="sAry[legal_name]" value="<?= h($dispSearch['legal_name']) ?>">` | legal_name | mst_business_partner |
| **[Bảng hiển thị kết quả]** |
| 事業所区分 | `<?= h(BUSINESS_PARTNER_CATEGORIES[$val['business_category']] ?? '') ?>` | business_category | mst_business_partner |
| 医療機関コード | `<?= h($val['medical_facility_code'] ?? '') ?>` | medical_facility_code | mst_business_partner |
| 指定事業所番号 | `<?= h($val['designated_business_code'] ?? '') ?>` | designated_business_code | mst_business_partner |
| 医療機関名／事業所名 | `<?= h($val['legal_name'] ?? '') ?>` | legal_name | mst_business_partner |
| 法人名 | `<?= h($val['facility_name'] ?? '') ?>` | facility_name | mst_business_partner |
| 住所 | `<?= h(($val['prefecture_name'] ?? '') . ($val['city_name'] ?? '') . ($val['town_name'] ?? '') . ($val['address_detail'] ?? '')) ?>` | prefecture_name, city_name, town_name, address_detail | mst_business_partner |
| 電話番号 | `<?= h($val['phone_number'] ?? '') ?>` | phone_number | mst_business_partner |
| **[Thông tin hiển thị]** |
| 該当件数 | `<?= is_null($dispData) ? 0 : count($dispData) ?>` | COUNT(*) | mst_business_partner |
| 編集リンク | `<a href="/system/business_edit/index.php?business_id=<?= h($tgtId) ?>">編集</a>` | unique_id | mst_business_partner |

### Key UI Elements:
- **Search Form**: 4 filter fields với business category dropdown
- **Results Table**: 8 columns showing comprehensive business partner information
- **Action Buttons**: 新規 (New), 絞り込み (Filter), クリア (Clear), 編集 (Edit)
- **Record Count**: Real-time count display của filtered results
- **Pagination**: 100 records per page với standard pager controls

### User Interactions:
1. **View**: Xem danh sách đối tác với pagination (220 total records)
   ```sql
   SELECT * FROM mst_business_partner
   WHERE corporate_id = ? AND delete_flg = 0
   ORDER BY unique_id DESC LIMIT 100 OFFSET ?;
   ```

2. **Search**: Tìm kiếm theo business category, codes, facility names
   ```sql
   SELECT * FROM mst_business_partner
   WHERE corporate_id = ? AND delete_flg = 0
     AND business_category = ?
     AND (medical_facility_code LIKE ? OR designated_business_code LIKE ?)
     AND facility_name LIKE ?
     AND legal_name LIKE ?
   ORDER BY unique_id DESC;
   ```

3. **Filter**: Lọc theo specific business categories (1,3,6,7,8)
   ```sql
   SELECT * FROM mst_business_partner
   WHERE corporate_id = ? AND delete_flg = 0
     AND business_category = 'specific business categories'
   ORDER BY unique_id DESC;
   ```

4. **Clear**: Reset tất cả filter criteria
   ```sql
   SELECT * FROM mst_business_partner
   WHERE corporate_id = ? AND delete_flg = 0
   ORDER BY unique_id DESC LIMIT 100;
   ```

5. **Edit**: Navigate to business_edit với business_id parameter
   ```sql
   SELECT * FROM mst_business_partner
   WHERE unique_id = ? AND corporate_id = ? AND delete_flg = 0;
   ```

6. **Create**: Navigate to business_edit màn hình tạo mới business partner via 新規 button

## 4. Business Logic - Logic nghiệp vụ

### Input Validation:
- **Business Category**: Must be valid category (1=医科, 3=歯科, 6=訪問看護, 7=居宅支援, 8=薬局)
- **Code Search**: Search both medical_facility_code và designated_business_code
- **Text Fields**: Facility name và legal name support partial matching với LIKE queries
- **Corporate Isolation**: Only show partners for current corporate_id

### Processing Rules:
1. **Default Display**: Show all active partners (delete_flg = 0) for corporate
2. **Category Filter**: Filter by business_category if selected
3. **Code Search**: Search both code fields với OR condition
4. **Name Search**: LIKE pattern matching cho facility_name và legal_name
5. **Pagination**: 100 records per page với DESC ordering by unique_id
6. **Permission Check**: Hide edit button based on user/partner type combinations

### Output Generation:
- **Table Display**: Formatted business partner information với Japanese address concatenation
- **Category Display**: Business category names from BUSINESS_PARTNER_CATEGORIES constant
- **Address Display**: Combined prefecture + city + town + detail trong single column
- **Action Controls**: Conditional edit buttons based on permission matrix

## 5. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/business_edit/
├── index.php              # Main view template (164 lines)
├── css/                   # Styling directory
├── js/                    # JavaScript directory
├── notice/                # Notice/notification directory
└── php/                   # Server-side logic directory
```

### AJAX Endpoints:
| Endpoint | Method | Purpose | Parameters |
|----------|--------|---------|------------|
| Main page | GET | Display filtered business partners | `sAry[business_category]`, `sAry[code]`, `sAry[facility_name]`, `sAry[legal_name]`, `page`, `btnSearch`, `btnClear` |

### Key Relationships:
- `mst_business_partner.corporate_id` → Corporate tenant isolation
- Links to medical records via medical_facility_code matching
- Referenced by healthcare service planning modules

## 6. Performance Considerations

### Optimization Areas:
- **Database Indexing**: Ensure corporate_id, delete_flg, business_category properly indexed
- **Query Performance**: Simple SELECT với efficient WHERE conditions
- **Search Optimization**: LIKE queries có thể benefit từ full-text indexing
- **Pagination**: Current implementation loads all data then paginates

### Current Performance:
- **Dataset Size**: 220 active records (manageable size)
- **Query Complexity**: Simple SELECT với basic WHERE conditions
- **Memory Usage**: Low với moderate dataset
- **Response Time**: Fast với indexed corporate_id filtering

### Monitoring:
- Database query execution time
- Page load performance
- Search response time
- Memory usage patterns

## 7. Security Considerations

### Input Security:
- **SQL Injection Prevention**: SafeDatabaseUtils với prepared statements
- **XSS Protection**: `h()` function cho all output escaping
- **Input Validation**: `filter_input()` cho secure parameter handling
- **Parameter Sanitization**: Proper validation của search criteria

### Access Control:
- **Corporate Isolation**: Enforced via corporate_id in all database queries
- **Session Validation**: Required active session với valid corporate context
- **Permission Checking**: Edit button visibility based on user/partner type
- **Multi-tenant Security**: Complete data segregation between corporations

### Data Protection:
- **Sensitive Information**: Medical facility codes và contact data secured
- **Audit Trail**: Soft deletes preserve historical data
- **Session Security**: Corporate ID validation prevents cross-tenant access
- **Error Handling**: Proper exception handling prevents information disclosure

## 8. Future Enhancements

### Planned Features:
- [ ] Excel export functionality cho business partner lists
- [ ] Advanced search với date ranges và multiple criteria
- [ ] Bulk operations (activate/deactivate multiple partners)
- [ ] Integration với user medical records
- [ ] Mobile-responsive design improvements

### Technical Debt:
- Add proper LIMIT clause cho database pagination efficiency
- Implement full-text search cho better text searching performance
- Add client-side input validation
- Optimize query performance với better indexing strategy
- Add API endpoints cho mobile application integration

---

## Notes

### Permission Logic:
```php
// Edit button visibility
$showButtons = !($_SESSION['login']['type'] === CORPORATE_ADMIN && 
                  $val['type'] === SYSTEM_ADMIN);
```
## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-01-10 | 1.0 | Initial documentation | Roo |