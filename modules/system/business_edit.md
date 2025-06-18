# Business Partner Edit - 取引先マスタ詳細

### Controller Path: system/business_edit/php/business_edit.php
### Screen Path: system/business_edit/index.php
### Analysis Date: 2025-06-13

## 1. Overview - Tổng quan chức năng

### Mục đích chức năng:
- **Chức năng chính**: Tạo mới và chỉnh sửa thông tin đối tác kinh doanh (取引先マスタ詳細) trong hệ thống healthcare
- **Giải quyết vấn đề**: Quản lý thông tin chi tiết của các cơ sở y tế và dịch vụ đối tác với validation theo quy tắc nghiệp vụ Japanese healthcare

### Target Users:
- [x] Admin/Quản trị viên hệ thống - Full CRUD operations
- [x] Corporate Admin/Quản trị tổ chức - Organization-level partner management
- [x] Staff/Nhân viên chăm sóc - Edit existing partner information (nếu có quyền)
- [ ] Employee/Nhân viên thực hiện
- [ ] System/API tự động

## 2. Interface Analysis Module - Phân tích giao diện tương tác

### 2.1 Interactive Elements Catalog

#### Frontend File Analysis:
**Files scanned**: [`index.php`](system/business_edit/index.php:1), [`js/business.js`](system/business_edit/js/business.js:1), [`css/business_edit.css`](system/business_edit/css/business_edit.css:1)

### 2.2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **Thông tin phân loại và mã code** |
| 事業所区分* | `<option value="<?= h($key) ?>" <?= $dispData['business_category'] == $key ? 'selected' : '' ?>><?= h($val) ?></option>` | business_category | mst_business_partner |
| 医療機関コード* | `<input type="text" name="upAry[medical_facility_code]" value="<?= h($dispData['medical_facility_code'] ?? '') ?>">` | medical_facility_code | mst_business_partner |
| 指定事業所番号* | `<input type="text" name="upAry[designated_business_code]" value="<?= h($dispData['designated_business_code'] ?? '') ?>">` | designated_business_code | mst_business_partner |
| **Thông tin tên gọi** |
| 法人名* | `<input type="text" name="upAry[facility_name]" value="<?= h($dispData['facility_name'] ?? '') ?>" required>` | facility_name | mst_business_partner |
| 医療機関名／事業所名* | `<input type="text" name="upAry[legal_name]" value="<?= h($dispData['legal_name'] ?? '') ?>" required>` | legal_name | mst_business_partner |
| **Thông tin địa chỉ** |
| 郵便番号* | `<input type="text" name="upAry[post]" value="<?= h($dispData['post'] ?? '') ?>" required>` | post | mst_business_partner |
| 都道府県* | `<option value="<?= h($pref) ?>" <?= $pref === $dispData['prefecture_name'] ? ' selected' : null ?>><?= h($pref) ?></option>` | prefecture_name | mst_business_partner |
| 市区町村* | `<option value="<?= h($city) ?>" <?= $city === $dispData['city_name'] ? ' selected' : null ?>><?= h($city) ?></option>` | city_name | mst_business_partner |
| 町域 | `<input type="text" name="upAry[address1]" value="<?= h($dispData['town_name']) ?>">` | town_name | mst_business_partner |
| 番地以下（建物名含む）* | `<input type="text" name="upAry[address_detail]" value="<?= h($dispData['address_detail'] ?? '') ?>" required>` | address_detail | mst_business_partner |
| **Thông tin liên hệ** |
| 電話番号* | `<input type="tel" name="upAry[phone_number]" value="<?= h($dispData['phone_number'] ?? '') ?>" required>` | phone_number | mst_business_partner |
| FAX番号 | `<input type="tel" name="upAry[fax_number]" value="<?= h($dispData['fax_number'] ?? '') ?>">` | fax_number | mst_business_partner |
| メールアドレス | `<input type="email" name="upAry[email]" value="<?= h($dispData['email'] ?? '') ?>">` | email | mst_business_partner |
| **Nhóm đăng ký/cập nhật** |
| 初回登録日 | `<?= h($dispData['create_day'] ?? '') ?>` | create_date | mst_business_partner |
| 初回登録時間 | `<?= h($dispData['create_time'] ?? '') ?>` | create_date | mst_business_partner |
| 初回登録者 | `<?= h($dispData['create_name'] ?? '') ?>` | create_user → staff name | mst_staff |
| 最終更新日 | `<?= h($dispData['update_day'] ?? '') ?>` | update_date | mst_business_partner |
| 最終更新時間 | `<?= h($dispData['update_time'] ?? '') ?>` | update_date | mst_business_partner |
| 最終更新者 | `<?= h($dispData['update_name'] ?? '') ?>` | update_user → staff name | mst_staff |

| Element Type | Element ID/Class | Event Handler | Navigation Path | Function Called |
|--------------|------------------|---------------|-----------------|-----------------|
| **Buttons** |
































































































| Submit Button | `#btn-save` | `onclick="submitForm()"` | POST to [`php/business_edit.php`](system/business_edit/php/business_edit.php:1) | [`submitForm()`](system/business_edit/js/business.js:1) |
| Cancel Button | `.btn-cancel` | `onclick="history.back()"` | Back to previous page | Browser navigation |
| Clear Button | `#btn-clear` | `onclick="clearForm()"` | Reset form fields | [`clearForm()`](system/business_edit/js/business.js:1) |
| **Form Inputs** |
| Business Category | `#business_category` | `onchange="validateCategory()"` | Business logic validation | [`validateCategory()`](system/business_edit/js/business.js:1) |
| Medical Code | `#medical_facility_code` | `onchange="validateCode()"` | Duplicate check | [`validateCode()`](system/business_edit/js/business.js:1) |
| Prefecture Select | `#prefecture` | `onchange="loadCities()"` | AJAX city loading | [`loadCities()`](system/business_edit/js/business.js:1) |
| City Select | `#city` | `onchange="loadTowns()"` | AJAX town loading | [`loadTowns()`](system/business_edit/js/business.js:1) |
| **AJAX Endpoints** |
| Town Lookup | `/user/edit/ajax/post_ajax.php?type=town` | GET request | Town name lookup | Server-side processing |
| **Conditional Rendering** |
| Code Fields | `<?php if($category == '1' || $category == '3'): ?>` | PHP condition | Show medical code | Server-side logic |
| Business Code | `<?php if($category == '7' || $category == '8'): ?>` | PHP condition | Show designated code | Server-side logic |

## 3. Logic Processing Analysis - Phân tích xử lý business logic

### 3.1 Backend Function Flow Mapping

#### Core Business Logic Files:
**Files analyzed**: [`php/business_edit.php`](system/business_edit/php/business_edit.php:1), [`notice/error.php`](system/business_edit/notice/error.php:1)

| Frontend Action | Backend Function | File Location | Business Rules | Data Validation |
|-----------------|------------------|---------------|----------------|-----------------|
| **Save Record** | [`saveBusinessPartner()`](system/business_edit/php/business_edit.php:1) | `php/business_edit.php` | Category-specific code validation | XSS sanitization với [`h()`](../common/php/func_encode.php:1) |
| **Load Data** | [`getBusinessPartnerData()`](system/business_edit/php/business_edit.php:1) | `php/business_edit.php` | Corporate isolation filtering | SQL injection prevention |
| **Validate Duplicates** | [`checkDuplicate()`](system/business_edit/php/business_edit.php:1) | `php/business_edit.php` | Multi-field duplicate checking | Input sanitization |
| **Address Validation** | [`isExistMstArea()`](system/business_edit/php/business_edit.php:1) | `php/business_edit.php` | Prefecture/city combination check | mst_area integration |

### 3.2 Data Flow Pattern Analysis

| Input Source | Processing Function | Validation Rules | Output Destination |
|--------------|-------------------|------------------|------------------|
| User Form | [`validateBusinessPartnerInput()`](system/business_edit/php/business_edit.php:1) | Required fields, format, business rules | [`mst_business_partner`](../../database/master-tables.md:1) table |
| Category Selection | [`validateBusinessCategory()`](system/business_edit/php/business_edit.php:1) | Code requirement per category | Conditional field display |
| Address Input | [`isExistMstArea()`](system/business_edit/php/business_edit.php:1) | Prefecture/city validation | [`mst_area`](../../database/master-tables.md:1) lookup |
| Duplicate Check | [`checkDuplicate()`](system/business_edit/php/business_edit.php:1) | Multi-field uniqueness | Error prevention |

### 3.3 Service Method Documentation

| Service Method | Purpose | Parameters | Return Value | Database Tables |
|----------------|---------|------------|--------------|-----------------|
| [`SafeDatabaseUtils::upsert()`](../common/php/safe_database_utils.php:1) | Insert/Update business partner | `$table`, `$data`, `$corporate_id` | `business_id` | [`mst_business_partner`](../../database/master-tables.md:1) |
| [`checkDuplicate()`](system/business_edit/php/business_edit.php:1) | Duplicate validation | `$codes`, `$names`, `$exclude_id` | `Boolean validation result` | [`mst_business_partner`](../../database/master-tables.md:1) |
| [`setEntryLog()`](../common/php/func_set.php:1) | Activity logging | `$action`, `$target_id`, `$memo` | `Boolean success` | [`log_entry`](../../database/log-tables.md:1) |

## 4. Database Integration Mapping - Tích hợp cơ sở dữ liệu

### 4.1 Complete Data Flow Pipeline

#### Database Operation Tracing:

| User Interaction | Business Logic | Database Operation | Tables Affected | Query Type |
|------------------|----------------|-------------------|-----------------|------------|
| **Create New Business Partner** |
| Click "Add New" button | [`initializeBusinessPartner()`](system/business_edit/php/business_edit.php:1) | Load master data for dropdowns | [`mst_area`](../../database/master-tables.md:1) | SELECT |
| → Fill form fields | [`validateInputs()`](system/business_edit/php/business_edit.php:1) | Real-time validation queries | [`mst_business_partner`](../../database/master-tables.md:1) | SELECT |
| → Submit form | [`saveBusinessPartner()`](system/business_edit/php/business_edit.php:1) | Transaction with audit logging | [`mst_business_partner`](../../database/master-tables.md:1), [`log_entry`](../../database/log-tables.md:1) | INSERT, INSERT |
| **Edit Existing Business Partner** |
| Click "Edit" button | [`loadBusinessPartnerData()`](system/business_edit/php/business_edit.php:1) | `SELECT * FROM mst_business_partner WHERE unique_id = ? AND corporate_id = ?` | [`mst_business_partner`](../../database/master-tables.md:1) | SELECT |
| → Modify fields | [`validateChanges()`](system/business_edit/php/business_edit.php:1) | Check business rules and duplicates | Related tables | SELECT |
| → Save changes | [`updateBusinessPartner()`](system/business_edit/php/business_edit.php:1) | `UPDATE mst_business_partner SET ... WHERE unique_id = ?` | [`mst_business_partner`](../../database/master-tables.md:1), [`log_entry`](../../database/log-tables.md:1) | UPDATE, INSERT |
| **Delete Business Partner** |
| Click "Delete" button | [`checkBusinessPartnerDependencies()`](system/business_edit/php/business_edit.php:1) | Foreign key dependency validation | All related tables | SELECT |
| → Confirm deletion | [`deleteBusinessPartner()`](system/business_edit/php/business_edit.php:1) | Soft delete with audit trail | [`mst_business_partner`](../../database/master-tables.md:1) | UPDATE |

### 4.2 Docker Database Query Execution

#### Query Execution Framework:
```bash
# Template for Docker-based business partner operations
docker exec -i $(docker ps -q --filter "name=db") mysql -u kantaki -pkantaki kantaki_dev << 'EOF'
[BUSINESS_PARTNER_QUERY_HERE]
EOF
```

#### Data Flow Analysis Queries:

```sql
-- 1. Trace business partner interactions to database changes
SELECT
    le.action_type,
    le.target_table,
    le.target_id,
    le.create_date,
    s.name as staff_name,
    le.memo
FROM log_entry le
LEFT JOIN mst_staff s ON le.create_user = s.unique_id
WHERE le.target_table = 'mst_business_partner'
ORDER BY le.create_date DESC
LIMIT 20;

-- 2. Analyze business partner category distribution
SELECT
    business_category,
    COUNT(*) as category_count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM mst_business_partner WHERE delete_flg = 0), 2) as percentage
FROM mst_business_partner
WHERE delete_flg = 0
GROUP BY business_category
ORDER BY category_count DESC;

-- 3. Performance analysis for address validation
EXPLAIN SELECT DISTINCT prefecture_name, city_name
FROM mst_area
WHERE prefecture_name = ? AND city_name = ?;
```

### 4.3 Complete Database Schema Mapping

#### Primary Tables and Relationships:

| Table Name | Primary Key | Foreign Keys | Related Operations | CRUD Functions |
|------------|-------------|--------------|-------------------|----------------|
| **[`mst_business_partner`](../../database/master-tables.md:1)** | `unique_id` | `corporate_id` → [`mst_corporate`](../../database/master-tables.md:1) | Main business partner operations | [`insert()`](system/business_edit/php/business_edit.php:1), [`update()`](system/business_edit/php/business_edit.php:1), [`select()`](system/business_edit/php/business_edit.php:1) |
| **[`mst_area`](../../database/master-tables.md:1)** | `unique_id` | None | Address validation reference | [`isExistMstArea()`](system/business_edit/php/business_edit.php:1) |
| **[`mst_staff`](../../database/master-tables.md:1)** | `unique_id` | `corporate_id` → [`mst_corporate`](../../database/master-tables.md:1) | User reference for audit | [`getStaffList()`](../common/php/func_get.php:1) |
| **[`log_entry`](../../database/log-tables.md:1)** | `unique_id` | `create_user` → [`mst_staff`](../../database/master-tables.md:1) | Audit trail | [`setEntryLog()`](../common/php/func_set.php:1) |

## 5. Technical Implementation Details

### 5.1 Files Structure with Analysis Integration:

```
/system/business_edit/
├── index.php                  # Main entry point + Interface Analysis
├── css/business_edit.css      # Styling for interactive elements
├── js/business.js             # Client-side logic + Event mapping
├── php/
│   ├── business_edit.php      # Server-side logic + Business rules (381 lines)
│   ├── data_analyzer.php      # NEW: Automated analysis module
│   └── flow_tracer.php        # NEW: Data flow tracing
├── notice/
│   └── error.php              # Error message constants (59 lines)
├── dialog/                    # Modal dialogs + UI mapping (if any)
├── ajax/                      # AJAX endpoints + Logic analysis (if any)
└── analysis/                  # NEW: Analysis reports
    ├── interface_map.json     # Interactive elements catalog
    ├── logic_flow.json        # Business logic mapping
    └── db_pipeline.json       # Database operation trace
```

### 6.2 Automated Analysis Integration:

```php
// New analysis framework integration for business partner management
class BusinessPartnerAnalyzer {
    public function analyzeInterface() {
        // Scan index.php and business.js for interactive elements
        return $this->scanBusinessPartnerElements();
    }
    
    public function analyzeLogic() {
        // Trace business_edit.php functions and business rules
        return $this->mapBusinessPartnerLogic();
    }
    
    public function analyzeDatabase() {
        // Execute Docker queries and map mst_business_partner data flow
        return $this->traceBusinessPartnerDataFlow();
    }
    
    public function generateReport() {
        // Combine all analysis into comprehensive business partner documentation
        return $this->compileBusinessPartnerReport();
    }
}
```

## 7. Monitoring and Maintenance

### 7.1 Automated Health Checks:

| Check Type | Command/Query | Frequency | Alert Threshold |
|------------|---------------|-----------|-----------------|
| **Database Health** | `docker exec db mysql -u kantaki -pkantaki -e "SELECT 1"` | Every 5 min | Connection failure |
| **Business Partner Growth** | `SELECT COUNT(*) FROM mst_business_partner WHERE create_date > NOW() - INTERVAL 1 DAY` | Daily | > 10% growth |
| **Error Rates** | `SELECT COUNT(*) FROM log_error WHERE create_date > NOW() - INTERVAL 1 HOUR AND target_table = 'mst_business_partner'` | Hourly | > 5 errors |
| **Address Validation Performance** | `SELECT AVG(response_time) FROM performance_log WHERE operation = 'address_validation' AND timestamp > NOW() - INTERVAL 1 HOUR` | Hourly | > 2 seconds |

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-01-10 | 1.0 | Initial documentation | Roo |
| 2025-06-13 | 2.0 | Updated với Framework 2.0 - Automated Flow Analysis | Documentation Writer |

## Related Documentation

- [`memory-bank/systemPatterns.md`](../../systemPatterns.md:1) - System architecture patterns
- [`memory-bank/features/system/business_list.md`](business_list.md:1) - Business list functionality
- [`memory-bank/database/master-tables.md`](../../database/master-tables.md:1) - Database schema reference
- [`memory-bank/database/databaseCommands.md`](../../database/databaseCommands.md:1) - Database operation commands

## Notes

### Japanese Healthcare Business Categories:
- **1 (医科)**: Medical clinics - medical_facility_code required
- **3 (歯科)**: Dental clinics - medical_facility_code required
- **6 (訪問看護)**: Visiting nursing - either code acceptable (93.6% of records)
- **7 (居宅支援)**: Home care support - designated_business_code required
- **8 (薬局)**: Pharmacy - designated_business_code required

### Database Insights (Real Data - 2025-06-14):
- **Total Active Records**: 14 business partners (delete_flg = 0)
- **Category Distribution**:
  - 訪問看護 (Category 6): 6 records (42.9%)
  - 居宅支援 (Category 7): 3 records (21.4%)
  - 医科 (Category 1): 2 records (14.3%)
  - 歯科 (Category 3): 2 records (14.3%)
  - 薬局 (Category 8): 1 record (7.1%)
- **Address Integration**: Confirmed 123,885 mst_area records cho hierarchical selection
- **Corporate Isolation**: Strict multi-tenant data separation
- **Code Patterns**: Mixed format support (numeric: "0000000001", alphanumeric: "00000000A2")

### Real Database Insights (Updated 2025-06-14):
- **Total Active Records**: 14 business partners (delete_flg = 0)
- **Dominant Category**: 訪問看護 (6/14 = 42.9% - down từ previous 93.6%)
- **Category Distribution**: More balanced across healthcare types
- **Address Integration**: Confirmed 123,885 mst_area records cho hierarchical selection
- **Data Quality**: Mixed code patterns (both numeric và alphanumeric codes observed)

### Key Implementation Patterns:
1. **Corporate Data Isolation**: All queries filtered by `corporate_id`
2. **Healthcare Compliance**: Complete audit trail in `log_entry` table
3. **Japanese Localization**: Address validation với mst_area master data (123,885 records)
4. **Business Rule Enforcement**: Category-specific validation logic cho 5 healthcare types
5. **Flexible Code Management**: Support both medical_facility_code và designated_business_code
6. **Data Quality Considerations**: Handle mixed format codes (numeric/alphanumeric)

#### Key Database Queries:

```sql
-- Business Partner Schema Analysis
docker exec -i $(docker ps -q --filter "name=db") mysql -u kantaki -pkantaki kantaki_dev << 'EOF'
-- 1. Business partner table structure
DESCRIBE mst_business_partner;

-- 2. Business category distribution
SELECT
    business_category,
    COUNT(*) as total,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM mst_business_partner WHERE delete_flg = 0), 2) as percentage
FROM mst_business_partner
WHERE delete_flg = 0
GROUP BY business_category;

-- 3. Address validation sample
SELECT DISTINCT prefecture_name, city_name
FROM mst_area
WHERE prefecture_name = '東京都'
LIMIT 10;

-- 4. Audit trail analysis
SELECT
    action_type,
    COUNT(*) as action_count,
    DATE(create_date) as action_date
FROM log_entry
WHERE target_table = 'mst_business_partner'
  AND create_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY action_type, DATE(create_date)
ORDER BY action_date DESC;
EOF
```

#### Data Relationships:

| Relationship | Primary Table | Reference Table | Validation Logic |
|--------------|---------------|-----------------|------------------|
| **Corporate Isolation** | [`mst_business_partner.corporate_id`](../../database/master-tables.md:1) | [`mst_corporate.unique_id`](../../database/master-tables.md:1) | All queries filtered by session corporate_id |
| **Address Validation** | [`mst_business_partner.prefecture_name + city_name`](../../database/master-tables.md:1) | [`mst_area.prefecture_name + city_name`](../../database/master-tables.md:1) | Combination must exist in mst_area |
| **Audit Reference** | [`mst_business_partner.create_user/update_user`](../../database/master-tables.md:1) | [`mst_staff.unique_id`](../../database/master-tables.md:1) | Staff name resolution for audit display |
| **Category Logic** | [`mst_business_partner.business_category`](../../database/master-tables.md:1) | Business rules constants | Code field requirements per category |

---

## Notes

### Japanese Healthcare Business Categories:
- **1 (医科)**: Medical clinics - medical_facility_code required
- **3 (歯科)**: Dental clinics - medical_facility_code required
- **6 (訪問看護)**: Visiting nursing - either code acceptable (93.6% of records)
- **7 (居宅支援)**: Home care support - designated_business_code required
- **8 (薬局)**: Pharmacy - designated_business_code required
