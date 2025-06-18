# 拠点管理 - Place List Management - 拠点管理

**Feature ID**: SYS-05  
**Priority**: High  
**Module**: system  
**Screen Path**: [`/system/place_list/index.php`](/system/place_list/index.php:1)  
**Last Updated**: 2025-06-10

## 1. Overview - Tổng quan

### Mục đích chức năng:
- Hiển thị danh sách tất cả các **拠点** (cơ sở/base locations) trong hệ thống
- Quản lý thông tin địa điểm kinh doanh và các văn phòng thuộc về từng cơ sở
- Cung cấp giao diện để xem, chỉnh sửa và thêm mới các cơ sở
- Hiển thị mối quan hệ giữa **拠点** (Place) và **事業所** (Office) trong healthcare system

### Target Users:
- [x] Admin/Quản trị viên
- [x] Staff/Nhân viên quản lý  
- [ ] User/Người dùng cuối
- [ ] System/Hệ thống tự động

## 2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **拠点情報 (Place Information)** |
| 拠点名 (Place Name) | `<?= h($val['name']) ?>` | name | mst_place |
| 階層コード (Layer Code) | `<?= h($val['layer_code']) ?>` | layer_code | mst_place |
| 郵便番号 (Post Code) | `<?= h($val['post']) ?>` | post | mst_place |
| 住所 (Address) | `<?= h($val['prefecture'] . ' ' . $val['area']) ?>` | prefecture + area | mst_place |
| **事業所情報 (Office Information)** |
| 事業所/看多機 (Kantaki Office) | `<a href="/system/office/?id=<?= h($tgtId) ?>"><?= h($val['看多機']['name']) ?></a>` | 看多機['name'] | mst_office |
| 訪問看護 (Visiting Nursing) | `<a href="/system/office/?id=<?= h($tgtId) ?>"><?= h($val['訪問看護']['name']) ?></a>` | 訪問看護['name'] | mst_office |

### Key UI Elements:
- **Tables**: Bảng hiển thị danh sách places với pagination (20 items/page)
- **Forms**: Không có form trên trang này (chỉ xem)
- **Buttons**: 
  - "拠点追加" (Add Place) - Link đến `/system/place_edit`
  - "編集" (Edit) - Link đến `/system/place_edit?place_id=ID`
- **Dialogs**: Không có
- **Navigation**: Header navigation với breadcrumb

### User Interactions:
1. **View**: Xem danh sách places với thông tin cơ bản
2. **Create**: Click "拠点追加" để tạo place mới
3. **Edit**: Click "編集" để chỉnh sửa place
4. **Navigate**: Click tên office để xem chi tiết office
5. **Paginate**: Sử dụng pager để xem các page khác

## 3. Business Logic - Logic nghiệp vụ

### Data Loading Process:
1. **Load Office List**: Truy vấn `mst_office` với `delete_flg = 0`, group theo `place_id` và `type`
2. **Load Place List**: Truy vấn `mst_place` với corporate isolation
3. **Data Merging**: Kết hợp place data với office data theo `place_id`
4. **Classification**: Phân loại offices thành "看多機" và "訪問看護"

### Data Structure Logic:
```php
// Office List Structure
$ofcList[$placeId][$type]['id']   = $officeId;
$ofcList[$placeId][$type]['name'] = $officeName;

// Final Display Data
$tgtData[$placeId] = [
    'name' => $placeName,
    'layer_code' => $layerCode,
    'post' => $postCode,
    'prefecture' => $prefecture,
    'area' => $area,
    '看多機' => ['id' => $id, 'name' => $name],
    '訪問看護' => ['id' => $id, 'name' => $name]
];
```

### Corporate Isolation:
- Tất cả truy vấn sử dụng `SafeDatabaseUtils::select($_SESSION['corporate_id'], ...)`
- Đảm bảo chỉ hiển thị places thuộc corporate của user đang đăng nhập

### Pagination Logic:
- Sử dụng `getPager($tgtData, $page, $line)` với `$line = 20`
- Hỗ trợ navigation qua multiple pages

## 4. Technical Implementation - Cài đặt kỹ thuật

### Files Structure:
```
/system/place_list/
├── index.php              # Main entry point (86 lines)
├── php/place_list.php     # Server-side logic (144 lines)
└── (no css/js files)      # Uses common styles
```

### Key Functions:
- `SafeDatabaseUtils::select()` - Corporate-isolated database queries
- `getPager()` - Pagination logic
- `dispPager()` - Pagination display
- `h()` - HTML escaping function

### Database Queries:
1. **Office Query**:
```sql
SELECT * FROM mst_office 
WHERE delete_flg = 0 
  AND corporate_id = ? 
ORDER BY unique_id DESC
```

2. **Place Query**:
```sql
SELECT * FROM mst_place 
WHERE delete_flg = 0 
  AND corporate_id = ? 
ORDER BY unique_id DESC
```

### Data Processing Flow:
```
Request → place_list.php → Database Queries → Data Merging → Pagination → Display
```

## 5. Database Schema - Cấu trúc database

### Primary Tables:

#### mst_place (拠点マスタ)
```sql
CREATE TABLE mst_place (
    unique_id VARCHAR(20) PRIMARY KEY,     -- 固有ID
    delete_flg TINYINT(1) NOT NULL,        -- 削除フラグ
    create_date DATETIME NOT NULL,         -- 作成日時
    create_user VARCHAR(20) NOT NULL,      -- 作成者
    update_date DATETIME NOT NULL,         -- 更新日時
    update_user VARCHAR(20) NOT NULL,      -- 更新者
    name VARCHAR(30),                      -- 拠点名称
    post VARCHAR(10),                      -- 郵便番号
    prefecture VARCHAR(10),                -- 住所(都道府県)
    area VARCHAR(30),                      -- 住所(市区町村)
    address1 VARCHAR(100),                 -- 住所(町域)
    address2 VARCHAR(100),                 -- 住所(番地以降)
    layer_code VARCHAR(20),                -- 階層コード
    corporate_id VARCHAR(20)               -- 法人ID
);
```

#### mst_office (事業所マスタ)
```sql
CREATE TABLE mst_office (
    unique_id VARCHAR(20) PRIMARY KEY,     -- 固有ID
    place_id VARCHAR(20),                  -- 拠点ID
    type VARCHAR(10),                      -- 事業所分類 ('看多機' or '訪問看護')
    name VARCHAR(30),                      -- 事業所名称
    -- ... (55 additional fields for healthcare-specific data)
    corporate_id VARCHAR(20)               -- 法人ID
);
```

### Key Relationships:
- `mst_place` 1:N `mst_office` (place_id)
- Corporate isolation through `corporate_id`
- Soft delete pattern with `delete_flg`

### Sample Data:
- **Total Places**: 65 places trong database
- **Office Types**: "看多機" (Kantaki multi-function nursing), "訪問看護" (Visiting nursing)
- **Examples**:
  - plce0000: "本社" (Head Office) - Tokyo
  - plce000059: "test place 1" - Tokyo, Sumida ward

## 6. API Integration - Tích hợp API

### Internal APIs:
- `SafeDatabaseUtils` - Database abstraction layer
- `getPager()` - Common pagination utility
- `dispPager()` - Common pagination display
- `h()` - HTML escaping utility

### Navigation Links:
- `/system/place_edit` - Create new place
- `/system/place_edit?place_id=ID` - Edit existing place
- `/system/office/?id=ID` - View office details

### Data Flow:
```
User Request → Authentication → Corporate ID Validation → Database Query → Data Processing → HTML Rendering
```

## 7. Performance Considerations

### Optimization Areas:
- **Database Queries**: 2 main queries với corporate isolation
- **Data Processing**: Efficient array manipulation cho office grouping
- **Pagination**: Load chỉ 20 records per page
- **Memory Usage**: Minimal - chỉ display data, không có complex processing

### Current Performance:
- **Database Load**: Low (2 simple SELECT queries)
- **Memory Usage**: Low (pagination limits dataset)
- **Processing**: Fast array operations
- **Page Load**: Quick rendering với minimal data transformation

## 8. Security Considerations

### Input Security:
- **Parameter Filtering**: `filter_input(INPUT_GET, 'page')` với validation
- **HTML Escaping**: Tất cả output sử dụng `h()` function
- **No User Input**: Không có form input trên page này

### Access Control:
- **Corporate Isolation**: `SafeDatabaseUtils::select($_SESSION['corporate_id'], ...)`
- **Session Validation**: Authentication through common system
- **Permission Checking**: Admin/Staff level access required
- **URL Authorization**: Path-based access control

### Data Protection:
- **Soft Delete**: `delete_flg = 0` filter
- **Prepared Statements**: SafeDatabaseUtils sử dụng PDO prepared statements
- **Corporate Segmentation**: Multi-tenant data isolation

## 9. Healthcare Domain Integration

### Japanese Healthcare System Support:
- **拠点** (Base/Place): Physical healthcare facility locations
- **看多機** (Kantaki): Multi-functional nursing service offices
- **訪問看護** (Home Visiting Nursing): Home care nursing offices
- **事業所** (Business Office): Licensed healthcare service providers

### Business Context:
- **Place Management**: Central registry của healthcare facility locations
- **Service Classification**: Clear separation between nursing types
- **Administrative Structure**: Hierarchical organization với layer codes
- **Geographic Coverage**: Address management cho service areas

### Compliance Features:
- **Corporate ID Isolation**: Multi-tenant architecture cho healthcare organizations
- **Audit Trail**: Create/update tracking cho regulatory compliance
- **Service Type Management**: Proper classification của healthcare services
- **Address Standardization**: Japanese address format support

## 10. Future Enhancements

### Planned Features:
- [ ] Search và filtering capabilities
- [ ] Export functionality (CSV/Excel)
- [ ] Bulk edit operations
- [ ] Geographic mapping integration
- [ ] Service capacity indicators

### Technical Debt:
- Search functionality đã commented out (lines 38-42)
- No CSS/JS customization - có thể enhance UI
- Pagination có thể optimize với AJAX loading
- Add real-time validation cho data integrity

---

## Change Log

| Date | Version | Changes | Author |
|------|---------|---------|--------|
| 2025-06-10 | 1.0 | Initial documentation | Roo |

## Related Documentation

- [`systemPatterns.md`](../../systemPatterns.md:1) - System architecture
- [`database/master-tables.md`](../../database/master-tables.md:1) - Master table documentation
- [`features/system/place_edit.md`](place_edit.md:1) - Place editing feature
- [`features/system/office_list.md`](office_list.md:1) - Office management feature