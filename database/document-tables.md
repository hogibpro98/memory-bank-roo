# Bảng Tài liệu - KANTAKI-WIZ

## Tổng quan
- **Số lượng**: 21 bảng (prefix `doc_`)
- **Mục đích**: Tài liệu và báo cáo y tế
- **Đặc điểm**: Theo dõi hồ sơ điều dưỡng và documentation

## Phân loại Chi tiết

### 1. Tài liệu Cốt lõi (7 bảng)
**Mục đích**: Documentation cơ bản cho healthcare workflow

#### `doc_kantaki` - 看多機記録 (Hồ sơ Kantaki - điều dưỡng đa chức năng)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `service_day` | DATE | サービス提供日 | Ngày cung cấp dịch vụ |
| `start_time` | TIME | サービス提供開始時刻 | Thời gian bắt đầu dịch vụ |
| `end_time` | TIME | サービス提供終了時刻 | Thời gian kết thúc dịch vụ |
| `important` | VARCHAR(10) | 重要フラグ | Flag quan trọng |
| `service_kind` | TEXT | サービス種類 | Loại dịch vụ |
| `body_assist` | TEXT | 身体介助 | Hỗ trợ thân thể |
| `life_support` | TEXT | 生活援助 | Hỗ trợ sinh hoạt |
| `deposit` | INT | 預り金 | Tiền gửi |
| `payment` | INT | 支払金 | Tiền thanh toán |
| `repayment` | INT | お釣り | Tiền thối |
| `medical_procedures` | TEXT | 医療処置 | Thủ thuật y tế |
| `rehabilitation` | TEXT | リハビリ | Phục hồi chức năng |
| `measures_contents` | TEXT | 処置内容 | Nội dung xử trí |
| `other` | TEXT | その他 | Khác |
| `body_height` | VARCHAR(10) | 身長 | Chiều cao |
| `body_weight` | VARCHAR(10) | 体重 | Cân nặng |
| `bmi` | VARCHAR(10) | BMI | Chỉ số BMI |
| `breakfast_main` | VARCHAR(10) | 朝食（主） | Bữa sáng (chính) |
| `breakfast_side` | VARCHAR(10) | 朝食（副） | Bữa sáng (phụ) |
| `breakfast_img` | VARCHAR(256) | 朝食イメージ | Hình ảnh bữa sáng |
| `lunch_main` | VARCHAR(10) | 昼食（主） | Bữa trưa (chính) |
| `lunch_side` | VARCHAR(10) | 昼食（副） | Bữa trưa (phụ) |
| `lunch_img` | VARCHAR(256) | 昼食イメージ | Hình ảnh bữa trưa |
| `bite` | VARCHAR(10) | おやつ | Đồ ăn vặt |
| `bite_img` | VARCHAR(256) | おやつイメージ | Hình ảnh đồ ăn vặt |
| `dinner_main` | VARCHAR(10) | 夕食（主） | Bữa tối (chính) |
| `dinner_side` | VARCHAR(10) | 夕食（副） | Bữa tối (phụ) |
| `dinner_img` | VARCHAR(256) | 夕食イメージ | Hình ảnh bữa tối |
| `drug_bb` | VARCHAR(10) | 服薬有無（朝食前） | Có uống thuốc (trước bữa sáng) |
| `drug_name_bb` | VARCHAR(50) | 服薬（朝食前） | Thuốc (trước bữa sáng) |
| `drug_ab` | VARCHAR(10) | 服薬有無（朝食後） | Có uống thuốc (sau bữa sáng) |
| `drug_name_ab` | VARCHAR(50) | 服薬（朝食後） | Thuốc (sau bữa sáng) |
| `drug_bl` | VARCHAR(10) | 服薬有無（昼食前） | Có uống thuốc (trước bữa trưa) |
| `drug_name_bl` | VARCHAR(50) | 服薬（昼食前） | Thuốc (trước bữa trưa) |
| `drug_al` | VARCHAR(10) | 服薬有無（昼食後） | Có uống thuốc (sau bữa trưa) |
| `drug_name_al` | VARCHAR(50) | 服薬（昼食後） | Thuốc (sau bữa trưa) |
| `drug_bd` | VARCHAR(10) | 服薬有無（夕食前） | Có uống thuốc (trước bữa tối) |
| `drug_name_bd` | VARCHAR(50) | 服薬（夕食前） | Thuốc (trước bữa tối) |
| `drug_ad` | VARCHAR(10) | 服薬有無（夕食後） | Có uống thuốc (sau bữa tối) |
| `drug_name_ad` | VARCHAR(50) | 服薬（夕食後） | Thuốc (sau bữa tối) |
| `drug_name_ps` | VARCHAR(50) | 服薬（就寝前） | Thuốc (trước khi ngủ) |
| `drug_name_oth` | VARCHAR(50) | 服薬（その他） | Thuốc (khác) |
| `state_care` | TEXT | 利用中の様子（介護） | Tình trạng trong khi sử dụng (chăm sóc) |
| `state_nurse` | TEXT | 利用中の様子（看護） | Tình trạng trong khi sử dụng (điều dưỡng) |
| `family_contact` | TEXT | 家族への連絡 | Liên lạc với gia đình |
| `staff_message` | TEXT | 職員申し送り事項 | Nội dung chuyển giao nhân viên |
| `nurse_needs1` | VARCHAR(20) | 看護必要度１ | Mức độ cần thiết điều dưỡng 1 |
| `nurse_needs2` | VARCHAR(20) | 看護必要度２ | Mức độ cần thiết điều dưỡng 2 |
| `nurse_needs3` | VARCHAR(20) | 看護必要度３ | Mức độ cần thiết điều dưỡng 3 |
| `nurse_needs4` | VARCHAR(20) | 看護必要度４ | Mức độ cần thiết điều dưỡng 4 |
| `nurse_needs5` | VARCHAR(20) | 看護必要度５ | Mức độ cần thiết điều dưỡng 5 |
| `nurse_needs6` | VARCHAR(20) | 看護必要度６ | Mức độ cần thiết điều dưỡng 6 |
| `nurse_needs7` | VARCHAR(20) | 看護必要度７ | Mức độ cần thiết điều dưỡng 7 |
| `nurse_needs8` | VARCHAR(20) | 看護必要度８ | Mức độ cần thiết điều dưỡng 8 |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `life_image` | VARCHAR(256) | 人体図画像 | Hình ảnh sơ đồ cơ thể |
| `image_json` | TEXT | 人体図JSONデータ | Dữ liệu JSON sơ đồ cơ thể |
| `receipt_img` | VARCHAR(256) | レシート画像 | Hình ảnh biên lai |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Comprehensive Care Recording**: 看多機記録 for multi-functional nursing documentation
- **Meal Management**: Detailed food intake tracking với images (朝食, 昼食, 夕食, おやつ)
- **Medication Management**: Complete drug administration tracking across all meal times
- **Physical Assessment**: 身長, 体重, BMI và 8-level nursing needs assessment
- **Visual Documentation**: 人体図画像, image_json for body diagram documentation
- **Financial Tracking**: 預り金, 支払金, お釣り for payment management

#### `doc_plan` - 計画書 (Kế hoạch)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `care_kb` | VARCHAR(20) | 訪問看護区分 | Phân loại chăm sóc thăm khám |
| `staff_id` | VARCHAR(20) | 担当者 | Người phụ trách |
| `report_day` | DATE | 作成日 | Ngày tạo |
| `validate_start` | DATE | 有効期間開始日 | Ngày bắt đầu hiệu lực |
| `validate_end` | DATE | 有効期間終了日 | Ngày kết thúc hiệu lực |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `target_person` | VARCHAR(200) | 宛先指定 | Chỉ định địa chỉ |
| `goal` | VARCHAR(200) | 看護リハビリテーションの目標 | Mục tiêu điều dưỡng phục hồi chức năng |
| `print_day` | DATE | 印刷日 | Ngày in |
| `material_needs` | VARCHAR(10) | 衛生材料等必要有無 | Có cần vật liệu vệ sinh |
| `dealing` | TEXT | 処置の内容 | Nội dung xử trí |
| `medical_material` | TEXT | 衛生材料 | Vật liệu y tế |
| `requirement` | TEXT | 必要量 | Lượng cần thiết |
| `visit_job` | VARCHAR(20) | 訪問職種 | Nghề thăm khám |
| `remarks` | TEXT | 備考 | Ghi chú |
| `create_staff1` | VARCHAR(20) | 作成者１ | Người tạo 1 |
| `create_job1` | VARCHAR(20) | 作成者職種１ | Nghề người tạo 1 |
| `create_staff2` | VARCHAR(20) | 作成者２ | Người tạo 2 |
| `create_job2` | VARCHAR(20) | 作成者職種２ | Nghề người tạo 2 |
| `manager` | VARCHAR(20) | 管理者 | Quản lý |
| `hospital` | VARCHAR(100) | 医療機関名称 | Tên cơ sở y tế |
| `address` | VARCHAR(100) | 所在地 | Địa chỉ |
| `doctor` | VARCHAR(50) | 主治医 | Bác sĩ điều trị |
| `tel1` | VARCHAR(20) | 電話番号１ | Số điện thoại 1 |
| `tel2` | VARCHAR(20) | 電話番号２ | Số điện thoại 2 |
| `fax` | VARCHAR(20) | FAX | FAX |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Care Planning**: 計画書 for comprehensive care plan documentation
- **Validity Period**: 有効期間開始日, 有効期間終了日 for plan effectiveness
- **Multi-creator Support**: 作成者１, 作成者２ with respective job roles
- **Medical Integration**: 医療機関名称, 主治医 for healthcare facility coordination
- **Material Planning**: 衛生材料, 必要量 for medical supply planning

#### `doc_progress` - 経過記録 (Hồ sơ tiến triển)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `staff_id` | VARCHAR(20) | 記録スタッフ | Staff ghi chép |
| `importantly` | VARCHAR(10) | 重要フラグ | Flag quan trọng |
| `record_day` | DATE | 記入日 | Ngày ghi |
| `target_date` | DATETIME | 発生日時 | Thời gian xảy ra |
| `type1` | VARCHAR(20) | 内容区分１ | Phân loại nội dung 1 |
| `type2` | VARCHAR(20) | 内容区分２ | Phân loại nội dung 2 |
| `title` | VARCHAR(200) | 件名 | Tiêu đề |
| `problem` | TEXT | 状況・課題 | Tình trạng, vấn đề |
| `direction` | TEXT | 指示事項 | Nội dung chỉ thị |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Progress Tracking**: 経過記録 for patient progress documentation
- **Event Recording**: 発生日時 for precise event timing
- **Categorization**: 内容区分１, 内容区分２ for content classification
- **Problem Documentation**: 状況・課題 for issue tracking
- **Action Planning**: 指示事項 for follow-up instructions

#### `doc_report` - 報告書 (Báo cáo)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `care_kb` | VARCHAR(20) | 訪問看護区分 | Phân loại chăm sóc thăm khám |
| `staff_id` | VARCHAR(20) | 担当者 | Người phụ trách |
| `report_day` | DATE | 作成日 | Ngày tạo |
| `validate_start` | DATE | 有効期間開始日 | Ngày bắt đầu hiệu lực |
| `validate_end` | DATE | 有効期間終了日 | Ngày kết thúc hiệu lực |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `target_person` | VARCHAR(200) | 宛先指定 | Chỉ định địa chỉ |
| `condition_progress` | TEXT | 病状の経過 | Tiến triển bệnh tình |
| `nursing_contents` | TEXT | 看護の内容 | Nội dung điều dưỡng |
| `care_situation` | TEXT | 家庭での介護状況 | Tình trạng chăm sóc tại gia |
| `material` | TEXT | 衛生材料 | Vật liệu vệ sinh |
| `material_term` | TEXT | 使用及び頻度 | Sử dụng và tần suất |
| `material_use` | TEXT | 使用量 | Lượng sử dụng |
| `material_change` | TEXT | 衛生材料の変更有無 | Có thay đổi vật liệu vệ sinh |
| `material_detail` | TEXT | 衛生材料の変更内容 | Nội dung thay đổi vật liệu vệ sinh |
| `information` | VARCHAR(200) | 情報提供 | Cung cấp thông tin |
| `information_day` | DATE | 情報提供日 | Ngày cung cấp thông tin |
| `special_report` | TEXT | 特記事項 | Ghi chú đặc biệt |
| `gaf_score` | INT | GAF点数 | Điểm GAF |
| `gaf_date` | DATE | GAF日付 | Ngày GAF |
| `create_staff1` | VARCHAR(20) | 作成者１ | Người tạo 1 |
| `create_job1` | VARCHAR(20) | 作成者職種１ | Nghề người tạo 1 |
| `create_staff2` | VARCHAR(20) | 作成者２ | Người tạo 2 |
| `create_job2` | VARCHAR(20) | 作成者職種２ | Nghề người tạo 2 |
| `manager` | VARCHAR(20) | 管理者 | Quản lý |
| `medical_institution` | VARCHAR(100) | 医療機関名称 | Tên cơ sở y tế |
| `address` | VARCHAR(100) | 所在地 | Địa chỉ |
| `doctor` | VARCHAR(50) | 主治医 | Bác sĩ điều trị |
| `tel1` | VARCHAR(20) | 電話番号１ | Số điện thoại 1 |
| `tel2` | VARCHAR(20) | 電話番号２ | Số điện thoại 2 |
| `fax` | VARCHAR(20) | FAX | FAX |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `print_day` | DATE | 印刷日 | Ngày in |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Comprehensive Reporting**: 報告書 for detailed service reports
- **Medical Progress**: 病状の経過, 看護の内容 for medical documentation
- **Home Care Assessment**: 家庭での介護状況 for family care evaluation
- **GAF Assessment**: GAF点数, GAF日付 for Global Assessment of Functioning
- **Material Management**: Detailed tracking of medical supply changes

#### `doc_instruct` - 指示書 (Sách chỉ thị)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `staff_id` | VARCHAR(20) | 担当者 | Người phụ trách |
| `direction_start` | DATE | 指示期間開始日 | Ngày bắt đầu thời gian chỉ thị |
| `direction_end` | DATE | 指示期間終了日 | Ngày kết thúc thời gian chỉ thị |
| `direction_months` | VARCHAR(10) | 指示期間月数 | Số tháng chỉ thị |
| `plan_day` | DATE | 計画書年月日 | Ngày tháng năm kế hoạch |
| `report_day` | DATE | 報告書年月日 | Ngày tháng năm báo cáo |
| `care_kb` | VARCHAR(20) | 看護区分 | Phân loại điều dưỡng |
| `direction_kb` | VARCHAR(20) | 指示区分 | Phân loại chỉ thị |
| `judgement_day` | DATE | 判定年月日 | Ngày tháng năm phán định |
| `rece_detail` | TEXT | レセ出力内容 | Nội dung xuất lệ thu |
| `postscript` | TEXT | 追記欄 | Mục ghi thêm |
| `sickness1-10` | VARCHAR(100) | 傷病名１-１０ | Tên bệnh 1-10 |
| `attached8` | VARCHAR(10) | 別表８ | Bảng phụ 8 |
| `seriously_child` | VARCHAR(10) | 重症児フラグ | Flag trẻ bệnh nặng |
| `attached8_detail` | TEXT | 別表８内容 | Nội dung bảng phụ 8 |
| `other_station1` | VARCHAR(50) | 他ステーション１ | Trạm khác 1 |
| `other_station1_address` | VARCHAR(100) | 他ステーション１所在地 | Địa chỉ trạm khác 1 |
| `other_station2` | VARCHAR(50) | 他ステーション２ | Trạm khác 2 |
| `other_station2_address` | VARCHAR(100) | 他ステーション２所在地 | Địa chỉ trạm khác 2 |
| `hospital` | VARCHAR(100) | 医療機関名称 | Tên cơ sở y tế |
| `hospital_rece` | VARCHAR(20) | レセプト出力用名称 | Tên xuất lệ thu |
| `address1` | VARCHAR(100) | 所在地 | Địa chỉ |
| `doctor` | VARCHAR(50) | 主治医 | Bác sĩ điều trị |
| `tel1` | VARCHAR(20) | 電話番号１ | Số điện thoại 1 |
| `tel2` | VARCHAR(20) | 電話番号２ | Số điện thoại 2 |
| `fax` | VARCHAR(20) | FAX | FAX |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `pdf_file` | TEXT | PDFファイルパス | Đường dẫn file PDF |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Medical Instructions**: 指示書 for doctor's instruction documentation
- **Multiple Diagnoses**: 傷病名１-１０ for up to 10 disease names
- **Period Management**: 指示期間開始日, 指示期間終了日 with month calculation
- **Insurance Integration**: レセ出力内容, レセプト出力用名称 for billing
- **Multi-station Coordination**: 他ステーション１, 他ステーション２ for facility coordination

#### `doc_plan_problem` - 計画書(課題) (Kế hoạch - vấn đề)

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| plan_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 計画書ID |
| plan_day | date | NULL | YES |  | NULL |  | 日付 |
| problem | text | utf8mb4_general_ci | YES |  | NULL |  | 問題点 |
| solution | text | utf8mb4_general_ci | YES |  | NULL |  | 解決策 |
| evaluation | text | utf8mb4_general_ci | YES |  | NULL |  | 評価 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Đặc điểm:**
- **Problem-Solution Framework**: 計画書(課題) for structured problem identification and resolution
- **Plan Integration**: 計画書ID linking problems directly to care plans
- **Solution Tracking**: 解決策 for documented intervention strategies
- **Outcome Evaluation**: 評価 for assessment of problem resolution effectiveness
- **Date Management**: 日付 for temporal tracking of problem identification and resolution

#### `doc_report_event` - 報告書(イベント) (Báo cáo - sự kiện)

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| report_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 報告書ID |
| event_day | date | NULL | YES |  | NULL |  | イベント日 |
| event_kb | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | イベント区分 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Đặc điểm:**
- **Event Documentation**: 報告書(イベント) for structured event reporting within healthcare reports
- **Report Integration**: 報告書ID linking events directly to comprehensive reports
- **Event Classification**: イベント区分 for categorizing different types of healthcare events
- **Temporal Tracking**: イベント日 for precise event date documentation
- **Minimal Structure**: Streamlined design for efficient event capture and reporting

### **Document Hierarchy:**
```
doc_plan (計画書) → Planning documents
    ├── doc_plan_problem (課題) → Problem identification & solutions
    ↓
doc_instruct (指示書) → Medical instructions
    ↓
doc_kantaki (看多機記録) → Main service records
    ↓
doc_progress (経過記録) → Progress tracking
    ↓
doc_report (報告書) → Final reports
    ├── doc_report_event (イベント) → Event documentation
```

### **Core Document Features:**
- **Comprehensive Documentation**: Each table covers specific aspect of healthcare documentation
- **Multi-creator Support**: Support for multiple staff members in document creation
- **Period Management**: Validity periods và instruction periods for proper workflow
- **Medical Integration**: Full integration với healthcare facilities và doctor information
- **Visual Documentation**: Image support for meals, receipts, và body diagrams

### 2. Tài liệu Kantaki Chuyên biệt (7 bảng)
**Mục đích**: Chi tiết các khía cạnh của dịch vụ Kantaki

| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_kantaki_drug` | 看多機記録(服薬) | Hồ sơ Kantaki (uống thuốc) |
| `doc_kantaki_vital` | 看多機記録(バイタル) | Hồ sơ Kantaki (dấu hiệu sinh tồn) |
| `doc_kantaki_excretion` | 看多機記録(排泄) | Hồ sơ Kantaki (bài tiết) |
| `doc_kantaki_water` | 看多機記録(水分摂取) | Hồ sơ Kantaki (lượng nước tiêu thụ) |
| `doc_kantaki_goods` | 看多機記録(物品使用) | Hồ sơ Kantaki (sử dụng vật phẩm) |
| `doc_kantaki_staff` | 看多機記録(スタッフ) | Hồ sơ Kantaki (nhân viên) |
| `doc_bedsore` | 褥瘡計画 | Kế hoạch loét do nằm lâu |

**Đặc điểm:**
- Ghi chép chi tiết về thuốc men và việc sử dụng thuốc
- Theo dõi các dấu hiệu sinh tồn của bệnh nhân
- Quản lý việc bài tiết và vệ sinh
- Theo dõi lượng nước tiêu thụ
- Quản lý việc sử dụng các vật phẩm y tế
- Ghi nhận hoạt động của nhân viên
- Lên kế hoạch phòng ngừa và điều trị loét do nằm lâu

**Kantaki Service Components:**
```
看多機 (Kantaki - Multi-functional Nursing):
├── 服薬 (Medication Management)
├── バイタル (Vital Signs Monitoring)
├── 排泄 (Excretion Care)
├── 水分摂取 (Fluid Intake Management)
├── 物品使用 (Equipment/Supplies Usage)
├── スタッフ (Staff Documentation)
└── 褥瘡 (Pressure Ulcer Prevention)
```

#### Cấu trúc bảng `doc_kantaki_drug`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| timing | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 服用タイミング |
| medicine | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 服用薬 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_kantaki_vital`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| counting_time | time | NULL | YES |  | NULL |  | 時刻 |
| temperature | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 体温 |
| pulse | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 脈拍 |
| blood_pressure1 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 血圧（上） |
| blood_pressure2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 血圧（下） |
| spo2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | サチュレーション |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_kantaki_excretion`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| counting_time | time | NULL | YES |  | NULL |  | 時刻 |
| urination | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 排尿有無 |
| urination_quantity | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 排尿量 |
| evacuation | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 排便有無 |
| evacuation_memo | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 排便メモ |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_kantaki_water`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| counting_time | time | NULL | YES |  | NULL |  | 時刻 |
| amount | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 摂取量 |
| method | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 摂取方法 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_kantaki_goods`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| goods_name | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 使用物品 |
| quantity | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 使用量 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_kantaki_staff`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| kantaki_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 看多機記録ID |
| staff_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 担当スタッフID |
| name | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 担当スタッフ |
| license | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 担当スタッフ資格 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

#### Cấu trúc bảng `doc_bedsore`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| user_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 利用者ID |
| staff_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 担当者 |
| plan_day | date | NULL | YES |  | NULL |  | 計画作成日 |
| bedsore_day | date | NULL | YES |  | NULL |  | 褥瘡発生日 |
| report_staff | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 記入者 |
| bedsore_now | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡有無（現在） |
| bedsore_position_now | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡箇所（現在） |
| bedsore_other_now | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡箇所その他（現在） |
| bedsore_past | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡有無（過去） |
| bedsore_position_past | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡箇所（過去） |
| bedsore_other_past | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 褥瘡箇所その他（過去） |
| independence_degree | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 障害自立度 |
| physique_conversion | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 自力体位変換 |
| seatrank_posture | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 座位姿勢の保持 |
| bone_prominence | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 病的骨突出 |
| arthrogryposis | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 関節拘縮 |
| nourishment_drop | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 栄養状態低下 |
| skin_dampness | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 皮膚湿潤 |
| swelling | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 皮膚脆弱性（浮腫） |
| skin_fragility | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 皮膚脆弱性（スキン－テア保有） |
| depth | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 深さ |
| effusion | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 滲出液 |
| size | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 大きさ |
| infection | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 炎症・感染 |
| granulation | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 肉芽形成 |
| sphacelus | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 壊死組織 |
| pocket | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | ポケット |
| pressure_bed | text | utf8mb4_general_ci | YES |  | NULL |  | 圧迫（ベッド） |
| pressure_chair | text | utf8mb4_general_ci | YES |  | NULL |  | 圧迫（椅子） |
| skincare | text | utf8mb4_general_ci | YES |  | NULL |  | スキンケア |
| nourishment_improvement | text | utf8mb4_general_ci | YES |  | NULL |  | 栄養状態改善 |
| rehabilitation | text | utf8mb4_general_ci | YES |  | NULL |  | リハビリ |
| status | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ステータス |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

### 3. Tài liệu Thăm khám Điều dưỡng (5 bảng)
**Mục đích**: Documentation cho visiting nursing services

| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_visit1` | (trống) | Thăm khám điều dưỡng 1 |
| `doc_visit1_facility` | 訪問看護記録1(関係施設) | Hồ sơ thăm khám điều dưỡng 1 (cơ sở liên quan) |
| `doc_visit1_family` | 訪問看護記録1(家族) | Hồ sơ thăm khám điều dưỡng 1 (gia đình) |
| `doc_visit2` | 訪問看護記録2 | Hồ sơ thăm khám điều dưỡng 2 |
| `doc_visit2_problem` | 訪問看護記録2(問題点) | Hồ sơ thăm khám điều dưỡng 2 (điểm vấn đề) |

**Đặc điểm của nhóm Visiting Nursing:**
- **Assessment Workflow**: `doc_visit1` → `doc_visit2` → `doc_visit2_problem` tạo thành quy trình đánh giá toàn diện
- **Multi-dimensional Documentation**: Bao gồm thông tin gia đình, cơ sở liên quan, và theo dõi vấn đề cụ thể
- **Integration Support**: Liên kết với các bảng kế hoạch chăm sóc và hệ thống báo cáo
- **Healthcare Standards**: Tuân thủ các tiêu chuẩn y tế Nhật Bản cho dịch vụ điều dưỡng thăm khám

### Cấu trúc bảng `doc_visit1`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| user_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 利用者ID |
| care_kb | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問看護区分 |
| report_day | date | NULL | YES |  | NULL |  | 作成日 |
| staff_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問スタッフ |
| visit_job | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問職種 |
| care_rank | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 要介護度 |
| first_day | date | NULL | YES |  | NULL |  | 初回訪問日 |
| start_time | time | NULL | YES |  | NULL |  | 初回訪問開始時刻 |
| end_time | time | NULL | YES |  | NULL |  | 初回訪問終了時刻 |
| main_sickness | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 主たる傷病名 |
| sickness1 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名１ |
| sickness2 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名２ |
| sickness3 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名３ |
| sickness4 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名４ |
| sickness5 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名５ |
| sickness6 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名６ |
| sickness7 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名７ |
| sickness8 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名８ |
| sickness9 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名９ |
| sickness10 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 傷病名１０ |
| medical_record | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 現病歴 |
| past_history | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 既往歴 |
| treatment | text | utf8mb4_general_ci | YES |  | NULL |  | 療養状況 |
| care | text | utf8mb4_general_ci | YES |  | NULL |  | 介護状況 |
| life | text | utf8mb4_general_ci | YES |  | NULL |  | 生活歴 |
| main_caregiver | text | utf8mb4_general_ci | YES |  | NULL |  | 主な介護者 |
| living | text | utf8mb4_general_ci | YES |  | NULL |  | 住環境 |
| purpose | text | utf8mb4_general_ci | YES |  | NULL |  | 依頼目的 |
| adl1 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL1 |
| adl2 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL2 |
| adl3 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL3 |
| adl4 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL4 |
| adl5 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL5 |
| adl6 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL6 |
| adl7 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL7 |
| adl8 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL8 |
| adl9 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL9 |
| adl10 | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ADL10 |
| notices | varchar(30) | utf8mb4_general_ci | YES |  | NULL |  | 特記事項 |
| remarks | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 備考 |
| handicap_opinion | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 障害自立度_見解 |
| handicap_rank | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 障害自立度_ランク |
| handicap_comment | text | utf8mb4_general_ci | YES |  | NULL |  | 障害自立度_コメント |
| dementia_opinion | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 認知症自立度_見解 |
| dementia_rank | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 認知症自立度_ランク |
| dementia_comment | text | utf8mb4_general_ci | YES |  | NULL |  | 認知症自立度_コメント |
| hospital | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 医療機関名称（主治医） |
| doctor | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 主治医 |
| address1 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 所在地（主治医） |
| tel1 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 電話番号１（主治医） |
| tel2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 電話番号２（主治医） |
| fax1 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | FAX（主治医） |
| office | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 事業所名称（居宅情報） |
| person | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 担当者（居宅情報） |
| address2 | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 所在地（居宅情報） |
| tel3 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 電話番号（居宅情報） |
| fax2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | FAX（居宅情報） |
| use_service | varchar(300) | utf8mb4_general_ci | YES |  | NULL |  | 健康・福祉サービス利用 |
| status | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ステータス |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

### Cấu trúc bảng `doc_visit1_facility`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| visit1_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問看護記録1ID |
| contact | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | 連絡先 |
| person | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 担当者 |
| remarks | text | utf8mb4_general_ci | YES |  | NULL |  | 備考 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

### Cấu trúc bảng `doc_visit1_family`

| Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
| unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
| delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
| create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
| create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
| update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
| update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
| visit1_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問看護記録1ID |
| name | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 氏名 |
| age | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 年齢 |
| relation | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 続柄 |
| relation_memo | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 続柄メモ |
| job | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 職業 |
| remarks | text | utf8mb4_general_ci | YES |  | NULL |  | 備考 |
| corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

### Cấu trúc bảng `doc_visit2`

Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
user_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 利用者ID |
target_plan_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 対象予定ID |
care_kb | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問看護区分 |
importantly | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | 重要フラグ |
staff1_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問スタッフ１ |
visit1_job | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問スタッフ資格１ |
staff2_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問スタッフ２ |
visit2_job | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問スタッフ資格２ |
service_day | date | NULL | YES |  | NULL |  | サービス提供日 |
start_time | time | NULL | YES |  | NULL |  | サービス提供開始時刻 |
end_time | time | NULL | YES |  | NULL |  | サービス提供終了時刻 |
next_day | date | NULL | YES |  | NULL |  | 次回サービス提供日 |
next_start | time | NULL | YES |  | NULL |  | 次回サービス提供開始時刻 |
next_end | time | NULL | YES |  | NULL |  | 次回サービス提供終了時刻 |
temperature | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 体温 |
pulse | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 脈拍 |
blood_pressure1 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 血圧（上） |
blood_pressure2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 血圧（下） |
pneusis | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 呼吸 |
pneusis_right | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 呼吸（右） |
pneusis_left | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 呼吸（左） |
spo2 | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | サチュレーション |
metabolism_kb | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 栄養・代謝区分 |
metabolism_detail | varchar(50) | utf8mb4_general_ci | YES |  | NULL |  | 栄養・代謝内容 |
metabolism_memo | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 栄養・代謝メモ |
rest_kb | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 睡眠・休息区分 |
rest_memo | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 睡眠・休息メモ |
urination_frequency | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 排尿（回数） |
urination_term | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 排尿（期間） |
evacuation_frequency | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 排便（回数） |
evacuation_term | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 排便（期間） |
bristol | int | NULL | YES |  | NULL |  | Bristol便形状スケール |
evacuation_memo | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 排尿・排便メモ |
doctor_instruction | text | utf8mb4_general_ci | YES |  | NULL |  | 医師からの指示 |
remarks | text | utf8mb4_general_ci | YES |  | NULL |  | 備考 |
next_examination | date | NULL | YES |  | NULL |  | 次回検診日 |
gaf | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | GAF尺度 |
deal_care | text | utf8mb4_general_ci | YES |  | NULL |  | 処置・ケア |
other | text | utf8mb4_general_ci | YES |  | NULL |  | その他 |
status | varchar(10) | utf8mb4_general_ci | YES |  | NULL |  | ステータス |
corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Đặc điểm:**
- **Comprehensive Visit Records**: 訪問看護記録2 for detailed visiting nursing documentation
- **Multi-staff Support**: 訪問スタッフ１, 訪問スタッフ２ with respective qualifications
- **Schedule Management**: サービス提供日, 次回サービス提供日 for current and next visit scheduling
- **Vital Signs Tracking**: 体温, 脈拍, 血圧, 呼吸, サチュレーション for complete health monitoring
- **Functional Assessment**: 栄養・代謝, 睡眠・休息, 排尿・排便 with detailed tracking
- **Medical Integration**: 医師からの指示, 次回検診日, GAF尺度 for comprehensive care coordination
- **Bristol Scale**: Bristol便形状スケール for standardized stool assessment

### Cấu trúc bảng `doc_visit2_problem`

Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
visit2_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 訪問看護記録2ID |
problem | text | utf8mb4_general_ci | YES |  | NULL |  | 問題点・記録 |
problem_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 計画書(課題)ID |
comment | text | utf8mb4_general_ci | YES |  | NULL |  | コメント |
corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Đặc điểm:**
- **Problem Documentation**: 問題点・記録 for detailed problem and issue tracking
- **Plan Integration**: 計画書(課題)ID linking problems to care plan objectives
- **Commentary System**: コメント for additional notes and observations
- **Visit2 Relationship**: 訪問看護記録2ID linking problems to specific visit records


### **Quy trình Thăm khám Điều dưỡng:**

```
doc_visit1 (Đánh giá Ban đầu)
    ↓
doc_visit2 (Hồ sơ Thăm khám Chi tiết)
    ↓
doc_visit2_problem (Xác định & Theo dõi Vấn đề)
    ↓
Tích hợp với Kế hoạch Chăm sóc & Theo dõi
```

**Mô tả chi tiết các trường dữ liệu quan trọng:**

#### **Theo dõi Dấu hiệu Sinh tồn:**
- `pneusis` - Hô hấp chung
- `pneusis_right` - Hô hấp (phổi phải)  
- `pneusis_left` - Hô hấp (phổi trái)
- `spo2` - Độ bão hòa oxy trong máu

#### **Đánh giá Dinh dưỡng & Trao đổi chất:**
- `metabolism_kb` - Phân loại tình trạng dinh dưỡng/trao đổi chất
- `metabolism_detail` - Chi tiết nội dung dinh dưỡng/trao đổi chất
- `metabolism_memo` - Ghi chú về dinh dưỡng/trao đổi chất

#### **Theo dõi Giấc ngủ & Nghỉ ngơi:**
- `rest_kb` - Phân loại tình trạng giấc ngủ/nghỉ ngơi
- `rest_memo` - Ghi chú về giấc ngủ/nghỉ ngơi

#### **Quản lý Bài tiết:**
- `urination_frequency` - Tần suất đi tiểu (số lần)
- `urination_term` - Chu kỳ đi tiểu (thời gian)
- `evacuation_frequency` - Tần suất đại tiện (số lần)
- `evacuation_term` - Chu kỳ đại tiện (thời gian)
- `bristol` - Thang đo Bristol (đánh giá hình dạng phân)
- `evacuation_memo` - Ghi chú về tiểu tiện & đại tiện

#### **Tích hợp Y tế:**
- `doctor_instruction` - Chỉ thị từ bác sĩ
- `next_examination` - Ngày khám bệnh tiếp theo
- `gaf` - Thang đo GAF (Đánh giá Chức năng Toàn diện)
- `deal_care` - Xử trí & Chăm sóc
- `other` - Các vấn đề khác
- `remarks` - Ghi chú tổng quát

**Ý nghĩa của quy trình:**
1. **Đánh giá Ban đầu** - Thu thập thông tin cơ bản về bệnh nhân
2. **Ghi chép Chi tiết** - Theo dõi các chỉ số y tế và tình trạng sức khỏe
3. **Xác định Vấn đề** - Phát hiện và ghi nhận các vấn đề cần can thiệp
4. **Tích hợp Chăm sóc** - Liên kết với kế hoạch điều trị tổng thể

