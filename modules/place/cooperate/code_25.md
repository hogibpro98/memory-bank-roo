# 📊 Phân Tích Logic Truy Vấn Mã CSV 25 - Thông Tin Thực Tích Chăm Sóc

## 🗃️ Các Bảng Dữ Liệu Được Truy Vấn

| **Bảng** | **Mục đích** | **Dòng code** |
|----------|--------------|---------------|
| [`mst_service`](place/cooperate/function/func_account.php:118) | Lấy thông tin master service | 118-122 |
| [`mst_add`](place/cooperate/function/func_account.php:127) | Lấy thông tin master gia tăng | 127-131 |
| [`dat_user_record`](place/cooperate/function/func_account.php:488) | Lấy thực tích chính (parent records) | 488-515 |
| [`dat_user_record_add`](place/cooperate/function/func_account.php:522) | Lấy thực tích gia tăng (child records) | 522-550 |
| [`dat_user_record_add`](place/cooperate/function/func_account.php:557) | Lấy thực tích gia tăng theo khoảng thời gian | 557-563 |

## 📋 Mapping Data Dạng Bảng

| **Mã f** | **Tên Trường** | **Tên Bảng** | **Logic Truy Vấn** |
|----------|----------------|---------------|---------------------|
| **f1** | other_id | userList | ID người dùng khác từ danh sách user |
| **f2** | prtCnt | Calculated | Số thứ tự thực tích (tự động tăng) |
| **f3** | "1" hoặc "3" | Static | Phân loại: 1=dịch vụ cơ bản, 3=gia tăng |
| **f4** | service_code | mst_service | Mã dịch vụ từ master service |
| **f5** | start_time | dat_user_record | Thời gian bắt đầu (format HHMM) |
| **f6** | end_time | dat_user_record | Thời gian kết thúc (format HHMM) |
| **f7** | "1" | Static | Cờ dữ liệu (luôn = 1) |
| **f8** | service_type[13] | svcTypeMst | Phân loại dịch vụ thăm khám (mã 13) |
| **f9** | service_type[77] | svcTypeMst | Phân loại dịch vụ đa chức năng (mã 77) |
| **f10** | use_day map | dat_user_record | Ngày sử dụng (31 bit, 1=có sử dụng) |
| **f11** | calculation map | Calculated | Ngày tính toán (31 bit) |
| **f12** | charge map | dat_user_record | Ngày tự thanh toán (31 bit) |
| **f13** | receipt info | Calculated | Thông tin biên lai theo mã dịch vụ |
| **f14** | person_name | mst_user_office2 | Tên người phụ trách |
| **f15** | fax | mst_user_office2 | Số fax liên hệ |

## 🔍 Logic Truy Vấn Database Chi Tiết

### 1. **Thực Tích Chính (Parent Records)** - Dòng 488
```sql
SELECT * FROM dat_user_record 
WHERE delete_flg = 0 
  AND user_id IN (user00005760, user00005767, ...)
  AND use_day >= '2024-01-01'
  AND use_day <= '2024-01-31'
  AND status != 'キャンセル'
```

### 2. **Thực Tích Gia Tăng (Child Records)** - Dòng 522
```sql
SELECT * FROM dat_user_record_add
WHERE delete_flg = 0
  AND user_record_id IN (urec00002193, urec00002194, ...)
  AND add_id IS NOT NULL
```

### 3. **Thực Tích Gia Tăng Theo Khoảng Thời Gian** - Dòng 557
```sql
SELECT * FROM dat_user_record_add
WHERE delete_flg = 0
  AND user_id IN (user00005760, user00005767, ...)
  AND user_id IS NOT NULL
```

## 🔧 Các Logic IF Quan Trọng

### 1. **Lọc trạng thái hủy** ([dòng 492](place/cooperate/function/func_account.php:492))
```php
if ($val['status'] === 'キャンセル') {
    continue; // Bỏ qua bản ghi bị hủy
}
```

### 2. **Kiểm tra loại dịch vụ** ([dòng 496](place/cooperate/function/func_account.php:496))
```php
if (searchSvcName($val['service_name'], $type1, $type2)) {
    continue; // Chỉ lấy dịch vụ phù hợp với type1/type2
}
```

### 3. **Lọc mã dịch vụ hợp lệ** ([dòng 641](place/cooperate/function/func_account.php:641))
```php
$initNum = substr($svcCode, 0, 2);
if ($initNum != 77 && $initNum != 79 && $initNum != 13 && $initNum != 63) {
    continue; // Chỉ xử lý mã dịch vụ chăm sóc
}
```

### 4. **Xử lý thời gian đặc biệt** ([dòng 647](place/cooperate/function/func_account.php:647))
```php
if (mb_strpos($svcName, "ターミナルケア") !== false) {
    $f5 = "9999";
    $f6 = "9999"; // Thời gian đặc biệt cho chăm sóc cuối đời
}
```

### 5. **Logic ngày tính toán phức tạp** ([dòng 588](place/cooperate/function/func_account.php:588))
```php
// Dịch vụ đa chức năng và tuần tra định kỳ
if (mb_strpos($svcType, "看多機") !== false || mb_strpos($svcType, "定期巡回") !== false) {
    if ($rcdVal['start_day'] && $rcdVal['end_day']) {
        // Áp dụng cho khoảng thời gian cụ thể
        for ($i = $d1; $i <= $d2; $i++) {
            $f11Map[$i] = 1;
        }
    } else {
        // Áp dụng cho toàn bộ tháng
        for ($i = $d1; $i <= $d2; $i++) {
            $f11Map[$i] = 1;
        }
    }
} else {
    // Dịch vụ thường chỉ áp dụng ngày sử dụng
    $f11Map[$idxDay] = 1;
}
```

### 6. **Logic xử lý gia tăng đặc biệt** ([dòng 755](place/cooperate/function/func_account.php:755))
```php
$countF11 = 0;
$matched = false;
if (mb_strpos($addVal['name'],'退院時共同指導加算') !== false) {
    $countF11 += 1;
    $matched = true;
}
if (mb_strpos($addVal['name'],'看護小規模初期加算') !== false) {
    // Xử lý đặc biệt cho gia tăng ban đầu
    if ($rcdVal['start_day'] && $rcdVal['end_day']) {
        for ($i = $d1; $i <= $d2; $i++) {
            $f11Map[$i] = 1;
        }
    }
    $matched = true;
}
```

## 🔍 Câu Truy Vấn SQL Với Alias f1-f15

### **Truy Vấn Chính Cho Mã CSV 25**
```sql
SELECT
  mu.other_id AS f1,
  ROW_NUMBER() OVER (PARTITION BY dur.user_id ORDER BY dur.use_day, dur.start_time) AS f2,
  '1' AS f3,
  ms.code AS f4,
  REPLACE(TIME_FORMAT(dur.start_time, '%H%i'), ':', '') AS f5,
  REPLACE(TIME_FORMAT(dur.end_time, '%H%i'), ':', '') AS f6,
  '1' AS f7,
  CASE
    WHEN dur.service_name LIKE '%訪問看護%' THEN '1'
    WHEN dur.service_name LIKE '%定期巡回%' THEN '2'
    ELSE ''
  END AS f8,
  CASE
    WHEN dur.service_name LIKE '%看多機%通い%' THEN '1'
    WHEN dur.service_name LIKE '%看多機%宿泊%' THEN '2'
    WHEN dur.service_name LIKE '%看多機%訪問介護%' THEN '3'
    WHEN dur.service_name LIKE '%看多機%訪問看護%' THEN '4'
    ELSE ''
  END AS f9,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f10,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f11,
  CASE
    WHEN dur.charge IS NOT NULL AND dur.charge != ''
    THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
    ELSE LPAD('0', 31, '0')
  END AS f12,
  CASE
    WHEN ms.code IN ('134003', '634003', '774003') THEN DATE_FORMAT(dur.use_day, '%m%d')
    WHEN ms.code = '134004' THEN DATE_FORMAT(dur.use_day, '%d')
    WHEN ms.code IN ('137000', '776100') THEN DATE_FORMAT(dur.use_day, '%Y%m%d')
    ELSE ''
  END AS f13,
  COALESCE(muo.person_name, '') AS f14,
  COALESCE(muo.fax, '') AS f15
FROM dat_user_record dur
LEFT JOIN mst_service ms ON dur.service_id = ms.unique_id
LEFT JOIN mst_user mu ON dur.user_id = mu.unique_id
LEFT JOIN mst_user_office2 muo ON dur.user_id = muo.user_id
WHERE dur.delete_flg = 0
  AND dur.use_day >= '2024-01-01'
  AND dur.use_day <= '2024-01-31'
  AND (dur.status IS NULL OR dur.status != 'キャンセル')
  AND ms.code IS NOT NULL
  AND SUBSTRING(ms.code, 1, 2) IN ('77', '79', '13', '63')
ORDER BY dur.user_id, dur.use_day, dur.start_time;
```

### **Truy Vấn Gia Tăng (f3 = '3')**
```sql
SELECT
  mu.other_id AS f1,
  ROW_NUMBER() OVER (PARTITION BY dur.user_id ORDER BY dur.use_day, dur.start_time) AS f2,
  '3' AS f3,
  ma.code AS f4,
  REPLACE(TIME_FORMAT(dur.start_time, '%H%i'), ':', '') AS f5,
  REPLACE(TIME_FORMAT(dur.end_time, '%H%i'), ':', '') AS f6,
  '1' AS f7,
  CASE
    WHEN dur.service_name LIKE '%訪問看護%' THEN '1'
    WHEN dur.service_name LIKE '%定期巡回%' THEN '2'
    ELSE ''
  END AS f8,
  CASE
    WHEN dur.service_name LIKE '%看多機%通い%' THEN '1'
    WHEN dur.service_name LIKE '%看多機%宿泊%' THEN '2'
    WHEN dur.service_name LIKE '%看多機%訪問介護%' THEN '3'
    WHEN dur.service_name LIKE '%看多機%訪問看護%' THEN '4'
    ELSE ''
  END AS f9,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f10,
  LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0') AS f11,
  CASE
    WHEN dur.charge IS NOT NULL AND dur.charge != ''
    THEN LPAD(CONV(POW(2, DAY(dur.use_day)-1), 10, 2), 31, '0')
    ELSE LPAD('0', 31, '0')
  END AS f12,
  CASE
    WHEN ma.code IN ('134003', '634003', '774003') THEN DATE_FORMAT(dur.use_day, '%m%d')
    WHEN ma.code = '134004' THEN DATE_FORMAT(dur.use_day, '%d')
    WHEN ma.code IN ('137000', '776100') THEN DATE_FORMAT(dur.use_day, '%Y%m%d')
    ELSE ''
  END AS f13,
  COALESCE(muo.person_name, '') AS f14,
  COALESCE(muo.fax, '') AS f15
FROM dat_user_record_add ura
LEFT JOIN mst_add ma ON ura.add_id = ma.unique_id
LEFT JOIN dat_user_record dur ON ura.user_record_id = dur.unique_id
LEFT JOIN mst_user mu ON dur.user_id = mu.unique_id
LEFT JOIN mst_user_office2 muo ON dur.user_id = muo.user_id
WHERE ura.delete_flg = 0
  AND ura.add_id IS NOT NULL
  AND dur.use_day >= '2024-01-01'
  AND dur.use_day <= '2024-01-31'
  AND SUBSTRING(ma.code, 1, 2) IN ('77', '79', '13', '63')
ORDER BY dur.user_id, dur.use_day, dur.start_time;
```

## ✅ Xác Minh Database

**Các truy vấn database đã được chạy và xác minh:**

### 1. **Cấu trúc bảng `dat_user_record`**: ✓
- Có đầy đủ các trường: unique_id, user_id, service_id, service_name, use_day, start_time, end_time, status, charge, start_day, end_day

### 2. **Dữ liệu thực tế CSV 25**: ✓
```
f1      | f2 | f3 | f4     | f5   | f6   | f7 | f8 | f9 | f10                             | f11                             | f12                             | f13 | f14               | f15
1170756 | 1  | 1  | 131111 | 0900 | 0930 | 1  | 1  |    | 0000000010000000000000000000000 | 0000000010000000000000000000000 | 0000000000000000000000000000000 |     |                   |
2023072 | 1  | 1  | 771111 | 0000 | 0130 | 1  | 1  |    | 0000000001000000000000000000000 | 0000000001000000000000000000000 | 0000000000000000000000000000000 |     | ogushi            | 03-5929-3533
2023072 | 2  | 1  | 771121 | 0800 | 1030 | 1  | 1  | 4  | 0000010000000000000000000000000 | 0000010000000000000000000000000 | 0000000000000000000000000000000 |     | ogushi            | 03-5929-3533
```

### 3. **Dữ liệu gia tăng**: ✓
```
add_id      | add_code | add_name                           | user_record_id | user_id      | use_day
add00000049 | 133100   | 緊急時訪問看護加算１               | urec00002194   | user00005767 | 2024-01-02
add00000226 | 776311   | 看護小規模中山間地域等提供加算・日割 | urec00002195   | user00005803 | 2024-01-04
add00000204 | 776008   | 看護小規模医療訪問看護減算４日割     | urec00002203   | user00005770 | 2024-01-05
```

### 4. **Giải thích Map 31-bit**: ✓
- **f10** (ngày sử dụng): `0000000010000000000000000000000` = ngày 11 (bit thứ 11 = 1)
- **f11** (ngày tính toán): Tương tự f10 cho dịch vụ thường
- **f12** (ngày tự thanh toán): `0000000000000000000000000000000` = không có tự thanh toán

## 🎯 Kết Luận

Logic mã CSV 25 trong file [`func_account.php`](place/cooperate/function/func_account.php:474) hoạt động theo quy trình:

1. **Thu thập dữ liệu**: Lấy thực tích từ `dat_user_record` và gia tăng từ `dat_user_record_add`
2. **Lọc dữ liệu**: Loại bỏ bản ghi hủy, kiểm tra loại dịch vụ và mã hợp lệ
3. **Xử lý thời gian**: Tạo map 31-bit cho ngày sử dụng, tính toán, và tự thanh toán
4. **Tạo CSV**: Format dữ liệu theo chuẩn CSV 25 với 15 trường (f1-f15)

**Đặc điểm quan trọng:**
- Hỗ trợ cả dịch vụ cơ bản (f3=1) và gia tăng (f3=3)
- Xử lý đặc biệt cho dịch vụ cuối đời (ターミナルケア)
- Logic phức tạp cho dịch vụ đa chức năng (看多機) và tuần tra định kỳ (定期巡回)
- Tích hợp thông tin từ nhiều bảng master và thực tích