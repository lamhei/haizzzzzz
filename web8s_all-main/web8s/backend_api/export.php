<?php
// 1. Cấu hình Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');

// 2. Thông tin kết nối Database
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "db_nhanluc";
$table_name = "user";

// 3. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);
// Thiết lập charset để đảm bảo hiển thị tiếng Việt
$conn->set_charset("utf8mb4"); 

if ($conn->connect_error) {
http_response_code(500);
die(json_encode(array("message" => "Lỗi kết nối Database.", "status" => false)));
}

// 4. Thực thi truy vấn SELECT
$sql = "SELECT id, ngay_nhan, ho_ten, nam_sinh, dia_chi, chuong_trinh, quoc_gia, sdt, ghi_chu FROM $table_name ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 5. Chuẩn bị nội dung cho file TXT
$txt_content = "DANH SÁCH NGƯỜI DÙNG ICOGROUP\n";
$txt_content .= "------------------------------------------------------------------------------------------------------------------------------------------------\n";

    // Header cột (sử dụng tab \t để phân tách dễ nhìn hơn)
$txt_content .= "ID\tNGÀY NHẬN\t\t\tHỌ TÊN\t\tSĐT\t\tNĂM SINH\tĐỊA CHỈ\t\tCHƯƠNG TRÌNH\tQUỐC GIA\tGHI CHÚ\n";
$txt_content .= "------------------------------------------------------------------------------------------------------------------------------------------------\n";

while($row = $result->fetch_assoc()) {
        // Lấy dữ liệu và thay thế ký tự tab hoặc xuống dòng trong nội dung để không làm hỏng định dạng file
$row_data = array(
$row['id'],
 $row['ngay_nhan'],
 str_replace(["\n", "\t"], ' ', $row['ho_ten']),
 $row['sdt'],
 $row['nam_sinh'] ?: '-',
 str_replace(["\n", "\t"], ' ', $row['dia_chi']) ?: '-',
 str_replace(["\n", "\t"], ' ', $row['chuong_trinh']) ?: '-',
str_replace(["\n", "\t"], ' ', $row['quoc_gia']) ?: '-',
 str_replace(["\n", "\t"], ' ', $row['ghi_chu']) ?: '-'
 );
$txt_content .= implode("\t", $row_data) . "\n";
}

    // 6. Lưu nội dung vào file
$file_name = 'user_export_' . date('Ymd_His') . '.txt';
$file_path = __DIR__ . '/exports/' . $file_name; // Lưu vào thư mục 'exports' bên trong thư mục API
    // Tạo thư mục 'exports' nếu chưa tồn tại
if (!is_dir(__DIR__ . '/exports/')) {
 mkdir(__DIR__ . '/exports/', 0777, true);
 }

    // Ghi dữ liệu vào file (UTF-8 BOM cho tương thích tốt hơn với các trình soạn thảo cũ)
$success = file_put_contents($file_path, "\xEF\xBB\xBF" . $txt_content); // Thêm BOM cho UTF-8

if ($success) {
 http_response_code(200);
        // Trả về đường dẫn để client có thể tải xuống
echo json_encode(array("message" => "Xuất file thành công!", "status" => true, "file_url" => "backend_api/exports/" . $file_name));
} else {
http_response_code(500);
echo json_encode(array("message" => "Lỗi ghi file.", "status" => false));
 }

} else {
http_response_code(200);
echo json_encode(array("message" => "Không có dữ liệu để xuất.", "status" => true));
}

$conn->close();
?>