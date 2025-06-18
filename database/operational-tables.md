# Bảng Dữ liệu Vận hành - KANTAKI-WIZ

## Tổng quan
- **Số lượng**: 18 bảng (prefix `dat_`)
- **Mục đích**: Dữ liệu y tế vận hành hàng ngày
- **Đặc điểm**: Phân biệt giữa 予定 (kế hoạch) và 実績 (thành tích thực tế)

## Phân loại Chi tiết

### 1. Dữ liệu Kế hoạch - 予定 (6 bảng)
**Mục đích**: Lập kế hoạch trước cho các hoạt động healthcare

#### `dat_root_plan` - ルート予定 (Lịch trình route)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `root_id` | VARCHAR(20) | ルートID | ID route |
| `target_day` | DATE | 対象日 | Ngày đích |
| `staff_id` | VARCHAR(20) | 対象スタッフ | Staff đích |
| `name` | VARCHAR(30) | ルート名 | Tên route |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `root_type` | VARCHAR(30) | ルート種類 | Loại route |
| `root_authority` | VARCHAR(20) | ルート権限 | Quyền route |
| `remarks` | TEXT | 備考 | Ghi chú |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Route Planning**: ルート予定 for overall route coordination
- **Daily Assignment**: 対象日, 対象スタッフ for daily staff route assignment
- **Route Classification**: ルート種類, ルート権限 for different route types và permissions
- **Location-based**: Links to 拠点ID for geographic organization

#### `dat_staff_plan` - 従業員予定 (Lịch trình nhân viên)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_id` | VARCHAR(20) | スタッフ | Staff |
| `target_day` | DATE | 対象日 | Ngày đích |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `work` | VARCHAR(30) | 作業内容 | Nội dung công việc |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `protection_flg` | TINYINT(1) | 保護フラグ | Flag bảo vệ |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `schedule_id` | VARCHAR(20) | 展開元スケジュールID | ID lịch trình nguồn triển khai |
| `memo` | TEXT | メモ | Ghi chú |
| `root_id` | VARCHAR(20) | ルートID | ID route |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Staff Scheduling**: 従業員予定 for individual staff member scheduling
- **Time Management**: 開始時刻, 終了時刻 for precise timing
- **Work Assignment**: 作業内容 for specific work content
- **Route Integration**: Links to ルートID for route coordination
- **Schedule Deployment**: 展開元スケジュールID for schedule template expansion

#### `dat_user_plan` - 利用者予定 (Lịch trình người sử dụng)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `use_day` | DATE | 利用日 | Ngày sử dụng |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `office_id` | VARCHAR(20) | 実施事業所 | Văn phòng thực hiện |
| `staff_id` | VARCHAR(20) | 対応スタッフ | Staff phụ trách |
| `service_id` | VARCHAR(20) | サービスコード | Mã dịch vụ |
| `service_name` | VARCHAR(30) | サービス内容 | Nội dung dịch vụ |
| `jihi_flg` | INT | 自費フラグ | Flag tự phí |
| `jihi_price` | INT | 自費金額 | Số tiền tự phí |
| `record` | TEXT | 保護記録 | Ghi chép bảo vệ |
| `service_item` | VARCHAR(20) | サービス項目 | Mục dịch vụ |
| `staff_license` | VARCHAR(20) | 訪問担当者資格 | Tư cách người phụ trách thăm khám |
| `visitor_num` | VARCHAR(20) | 同一訪問者数 | Số người thăm khám cùng lúc |
| `area_add` | VARCHAR(10) | 特別地域加算有無 | Có phụ cấp khu vực đặc biệt |
| `ins_station` | VARCHAR(20) | 緊急指示先ステーション | Trạm chỉ thị khẩn cấp |
| `qualification` | VARCHAR(50) | 絞り込み_資格 | Lọc_tư cách |
| `no_people` | VARCHAR(50) | 絞り込み_人数 | Lọc_số người |
| `condition1` | VARCHAR(50) | 絞り込み_条件1 | Lọc_điều kiện 1 |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `protection_flg` | TINYINT(1) | 保護フラグ | Flag bảo vệ |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `schedule_id` | VARCHAR(20) | 展開元スケジュールID | ID lịch trình nguồn triển khai |
| `kantaki` | VARCHAR(256) | 看多機記録 | Ghi chép Kantaki |
| `care_job` | VARCHAR(30) | 訪問職種 | Nghề thăm khám |
| `kantaki2` | VARCHAR(256) | 看多機記録２ | Ghi chép Kantaki 2 |
| `start_day` | DATE | 開始日(日割入力用) | Ngày bắt đầu (cho nhập theo ngày) |
| `end_day` | DATE | 終了日(日割入力用) | Ngày kết thúc (cho nhập theo ngày) |
| `corporate_id` | VARCHAR(20) | | ID công ty |
| `service_staff` | VARCHAR(20) | サービス対応者 | Người phụ trách dịch vụ |

**Đặc điểm:**
- **Patient Scheduling**: 利用者予定 for individual patient service planning
- **Service Integration**: サービスコード, サービス内容 link to service master
- **Self-pay Support**: 自費フラグ, 自費金額 for private payment services
- **Complex Qualification Filtering**: Multiple filtering fields for staff qualification matching
- **Kantaki Integration**: 看多機記録 fields for specific Kantaki service documentation
- **Daily Rate Support**: 日割入力用 fields for daily rate calculations

#### `dat_user_plan_add` - 利用者予定(加減算) (Lịch trình người sử dụng - cộng trừ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_plan_id` | VARCHAR(20) | 利用者予定ID | ID lịch trình người sử dụng |
| `add_id` | VARCHAR(20) | 加減算ID | ID cộng trừ |
| `add_name` | VARCHAR(30) | 加減算項目名称 | Tên mục cộng trừ |
| `start_day` | DATE | 期間開始日 | Ngày bắt đầu kỳ |
| `end_day` | DATE | 期間終了日 | Ngày kết thúc kỳ |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Billing Adjustments**: 加減算 for planned billing adjustments
- **Parent-Child Relationship**: Links to 利用者予定ID for main plan
- **Period-based**: 期間開始日, 期間終了日 for adjustment periods
- **Master Integration**: 加減算ID links to mst_add master table

#### `dat_user_plan_jippi` - 利用者予定(実費) (Lịch trình người sử dụng - chi phí thực tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_plan_id` | VARCHAR(20) | 利用者予定ID | ID lịch trình người sử dụng |
| `uninsure_id` | VARCHAR(20) | 保険外マスタID | ID master ngoài bảo hiểm |
| `type` | VARCHAR(10) | 種類 | Loại |
| `name` | VARCHAR(30) | 項目名称 | Tên mục |
| `price` | INT | 単価 | Đơn giá |
| `zei_type` | VARCHAR(10) | 税区分 | Phân loại thuế |
| `rate` | VARCHAR(5) | 税率 | Tỷ lệ thuế |
| `subsidy` | VARCHAR(10) | 控除対象 | Đối tượng khấu trừ |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Out-of-pocket Planning**: 実費 for planned self-pay services
- **Uninsured Services**: Links to 保険外マスタID for non-covered services
- **Tax Management**: 税区分, 税率 for proper tax calculations
- **Price Details**: 単価 with tax calculations for accurate billing

#### `dat_user_plan_service` - 利用者予定(サービス詳細) (Lịch trình người sử dụng - chi tiết dịch vụ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_plan_id` | VARCHAR(20) | 利用者予定ID | ID lịch trình người sử dụng |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `service_id` | VARCHAR(20) | サービスID | ID dịch vụ |
| `service_detail_id` | VARCHAR(20) | サービス詳細ID | ID chi tiết dịch vụ |
| `service_name` | VARCHAR(30) | サービス名称 | Tên dịch vụ |
| `type` | VARCHAR(10) | 種類 | Loại |
| `staff_id` | VARCHAR(20) | 対応スタッフ | Staff phụ trách |
| `name` | TEXT | 自動車名称 | Tên ô tô |
| `protection_flg` | TINYINT(1) | 保護フラグ | Flag bảo vệ |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Service Breakdown**: サービス詳細 for detailed service planning
- **Time-specific**: 開始時刻, 終了時刻 for service timing
- **Service Hierarchy**: サービスID → サービス詳細ID for detailed service breakdown
- **Vehicle Assignment**: 自動車名称 for vehicle planning
- **Route Integration**: ルート名 for route coordination

### **Planning Workflow Overview:**
```
1. dat_root_plan: Lập route tổng thể cho ngày
2. dat_staff_plan: Phân công nhân viên vào routes
3. dat_user_plan: Lên lịch cho từng patient
4. dat_user_plan_add: Tính toán adjustments
5. dat_user_plan_jippi: Thêm self-pay services
6. dat_user_plan_service: Chi tiết service breakdown
```

### **Data Relationships:**
```
dat_root_plan (route planning)
    ↓
dat_staff_plan (staff assignment)
    ↓
dat_user_plan (patient scheduling)
    ├── dat_user_plan_add (billing adjustments)
    ├── dat_user_plan_jippi (self-pay items)
    └── dat_user_plan_service (service details)
```

### 2. Dữ liệu Thành tích - 実績 (5 bảng)
**Mục đích**: Ghi nhận kết quả thực tế sau khi thực hiện

#### `dat_staff_record` - 従業員実績 (Thành tích nhân viên)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_plan_id` | VARCHAR(20) | 従業員予定ID | ID lịch trình nhân viên |
| `staff_id` | VARCHAR(20) | スタッフ | Staff |
| `target_day` | DATE | 対象日 | Ngày đích |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `work` | VARCHAR(30) | 作業内容 | Nội dung công việc |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `memo` | TEXT | メモ | Ghi chú |
| `root_id` | VARCHAR(20) | ルートID | ID route |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Work Recording**: 従業員実績 for actual staff work performed
- **Plan Linkage**: 従業員予定ID links back to planned schedule
- **Time Tracking**: 開始時刻, 終了時刻 for actual work hours
- **Patient Association**: 利用者ID for patient-specific work
- **Route Integration**: ルートID, ルート名 for route execution tracking

#### `dat_user_record` - 利用者実績 (Thành tích người sử dụng)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_plan_id` | VARCHAR(20) | 利用者予定ID | ID lịch trình người sử dụng |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `use_day` | DATE | 利用日 | Ngày sử dụng |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `office_id` | VARCHAR(20) | 実施事業所 | Văn phòng thực hiện |
| `staff_id` | VARCHAR(20) | 対応スタッフ | Staff phụ trách |
| `service_id` | VARCHAR(20) | サービスコード | Mã dịch vụ |
| `service_name` | VARCHAR(30) | サービス内容 | Nội dung dịch vụ |
| `jihi_flg` | INT | 自費フラグ | Flag tự phí |
| `jihi_price` | INT | 自費金額 | Số tiền tự phí |
| `record` | TEXT | 保護記録 | Ghi chép bảo vệ |
| `service_item` | VARCHAR(20) | サービス項目 | Mục dịch vụ |
| `staff_license` | VARCHAR(20) | 訪問担当者資格 | Tư cách người phụ trách thăm khám |
| `visitor_num` | VARCHAR(20) | 同一訪問者数 | Số người thăm khám cùng lúc |
| `area_add` | VARCHAR(10) | 特別地域加算有無 | Có phụ cấp khu vực đặc biệt |
| `ins_station` | VARCHAR(20) | 緊急指示先ステーション | Trạm chỉ thị khẩn cấp |
| `qualification` | VARCHAR(50) | 絞り込み_資格 | Lọc_tư cách |
| `no_people` | VARCHAR(50) | 絞り込み_人数 | Lọc_số người |
| `condition1` | VARCHAR(50) | 絞り込み_条件1 | Lọc_điều kiện 1 |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `care_job` | VARCHAR(30) | 訪問職種 | Nghề thăm khám |
| `charge` | VARCHAR(20) | 自己負担 | Tự phụ trách |
| `start_day` | DATE | 開始日(日割入力用) | Ngày bắt đầu (cho nhập theo ngày) |
| `end_day` | DATE | 終了日(日割入力用) | Ngày kết thúc (cho nhập theo ngày) |
| `corporate_id` | VARCHAR(20) | | ID công ty |
| `service_staff` | VARCHAR(20) | サービス対応者 | Người phụ trách dịch vụ |

**Đặc điểm:**
- **Service Recording**: 利用者実績 for actual patient service delivery
- **Plan Connection**: 利用者予定ID links to original plan
- **Billing Documentation**: 自費フラグ, 自費金額, 自己負担 for payment tracking
- **Care Documentation**: 保護記録 for detailed care notes
- **Additional Field**: 自己負担 (charge) not present in plan table for actual cost tracking

#### `dat_user_record_add` - 利用者実績(加減算) (Thành tích người sử dụng - cộng trừ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_record_id` | VARCHAR(20) | 利用者実績ID | ID thành tích người sử dụng |
| `add_id` | VARCHAR(20) | 加減算ID | ID cộng trừ |
| `add_name` | VARCHAR(30) | 加減算項目名称 | Tên mục cộng trừ |
| `start_day` | DATE | 期間開始日 | Ngày bắt đầu kỳ |
| `end_day` | DATE | 期間終了日 | Ngày kết thúc kỳ |
| `user_id` | VARCHAR(20) | 利用者ID(独立加減算のみ) | ID người sử dụng (chỉ cho cộng trừ độc lập) |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Actual Billing Adjustments**: 利用者実績 (not 予定) for recorded adjustments
- **Record Linkage**: 利用者実績ID links to actual service records
- **Independent Adjustments**: 独立加減算のみ field for standalone adjustments
- **Period Tracking**: 期間開始日, 期間終了日 for adjustment applicability

> ⚠️ **Note**: Table comment shows "利用者予定(加減算)" but based on field structure và naming pattern, this should be "利用者実績(加減算)" for consistency with other record tables.

#### `dat_user_record_jippi` - 利用者実績(実費) (Thành tích người sử dụng - chi phí thực tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_record_id` | VARCHAR(20) | 利用者実績ID | ID thành tích người sử dụng |
| `uninsure_id` | VARCHAR(20) | 保険外マスタID | ID master ngoài bảo hiểm |
| `type` | VARCHAR(10) | 種類 | Loại |
| `name` | VARCHAR(30) | 項目名称 | Tên mục |
| `price` | INT | 単価 | Đơn giá |
| `zei_type` | VARCHAR(10) | 税区分 | Phân loại thuế |
| `rate` | VARCHAR(5) | 税率 | Tỷ lệ thuế |
| `subsidy` | VARCHAR(10) | 控除対象 | Đối tượng khấu trừ |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Actual Out-of-pocket**: 実費 for recorded self-pay services
- **Record Connection**: 利用者実績ID links to main service record
- **Tax Documentation**: 税区分, 税率 for actual tax calculations
- **Uninsured Service**: 保険外マスタID for non-covered service tracking

#### `dat_user_record_service` - 利用者実績(サービス詳細) (Thành tích người sử dụng - chi tiết dịch vụ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_record_id` | VARCHAR(20) | 利用者実績ID | ID thành tích người sử dụng |
| `user_plan_service_id` | VARCHAR(20) | 利用者予定サービスID | ID dịch vụ lịch trình người sử dụng |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `service_id` | VARCHAR(20) | サービスID | ID dịch vụ |
| `service_detail_id` | VARCHAR(20) | サービス詳細ID | ID chi tiết dịch vụ |
| `service_name` | VARCHAR(30) | サービス名称 | Tên dịch vụ |
| `type` | VARCHAR(10) | 種類 | Loại |
| `staff_id` | VARCHAR(20) | 対応スタッフ | Staff phụ trách |
| `name` | TEXT | 自動車名称 | Tên ô tô |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Actual Service Details**: サービス詳細 for recorded detailed services
- **Dual Linkage**: Links to both 利用者実績ID và 利用者予定サービスID
- **Time Documentation**: 開始時刻, 終了時刻 for actual service timing
- **Vehicle Tracking**: 自動車名称 for actual vehicle used
- **Simplified Structure**: Fewer fields than plan service (no protection_flg, status)

### **Recording Workflow Overview:**
```
1. dat_staff_record: Ghi nhận công việc đã làm
2. dat_user_record: Record dịch vụ đã cung cấp
3. dat_user_record_add: Actual billing adjustments
4. dat_user_record_jippi: Actual self-pay services
5. dat_user_record_service: Detailed service breakdown
```

### **Plan vs Record Relationships:**
```
Planning (予定) → Recording (実績)
dat_user_plan → dat_user_record
dat_user_plan_add → dat_user_record_add
dat_user_plan_jippi → dat_user_record_jippi
dat_user_plan_service → dat_user_record_service
dat_staff_plan → dat_staff_record
```

### **Key Differences Between Plan và Record:**
- **Additional Fields in Records**: 自己負担 (charge) field in user_record
- **Linkage Fields**: Records link back to plan IDs
- **Independent Adjustments**: 独立加減算のみ in record_add
- **Simplified Service Records**: Fewer status/protection fields
- **Dual Linkage**: Service records link to both main record và plan service

### 3. Dữ liệu Lịch trình Tuần - 週間スケジュール (5 bảng)
**Mục đích**: Quản lý lịch trình theo tuần

#### `dat_staff_schedule` - 従業員予定 (Lịch trình nhân viên)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_id` | VARCHAR(20) | スタッフ | Staff |
| `week` | VARCHAR(50) | 曜日 | Thứ |
| `week_num` | VARCHAR(50) | 対象週 | Tuần đích |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `work` | VARCHAR(30) | 作業内容 | Nội dung công việc |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `memo` | TEXT | メモ | Ghi chú |
| `root_id` | VARCHAR(20) | ルートID | ID route |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Weekly Staff Scheduling**: 従業員予定 for recurring weekly staff schedules
- **Day-of-Week Planning**: 曜日 cho specific day of week patterns
- **Week Targeting**: 対象週 for specific week identification
- **Route Assignment**: ルートID, ルート名 for weekly route assignments
- **Template-based**: Used as template for daily plan deployment

#### `dat_week_schedule` - 週間スケジュール (Lịch trình tuần)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `week` | INT | 対象曜日 | Thứ đích |
| `week_num` | VARCHAR(50) | 対象週 | Tuần đích |
| `office_id` | VARCHAR(20) | 実施事業所 | Văn phòng thực hiện |
| `service_name` | VARCHAR(20) | サービス内容 | Nội dung dịch vụ |
| `service_id` | VARCHAR(20) | サービスID | ID dịch vụ |
| `jihi_flg` | INT | 自費フラグ | Flag tự phí |
| `jihi_price` | INT | 自費金額 | Số tiền tự phí |
| `root_name` | VARCHAR(30) | ルート名 | Tên route |
| `corporate_id` | VARCHAR(20) | | ID công ty |
| `service_staff` | VARCHAR(20) | サービス対応者 | Người phụ trách dịch vụ |
| `care_job` | VARCHAR(30) | 訪問職種 | Nghề thăm khám |

**Đặc điểm:**
- **Weekly Patient Schedule**: 週間スケジュール for recurring patient services
- **Day-of-Week Integer**: 対象曜日 using integer (1=Monday, 7=Sunday)
- **Service Integration**: サービスID, サービス内容 for service definitions
- **Self-pay Support**: 自費フラグ, 自費金額 for weekly billing
- **Staff Assignment**: サービス対応者 for service staff planning

#### `dat_week_schedule_add` - 週間スケジュール(加減算) (Lịch trình tuần - cộng trừ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `schedule_id` | VARCHAR(20) | スケジュールID | ID lịch trình |
| `add_id` | VARCHAR(20) | 加減算ID | ID cộng trừ |
| `add_name` | VARCHAR(30) | 加減算名称 | Tên cộng trừ |
| `start_day` | DATE | 開始日 | Ngày bắt đầu |
| `end_day` | DATE | 終了日 | Ngày kết thúc |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Weekly Billing Adjustments**: 加減算 for recurring weekly adjustments
- **Schedule Linkage**: スケジュールID links to main weekly schedule
- **Period-based**: 開始日, 終了日 for adjustment validity periods
- **Template for Daily**: Used as template for daily plan add tables

#### `dat_week_schedule_jippi` - 週間スケジュール(実費) (Lịch trình tuần - chi phí thực tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `schedule_id` | VARCHAR(20) | スケジュールID | ID lịch trình |
| `uninsure_id` | VARCHAR(20) | 保険外マスタID | ID master ngoài bảo hiểm |
| `type` | VARCHAR(10) | 種類 | Loại |
| `name` | VARCHAR(30) | 項目名称 | Tên mục |
| `price` | INT | 金額 | Số tiền |
| `zei_type` | VARCHAR(10) | 税区分 | Phân loại thuế |
| `rate` | VARCHAR(5) | 税率 | Tỷ lệ thuế |
| `subsidy` | VARCHAR(10) | 控除対象 | Đối tượng khấu trừ |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Weekly Self-pay Services**: 実費 for recurring weekly self-pay items
- **Schedule Integration**: スケジュールID links to main weekly schedule
- **Tax Management**: 税区分, 税率 for weekly tax calculations
- **Price Field**: 金額 (amount) instead of 単価 (unit price) like other jippi tables

#### `dat_week_schedule_service` - 週間スケジュール(サービス詳細) (Lịch trình tuần - chi tiết dịch vụ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `schedule_id` | VARCHAR(20) | スケジュールID | ID lịch trình |
| `service_id` | VARCHAR(20) | サービスID | ID dịch vụ |
| `service_name` | VARCHAR(30) | サービス名称 | Tên dịch vụ |
| `service_detail_id` | VARCHAR(20) | サービス詳細ID | ID chi tiết dịch vụ |
| `type` | VARCHAR(10) | 種類 | Loại |
| `start_time` | TIME | 開始時刻 | Thời gian bắt đầu |
| `end_time` | TIME | 終了時刻 | Thời gian kết thúc |
| `root_name` | VARCHAR(20) | ルート名 | Tên route |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Weekly Service Details**: サービス詳細 for recurring detailed service breakdown
- **Schedule Integration**: スケジュールID links to main weekly schedule
- **Service Hierarchy**: サービスID → サービス詳細ID for detailed service planning
- **Time-specific**: 開始時刻, 終了時刻 for service timing within weekly schedule
- **Simplified Structure**: No vehicle assignment (自動車名称) compared to plan service

### **Weekly Scheduling Workflow:**
```
1. dat_staff_schedule: Template cho weekly staff assignments
2. dat_week_schedule: Template cho weekly patient services
3. dat_week_schedule_add: Template cho weekly billing adjustments
4. dat_week_schedule_jippi: Template cho weekly self-pay services
5. dat_week_schedule_service: Template cho weekly service details
```

### **Weekly vs Daily Relationship:**
```
Weekly Templates (週間スケジュール) → Daily Deployment (展開元スケジュールID)
dat_week_schedule → dat_user_plan (via schedule_id reference)
dat_week_schedule_* → dat_user_plan_* (template expansion)
dat_staff_schedule → dat_staff_plan (weekly → daily deployment)
```

### **Weekly Planning Pattern:**
```
Monday → Tuesday → Wednesday → Thursday → Friday → Saturday → Sunday
   1         2         3          4         5         6         7
   ↓         ↓         ↓          ↓         ↓         ↓         ↓
Route 1   Route 2   Route 3    Route 4   Route 5   Weekend   Off
```

### **Key Features:**
- **Template-based**: Weekly schedules serve as templates for daily plan generation
- **Day-of-Week Support**: Both string (曜日) và integer (対象曜日) formats
- **Recurring Patterns**: Support for regular weekly service patterns
- **Deployment Tracking**: 展開元スケジュールID in daily plans tracks weekly template source
- **Simplified Structure**: Week schedules have fewer fields than daily plans

### 4. Cấu hình & Trạng thái (2 bảng)
**Mục đích**: System configuration và status tracking

#### `dat_root_config` - ルート設定 (Cấu hình route)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `name` | VARCHAR(30) | ルート名称 | Tên route |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `root_type` | VARCHAR(30) | ルート種類 | Loại route |
| `root_authority` | VARCHAR(20) | ルート権限 | Quyền route |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Route Configuration**: ルート設定 for route management settings
- **Route Types**: ルート種類 for different route classifications
- **Authority Control**: ルート権限 for route access permissions
- **Location-based**: 拠点ID for location-specific route configurations
- **Template Definition**: Base configuration for route planning

#### `dat_news_status` - お知らせ閲覧状況 (Tình trạng đọc thông báo)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_id` | VARCHAR(20) | スタッフID | ID staff |
| `news_id` | VARCHAR(20) | お知らせID | ID thông báo |
| `read_flg` | TINYINT(1) | 既読フラグ | Flag đã đọc |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Read Status Tracking**: 閲覧状況 for news/announcement read status
- **Staff-specific**: スタッフID for individual staff read tracking
- **News Integration**: お知らせID links to mst_news master table
- **Read Flag**: 既読フラグ for read/unread status management
- **Audit Trail**: Tracks when và who read each announcement

### **Configuration & Status Management:**
```
1. dat_root_config: Route configuration templates
2. dat_news_status: Communication tracking system
```

### **Integration with Master Data:**
```
dat_root_config ↔ mst_place (拠点ID)
dat_news_status ↔ mst_news (お知らせID)
dat_news_status ↔ mst_staff (スタッフID)
```

## Kiến trúc Dữ liệu Vận hành

### Data Flow Cycle
```
Planning Phase (予定):
Master Data → Route Planning → Staff Assignment → Patient Scheduling
    ↓              ↓              ↓                   ↓
mst_*     → dat_root_plan → dat_staff_plan → dat_user_plan*

Execution Phase (実績):
Service Delivery → Record Keeping → Billing
       ↓              ↓              ↓
   Real Work  → dat_*_record → Financial Processing
```

### Relationship Patterns
```
dat_user_plan (計画)
    ↓ (execution)
dat_user_record (実績)
    ↓ (analysis)
Reports & Analytics
```

## Pricing & Billing Architecture

### 加減算 (Addition/Subtraction) System
- **Base Service**: Giá cơ bản từ `mst_service`
- **Adjustments**: Tăng/giảm giá qua `*_add` tables
- **Out-of-pocket**: Chi phí tự túi qua `*_jippi` tables

### Service Detail Tracking
- **Service Catalog**: Từ `mst_service_detail`
- **Planned Services**: `*_plan_service` tables
- **Delivered Services**: `*_record_service` tables

## Japanese Healthcare Compliance

### Scheduling Requirements
- **Home Visit Frequency**: Theo quy định bảo hiểm Nhật
- **Staff Qualification**: Matching với patient needs
- **Route Optimization**: Giảm chi phí di chuyển

### Documentation Standards
- **Audit Trail**: Mọi thay đổi plan → record
- **Timestamp**: JST timezone cho tất cả records
- **User Tracking**: Staff member thực hiện

## Performance Considerations

### Read vs Write Patterns
- **Planning Tables**: Heavy writes during planning phase
- **Record Tables**: Heavy writes during service delivery
- **Schedule Tables**: Frequent reads for daily operations

### Indexing Strategy
```sql
-- Critical indexes for operational tables
CREATE INDEX idx_dat_user_plan_date ON dat_user_plan(plan_date);
CREATE INDEX idx_dat_staff_record_staff_date ON dat_staff_record(staff_id, record_date);
CREATE INDEX idx_dat_week_schedule_week ON dat_week_schedule(week_start_date);
```

### Data Archival
- **Active Period**: Current + 3 months
- **Archive Period**: 7 years (healthcare requirement)
- **Partition Strategy**: Monthly partitions

## Monitoring & Alerts

### Key Metrics
- **Plan vs Record Variance**: Sự khác biệt giữa kế hoạch và thực tế
- **Staff Utilization**: Hiệu suất sử dụng nhân viên
- **Route Efficiency**: Tối ưu hóa tuyến đường

### Alert Conditions
```sql
-- Unrecorded services (plan without record)
SELECT p.* FROM dat_user_plan p 
LEFT JOIN dat_user_record r ON p.plan_id = r.plan_id 
WHERE r.plan_id IS NULL AND p.plan_date < CURDATE();

-- Overdue records
SELECT * FROM dat_user_plan 
WHERE plan_date < CURDATE() - INTERVAL 7 DAY 
AND status != 'completed';
```

## Business Rules

### Planning Rules
1. **Staff Qualification**: Nhân viên phải có qualification phù hợp
2. **Time Slots**: Không overlap appointments
3. **Route Optimization**: Minimize travel time
4. **Insurance Coverage**: Verify coverage trước khi plan

### Recording Rules
1. **Same Day Recording**: Record trong ngày hoặc ngày hôm sau
2. **Signature Required**: Digital signature cho verification
3. **Photo Documentation**: Required cho certain services
4. **Exception Handling**: Special workflow cho emergency cases

## Error Handling & Recovery

### Common Issues
1. **Missing Records**: Plan có nhưng không có record
2. **Data Inconsistency**: Plan và record không match
3. **Timing Issues**: Late submissions
4. **Staff Conflicts**: Double booking

### Recovery Procedures
```sql
-- Find missing records
SELECT plan_id, patient_name, plan_date 
FROM dat_user_plan p
WHERE NOT EXISTS (
    SELECT 1 FROM dat_user_record r 
    WHERE r.plan_id = p.plan_id
) AND plan_date < CURDATE();
```

## Integration Points

### With Master Data
- Staff availability từ `mst_staff`
- Patient information từ `mst_user*`
- Service definitions từ `mst_service*`

### With Document System
- Service delivery → `doc_kantaki*` records
- Progress notes → `doc_progress`
- Reports → `doc_report*`

---

> 💡 **Lưu ý**: Operational data là heart của hệ thống KANTAKI-WIZ. Cần đảm bảo data integrity giữa planning và recording phases để system hoạt động chính xác.