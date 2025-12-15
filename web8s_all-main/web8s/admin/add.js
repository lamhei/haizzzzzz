// --- BẢO MẬT: KIỂM TRA TRẠNG THÁI ĐĂNG NHẬP ---
function checkAuth() {
    // Kiểm tra xem biến 'adminLoggedIn' có tồn tại và là 'true' trong Local Storage không
    if (localStorage.getItem('adminLoggedIn') !== 'true') {
        // Nếu chưa đăng nhập, chuyển hướng đến trang login.html
        window.location.href = 'loginadmin.html';
    }
}

// Hàm Đăng Xuất
function logoutAdmin() {
    localStorage.removeItem('adminLoggedIn'); // Xóa trạng thái đăng nhập
    alert('Bạn đã đăng xuất thành công.');
    window.location.href = 'home.html'; // Chuyển hướng về trang đăng nhập
}

// Gọi hàm kiểm tra mỗi khi trang admin.html được tải
checkAuth(); 
// --- HẾT PHẦN BẢO MẬT ---


document.addEventListener('DOMContentLoaded', function() {
    const topNavButtons = document.querySelectorAll('.main-nav .nav-item');
    const sidebarMenus = document.querySelectorAll('.menu-list');
    const sidebarMenuItems = document.querySelectorAll('.sidebar .menu-item');
    const contentViews = document.querySelectorAll('.content-view');
    const logoutButton = document.querySelector('.user-control .nav-item');
    const searchInput = document.getElementById('searchInput');
    let allUsers = []; // **Thêm biến này để lưu toàn bộ dữ liệu**
    


    // Gắn chức năng đăng xuất vào nút
    logoutButton.addEventListener('click', logoutAdmin);

    // Hàm chuyển đổi giữa các menu chính (Dữ liệu/Web)
    function switchMainMenu(targetContent) {
        // 1. Cập nhật nút trên Top Bar
        topNavButtons.forEach(btn => btn.classList.remove('active'));
        const targetBtn = document.querySelector(`.main-nav button[data-content="${targetContent}"]`);
        if(targetBtn) {
            targetBtn.classList.add('active');
        }

        // 2. Cập nhật Menu Sidebar
        sidebarMenus.forEach(menu => menu.classList.remove('active-menu'));
        const activeMenu = document.getElementById(`${targetContent}-menu`);
        if (activeMenu) {
            activeMenu.classList.add('active-menu');
            
            // 3. Tự động chọn mục đầu tiên của menu mới
            const firstItem = activeMenu.querySelector('.menu-item');
            if (firstItem) {
                // Kích hoạt mục menu sidebar đầu tiên
                sidebarMenuItems.forEach(item => item.classList.remove('active'));
                firstItem.classList.add('active');
                
                // Hiển thị nội dung tương ứng
                const firstContentView = document.getElementById(firstItem.dataset.view);
                contentViews.forEach(view => view.classList.remove('active-view'));
                if (firstContentView) {
                    firstContentView.classList.add('active-view');
                }
            }
        }
    }

    // Lắng nghe sự kiện click trên Top Bar
    topNavButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-content');
            switchMainMenu(target);
        });
    });

    // Lắng nghe sự kiện click trên Menu Sidebar
    sidebarMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            // 1. Cập nhật trạng thái active của Sidebar Menu Item
            sidebarMenuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // 2. Hiển thị Content View tương ứng
            const targetView = this.getAttribute('data-view');
            contentViews.forEach(view => view.classList.remove('active-view'));
            const activeView = document.getElementById(targetView);
            if (activeView) {
                activeView.classList.add('active-view');
            }
        });
    });

    // Khởi tạo trạng thái ban đầu (ví dụ: "Quản lý Dữ liệu" được chọn)
    switchMainMenu('data-management');
});