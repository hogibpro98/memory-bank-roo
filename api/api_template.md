# API Template - [Tên API] ([Tên tiếng Nhật])

## Tổng quan API
- **File**: `[đường dẫn file PHP]`
- **Màn hình**: `[đường dẫn file hiển thị]`
- **Chức năng**: [Mô tả chức năng chính của API]

## Phân tích Logic Database

### 1. Input Parameters
```php
// Lấy tham số truy vấn
$dataQueryParams = h(filter_input(INPUT_GET, 'data'));

// Giải mã tham số
$dataQueryParams = decryptAesBase64($dataQueryParams);

// Tham số bắt buộc:
- [tham_so_1] ([Mô tả tiếng Nhật] - [Mô tả tiếng Việt])
- [tham_so_2] ([Mô tả tiếng Nhật] - [Mô tả tiếng Việt])
```

### 2. Cấu trúc Bảng và Truy vấn Database

#### Bảng chính được sử dụng:
```php
$table1 = '[tên_bảng_chính]';    // [Mô tả bảng chính]
$table2 = '[tên_bảng_phụ]';     // [Mô tả bảng phụ]
```

#### Các bảng liên quan:
1. **[tên_bảng_1]** - [Mô tả chức năng]
2. **[tên_bảng_2]** - [Mô tả chức năng]
3. **[tên_bảng_3]** - [Mô tả chức năng]
4. **[tên_bảng_4]** - [Mô tả chức năng]
5. **[tên_bảng_5]** - [Mô tả chức năng]

### 3. Logic Truy vấn Chi tiết

#### 3.1 Xác thực Corporate ID (dòng [số_dòng])
```sql
SELECT unique_id
FROM mst_corporate mc
WHERE h2_corporate_id = ? or kantaki_corporate_id = ?
```

#### 3.2 [Tên truy vấn] (dòng [số_dòng])
```sql
[SQL Query]
```

#### 3.3 [Tên truy vấn] (dòng [số_dòng])
```sql
[SQL Query]
```

#### 3.4 [Tên truy vấn] (function [tên_function] - dòng [số_dòng])
```sql
[SQL Query]
```

#### 3.5 Lấy mã chung (function getCode - dòng [số_dòng])
```sql
SELECT unique_id, name, group_div, type
FROM mst_code
WHERE delete_flg = 0 AND group_div = '[nhóm_mã]'
ORDER BY unique_id ASC
```

## 4. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **[Nhóm thông tin 1]** |
| [Nhãn 1] | `<?= h($dispData['[field_name]']) ?>` | [field_name] | [table_name] |
| [Nhãn 2] | `<?= h($dispData['[field_name]']) ?>` | [field_name] | [table_name] |
| **[Nhóm thông tin 2]** |
| [Nhãn 3] | `$dispData['[field_name]']` | [field_name] | [table_name] |
| [Nhãn 4] | `$dispData['[field_name]']` | [field_name] | [table_name] |
| **[Nhóm thông tin 3]** |
| [Nhãn 5] | `<?= h($dispData['[field_name]']) ?>` | [field_name] | [table_name] |
| **[Nhóm đăng ký/cập nhật]** |
| 初回登録日 | `<?= h($dispData['create_day']) ?>` | create_date | [main_table] |
| 初回登録時間 | `<?= h($dispData['create_time']) ?>` | create_date | [main_table] |
| 初回登録者 | `<?= h($dispData['create_name']) ?>` | create_user → staff name | mst_staff |
| 最終更新日 | `<?= h($dispData['update_day']) ?>` | update_date | [main_table] |
| 最終更新時間 | `<?= h($dispData['update_time']) ?>` | update_date | [main_table] |
| 最終更新者 | `<?= h($dispData['update_name']) ?>` | update_user → staff name | mst_staff |

## 5. Các bảng mã chung (mst_code)

Bảng `mst_code` được sử dụng để lưu trữ các giá trị dropdown cho:
- [Loại mã 1] ([Mô tả tiếng Nhật])
- [Loại mã 2] ([Mô tả tiếng Nhật])
- [Loại mã 3] ([Mô tả tiếng Nhật])

## 6. Truy vấn Dữ liệu Hợp lệ trong Năm 2024

### 6.1 Query Lọc Dữ liệu Theo Năm 2024

#### Lấy bản ghi chính trong năm 2024:
```sql
SELECT *
FROM [main_table]
WHERE delete_flg = 0
  AND unique_id = ?
  AND (
    YEAR([date_field]) = 2024 OR
    YEAR(create_date) = 2024 OR
    YEAR(update_date) = 2024
  )
```

#### Lấy dữ liệu liên quan trong năm 2024:
```sql
SELECT *
FROM [related_table]
WHERE delete_flg = 0
  AND unique_id = ?
  AND YEAR([date_field]) = 2024
```

#### Lấy Staff data đang hoạt động trong năm 2024:
```sql
SELECT last_name, first_name, staff_id
FROM mst_staff
WHERE delete_flg = 0
  AND unique_id = ?
  AND (
    retire_day IS NULL OR
    YEAR(retire_day) > 2024 OR
    retire_day = '0000-00-00'
  )
```

#### Lấy User data hợp lệ trong năm 2024:
```sql
SELECT unique_id, last_name, first_name, other_id
FROM mst_user
WHERE delete_flg = 0
  AND unique_id = ?
  AND (
    stop_day IS NULL OR
    YEAR(stop_day) > 2024 OR
    stop_day = '0000-00-00'
  )
```

### 6.2 Validation Điều kiện Năm 2024

```php
// Kiểm tra [date_field] trong năm 2024
if (!empty($dispData['[date_field]'])) {
    $fieldYear = date('Y', strtotime($dispData['[date_field]']));
    if ($fieldYear != '2024') {
        $_SESSION['notice']['error'][] = '[Error message tiếng Nhật]';
    }
}

// Kiểm tra [next_date_field] trong năm 2024 hoặc 2025
if (!empty($dispData['[next_date_field]'])) {
    $nextYear = date('Y', strtotime($dispData['[next_date_field]']));
    if ($nextYear < '2024' || $nextYear > '2025') {
        $_SESSION['notice']['error'][] = '[Error message tiếng Nhật]';
    }
}
```

### 6.3 Filter Condition cho API Call

```php
// Thêm điều kiện lọc năm 2024 cho truy vấn chính
$whereConditions = [
    'delete_flg' => 0,
    'unique_id' => $keyId
];

// Thêm điều kiện năm nếu cần
$yearFilter = '2024';
$dateFilter = " AND (
    YEAR([main_date_field]) = '{$yearFilter}' OR
    YEAR(create_date) = '{$yearFilter}' OR
    YEAR(update_date) = '{$yearFilter}'
)";

$additionalWhere = $dateFilter;
```

## 7. Lưu ý đặc biệt

1. **Logic đặc biệt 1**: [Mô tả logic đặc biệt]
2. **Logic đặc biệt 2**: [Mô tả logic đặc biệt]
3. **Format dữ liệu**: [Mô tả format dữ liệu]
4. **Mã hóa tham số**: Input parameters được mã hóa AES Base64
5. **Validation**: Kiểm tra tồn tại của [tham_số_1] và [tham_số_2] trước khi xử lý
6. **Filter năm 2024**: Tất cả truy vấn dữ liệu phải bao gồm điều kiện lọc năm 2024
7. **Date Range Validation**: Kiểm tra các trường ngày tháng phải nằm trong phạm vi hợp lệ

## 8. Kết quả Truy vấn Thực tế Database (Năm 2024)

### 8.1 Thống kê Dữ liệu Hợp lệ Năm 2024
```
Bảng [main_table]:
- Tổng số bản ghi: [số_lượng]
- [Field] 2024: [số_lượng] bản ghi
- Created 2024: [số_lượng] bản ghi
- Updated 2024: [số_lượng] bản ghi
```

### 8.2 Validation Test Results
```
Test Type: Validation Test
- Corporate ID Check: [số_lượng] corporate hợp lệ
- Doc ID 2024 Check: [số_lượng] bản ghi năm 2024
- Active Users 2024: [số_lượng] user hoạt động năm 2024
```

### 8.3 Mẫu Params Hợp lệ Năm 2024

#### Test Case thành công:
```php
// URL params được mã hóa AES Base64
$dataQueryParams = [
    '[param1]' => '[value1]',
    '[param2]' => '[value2]'
];

// Dữ liệu trả về:
$validRecord = [
    '[field1]' => '[value1]',
    '[field2]' => '[value2]',
    '[date_field]' => '2024-XX-XX',
    'status' => '[status_value]',
    'validation_status' => 'Valid 2024 Record'
];
```

### 8.4 Query Samples cho Validation Năm 2024

```sql
-- Kiểm tra params hợp lệ
SELECT [fields]
FROM [main_table] mt
WHERE mt.delete_flg = 0
  AND YEAR(mt.[date_field]) = 2024
  AND mt.unique_id = ?

-- Validate corporate
SELECT mc.unique_id
FROM mst_corporate mc
WHERE (mc.h2_corporate_id = ? OR mc.kantaki_corporate_id = ?)
  AND mc.delete_flg = 0
```

### 8.5 Top 10 Bản ghi Hợp lệ Năm 2024

| [field1] | [field2] | [date_field] | [field3] | [field4] | status |
|----------|----------|--------------|----------|----------|--------|
| [value1] | [value2] | 2024-XX-XX   | [value3] | [value4] | [status] |

### 8.6 Recommendation cho API Call

1. **Sử dụng [param] từ danh sách trên** để test API
2. **[Param_name]**: Lấy từ bảng [table_name]
3. **[Date_field]**: Phải nằm trong khoảng [date_range]
4. **Status**: [Mô tả status hợp lệ]

### 8.7 Sample API URL Test

```bash
# Mã hóa params
$params = ['[param1]' => '[value1]', '[param2]' => '[value2]'];
$encodedParams = encryptAesBase64($params);

# URL test
http://domain.com/[api_path]?data={$encodedParams}
```

### 8.8 Expected Response Data

```json
{
  "[field1]": "[value1]",
  "[field2]": "[value2]", 
  "[date_field]": "2024-XX-XX",
  "[field3]": "[value3]",
  "status": "[status_value]",
  "create_day": "2024/XX/XX",
  "create_time": "XX:XX",
  "update_day": "2024/XX/XX", 
  "update_time": "XX:XX"
}
```

---

## Hướng dẫn sử dụng Template

### Bước 1: Chuẩn bị thông tin cơ bản
- Thay thế `[Tên API]` và `[Tên tiếng Nhật]` 
- Cập nhật đường dẫn file PHP và file hiển thị
- Mô tả chức năng chính

### Bước 2: Phân tích Database
- Xác định các bảng chính và bảng liên quan
- Liệt kê các tham số input bắt buộc
- Phân tích logic truy vấn từ code PHP

### Bước 3: Mapping dữ liệu
- Tạo bảng mapping giữa màn hình hiển thị và database
- Nhóm các trường theo logic nghiệp vụ
- Xác định các trường sử dụng mã chung (mst_code)

### Bước 4: Validation và Test
- Tạo queries lọc dữ liệu năm 2024
- Thống kê dữ liệu thực tế từ database
- Tạo test cases và expected responses

### Bước 5: Hoàn thiện tài liệu
- Bổ sung các lưu ý đặc biệt
- Tạo sample API calls
- Cung cấp hướng dẫn troubleshooting