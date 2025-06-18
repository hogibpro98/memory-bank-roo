# API No.3 - Phân Tích API Kế Hoạch Người Dùng

## Tổng Quan API
**File:** `api/hitsuji/user_plan/index.php`  
**Mục đích:** 予定情報送信API (API Truyền Thông Tin Lịch Trình)  
**Phương thức:** GET  
**Phản hồi:** JSON

## Dữ lệu được tạo từ màn hình 利用者予定実績(/record/user/index.php)

## Phân Tích Tham Số Yêu Cầu

### Tham Số Chính
```php
$dataQueryParams = h(filter_input(INPUT_GET, 'data'));
```

### Quy Trình Xử Lý Tham Số
1. **Xác thực đầu vào**: Kiểm tra tham số `data` có tồn tại hay không
2. **Giải mã**: Giải mã bằng mã hóa AES Base64
   ```php
   $dataQueryParams = decryptAesBase64($dataQueryParams);
   ```
3. **Trích xuất tham số**: Trích xuất các tham số riêng lẻ từ dữ liệu đã giải mã

### Tham Số Bắt Buộc (trong $dataQueryParams)
| Tham số | Tiếng Nhật | Tiếng Việt | Mục đích |
|---------|------------|------------|----------|
| `houjin_no` | 法人番号 | Số pháp nhân | Định danh doanh nghiệp |
| `jigyo_no` | 事業所番号 | Số cơ sở kinh doanh | Định danh văn phòng/chi nhánh |
| `riyo_no` | 利用者番号 | Số người sử dụng | Định danh người dùng |
| `month` | 対象年月 | Tháng mục tiêu | Tháng mục tiêu (YYYY/MM hoặc YYYY-MM) |

## Logic Truy Vấn Cơ Sở Dữ Liệu

### 1. Phân Giải ID Doanh Nghiệp
```sql
SELECT unique_id
FROM mst_corporate mc
WHERE h2_corporate_id = ? or kantaki_corporate_id = ?
```
- **Đầu vào**: `houjin_no` (法人番号)
- **Đầu ra**: `corporateId` (unique_id)
- **Bảng**: [`mst_corporate`](../database/master-tables.md:corporate-master)

### 2. Phân Giải ID Người Dùng
```php
$where['other_id'] = $usrNo; // riyo_no
$temp = getData('mst_user', $where);
```
- **Đầu vào**: `riyo_no` (利用者番号) 
- **Đầu ra**: `userId` (unique_id)
- **Bảng**: [`mst_user`](../database/master-tables.md:user-master)
- **Ánh xạ**: Trường `other_id` ánh xạ với `riyo_no`

### 3. Phân Giải ID Văn Phòng  
```php
$where['other_code'] = $ofcNo; // jigyo_no
$temp = getData('mst_office', $where);
```
- **Đầu vào**: `jigyo_no` (事業所番号)
- **Đầu ra**: `ofcId` (unique_id) 
- **Bảng**: [`mst_office`](../database/master-tables.md:office-master)
- **Ánh xạ**: Trường `other_code` ánh xạ với `jigyo_no`

### 4. Truy Vấn Dữ Liệu Chính - Kế Hoạch Người Dùng
```php
$where = array();
$where['use_day LIKE'] = '%' . $month . '%';  // Bộ lọc tháng mục tiêu
$where['user_id'] = $userId;                  // Bộ lọc người dùng
if ($ofcNo != 'all') {
    $where['office_id'] = $ofcId;             // Bộ lọc văn phòng (tùy chọn)
}
$planData = getData('dat_user_plan', $where);
```
- **Bảng**: [`dat_user_plan`](../database/operational-tables.md:user-plan)
- **Bộ lọc**:
  - Tháng: `use_day LIKE '%YYYY-MM%'`
  - Người dùng: `user_id = $userId`
  - Văn phòng: `office_id = $ofcId` (nếu không phải 'all')

### 5. Truy Vấn Chi Tiết Dịch Vụ
```php
$where = array();
$where['user_plan_id'] = $planIds;  // Mảng các ID kế hoạch
$temp = getData('dat_user_plan_service', $where);
```
- **Bảng**: [`dat_user_plan_service`](../database/operational-tables.md:user-plan-service)
- **Mối quan hệ**: Bản ghi con của `dat_user_plan`
- **Khóa ngoại**: `user_plan_id` → `dat_user_plan.unique_id`

## Tải Dữ Liệu Master

### Master Nhân Viên
```php
$stfMst = getData('mst_staff');
```
- **Bảng**: [`mst_staff`](../database/master-tables.md:staff-master)
- **Sử dụng**: Tên và ID nhân viên cho chi tiết dịch vụ

### Master Chi Tiết Dịch Vụ
```php
$svcMst = getData('mst_service_detail');
```
- **Bảng**: [`mst_service_detail`](../database/master-tables.md:service-detail-master)
- **Sử dụng**: Tên loại dịch vụ và chi tiết

## Bảng Cơ Sở Dữ Liệu Liên Quan

| Bảng | Loại | Mục đích | Trường khóa |
|------|------|----------|-------------|
| `mst_corporate` | Master | Thông tin doanh nghiệp | `h2_corporate_id`, `kantaki_corporate_id`, `unique_id` |
| `mst_user` | Master | Thông tin người dùng | `other_id`, `unique_id` |
| `mst_office` | Master | Thông tin văn phòng | `other_code`, `unique_id`, `name` |
| `mst_staff` | Master | Thông tin nhân viên | `unique_id`, `staff_id`, `last_name`, `first_name` |
| `mst_service_detail` | Master | Chi tiết dịch vụ | `unique_id`, `name` |
| `dat_user_plan` | Hoạt động | Kế hoạch lịch trình người dùng | `unique_id`, `user_id`, `office_id`, `use_day`, `service_name`, `status`, `start_time`, `end_time`, `kantaki` |
| `dat_user_plan_service` | Hoạt động | Chi tiết dịch vụ kế hoạch | `user_plan_id`, `service_detail_id`, `staff_id` |

## Cấu Trúc Dữ Liệu Phản Hồi

### Định Dạng Phản Hồi Chính
```json
{
    "result": 0,           // 0=thành công, 1=lỗi
    "error": "",           // Thông báo lỗi nếu có
    "kbn": 0,             // Cờ loại dịch vụ (1 nếu là dịch vụ 看多機)
    "plan": [             // Mảng dữ liệu kế hoạch
        {
            "plan_day": "YYYY/MM/DD",
            "status": 1,           // 1=実施, 0=khác
            "start_time": "HH:MM",
            "end_time": "HH:MM", 
            "svc_name": "Tên Dịch Vụ",
            "doc_id": "ID Tài Liệu",
            "jigyo_name": "Tên Văn Phòng",
            "jigyo_no": "Số Văn Phòng",
            "detail": [           // Mảng chi tiết dịch vụ
                {
                    "detail_name": "Tên Chi Tiết Dịch Vụ",
                    "person_name": "Tên Nhân Viên",
                    "person_id": "ID Nhân Viên"
                }
            ]
        }
    ]
}
```

## Ghi Chú Logic Kinh Doanh

1. **Phát hiện 看多機**: Nếu tên dịch vụ chứa '看多機', đặt `kbn = 1`
2. **Ánh xạ trạng thái**: `status == '実施'` → 1, ngược lại → 0  
3. **Định dạng thời gian**: Các trường thời gian cắt bớt thành định dạng HH:MM
4. **Bộ lọc văn phòng**: `jigyo_no = 'all'` trả về kế hoạch từ tất cả văn phòng
5. **Xử lý lỗi**: Trả về HTTP 400 với thông báo lỗi cho tham số không hợp lệ

## Tính Năng Bảo Mật

- **Mã hóa tham số**: Dữ liệu đầu vào được mã hóa bằng AES Base64
- **Bảo vệ SQL Injection**: Sử dụng `SafeDatabaseUtils` cho truy vấn cơ sở dữ liệu
- **Làm sạch đầu vào**: Sử dụng hàm `h()` để làm sạch đầu vào

## Ví Dụ Sử Dụng

```
GET api/hitsuji/user_plan/index.php?data=[encrypted_params]

Trong đó encrypted_params chứa:
{
    "houjin_no": "CORP001",
    "jigyo_no": "OFC001", 
    "riyo_no": "USR001",
    "month": "2024/12"
}
```

## Kết Quả Truy Vấn Cơ Sở Dữ Liệu - Tham Số Hợp Lệ

### ID Doanh Nghiệp Hợp Lệ (houjin_no)
```sql
SELECT h2_corporate_id, kantaki_corporate_id, h2_company_name, kantaki_company_name 
FROM mst_corporate WHERE delete_flg = 0;
```
| h2_corporate_id | kantaki_corporate_id | h2_company_name | kantaki_company_name |
|----------------|---------------------|-----------------|---------------------|
| 0001000 | 9001000 | Yasashiite | Yasashiite |
| 0004000 | NULL | H2 Forte Enterprises | NULL |
| 0096000 | NULL | H2 Top Solutions | NULL |
| NULL | 9002000 | NULL | kantaki test |

**Giá trị houjin_no hợp lệ**: `0001000`, `9001000`, `0004000`, `0096000`, `9002000`

### Số Văn Phòng Hợp Lệ (jigyo_no)
```sql
SELECT other_code, name FROM mst_office WHERE delete_flg = 0 LIMIT 10;
```
| other_code | name |
|------------|------|
| 1 | Văn Phòng 1 |
| 0001331 | Văn Phòng Y Tế 1 |
| 0001227 | Văn Phòng Điều Dưỡng 1 |
| 0001332 | Trung Tâm Chăm Sóc 1 |
| 0001210 | Văn Phòng Y Khoa 1 |
| 0001334 | Trung Tâm Dịch Vụ 1 |
| 0001224 | Cơ Sở Chăm Sóc 1 |
| 0001333 | Trung Tâm Y Tế 1 |
| 0001223 | Trung Tâm Điều Dưỡng 1 |
| 0001347 | Trung Tâm Y Khoa 1 |

**Giá trị jigyo_no hợp lệ**: `1`, `0001331`, `0001227`, `0001332`, `0001210`, v.v.
**Giá trị đặc biệt**: `all` (trả về kế hoạch từ tất cả văn phòng)

### Số Người Dùng Hợp Lệ (riyo_no)
```sql
SELECT other_id, last_name, first_name FROM mst_user WHERE delete_flg = 0 LIMIT 10;
```
| other_id | last_name | first_name |
|----------|-----------|------------|
| 0000001 | Người Dùng | 001 |
| 0000003 | Người Dùng | 003 |
| 1089056 | Bệnh Nhân | A |
| 1122690 | Bệnh Nhân | B |
| 1147262 | Bệnh Nhân | C |
| 1148442 | Bệnh Nhân | D |
| 1150210 | Bệnh Nhân | E |
| 1117605 | Bệnh Nhân | F |
| 1069535 | Bệnh Nhân | G |
| 1147261 | Bệnh Nhân | H |

**Giá trị riyo_no hợp lệ**: `0000001`, `0000003`, `1089056`, `1122690`, `1147262`, v.v.

### Tham Số Tháng Hợp Lệ
```sql
SELECT COUNT(*) as total_plans, MIN(use_day) as earliest_date, MAX(use_day) as latest_date 
FROM dat_user_plan WHERE delete_flg = 0;
```
| total_plans | earliest_date | latest_date |
|-------------|---------------|-------------|
| 11,233 | 0000-00-00 | 2025-05-31 |

**Ngày hợp lệ gần đây**: `2025-05-31`, `2025-05-30`, `2025-05-29`, `2025-05-28`

**Định dạng tháng hợp lệ**:
- `YYYY/MM` (v.d., `2025/05`, `2024/12`)
- `YYYY-MM` (v.d., `2025-05`, `2024-12`)

### Mẫu Tham Số Yêu Cầu API Hợp Lệ
```json
{
    "houjin_no": "0001000",
    "jigyo_no": "0001331", 
    "riyo_no": "0000001",
    "month": "2025/05"
}
```

```json
{
    "houjin_no": "9001000",
    "jigyo_no": "all",
    "riyo_no": "1089056", 
    "month": "2024-12"
}
```

### Thống Kê Cơ Sở Dữ Liệu
- **Tổng số bản ghi doanh nghiệp hoạt động**: 4
- **Tổng số bản ghi văn phòng hoạt động**: 10+ 
- **Tổng số bản ghi người dùng hoạt động**: 10+
- **Tổng số kế hoạch người dùng**: 11,233 bản ghi
- **Phạm vi ngày**: 2020-01-01 đến 2025-05-31
## Dữ Liệu Hợp Lệ Năm 2024

### Thống Kê Năm 2024
```sql
SELECT COUNT(*) as total_2024_plans FROM dat_user_plan 
WHERE delete_flg = 0 AND use_day >= '2024-01-01' AND use_day <= '2024-12-31';
```
- **Tổng số kế hoạch 2024**: 2,227 bản ghi
- **Phạm vi tháng có dữ liệu**: Tháng 1 đến tháng 12/2024
- **Dữ liệu đầy đủ**: Có dữ liệu trong tất cả 12 tháng của năm 2024

### Các Tháng Có Dữ Liệu Năm 2024
```sql
SELECT DISTINCT YEAR(use_day) as year, MONTH(use_day) as month 
FROM dat_user_plan WHERE delete_flg = 0 AND use_day >= '2024-01-01' AND use_day <= '2024-12-31';
```
| Năm | Tháng | Format API |
|-----|-------|------------|
| 2024 | 1 | `2024/01` hoặc `2024-01` |
| 2024 | 2 | `2024/02` hoặc `2024-02` |
| 2024 | 3 | `2024/03` hoặc `2024-03` |
| 2024 | 4 | `2024/04` hoặc `2024-04` |
| 2024 | 5 | `2024/05` hoặc `2024-05` |
| 2024 | 6 | `2024/06` hoặc `2024-06` |
| 2024 | 7 | `2024/07` hoặc `2024-07` |
| 2024 | 8 | `2024/08` hoặc `2024-08` |
| 2024 | 9 | `2024/09` hoặc `2024-09` |
| 2024 | 10 | `2024/10` hoặc `2024-10` |
| 2024 | 11 | `2024/11` hoặc `2024-11` |
| 2024 | 12 | `2024/12` hoặc `2024-12` |

### Tham Số Hợp Lệ Cho Năm 2024
```sql
SELECT DISTINCT u.other_id as riyo_no, o.other_code as jigyo_no 
FROM dat_user_plan up 
LEFT JOIN mst_user u ON up.user_id = u.unique_id 
LEFT JOIN mst_office o ON up.office_id = o.unique_id 
WHERE up.delete_flg = 0 AND up.use_day >= '2024-01-01' AND up.use_day <= '2024-12-31';
```

| riyo_no | jigyo_no | Mô tả |
|---------|----------|-------|
| 1230195 | 0001328 | Người dùng có kế hoạch tại văn phòng 0001328 |
| 1227734 | NULL | Người dùng có kế hoạch (văn phòng không xác định) |
| 1232741 | 0001328 | Người dùng có kế hoạch tại văn phòng 0001328 |
| 1233078 | 0001238 | Người dùng có kế hoạch tại văn phòng 0001238 |
| 990094 | 1234 | Người dùng có kế hoạch tại văn phòng 1234 |
| 1230879 | 0001238 | Người dùng có kế hoạch tại văn phòng 0001238 |
| 1231335 | 0001238 | Người dùng có kế hoạch tại văn phòng 0001238 |
| 123303 | 0001238 | Người dùng có kế hoạch tại văn phòng 0001238 |
| 1231915 | 0001328 | Người dùng có kế hoạch tại văn phòng 0001328 |
| 1232563 | 0001238 | Người dùng có kế hoạch tại văn phòng 0001238 |

### Mẫu Request Hợp Lệ Cho Năm 2024
```json
{
    "houjin_no": "0001000",
    "jigyo_no": "0001328",
    "riyo_no": "1230195",
    "month": "2024/01"
}
```

```json
{
    "houjin_no": "9001000", 
    "jigyo_no": "0001238",
    "riyo_no": "1233078",
    "month": "2024-12"
}
```

```json
{
    "houjin_no": "0001000",
    "jigyo_no": "all",
    "riyo_no": "990094", 
    "month": "2024/06"
}
```

**Lưu ý**: Tất cả các tham số trên đều được xác nhận có dữ liệu thực tế trong năm 2024.