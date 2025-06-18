# API Documentation Guidelines

## Tổng quan
Thư mục này chứa tài liệu phân tích chi tiết cho các API của hệ thống KANTAKI-WIZ. Mỗi tài liệu tuân theo một cấu trúc chuẩn để đảm bảo tính nhất quán và dễ dàng tra cứu.

## Cấu trúc Template (api_template.md)

### 1. Phân tích từ api_no4.md
Template được xây dựng dựa trên cấu trúc của `api_no4.md` với các đặc điểm chính:

#### **Cấu trúc 8 phần chính:**
1. **Tổng quan API** - Thông tin cơ bản về file và chức năng
2. **Phân tích Logic Database** - Input params và cấu trúc bảng
3. **Logic Truy vấn Chi tiết** - Các SQL queries được sử dụng
4. **Mapping Data** - Bảng mapping giữa UI và database
5. **Bảng mã chung** - Sử dụng mst_code cho dropdown
6. **Truy vấn năm 2024** - Validation và filter theo năm
7. **Lưu ý đặc biệt** - Logic business đặc thù
8. **Kết quả thực tế** - Test data và sample responses

#### **Điểm mạnh của cấu trúc:**
- **Toàn diện**: Bao phủ từ input params đến response data
- **Chi tiết**: Bao gồm cả line numbers và SQL queries
- **Thực tế**: Có dữ liệu test thực từ database
- **Chuẩn hóa**: Cấu trúc nhất quán, dễ tra cứu
- **Validation**: Rõ ràng về điều kiện năm 2024

## Quy tắc Đặt tên File

```
api_no[X].md
```
- `X`: Số thứ tự tăng dần (1, 2, 3, 4, 5...)
- Tên file ngắn gọn, dễ sắp xếp theo thứ tự

## Cấu trúc Thông tin Bắt buộc

### Header Information
```markdown
# API No.[X] - [Tên chức năng Nhật] ([Tên chức năng Việt])

## Tổng quan API
- **File**: `[đường dẫn PHP file]`
- **Màn hình**: `[đường dẫn UI file]`  
- **Chức năng**: [Mô tả ngắn gọn]
```

### Database Analysis
- **Input Parameters**: Liệt kê đầy đủ params và mô tả
- **Bảng chính**: Xác định bảng chính và bảng phụ
- **Bảng liên quan**: List tất cả bảng được JOIN
- **SQL Queries**: Copy chính xác từ code, kèm line numbers

### UI Mapping Table
```markdown
| Tên hiển thị | Logic code hiển thị | Tên trường | Tên bảng |
|--------------|-------------------|------------|----------|
```
- **Nhóm theo section**: Thông tin cơ bản, thời gian, staff...
- **Logic code**: Copy chính xác từ PHP template
- **Trường mapping**: Chính xác tên column trong DB

### 2024 Validation
- **Queries filter 2024**: Tất cả SQL phải có điều kiện năm
- **Validation logic**: PHP code kiểm tra điều kiện
- **Test data**: Dữ liệu thực từ database năm 2024

## Quy trình Tạo Tài liệu Mới

### Bước 1: Chuẩn bị
1. Copy `api_template.md` thành `api_no[X].md`
2. Đọc code PHP của API cần phân tích
3. Xác định các bảng database liên quan

### Bước 2: Phân tích Code
1. **Tìm input parameters** (thường ở đầu file)
2. **Trace database queries** (search SELECT, INSERT, UPDATE)
3. **Xác định bảng chính** (FROM clause đầu tiên)
4. **List bảng liên quan** (các JOIN statements)
5. **Note functions** (getStaffName, getCode, etc.)

### Bước 3: Mapping UI
1. **Đọc file template PHP/HTML** tương ứng
2. **Tìm các `<?= h($dispData[''])` pattern**
3. **Tạo bảng mapping** UI field → DB field
4. **Nhóm theo logic** (basic info, time, staff, etc.)

### Bước 4: Database Testing
1. **Chạy queries thực tế** trên database
2. **Lấy sample data** cho test cases
3. **Validate điều kiện 2024** 
4. **Tạo expected responses**

### Bước 5: Hoàn thiện
1. **Review tính chính xác** của SQL và mapping
2. **Kiểm tra format** theo template
3. **Thêm lưu ý đặc biệt** nếu có
4. **Test API calls** với sample data

## Tiêu chuẩn Chất lượng

### ✅ Bắt buộc có:
- [ ] Đầy đủ 8 sections theo template
- [ ] SQL queries chính xác với line numbers
- [ ] UI mapping table hoàn chỉnh
- [ ] Filter điều kiện năm 2024
- [ ] Sample test data thực tế
- [ ] Expected response format

### ✅ Chất lượng cao:
- [ ] Giải thích logic business đặc thù
- [ ] Mô tả relationship giữa các bảng
- [ ] Troubleshooting common issues
- [ ] Performance considerations
- [ ] Security notes (encryption, validation)

### ❌ Tránh:
- Sao chép thông tin không chính xác
- Bỏ qua validation logic
- Mapping sai tên trường
- Thiếu điều kiện lọc năm 2024
- Response format không đúng thực tế

## Best Practices

### 1. Accuracy First
- **Copy chính xác** SQL queries từ code
- **Verify** tên bảng và tên trường với database schema
- **Test** queries thực tế trước khi document

### 2. Consistency
- **Sử dụng format** giống nhau cho tất cả documents
- **Naming convention** cho sections và subsections
- **Table structure** chuẩn cho UI mapping

### 3. Completeness  
- **Không bỏ qua** bất kỳ query nào trong code
- **Document** tất cả input parameters
- **Include** error handling logic nếu có

### 4. Practical Value
- **Sample data** phải thực tế và test được
- **URL examples** phải chạy được
- **Expected responses** match với thực tế

## Maintenance

### Khi nào cần update:
- Code PHP thay đổi logic
- Database schema changes
- Business rules mới
- Security requirements thay đổi

### Review Schedule:
- **Quarterly**: Kiểm tra tính chính xác của sample data
- **When updated**: Code changes trigger documentation update
- **Annual**: Full review của tất cả API docs

## Templates và Tools

### Available Templates:
- `api_template.md` - Template chính cho API documentation
- Sample files: `api_no4.md`, `api_no5.md`

### Recommended Tools:
- **MySQL Workbench**: Để test SQL queries
- **Postman**: Để test API endpoints  
- **VS Code**: Với markdown preview để review format
- **Database browser**: Để verify schema và data

---

## Contact & Support

Để hỗ trợ tạo documentation hoặc câu hỏi về template:
1. Review existing samples (`api_no4.md`)
2. Check template (`api_template.md`)
3. Follow guidelines trong file này
4. Test thoroughly trước khi commit

**Remember**: Documentation quality directly impacts development efficiency và maintenance ease!