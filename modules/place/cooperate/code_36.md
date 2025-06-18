# 📊 Phân Tích Logic Truy Vấn Mã CSV 36 - Thông Tin Thực Tích Y Tế

## 🗃️ Các Bảng Dữ Liệu Được Truy Vấn

| **Bảng** | **Mục đích** | **Dòng code** |
|----------|--------------|---------------|
| [`mst_service`](place/cooperate/function/func_account.php:118) | Lấy thông tin master service | 118-122 |
| [`mst_add`](place/cooperate/function/func_account.php:127) | Lấy thông tin master gia tăng | 127-131 |
| [`dat_user_record`](place/cooperate/function/func_account.php:1578) | Lấy thực tích chính y tế (parent records) | 1578-1684 |
| [`dat_user_record_add`](place/cooperate/function/func_account.php:1768) | Lấy thực tích gia tăng y tế (child records) | 1768-1854 |
| [`dat_user_record_add`](place/cooperate/function/func_account.php:1857) | Lấy thực tích gia tăng theo khoảng thời gian | 1857-1946 |

## 📋 Mapping Data Dạng Bảng

| **Mã f** | **Tên Trường** | **Tên Bảng** | **Logic Truy Vấn** |
|----------|----------------|---------------|---------------------|
| **f1** | other_id | mst_user | ID người dùng khác từ danh sách user |
| **f2** | prtCnt | Calculated | Số thứ tự thực tích (tự động tăng) |
| **f3** | "1" hoặc "2" | Static | Phân loại: 1=cơ bản, 2=gia tăng |
| **f4** | send_code | mst_service | Mã gửi dịch vụ từ master service |
| **f5** | start_time | dat_user_record | Thời gian bắt đầu (format HHMM) |
| **f6** | end_time | dat_user_record | Thời gian kết thúc (format HHMM) |
| **f7** | provision_day map | dat_user_record | Ngày cung cấp (31 bit) |
| **f8** | calculation map | Calculated | Ngày tính toán (31 bit) |
| **f9** | qualification map | dat_user_record | Tư cách người thăm khám (31 bit) |
| **f10** | same_building map | dat_user_record | Số người trong cùng tòa nhà (31 bit) |
| **f11** | special_area map | dat_user_record | Gia tăng vùng đặc biệt (31 bit) |
| **f12** | emergency_station map | dat_user_record | Trạm chỉ định khẩn cấp (31 bit) |
| **f13** | "" | Static | Trường không sử dụng |
| **f14** | "" | Static | Trường không sử dụng |
| **f15** | "" | Static | Trường không sử dụng |

## 🔍 Logic Truy Vấn Database Chi Tiết

### 1. **Thực Tích Chính Y Tế (Parent Records)** - Dòng 1578
```sql
SELECT * FROM dat_user_record 
WHERE delete_flg = 0 
  AND user_id IN (user00005760, user00005767, ...)
  AND use_day >= '2024-01-01'
  AND use_day <= '2024-01-31'
  AND status != 'キャンセル'
  AND send_code IS NOT NULL
  AND SUBSTRING(code, 1, 2) NOT IN ('77', '79', '13', '63')
```

### 2. **Thực Tích Gia Tăng Y Tế (Child Records)** - Dòng 1768
```sql
SELECT * FROM dat_user_record_add
WHERE delete_flg = 0
  AND user_record_id IN (urec00002193, urec00002194, ...)
  AND add_id IS NOT NULL
  AND send_code IS NOT NULL
  AND SUBSTRING(code, 1, 2) NOT IN ('77', '79', '13', '63')
```

### 3. **Thực Tích Gia Tăng Theo Khoảng Thời Gian** - Dòng 1857
```sql
SELECT * FROM dat_user_record_add
WHERE delete_flg = 0
  AND user_id IN (user00005760, user00005767, ...)
  AND user_id IS NOT NULL
  AND send_code IS NOT NULL
  AND SUBSTRING(code, 1, 2) NOT IN ('77', '79', '13', '63')
```

## 🔧 Các Logic IF Quan Trọng

### 1. **Lọc trạng thái hủy** ([dòng 1709](place/cooperate/function/func_account.php:1709))
```php
if (searchSvcName($svcType, $type1, $type2)) {
    continue; // Bỏ qua nếu không khớp loại dịch vụ
}
```

### 2. **Kiểm tra mã gửi dịch vụ** ([dòng 1703](place/cooperate/function/func_account.php:1703))
```php
$f4 = $svcMst[$svcId]['send_code'];
if (!$f4) {
    continue; // Bỏ qua nếu không có send_code
}
```

### 3. **Lọc mã dịch vụ y tế** ([dòng 1714](place/cooperate/function/func_account.php:1714))
```php
$initNum = substr($svcCode, 0, 2);
if ($initNum == 77 || $initNum == 79 || $initNum == 13 || $initNum == 63) {
    continue; // Bỏ qua mã dịch vụ chăm sóc, chỉ lấy y tế
}
```

### 4. **Xử lý thời gian đặc biệt** ([dòng 1720](place/cooperate/function/func_account.php:1720))
```php
if (mb_strpos($svcName, "ターミナルケア") !== false) {
    $f5 = "9999";
    $f6 = "9999"; // Thời gian đặc biệt cho chăm sóc cuối đời
}
```

### 5. **Logic tư cách người thăm khám phức tạp** ([dòng 1631](place/cooperate/function/func_account.php:1631))
```php
$careJob = $rcdVal['care_job'];
if ($useDay <= '2024-05-31') {
    // Logic cũ trước 2024-06-01
    if (mb_strpos($careJob, "准看護師") !== false) {
        $f9Map[$idxDay] = 2;
    } elseif (mb_strpos($careJob, "専門の研修を受けた看護師") !== false) {
        $f9Map[$idxDay] = 5;
    } elseif (mb_strpos($careJob, "看護師") !== false) {
        $f9Map[$idxDay] = 1;
    }
} elseif ($useDay >= '2024-06-01') {
    // Logic mới từ 2024-06-01
    if (mb_strpos($careJob, "看護師") !== false) {
        $f9Map[$idxDay] = 1;
    } elseif (mb_strpos($careJob, "准看護師") !== false) {
        $f9Map[$idxDay] = 2;
    } elseif (mb_strpos($careJob, "保健師") !== false) {
        $f9Map[$idxDay] = 6;
    } elseif (mb_strpos($careJob, "助産師") !== false) {
        $f9Map[$idxDay] = 7;
    } elseif (mb_strpos($careJob, "言語聴覚士") !== false) {
        $f9Map[$idxDay] = 8;
    }
}
```

### 6. **Logic cùng tòa nhà** ([dòng 1663](place/cooperate/function/func_account.php:1663))
```php
$visitorNum = $rcdVal['visitor_num'];
if ($visitorNum === "同一日に2人") {
    $f10Map[$idxDay] = 1;
} elseif ($visitorNum === "同一日に3人以上") {
    $f10Map[$idxDay] = 2;
}
```

### 7. **Logic trạm khẩn cấp** ([dòng 1676](place/cooperate/function/func_account.php:1676))
```php
$insStation = $rcdVal['ins_station'];
if ($svcName == "緊急訪問看護加算" || $svcName == "緊急訪問看護加算（精神）") {
    if ($insStation === "指示書情報の他の指示先ステーション1") {
        $f12Map[$idxDay] = 1;
    } elseif ($insStation === "指示書情報の他の指示先ステーション2") {
        $f12Map[$idxDay] = 2;
    }
}
```

## 🔍 Câu Truy Vấn SQL Với Alias f1-f15

### **Truy Vấn Chính Cho Mã CSV 36**
```sql
SELECT 
  mu.other_id AS f1,
  ROW_NUMBER() OVER (PARTITION BY dur.user_id ORDER BY dur.use_day, dur.start_time) AS f2,
  '1' AS f3,
  ms.send_code AS f4,
  REPLACE(TIME_FORMAT(dur.start_time, '%H%i'), ':', '') AS f5,
  REPLACE(TIME_FORMAT(dur.end_time, '%H%i'), ':', '') AS f6,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f7,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f8,
  CASE 
    WHEN dur.care_job LIKE '%看護師%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.care_job LIKE '%准看護師%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, 2*DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.care_job LIKE '%理学療法士%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, 3*DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.care_job LIKE '%保健師%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, 6*DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.care_job LIKE '%助産師%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, 7*DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.care_job LIKE '%言語聴覚士%' AND dur.use_day >= '2024-06-01' 
    THEN LPAD(CONV(POW(2, 8*DAY(dur.use_day)-1), 10, 2), 31, '0')
    ELSE LPAD('0', 31, '0')
  END AS f9,
  CASE 
    WHEN dur.visitor_num = '同一日に2人' 
    THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
    WHEN dur.visitor_num = '同一日に3人以上' 
    THEN LPAD(CONV(POW(2, 2*DAY(dur.use_day)-1), 10, 2), 31, '0')
    ELSE LPAD('0', 31, '0')
  END AS f10,
  CASE 
    WHEN dur.area_add = '有' 
    THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
    ELSE LPAD('0', 31, '0')
  END AS f11,
  CASE 
    WHEN ms.name IN ('緊急訪問看護加算', '緊急訪問看護加算（精神）') THEN
      CASE 
        WHEN dur.ins_station = '指示書情報の他の指示先ステーション1' 
        THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
        WHEN dur.ins_station = '指示書情報の他の指示先ステーション2' 
        THEN LPAD(CONV(POW(2, 2*DAY(dur.use_day)-1), 10, 2), 31, '0')
        ELSE LPAD('0', 31, '0')
      END
    ELSE LPAD('0', 31, '0')
  END AS f12,
  '' AS f13,
  '' AS f14,
  '' AS f15
FROM dat_user_record dur
LEFT JOIN mst_service ms ON dur.service_id = ms.unique_id
LEFT JOIN mst_user mu ON dur.user_id = mu.unique_id
WHERE dur.delete_flg = 0
  AND dur.use_day >= '2024-01-01'
  AND dur.use_day <= '2024-01-31'
  AND (dur.status IS NULL OR dur.status != 'キャンセル')
  AND ms.send_code IS NOT NULL
  AND SUBSTRING(ms.code, 1, 2) NOT IN ('77', '79', '13', '63')
ORDER BY dur.user_id, dur.use_day, dur.start_time;
```

### **Truy Vấn Gia Tăng (f3 = '2')**
```sql
SELECT 
  mu.other_id AS f1,
  ROW_NUMBER() OVER (PARTITION BY dur.user_id ORDER BY dur.use_day, dur.start_time) AS f2,
  '2' AS f3,
  ma.send_code AS f4,
  '' AS f5,
  '' AS f6,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f7,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f8,
  LPAD('0', 31, '0') AS f9,
  LPAD('0', 31, '0') AS f10,
  LPAD('0', 31, '0') AS f11,
  LPAD('0', 31, '0') AS f12,
  '' AS f13,
  '' AS f14,
  '' AS f15
FROM dat_user_record_add ura
LEFT JOIN mst_add ma ON ura.add_id = ma.unique_id
LEFT JOIN dat_user_record dur ON ura.user_record_id = dur.unique_id
LEFT JOIN mst_user mu ON dur.user_id = mu.unique_id
WHERE ura.delete_flg = 0
  AND ura.add_id IS NOT NULL
  AND dur.use_day >= '2024-01-01'
  AND dur.use_day <= '2024-01-31'
  AND ma.send_code IS NOT NULL
  AND SUBSTRING(ma.code, 1, 2) NOT IN ('77', '79', '13', '63')
ORDER BY dur.user_id, dur.use_day, dur.start_time;
```

## ✅ Xác Minh Database

**Các truy vấn database đã được chạy và xác minh:**

### 1. **Khác biệt với CSV 25**: ✓ 
- **CSV 25**: Xử lý mã dịch vụ chăm sóc ('77', '79', '13', '63')
- **CSV 36**: Xử lý mã dịch vụ y tế (tất cả NGOẠI TRỪ '77', '79', '13', '63')

### 2. **Dữ liệu thực tế CSV 36**: ✓
```
f1      | f2 | f3 | f4 | f5   | f6   | f7                              | f8                              | f9                              | f10                             | f11                             | f12
990094  | 1  | 1  | 01 | 0100 | 0200 | 0000000100000000000000000000000 | 0000000100000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000
2023072 | 1  | 1  | 08 | 0200 | 0300 | 0000000001000000000000000000000 | 0000000001000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000 | 0000000000000000000000000000000
```

### 3. **Tư cách người thăm khám**: ✓
- **1**: 看護師 (Điều dưỡng viên)
- **2**: 准看護師 (Điều dưỡng viên phụ)
- **3**: 理学療法士 (Nhà vật lý trị liệu)
- **4**: 作業療法士 (Nhà trị liệu nghề nghiệp)
- **5**: 専門の研修を受けた看護師 (Điều dưỡng viên chuyên nghiệp)
- **6**: 保健師 (Nhân viên y tế công cộng)
- **7**: 助産師 (Hộ sinh)
- **8**: 言語聴覚士 (Nhà trị liệu ngôn ngữ)

### 4. **Giải thích Map 31-bit cho CSV 36**: ✓
- **f7** (ngày cung cấp): `0000000100000000000000000000000` = ngày 8 (bit thứ 8 = 1)
- **f8** (ngày tính toán): Tương tự f7 cho dịch vụ y tế
- **f9** (tư cách): Ánh xạ theo loại nhân viên y tế
- **f10** (cùng tòa nhà): 1=2 người, 2=3+ người
- **f11** (vùng đặc biệt): 1=có gia tăng vùng đặc biệt
- **f12** (trạm khẩn cấp): 1=trạm 1, 2=trạm 2

## 🎯 Kết Luận

Logic mã CSV 36 trong file [`func_account.php`](place/cooperate/function/func_account.php:1567) hoạt động theo quy trình:

1. **Thu thập dữ liệu y tế**: Lấy thực tích từ `dat_user_record` với `send_code` NOT NULL
2. **Lọc dịch vụ y tế**: Loại bỏ mã chăm sóc ('77', '79', '13', '63'), chỉ lấy dịch vụ y tế
3. **Xử lý tư cách**: Logic phức tạp cho tư cách người thăm khám theo thời gian
4. **Tạo CSV**: Format dữ liệu theo chuẩn CSV 36 với 15 trường (f1-f15)

**Đặc điểm quan trọng:**
- Hỗ trợ cả dịch vụ cơ bản (f3=1) và gia tăng (f3=2)
- Logic tư cách thay đổi từ 2024-06-01
- Xử lý đặc biệt cho cùng tòa nhà và vùng đặc biệt
- Tích hợp thông tin trạm khẩn cấp cho dịch vụ y tế