# Ngữ cảnh Database - KANTAKI-WIZ

## Tổng quan Database

### Thông tin Kết nối
- **Tên Database**: `kantaki_dev`
- **Container**: `db` (Docker)
- **Engine**: MySQL 8.0
- **Credentials**: kantaki/kantaki
- **Port**: 3306 (external), 3306 (internal)

### Thống kê Database
- **Tổng số Bảng**: 82 bảng
- **Character Set**: UTF-8 (yêu cầu y tế Nhật Bản)
- **Timezone**: Asia/Tokyo (JST)

## Phân loại Bảng & Kiến trúc

### 1. Bảng Hệ thống (7 bảng)
**Mục đích**: Quản lý framework và hạ tầng

| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `cache` | (trống) | Lưu trữ cache ứng dụng |
| `cache_locks` | (trống) | Cơ chế khóa cache |
| `csv_imports` | (trống) | Import dữ liệu CSV |
| `failed_jobs` | (trống) | Jobs thất bại nền |
| `job_batches` | (trống) | Xử lý batch job |
| `jobs` | (trống) | Quản lý queue job |
| `migrations` | (trống) | Versioning schema database |

### 2. Bảng Dữ liệu Master (36 bảng - prefix `mst_`)
**Mục đích**: Dữ liệu tham chiếu cốt lõi và cấu hình

#### Các Entity Cốt lõi
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `mst_user` | 利用者マスタ | Dữ liệu master người sử dụng dịch vụ |
| `mst_staff` | 従業員マスタ(基本情報) | Master nhân viên (thông tin cơ bản) |
| `mst_staff_office` | 従業員マスタ(所属情報) | Master nhân viên (thông tin thuộc về) |
| `mst_office` | 事業所マスタ | Master văn phòng kinh doanh |
| `mst_office_other` | 事業所マスタ(巡回事業所) | Master văn phòng (văn phòng tuần hoàn) |
| `mst_office_patrol` | 事業所マスタ(巡回事業所) | Master văn phòng (văn phòng tuần hoàn) |
| `mst_place` | 拠点マスタ | Master cơ sở |
| `mst_company` | 法人マスタ | Master pháp nhân |
| `mst_corporate` | (trống) | Master tập đoàn |

#### Master Y tế Chuyên biệt
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `mst_disease` | 病名マスタ | Master tên bệnh |
| `mst_insure` | 保険マスタ | Master bảo hiểm |
| `mst_uninsure` | 保険外マスタ | Master ngoài bảo hiểm |
| `mst_service` | 利用者サービスマスタ | Master dịch vụ người sử dụng |
| `mst_service_detail` | 利用者サービスマスタ | Master dịch vụ người sử dụng |

#### Master Liên quan Người dùng (Dữ liệu mở rộng)
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `mst_user_drug` | 利用者マスタ(薬情) | Master người sử dụng (thông tin thuốc) |
| `mst_user_emergency` | 利用者マスタ(緊急連絡先) | Master người sử dụng (liên lạc khẩn cấp) |
| `mst_user_family` | 利用者マスタ(家族構成) | Master người sử dụng (cấu trúc gia đình) |
| `mst_user_gaf` | 利用者マスタ(GAF) | Master người sử dụng (GAF) |
| `mst_user_hospital` | 利用者マスタ(医療機関履歴) | Master người sử dụng (lịch sử cơ quan y tế) |
| `mst_user_image` | 利用者マスタ(画像) | Master người sử dụng (hình ảnh) |
| `mst_user_insure1` | 利用者マスタ(介護保険証履歴) | Master người sử dụng (lịch sử thẻ bảo hiểm chăm sóc) |
| `mst_user_insure2` | 利用者マスタ(給付情報) | Master người sử dụng (thông tin trợ cấp) |
| `mst_user_insure3` | 利用者マスタ(医療保険証) | Master người sử dụng (thẻ bảo hiểm y tế) |
| `mst_user_insure4` | 利用者マスタ(公費) | Master người sử dụng (chi phí công) |
| `mst_user_introduct` | 利用者マスタ(紹介履歴) | Master người sử dụng (lịch sử giới thiệu) |
| `mst_user_medical` | 利用者マスタ(医療情報) | Master người sử dụng (thông tin y tế) |
| `mst_user_office1` | 利用者マスタ(契約事業所履歴) | Master người sử dụng (lịch sử văn phòng hợp đồng) |
| `mst_user_office2` | 利用者マスタ(居宅支援事業所履歴) | Master người sử dụng (lịch sử văn phòng hỗ trợ tại nhà) |
| `mst_user_pay` | 利用者マスタ(支払方法) | Master người sử dụng (phương thức thanh toán) |
| `mst_user_person` | 利用者マスタ(キーパーソン) | Master người sử dụng (người liên hệ chính) |
| `mst_user_service` | 利用者マスタ(サービス履歴) | Master người sử dụng (lịch sử dịch vụ) |

#### Master Hỗ trợ
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `mst_add` | 加減算マスタ | Master cộng trừ |
| `mst_area` | エリアマスタ | Master khu vực |
| `mst_bank` | (trống) | Master ngân hàng |
| `mst_business_partner` | 事業所情報 | Thông tin văn phòng kinh doanh |
| `mst_car` | 自動車マスタ | Master ô tô |
| `mst_code` | コードマスタ | Master mã |
| `mst_news` | お知らせマスタ | Master thông báo |
| `mst_number` | 発番管理マスタ | Master quản lý phát hành số |

#### Backup Tables
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `mst_add.20240122` | 加減算マスタ | Master cộng trừ (backup 22/01/2024) |
| `mst_user.20240123` | 利用者マスタ | Master người sử dụng (backup 23/01/2024) |

### 3. Bảng Dữ liệu Vận hành (18 bảng - prefix `dat_`)
**Mục đích**: Dữ liệu y tế vận hành hàng ngày

#### Dữ liệu Kế hoạch (予定)
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `dat_root_plan` | ルート予定 | Lịch trình route |
| `dat_staff_plan` | 従業員予定 | Lịch trình nhân viên |
| `dat_user_plan` | 利用者予定 | Lịch trình người sử dụng |
| `dat_user_plan_add` | 利用者予定(加減算) | Lịch trình người sử dụng (cộng trừ) |
| `dat_user_plan_jippi` | 利用者予定(実費) | Lịch trình người sử dụng (chi phí thực tế) |
| `dat_user_plan_service` | 利用者予定(サービス詳細) | Lịch trình người sử dụng (chi tiết dịch vụ) |

#### Dữ liệu Thành tích (実績)
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `dat_staff_record` | 従業員実績 | Thành tích nhân viên |
| `dat_user_record` | 利用者実績 | Thành tích người sử dụng |
| `dat_user_record_add` | 利用者予定(加減算) | Lịch trình người sử dụng (cộng trừ) ⚠️ |
| `dat_user_record_jippi` | 利用者実績(実費) | Thành tích người sử dụng (chi phí thực tế) |
| `dat_user_record_service` | 利用者実績(サービス詳細) | Thành tích người sử dụng (chi tiết dịch vụ) |

#### Dữ liệu Lịch trình Tuần
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `dat_staff_schedule` | 従業員予定 | Lịch trình nhân viên |
| `dat_week_schedule` | 週間スケジュール | Lịch trình tuần |
| `dat_week_schedule_add` | 週間スケジュール(加減算) | Lịch trình tuần (cộng trừ) |
| `dat_week_schedule_jippi` | 週間スケジュール(実費) | Lịch trình tuần (chi phí thực tế) |
| `dat_week_schedule_service` | 週間スケジュール(サービス詳細) | Lịch trình tuần (chi tiết dịch vụ) |

#### Cấu hình & Trạng thái
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `dat_root_config` | ルート設定 | Cấu hình route |
| `dat_news_status` | お知らせ閲覧状況 | Tình trạng đọc thông báo |

> ⚠️ **Lỗi phát hiện**: `dat_user_record_add` có comment "利用者予定(加減算)" nhưng nên là "利用者実績(加減算)" để đồng nhất với các bảng record khác.

### 4. Bảng Tài liệu (19 bảng - prefix `doc_`)
**Mục đích**: Tài liệu và báo cáo y tế

#### Tài liệu Cốt lõi
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_kantaki` | 看多機記録 | Hồ sơ Kantaki (điều dưỡng đa chức năng) |
| `doc_plan` | 計画書 | Kế hoạch |
| `doc_progress` | 経過記録 | Hồ sơ tiến triển |
| `doc_report` | 報告書 | Báo cáo |
| `doc_instruct` | 指示書 | Sách chỉ thị |

#### Tài liệu Kantaki Chuyên biệt
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_kantaki_drug` | 看多機記録(服薬) | Hồ sơ Kantaki (uống thuốc) |
| `doc_kantaki_vital` | 看多機記録(バイタル) | Hồ sơ Kantaki (dấu hiệu sinh tồn) |
| `doc_kantaki_excretion` | 看多機記録(排泄) | Hồ sơ Kantaki (bài tiết) |
| `doc_kantaki_water` | 看多機記録(水分摂取) | Hồ sơ Kantaki (lượng nước tiêu thụ) |
| `doc_kantaki_goods` | 看多機記録(物品使用) | Hồ sơ Kantaki (sử dụng vật phẩm) |
| `doc_kantaki_staff` | 看多機記録(スタッフ) | Hồ sơ Kantaki (nhân viên) |
| `doc_bedsore` | 褥瘡計画 | Kế hoạch loét do nằm lâu |

#### Tài liệu Thăm khám Điều dưỡng
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_visit1` | (trống) | Thăm khám điều dưỡng 1 |
| `doc_visit1_facility` | 訪問看護記録1(関係施設) | Hồ sơ thăm khám điều dưỡng 1 (cơ sở liên quan) |
| `doc_visit1_family` | 訪問看護記録1(家族) | Hồ sơ thăm khám điều dưỡng 1 (gia đình) |
| `doc_visit2` | 訪問看護記録2 | Hồ sơ thăm khám điều dưỡng 2 |
| `doc_visit2_problem` | 訪問看護記録2(問題点) | Hồ sơ thăm khám điều dưỡng 2 (điểm vấn đề) |

#### Theo dõi Vấn đề & Sự kiện
| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `doc_plan_problem` | 計画書(課題) | Kế hoạch (vấn đề) |
| `doc_report_event` | 報告書(イベント) | Báo cáo (sự kiện) |

### 5. Bảng Hỗ trợ & Log (2 bảng)
**Mục đích**: Logging và import dữ liệu

| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `log_entry` | 更新ログ | Log cập nhật |
| `log_error` | エラーログ | Log lỗi |

## Mối quan hệ Chính

### Mối quan hệ Entity Cốt lõi
```
mst_user (患者/利用者)
├── mst_user_* (dữ liệu mở rộng người dùng)
├── dat_user_* (dữ liệu vận hành)
└── doc_* (tài liệu)

mst_staff (職員)
├── mst_staff_office (phân công văn phòng)
├── dat_staff_* (dữ liệu vận hành)
└── doc_kantaki_staff

mst_office (事業所)
├── mst_place (địa điểm)
└── mst_staff_office
```

### Luồng Vận hành
```
Dữ liệu Master → Kế hoạch (dat_*_plan) → Hồ sơ (dat_*_record) → Tài liệu (doc_*)
```

## Tiêu chuẩn Dữ liệu Y tế

### Tuân thủ Y tế Nhật Bản
- **ID Bệnh nhân**: Định danh duy nhất theo tiêu chuẩn Nhật Bản
- **Bảo hiểm**: Hỗ trợ nhiều bảo hiểm (tối đa 4 loại)
- **Hồ sơ Y tế**: Tuân thủ luật hồ sơ y tế Nhật Bản
- **Audit Trail**: Theo dõi thay đổi hoàn chỉnh để tuân thủ quy định

### Phân loại Bảo mật Dữ liệu
- **Cấp 1 (Cao nhất)**: Hồ sơ y tế bệnh nhân, thông tin cá nhân
- **Cấp 2 (Cao)**: Thông tin nhân viên, dữ liệu bảo hiểm
- **Cấp 3 (Trung bình)**: Lịch trình vận hành, dữ liệu kế hoạch
- **Cấp 4 (Tiêu chuẩn)**: Dữ liệu master, cấu hình

## Cân nhắc Hiệu suất

### Chiến lược Indexing
- **Primary Keys**: Tất cả bảng có primary key phù hợp
- **Foreign Keys**: Duy trì mối quan hệ cho tính toàn vẹn dữ liệu
- **Search Indexes**: Tối ưu cho tìm kiếm văn bản tiếng Nhật
- **Date Indexes**: Tối ưu cho phạm vi ngày y tế

### Mẫu Query
- **Read-Heavy**: Bảng dữ liệu master (tra cứu thường xuyên)
- **Write-Heavy**: Bảng dữ liệu vận hành (cập nhật hàng ngày)
- **Reporting**: Bảng tài liệu (join phức tạp cho báo cáo)

## Backup & Recovery

### Chiến lược Backup
- **Full Backup**: Hàng ngày lúc 2:00 AM JST
- **Incremental**: Mỗi 4 giờ trong giờ làm việc
- **Transaction Log**: Backup liên tục cho khôi phục point-in-time
- **Retention**: 30 ngày online, 7 năm lưu trữ (yêu cầu y tế)

### Quy trình Recovery
- **RTO**: Tối đa 4 giờ
- **RPO**: Tối đa 15 phút
- **Lịch Test**: Test khôi phục hàng tháng
- **Tài liệu**: Runbook khôi phục hoàn chỉnh

## Cửa sổ Bảo trì

### Bảo trì Thường xuyên
- **Hàng tuần**: Tối ưu index (Chủ nhật 1:00 AM JST)
- **Hàng tháng**: Cập nhật thống kê (Chủ nhật đầu tiên 2:00 AM JST)
- **Hàng quý**: Kiểm tra sức khỏe database toàn diện

### Quy trình Khẩn cấp
- **Database Corruption**: Failover tự động sang standby
- **Vấn đề Hiệu suất**: Quy trình tối ưu query
- **Vấn đề Dung lượng**: Cleanup và lưu trữ tự động

## Hướng dẫn Phát triển

### Quy ước Đặt tên
- **Tables**: `prefix_mô_tả` (mst_, dat_, doc_)
- **Columns**: `snake_case` với ngữ cảnh tiếng Nhật
- **Indexes**: `idx_tên_bảng_cột`
- **Foreign Keys**: `fk_tên_bảng_bảng_tham_chiếu`

### Tiêu chuẩn Code
- **Chỉ PDO**: Không SQL trực tiếp trong ứng dụng
- **Prepared Statements**: Tất cả query sử dụng tham số
- **Quản lý Transaction**: Xử lý transaction phù hợp
- **Xử lý Lỗi**: Mẫu lỗi database nhất quán

## Monitoring & Cảnh báo

### Metrics Hiệu suất
- **Thời gian Response Query**: < 2 giây trung bình
- **Connection Pool**: < 80% sử dụng
- **Disk Usage**: < 85% dung lượng
- **Index Fragmentation**: < 30%

### Ngưỡng Cảnh báo
- **Critical**: Database không khả dụng, phát hiện corruption
- **Warning**: Thời gian response cao, giới hạn kết nối
- **Info**: Hoàn thành backup, cửa sổ bảo trì

## Ghi chú Data Dictionary

### Cân nhắc Đặc biệt
- **Văn bản Nhật**: Tất cả text field hỗ trợ bộ ký tự tiếng Nhật đầy đủ
- **Định dạng Ngày**: Tiêu chuẩn ngày Nhật Bản (年月日)
- **Time Zones**: Tất cả timestamp trong JST
- **Collation**: Cài đặt collation phù hợp tiếng Nhật

### Quy tắc Kinh doanh
- **Quyền riêng tư Bệnh nhân**: Kiểm soát truy cập nghiêm ngặt theo luật Nhật Bản
- **Yêu cầu Audit**: Tất cả thay đổi được log với user/timestamp
- **Lưu trữ Dữ liệu**: Tối thiểu 7 năm cho hồ sơ y tế
- **Tích hợp**: API được thiết kế cho tiêu chuẩn y tế Nhật Bản

## Giải thích thuật ngữ tiếng Nhật

### Từ khóa cốt lõi:
- **看多機** (Kantaki) = Điều dưỡng đa chức năng
- **利用者** (Riyōsha) = Người sử dụng dịch vụ
- **従業員** (Jūgyōin) = Nhân viên
- **事業所** (Jigyōsho) = Văn phòng kinh doanh
- **予定** (Yotei) = Lịch trình/kế hoạch
- **実績** (Jisseki) = Thành tích/kết quả thực tế
- **記録** (Kiroku) = Hồ sơ/ghi chép
- **マスタ** (Master) = Dữ liệu master

### Dịch vụ y tế:
- **訪問看護** (Hōmon Kango) = Điều dưỡng thăm khám
- **加減算** (Kagensan) = Cộng trừ (điều chỉnh phí)
- **実費** (Jippi) = Chi phí thực tế
- **保険** (Hoken) = Bảo hiểm
- **保険外** (Hoken-gai) = Ngoài bảo hiểm
- **巡回事業所** (Junkai Jigyōsho) = Văn phòng tuần hoàn

### Y tế chuyên môn:
- **服薬** (Fukuyaku) = Uống thuốc
- **排泄** (Haisetsu) = Bài tiết
- **バイタル** (Vital) = Dấu hiệu sinh tồn
- **水分摂取** (Suibun Sesshu) = Lượng nước tiêu thụ
- **褥瘡** (Jokusō) = Loét do nằm lâu
- **病名** (Byōmei) = Tên bệnh

### Ghi chú quan trọng:
- **dat_user_record_add** có comment sai: nên là "利用者実績(加減算)" thay vì "利用者予定(加減算)"
- Các bảng backup: mst_add.20240122, mst_user.20240123
- Bảng không có comment: cache, jobs, migrations, bank, corporate, h2_corporate, doc_visit1
