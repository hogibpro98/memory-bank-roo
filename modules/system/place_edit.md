# 拠点編集 - Place Edit Management

**Feature ID**: SYS-06  
**Priority**: High  
**Module**: system  
**Screen Path**: [`/system/place_edit/index.php`](/system/place_edit/index.php:1)  
**Last Updated**: 2025-06-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- **Tạo mới** và **chỉnh sửa** thông tin **拠点** (cơ sở/base locations) trong hệ thống healthcare
- Quản lý địa chỉ và thông tin liên lạc của các cơ sở y tế
- Hỗ trợ **郵便番号検索** (postal code lookup) để tự động điền địa chỉ
- Cung cấp form validation và error handling cho data integrity
- Integration với **mst_area** database (123,885 records) cho address validation

### Target Users:
- [x] Admin/Quản trị viên
- [x] Staff/Nhân viên quản lý  
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **基本情報 (Basic Information)** |
| 拠点名称 (Place Name) <span class="req">*</span> | `<input name="upAry[name]" value="<?= h($dispData['name']) ?>">` | name | mst_place |
| 階層コード (Layer Code) | `<input name="upAry[layer_code]" value="<?= h($dispData['layer_code']) ?>">` | layer_code | mst_place |
| **住所情報 (Address Information)** |
| 郵便番号 (Post Code) | `<input name="upAry[post]" value="<?= h($dispData['post']) ?>">` | post | mst_place |
| 都道府県 (Prefecture) <span class="req">*</span> | `<select name="upAry[prefecture]">` | prefecture | mst_place |
| 市区町村 (City/Municipality) <span class="req">*</span> | `<select name="upAry[area]">` | area | mst_place |
| 町域 (Town/District) | `<input name="upAry[address1]" value="<?= h($dispData['address1']) ?>">` | address1 | mst_place |
| 番地以降 (House Number) | `<input name="upAry[address2]" value="<?= h($dispData['address2']) ?>">` | address2 | mst_place |
| **登録・更新情報 (Registration/Update Info)** |
| 初回登録日 (First Registration Date) | `<?= h($dispData['create_day']) ?>` | create_date | mst_place |
| 初回登録時間 (First Registration Time) | `<?= h($dispData['create_time']) ?>` | create_date | mst_place |
| 初回登録者 (First Registrant) | `<?= h($dispData['create_name']) ?>` | create_user → staff name | mst_staff |
| 更新日時 (Last Update Date) | `<?= h($dispData['update_day']) ?>` | update_date | mst_place |
| 更新時間 (Last Update Time) | `<?= h($dispData['update_time']) ?>` | update_date | mst_place |
| 更新者 (Last Updater) | `<?= h($dispData['update_name']) ?>` | update_user → staff name | mst_staff |

### Key UI Elements:
- **Forms**: Multi-section form với address hierarchy
- **Buttons**: 
  - "保存" (Save) - Submit form button
  - "一覧へ戻る" (Back to List) - Return to place list
- **AJAX**: Postal code lookup với automatic address population
- **Dropdowns**: Cascading prefecture → city selection
- **Validation**: Client-side và server-side validation
- **Fixed Navigation**: Bottom fixed navigation bar

### User Interactions:
1. **Create**: Truy cập `/system/place_edit` để tạo place mới
2. **Edit**: Truy cập `/system/place_edit?place_id=ID` để edit existing place
3. **Address Lookup**: Nhập postal code để auto-populate address
4. **Cascading Selection**: Chọn prefecture để filter cities
5. **Save**: Submit form để lưu changes
6. **Navigate Back**: Return về place list

## 3. Business Logic - Logic nghiệp vụ

### Input Validation Rules:
```php
// Required Fields Validation
if (empty($upData['name'])) {
    $notice[] = '名称の指定がありません';
}
if (empty($upData['prefecture'])) {
    $notice[] = '都道府県の指定がありません';
}
if (empty($upData['area'])) {
    $notice[] = '市区町村の指定がありません';
}
```

### Data Processing Workflow:
1. **Mode Detection**: Check `place_id` parameter để determine Create vs Edit mode
2. **Data Loading**: Load existing data nếu Edit mode
3. **Staff Master Loading**: Load staff names cho create/update user display
4. **Area Master Loading**: Load prefecture/city data cho dropdowns (123,885 records)
5. **Form Submission**: Validate input và save to database
6. **Success Redirect**: Redirect về edit page với new ID

### Postal Code Lookup Logic:
```php
// Address AJAX Logic
if ($type === 'post') {
    $zip = substr($upAry['post'], 0, 3) . '-' . substr($upAry['post'], -4);
    $where['post'] = $zip;
    $temp = SafeDatabaseUtils::select(null, 'mst_area', 'prefecture_name,city_name,town_name', $where);
    
    if (isset($temp[0])) {
        $upAry['prefecture'] = $temp[0]['prefecture_name'];
        $upAry['area'] = $temp[0]['city_name'];
        $upAry['address1'] = $temp[0]['town_name'];
    }
}
```

### Corporate Isolation:
- Database operations sử dụng `SafeDatabaseUtils::upsert($_SESSION['corporate_id'], ...)`
- Chỉ có thể edit places thuộc corporate của user

### Audit Logging:
- Automatic logging với `setEntryLog($upData)`
- Track create_user, create_date, update_user, update_date

## 4. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/place_edit/
├── index.php                     # Main entry point (116 lines)
├── php/place_edit.php            # Server-side logic (215 lines)
├── js/place.js                   # Client-side logic (29 lines)
└── ajax/
    └── address_ajax.php          # Postal code lookup AJAX (99 lines)
```

### Key Functions:
- `SafeDatabaseUtils::upsert()` - Corporate-isolated CRUD operations
- `initTable()` - Initialize default values cho new records
- `formatDateTime()` - Date/time formatting cho display
- `setEntryLog()` - Audit trail logging
- `h()` - HTML escaping function

### JavaScript Components:
```javascript
// Cascading Prefecture → City Selection
$('#prefecture').change(function () {
    const lv1Val = $("#prefecture").val();
    $('#municipal').removeAttr('disabled');
    $('#municipal option').remove();
    // Filter cities based on selected prefecture
    $('#municipal option[class != ' + lv1Val + ']').remove();
});
```

### AJAX Endpoints:
| Endpoint | Method | Purpose | Parameters |
|----------|--------|---------|------------|
| `/ajax/address_ajax.php?type=post` | GET | Postal code lookup | `upAry[post]` |

### Database Operations:
```sql
-- Create/Update Place
CALL SafeDatabaseUtils::upsert(corporate_id, user_id, 'mst_place', data_array)

-- Load Area Master (123,885 records)
SELECT prefecture_name, city_name FROM mst_area WHERE delete_flg = 0

-- Postal Code Lookup
SELECT prefecture_name, city_name, town_name FROM mst_area 
WHERE delete_flg = 0 AND post = ?
```

## 5. Database Schema - Cấu trúc database

### Primary Table: mst_place
```sql
CREATE TABLE mst_place (
    unique_id VARCHAR(20) PRIMARY KEY,     -- 固有ID
    delete_flg TINYINT(1) NOT NULL,        -- 削除フラグ
    create_date DATETIME NOT NULL,         -- 作成日時
    create_user VARCHAR(20) NOT NULL,      -- 作成者
    update_date DATETIME NOT NULL,         -- 更新日時
    update_user VARCHAR(20) NOT NULL,      -- 更新者
    name VARCHAR(30),                      -- 拠点名称 (Required)
    post VARCHAR(10),                      -- 郵便番号
    prefecture VARCHAR(10),                -- 住所(都道府県) (Required)
    area VARCHAR(30),                      -- 住所(市区町村) (Required)
    address1 VARCHAR(100),                 -- 住所(町域)
    address2 VARCHAR(100),                 -- 住所(番地以降)
    layer_code VARCHAR(20),                -- 階層コード
    corporate_id VARCHAR(20)               -- 法人ID
);
```

### Supporting Tables:

#### mst_area (Address Master - 123,885 records)
```sql
CREATE TABLE mst_area (
    post VARCHAR(8),                       -- 郵便番号 (153-0044 format)
    prefecture_name VARCHAR(10),           -- 都道府県名
    city_name VARCHAR(30),                 -- 市区町村名
    town_name VARCHAR(100),                -- 町域名
    delete_flg TINYINT(1)                  -- 削除フラグ
);
```

#### mst_staff (Staff Master)
```sql
CREATE TABLE mst_staff (
    unique_id VARCHAR(20) PRIMARY KEY,     -- スタッフID
    last_name VARCHAR(20),                 -- 姓
    first_name VARCHAR(20),                -- 名
    corporate_id VARCHAR(20)               -- 法人ID
);
```

### Business Rules:
- **Required Fields**: name, prefecture, area
- **Postal Code Format**: Auto-format từ 7 digits sang XXX-XXXX
- **Corporate Isolation**: All operations filtered by corporate_id
- **Soft Delete**: Records marked với delete_flg instead of physical deletion

## 6. Address Lookup Integration - Tích hợp tra cứu địa chỉ

### Postal Code API:
- **Database Source**: mst_area table với 123,885 Japanese addresses
- **Lookup Logic**: Input 7-digit postal code → Auto-populate prefecture, city, town
- **Real-time AJAX**: Instant address completion without page reload

### Prefecture-City Cascade:
- **Dynamic Filtering**: Prefecture selection filters available cities
- **JavaScript-driven**: Client-side performance for better UX
- **Data Structure**: PHP generates nested array structure cho JavaScript consumption

### Address Validation:
```php
// Sample Address Data
// Post: 153-0044 → Prefecture: 東京都, City: 目黒区, Town: 大橋
```

## 7. Performance Considerations

### Optimization Areas:
- **Area Master Loading**: 123,885 records loaded efficiently với targeting
- **JavaScript Caching**: Prefecture-city relationships cached client-side
- **AJAX Efficiency**: Single request cho postal code lookup
- **Form Processing**: Minimal server-side validation overhead

### Memory Management:
- **Area Data**: Efficient loading strategy cho large dataset
- **Corporate Filtering**: Reduces dataset size per corporate
- **Session Management**: Minimal session data storage

### Database Performance:
- **Index Strategy**: Postal code và corporate_id indexing
- **Query Efficiency**: Targeted WHERE clauses
- **Connection Reuse**: SafeDatabaseUtils connection pooling

## 8. Security Considerations

### Input Security:
- **SQL Injection Prevention**: SafeDatabaseUtils với prepared statements
- **XSS Protection**: All output escaped với `h()` function
- **Parameter Validation**: filter_input() cho all user inputs
- **CSRF Protection**: Form-based submission patterns

### Access Control:
- **Corporate Isolation**: Multi-tenant data separation
- **Session Validation**: Authentication required
- **Permission Checking**: Admin/Staff access levels
- **Audit Trail**: Complete change tracking

### Data Validation:
- **Required Field Enforcement**: Server-side validation
- **Data Type Checking**: Input filtering và sanitization
- **Business Rule Validation**: Prefecture-city consistency
- **Error Handling**: Secure error messages

## 9. Japanese Healthcare System Integration

### Address Management:
- **Japanese Postal System**: Full support cho JP postal code format
- **Prefecture Structure**: All 47 Japanese prefectures supported
- **Healthcare Facility Addressing**: Compliance với medical facility standards

### Healthcare Context:
- **拠点管理** (Base Management): Central facility location management
- **医療施設** (Medical Facilities): Healthcare service location tracking
- **法人管理** (Corporate Management): Multi-corporate healthcare organization support

### Compliance Features:
- **Address Standardization**: Japanese government address standards
- **Corporate Segmentation**: Healthcare organization data isolation
- **Audit Requirements**: Regulatory compliance logging
- **Data Retention**: Healthcare record retention policies

## 10. Error Handling & User Experience

### Validation Messages:
```php
// Japanese Error Messages
'名称の指定がありません'     // Name specification missing
'都道府県の指定がありません' // Prefecture specification missing  
'市区町村の指定がありません' // City specification missing
'検索条件に合致しません'     // Search criteria not found
'システムエラーが発生しました' // System error occurred
```

### User Experience Features:
- **Auto-complete**: Postal code lookup với instant results
- **Cascading Dropdowns**: Progressive address selection
- **Fixed Navigation**: Always-visible save/cancel buttons
- **Success Feedback**: Automatic redirect after successful save
- **Error Display**: Clear validation message display

### Recovery Mechanisms:
- **Form State Preservation**: Input values retained on validation errors
- **Error Recovery**: Clear error messages với correction guidance
- **Navigation Safety**: Confirmation cho unsaved changes

## 11. Future Enhancements

### Planned Features:
- [ ] Address validation integration với external services
- [ ] Bulk place import/export functionality
- [ ] Geographic coordinates storage và mapping
- [ ] Place hierarchy management
- [ ] Advanced search và filtering

### Technical Improvements:
- [ ] Real-time validation without form submission
- [ ] Enhanced error messaging
- [ ] Mobile-responsive form design
- [ ] Performance optimization cho large datasets
- [ ] Integration với mapping services

---

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-06-10 | 1.0 | Initial documentation | Roo |

## Related Documentation

- [`features/system/place_list.md`](place_list.md:1) - Place list management
- [`systemPatterns.md`](../../systemPatterns.md:1) - System architecture
- [`database/master-tables.md`](../../database/master-tables.md:1) - Master table documentation
- [`common/javascript-common.md`](../../common/javascript-common.md:1) - JavaScript utilities