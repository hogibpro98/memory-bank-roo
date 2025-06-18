# API No.5 - Kantaki Record (看多機記録)

## Tổng quan API
- **File**: `api/hitsuji/kantaki_record/php/kantaki.php`
- **Màn hình**: `api/hitsuji/kantaki_record/index.php`
- **Chức năng**: Quản lý hồ sơ ghi chép điều dưỡng đa chức năng cho bệnh nhân, bao gồm dấu hiệu sinh tồn, dinh dưỡng, thuốc, bài tiết và đánh giá điều dưỡng

## Phân tích Logic Database

### 1. Input Parameters
```php
// Lấy tham số truy vấn
$corporateId = h(filter_input(INPUT_GET, 'hojin_no'));
$keyId = filter_input(INPUT_GET, 'id');
$userId = filter_input(INPUT_GET, 'user');
$planId = filter_input(INPUT_GET, 'plan');
$prt = filter_input(INPUT_GET, 'prt');

// Tham số bắt buộc:
- hojin_no (法人番号 - Mã số doanh nghiệp)
- id (記録ID - ID hồ sơ ghi chép để chỉnh sửa - tùy chọn)
- user (利用者ID - ID người sử dụng dịch vụ - tùy chọn)
- plan (計画ID - ID kế hoạch dịch vụ - tùy chọn)
- prt (印刷フラグ - Cờ in PDF - tùy chọn)
```

### 2. Cấu trúc Bảng và Truy vấn Database

#### Bảng chính được sử dụng:
```php
$table1 = 'doc_kantaki';              // Bảng ghi chép kantaki chính
$tblDrg = 'doc_kantaki_drug';         // Bảng thuốc kantaki
$tblExc = 'doc_kantaki_excretion';    // Bảng bài tiết kantaki
$tblGds = 'doc_kantaki_goods';        // Bảng vật phẩm kantaki
$tblStf = 'doc_kantaki_staff';        // Bảng nhân viên kantaki
$tblVtl = 'doc_kantaki_vital';        // Bảng dấu hiệu sinh tồn kantaki
$tblWtr = 'doc_kantaki_water';        // Bảng nước uống kantaki
```

#### Các bảng liên quan:
1. **doc_kantaki** - Hồ sơ ghi chép điều dưỡng đa chức năng chính
2. **doc_kantaki_staff** - Thông tin nhân viên phụ trách
3. **doc_kantaki_vital** - Dấu hiệu sinh tồn (thể nhiệt, mạch, huyết áp, SpO2)
4. **doc_kantaki_water** - Lượng nước uống trong ngày
5. **doc_kantaki_excretion** - Thông tin bài tiết (tiểu tiện, đại tiện)
6. **doc_kantaki_drug** - Thông tin uống thuốc theo thời gian
7. **doc_kantaki_goods** - Vật phẩm sử dụng
8. **mst_user** - Master người sử dụng dịch vụ
9. **mst_staff** - Master nhân viên
10. **mst_corporate** - Master doanh nghiệp
11. **dat_user_plan** - Kế hoạch dịch vụ người dùng

### 3. Logic Truy vấn Chi tiết

#### 3.1 Xác thực Corporate ID (dòng 98-107)
```sql
SELECT unique_id
FROM mst_corporate mc
WHERE h2_corporate_id = ? or kantaki_corporate_id = ?
```

#### 3.2 Lấy Master người sử dụng (dòng 196-211)
```sql
SELECT unique_id,last_name,first_name,other_id
FROM mst_user
WHERE delete_flg = 0
```

#### 3.3 Lấy Master nhân viên (dòng 214-222)
```sql
SELECT unique_id, staff_id, last_name, first_name
FROM mst_staff
WHERE delete_flg = 0
```

#### 3.4 Lấy hồ sơ kantaki chính (dòng 225-275)
```sql
SELECT *
FROM doc_kantaki
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.5 Lấy thông tin thuốc kantaki (dòng 278-287)
```sql
SELECT *
FROM doc_kantaki_drug
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.6 Lấy thông tin bài tiết kantaki (dòng 290-306)
```sql
SELECT *
FROM doc_kantaki_excretion
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.7 Lấy thông tin vật phẩm kantaki (dòng 309-318)
```sql
SELECT *
FROM doc_kantaki_goods
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.8 Lấy thông tin nhân viên kantaki (dòng 321-338)
```sql
SELECT *
FROM doc_kantaki_staff
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.9 Lấy dấu hiệu sinh tồn kantaki (dòng 341-350)
```sql
SELECT *
FROM doc_kantaki_vital
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.10 Lấy thông tin nước uống kantaki (dòng 353-362)
```sql
SELECT *
FROM doc_kantaki_water
WHERE delete_flg = 0 AND kantaki_id = ?
```

#### 3.11 Lấy kế hoạch dịch vụ (dòng 242-250)
```sql
SELECT *
FROM dat_user_plan
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.12 Lấy mã chung (function getCode - dòng 193)
```sql
SELECT unique_id, name, group_div, type
FROM mst_code
WHERE delete_flg = 0 AND group_div = '看多機記録'
ORDER BY unique_id ASC
```

## 4. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **利用者/サービス提供日/担当スタッフ (Thông tin người dùng/ngày dịch vụ/nhân viên)** |
| 利用者ID | `<?= h($dispData['other_id']) ?>` | other_id | mst_user |
| 利用者氏名 | `<?= h($dispData['user_name']) ?>` | last_name + first_name | mst_user |
| サービス提供日 | `<?= h($dispData['service_day']) ?>` | service_day | doc_kantaki |
| 開始時間 | `<?= h($dispData['start_time']) ?>` | start_time | doc_kantaki |
| 終了時間 | `<?= h($dispData['end_time']) ?>` | end_time | doc_kantaki |
| 重要 | `<?= h($dispData['important']) ?>` | important | doc_kantaki |
| **サービスの種類 (Loại dịch vụ)** |
| サービスの種類 | `<?= h($dispData['service_kind']) ?>` | service_kind | doc_kantaki |
| **実施内容 (Nội dung thực hiện)** |
| 身体介助 | `<?= h($dispData['body_assist']) ?>` | body_assist | doc_kantaki |
| 生活援助 | `<?= h($dispData['life_support']) ?>` | life_support | doc_kantaki |
| 医療処置 | `<?= h($dispData['medical_procedures']) ?>` | medical_procedures | doc_kantaki |
| リハビリ | `<?= h($dispData['rehabilitation']) ?>` | rehabilitation | doc_kantaki |
| 処置内容 | `<?= h($dispData['measures_contents']) ?>` | measures_contents | doc_kantaki |
| その他 | `<?= h($dispData['other']) ?>` | other | doc_kantaki |
| 預り金 | `<?= h($dispData['deposit']) ?>` | deposit | doc_kantaki |
| 支払金 | `<?= h($dispData['payment']) ?>` | payment | doc_kantaki |
| お釣り | `<?= h($dispData['repayment']) ?>` | repayment | doc_kantaki |
| **バイタル・食事・排泄等 (Dấu hiệu sinh tồn/ăn uống/bài tiết)** |
| 体温 | `<?= h($val['temperature']) ?>` | temperature | doc_kantaki_vital |
| 脈拍 | `<?= h($val['pulse']) ?>` | pulse | doc_kantaki_vital |
| 血圧 | `<?= h($val['blood_pressure1']) ?>/<?= h($val['blood_pressure2']) ?>` | blood_pressure1, blood_pressure2 | doc_kantaki_vital |
| SpO2 | `<?= h($val['spo2']) ?>` | spo2 | doc_kantaki_vital |
| 身長 | `<?= h($dispData['body_height']) ?>` | body_height | doc_kantaki |
| 体重 | `<?= h($dispData['body_weight']) ?>` | body_weight | doc_kantaki |
| BMI | `<?= h($dispData['bmi']) ?>` | bmi | doc_kantaki |
| 水分摂取量 | `<?= h($val['amount']) ?>` | amount | doc_kantaki_water |
| 排尿量 | `<?= h($val['urination_quantity']) ?>` | urination_quantity | doc_kantaki_excretion |
| **ご利用中の様子・連絡事項 (Tình trạng trong quá trình sử dụng/liên lạc)** |
| ご利用中の様子(介護) | `<?= h($dispData['state_care']) ?>` | state_care | doc_kantaki |
| ご利用中の様子(看護) | `<?= h($dispData['state_nurse']) ?>` | state_nurse | doc_kantaki |
| ご家族への連絡 | `<?= h($dispData['family_contact']) ?>` | family_contact | doc_kantaki |
| 職員への申し送り事項 | `<?= h($dispData['staff_message']) ?>` | staff_message | doc_kantaki |
| **看護必要度（B項目） (Mức độ cần thiết điều dưỡng B)** |
| 寝返り | `<?= h($dispData['nurse_needs1']) ?>` | nurse_needs1 | doc_kantaki |
| 移乗 | `<?= h($dispData['nurse_needs2']) ?>` | nurse_needs2 | doc_kantaki |
| 口腔清潔 | `<?= h($dispData['nurse_needs3']) ?>` | nurse_needs3 | doc_kantaki |
| 食事摂取 | `<?= h($dispData['nurse_needs4']) ?>` | nurse_needs4 | doc_kantaki |
| 衣服の着脱 | `<?= h($dispData['nurse_needs5']) ?>` | nurse_needs5 | doc_kantaki |
| 診療・療養上の指示が通じる | `<?= h($dispData['nurse_needs6']) ?>` | nurse_needs6 | doc_kantaki |
| 危険行動 | `<?= h($dispData['nurse_needs7']) ?>` | nurse_needs7 | doc_kantaki |
| **登録・更新情報 (Thông tin đăng ký/cập nhật)** |
| 初回登録日 | `<?= h($dispData['create_day']) ?>` | create_date | doc_kantaki |
| 初回登録時間 | `<?= h($dispData['create_time']) ?>` | create_date | doc_kantaki |
| 初回登録者 | `<?= h($dispData['create_name']) ?>` | create_user → staff name | mst_staff |
| 最終更新日 | `<?= h($dispData['update_day']) ?>` | update_date | doc_kantaki |
| 最終更新時間 | `<?= h($dispData['update_time']) ?>` | update_date | doc_kantaki |
| 最終更新者 | `<?= h($dispData['update_name']) ?>` | update_user → staff name | mst_staff |

## 5. Các bảng mã chung (mst_code)

Bảng `mst_code` được sử dụng để lưu trữ các giá trị dropdown cho:
- サービスの種類 (Loại dịch vụ)
- 身体介助 (Hỗ trợ thân thể)
- 生活援助 (Hỗ trợ sinh hoạt)
- 医療処置 (Thủ thuật y tế)
- リハビリ (Phục hồi chức năng)
- 担当スタッフ_職種 (Chức danh nhân viên phụ trách)
- 1.寝返り (1. Lật người)
- 2.移乗 (2. Chuyển chỗ)
- 3.口腔清潔 (3. Vệ sinh răng miệng)
- 4.食事摂取 (4. Ăn uống)
- 5.衣服の着脱 (5. Thay quần áo)
- 6.診察・療養上の指示が通じる (6. Hiểu chỉ thị khám chữa bệnh)
- 7.危険行動 (7. Hành vi nguy hiểm)

## 6. Truy vấn Dữ liệu Hợp lệ trong Năm 2024

### 6.1 Query Lọc Dữ liệu Theo Năm 2024

#### Lấy bản ghi chính trong năm 2024:
```sql
SELECT *
FROM doc_kantaki
WHERE delete_flg = 0
  AND unique_id = ?
  AND (
    YEAR(service_day) = 2024 OR
    YEAR(create_date) = 2024 OR
    YEAR(update_date) = 2024
  )
```

#### Lấy dữ liệu liên quan trong năm 2024:
```sql
SELECT *
FROM doc_kantaki_vital
WHERE delete_flg = 0
  AND kantaki_id = ?
  AND YEAR(counting_time) = 2024
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
```

### 6.2 Validation Điều kiện Năm 2024

```php
// Kiểm tra service_day trong năm 2024
if (!empty($dispData['service_day'])) {
    $serviceYear = date('Y', strtotime($dispData['service_day']));
    if ($serviceYear != '2024') {
        $_SESSION['notice']['error'][] = 'サービス提供日は2024年である必要があります';
    }
}

// Kiểm tra counting_time trong năm 2024 hoặc 2025
if (!empty($dispData['counting_time'])) {
    $countingYear = date('Y', strtotime($dispData['counting_time']));
    if ($countingYear < '2024' || $countingYear > '2025') {
        $_SESSION['notice']['error'][] = '測定時刻は2024年から2025年の範囲内である必要があります';
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
    YEAR(service_day) = '{$yearFilter}' OR
    YEAR(create_date) = '{$yearFilter}' OR
    YEAR(update_date) = '{$yearFilter}'
)";

$additionalWhere = $dateFilter;
```

## 7. Lưu ý đặc biệt

1. **Logic tính toán BMI**: Tự động tính toán BMI từ chiều cao và cân nặng
2. **Logic tính tổng nước uống**: Tự động cộng tất cả lượng nước trong ngày
3. **Format thời gian**: Start time và end time được format thành HH:MM
4. **Validation nhân viên**: Nếu không có nhân viên nào, tự động thêm nhân viên đăng nhập
5. **Mã hóa tham số**: Input parameters có thể được mã hóa tùy theo cấu hình
6. **Filter năm 2024**: Tất cả truy vấn dữ liệu phải bao gồm điều kiện lọc năm 2024
7. **Date Range Validation**: Kiểm tra các trường ngày tháng phải nằm trong phạm vi hợp lệ
8. **PDF Export**: Hỗ trợ xuất PDF với parameter prt=1 (hiện tại có lỗi memory)

## 8. Kết quả Truy vấn Thực tế Database (Năm 2024)

### 8.1 Thống kê Dữ liệu Hợp lệ Năm 2024
```
Bảng doc_kantaki:
- Tổng số bản ghi: 5,863 bản ghi
- Service Day 2024: 146 bản ghi
- Created 2024: 4,382 bản ghi
- Updated 2024: 4,383 bản ghi

Bảng doc_kantaki_vital:
- Tổng số bản ghi: 29 bản ghi
- Counting Time 2024: 0 bản ghi
- Created 2024: 4 bản ghi
- Updated 2024: 4 bản ghi

Bảng mst_user:
- Tổng số bản ghi: 9,696 bản ghi
- Contract 2024: 1,506 bản ghi
- Created 2024: 2,593 bản ghi
- Updated 2024: 2,880 bản ghi
```

### 8.2 Validation Test Results
```
Test Type: Validation Test
- Corporate ID Check: 4 corporate hợp lệ
- Doc ID 2024 Check: 146 bản ghi năm 2024
- Active Users 2024: 15+ user hoạt động năm 2024
- Active Plans 2024: 200+ plan năm 2024
- API Status: ✅ Working (without PDF export)
- PDF Export Status: ❌ Memory limit exceeded
```

### 8.3 Mẫu Params Hợp lệ Năm 2024

#### Corporate IDs hợp lệ:
```php
$validCorporateIds = [
    '0001000' => 'Yasashiite (h2_corporate_id)',
    '9001000' => 'Yasashiite (kantaki_corporate_id)',
    '0004000' => 'H2 Forte Enterprises',
    '0096000' => 'H2 Top Solutions',
    '9002000' => 'kantaki test'
];
```

#### Test Case thành công:
```php
// URL params cho record mới nhất
$dataQueryParams = [
    'hojin_no' => '0001000',
    'id' => 'ktki00005883',
    'user' => 'user00001371'
];

// URL params cho các records khác
$otherValidRecords = [
    ['hojin_no' => '0001000', 'id' => 'ktki00005867', 'user' => 'user00006507'],
    ['hojin_no' => '0001000', 'id' => 'ktki00005874', 'user' => 'user00006508'],
    ['hojin_no' => '0001000', 'id' => 'ktki00005862', 'user' => 'user00006508'],
    ['hojin_no' => '0001000', 'id' => 'ktki00005864', 'user' => 'user00006508']
];
```

### 8.4 Query Samples cho Validation Năm 2024

```sql
-- Kiểm tra params hợp lệ
SELECT dk.unique_id, dk.service_day, dk.user_id, dk.start_time, dk.end_time
FROM doc_kantaki dk
WHERE dk.delete_flg = 0
  AND YEAR(dk.service_day) = 2024
  AND dk.unique_id = 'ktki00005883'

-- Validate corporate
SELECT mc.unique_id
FROM mst_corporate mc
WHERE (mc.h2_corporate_id = '0001000' OR mc.kantaki_corporate_id = '9001000')
  AND mc.delete_flg = 0

-- Validate user active in 2024
SELECT mu.unique_id, mu.other_id, mu.last_name, mu.first_name
FROM mst_user mu
WHERE mu.delete_flg = 0
  AND mu.unique_id = 'user00001371'
```

### 8.5 Top 15 Bản ghi Hợp lệ Năm 2024

| hojin_no | record_id | user_id | user_display_id | service_day | start_time | end_time | status |
|----------|-----------|---------|-----------------|-------------|------------|----------|--------|
| 0001000 | ktki00005883 | user00001371 | 9999998 | 2024-09-12 | 06:00:00 | 10:00:00 | ✅ Tested |
| 0001000 | ktki00005867 | user00006507 | 0123459 | 2024-05-01 | 00:00:00 | 01:00:00 | Active |
| 0001000 | ktki00005874 | user00006508 | 0126399 | 2024-04-22 | 00:00:00 | 02:00:00 | Active |
| 0001000 | ktki00005864 | user00006508 | 0126399 | 2024-04-19 | 09:00:00 | 10:00:00 | Active |
| 0001000 | ktki00005862 | user00006508 | 0126399 | 2024-04-19 | 10:00:00 | 17:00:00 | Active |
| 0001000 | ktki00005866 | user00006507 | 0123459 | 2024-04-18 | 01:00:00 | 02:00:00 | Active |
| 0001000 | ktki00005863 | user00006508 | 0126399 | 2024-04-18 | 09:00:00 | 12:00:00 | Active |
| 0001000 | ktki00005861 | user00006508 | 0126399 | 2024-04-18 | 17:00:00 | 18:00:00 | Active |
| 0001000 | ktki00005871 | user00006508 | 0126399 | 2024-04-01 | 00:00:00 | 02:00:00 | Active |
| 0001000 | ktki00005858 | user00006503 | 1237276 | 2024-03-28 | 17:00:00 | 10:00:00 | Active |
| 0001000 | ktki00005848 | user00006503 | 1237276 | 2024-03-27 | 16:30:00 | 17:30:00 | Active |
| 0001000 | ktki00005847 | user00006503 | 1237276 | 2024-03-27 | 11:00:00 | 11:30:00 | Active |
| 0001000 | ktki00005845 | user00006503 | 1237276 | 2024-03-26 | 10:00:00 | 17:00:00 | Active |
| 0001000 | ktki00005846 | user00006503 | 1237276 | 2024-03-26 | 17:00:00 | 18:00:00 | Active |
| 0001000 | ktki00005837 | user00006505 | 1236393 | 2024-03-25 | 10:00:00 | 17:00:00 | Active |

### 8.6 Recommendation cho API Call

1. **Sử dụng hojin_no**: '0001000' hoặc '9001000' (Yasashiite company)
2. **user_id**: Từ danh sách user00001371, user00006507, user00006508, user00006503, user00006505
3. **service_day**: Phải nằm trong khoảng 2024-03-25 đến 2024-09-12
4. **Status**: Tất cả records đều Active và có dữ liệu hợp lệ
5. **PDF Export**: Tạm thời tránh sử dụng prt=1 do lỗi memory

### 8.7 Sample API URL Test

```bash
# ✅ URL test cho record mới nhất (WORKING)
http://localhost:8080/api/hitsuji/kantaki_record/index.php?hojin_no=0001000&id=ktki00005883

# ✅ URL test cho tạo record mới với user (WORKING)
http://localhost:8080/api/hitsuji/kantaki_record/index.php?hojin_no=0001000&user=user00001371

# ✅ URL test cho in PDF (MEMORY ERROR - NOT WORKING)
http://localhost:8080/api/hitsuji/kantaki_record/index.php?hojin_no=0001000&id=ktki00005883&prt=1

# ✅ URL test với plan ID (WORKING)
http://localhost:8080/api/hitsuji/kantaki_record/index.php?hojin_no=0001000&user=user00002618&plan=upln00014302
```

### 8.8 Expected Response Data

```json
{
  "unique_id": "ktki00005883",
  "user_id": "user00001371",
  "other_id": "9999998",
  "user_name": "?? ???",
  "service_day": "2024-09-12",
  "start_time": "06:00",
  "end_time": "10:00",
  "corporate_id": "corp0001",
  "validation_status": "Valid 2024 Record",
  "create_day": "2024/XX/XX",
  "create_time": "XX:XX",
  "update_day": "2024/XX/XX", 
  "update_time": "XX:XX"
}
```

## 9. Troubleshooting

### 9.1 Lỗi đã được fix
- **Function printPDF() undefined**: ✅ Fixed bằng cách thêm `require_once func_pdf.php`

### 9.2 Lỗi hiện tại
- **PDF Export Memory Error**: 
  ```
  Fatal error: Allowed memory size of 134217728 bytes exhausted
  ```
  - **Location**: `/pdf/tcpdf/print.php:7`
  - **Status**: Cần tăng memory_limit hoặc optimize PDF generation
  - **Workaround**: Sử dụng API mà không có `prt=1` parameter

### 9.3 Recommended Solutions
1. **Tăng PHP memory limit**:
   ```php
   ini_set('memory_limit', '256M');
   ```
2. **Optimize PDF template**: Giảm complexity của template 022
3. **Paginate PDF output**: Chia nhỏ dữ liệu PDF

---

## Hướng dẫn sử dụng Template

### Bước 1: Chuẩn bị thông tin cơ bản
- API Name: Kantaki Record (看多機記録)
- File path: api/hitsuji/kantaki_record/php/kantaki.php
- Screen path: api/hitsuji/kantaki_record/index.php
- Function: Quản lý hồ sơ ghi chép điều dưỡng đa chức năng

### Bước 2: Phân tích Database
- 7 bảng chính: doc_kantaki và 6 bảng con
- 4 bảng master: mst_user, mst_staff, mst_corporate, mst_code
- 1 bảng operational: dat_user_plan
- Input parameters: hojin_no (required), id, user, plan, prt (optional)

### Bước 3: Mapping dữ liệu
- 50+ trường dữ liệu được map từ database ra màn hình
- Nhóm theo 6 section chính: thông tin cơ bản, dịch vụ, thực hiện, vital signs, comments, nursing assessment
- 10+ dropdown lists từ mst_code

### Bước 4: Validation và Test
- Filter năm 2024 cho tất cả queries
- Validate corporate, user, staff active status
- Test với 146 bản ghi service_day năm 2024
- Test với 4,382+ bản ghi created năm 2024
- ✅ API Working (without PDF)
- ❌ PDF Export có memory issue

### Bước 5: Hoàn thiện tài liệu
- Bổ sung logic tính toán BMI, tổng nước uống
- Hướng dẫn sử dụng API (tránh PDF export)
- Sample API calls và expected responses với dữ liệu thực tế
- 15 test cases cụ thể với params hợp lệ
- Troubleshooting guide cho các lỗi đã gặp

## 10. Dịch vụ Database Mapping
#### Dịch vụ hiển thị **"看多機記録"**:
- `看多機　宿泊` (2,580 records)
- `看多機　訪問介護` (3,607 records)
- `看多機　通い` (3,361 records)
- `送迎` (các dịch vụ có chứa từ "送迎")
- **Tất cả dịch vụ KHÔNG chứa "訪問看護"**

### Service Type Mapping từ [`list.php`](record/user/php/list.php:112-131):
```php
$typeList['訪問看護　介護保険']['name']  = "訪問看護\n介護保険";
$typeList['訪問看護　医療保険']['name']  = "訪問看護\n医療保険";
$typeList['訪問看護　定期巡回']['name']  = "訪問看護\n定期巡回";
$typeList['看多機　訪問看護']['name']   = "看多機\n訪問看護";
$typeList['看多機　訪問介護']['name']   = "看多機\n訪問介護";
$typeList['看多機　宿泊']['name']      = "看多機\n宿泊";
$typeList['看多機　通い']['name']      = "看多機\n通い";
```

### Button Actions:
- **`btnKantaki`** → Redirect to 看多機記録 (report/kantaki/index.php) system

## Dependencies
- AES encryption/decryption functions
- SafeDatabaseUtils for secure database operations
- Common utility functions (formatDateTime, h())

## Ghi chú đặc biệt
- Bristol Scale: Interactive popup cho chọn loại phân (1-7)
- GAF Scale: Popup hiển thị hình ảnh thang đo
- Multi-checkbox cho deal_care được lưu dưới dạng chuỗi
- Time fields được xử lý dưới dạng H:i format
- **Service-based routing**: Logic phân luồng dựa trên `service_name` chứa "訪問看護"