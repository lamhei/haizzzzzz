<?php
// 1. Cấu hình Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');

// Đảm bảo múi giờ được đặt đúng để hàm date() trả về thời gian hiện tại
date_default_timezone_set('Asia/Ho_Chi_Minh'); 

// 2. Thông tin kết nối Database
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "db_nhanluc";
$table_name = "user";

// 3. Khởi tạo Kích thước cột tối thiểu (minimum starting width)
$columns = [
    'id'            => ['title' => 'ID', 'width' => 2], 
    'ngay_nhan'     => ['title' => 'NGÀY NHẬN', 'width' => 9], 
    'ho_ten'        => ['title' => 'HỌ TÊN', 'width' => 15], 
    'sdt'           => ['title' => 'SĐT', 'width' => 3], 
    'nam_sinh'      => ['title' => 'NĂM SINH', 'width' => 8], 
    'dia_chi'       => ['title' => 'ĐỊA CHỈ', 'width' => 7], 
    'chuong_trinh'  => ['title' => 'CHƯƠNG TRÌNH', 'width' => 12], 
    'quoc_gia'      => ['title' => 'QUỐC GIA', 'width' => 8], 
    'ghi_chu'       => ['title' => 'GHI CHÚ', 'width' => 10], 
];

// Hàm tính chiều dài chuỗi tiếng Việt an toàn
function mb_strlen_safe($str) {
    return mb_strlen((string)$str, 'UTF-8');
}

// Hàm tạo đường kẻ ngang (dùng '-' và '+')
function create_horizontal_separator($columns) {
    $line = '+';
    foreach ($columns as $col) {
        $line .= str_repeat('-', $col['width']) . '+';
    }
    return $line . "\n";
}

// 4. Kết nối Database
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4"); 

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(array("message" => "Lỗi kết nối Database.", "status" => false)));
}

// 5. Thực thi truy vấn SELECT
$sql = "SELECT id, ngay_nhan, ho_ten, nam_sinh, dia_chi, chuong_trinh, quoc_gia, sdt, ghi_chu FROM $table_name ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $all_rows = [];
    $processed_rows = [];
    
    $PADDING = 4; 

    // -----------------------------------------------------------
    // BƯỚC 1: QUÉT DỮ LIỆU & TÍNH TOÁN ĐỘ RỘNG TỐI ĐA THỰC TẾ
    // -----------------------------------------------------------
    
    while($row = $result->fetch_assoc()) {
        $all_rows[] = $row;
    }
    
    // 1. Khởi tạo độ rộng tối đa dựa trên Tiêu đề cột
    foreach ($columns as $key => &$col) {
        // Độ rộng cột TỐI THIỂU = Độ dài Tiêu đề + PADDING
        $col['width'] = max($col['width'], mb_strlen_safe($col['title'])) + $PADDING; 
    }
    unset($col);
    
    foreach ($all_rows as $row) {
        $processed_row = [];

        // Định dạng Ngày
        $ngay_nhan_data = empty($row['ngay_nhan']) ? '-' : (new DateTime($row['ngay_nhan']))->format('Y-m-d H:i:s');
        $processed_row['ngay_nhan'] = $ngay_nhan_data;
        
        // 2. Cập nhật độ rộng tối đa cho cột Ngày Nhận
        $columns['ngay_nhan']['width'] = max($columns['ngay_nhan']['width'], mb_strlen_safe($ngay_nhan_data) + $PADDING);

        // Lặp qua các cột còn lại để làm sạch và tính độ rộng
        foreach ($columns as $key => &$col) {
            if ($key === 'ngay_nhan') continue;
            
            $raw_data = $row[$key];
            $clean_data = empty($raw_data) ? '-' : str_replace(["\n", "\t"], ' ', $raw_data);
            
            $processed_row[$key] = $clean_data;
            
            // 3. Cập nhật độ rộng tối đa cho cột
            $col['width'] = max($col['width'], mb_strlen_safe($clean_data) + $PADDING); 
        }
        $processed_rows[] = $processed_row;
        unset($col);
    }
    
    // -----------------------------------------------------------
    // BƯỚC 2: IN NỘI DUNG VỚI ĐỘ RỘNG ĐÃ TÍNH TOÁN VÀ KHUNG BẢNG
    // -----------------------------------------------------------
    
    // Tổng độ rộng thực tế
    $TOTAL_WIDTH = array_sum(array_column($columns, 'width')) + count($columns) + 1; 
    
    $date_export = date('Y-m-d H:i:s'); 
    $line_delimiter_table = create_horizontal_separator($columns); 
    
    // Tiêu đề báo cáo
    $title_line = str_pad("DANH SÁCH NGƯỜI DÙNG ICOGROUP", $TOTAL_WIDTH, " ", STR_PAD_BOTH) . "\n";
    
    // Dòng thời gian
    $time_text = "Thời gian xuất file: " . $date_export;
    $time_line = str_pad($time_text, $TOTAL_WIDTH, " ", STR_PAD_RIGHT) . "\n\n";
    
    
    // Bắt đầu nội dung
    $txt_content = $title_line;
    $txt_content .= $time_line;
    
    $txt_content .= $line_delimiter_table; // Khung trên cùng

    // In Header cột (Căn lề trái)
    $header_line = '|';
    foreach ($columns as $key => $col) {
        $header_line .= str_pad(" " . $col['title'], $col['width'], " ", STR_PAD_RIGHT) . '|';
    }
    $txt_content .= $header_line . "\n";
    $txt_content .= $line_delimiter_table; // Đường kẻ sau header

    // In Dữ liệu từng hàng (Căn lề trái)
    foreach ($processed_rows as $p_row) {
        $data_line = '|';
        foreach ($columns as $key => $col) {
            $data_line .= str_pad(" " . $p_row[$key], $col['width'], " ", STR_PAD_RIGHT) . '|';
        }
        $txt_content .= $data_line . "\n";
    }

    // Footer đóng khung
    $txt_content .= $line_delimiter_table;
    $txt_content .= str_pad("--- HẾT DỮ LIỆU ---", $TOTAL_WIDTH, " ", STR_PAD_BOTH) . "\n";


    // 7. Lưu nội dung vào file
    $file_name = 'user_export_' . date('Ymd_His') . '.txt';
    $file_path = __DIR__ . '/exports/' . $file_name;
    
    if (!is_dir(__DIR__ . '/exports/')) {
        mkdir(__DIR__ . '/exports/', 0777, true);
    }

    // Ghi dữ liệu vào file (Thêm BOM cho UTF-8 để hiển thị tiếng Việt chính xác)
    $success = file_put_contents($file_path, "\xEF\xBB\xBF" . $txt_content); 

    if ($success) {
        http_response_code(200);
        echo json_encode(array("message" => "Xuất file thành công!", "status" => true, "file_url" => "backend_api/exports/" . $file_name));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Lỗi ghi file. Vui lòng kiểm tra quyền ghi của thư mục exports.", "status" => false));
    }

} else {
    http_response_code(200);
    echo json_encode(array("message" => "Không có dữ liệu để xuất.", "status" => true));
}

$conn->close();
?>