# 事業所管理 - Office Management System

**Feature ID**: SYS-07  
**Priority**: Critical  
**Module**: system  
**Screen Path**: [`/system/office/index.php`](/system/office/index.php:1)  
**Last Updated**: 2025-06-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- **Comprehensive Office Management**: Quản lý toàn diện các **事業所** (healthcare offices) thuộc một **拠点** (place/location)
- **Dual Office Types**: Quản lý đồng thời 2 loại office: **看多機** (Kantaki Multi-function) và **訪問看護** (Visiting Nursing)
- **Historical Records**: Hỗ trợ versioning với **履歴番号** (record numbers) cho tracking changes
- **Complex Healthcare Configuration**: Quản lý detailed healthcare service configurations, fees, và compliance requirements
- **Multi-entity Management**: Cars, patrol offices, staff assignments trong một interface

### Target Users:
- [x] Admin/Quản trị viên
- [x] Staff/Nhân viên quản lý tại office level
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Mapping Data với Màn hình Hiển thị

### 2.1 看多機 (Kantaki Multi-function) Section

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **基本情報 (Basic Information)** |
| 履歴No (Record Number) | `<option value="<?= h($ofcId) ?>" <?= $rcdNo == $dispData[0]['record_no'] ? 'selected' : '' ?>><?= h($rcdNo) ?></option>` | record_no | mst_office |
| 有効期間 (Valid Period) | `<input type="date" name="upAry[0][start_day]" value="<?= h($dispData[0]['start_day']) ?>">` | start_day, end_day | mst_office |
| 事業所名 (Office Name) | `<input type="text" name="upAry[0][name]" value="<?= h($dispData[0]['name']) ?>">` | name | mst_office |
| 帳票表記名称 (Report Display Name) | `<input type="text" name="upAry[0][disply_name]" value="<?= h($dispData[0]['disply_name']) ?>">` | disply_name | mst_office |
| **住所情報 (Address Information)** |
| 郵便番号 (Post Code) | `<input type="text" name="upAry[0][post]" value="<?= h($dispData[0]['post']) ?>" onKeyUp="AjaxZip3.zip2addr(...)">` | post | mst_office |
| 都道府県 (Prefecture) | `<select name="upAry[0][prefecture]">` | prefecture | mst_office |
| 市区町村 (City) | `<select name="upAry[0][area]">` | area | mst_office |
| 町域 (Town) | `<input type="text" name="upAry[0][address1]" value="<?= h($dispData[0]['address1']) ?>">` | address1 | mst_office |
| 番地以降 (House Number) | `<input type="text" name="upAry[0][address2]" value="<?= h($dispData[0]['address2']) ?>">` | address2 | mst_office |
| **連絡先情報 (Contact Information)** |
| 電話番号 (Phone) | `<input type="tel" name="upAry[0][tel]" value="<?= h($dispData[0]['tel']) ?>">` | tel | mst_office |
| FAX | `<input type="tel" name="upAry[0][fax]" value="<?= h($dispData[0]['fax']) ?>">` | fax | mst_office |
| メールアドレス (Email) | `<input type="email" name="upAry[0][mail]" value="<?= h($dispData[0]['mail']) ?>">` | mail | mst_office |
| **管理情報 (Management Information)** |
| 管理者名 (Manager) | Modal search → `<input type="hidden" name="upAry[0][manager_id]" value="<?= h($dispData[0]['manager_id']) ?>">` | manager_id → staff info | mst_staff |
| 宿泊定員 (Accommodation Capacity) | `<input type="text" name="upAry[0][capacity1]" value="<?= h($dispData[0]['capacity1']) ?>">` | capacity1 | mst_office |
| 通い定員 (Day-care Capacity) | `<input type="text" name="upAry[0][capacity2]" value="<?= h($dispData[0]['capacity2']) ?>">` | capacity2 | mst_office |
| **業務情報 (Business Information)** |
| 指定事業所番号 (Designated Office Number) | `<input type="text" name="upAry[0][office_no]" value="<?= h($dispData[0]['office_no']) ?>">` | office_no | mst_office |
| 地域単価 (Regional Unit Price) | `<input type="text" name="upAry[0][price]" value="<?= h($dispData[0]['price']) ?>">` | price | mst_office |
| 別システムコード (External System Code) | `<input type="text" name="upAry[0][other_code]" value="<?= h($dispData[0]['other_code']) ?>">` | other_code | mst_office |
| 階層コード (Layer Code) | `<input type="text" name="upAry[0][layer_code]" value="<?= h($dispData[0]['layer_code']) ?>">` | layer_code | mst_office |
| **自動車管理 (Vehicle Management)** |
| 自動車名称 (Vehicle Names) | `<input type="text" name="upCar[0][<?= h($carId) ?>][name]" value="<?= h($carVal['name']) ?>">` | name | mst_car |

### 2.2 訪問看護 (Visiting Nursing) Section

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **Similar fields như Kantaki nhưng với array index [1]** |
| 事業所名 (Office Name) | `<input type="text" name="upAry[1][name]" value="<?= h($dispData[1]['name']) ?>">` | name | mst_office |
| ステーションコード (Station Code) | `<input type="text" name="upAry[1][station_code]" value="<?= h($dispData[1]['station_code']) ?>">` | station_code | mst_office |
| **連携定期巡回事業所 (Cooperative Patrol Offices)** |
| 連携定期巡回事業所名 (Patrol Office Names) | `<input type="text" name="upPtl[1][<?= h($ptlId) ?>][name]" value="<?= h($patVal['name']) ?>">` | name | mst_office_patrol |
| 巡回事業所タイプ (Patrol Type) | `<input type="radio" name="upPtl[1][<?= h($ptlId) ?>][type]" value="<?= h($ofcType) ?>">` | type | mst_office_patrol |

### 2.3 登録・更新情報 (Registration/Update Info)

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| 初回登録 (First Registration) | `<?= h($dispData[0]['create_day']) ?> <?= h($dispData[0]['create_time']) ?> <?= h($dispData[0]['create_name']) ?>` | create_date, create_user → staff name | mst_office, mst_staff |
| 更新日時 (Last Update) | `<?= h($dispData[0]['update_day']) ?> <?= h($dispData[0]['update_time']) ?> <?= h($dispData[0]['update_name']) ?>` | update_date, update_user → staff name | mst_office, mst_staff |

## 3. Business Logic - Logic nghiệp vụ

### 3.1 Complex Data Structure:
```php
// Dual Office Management Array Structure
$dispData[0] = // 看多機 (Kantaki) data
$dispData[1] = // 訪問看護 (Visiting Nursing) data

// Related Entities
$dispCar[0] = // Kantaki vehicles
$dispCar[1] = // Visiting nursing vehicles
$dispPtl[1] = // Visiting nursing patrol offices (only type 1 has patrol)
$rcdList[0] = // Kantaki record history
$rcdList[1] = // Visiting nursing record history
```

### 3.2 Historical Record Management:
- **履歴追加 (Add History)**: Creates new version, sets end_day cho current version, increments record_no
- **Version Navigation**: User có thể select different record numbers để view historical data
- **Data Integrity**: Previous versions preserved với proper start/end dates

### 3.3 Office Group Management:
```php
// New Office Creation
if (empty($upData['unique_id'])) {
    $upData['office_group'] = $ofcId;  // Set group ID to first created ID
    $upData['unique_id'] = $ofcId;
}
```

### 3.4 Multi-Entity Operations:
1. **Office Save**: Main office record (mst_office)
2. **Vehicle Management**: Dynamic add/remove vehicles (mst_car)
3. **Patrol Office Management**: Visiting nursing patrol offices (mst_office_patrol)
4. **Manager Assignment**: Staff assignment qua modal search

### 3.5 Session Integration:
```php
// Update user session với new office information
$_SESSION['login']['place'] = getPlaceList();
$_SESSION['login']['office'] = getOfficeList();
```

## 4. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/office/
├── index.php                     # Main UI (802 lines - complex form)
├── php/office.php                # Business logic (569 lines)
└── dialog/
    └── manager_search_dialog.php # Staff selection modal
```

### 4.1 Key JavaScript Functions:
```javascript
// Dynamic Vehicle Management
function addCarInput(t) {
    // Add new vehicle input for office type t (0=Kantaki, 1=Visiting)
}

// Dynamic Patrol Office Management  
function addPatrolInput() {
    // Add new patrol office for visiting nursing
}

// Address Prefecture-City Cascading
function changePref(no) {
    // Filter cities based on selected prefecture for office type no
}

// Record History Navigation
$(".sendHisNo1").on("change", function() {
    // Navigate to different Kantaki record version
});
```

### 4.2 AJAX Integration:
- **Address Lookup**: AjaxZip3.js cho postal code → address auto-completion
- **Manager Search**: Modal dialog cho staff selection
- **Prefecture-City Cascading**: Client-side filtering

### 4.3 Database Operations:
```php
// Multi-table upsert operations
SafeDatabaseUtils::upsert($corporateId, $user, 'mst_office', $officeData);
SafeDatabaseUtils::multiUpsert($corporateId, $user, 'mst_car', $carData);
SafeDatabaseUtils::multiUpsert($corporateId, $user, 'mst_office_patrol', $patrolData);
```

## 5. Database Schema - Cấu trúc database

### 5.1 Core Tables:

#### mst_office (事業所マスタ) - 96 records
```sql
-- Key fields for dual office management
unique_id VARCHAR(20) PRIMARY KEY,
place_id VARCHAR(20),              -- Links to mst_place
type VARCHAR(10),                  -- '看多機' or '訪問看護'  
office_group VARCHAR(20),          -- Groups related office versions
record_no INT,                     -- Version number
start_day DATE,                    -- Version validity period
end_day DATE,
name VARCHAR(30),                  -- Office name
station_code VARCHAR(20),          -- For visiting nursing only
manager_id VARCHAR(20),            -- Links to mst_staff
capacity1 INT,                     -- Accommodation capacity
capacity2 INT,                     -- Day-care capacity
-- 55+ additional healthcare-specific fields
```

#### mst_car (自動車マスタ)
```sql
unique_id VARCHAR(20) PRIMARY KEY,
office_id VARCHAR(20),             -- Links to mst_office
name VARCHAR(30),                  -- Vehicle name
corporate_id VARCHAR(20)
```

#### mst_office_patrol (巡回事業所マスタ)
```sql
unique_id VARCHAR(20) PRIMARY KEY,
office_id VARCHAR(20),             -- Links to mst_office
type VARCHAR(20),                  -- Patrol office type
name VARCHAR(50),                  -- Patrol office name
corporate_id VARCHAR(20)
```

### 5.2 Current Data Distribution:
- **Total Offices**: 96 active records
- **看多機 (Kantaki)**: 51 offices
- **訪問看護 (Visiting Nursing)**: 44 offices
- **Unspecified Type**: 1 office

### 5.3 Complex Relationships:
```
mst_place (拠点)
    ↓ 1:N
mst_office (事業所) ← office_group → Related office versions
    ↓ 1:N                    ↓ 1:N
mst_car (自動車)        mst_office_patrol (巡回事業所)
```

## 6. Healthcare Domain Integration

### 6.1 Japanese Healthcare System Compliance:
- **看多機 (Kantaki)**: Multi-functional nursing care combining day-care, overnight stays, và home visits
- **訪問看護 (Visiting Nursing)**: Home-based nursing care services
- **定期巡回 (Regular Patrol)**: Scheduled visiting care services
- **Capacity Management**: Regulatory compliance cho accommodation và day-care limits

### 6.2 Fee Structure Management:
- **地域単価 (Regional Unit Price)**: Location-based pricing
- **指定事業所番号 (Designated Office Number)**: Government certification numbers
- **Healthcare Add-ons**: Complex fee calculation system (add1_*, add2_* fields)

### 6.3 Regulatory Compliance:
- **Version Control**: Historical tracking cho audit requirements
- **Staff Assignment**: Manager responsibility tracking
- **Service Classification**: Proper categorization theo healthcare regulations

## 7. Performance Considerations

### 7.1 Optimization Areas:
- **Complex Form Processing**: 569-line PHP logic với multiple entity handling
- **Large Dataset**: 123,885 area records cho address lookup
- **Multi-table Operations**: Coordinated upserts across 3+ tables
- **Session Management**: Real-time session updates cho office assignments

### 7.2 Memory Management:
- **Address Data Caching**: Strategic loading của prefecture-city data
- **Historical Data**: Efficient record version loading
- **Multi-entity Processing**: Batch operations cho cars và patrol offices

### 7.3 User Experience:
- **AJAX Operations**: Real-time address lookup và form updates
- **Dynamic Form Elements**: Add/remove vehicles và patrol offices
- **Modal Integration**: Staff selection dialogs

## 8. Security Considerations

### 8.1 Corporate Isolation:
```php
// All operations filtered by corporate_id
SafeDatabaseUtils::select($_SESSION['corporate_id'], 'mst_office', '*', $where);
```

### 8.2 Data Validation:
- **Price Validation**: `pattern="^[0-9]+(\.[0-9]+)?$"` cho numeric fields
- **Capacity Validation**: `pattern="^[0-9]+$"` cho integer fields
- **Required Field Enforcement**: Place selection required trước office management

### 8.3 Access Control:
- **Role-based Permissions**: System admin, corporate admin, employee levels
- **Place-based Access**: Users limited to assigned places/offices
- **Manager Assignment**: Modal-based staff selection với proper validation

## 9. Advanced Features

### 9.1 Dynamic Form Management:
- **Vehicle Management**: Add/remove vehicles per office type
- **Patrol Office Management**: Dynamic patrol office assignment cho visiting nursing
- **Historical Navigation**: Switch between record versions

### 9.2 Integration Features:
- **Address API**: AjaxZip3 cho Japanese postal code lookup
- **Staff Modal**: Searchable staff assignment dialog
- **Session Synchronization**: Real-time session updates after office changes

### 9.3 Healthcare-specific Configurations:
- **Service Type Management**: Complex healthcare service configurations
- **Capacity Tracking**: Accommodation và day-care capacity management
- **Fee Calculation**: Regional pricing và add-on fee structures

## 10. Future Enhancements

### Planned Features:
- [ ] Enhanced search và filtering capabilities
- [ ] Bulk office operations
- [ ] Advanced reporting cho healthcare compliance
- [ ] Integration với external healthcare systems
- [ ] Mobile-responsive design

### Technical Improvements:
- [ ] API-based architecture cho better modularity
- [ ] Real-time validation
- [ ] Enhanced error handling
- [ ] Performance optimization cho large datasets
- [ ] Automated backup và recovery procedures

---

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-06-10 | 1.0 | Initial documentation | Roo |

## Related Documentation

- [`features/system/place_list.md`](place_list.md:1) - Place management foundation
- [`features/system/place_edit.md`](place_edit.md:1) - Place editing workflows
- [`systemPatterns.md`](../../systemPatterns.md:1) - System architecture
- [`database/master-tables.md`](../../database/master-tables.md:1) - Master table documentation
- [`common/javascript-common.md`](../../common/javascript-common.md:1) - JavaScript utilities