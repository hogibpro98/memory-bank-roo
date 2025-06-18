# Bảng Log & Hỗ trợ - KANTAKI-WIZ

## Tổng quan
- **Số lượng**: 2 bảng 
- **Mục đích**: Logging và audit trail hệ thống
- **Đặc điểm**: Critical cho security và troubleshooting

## Danh sách Bảng

| Tên Bảng | Comment | Mô tả |
|-----------|---------|-------|
| `log_entry` | 更新ログ | Log cập nhật |
| `log_error` | エラーログ | Log lỗi |

## Chi tiết Cấu trúc Bảng

### 1. `log_entry` - 更新ログ (Log cập nhật)

Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
user_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 利用者ID |
screen | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  | 対象画面・機能 |
entry_data | text | utf8mb4_general_ci | YES |  | NULL |  | 更新データ |
corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Mục đích & Chức năng:**
- **Activity Logging**: Ghi lại tất cả các hoạt động cập nhật trong hệ thống
- **User Tracking**: Theo dõi người dùng thực hiện các thao tác (`user_id`)
- **Screen Identification**: Xác định màn hình/chức năng được sử dụng (`screen`)
- **Data Recording**: Lưu trữ dữ liệu cập nhật chi tiết (`entry_data`)
- **Audit Trail**: Cung cấp đường mòn kiểm toán cho compliance

**Đặc điểm:**
- **Comprehensive Tracking**: Theo dõi toàn diện các hoạt động của người dùng
- **Healthcare Compliance**: Tuân thủ yêu cầu audit cho ngành y tế
- **Data Integrity**: Bảo đảm tính toàn vẹn của thông tin log
- **Multi-corporate Support**: Hỗ trợ môi trường đa công ty

### 2. `log_error` - エラーログ (Log lỗi)

Field | Type | Collation | Null | Key | Default | Extra | Comment |
|---|---|---|---|---|---|---|---|
unique_id | varchar(20) | utf8mb4_general_ci | NO | PRI | NULL |  | 固有ID |
delete_flg | tinyint(1) | NULL | NO |  | NULL |  | 削除フラグ |
create_date | datetime | NULL | NO |  | NULL |  | 作成日時 |
create_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 作成者 |
update_date | datetime | NULL | NO |  | NULL |  | 更新日時 |
update_user | varchar(20) | utf8mb4_general_ci | NO |  | NULL |  | 更新者 |
target_page | varchar(256) | utf8mb4_general_ci | YES |  | NULL |  | 対象ソース |
message | varchar(100) | utf8mb4_general_ci | YES |  | NULL |  | エラー内容 |
entry_data | text | utf8mb4_general_ci | YES |  | NULL |  | エラー詳細 |
corporate_id | varchar(20) | utf8mb4_general_ci | YES |  | NULL |  |  |

**Mục đích & Chức năng:**
- **Error Tracking**: Theo dõi và ghi lại tất cả lỗi hệ thống
- **Source Identification**: Xác định nguồn gốc lỗi (`target_page`)
- **Error Categorization**: Phân loại lỗi theo nội dung (`message`)
- **Detailed Debugging**: Thông tin chi tiết để debug (`entry_data`)
- **System Monitoring**: Giám sát tình trạng hoạt động của hệ thống

**Đặc điểm:**
- **Critical System Health**: Quan trọng cho việc duy trì sức khỏe hệ thống
- **Debugging Support**: Hỗ trợ quá trình tìm và sửa lỗi
- **Performance Monitoring**: Giám sát hiệu suất và ổn định
- **Healthcare Reliability**: Đảm bảo độ tin cậy cho môi trường y tế

## Mối quan hệ và Tích hợp

### Log System Architecture
```
Healthcare Application
    ↓
User Actions → log_entry (Activity Tracking)
    ↓
System Errors → log_error (Error Management)
    ↓
Compliance & Monitoring Reports
```

### Ứng dụng trong Healthcare Environment

#### 1. **Audit Trail cho Medical Records**
- Mọi thay đổi đối với hồ sơ bệnh nhân được ghi lại trong `log_entry`
- Đáp ứng yêu cầu compliance của ngành y tế Nhật Bản
- Truy xuất được lịch sử thay đổi cho mục đích pháp lý

#### 2. **System Reliability**
- `log_error` giúp phát hiện sớm các vấn đề hệ thống
- Đảm bảo tính ổn định trong môi trường chăm sóc sức khỏe quan trọng
- Hỗ trợ maintenance và troubleshooting

#### 3. **Performance Analysis**
- Phân tích pattern sử dụng từ `log_entry`
- Tối ưu hóa workflow dựa trên dữ liệu thực tế
- Cải thiện user experience cho nhân viên y tế

#### 4. **Security Monitoring**
- Theo dõi các hoạt động bất thường
- Phát hiện potential security breaches
- Bảo vệ thông tin bệnh nhân nhạy cảm

### Technical Implementation Notes

#### **Data Retention Policy**
- Log data cần được lưu trữ theo quy định pháp lý
- Cân nhắc archiving cho dữ liệu cũ
- Backup strategy cho disaster recovery

#### **Performance Considerations**
- Index optimization cho queries thường xuyên
- Partitioning theo thời gian cho large datasets
- Monitoring disk space usage

#### **Security Measures**
- Encryption cho sensitive log data
- Access control cho log viewing
- Tamper-proof logging mechanisms
