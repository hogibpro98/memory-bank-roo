# Bảng Hệ thống - KANTAKI-WIZ

## Tổng quan
- **Số lượng**: 7 bảng
- **Mục đích**: Quản lý framework và hạ tầng
- **Đặc điểm**: Hầu hết không có comment tiếng Nhật

## Danh sách Bảng

| Tên Bảng | Comment | Mô tả | Chức năng |
|-----------|---------|-------|-----------|
| `cache` | (trống) | Lưu trữ cache ứng dụng | Caching hệ thống cho performance |
| `cache_locks` | (trống) | Cơ chế khóa cache | Quản lý lock để tránh race condition |
| `csv_imports` | (trống) | Import dữ liệu CSV | Theo dõi việc import dữ liệu từ CSV |
| `failed_jobs` | (trống) | Jobs thất bại nền | Lưu trữ các background job thất bại |
| `job_batches` | (trống) | Xử lý batch job | Quản lý batch processing |
| `jobs` | (trống) | Quản lý queue job | Queue system cho background tasks |
| `migrations` | (trống) | Versioning schema database | Lịch sử thay đổi cấu trúc database |

## Đặc điểm Kỹ thuật

### Framework Components
- **Laravel/Symfony inspired**: Các bảng này có vẻ được thiết kế theo pattern của framework hiện đại
- **Queue System**: `jobs`, `failed_jobs`, `job_batches` tạo thành hệ thống queue hoàn chỉnh
- **Cache Layer**: `cache`, `cache_locks` hỗ trợ caching với thread-safety

### Mục đích Sử dụng

#### Cache System
- **`cache`**: Lưu trữ dữ liệu cache để tăng performance
- **`cache_locks`**: Đảm bảo thread-safety khi multiple processes truy cập cache

#### Job Queue System
- **`jobs`**: Queue chính cho background tasks
- **`failed_jobs`**: Lưu trữ jobs thất bại để debug và retry
- **`job_batches`**: Nhóm nhiều jobs lại để xử lý batch

#### Data Management
- **`csv_imports`**: Tracking việc import dữ liệu từ CSV files
- **`migrations`**: Version control cho database schema

## Patterns & Best Practices

### Caching Strategy
```php
// Ví dụ sử dụng cache tables
$cached_data = Cache::remember('user_list', 3600, function() {
    return DB::table('mst_user')->get();
});
```

### Job Queue Usage
```php
// Ví dụ sử dụng job queue
dispatch(new ProcessHealthcareReport($patient_id));
```

### Migration Tracking
- Mỗi lần thay đổi schema được ghi lại trong `migrations`
- Đảm bảo consistency giữa các environments

## Monitoring & Maintenance

### Performance Metrics
- **Cache Hit Rate**: Monitor hiệu quả của caching
- **Queue Processing Time**: Theo dõi thời gian xử lý jobs
- **Failed Job Rate**: Tỷ lệ jobs thất bại

### Cleanup Procedures
- **Cache**: Tự động cleanup cache cũ
- **Jobs**: Archive hoặc xóa jobs đã hoàn thành
- **Failed Jobs**: Regular review và retry

## Security Considerations

### Access Control
- Các bảng hệ thống không chứa dữ liệu nhạy cảm
- Cần bảo vệ khỏi direct access từ application layer

### Backup Strategy
- **Priority**: Medium (không chứa business data)
- **Frequency**: Backup cùng với database chính
- **Recovery**: Có thể rebuild từ application nếu cần

## Troubleshooting

### Common Issues
1. **Cache Lock Timeout**: Khi `cache_locks` bị stuck
2. **Queue Backlog**: Khi `jobs` tích tụ quá nhiều
3. **Failed Job Accumulation**: `failed_jobs` tăng đột biến

### Debug Commands
```sql
-- Kiểm tra cache usage
SELECT COUNT(*) as cache_entries FROM cache;

-- Kiểm tra pending jobs
SELECT COUNT(*) as pending_jobs FROM jobs;

-- Kiểm tra failed jobs
SELECT COUNT(*) as failed_jobs FROM failed_jobs;
```

## Integration Notes

### Framework Integration
- Tích hợp với PHP framework (có thể Laravel/Symfony)
- Sử dụng ORM/QueryBuilder patterns
- Compatible với standard PHP caching libraries

### Healthcare Specific Usage
- Queue processing cho healthcare reports
- Cache patient data với appropriate TTL
- CSV import cho bulk healthcare data

---

> 💡 **Lưu ý**: Các bảng hệ thống này không chứa comment tiếng Nhật vì chúng là infrastructure tables, không liên quan trực tiếp đến business logic healthcare.