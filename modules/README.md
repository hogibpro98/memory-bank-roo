# Features Documentation - Chức năng màn hình

Thư mục này chứa tài liệu chi tiết cho từng chức năng/màn hình trong hệ thống PHP Business Management.

## Cấu trúc thư mục

```
features/
├── README.md                    # File này - hướng dẫn sử dụng
├── feature_template.md          # Template cho việc document một chức năng
├── system/                      # Chức năng quản lý hệ thống
├── record/                      # Chức năng ghi chép dữ liệu
├── schedule/                    # Chức năng lịch trình
├── place/                       # Chức năng quản lý địa điểm
├── api/                         # Chức năng API endpoints
└── common/                      # Chức năng chung/tiện ích
```

## Cách sử dụng

### Tạo tài liệu cho một chức năng mới:
1. Copy [`feature_template.md`](feature_template.md:1) 
2. Rename thành tên chức năng (ví dụ: `kantaki_record.md`)
3. Điền thông tin chi tiết theo template
4. Đặt vào thư mục phù hợp

### Naming Convention:
- **File names**: lowercase với underscores (`user_management.md`)
- **Folder names**: theo module structure (`system/`, `record/`)
- **Feature IDs**: sử dụng module prefix (ví dụ: `SYS-01`, `REC-01`)

## Các loại chức năng cần document:

### 🖥️ Screen Functions (Chức năng màn hình):
- **CRUD Operations**: Create, Read, Update, Delete
- **Dialog Systems**: Modal dialogs và popup forms
- **List Views**: Danh sách và bảng dữ liệu
- **Detail Views**: Chi tiết bản ghi
- **Search/Filter**: Tìm kiếm và lọc dữ liệu

### 🔄 Business Logic (Logic nghiệp vụ):
- **Validation Rules**: Quy tắc kiểm tra dữ liệu
- **Workflow**: Quy trình xử lý
- **Calculations**: Tính toán và công thức
- **Integration**: Tích hợp giữa các module

### 📊 Reporting Features (Chức năng báo cáo):
- **Excel Export**: Xuất Excel với template
- **CSV Operations**: Import/Export CSV
- **Print Functions**: In ấn và PDF
- **Dashboard**: Bảng điều khiển

### 🔧 Technical Features (Chức năng kỹ thuật):
- **AJAX Endpoints**: API calls và responses
- **File Operations**: Upload, download, processing
- **Authentication**: Đăng nhập và phân quyền
- **Error Handling**: Xử lý lỗi

## Template Structure

Mỗi feature document sẽ bao gồm:

1. **Overview**: Mô tả tổng quan chức năng
2. **User Interface**: Giao diện người dùng
3. **Business Logic**: Logic xử lý
4. **Technical Implementation**: Cài đặt kỹ thuật
5. **Database Schema**: Cấu trúc database liên quan
6. **API Endpoints**: Các endpoint liên quan
7. **Testing**: Hướng dẫn test
8. **Troubleshooting**: Xử lý sự cố

## Lợi ích của việc document features:

- ✅ **Hiểu rõ chức năng**: Mỗi màn hình làm gì, hoạt động ra sao
- ✅ **Debug dễ dàng**: Khi có lỗi biết tìm ở đâu
- ✅ **Maintain code**: Sửa đổi không làm hỏng logic khác
- ✅ **Onboard nhanh**: Developer mới hiểu nhanh hệ thống
- ✅ **Documentation**: Tài liệu cho user và admin
- ✅ **Testing guide**: Hướng dẫn test từng chức năng

## Priority Features để document:

### High Priority:
1. **Kantaki Record** - API No.5 (Đã có tài liệu)
2. **User Management** - record/user/
3. **Staff Management** - record/staff/
4. **Corporate Management** - system/corporate_*
5. **Authentication System** - auth/

### Medium Priority:
6. **CSV Import/Export** - system/csv_import/, place/csv/
7. **Excel Reports** - excel/018, excel/019
8. **Schedule Management** - schedule/
9. **Place Cooperation** - place/cooperate/
10. **Image Management** - image/

### Low Priority:
11. **News Management** - place/news*
12. **Batch Operations** - batch/
13. **Upload System** - upload/
14. **Debug Tools** - debug/
15. **Migration Tools** - migration/

## Cập nhật Features:

Khi có thay đổi trong code, cần cập nhật tài liệu:
- **Code changes**: Cập nhật technical implementation
- **UI changes**: Cập nhật user interface section
- **Business logic changes**: Cập nhật business rules
- **Database changes**: Cập nhật database schema