# Bảng Dữ liệu Master - KANTAKI-WIZ

## Tổng quan
- **Số lượng**: 36+ bảng (prefix `mst_`)
- **Mục đích**: Dữ liệu tham chiếu cốt lõi và cấu hình
- **Đặc điểm**: Chứa tất cả master data cho hệ thống healthcare

## Cấu trúc Bảng Chi tiết với Comment Tiếng Nhật

### 1. Entity Cốt lõi

#### `mst_user` - 利用者マスタ (Master người sử dụng dịch vụ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `other_id` | VARCHAR(7) | 利用者ID | ID người sử dụng |
| `last_name` | VARCHAR(30) | 漢字氏名(苗字) | Họ (Kanji) |
| `first_name` | VARCHAR(30) | 漢字氏名(名前) | Tên (Kanji) |
| `last_kana` | VARCHAR(30) | カナ氏名(苗字) | Họ (Katakana) |
| `first_kana` | VARCHAR(30) | カナ氏名(名前) | Tên (Katakana) |
| `sex` | VARCHAR(10) | 性別 | Giới tính |
| `birthday` | DATE | 生年月日 | Ngày sinh |
| `prefecture` | VARCHAR(10) | 住所(都道府県) | Địa chỉ (Tỉnh/Thành phố) |
| `area` | VARCHAR(30) | 住所(市区町村) | Địa chỉ (Quận/Huyện) |
| `address1` | VARCHAR(100) | 住所(町域) | Địa chỉ (Khu vực) |
| `address2` | VARCHAR(100) | 住所(番地) | Địa chỉ (Số nhà) |
| `address3` | VARCHAR(100) | 住所(建物) | Địa chỉ (Tòa nhà) |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `tel1` | VARCHAR(20) | 電話番号1 | Số điện thoại 1 |
| `tel2` | VARCHAR(20) | 電話番号2 | Số điện thoại 2 |
| `fax` | VARCHAR(20) | FAX | Số fax |
| `mail` | VARCHAR(100) | メールアドレス | Địa chỉ email |
| `household_type` | VARCHAR(10) | 世帯区分 | Phân loại hộ gia đình |
| `household_memo` | VARCHAR(256) | 世帯区分メモ | Ghi chú hộ gia đình |
| `service_type` | VARCHAR(20) | サービス利用区分 | Phân loại sử dụng dịch vụ |
| `bath_type` | VARCHAR(10) | 入浴区分 | Phân loại tắm |
| `bath_memo` | VARCHAR(256) | 入浴メモ | Ghi chú tắm |
| `excretion_type` | VARCHAR(10) | 排泄区分 | Phân loại bài tiết |
| `excretion_memo` | VARCHAR(256) | 排泄メモ | Ghi chú bài tiết |
| `meal_type` | VARCHAR(10) | 食事区分 | Phân loại ăn uống |
| `meal_memo` | VARCHAR(256) | 食事メモ | Ghi chú ăn uống |
| `remarks` | VARCHAR(256) | メモ | Ghi chú |
| `contract_date` | DATE | 契約日 | Ngày ký hợp đồng |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Patient ID**: `other_id` - ID người bệnh theo chuẩn 7 ký tự
- **Japanese Names**: Hỗ trợ cả Kanji và Katakana
- **Care Classification**: Bath/Excretion/Meal types cho healthcare planning
- **Multi-level Address**: Prefecture → Area → Address1-3 theo chuẩn Nhật

### 1.1 User Extension Tables

#### `mst_user_drug` - 利用者マスタ(薬情) (Master người sử dụng - thông tin thuốc)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 処方開始日 | Ngày bắt đầu kê đơn |
| `end_day` | DATE | 処方終了日 | Ngày kết thúc kê đơn |
| `drug_name` | TEXT | 医薬品名 | Tên dược phẩm |
| `drug_usage` | VARCHAR(30) | 用法 | Cách dùng |
| `dose` | TEXT | 量 | Liều lượng |
| `effect` | TEXT | 効果 | Hiệu quả |
| `side_effect` | TEXT | 副作用 | Tác dụng phụ |
| `remarks` | TEXT | 備考 | Ghi chú |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Drug Management**: Comprehensive medication tracking (医薬品名, 用法, 量)
- **Time-bound Prescriptions**: 処方開始日/処方終了日 for prescription periods
- **Medical Safety**: 効果, 副作用 tracking for patient safety
- **User Extension**: Links to mst_user via user_id

#### `mst_user_emergency` - 利用者マスタ(緊急連絡先) (Master người sử dụng - liên lạc khẩn cấp)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `name` | VARCHAR(30) | 漢字氏名 | Tên Kanji |
| `kana` | VARCHAR(30) | カナ氏名 | Tên Katakana |
| `together` | VARCHAR(10) | 同居区分 | Phân loại cùng sống |
| `relation_type` | VARCHAR(10) | 続柄 | Mối quan hệ |
| `relation_memo` | VARCHAR(256) | 続柄メモ | Ghi chú mối quan hệ |
| `mail` | VARCHAR(100) | メールアドレス | Địa chỉ email |
| `tel1` | VARCHAR(30) | 電話番号1 | Số điện thoại 1 |
| `tel2` | VARCHAR(30) | 電話番号2 | Số điện thoại 2 |
| `fax` | VARCHAR(20) | FAX | Số fax |
| `post` | VARCHAR(20) | 郵便番号 | Mã bưu chính |
| `prefecture` | VARCHAR(10) | 住所(都道府県) | Địa chỉ (Tỉnh/Thành phố) |
| `area` | VARCHAR(30) | 住所(市区町村) | Địa chỉ (Quận/Huyện) |
| `address1` | VARCHAR(100) | 住所(町域) | Địa chỉ (Khu vực) |
| `address2` | VARCHAR(100) | 住所(番地以降) | Địa chỉ (Từ số nhà trở đi) |
| `remarks` | TEXT | 備考 | Ghi chú |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Emergency Contacts**: Multiple contacts với full address information
- **Relationship Tracking**: 続柄, 同居区分 for relationship types và living arrangements
- **Multi-channel Communication**: tel1/tel2, mail, fax for emergency reach
- **Index on user_id**: Optimized for user-based queries

#### `mst_user_family` - 利用者マスタ(家族構成) (Master người sử dụng - cấu trúc gia đình)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `type` | VARCHAR(30) | 反映設定 | Cài đặt phản ánh |
| `name` | VARCHAR(30) | 氏名 | Họ tên |
| `relation_type` | VARCHAR(10) | 続柄 | Mối quan hệ |
| `relation_memo` | VARCHAR(256) | 続柄メモ | Ghi chú mối quan hệ |
| `business` | VARCHAR(30) | 職業 | Nghề nghiệp |
| `remarks` | TEXT | 備考 | Ghi chú |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Family Structure**: Complete family member tracking (氏名, 続柄, 職業)
- **Configuration Settings**: 反映設定 for reflection/visibility settings
- **Occupation Tracking**: 職業 for family member occupations
- **Simpler than Emergency**: Focus on family relationships vs emergency contact details

#### `mst_user_gaf` - 利用者マスタ(GAF) (Master người sử dụng - GAF)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `target_day` | DATE | 対象日 | Ngày đích |
| `score` | INT | 点数 | Điểm số |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **GAF Assessment**: Global Assessment of Functioning score tracking
- **Time-series Data**: 対象日, 点数 for tracking functional changes over time
- **Mental Health**: Standard psychiatric assessment tool
- **Simple Structure**: Minimal fields focused on assessment data

#### `mst_user_hospital` - 利用者マスタ(医療機関履歴) (Master người sử dụng - lịch sử cơ quan y tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `name` | VARCHAR(30) | 医療機関名称 | Tên cơ quan y tế |
| `disp_name` | VARCHAR(30) | レセプト出力用名称 | Tên cho xuất receipt |
| `select1` | INT | 指示書発行有無 | Có phát hành chỉ thị hay không |
| `type1` | VARCHAR(10) | 病院在宅区分 | Phân loại bệnh viện tại nhà |
| `doctor` | VARCHAR(30) | 主治医 | Bác sĩ chính |
| `address` | VARCHAR(255) | 所在地 | Địa điểm |
| `tel1` | VARCHAR(30) | 電話番号1 | Số điện thoại 1 |
| `tel2` | VARCHAR(30) | 電話番号2 | Số điện thoại 2 |
| `fax` | VARCHAR(30) | FAX | Số fax |
| `medical_facility_code` | VARCHAR(50) | 医療機関コード | Mã cơ quan y tế |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Medical History**: Complete medical facility history tracking
- **Receipt Integration**: レセプト出力用名称 for insurance claim processing
- **Doctor Tracking**: 主治医 for primary physician information
- **Facility Codes**: 医療機関コード for official medical facility identification
- **Time-bound Relationships**: Effective periods for facility associations

#### `mst_user_image` - 利用者マスタ(画像) (Master người sử dụng - hình ảnh)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `entry_day` | DATE | 登録日 | Ngày đăng ký |
| `month` | VARCHAR(10) | 該当月 | Tháng tương ứng |
| `tag` | VARCHAR(30) | タグ種類 | Loại tag |
| `memo` | TEXT | メモ | Ghi chú |
| `image` | VARCHAR(256) | 画像ディレクトリ | Thư mục hình ảnh |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Image Management**: 画像ディレクトリ for storing image file paths
- **Monthly Organization**: 該当月 for organizing images by month
- **Tagging System**: タグ種類 for categorizing images
- **Metadata Tracking**: 登録日, メモ for image documentation

#### `mst_user_insure1` - 利用者マスタ(介護保険証履歴) (Master người sử dụng - lịch sử thẻ bảo hiểm chăm sóc)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) IDX | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `start_day1` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day1` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `start_day2` | DATE | 区部支給開始日 | Ngày bắt đầu cung cấp khu vực |
| `end_day2` | DATE | 区部支給終了日 | Ngày kết thúc cung cấp khu vực |
| `insure_no` | VARCHAR(8) | 保険者番号 | Số người bảo hiểm |
| `insured_mark` | VARCHAR(10) | 被保険者記号 | Ký hiệu người được bảo hiểm |
| `insured_no` | VARCHAR(10) | 被保険者番号 | Số người được bảo hiểm |
| `insured_branch_no` | VARCHAR(10) | 被保険者枝番 | Số nhánh người được bảo hiểm |
| `care_rank` | VARCHAR(20) | 要介護度 | Mức độ cần chăm sóc |
| `certif_day` | DATE | 認定日 | Ngày chứng nhận |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Care Insurance History**: Complete long-term care insurance history
- **Dual Period Tracking**: start_day1/end_day1 + start_day2/end_day2 for different coverage periods
- **Insurance Identifiers**: 保険者番号, 被保険者記号/番号 for official identification
- **Care Level Tracking**: 要介護度 for care requirement assessment
- **Index on delete_flg**: Optimized for active record queries

#### `mst_user_insure2` - 利用者マスタ(給付情報) (Master người sử dụng - thông tin trợ cấp)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `rate` | INT | 給付率 | Tỷ lệ trợ cấp |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Benefit Information**: 給付率 for subsidy/benefit rate tracking
- **Time-bound**: 有効開始日/有効終了日 for benefit periods
- **Simple Structure**: Focus on benefit rate management
- **Index on user_id**: Optimized for user-based queries

#### `mst_user_insure3` - 利用者マスタ(医療保険証) (Master người sử dụng - thẻ bảo hiểm y tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `select1` | INT | 経過措置 | Biện pháp chuyển tiếp |
| `select2` | INT | 退職者制度区分 | Phân loại chế độ người nghỉ hưu |
| `type1` | VARCHAR(10) | 保健区分 | Phân loại bảo hiểm y tế |
| `type2` | VARCHAR(10) | 本人区分 | Phân loại bản thân |
| `type3` | VARCHAR(10) | 所得区分 | Phân loại thu nhập |
| `number1` | VARCHAR(8) | 保険者番号 | Số người bảo hiểm |
| `number2` | VARCHAR(2) | 法別番号 | Số pháp biệt |
| `number3` | VARCHAR(15) | 記号 | Ký hiệu |
| `number4` | VARCHAR(15) | 番号 | Số |
| `number5` | VARCHAR(10) | 枝番 | Số nhánh |
| `name` | VARCHAR(30) | 保健名称 | Tên bảo hiểm y tế |
| `type4` | VARCHAR(10) | 職務上の事由 | Lý do liên quan đến công việc |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Medical Insurance**: Complete medical insurance card information
- **Complex Numbering**: number1-5 for comprehensive insurance identification
- **Classification System**: type1-4, select1-2 for detailed categorization
- **Work-related Coverage**: 職務上の事由 for job-related medical coverage

#### `mst_user_insure4` - 利用者マスタ(公費) (Master người sử dụng - chi phí công)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `number1` | VARCHAR(10) | 法別番号 | Số pháp biệt |
| `name` | VARCHAR(30) | 公費名称 | Tên chi phí công |
| `number2` | VARCHAR(10) | 負担者番号 | Số người chịu trách nhiệm |
| `number3` | VARCHAR(10) | 受給者番号 | Số người thụ hưởng |
| `upper_limit` | INT | 上限額 | Số tiền giới hạn trên |
| `rate` | INT | 負担割合 | Tỷ lệ gánh chịu |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Public Cost**: Government-funded healthcare cost management
- **Cost Control**: 上限額, 負担割合 for cost limits và burden ratios
- **Beneficiary Tracking**: 負担者番号, 受給者番号 for payer/recipient identification
- **Official Coverage**: 公費名称 for government program names

#### `mst_user_introduct` - 利用者マスタ(紹介履歴) (Master người sử dụng - lịch sử giới thiệu)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |

**Referral Pattern (in1_*, in2_*, in3_):**
- `in1_name` - 紹介1機関名 (Referral institution 1)
- `in1_company` - 紹介1法人名 (Referral corporation 1)
- `in1_post` - 紹介1郵便番号 (Postal code 1)
- `in1_address` - 紹介1所在地 (Address 1)
- `in1_tel/fax/mail` - Contact information
- `in1_start` - 紹介1サービス開始日 (Service start date)
- `in1_person1-3` - 紹介1担当者 (Contact persons 1-3)

**Outflow Tracking:**
- `out_day` - 流出日 (Outflow date)
- `out_name` - 流出機関名 (Outflow institution)
- `out_person` - 流出担当者 (Outflow contact)
- `out_type` - 流出理由 (Outflow reason)
- `out_memo` - 流出理由メモ (Outflow reason memo)

**Đặc điểm:**
- **Referral Network**: Complete referral history tracking (紹介1-3)
- **Multi-contact**: Up to 3 contact persons per referral
- **Outflow Tracking**: 流出 for patient transfer/discharge tracking
- **Large Structure**: 38 fields total for comprehensive referral management

#### `mst_user_medical` - 利用者マスタ(医療情報) (Master người sử dụng - thông tin y tế)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `drug_name` | VARCHAR(30) | 薬局名称 | Tên nhà thuốc |
| `drug_person` | VARCHAR(30) | 薬局担当者 | Người phụ trách nhà thuốc |
| `drug_tel` | VARCHAR(20) | 薬局電話番号 | Số điện thoại nhà thuốc |
| `drug_fax` | VARCHAR(20) | 薬局FAX | FAX nhà thuốc |
| `dental_name` | VARCHAR(30) | 訪問歯科名称 | Tên nha khoa thăm khám |
| `dental_person` | VARCHAR(30) | 訪問歯科担当者 | Người phụ trách nha khoa thăm khám |
| `dental_tel` | VARCHAR(20) | 訪問歯科電話番号 | Số điện thoại nha khoa thăm khám |
| `dental_fax` | VARCHAR(20) | 訪問歯科FAX | FAX nha khoa thăm khám |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Medical Providers**: Pharmacy và dental care provider information
- **Contact Management**: Complete contact details cho medical providers
- **Visiting Services**: 訪問歯科 for home dental care services
- **Simple Structure**: Focus on essential medical provider contacts

#### `mst_user_office1` - 利用者マスタ(契約事業所履歴) (Master người sử dụng - lịch sử văn phòng hợp đồng)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `office_id` | VARCHAR(20) | 事業所ID | ID văn phòng |
| `office_name` | VARCHAR(30) | 事業所名称 | Tên văn phòng |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Contract Office History**: 契約事業所履歴 for contract office tracking
- **Time-bound Contracts**: Effective periods for office assignments
- **Office Linkage**: Links to mst_office via office_id
- **Simple Structure**: Focus on office-user contract relationships

#### `mst_user_office2` - 利用者マスタ(居宅支援事業所履歴) (Master người sử dụng - lịch sử văn phòng hỗ trợ tại nhà)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 開始日 | Ngày bắt đầu |
| `end_day` | DATE | 終了日 | Ngày kết thúc |
| `office_code` | VARCHAR(10) | 事業所番号 | Số văn phòng |
| `office_name` | VARCHAR(30) | 事業所名称 | Tên văn phòng |
| `address` | VARCHAR(256) | 所在地 | Địa điểm |
| `tel` | VARCHAR(20) | 電話番号 | Số điện thoại |
| `fax` | VARCHAR(20) | FAX | FAX |
| `found_day` | DATE | 届出年月日 | Ngày báo cáo |
| `person_name` | VARCHAR(30) | 担当者(漢字) | Người phụ trách (Kanji) |
| `person_kana` | VARCHAR(30) | 担当者(カナ) | Người phụ trách (Katakana) |
| `plan_type` | VARCHAR(30) | 計画作成区分 | Phân loại tạo kế hoạch |
| `cancel_type` | VARCHAR(30) | 中止理由 | Lý do dừng |
| `cancel_memo` | TEXT | 中止理由備考 | Ghi chú lý do dừng |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Home Support Office**: 居宅支援事業所 for home care support offices
- **Care Planning**: 計画作成区分 for care plan creation types
- **Cancellation Tracking**: 中止理由, 中止理由備考 for service termination
- **Official Registration**: 届出年月日 for official notification dates

#### `mst_user_pay` - 利用者マスタ(支払方法) (Master người sử dụng - phương thức thanh toán)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `method` | VARCHAR(10) | 支払方法 | Phương thức thanh toán |
| `bank_type` | VARCHAR(10) | 金融機関区分 | Phân loại cơ quan tài chính |
| `bank_code` | VARCHAR(5) | 金融機関コード | Mã cơ quan tài chính |
| `bank_name` | VARCHAR(20) | 金融機関名称 | Tên cơ quan tài chính |
| `branch_code` | VARCHAR(10) | 支店コード | Mã chi nhánh |
| `branch_name` | VARCHAR(30) | 支店名 | Tên chi nhánh |
| `deposit_type` | VARCHAR(10) | 預金種別 | Loại tiền gửi |
| `deposit_code` | VARCHAR(10) | 口座番号 | Số tài khoản |
| `deposit_name` | VARCHAR(30) | 預金者名 | Tên người gửi tiền |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Payment Methods**: 支払方法 for various payment options
- **Banking Details**: Complete bank account information
- **Japanese Banking**: Standard Japanese banking structure (金融機関, 支店)
- **Account Management**: 預金種別, 口座番号, 預金者名 for account details

#### `mst_user_person` - 利用者マスタ(キーパーソン) (Master người sử dụng - người liên hệ chính)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) IDX | 利用者ID | ID người sử dụng |
| `name` | VARCHAR(30) | 漢字氏名 | Tên Kanji |
| `kana` | VARCHAR(30) | カナ氏名 | Tên Katakana |
| `relation` | VARCHAR(10) | 関係性 | Mối quan hệ |
| `tel` | VARCHAR(20) | 電話番号 | Số điện thoại |
| `prefecture` | VARCHAR(10) | | Tỉnh/Thành phố |
| `area` | VARCHAR(30) | | Quận/Huyện |
| `address1` | VARCHAR(100) | | Địa chỉ 1 |
| `address2` | VARCHAR(100) | | Địa chỉ 2 |
| `remarks` | TEXT | 備考 | Ghi chú |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Key Person**: キーパーソン - primary contact person for patient
- **Relationship Tracking**: 関係性 for relationship type
- **Contact Information**: Phone và address details
- **Japanese Names**: Kanji + Katakana name support

#### `mst_user_service` - 利用者マスタ(サービス履歴) (Master người sử dụng - lịch sử dịch vụ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `user_id` | VARCHAR(20) | 利用者ID | ID người sử dụng |
| `start_day` | DATE | 看護開始日 | Ngày bắt đầu điều dưỡng |
| `end_day` | DATE | 看護終了日 | Ngày kết thúc điều dưỡng |
| `start_type` | VARCHAR(30) | 開始区分 | Phân loại bắt đầu |
| `cancel_reason` | VARCHAR(30) | 中止理由 | Lý do dừng |
| `death_day` | DATE | | Ngày tử vong |
| `death_time` | TIME | | Thời gian tử vong |
| `death_place` | VARCHAR(30) | | Nơi tử vong |
| `death_reason` | VARCHAR(30) | | Lý do tử vong |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Nursing Service History**: 看護開始日/看護終了日 for nursing care periods
- **Service Lifecycle**: 開始区分, 中止理由 for service start/end reasons
- **End-of-Life Care**: Death-related fields for comprehensive care tracking
- **Care Documentation**: Complete service history với detailed outcomes

### 1.2 Complete User Data Architecture

```
mst_user (利用者マスタ) - Core user information
    ├── Personal & Social
    │   ├── mst_user_emergency (緊急連絡先) - Emergency contacts
    │   ├── mst_user_family (家族構成) - Family structure
    │   └── mst_user_person (キーパーソン) - Key contact person
    ├── Medical & Health
    │   ├── mst_user_drug (薬情) - Medication management
    │   ├── mst_user_medical (医療情報) - Medical provider info
    │   ├── mst_user_hospital (医療機関履歴) - Medical facility history
    │   └── mst_user_gaf (GAF) - Mental health assessment
    ├── Insurance & Finance
    │   ├── mst_user_insure1 (介護保険証履歴) - Care insurance history
    │   ├── mst_user_insure2 (給付情報) - Benefit information
    │   ├── mst_user_insure3 (医療保険証) - Medical insurance card
    │   ├── mst_user_insure4 (公費) - Public cost coverage
    │   └── mst_user_pay (支払方法) - Payment methods
    ├── Service Management
    │   ├── mst_user_service (サービス履歴) - Service history
    │   ├── mst_user_office1 (契約事業所履歴) - Contract office history
    │   ├── mst_user_office2 (居宅支援事業所履歴) - Home support office history
    │   └── mst_user_introduct (紹介履歴) - Referral history
    └── Documentation
        └── mst_user_image (画像) - Image management
```

#### `mst_staff` - 従業員マスタ(基本情報) (Master nhân viên - thông tin cơ bản)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_id` | VARCHAR(20) | 社員ID | ID nhân viên |
| `last_name` | VARCHAR(30) | 漢字氏名(苗字) | Họ (Kanji) |
| `first_name` | VARCHAR(30) | 漢字氏名(名前) | Tên (Kanji) |
| `last_kana` | VARCHAR(30) | カナ氏名(苗字) | Họ (Katakana) |
| `first_kana` | VARCHAR(30) | カナ氏名(名前) | Tên (Katakana) |
| `birthday` | DATE | 生年月日 | Ngày sinh |
| `sex` | VARCHAR(10) | 性別 | Giới tính |
| `address` | VARCHAR(512) | 住所 | Địa chỉ |
| `tel` | VARCHAR(20) | 電話番号 | Số điện thoại |
| `emg_contact` | VARCHAR(20) | 緊急連絡先 | Liên lạc khẩn cấp |
| `mail` | VARCHAR(100) | メールアドレス | Địa chỉ email |
| `role1` | VARCHAR(30) | 第1役割 | Vai trò thứ 1 |
| `role2` | VARCHAR(30) | 第2役割 | Vai trò thứ 2 |
| `linkage_name` | VARCHAR(30) | 連携システム名称 | Tên hệ thống liên kết |
| `linkage_code` | VARCHAR(20) | 連携システムコード | Mã hệ thống liên kết |
| `license1` | VARCHAR(20) | 請求用資格 | Chứng chỉ thanh toán |
| `job` | VARCHAR(20) | 職種 | Nghề nghiệp |
| `license2` | TEXT | 保有資格 | Chứng chỉ sở hữu |
| `retired` | VARCHAR(10) | 退職 | Nghỉ hưu |
| `remarks` | TEXT | 備考 | Ghi chú |
| `emg_name` | VARCHAR(30) | 緊急連絡先氏名(漢字) | Tên liên lạc khẩn cấp (Kanji) |
| `emg_kana` | VARCHAR(30) | 緊急連絡先氏名(カナ) | Tên liên lạc khẩn cấp (Katakana) |
| `relation_type` | VARCHAR(10) | 続柄 | Mối quan hệ |
| `emg_address` | VARCHAR(512) | 緊急連絡先住所 | Địa chỉ liên lạc khẩn cấp |
| `emg_mail` | VARCHAR(100) | 緊急連絡先メールアドレス | Email liên lạc khẩn cấp |
| `emg_tel` | VARCHAR(20) | 緊急連絡先電話番号 | Điện thoại liên lạc khẩn cấp |
| `emg_phone` | VARCHAR(20) | 緊急連絡先携帯 | Di động liên lạc khẩn cấp |
| `emg_remarks` | TEXT | 緊急連絡先備考 | Ghi chú liên lạc khẩn cấp |
| `account` | VARCHAR(256) | アカウント | Tài khoản |
| `hash_password` | VARCHAR(255) | | Mật khẩu hash |
| `type` | VARCHAR(10) | システム権限 | Quyền hệ thống |
| `employee_type` | VARCHAR(20) | 社員区分 | Phân loại nhân viên |
| `driving_license` | TINYINT(1) | 自動車免許の有無 | Có bằng lái xe |
| `name` | VARCHAR(100) | 対象スタッフ | Staff đích |
| `manual_updated_permission` | TINYINT(1) | 手動更新権限 | Quyền cập nhật thủ công |
| `corporate_id` | VARCHAR(20) | | ID công ty |
| `default_password_flg` | TINYINT(1) | | Flag mật khẩu mặc định |
| `token` | VARCHAR(255) | | Token xác thực |
| `corporate_admin_flg` | TINYINT(1) | | Flag admin công ty |

**Đặc điểm:**
- **Dual Roles**: Hỗ trợ 2 vai trò đồng thời
- **Healthcare Licensing**: license1 (billing), license2 (professional)
- **Emergency Contact**: Hệ thống liên lạc khẩn cấp hoàn chỉnh
- **System Access**: Account, password, permissions management

#### `mst_staff_office` - 従業員マスタ(所属情報) (Master nhân viên - thông tin thuộc về)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `staff_id` | VARCHAR(20) | スタッフID | ID nhân viên |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `place_name` | VARCHAR(30) | 拠点名称 | Tên cơ sở |
| `office1_id` | VARCHAR(20) | 事業所1ID(看多機) | ID văn phòng 1 (Kantaki) |
| `office1_name` | VARCHAR(30) | 事業所1名称(看多機) | Tên văn phòng 1 (Kantaki) |
| `office2_id` | VARCHAR(20) | 事業所2ID(訪問看護) | ID văn phòng 2 (Thăm khám điều dưỡng) |
| `office2_name` | VARCHAR(30) | 事業所2名称(訪問看護) | Tên văn phòng 2 (Thăm khám điều dưỡng) |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Time-bound Assignment**: start_day/end_day cho assignment có thời hạn
- **Multi-office Assignment**: Hỗ trợ 2 loại văn phòng (Kantaki + Visiting Nurse)
- **Hierarchical Structure**: Place → Office assignment

#### `mst_office` - 事業所マスタ (Master văn phòng kinh doanh)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `place_id` | VARCHAR(20) | 拠点ID | ID cơ sở |
| `office_group` | VARCHAR(20) | 事業所グループID | ID nhóm văn phòng |
| `type` | VARCHAR(10) | 事業所分類 | Phân loại văn phòng |
| `status` | VARCHAR(10) | ステータス | Trạng thái |
| `record_no` | INT | 履歴番号 | Số lịch sử |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `name` | VARCHAR(30) | 事業所名称 | Tên văn phòng |
| `office_no` | VARCHAR(10) | 指定事業所番号 | Số văn phòng được chỉ định |
| `disply_name` | VARCHAR(30) | 帳票表記名称 | Tên hiển thị trên báo cáo |
| `price` | VARCHAR(10) | 地域単価 | Đơn giá theo khu vực |
| `other_code` | VARCHAR(10) | 別システムコード | Mã hệ thống khác |
| `layer_code` | VARCHAR(20) | 階層コード | Mã phân cấp |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `prefecture` | VARCHAR(10) | 住所(都道府県) | Địa chỉ (Tỉnh/Thành phố) |
| `area` | VARCHAR(30) | 住所(市区町村) | Địa chỉ (Quận/Huyện) |
| `address1` | VARCHAR(100) | 住所(町域) | Địa chỉ (Khu vực) |
| `address2` | VARCHAR(100) | 住所(番地以降) | Địa chỉ (Từ số nhà trở đi) |
| `tel` | VARCHAR(20) | 電話番号 | Số điện thoại |
| `fax` | VARCHAR(20) | FAX | Số fax |
| `mail` | VARCHAR(100) | メールアドレス | Địa chỉ email |
| `manager_id` | VARCHAR(20) | 管理者スタッフID | ID nhân viên quản lý |
| `capacity1` | INT | 宿泊定員 | Số người ở lại qua đêm |
| `capacity2` | INT | 通い定員 | Số người đi lại trong ngày |
| `capacity3` | INT | 同時可能件数(一般浴) | Số lượng đồng thời (tắm thường) |
| `capacity4` | INT | 同時可能件数(ストレッチャー浴) | Số lượng đồng thời (tắm cáng) |
| `capacity5` | INT | 同時可能件数(チェアー浴) | Số lượng đồng thời (tắm ghế) |
| `station_code` | VARCHAR(20) | ステーションコード | Mã trạm |

**Service Enhancement Adds (select1-6):**
| Field | Comment | Mô tả |
|-------|---------|-------|
| `select1` | サービス提供体制強化加算 | Phụ cấp tăng cường thể chế cung cấp dịch vụ |
| `select2` | 看護体制強化加算 | Phụ cấp tăng cường thể chế điều dưỡng |
| `select3` | 総合マネジメント体制強化加算 | Phụ cấp tăng cường thể chế quản lý tổng hợp |
| `select4` | 訪問体制強化加算 | Phụ cấp tăng cường thể chế thăm khám |
| `select5` | 介護職員処遇改善加算 | Phụ cấp cải thiện đãi ngộ nhân viên chăm sóc |
| `select6` | 介護職員特定処遇改善加算 | Phụ cấp cải thiện đãi ngộ cụ thể nhân viên chăm sóc |

**Visiting Nurse Adds (select7-15):**
| Field | Comment | Mô tả |
|-------|---------|-------|
| `select7` | 特別地域訪問看護加算 | Phụ cấp thăm khám điều dưỡng khu vực đặc biệt |
| `select8` | 訪問看護小規模事業所加算 | Phụ cấp văn phòng quy mô nhỏ thăm khám điều dưỡng |
| `select9` | 訪問看護中山間地域提供加算 | Phụ cấp cung cấp khu vực miền núi thăm khám điều dưỡng |
| `select10` | 訪問看護体制強化加算 | Phụ cấp tăng cường thể chế thăm khám điều dưỡng |
| `select11` | 訪問看護サービス提供体制加算1 | Phụ cấp thể chế cung cấp dịch vụ thăm khám điều dưỡng 1 |
| `select12` | 訪問看護サービス提供体制加算2 | Phụ cấp thể chế cung cấp dịch vụ thăm khám điều dưỡng 2 |
| `select13` | 緊急時訪問看護加算 | Phụ cấp thăm khám điều dưỡng khẩn cấp |
| `select14` | 24時間対応体制加算 | Phụ cấp thể chế ứng phó 24 giờ |
| `select15` | 機能強化型訪問看護管理療養費 | Phí điều trị quản lý thăm khám điều dưỡng loại tăng cường chức năng |

**Complex Pricing Structure (add1_1_1 to add2_3_4):**
- **add1_1_x**: Kantaki service reductions
- **add2_1_x**: Medical insurance visiting nurse
- **add2_2_x**: Care insurance visiting nurse  
- **add2_3_x**: Regular patrol services

#### `mst_office_other` - 事業所マスタ(巡回事業所) (Master văn phòng tuần hoàn)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `wellness_no` | VARCHAR(20) | WELLNESS_NO | Số Wellness |
| `corp_id` | VARCHAR(20) | 法人番号 | Số pháp nhân |
| `office_id` | VARCHAR(20) | 事業所ID | ID văn phòng |
| `prefecture_id` | VARCHAR(10) | 都道府県コード | Mã tỉnh/thành phố |
| `area_id` | VARCHAR(10) | 市町村コード | Mã thành phố/thị trấn |
| `corp_name` | VARCHAR(50) | 運営法人名 | Tên pháp nhân vận hành |
| `office_name` | VARCHAR(50) | 事業所名 | Tên văn phòng |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `prefecture` | VARCHAR(10) | 都道府県 | Tỉnh/Thành phố |
| `area` | VARCHAR(30) | 市区町村 | Quận/Huyện |
| `address` | VARCHAR(100) | 町番地 | Địa chỉ số nhà |
| `tel` | VARCHAR(20) | TEL | Điện thoại |
| `fax` | VARCHAR(20) | FAX | Fax |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Patrol Office**: Văn phòng tuần hoàn cho mobile healthcare services
- **External Integration**: wellness_no cho tích hợp hệ thống bên ngoài
- **Geographic Coding**: prefecture_id, area_id cho location-based services

#### `mst_office_patrol` - 事業所マスタ(巡回事業所) (Master văn phòng tuần hoàn)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `office_id` | VARCHAR(20) | 事業所ID | ID văn phòng |
| `type` | VARCHAR(20) | 巡回事業所タイプ | Loại văn phòng tuần hoàn |
| `name` | VARCHAR(50) | 巡回事業所名 | Tên văn phòng tuần hoàn |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Simple Structure**: Cấu trúc đơn giản cho patrol office management
- **Type Classification**: 巡回事業所タイプ cho different patrol service types
- **Office Linkage**: Links to main office via office_id

#### `mst_place` - 拠点マスタ (Master cơ sở)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `name` | VARCHAR(30) | 拠点名称 | Tên cơ sở |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `prefecture` | VARCHAR(10) | 住所(都道府県) | Địa chỉ (Tỉnh/Thành phố) |
| `area` | VARCHAR(30) | 住所(市区町村) | Địa chỉ (Quận/Huyện) |
| `address1` | VARCHAR(100) | 住所(町域) | Địa chỉ (Khu vực) |
| `address2` | VARCHAR(100) | 住所(番地以降) | Địa chỉ (Từ số nhà trở đi) |
| `layer_code` | VARCHAR(20) | 階層コード | Mã phân cấp |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Location Entity**: Represents physical locations/facilities
- **Address Structure**: Standard Japanese address format
- **Hierarchical**: layer_code for organizational hierarchy
- **Foundation**: Base level cho office assignments

#### `mst_company` - 法人マスタ (Master pháp nhân)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `name` | VARCHAR(30) | 法人名称 | Tên pháp nhân |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Legal Entity**: Represents legal corporations
- **Time-bound**: start_day/end_day for entity lifecycle
- **Simple Structure**: Minimal fields for legal entity management
- **Foundation**: Top level cho corporate hierarchy

#### `mst_corporate` - Master tập đoàn (No Japanese table comment)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(255) PK | | ID duy nhất |
| `h2_corporate_id` | VARCHAR(255) | | ID tập đoàn H2 |
| `kantaki_corporate_id` | VARCHAR(255) UNI | | ID tập đoàn Kantaki |
| `h2_company_name` | VARCHAR(255) | | Tên công ty H2 |
| `kantaki_company_name` | VARCHAR(255) UNI | | Tên công ty Kantaki |
| `corporate_status` | TINYINT(1) | 0: Active, 1: Stop | Trạng thái tập đoàn |
| `contract_period_start` | DATETIME | | Ngày bắt đầu hợp đồng |
| `contract_period_end` | DATETIME | | Ngày kết thúc hợp đồng |
| `phone_number` | VARCHAR(255) | | Số điện thoại |
| `postal_code` | VARCHAR(10) | | Mã bưu chính |
| `prefecture` | VARCHAR(255) | | Tỉnh/Thành phố |
| `area` | VARCHAR(255) | | Quận/Huyện |
| `address1` | VARCHAR(255) | | Địa chỉ 1 |
| `address2` | TEXT | | Địa chỉ 2 |
| `email` | VARCHAR(255) | | Email |
| `logo_path` | TEXT | | Đường dẫn logo |
| `delete_flg` | TINYINT(1) | 0: Active, 1: Deleted | Flag xóa mềm |
| `create_user` | VARCHAR(255) | | Người tạo |
| `create_date` | DATETIME | | Thời gian tạo |
| `update_user` | VARCHAR(255) | | Người cập nhật |
| `update_date` | DATETIME | | Thời gian cập nhật |
| `mapping_status` | TINYINT(1) | 0: Unconfirmed, 1: Confirmed | Trạng thái mapping |

**Đặc điểm:**
- **System Integration**: H2 ↔ Kantaki system mapping
- **Corporate Management**: Top-level corporate entity management
- **Contract Management**: Contract period tracking
- **Status Management**: Corporate status, mapping status
- **Different Pattern**: Uses VARCHAR(255) instead of VARCHAR(20) for IDs
- **Unique Constraints**: kantaki_corporate_id, kantaki_company_name

### 2. Healthcare Specialized Tables

#### `mst_disease` - 病名マスタ (Master tên bệnh)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `code` | INT | 病名コード | Mã tên bệnh |
| `name` | VARCHAR(30) | 正式名称 | Tên chính thức |
| `disp_name` | VARCHAR(30) | 略称 | Tên viết tắt |
| `kana` | VARCHAR(100) | カナ名称 | Tên Katakana |
| `target_flg1` | TINYINT(1) | 別表7対応 | Tương ứng bảng phụ 7 |
| `target_flg2` | TINYINT(1) | 別表8対応 | Tương ứng bảng phụ 8 |

**Đặc điểm:**
- **Disease Classification**: Standardized disease codes cho healthcare billing
- **Japanese Medical Standards**: Follows Japanese medical classification system
- **Multiple Names**: 正式名称 (official) + 略称 (abbreviation) + カナ名称 (katakana)
- **Regulatory Compliance**: 別表7/8対応 for specific healthcare regulations

#### `mst_insure` - 保険マスタ (Master bảo hiểm)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `type` | VARCHAR(10) | 保険分類 | Phân loại bảo hiểm |
| `code` | VARCHAR(10) | 法別番号 | Số pháp biệt |
| `name` | VARCHAR(30) | 保険名称 | Tên bảo hiểm |
| `remarks` | TEXT | 備考 | Ghi chú |

**Đặc điểm:**
- **Insurance Classification**: 保険分類 for different insurance types
- **Legal Numbers**: 法別番号 for official insurance identification
- **Coverage Management**: Standard insurance information for billing

#### `mst_uninsure` - 保険外マスタ (Master ngoài bảo hiểm)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `type` | VARCHAR(10) | 保険種類 | Loại bảo hiểm |
| `code1` | VARCHAR(10) | 法別番号 | Số pháp biệt |
| `code2` | VARCHAR(10) | 保険番号 | Số bảo hiểm |
| `name` | VARCHAR(30) | 保険名称 | Tên bảo hiểm |
| `disp_name` | VARCHAR(30) | 請求書名称 | Tên trên hóa đơn |
| `standard_flg` | TINYINT(1) | 基本サービス区分 | Phân loại dịch vụ cơ bản |
| `start_day` | DATE | 有効開始日 | Ngày bắt đầu hiệu lực |
| `end_day` | DATE | 有効終了日 | Ngày kết thúc hiệu lực |
| `price` | INT | 金額 | Số tiền |
| `zei_type` | VARCHAR(10) | 税区分 | Phân loại thuế |
| `rate` | INT | 税率(%) | Tỷ lệ thuế (%) |
| `subsidy` | VARCHAR(10) | 控除対象 | Đối tượng khấu trừ |
| `office` | TEXT | 使用事業所 | Văn phòng sử dụng |
| `link_office` | VARCHAR(20) | 事業所ID | ID văn phòng |
| `remarks` | TEXT | 備考 | Ghi chú |
| `display_order` | INT | | Thứ tự hiển thị |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Self-Pay Services**: Services not covered by insurance
- **Tax Management**: 税区分, 税率 for proper tax calculation
- **Pricing Structure**: 金額 with tax rate calculations
- **Office Specific**: Can be linked to specific offices
- **Time-bound**: start_day/end_day for service availability

#### `mst_service` - 利用者サービスマスタ (Master dịch vụ người sử dụng)

Đã được mô tả ở phần trước với cấu trúc:
- `type`, `code`, `name`, `remarks`, `send_code`

#### `mst_service_detail` - 利用者サービスマスタ (Master chi tiết dịch vụ người sử dụng)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `type` | VARCHAR(10) | サービス内容 | Nội dung dịch vụ |
| `name` | VARCHAR(30) | サービス内容詳細 | Chi tiết nội dung dịch vụ |
| `remarks` | TEXT | 備考 | Ghi chú |

**Đặc điểm:**
- **Service Details**: Chi tiết breakdown của services
- **Hierarchical**: type → name for service classification
- **Simple Structure**: Focus on service content description

### 3. Healthcare Service Architecture

```
mst_service (利用者サービス)
    ↓
mst_service_detail (サービス内容詳細)
    ↓
mst_disease (病名) ← Medical conditions
    ↓
mst_insure (保険) / mst_uninsure (保険外) ← Coverage determination
```

### 4. Organizational Hierarchy

```
mst_corporate (集団) - Highest level
    ↓
mst_company (法人) - Legal entity level
    ↓
mst_place (拠点) - Physical location level
    ↓
mst_office (事業所) - Operational office level
    ↓
mst_office_patrol (巡回事業所) - Mobile service level
```

### 5. System Configuration & Support Tables

#### `mst_add` - 加減算マスタ (Master cộng trừ)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `type` | VARCHAR(20) | サービス内容 | Nội dung dịch vụ |
| `code` | VARCHAR(10) | 加減算コード | Mã cộng trừ |
| `name` | VARCHAR(30) | 加減算名称 | Tên cộng trừ |
| `kaisu_flg` | INT | 回数加算判定用フラグ | Flag xác định cộng theo số lần |
| `remarks` | TEXT | 備考 | Ghi chú |
| `span_flg` | TINYINT(1) | 期間指定フラグ | Flag chỉ định thời gian |
| `office_flg` | TINYINT(1) | 事業所フラグ | Flag văn phòng |
| `count_flg` | TINYINT(1) | 回数フラグ | Flag số lần |
| `send_code` | VARCHAR(2) | コンダクト連携用 | Cho liên kết Conduct |

**Đặc điểm:**
- **Billing Adjustments**: 加減算コード for healthcare billing add/subtract calculations
- **Service-specific**: Links to サービス内容 for service-specific adjustments
- **Complex Logic**: Multiple flags for different calculation scenarios
- **External Integration**: コンダクト連携用 for external system integration

#### `mst_area` - エリアマスタ (Master khu vực)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `prefecture_id` | VARCHAR(2) | 都道府県ID | ID tỉnh/thành phố |
| `prefecture_name` | VARCHAR(20) | 都道府県名称 | Tên tỉnh/thành phố |
| `prefecture_kana` | VARCHAR(20) | 都道府県カナ | Tên tỉnh/thành phố (Katakana) |
| `city_id` | VARCHAR(10) | 市区町村ID | ID thành phố/quận/huyện |
| `city_name` | VARCHAR(20) | 市区町村名 | Tên thành phố/quận/huyện |
| `city_kana` | VARCHAR(40) | 市区町村カナ | Tên thành phố/quận/huyện (Katakana) |
| `town_name` | VARCHAR(20) | 町域名 | Tên khu vực |
| `remarks` | TEXT | 対象地域判定 | Xác định khu vực đích |

**Đặc điểm:**
- **Japanese Address System**: Complete Japanese postal code to address mapping
- **Multi-language**: Kanji + Katakana names for all geographic levels
- **Hierarchical Structure**: Prefecture → City → Town hierarchy
- **Service Area Determination**: 対象地域判定 for service coverage areas

#### `mst_bank` - Master ngân hàng (No Japanese table comment)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `bank_code` | VARCHAR(10) | 金融機関コード | Mã cơ quan tài chính |
| `bank_name` | VARCHAR(50) | 金融機関名 | Tên cơ quan tài chính |
| `branch_code` | VARCHAR(10) | 支店コード | Mã chi nhánh |
| `branch_name` | VARCHAR(20) | 支店名 | Tên chi nhánh |
| `remarks` | VARCHAR(256) | 備考 | Ghi chú |

**Đặc điểm:**
- **Banking Infrastructure**: Japanese banking system codes và names
- **Branch Management**: 支店コード, 支店名 for bank branch information
- **Payment Integration**: Supports mst_user_pay banking requirements
- **Standard Structure**: Follows Japanese banking code conventions

#### `mst_business_partner` - 事業所情報 (Thông tin văn phòng kinh doanh)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `business_category` | VARCHAR(255) | 事業所区分 | Phân loại văn phòng kinh doanh |
| `medical_facility_code` | VARCHAR(50) | 医療機関コード | Mã cơ quan y tế |
| `designated_business_code` | VARCHAR(50) | 指定事業所番号 | Số văn phòng kinh doanh được chỉ định |
| `legal_name` | VARCHAR(255) | 医療機関名／事業所名 | Tên cơ quan y tế / văn phòng |
| `facility_name` | VARCHAR(255) | 施設名 | Tên cơ sở |
| `post` | VARCHAR(10) | 郵便番号 | Mã bưu chính |
| `prefecture_name` | VARCHAR(20) | 都道府県名称 | Tên tỉnh/thành phố |
| `city_name` | VARCHAR(20) | 市区町村名 | Tên thành phố/quận/huyện |
| `town_name` | VARCHAR(100) | 町域名 | Tên khu vực |
| `address_detail` | VARCHAR(255) | 番地以下（建物名含む） | Chi tiết địa chỉ (bao gồm tên tòa nhà) |
| `phone_number` | VARCHAR(20) | 電話番号 | Số điện thoại |
| `fax_number` | VARCHAR(20) | FAX番号 | Số FAX |
| `email` | VARCHAR(100) | メールアドレス | Địa chỉ email |
| `corporate_id` | VARCHAR(255) IDX | 企業ID | ID doanh nghiệp |

**Đặc điểm:**
- **Business Partner Network**: External medical facilities và business offices
- **Official Codes**: 医療機関コード, 指定事業所番号 for regulatory compliance
- **Complete Contact Info**: Full address, phone, fax, email information
- **Multi-purpose**: Handles both medical facilities và general business partners

#### `mst_car` - 自動車マスタ (Master ô tô)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `office_id` | VARCHAR(20) | 事業所ID | ID văn phòng |
| `name` | VARCHAR(30) | 自動車名称 | Tên ô tô |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **Vehicle Management**: 自動車マスタ for healthcare service vehicles
- **Office Assignment**: Links vehicles to specific offices
- **Simple Structure**: Basic vehicle identification for visiting nurse services
- **Resource Planning**: Supports staff assignment và service scheduling

#### `mst_code` - コードマスタ (Master mã)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `group_div` | VARCHAR(40) | 分類 | Phân loại |
| `type` | VARCHAR(40) | 項目 | Mục |
| `name` | VARCHAR(100) | 名称 | Tên |
| `remarks` | TEXT | 備考 | Ghi chú |
| `edit_flg` | TINYINT(1) | 編集可否フラグ | Flag có thể chỉnh sửa |
| `disp_flg` | TINYINT(1) | 表示フラグ | Flag hiển thị |

**Đặc điểm:**
- **System Code Management**: Central repository cho all system codes
- **Hierarchical**: 分類 → 項目 → 名称 structure
- **Configurable**: 編集可否フラグ, 表示フラグ for runtime configuration
- **Flexible**: Supports various dropdown lists và system constants

#### `mst_news` - お知らせマスタ (Master thông báo)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `title` | VARCHAR(100) | タイトル | Tiêu đề |
| `detail` | TEXT | 本文 | Nội dung |
| `status` | INT | 公開状態 | Trạng thái công khai |
| `target` | INT | 公開対象 | Đối tượng công khai |
| `place_id` | VARCHAR(20) | 拠点 | Cơ sở |
| `corporate_id` | VARCHAR(20) | | ID công ty |

**Đặc điểm:**
- **News Management**: お知らせマスタ for system announcements
- **Publication Control**: 公開状態, 公開対象 for content management
- **Location-specific**: Can target specific 拠点 (places)
- **Content Management**: タイトル, 本文 for rich content support

#### `mst_number` - 発番管理マスタ (Master quản lý phát hành số)

| Field | Type | Comment | Mô tả |
|-------|------|---------|-------|
| `unique_id` | VARCHAR(20) PK | 固有ID | ID duy nhất |
| `delete_flg` | TINYINT(1) | 削除フラグ | Flag xóa mềm |
| `create_date` | DATETIME | 作成日時 | Thời gian tạo |
| `create_user` | VARCHAR(20) | 作成者 | Người tạo |
| `update_date` | DATETIME | 更新日時 | Thời gian cập nhật |
| `update_user` | VARCHAR(20) | 更新者 | Người cập nhật |
| `type` | VARCHAR(30) | 対象データ | Dữ liệu đích |
| `name` | VARCHAR(30) | 対象データ名称 | Tên dữ liệu đích |
| `initial` | VARCHAR(4) | 頭文字 | Chữ cái đầu |
| `digits` | VARCHAR(20) | 桁数 | Số chữ số |
| `last` | INT | 発番済みSEQ | Sequence đã phát hành |

**Đặc điểm:**
- **ID Generation**: 発番管理マスタ for automatic ID generation
- **Configurable Format**: 頭文字, 桁数 for custom ID formats
- **Sequence Management**: 発番済みSEQ tracks last issued numbers
- **Multi-entity**: Supports different ID formats cho different 対象データ

### 6. System Architecture Overview

```
System Configuration & Support Layer
├── Core Configuration
│   ├── mst_code (コードマスタ) - System codes & constants
│   ├── mst_number (発番管理マスタ) - ID generation management
│   └── mst_news (お知らせマスタ) - System announcements
├── Geographic & Location
│   ├── mst_area (エリアマスタ) - Japanese address system
│   └── mst_business_partner (事業所情報) - External partners
├── Financial & Billing
│   ├── mst_add (加減算マスタ) - Billing adjustments
│   └── mst_bank (Master ngân hàng) - Banking information
└── Operations Support
    └── mst_car (自動車マスタ) - Vehicle management
```

## Common Patterns trong Master Tables

### Standard Audit Fields
Tất cả bảng master đều có pattern audit trail nhất quán:
```sql
unique_id VARCHAR(20) PRIMARY KEY,      -- 固有ID
delete_flg TINYINT(1) NOT NULL,         -- 削除フラグ
create_date DATETIME NOT NULL,          -- 作成日時
create_user VARCHAR(20) NOT NULL,       -- 作成者
update_date DATETIME NOT NULL,          -- 更新日時
update_user VARCHAR(20) NOT NULL,       -- 更新者
corporate_id VARCHAR(20)                -- Multi-tenant isolation
```

### Japanese Healthcare Compliance
- **Patient/Staff ID Management**: Standardized ID formats via mst_number
- **Multi-language Support**: Kanji + Katakana fields across all entities
- **Geographic Structure**: Prefecture → Area → Address hierarchy via mst_area
- **Service Classification**: Complex healthcare service categorization
- **Pricing Integration**: Extensive add/subtract pricing fields via mst_add
- **External Integration**: Business partner network via mst_business_partner

### Configuration Management
- **System Codes**: Central mst_code table cho all dropdown values
- **ID Generation**: Automated mst_number for consistent ID formats
- **Geographic Data**: Complete Japanese postal system via mst_area
- **Banking Integration**: Standard Japanese banking codes via mst_bank

---

> 💡 **Lưu ý**: Comment tiếng Nhật trong database cung cấp context chính xác cho business logic. Các pattern nhất quán trong naming và structure giúp maintain data integrity across healthcare workflow. System configuration tables provide essential infrastructure cho comprehensive healthcare management system.