# [User List] - 利用者基本情報

**Feature ID**: USER-01
**Priority**: High
**Module**: [user/list/function/func_user.php](../../user/list/function/func_user.php)[user/list/php/user_list.php](../../user/list/php/user_list.php)
**Screen Path**: [`/user/list/index.php`](../../user/list/index.php:1)
**Analysis Date**: 2025-06-21
**Framework Version**: 2.0 - Automated Flow Analysis

## 1. Overview - Tổng quan chức năng

### Mục đích chức năng:
- **Chức năng chính**: Hiển thị danh sách người dùng (利用者一覧), cho phép tìm kiếm, lọc, kiểm tra trạng thái dữ liệu, và thao tác batch (triển khai kế hoạch).
- **Giải quyết vấn đề**: Quản lý, kiểm soát và thao tác nhanh với danh sách user, phục vụ nghiệp vụ chăm sóc và quản trị hệ thống Kantaki.
- **Workflow position**: Entry point cho các thao tác liên quan đến user (xem, lọc, chuyển sang lập kế hoạch, kiểm tra dữ liệu NG).
- **Healthcare domain**: Quản lý hồ sơ người dùng dịch vụ chăm sóc tại nhà.

### Target Users:
- [x] Admin/Quản trị viên hệ thống
- [x] Corporate Admin/Quản trị tổ chức
- [x] Staff/Nhân viên chăm sóc
- [ ] Employee/Nhân viên thực hiện
- [ ] System/API tự động

## 2. Interface Analysis Module - Phân tích giao diện tương tác

### 2.1 Interactive Elements Catalog

**Files scanned**: `index.php`, `js/user.js`, `css/`, `dialog/`

### 2.2. Mapping Data với Màn hình Hiển thị

| Tên hiển thị      | Logic code hiển thị                | Tên trường                | Tên bảng           |
|-------------------|------------------------------------|---------------------------|--------------------|
| 利用者ID          | `<?= h($dat['other_id']) ?>`        | other_id                  | mst_user           |
| 氏名              | `<?= h($dat['last_name'].$dat['first_name']) ?>` | last_name, first_name     | mst_user           |
| カナ              | `<?= h($dat['last_kana'].$dat['first_kana']) ?>` | last_kana, first_kana    | mst_user           |
| 年齢              | `$dat['age']`                      | birthday                  | mst_user           |
| 住所              | `$dat['address']`                  | prefecture, area, address1, address2, address3 | mst_user |
| 電話番号          | `$dat['tel']`                      | tel1                      | mst_user           |
| 契約状態          | `$dat['status']`                   | office1                   | mst_user_office1   |
| 要介護度          | `$dat['care_rank']`                | care_rank                 | mst_user_insure1   |
| サービス区分      | `$dat['service_type']`             | service_type              | mst_user           |
| NG状態            | `$dat['ng']`                       | (tổng hợp checkUserList)  | nhiều bảng         |

| Element Type | Element ID/Class | Event Handler | Navigation Path | Function Called |
|--------------|------------------|---------------|-----------------|-----------------|
| Button       | `.btn.save`      | submit        | POST            | 展開処理 (makePlan) |
| Button       | `.btn.add`       | click         | /user/edit/     | 新規作成         |
| Checkbox     | `.check_user`    | click         |                 | Chọn user       |
| Text Input   | `#user_name-k`   | blur/focus    |                 | Katakana convert|
| Select       | `#fil_con`       | change        |                 | Lọc trạng thái   |
| Select       | `#fil_cat`       | change        |                 | Lọc dịch vụ      |
| Select       | `#fil_all`       | change        |                 | Lọc NG          |

#### Event Handler Mapping:
- Tìm kiếm, lọc, chọn user, chuyển sang lập kế hoạch, kiểm tra NG, chuyển đổi Katakana, lưu vị trí scroll.

### 2.3 User Journey Flow Map

```mermaid
graph TD
    A[User visits user_list] --> B[Load user list]
    B --> C{User action?}
    C -->|Search| D[Filter user list]
    C -->|Select| E[Choose user(s)]
    C -->|Batch Deploy| F[Trigger makePlan]
    C -->|Create| G[Go to user edit]
    F --> H[Redirect or batch process]
    G --> I[User edit screen]
```

## 3. Logic Processing Analysis - Phân tích xử lý business logic

### 3.1 Backend Function Flow Mapping

| Frontend Action | Backend Function         | File Location                        | Business Rules         | Data Validation      |
|-----------------|-------------------------|--------------------------------------|-----------------------|---------------------|
| Load List       | `getUserList()`         | `common/php/func_get.php`            | Lọc theo place, search | XSS, SQL safe       |
| Load User Data  | `getUserAry()`          | `user/list/function/func_user.php`   | Lấy chi tiết user      | Chuẩn hóa, an toàn  |
| Check NG        | `checkUserList()`       | `user/list/function/func_user.php`   | Kiểm tra thiếu dữ liệu | Logic nghiệp vụ     |
| Deploy Plan     | `makePlan()`            | `func_set.php` (gián tiếp)           | Triển khai kế hoạch    | Kiểm tra quyền      |

#### Function Call Trace Analysis:
- `getUserList` → lấy danh sách userId theo place, search
- `getUserAry` → lấy chi tiết user từ nhiều bảng liên quan
- `checkUserList` → kiểm tra dữ liệu thiếu/NG
- `makePlan` → triển khai kế hoạch cho user

### 3.2 Data Flow Pattern Analysis

| Input Source | Processing Function | Validation Rules | Output Destination |
|--------------|--------------------|------------------|--------------------|
| Form Search  | `getUserList`      | XSS, SQL safe    | Array userId       |
| UserId List  | `getUserAry`       | Chuẩn hóa        | Array user detail  |
| User Detail  | `checkUserList`    | Logic nghiệp vụ  | NG message         |
| Batch Deploy | `makePlan`         | Quyền, logic     | Kết quả triển khai |

### 3.3 Service Method Documentation

| Service Method | Purpose                | Parameters         | Return Value      | Database Tables           |
|---------------|------------------------|--------------------|-------------------|---------------------------|
| getUserList   | Lấy danh sách user     | $placeId, $search  | Array userId      | mst_user, mst_user_office1|
| getUserAry    | Lấy chi tiết user      | $userId, $orderBy  | Array user detail | mst_user, mst_user_*      |
| checkUserList | Kiểm tra dữ liệu NG    | $userInfo          | Array message     | (logic tổng hợp)          |
| makePlan      | Triển khai kế hoạch    | $userAry, ...      | Kết quả           | dat_user_plan, ...        |

## 4. Database Integration Mapping - Tích hợp cơ sở dữ liệu

### 4.1 Complete Data Flow Pipeline

| User Interaction | Business Logic | Database Operation | Tables Affected      | Query Type |
|------------------|---------------|-------------------|----------------------|------------|
| Search/filter    | getUserList   | SELECT            | mst_user, mst_user_office1 | SELECT     |
| Load detail      | getUserAry    | SELECT            | mst_user, mst_user_* | SELECT     |
| Check NG         | checkUserList | (no query)        | (logic tổng hợp)     | -          |
| Deploy plan      | makePlan      | INSERT/UPDATE     | dat_user_plan, ...   | INSERT/UPDATE |

#### Docker Database Query Execution (chuẩn dự án):
```bash
docker exec db mysql -u root -prootpassword kantaki_dev -e "SET NAMES utf8mb4; SELECT * FROM mst_user WHERE delete_flg=0 LIMIT 10;" --default-character-set=utf8mb4 -t
```

#### SQL tổng hợp (tham khảo):
```sql
SELECT
  u.unique_id,
  u.last_name,
  u.first_name,
  u.last_kana,
  u.first_kana,
  u.birthday,
  u.prefecture,
  u.area,
  u.address1,
  u.address2,
  u.address3,
  u.tel1,
  u.service_type,
  o1.start_day AS office1_start_day,
  o1.end_day AS office1_end_day,
  o2.office_code,
  o2.office_name,
  o2.tel AS office2_tel,
  p.method AS pay_method,
  p.bank_type,
  p.bank_code,
  p.bank_name,
  i1.insure_no,
  i1.start_day1,
  i1.end_day1,
  i1.care_rank,
  i2.start_day AS insure2_start_day,
  i2.rate AS insure2_rate,
  h.name AS hospital_name,
  h.doctor AS hospital_doctor,
  e.name AS emergency_name,
  e.tel1 AS emergency_tel,
  per.name AS person_name,
  per.relation AS person_relation
FROM mst_user u
LEFT JOIN (
  SELECT * FROM mst_user_office1 WHERE delete_flg=0 AND start_day <= CURDATE() AND (end_day IS NULL OR end_day > CURDATE())
) o1 ON o1.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_office2 WHERE delete_flg=0
) o2 ON o2.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_pay WHERE delete_flg=0
) p ON p.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_insure1 WHERE delete_flg=0 AND start_day1 <= CURDATE() AND end_day1 > CURDATE()
) i1 ON i1.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_insure2 WHERE delete_flg=0
) i2 ON i2.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_hospital WHERE delete_flg=0
) h ON h.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_emergency WHERE delete_flg=0
) e ON e.user_id = u.unique_id
LEFT JOIN (
  SELECT * FROM mst_user_person WHERE delete_flg=0
) per ON per.user_id = u.unique_id
WHERE u.delete_flg=0
-- Thêm điều kiện tìm kiếm nếu có, ví dụ:
-- AND (u.last_kana LIKE '%キ%' OR u.first_kana LIKE '%キ%')
ORDER BY u.unique_id ASC
LIMIT 200
```

### 4.3 Complete Database Schema Mapping

| Table Name           | Primary Key | Foreign Keys         | Related Operations | CRUD Functions |
|---------------------|-------------|----------------------|-------------------|---------------|
| mst_user            | unique_id   | corporate_id         | Main user         | select, update|
| mst_user_office1    | unique_id   | user_id → mst_user   | Office contract   | select        |
| mst_user_office2    | unique_id   | user_id → mst_user   | Support office    | select        |
| mst_user_pay        | unique_id   | user_id → mst_user   | Payment           | select        |
| mst_user_insure1    | unique_id   | user_id → mst_user   | Care insurance    | select        |
| mst_user_insure2    | unique_id   | user_id → mst_user   | Benefit info      | select        |
| mst_user_hospital   | unique_id   | user_id → mst_user   | Hospital history  | select        |
| mst_user_emergency  | unique_id   | user_id → mst_user   | Emergency contact | select        |
| mst_user_person     | unique_id   | user_id → mst_user   | Key person        | select        |

## 5. Technical Implementation Details

### 5.1 Files Structure with Analysis Integration:

```
/user/list/
├── index.php                  # Main entry point + Interface Analysis
├── js/user.js                 # Client-side logic + Event mapping
├── php/
│   └── user_list.php          # Server-side logic + Business rules
├── function/
│   └── func_user.php          # User data logic
└── ...
```

## Change Log

| Date       | Version | Changes                        | Author            |
|------------|---------|--------------------------------|-------------------|
| 2025-06-21 | 2.0     | Automated analysis for user_list| AI Documentation  |
