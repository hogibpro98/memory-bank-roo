# Staff List - 従業員一覧

**Feature ID**: SYS-05  
**Priority**: High  
**Module**: [`system`](/system/:1)  
**Screen Path**: [`/system/staff_list/index.php`](/system/staff_list/index.php:1)  
**Last Updated**: 2025-01-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- Hiển thị danh sách toàn bộ nhân viên trong hệ thống KANTAKI-WIZ
- Cung cấp công cụ tìm kiếm và lọc nhân viên theo các tiêu chí khác nhau
- Quản lý thông tin nhân viên cơ bản và cho phép truy cập vào chức năng chỉnh sửa
- Hỗ trợ phân quyền truy cập dựa trên vai trò người dùng và cấp độ công ty

### Target Users:
- [x] Admin/Quản trị viên (System Admin, Corporate Admin)
- [x] Staff/Nhân viên (Employee)
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **[Bộ lọc tìm kiếm]** |
| 所属拠点 | `<option value="<?= h($val['unique_id']) ?>" <?= $dispSearch['place'] == $val['unique_id'] ? 'selected' : '' ?>><?= h($val['name']) ?></option>` | place_id | mst_place |
| 社員ID | `<input type="text" name="sAry[staff_id]" value="<?= h($dispSearch['staff_id']) ?>">` | staff_id | mst_staff |
| 氏名 | `<input type="text" name="sAry[name]" value="<?= h($dispSearch['name']) ?>">` | last_name + first_name | mst_staff |
| 退職者も表示 | `<input type="checkbox" name="sAry[retired]" value="1" <?= ($dispSearch['retired'] != '') ? 'checked' : '' ?>>` | retired | mst_staff |
| **[Bảng kết quả - Header]** |
| 該当件数 | `<?= is_null($dispData) ? 0 : count($dispData) ?>` | COUNT(*) | mst_staff |
| **[Bảng kết quả - Data Rows]** |
| 社員ID | `<?= h($val['staff_id']) ?>` | staff_id | mst_staff |
| 氏名(漢字) | `<?= h($val['last_name'] . ' ' . $val['first_name']) ?>` | last_name, first_name | mst_staff |
| 氏名(カナ) | `<?= h($val['last_kana'] . ' ' . $val['first_kana']) ?>` | last_kana, first_kana | mst_staff |
| 第１役割 | `<?= h($val['role1']) ?>` | role1 | mst_staff |
| 所属拠点 | `<?= h(trimStrWidth($val['place_name'])) ?>` | place_name | mst_staff_office → mst_place |
| 所属事業所 | `<?= h(trimStrWidth($val['office_name'])) ?>` | office1_name, office2_name | mst_staff_office → mst_office |
| 請求用資格 | `<?= h($val['license1']) ?>` | license1 | mst_staff |
| システム権限 | `<?= h($val['type']) ?>` | type | mst_staff |
| 社員区分 | `<?= h($val['employee_type']) ?>` | employee_type | mst_staff |
| 緊急連絡先 | `<?= h($val['emg_tel']) ?>` | emg_tel | mst_staff |
| 退職 | `<?= $val['retired'] == '1' ? '<div class="retire_btn">退職</div>' : '' ?>` | retired | mst_staff |
| **[Action Controls]** |
| 編集リンク | `<a href="/system/staff_edit?id=<?= h($tgtId) ?>">編集</a>` | unique_id | mst_staff |
| 削除ボタン | `<button name="btnDel" value="<?= h($tgtId) ?>">削除</button>` | unique_id | mst_staff |
| 新規従業員登録 | `<a href="/system/staff_edit/">新規従業員登録</a>` | - | - |

### Key UI Elements:
- **Place-based Access Control**: 本社 users see all places, others restricted to assigned place
- **Search Form**: 4 filter criteria với real-time validation
- **Results Table**: 13 columns displaying comprehensive staff information
- **Permission-based Actions**: Edit/Delete buttons với role-based visibility
- **Pagination**: 100 records per page với standard pager
- **Retired Staff Toggle**: Checkbox to include/exclude retired employees

### User Interactions:
1. **View**: Xem danh sách nhân viên với pagination (17,027 total records)
2. **Search**: Tìm kiếm theo staff_id, name, place assignment
3. **Filter**: Toggle retired staff inclusion/exclusion
4. **Clear**: Reset tất cả search criteria
5. **Edit**: Navigate to staff_edit với staff unique_id
6. **Delete**: Soft delete staff (set delete_flg = 1)
7. **Create**: Tạo nhân viên mới via 新規従業員登録

## 3. Business Logic - Logic nghiệp vụ

### Input Validation:
- **Place Access**: 本社 users access all places, others restricted to assigned place
- **Corporate Isolation**: Only staff from current corporate_id displayed
- **Role-Based Filtering**: EMPLOYEE/LIMITED_FUNCTION see only same-level staff
- **Search Criteria**: Optional - staff_id (exact), name (partial), place, retired flag

### Processing Rules:
1. **Default Display**: All active staff (delete_flg = 0) for authorized places
2. **Place-Based Security**: 
   - 本社 (Head Office): Full access to all places
   - Other places: Restricted to assigned place only
3. **Role-Based Access**:
   - SYSTEM_ADMIN: See all staff types
   - CORPORATE_ADMIN: Cannot see SYSTEM_ADMIN staff
   - EMPLOYEE/LIMITED_FUNCTION: See only EMPLOYEE/LIMITED_FUNCTION staff
4. **Retired Staff Logic**: Default hide retired (retired != '1'), checkbox to include
5. **Name Search**: Partial match on combined last_name + first_name
6. **Office Assignment**: Complex join với mst_staff_office for place/office display

### Output Generation:
- **Table Display**: Formatted staff information với Japanese name concatenation
- **Place/Office Names**: Resolved from related tables với width trimming
- **Permission-based UI**: Conditional edit/delete buttons
- **Record Count**: Real-time filtered result counting
- **Audit Information**: Retirement status display

## 4. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/staff_list/
├── index.php              # Main view template (174 lines)
├── php/staff_list.php     # Server-side logic (289 lines)
```

### Key Functions:
- `SafeDatabaseUtils::select()` - Corporate-isolated database queries
- `getPager()` - Pagination logic và display
- `trimStrWidth()` - Text formatting cho column display
- `getData()` - Helper function cho dropdown population
- `h()` - HTML escaping cho security

### AJAX Endpoints:
| Endpoint | Method | Purpose | Parameters |
|----------|--------|---------|------------|
| Main page | GET | Display filtered staff list | `sAry[place]`, `sAry[staff_id]`, `sAry[name]`, `sAry[retired]`, `page`, `btnSearch`, `btnClear` |
| Delete action | POST | Soft delete staff | `btnDel` (staff unique_id) |

## 5. Database Schema - Cấu trúc database

### Primary Tables:
```sql
-- Staff master table (17,027 active records)
CREATE TABLE mst_staff (
    unique_id VARCHAR(20) PRIMARY KEY,          -- 固有ID
    staff_id VARCHAR(20),                       -- 社員ID
    last_name VARCHAR(30),                      -- 漢字氏名(苗字)
    first_name VARCHAR(30),                     -- 漢字氏名(名前)
    last_kana VARCHAR(30),                      -- カナ氏名(苗字)
    first_kana VARCHAR(30),                     -- カナ氏名(名前)
    role1 VARCHAR(30),                          -- 第1役割
    license1 VARCHAR(20),                       -- 請求用資格
    type VARCHAR(10),                           -- システム権限
    employee_type VARCHAR(20),                  -- 社員区分
    emg_tel VARCHAR(20),                        -- 緊急連絡先電話番号
    retired VARCHAR(10),                        -- 退職 (0/1)
    corporate_id VARCHAR(20),                   -- Corporate isolation
    delete_flg TINYINT(1) DEFAULT 0            -- Soft delete flag
);

-- Staff-Office relationship (4,478 active assignments)
CREATE TABLE mst_staff_office (
    unique_id VARCHAR(20) PRIMARY KEY,
    staff_id VARCHAR(20),                       -- FK to mst_staff
    place_id VARCHAR(20),                       -- FK to mst_place
    place_name VARCHAR(30),                     -- 拠点名称
    office1_id VARCHAR(20),                     -- 事業所1ID(看多機)
    office1_name VARCHAR(30),                   -- 事業所1名称
    office2_id VARCHAR(20),                     -- 事業所2ID(訪問看護)
    office2_name VARCHAR(30),                   -- 事業所2名称
    corporate_id VARCHAR(20),                   -- Corporate isolation
    delete_flg TINYINT(1) DEFAULT 0
);

-- Place master table
CREATE TABLE mst_place (
    unique_id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(50),                           -- 拠点名称
    corporate_id VARCHAR(20),
    delete_flg TINYINT(1) DEFAULT 0
);
```

### Key Relationships:
- `mst_staff` 1:N `mst_staff_office` (via staff_id)
- `mst_staff_office` N:1 `mst_place` (via place_id)
- `mst_staff_office` N:1 `mst_office` (via office1_id, office2_id)

### Sample Data Distribution:
```sql
-- Staff by system permission type (17,027 total active)
社員: 13,788 (81.0%)
法人管理者: 973 (5.7%)
システム管理者: 511 (3.0%)
機能制限: 457 (2.7%)
NULL: 1,298 (7.6%)

-- Staff by employee type
正社員: 1,335 (7.8%)
登録型: 532 (3.1%)
日給: 514 (3.0%)
契約社員: 478 (2.8%)
NULL: 13,337 (78.4%)

-- Retirement status
Active: 16,929 (99.4%)
Retired: 98 (0.6%)

-- Office assignments: 4,478 total (0.26 per staff average)
```

## 6. API Integration - Tích hợp API

### Internal APIs:
- **SafeDatabaseUtils**: Corporate-isolated database operations
- **getData()**: Place list population cho dropdown
- **getPager()**: Pagination utility functions
- **Session Management**: $_SESSION['place'], $_SESSION['corporate_id']

### External APIs:
- None - Pure internal staff management system

### Data Flow:
```
User Search Input → Permission Validation → Place-based Filtering →
Database Queries (Multi-table JOINs) → Role-based Result Filtering →
Pagination Processing → HTML Table Rendering → Permission-based UI Controls
```

## 7. Testing Guide - Hướng dẫn test

### Test Cases:
#### Happy Path:
1. **Test case 1**: View all staff (本社 user)
   - Input: No search filters, 本社 user session
   - Expected: All 17,027 staff displayed với pagination
   - Verification: Check record count và all places accessible

2. **Test case 2**: Place-restricted access
   - Input: Non-本社 user với specific place assignment
   - Expected: Only staff from assigned place displayed
   - Verification: Verify place dropdown disabled, results filtered

3. **Test case 3**: Search by staff ID
   - Input: `sAry[staff_id]=ST001`
   - Expected: Exact match staff displayed
   - Verification: Single result với matching staff_id

4. **Test case 4**: Name partial search
   - Input: `sAry[name]=田中`
   - Expected: All staff với '田中' in combined name
   - Verification: Results contain search term in last_name or first_name

#### Edge Cases:
5. **Test case 5**: Retired staff toggle
   - Input: Check 退職者も表示 checkbox
   - Expected: Retired staff included in results
   - Verification: Results include staff với retired='1'

6. **Test case 6**: Permission boundary testing
   - Input: CORPORATE_ADMIN user accessing system
   - Expected: SYSTEM_ADMIN staff not visible, edit buttons disabled
   - Verification: No SYSTEM_ADMIN records, no edit access

7. **Test case 7**: Clear filters functionality
   - Input: Set filters then click クリア
   - Expected: All filters reset, full authorized list shown
   - Verification: Search fields empty, complete dataset displayed

### Test Data:
```php
// Valid search parameters
$valid_search = [
    'sAry' => [
        'place' => 'plce0001',                   // Valid place ID
        'staff_id' => 'ST001',                   // Existing staff ID
        'name' => '田中',                        // Common name part
        'retired' => '1'                         // Include retired
    ],
    'btnSearch' => 'true'
];

// Edge case - no results
$no_results = [
    'sAry' => [
        'staff_id' => 'NONEXISTENT',            // Non-existent staff ID
        'name' => '存在しない名前'                // Non-existent name
    ],
    'btnSearch' => 'true'
];
```

### Testing URLs:
- Main screen: `http://localhost:8080/system/staff_list/`
- Place filter: `?sAry[place]=plce0001&btnSearch=true`
- Staff ID search: `?sAry[staff_id]=ST001&btnSearch=true`
- Include retired: `?sAry[retired]=1&btnSearch=true`

## 8. Performance Considerations

### Optimization Areas:
- **Multi-table JOINs**: Complex queries joining 3-4 tables per staff record
- **Large Dataset**: 17,027 staff + 4,478 office assignments
- **Role-based Filtering**: Multiple permission checks per record
- **Japanese Text Search**: Partial matching on concatenated names

### Current Performance Metrics:
- **Dataset Size**: 17,027 total staff records
- **Office Assignments**: 4,478 relationships (0.26 per staff)
- **Page Load**: Multi-table queries for complete staff information
- **Search Performance**: Indexed staff_id, partial name matching

### Monitoring:
- Database query execution time for complex JOINs
- Memory usage với large result sets
- Search response time with different criteria
- Pagination performance with filtered results

## 9. Security Considerations

### Input Security:
- **SQL Injection Prevention**: SafeDatabaseUtils với prepared statements
- **XSS Protection**: `h()` function cho all output escaping
- **Input Validation**: `filter_input()` cho secure parameter handling
- **CSRF Protection**: POST forms cho delete operations

### Access Control:
- **Corporate Isolation**: Strict corporate_id filtering on all queries
- **Place-based Security**: 本社 vs restricted place access
- **Role-based Visibility**: Multi-level permission matrix
- **Permission-based Actions**: Edit/delete button conditional display

### Data Protection:
- **Personal Information**: Staff personal data access controlled
- **Emergency Contact**: Contact information secured
- **Professional Data**: License và role information protected
- **Audit Trail**: Soft deletes preserve data integrity

## 10. Future Enhancements

### Planned Features:
- [ ] Excel export functionality (commented out in current code)
- [ ] Advanced search với multiple role filtering
- [ ] Bulk operations (mass edit/activation)
- [ ] Staff photo integration
- [ ] Mobile-responsive design improvements

### Technical Debt:
- **Query Optimization**: Consolidate multiple SELECTs into efficient JOINs
- **Performance**: Implement proper database pagination instead of in-memory
- **Code Structure**: Extract business logic từ view layer
- **Caching**: Add caching cho place/office dropdown data
- **Error Handling**: Improve exception handling và user feedback

---

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-01-10 | 1.0 | Initial documentation | Roo |
| 2025-01-10 | 2.0 | Updated với new template format | Roo |

## Related Documentation

- [`memory-bank/features/system/staff_edit.md`](staff_edit.md:1) - Staff editing functionality
- [`memory-bank/systemPatterns.md`](../../systemPatterns.md:1) - System architecture
- [`memory-bank/database/databaseContext.md`](../../database/databaseContext.md:1) - Database schema
- [`memory-bank/common/php-utilities.md`](../../common/php-utilities.md:1) - Common utilities

## Notes

### Japanese Healthcare Context:
- **従業員一覧** (Jūgyōin Ichiran): Staff list management
- **所属拠点** (Shozoku Kyoten): Affiliated base/location
- **第1役割** (Dai-1 Yakuwari): Primary role in healthcare team
- **請求用資格** (Seikyū-yō Shikaku): Billing qualification
- **退職者** (Taishokusha): Retired/terminated employees

### Place-based Security Logic:
```php
// Head office access control
$isHeadOffice = ($place['name'] === '本社');
if ($isHeadOffice) {
    // Full access to all places
    $placeDropdown = 'enabled';
} else {
    // Restricted to assigned place
    $placeDropdown = 'disabled';
}
```

### Permission Matrix:
```php
// Role-based button visibility
$showButtons = true;
if (($_SESSION['login']['type'] === CORPORATE_ADMIN && $val['type'] === SYSTEM_ADMIN) ||
    $_SESSION['login']['type'] === LIMITED_FUNCTION) {
    $showButtons = false;
}
```

### Database Query Pattern:
```sql
-- Core staff data với office relationships
SELECT s.*, so.place_name, so.office1_name, so.office2_name
FROM mst_staff s
LEFT JOIN mst_staff_office so ON s.unique_id = so.staff_id
WHERE s.corporate_id = ? 
  AND s.delete_flg = 0
  [AND place filtering]
  [AND role filtering]
  [AND search criteria]
ORDER BY s.unique_id ASC