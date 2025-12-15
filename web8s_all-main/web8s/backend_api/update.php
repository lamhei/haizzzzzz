<?php
// 1. Cấu hình Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
// Thường dùng POST hoặc PUT cho Update. Ở đây ta dùng POST cho đơn giản.
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 2. Thông tin kết nối Database
$servername = "localhost";
$username = "root";       
$password = "";           
$dbname = "db_nhanluc";   
$table_name = "user";     

// 3. Xử lý dữ liệu đầu vào (Nhận JSON từ Frontend)
$data = json_decode(file_get_contents("php://input"), true);

// Lấy các trường dữ liệu, bao gồm ID (rất quan trọng)
$id = $data['id'] ?? null; // ID là cần thiết để biết sửa ai
$ho_ten = $data['ho_ten'] ?? null;
$nam_sinh = $data['nam_sinh'] ?? null;
$dia_chi = $data['dia_chi'] ?? null;
$chuong_trinh = $data['chuong_trinh'] ?? null;
$quoc_gia = $data['quoc_gia'] ?? null;
$sdt = $data['sdt'] ?? null;
$ghi_chu = $data['ghi_chu'] ?? null;

if (empty($id)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Thiếu ID người dùng để cập nhật.", "status" => false));
    exit();
}

// 4. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500); 
    die(json_encode(array("message" => "Lỗi kết nối Database.", "status" => false)));
}

// 5. Chuẩn bị và Thực thi truy vấn UPDATE
$sql = "UPDATE $table_name 
        SET ho_ten=?, nam_sinh=?, dia_chi=?, chuong_trinh=?, quoc_gia=?, sdt=? , ghi_chu=?
        WHERE id=?";

$stmt = $conn->prepare($sql);

// "ssssssi" : 6 tham số string và 1 tham số integer (id)
$stmt->bind_param("sssssssi", $ho_ten, $nam_sinh, $dia_chi, $chuong_trinh, $quoc_gia, $sdt, $ghi_chu, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(array("message" => "Cập nhật người dùng ID $id thành công.", "status" => true));
    } else {
        // Có thể ID không tồn tại hoặc không có gì thay đổi
        http_response_code(404);
        echo json_encode(array("message" => "Không tìm thấy ID $id hoặc không có thay đổi nào.", "status" => false));
    }
} else {
    http_response_code(500);
    echo json_encode(array("message" => "Lỗi thực thi SQL: " . $stmt->error, "status" => false));
}

// 6. Đóng kết nối
$stmt->close();
$conn->close();

?>