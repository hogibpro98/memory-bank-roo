# Staff Edit - 従業員詳細

**Feature ID**: SYS-06  
**Priority**: High  
**Module**: [`system`](/system/:1)  
**Screen Path**: [`/system/staff_edit/index.php`](/system/staff_edit/index.php:1)  
**Last Updated**: 2025-01-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- Tạo mới và chỉnh sửa thông tin chi tiết nhân viên trong hệ thống KANTAKI-WIZ
- Quản lý thông tin cá nhân, chuyên môn, và phân quyền hệ thống
- Quản lý nhiều mối quan hệ phức tạp: Place → Office → Staff assignment
- Xử lý phân quyền đa cấp và bảo mật thông tin nhân viên y tế

### Target Users:
- [x] Admin/Quản trị viên (System Admin, Corporate Admin)
- [x] Staff/Nhân viên (Employee) - giới hạn chỉnh sửa
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **[Thông tin cơ bản]** |
| 社員ID* | `<input type="text" name="upAry[staff_id]" value="<?= h($dispData['staff_id']) ?>" required>` | staff_id | mst_staff |
| 氏(漢字)* | `<input type="text" name="upAry[last_name]" value="<?= h($dispData['last_name']) ?>" required>` | last_name | mst_staff |
| 名(漢字)* | `<input type="text" name="upAry[first_name]" value="<?= h($dispData['first_name']) ?>" required>` | first_name | mst_staff |
| 氏(カナ)* | `<input type="text" name="upAry[last_kana]" value="<?= h($dispData['last_kana']) ?>" required>` | last_kana | mst_staff |
| 名(カナ)* | `<input type="text" name="upAry[first_kana]" value="<?= h($dispData['first_kana']) ?>" required>` | first_kana | mst_staff |
| アカウント* | `<input type="text" name="upAry[account]" value="<?= h($dispData['account']) ?>" required>` | account | mst_staff |
| パスワード | `<input type="password" name="upAry[password]" <?= !$isEdit ? 'required' : '' ?>>` | hash_password | mst_staff |
| **[役割・連携情報]** |
| 第1役割 | `<input type="text" name="upAry[role1]" value="<?= h($dispData['role1']) ?>">` | role1 | mst_staff |
| 第2役割 | `<input type="text" name="upAry[role2]" value="<?= h($dispData['role2']) ?>">` | role2 | mst_staff |
| 連携システム名称 | `<input type="text" name="upAry[linkage_name]" value="<?= h($dispData['linkage_name']) ?>">` | linkage_name | mst_staff |
| 連携システムコード | `<input type="text" name="upAry[linkage_code]" value="<?= h($dispData['linkage_code']) ?>">` | linkage_code | mst_staff |
| **[所属情報 - Dynamic Table]** |
| 所属拠点* | `<select name="upOfc[<?= h($stfOfcId) ?>][place_id]" required>` | place_id | mst_staff_office |
| 事業所1(看多機) | `<select name="upOfc[<?= h($stfOfcId) ?>][office1_id]" required>` | office1_id | mst_staff_office |
| 事業所2(訪問看護) | `<select name="upOfc[<?= h($stfOfcId) ?>][office2_id]">` | office2_id | mst_staff_office |
| 有効開始日* | `<input type="date" name="upOfc[<?= h($stfOfcId) ?>][start_day]" required>` | start_day | mst_staff_office |
| 有効終了日 | `<input type="date" name="upOfc[<?= h($stfOfcId) ?>][end_day]">` | end_day | mst_staff_office |
| **[資格・権限情報]** |
| 主たる資格 | `<select name="upAry[license1]">` | license1 | mst_staff |
| 看多機用職種 | `<select name="upAry[job]">` | job | mst_staff |
| 保有資格 | `<input type="checkbox" name="upAry[license2][]" value="<?= h($val) ?>" <?= mb_strpos($dispData['license2'], $val) !== false ? 'checked' : '' ?>>` | license2 | mst_staff |
| システム権限* | `<select name="upAry[type]" required>` | type | mst_staff |
| 社員区分 | `<select name="upAry[employee_type]">` | employee_type | mst_staff |
| **[個人情報]** |
| 生年月日 | `<input type="text" name="upAry[birthday][Y]" value="<?= h($dispData['birthAry']['Y']) ?>">` | birthday | mst_staff |
| 性別 | `<input type="radio" name="upAry[sex]" value="女性" <?= $dispData['sex'] == '女性' ? 'checked' : '' ?>>` | sex | mst_staff |
| 住所 | `<input type="text" name="upAry[address]" value="<?= h($dispData['address']) ?>">` | address | mst_staff |
| 電話番号 | `<input type="tel" name="upAry[tel]" value="<?= h($dispData['tel']) ?>">` | tel | mst_staff |
| 緊急連絡先 | `<input type="text" name="upAry[emg_contact]" value="<?= h($dispData['emg_contact']) ?>">` | emg_contact | mst_staff |
| メールアドレス | `<input type="email" name="upAry[mail]" value="<?= h($dispData['mail']) ?>">` | mail | mst_staff |
| 自動車免許 | `<input type="checkbox" name="upAry[driving_license]" value="1" <?= $dispData['driving_license'] == '1' ? 'checked' : '' ?>>` | driving_license | mst_staff |
| 退職 | `<input type="checkbox" name="upAry[retired]" value="1" <?= $dispData['retired'] == '1' ? 'checked' : '' ?>>` | retired | mst_staff |
| 備考 | `<input type="text" name="upAry[remarks]" value="<?= h($dispData['remarks']) ?>">` | remarks | mst_staff |
| **[緊急連絡先詳細]** |
| 緊急連絡先氏名(漢字) | `<input type="text" name="upAry[emg_name]" value="<?= h($dispData['emg_name']) ?>">` | emg_name | mst_staff |
| 緊急連絡先氏名(カナ) | `<input type="text" name="upAry[emg_kana]" value="<?= h($dispData['emg_kana']) ?>">` | emg_kana | mst_staff |
| 続柄 | `<input type="text" name="upAry[relation_type]" value="<?= h($dispData['relation_type']) ?>">` | relation_type | mst_staff |
| 緊急連絡先住所 | `<input type="text" name="upAry[emg_address]" value="<?= h($dispData['emg_address']) ?>">` | emg_address | mst_staff |
| 緊急連絡先メール | `<input type="email" name="upAry[emg_mail]" value="<?= h($dispData['emg_mail']) ?>">` | emg_mail | mst_staff |
| 緊急連絡先電話番号 | `<input type="tel" name="upAry[emg_tel]" value="<?= h($dispData['emg_tel']) ?>">` | emg_tel | mst_staff |
| 緊急連絡先携帯 | `<input type="tel" name="upAry[emg_phone]" value="<?= h($dispData['emg_phone']) ?>">` | emg_phone | mst_staff |
| 緊急連絡先備考 | `<input type="text" name="upAry[emg_remarks]" value="<?= h($dispData['emg_remarks']) ?>">` | emg_remarks | mst_staff |
| **[Nhóm đăng ký/cập nhật]** |
| 初回登録日 | `<?= h($dispData['create_day']) ?>` | create_date | mst_staff |
| 初回登録時間 | `<?= h($dispData['create_time']) ?>` | create_date | mst_staff |
| 初回登録者 | `<?= h($dispData['create_name']) ?>` | create_user → staff name | mst_staff |
| 最終更新日 | `<?= h($dispData['update_day']) ?>` | update_date | mst_staff |
| 最終更新時間 | `<?= h($dispData['update_time']) ?>` | update_date | mst_staff |
| 最終更新者 | `<?= h($dispData['update_name']) ?>` | update_user → staff name | mst_staff |

### Key UI Elements:
- **Complex Form**: 39+ input fields với sophisticated validation
- **Dynamic Office Assignment Table**: Add/remove multiple office assignments
- **Era-based Date Input**: Japanese era system (明治/大正/昭和/平成/令和)
- **Role-based Field Disabling**: Dynamic readonly states based on permissions
- **Modal Dialogs**: Place/Office selection dialogs
- **Emergency Contact Section**: Collapsible detailed contact information

### User Interactions:
1. **Create**: Tạo nhân viên mới với full validation
2. **Edit**: Chỉnh sửa existing staff với permission checks
3. **Office Management**: Add/remove office assignments với date validation
4. **Permission Assignment**: System role và employee type management
5. **Emergency Contact**: Manage detailed emergency contact information
6. **Password Management**: Secure password creation và updates

## 3. Business Logic - Logic nghiệp vụ

### Input Validation:
- **Required Fields**: staff_id, names (kanji/kana), account, type, office assignments
- **Unique Constraints**: staff_id within corporate, account globally unique
- **Password Policy**: Required for new, optional for edit, BCRYPT hashing
- **Date Validation**: Office assignment start ≤ end dates, no overlaps
- **Permission Validation**: Role-based field editing restrictions
- **Duplicate Prevention**: Account và staff_id uniqueness checks

### Processing Rules:
1. **Role-Based Access Control**:
   - SYSTEM_ADMIN: Full access, limited self-edit
   - CORPORATE_ADMIN: Cannot edit SYSTEM_ADMIN, full corporate access
   - EMPLOYEE: Can only edit same-level or lower, limited self-edit
   - LIMITED_FUNCTION: Read-only access
2. **Office Assignment Logic**:
   - Multiple assignments allowed per staff
   - Date range validation prevents overlaps
   - Place-office relationship validation
   - Session place update for current user
3. **Field Protection**: Higher roles protected from lower role edits
4. **Corporate Isolation**: All operations scoped to corporate_id

### Output Generation:
- **Form Pre-population**: Load existing data với audit information
- **Dynamic Dropdowns**: Place/office options based on corporate context
- **Permission-based UI**: Conditional field disabling và button visibility
- **Audit Trail Display**: Formatted creation/update information
- **Error Messaging**: Comprehensive Japanese error messages

## 4. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/staff_edit/
├── index.php              # Main form view (651 lines)
├── php/staff_edit.php     # Business logic (638 lines)
├── js/staff.js            # Client-side dynamics
├── dialog/                # Modal dialogs
│   ├── place.php         # Place selection dialog
│   ├── office_simple.php # Office selection dialog
│   └── office_copy.php   # Office copy dialog
```

### Key Functions:
- `isPermissionToEdit()` - Complex permission validation
- `checkDuplicate()` - Staff ID duplicate checking  
- `checkDuplicateAccount()` - Account uniqueness validation
- `isDisableByRole()` - Role-based field disabling
- `isDisabled()` - Form element access control
- `setManualUpdateType()` - Manual permission tracking
- `getActivePlace()` - Active place assignments

### AJAX Endpoints:
| Endpoint | Method | Purpose | Parameters |
|----------|--------|---------|------------|
| Dynamic dropdown population | GET | Load place/office options | `place_id` |
| Form validation | POST | Real-time validation | `field_data` |
| Office assignment validation | POST | Validate date ranges | `office_assignments[]` |

## 5. Database Schema - Cấu trúc database

### Primary Tables:
```sql
-- Staff master table (17,027 active records)
CREATE TABLE mst_staff (
    unique_id VARCHAR(20) PRIMARY KEY,          -- 固有ID
    staff_id VARCHAR(20),                       -- 社員ID
    last_name VARCHAR(30) NOT NULL,             -- 漢字氏名(苗字)
    first_name VARCHAR(30) NOT NULL,            -- 漢字氏名(名前)
    last_kana VARCHAR(30) NOT NULL,             -- カナ氏名(苗字)
    first_kana VARCHAR(30) NOT NULL,            -- カナ氏名(名前)
    birthday DATE,                              -- 生年月日
    sex VARCHAR(10),                            -- 性別
    address VARCHAR(512),                       -- 住所
    tel VARCHAR(20),                            -- 電話番号
    emg_contact VARCHAR(20),                    -- 緊急連絡先
    mail VARCHAR(100),                          -- メールアドレス
    role1 VARCHAR(30),                          -- 第1役割
    role2 VARCHAR(30),                          -- 第2役割
    linkage_name VARCHAR(30),                   -- 連携システム名称
    linkage_code VARCHAR(20),                   -- 連携システムコード
    license1 VARCHAR(20),                       -- 請求用資格
    job VARCHAR(20),                            -- 職種
    license2 TEXT,                              -- 保有資格 (^ separated)
    retired VARCHAR(10),                        -- 退職
    remarks TEXT,                               -- 備考
    account VARCHAR(256) NOT NULL,              -- アカウント (unique)
    hash_password VARCHAR(255),                 -- ハッシュパスワード
    type VARCHAR(10) NOT NULL,                  -- システム権限
    employee_type VARCHAR(20),                  -- 社員区分
    driving_license TINYINT(1),                 -- 自動車免許の有無
    -- Emergency contact fields
    emg_name VARCHAR(30),                       -- 緊急連絡先氏名(漢字)
    emg_kana VARCHAR(30),                       -- 緊急連絡先氏名(カナ)
    relation_type VARCHAR(10),                  -- 続柄
    emg_address VARCHAR(512),                   -- 緊急連絡先住所
    emg_mail VARCHAR(100),                      -- 緊急連絡先メールアドレス
    emg_tel VARCHAR(20),                        -- 緊急連絡先電話番号
    emg_phone VARCHAR(20),                      -- 緊急連絡先携帯
    emg_remarks TEXT,                           -- 緊急連絡先備考
    corporate_id VARCHAR(20)                    -- Corporate isolation
);

-- Staff office assignments (4,478 active assignments)
CREATE TABLE mst_staff_office (
    unique_id VARCHAR(20) PRIMARY KEY,
    staff_id VARCHAR(20) NOT NULL,             -- FK to mst_staff
    start_day DATE NOT NULL,                   -- 有効開始日
    end_day DATE,                              -- 有効終了日
    place_id VARCHAR(20) NOT NULL,             -- FK to mst_place
    place_name VARCHAR(30),                    -- 拠点名称
    office1_id VARCHAR(20),                    -- 事業所1ID(看多機)
    office1_name VARCHAR(30),                  -- 事業所1名称(看多機)
    office2_id VARCHAR(20),                    -- 事業所2ID(訪問看護)
    office2_name VARCHAR(30),                  -- 事業所2名称(訪問看護)
    corporate_id VARCHAR(20)                   -- Corporate isolation
);
```

### Key Relationships:
- `mst_staff` 1:N `mst_staff_office` (via staff_id)
- `mst_staff_office` N:1 `mst_place` (via place_id)
- `mst_staff_office` N:1 `mst_office` (via office1_id, office2_id)

### Sample Data Distribution:
```sql
-- Employee types (17,027 total active)
正社員: 1,335 (7.8%)
登録型: 532 (3.1%)
日給: 514 (3.0%)
契約社員: 478 (2.8%)
パート: 472 (2.8%)
NULL: 13,337 (78.4%)

-- Job categories (Top 10)
訪問介護員: 8,634 (50.7%)
看護師: 815 (4.8%)
介護: 763 (4.5%)
看護: 687 (4.0%)
介護職: 654 (3.8%)
事業所責任者: 615 (3.6%)

-- Office assignments: 4,478 total active assignments
-- Average: ~0.26 assignments per staff member
```

## 6. API Integration - Tích hợp API

### Internal APIs:
- **SafeDatabaseUtils**: Corporate-isolated database operations
- **func_staff.php**: Staff-specific utility functions
- **getCode()**: Master data for dropdowns
- **getOfficeList()**: Office assignment options
- **formatDateTime()**: Japanese date formatting

### External APIs:
- None - Pure internal staff management system

### Data Flow:
```
User Form Input →
├─ Permission Validation (role-based)
├─ Input Validation (required fields, duplicates)
├─ Business Logic Processing:
│  ├─ Password hashing (BCRYPT)
│  ├─ Date format conversion
│  ├─ License array processing
│  ├─ Office assignment validation
│  └─ Corporate isolation enforcement
├─ Database Operations:
│  ├─ mst_staff UPSERT
│  ├─ mst_staff_office MULTI-UPSERT
│  └─ Audit logging
└─ Response/Redirect
```

## 7. Testing Guide - Hướng dẫn test

### Test Cases:
#### Happy Path:
1. **Test case 1**: Create new staff (System Admin)
   - Input: Complete valid staff data với office assignments
   - Expected: Staff created với all relationships
   - Verification: Database records + session updates + audit trail

2. **Test case 2**: Edit existing staff (same role)
   - Input: Modified staff data (non-protected fields)
   - Expected: Updates applied successfully
   - Verification: Changed fields updated, protected fields unchanged

3. **Test case 3**: Add office assignment
   - Input: New office assignment với valid date range
   - Expected: Assignment added without conflicts
   - Verification: Multiple active assignments với no overlaps

#### Edge Cases:
4. **Test case 4**: Permission boundary testing
   - Input: Corporate Admin attempting to edit System Admin
   - Expected: Access denied hoặc fields disabled
   - Verification: No unauthorized changes allowed

5. **Test case 5**: Duplicate validation
   - Input: Account hoặc staff_id already exists
   - Expected: Validation error displayed
   - Verification: No duplicate records created

6. **Test case 6**: Office assignment overlap
   - Input: Date ranges overlap for same staff
   - Expected: Validation error prevents save
   - Verification: Error message + no conflicting records

### Test Data:
```php
// Valid staff data
$valid_staff = [
    'staff_id' => 'ST' . date('Ymd') . '001',
    'last_name' => 'テスト',
    'first_name' => '太郎',
    'last_kana' => 'テスト',
    'first_kana' => 'タロウ',
    'account' => 'test' . time(),
    'password' => 'TestPass123!',
    'type' => '社員',
    'role1' => '一般',
    'job' => '訪問介護員'
];

// Valid office assignment
$valid_office = [
    'place_id' => 'plce0001',
    'office1_id' => 'ofce0001',
    'start_day' => date('Y-m-d'),
    'end_day' => date('Y-m-d', strtotime('+1 year'))
];
```

### Testing URLs:
- New staff: `http://localhost:8080/system/staff_edit/`
- Edit staff: `?id=stff0001`

## 8. Performance Considerations

### Optimization Areas:
- **Complex Permission Checks**: Multiple role validation queries
- **Office Assignment Loading**: Dynamic dropdown population
- **Master Data Loading**: Large code lists cho qualifications
- **Form Validation**: Client-side + server-side dual validation
- **JavaScript Dynamics**: Complex form interactions

### Current Performance Metrics:
- **Total Records**: 17,027 staff + 4,478 office assignments
- **Form Complexity**: 39+ fields với dynamic interactions
- **Permission Validation**: Multiple database queries per check
- **Office Assignment Processing**: Bulk operations for multiple assignments

### Monitoring:
- Database query execution time
- Form validation response time
- Memory usage với large datasets
- JavaScript performance với complex interactions

## 9. Security Considerations

### Input Security:
- **SQL Injection Prevention**: SafeDatabaseUtils với prepared statements
- **XSS Protection**: `h()` function sanitization
- **CSRF Protection**: Form token validation
- **Password Security**: BCRYPT hashing với proper salt
- **Input Validation**: Comprehensive server-side validation

### Access Control:
- **Multi-level Permissions**: 4-tier role hierarchy
- **Corporate Isolation**: Strict corporate_id filtering
- **Self-Edit Restrictions**: Limited field modification
- **Cross-Role Protection**: Higher roles protected từ lower role edits
- **Session Security**: Role và corporate validation per request

### Data Protection:
- **Personal Information**: Complete staff personal data protection
- **Emergency Contacts**: Additional contact information security
- **Medical Licensing**: Healthcare qualification data protection
- **Account Credentials**: Secure password storage và transmission

## 10. Future Enhancements

### Planned Features:
- [ ] Staff photo upload và management
- [ ] Document attachment system (certificates, licenses)
- [ ] Advanced search với multiple criteria
- [ ] Bulk import/export functionality
- [ ] Mobile-responsive design improvements

### Technical Debt:
- **Code Structure**: Separate business logic từ view layer (638-line PHP file)
- **JavaScript Optimization**: Consolidate multiple JS functions
- **Database Performance**: Optimize multiple SELECT queries
- **Caching Implementation**: Add master data caching layer
- **Error Handling**: Improve user-friendly error messages

---

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-01-10 | 1.0 | Initial documentation | Roo |
| 2025-01-10 | 2.0 | Updated với new template format | Roo |

## Related Documentation

- [`memory-bank/features/system/staff_list.md`](staff_list.md:1) - Staff listing functionality
- [`memory-bank/systemPatterns.md`](../../systemPatterns.md:1) - System architecture
- [`memory-bank/database/databaseContext.md`](../../database/databaseContext.md:1) - Database schema
- [`memory-bank/common/php-utilities.md`](../../common/php-utilities.md:1) - Common utilities

## Notes

### Japanese Healthcare Context:
- **従業員詳細** (Jūgyōin Shōsai): Staff details management
- **訪問介護員** (Hōmon Kaigo-in): Home care workers (50.7% of staff)
- **看多機** (Kantaki): Multi-functional nursing service
- **所属拠点** (Shozoku Kyoten): Affiliated base/location
- **有効期間** (Yūkō Kikan): Effective period for assignments

### Database Statistics:
- **17,027 total active staff** với complex permission matrix
- **4,478 office assignments** averaging 0.26 per staff
- **Employee types**: 78.4% NULL, 7.8% 正社員 (regular employees)
- **Job distribution**: 50.7% 訪問介護員 (home care workers)

### Permission Matrix:
```php
// Role hierarchy với specific restrictions
SYSTEM_ADMIN → can edit SYSTEM_ADMIN (limited fields)
CORPORATE_ADMIN → cannot edit SYSTEM_ADMIN
EMPLOYEE → can edit same level only
LIMITED_FUNCTION → read-only access