<?php
// 1. Cấu hình Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');
// Không cần nhận dữ liệu, chỉ cần gửi
// header('Access-Control-Allow-Headers: ...'); 

// 2. Thông tin kết nối Database
$servername = "localhost";
$username = "root";       
$password = "";           
$dbname = "db_nhanluc";   
$table_name = "user";     

// 3. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(array("message" => "Lỗi kết nối Database.", "status" => false)));
}

// 4. Thực thi truy vấn SELECT
// Lấy tất cả các cột. Nên sắp xếp theo ID giảm dần để thấy người mới nhất trước.
$sql = "SELECT id, ngay_nhan, ho_ten, nam_sinh, dia_chi, chuong_trinh, quoc_gia, sdt, ghi_chu FROM $table_name ORDER BY id DESC";

$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
    // 5. Lặp qua các kết quả và đưa vào mảng
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    // Trả về dữ liệu thành công
    http_response_code(200);
    echo json_encode($data);
} else {
    // Không tìm thấy dữ liệu
    http_response_code(200);
    echo json_encode(array("message" => "Không tìm thấy người dùng nào.", "status" => true, "data" => []));
}

// 6. Đóng kết nối
$conn->close();

?>