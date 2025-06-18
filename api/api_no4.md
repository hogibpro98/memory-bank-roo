# API No.4 - 訪問看護記録Ⅱ詳細 (Nurse Record Visit 2 Detail)

## Tổng quan API
- **File**: `api/hitsuji/nurse_record/php/visit2.php`
- **Màn hình**: `api/hitsuji/nurse_record/index.php`
- **Chức năng**: Quản lý chi tiết bản ghi thăm khám điều dưỡng lần 2

## Phân tích Logic Database (từ dòng 88)

### 1. Input Parameters
```php
// Dòng 88: Lấy tham số truy vấn
$dataQueryParams = h(filter_input(INPUT_GET, 'data'));

// Giải mã tham số
$dataQueryParams = decryptAesBase64($dataQueryParams);

// Tham số bắt buộc:
- houjin_no (法人番号 - Mã pháp nhân)
- doc_id (文書ID - ID tài liệu)
```

### 2. Cấu trúc Bảng và Truy vấn Database

#### Bảng chính được sử dụng:
```php
$table1 = 'doc_visit2';           // Bảng chính - bản ghi thăm khám điều dưỡng
$table2 = 'doc_visit2_problem';   // Bảng phụ - vấn đề/ghi chú
```

#### Các bảng liên quan:
1. **mst_corporate** - Thông tin pháp nhân
2. **mst_user** - Thông tin người dùng/bệnh nhân  
3. **mst_staff** - Thông tin nhân viên
4. **dat_user_plan** - Kế hoạch sử dụng dịch vụ
5. **mst_code** - Bảng mã chung

### 3. Logic Truy vấn Chi tiết

#### 3.1 Xác thực Corporate ID (dòng 116-125)
```sql
SELECT unique_id
FROM mst_corporate mc
WHERE h2_corporate_id = ? or kantaki_corporate_id = ?
```

#### 3.2 Lấy User ID từ Document (dòng 131-139)
```sql
SELECT user_id
FROM doc_visit2
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.3 Lấy thông tin User (dòng 255-270)
```sql
SELECT unique_id, last_name, first_name, other_id
FROM mst_user
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.4 Lấy dữ liệu chính Visit2 (dòng 273-330)
```sql
SELECT *
FROM doc_visit2
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.5 Lấy thông tin Staff (function getStaffName - dòng 148-165)
```sql
SELECT last_name, first_name
FROM mst_staff
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.6 Lấy Staff ID (function getStaffIdByUniqueId - dòng 167-177)
```sql
SELECT staff_id
FROM mst_staff
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.7 Lấy thông tin Plan (dòng 295-306)
```sql
SELECT *
FROM dat_user_plan
WHERE delete_flg = 0 AND unique_id = ?
```

#### 3.8 Lấy dữ liệu Problem (dòng 333-344)
```sql
SELECT *
FROM doc_visit2_problem
WHERE delete_flg = 0 AND visit2_id = ?
```

#### 3.9 Lấy mã chung (function getCode - dòng 179-220)
```sql
SELECT unique_id, name, group_div, type
FROM mst_code
WHERE delete_flg = 0 AND group_div = '訪問看護記録Ⅱ詳細'
ORDER BY unique_id ASC
```

## 4. Mapping Data với Màn hình Hiển thị

| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
| **Thông tin cơ bản** |
| 利用者ID | `<?= h($dispData['other_id']) ?>` | other_id | mst_user |
| 利用者氏名 | `<?= h($dispData['user_name']) ?>` | last_name + first_name | mst_user |
| 訪問看護区分 | `$dispData['care_kb']` | care_kb | doc_visit2 |
| 重要 | `$dispData['importantly']` | importantly | doc_visit2 |
| **サービス提供日時** |
| サービス提供日 | `<?= h($dispData['service_day']) ?>` | service_day | doc_visit2/dat_user_plan |
| 開始時間 | `$dispData['start_time']` | start_time | doc_visit2/dat_user_plan |
| 終了時間 | `$dispData['end_time']` | end_time | doc_visit2/dat_user_plan |
| 次回サービス提供日 | `<?= h($dispData['next_day']) ?>` | next_day | doc_visit2 |
| 次回開始時間 | `$dispData['next_start']` | next_start | doc_visit2 |
| 次回終了時間 | `$dispData['next_end']` | next_end | doc_visit2 |
| **スタッフ情報** |
| 訪問スタッフ1 | `<?= h($dispData['staff1_name']) ?>` | staff1_id → last_name + first_name | mst_staff |
| スタッフ1コード | `<?= h($dispData['staff1_cd']) ?>` | staff1_id → staff_id | mst_staff |
| スタッフ1資格 | `$dispData['visit1_job']` | visit1_job | doc_visit2 |
| 訪問スタッフ2 | `<?= h($dispData['staff2_name']) ?>` | staff2_id → last_name + first_name | mst_staff |
| スタッフ2コード | `<?= h($dispData['staff2_cd']) ?>` | staff2_id → staff_id | mst_staff |
| スタッフ2資格 | `$dispData['visit2_job']` | visit2_job | doc_visit2 |
| **バイタルサイン** |
| 体温 | `<?= h($dispData['temperature']) ?>` | temperature | doc_visit2 |
| 脈拍 | `<?= h($dispData['pulse']) ?>` | pulse | doc_visit2 |
| 血圧上 | `<?= h($dispData['blood_pressure1']) ?>` | blood_pressure1 | doc_visit2 |
| 血圧下 | `<?= h($dispData['blood_pressure2']) ?>` | blood_pressure2 | doc_visit2 |
| 呼吸 | `<?= h($dispData['pneusis']) ?>` | pneusis | doc_visit2 |
| 呼吸右 | `$dispData['pneusis_right']` | pneusis_right | doc_visit2 |
| 呼吸左 | `$dispData['pneusis_left']` | pneusis_left | doc_visit2 |
| SpO2 | `<?= h($dispData['spo2']) ?>` | spo2 | doc_visit2 |
| **栄養・代謝** |
| 栄養・代謝区分 | `$dispData['metabolism_kb']` | metabolism_kb | doc_visit2 |
| 栄養・代謝詳細 | `<?= h($dispData['metabolism_detail']) ?>` | metabolism_detail | doc_visit2 |
| 栄養・代謝メモ | `<?= h($dispData['metabolism_memo']) ?>` | metabolism_memo | doc_visit2 |
| **睡眠・休息** |
| 睡眠・休息区分 | `$dispData['rest_kb']` | rest_kb | doc_visit2 |
| 睡眠・休息メモ | `<?= h($dispData['rest_memo']) ?>` | rest_memo | doc_visit2 |
| **排尿** |
| 排尿頻度 | `<?= h($dispData['urination_frequency']) ?>` | urination_frequency | doc_visit2 |
| 排尿期間 | `$dispData['urination_term']` | urination_term | doc_visit2 |
| **排便** |
| 排便頻度 | `<?= h($dispData['evacuation_frequency']) ?>` | evacuation_frequency | doc_visit2 |
| 排便期間 | `$dispData['evacuation_term']` | evacuation_term | doc_visit2 |
| Bristol便形状 | `$dispData['bristol']` | bristol | doc_visit2 |
| 排便メモ | `<?= h($dispData['evacuation_memo']) ?>` | evacuation_memo | doc_visit2 |
| **その他** |
| 医師からの指示 | `<?= h($dispData['doctor_instruction']) ?>` | doctor_instruction | doc_visit2 |
| 備考 | `<?= h($dispData['remarks']) ?>` | remarks | doc_visit2 |
| 主治医次回診察日 | `<?= h($dispData['next_examination']) ?>` | next_examination | doc_visit2 |
| GAF尺度 | `<?= h($dispData['gaf']) ?>` | gaf | doc_visit2 |
| その他処置 | `<?= h($dispData['other']) ?>` | other | doc_visit2 |
| **問題点/記録** |
| 問題点 | `<?= h($val['problem']) ?>` | problem | doc_visit2_problem |
| コメント | `<?= h($val['comment']) ?>` | comment | doc_visit2_problem |
| **処置・ケア** |
| 身体介助 | `strpos($dispData['deal_care'], $val)` | deal_care | doc_visit2 |
| 処置 | `strpos($dispData['deal_care'], $val)` | deal_care | doc_visit2 |
| 管理・指導 | `strpos($dispData['deal_care'], $val)` | deal_care | doc_visit2 |
| リハビリ | `strpos($dispData['deal_care'], $val)` | deal_care | doc_visit2 |
| **登録・更新情報** |
| 初回登録日 | `<?= h($dispData['create_day']) ?>` | create_date | doc_visit2 |
| 初回登録時間 | `<?= h($dispData['create_time']) ?>` | create_date | doc_visit2 |
| 初回登録者 | `<?= h($dispData['create_name']) ?>` | create_user → staff name | mst_staff |
| 最終更新日 | `<?= h($dispData['update_day']) ?>` | update_date | doc_visit2 |
| 最終更新時間 | `<?= h($dispData['update_time']) ?>` | update_date | doc_visit2 |
| 最終更新者 | `<?= h($dispData['update_name']) ?>` | update_user → staff name | mst_staff |

## 5. Các bảng mã chung (mst_code)

Bảng `mst_code` được sử dụng để lưu trữ các giá trị dropdown cho:
- 訪問看護区分 (Phân loại thăm khám điều dưỡng)
- 訪問スタッフ1_資格, 訪問スタッフ2_資格 (Tư cách nhân viên)
- 右, 左 (Phải, Trái - cho hô hấp)
- 栄養・代謝, 睡眠・休息 (Dinh dưỡng, giấc ngủ)
- 排尿, 排便 (Tiểu tiện, đại tiện)
- 身体介助, 処置, 管理・指導, リハビリ (Các loại chăm sóc)

## 6. Truy vấn Dữ liệu Hợp lệ trong Năm 2024

### 6.1 Query Lọc Dữ liệu Theo Năm 2024

#### Lấy bản ghi Visit2 trong năm 2024:
```sql
SELECT *
FROM doc_visit2
WHERE delete_flg = 0
  AND unique_id = ?
  AND (
    YEAR(service_day) = 2024 OR
    YEAR(create_date) = 2024 OR
    YEAR(update_date) = 2024
  )
```

#### Lấy Plan data trong năm 2024:
```sql
SELECT *
FROM dat_user_plan
WHERE delete_flg = 0
  AND unique_id = ?
  AND YEAR(use_day) = 2024
```

#### Lấy Problem data có liên quan đến Visit2 năm 2024:
```sql
SELECT p.*
FROM doc_visit2_problem p
INNER JOIN doc_visit2 v ON p.visit2_id = v.unique_id
WHERE p.delete_flg = 0
  AND p.visit2_id = ?
  AND (
    YEAR(v.service_day) = 2024 OR
    YEAR(v.create_date) = 2024
  )
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
// Kiểm tra service_day trong năm 2024
if (!empty($dispData['service_day'])) {
    $serviceYear = date('Y', strtotime($dispData['service_day']));
    if ($serviceYear != '2024') {
        // Xử lý trường hợp không hợp lệ
        $_SESSION['notice']['error'][] = 'サービス提供日は2024年である必要があります';
    }
}

// Kiểm tra next_day trong năm 2024 hoặc 2025
if (!empty($dispData['next_day'])) {
    $nextYear = date('Y', strtotime($dispData['next_day']));
    if ($nextYear < '2024' || $nextYear > '2025') {
        $_SESSION['notice']['error'][] = '次回サービス提供日は2024年または2025年である必要があります';
    }
}

// Kiểm tra next_examination trong năm 2024 trở đi
if (!empty($dispData['next_examination'])) {
    $examYear = date('Y', strtotime($dispData['next_examination']));
    if ($examYear < '2024') {
        $_SESSION['notice']['error'][] = '主治医次回診察日は2024年以降である必要があります';
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

1. **Logic lấy thời gian từ Plan**: Nếu service_day, start_time, end_time trống trong doc_visit2, hệ thống sẽ lấy từ dat_user_plan dựa trên target_plan_id
2. **Xử lý checkbox**: Trường deal_care lưu dạng chuỗi, sử dụng strpos() để check các giá trị
3. **Format thời gian**: Các trường thời gian được format bằng formatDateTime()
4. **Mã hóa tham số**: Input parameters được mã hóa AES Base64
5. **Validation**: Kiểm tra tồn tại của houjin_no và doc_id trước khi xử lý
6. **Filter năm 2024**: Tất cả truy vấn dữ liệu phải bao gồm điều kiện lọc năm 2024 để đảm bảo tính hợp lệ
7. **Date Range Validation**: Kiểm tra các trường ngày tháng phải nằm trong phạm vi hợp lệ của năm 2024
## 8. Kết quả Truy vấn Thực tế Database (Năm 2024)

### 8.1 Thống kê Dữ liệu Hợp lệ Năm 2024
```
Bảng doc_visit2:
- Tổng số bản ghi: 1,683
- Service Day 2024: 82 bản ghi
- Next Day 2024: 5 bản ghi  
- Next Exam 2024: 2 bản ghi
- Created 2024: 1,200 bản ghi
- Updated 2024: 1,200 bản ghi
```

### 8.2 Validation Test Results
```
Test Type: Validation Test
- Corporate ID Check: 4 corporate hợp lệ
- Doc ID 2024 Check: 82 bản ghi visit2 năm 2024
- Active Users 2024: 22 user hoạt động năm 2024
```

### 8.3 Mẫu Params Hợp lệ Năm 2024

#### Test Case thành công:
```php
// URL params được mã hóa AES Base64
$dataQueryParams = [
    'houjin_no' => 'h2_corporate_id_value', // Từ mst_corporate
    'doc_id' => 'vis200001686'               // Document ID hợp lệ năm 2024
];

// Dữ liệu trả về:
$validRecord = [
    'doc_id' => 'vis200001686',
    'user_id' => 'user00000011',
    'service_day' => '2024-12-02',
    'care_kb' => null,
    'staff1_id' => 'stff0002', 
    'status' => '完成',
    'validation_status' => 'Valid 2024 Record'
];
```

#### Dữ liệu liên quan được validate:
```php
// User info (mst_user)
$userInfo = [
    'unique_id' => 'user00000011',
    'other_id' => '1139196',
    'name' => '安齋 紀子'
];

// Staff info (mst_staff)  
$staffInfo = [
    'unique_id' => 'stff0002',
    'staff_id' => '000003',
    'name' => '香取 寛'
];

// Problem data (doc_visit2_problem)
$problemInfo = [
    'unique_id' => 'vpro00001803',
    'visit2_id' => 'vis200001686',
    'problem' => '',
    'comment' => '',
    'create_date' => '2024-12-03 17:27:17'
];
```

### 8.4 Query Samples cho Validation Năm 2024

```sql
-- Kiểm tra params hợp lệ
SELECT dv.unique_id, dv.user_id, dv.service_day, dv.status
FROM doc_visit2 dv
WHERE dv.delete_flg = 0
  AND YEAR(dv.service_day) = 2024
  AND dv.unique_id = ?  -- doc_id từ params

-- Validate corporate
SELECT mc.unique_id
FROM mst_corporate mc
WHERE (mc.h2_corporate_id = ? OR mc.kantaki_corporate_id = ?)
  AND mc.delete_flg = 0

-- Validate user active in 2024
SELECT mu.unique_id, mu.other_id, 
       CONCAT(mu.last_name, ' ', mu.first_name) as name
FROM mst_user mu
WHERE mu.unique_id = ?
  AND mu.delete_flg = 0
```

### 8.5 Top 10 Bản ghi Hợp lệ Năm 2024

| doc_id | user_id | service_day | care_kb | staff1_id | status |
|--------|---------|-------------|---------|-----------|--------|
| vis200001686 | user00000011 | 2024-12-02 | NULL | stff0002 | 完成 |
| vis200001683 | user00005632 | 2024-11-14 | 訪問看護 | stff0382 | 完成 |
| vis200001680 | user00001371 | 2024-10-24 | NULL | stff0444 | 完成 |
| vis200001675 | user00005755 | 2024-08-15 | 精神訪問看護 | stff0382 | 完成 |
| vis200001674 | user00005755 | 2024-08-01 | 精神訪問看護 | stff0382 | 完成 |
| vis200001673 | user00006507 | 2024-07-24 | 訪問看護 | stff7181 | 完成 |
| vis200001666 | user00005632 | 2024-06-05 | 訪問看護 | stff0382 | 完成 |
| vis200001664 | user00006507 | 2024-06-03 | NULL | stff7181 | 完成 |
| vis200001677 | user00001371 | 2024-06-02 | NULL | stff0530 | 完成 |
| vis200001662 | user00001398 | 2024-05-13 | 訪問看護 | stff0382 | 完成 |

### 8.6 Recommendation cho API Call

1. **Sử dụng doc_id từ danh sách trên** để test API
2. **Houjin_no**: Lấy từ bảng mst_corporate (4 corporate khả dụng)
3. **Service_day**: Phải nằm trong khoảng 2024-05-13 đến 2024-12-02
4. **Status**: Tất cả bản ghi đều có status = '完成' (hoàn thành)
5. **Care_kb**: Các giá trị hợp lệ: NULL, '訪問看護', '精神訪問看護'

### 8.7 Sample API URL Test

```bash
# Mã hóa params
$params = ['houjin_no' => 'corp0001', 'doc_id' => 'vis200001686'];
$encodedParams = encryptAesBase64($params);

# URL test
http://domain.com/api/hitsuji/nurse_record/index.php?data={$encodedParams}
```

### 8.8 Expected Response Data

```json
{
  "other_id": "1139196",
  "user_name": "安齋 紀子", 
  "service_day": "2024-12-02",
  "care_kb": null,
  "staff1_name": "香取 寛",
  "staff1_cd": "000003",
  "status": "完成",
  "create_day": "2024/12/03",
  "create_time": "17:27",
  "update_day": "2024/12/03", 
  "update_time": "17:27"
}
```
## 9. Dịch vụ Database Mapping
#### Dịch vụ hiển thị **"看多機記録"**:
- `看多機　宿泊` (2,580 records)
- `看多機　訪問介護` (3,607 records)
- `看多機　通い` (3,361 records)
- `送迎` (các dịch vụ có chứa từ "送迎")
- **Tất cả dịch vụ KHÔNG chứa "訪問看護"**

#### Dịch vụ hiển thị **"訪看記録Ⅱ"** (API này):
- `訪問看護　介護保険` (1,019 records)
- `訪問看護　医療保険` (2,919 records)
- `訪問看護　定期巡回` (83 records)
- `看多機　訪問看護` (1,458 records)
- **Tất cả dịch vụ có chứa "訪問看護"**

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
- **`btnHokan2`** → Redirect to 訪看記録Ⅱ system (API `api/hitsuji/nurse_record`)

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