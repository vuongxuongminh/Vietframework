<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                20/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */


/* Khởi tạo session */
ob_start();
session_name("session_id");
if (ini_get('session.use_cookies') && isset($_COOKIE['session_id'])) {
    $sessid = $_COOKIE['session_id'];
} elseif (!ini_get('session.use_only_cookies') && isset($_GET['session_id'])) {
    $sessid = $_GET['session_id'];
} else {
    session_start();
}
if (isset($sessid)) {
    if (!preg_match('/^([a-z0-9]{26})|([a-z0-9]{32})$/', $sessid)) {
        header("Location: /");
        die;
    }
    session_start();
}

/* Khởi tạo hằng mang giá trị là kí tự phân cách đường dẫn thư mục */
define("DS", DIRECTORY_SEPARATOR);

/* Khởi tạo hằng mang giá trị là đường dẫn thư mục trang */
define("DUONGDANTHUMUCTRANG", dirname(__FILE__) . DS);

/**
 * Hàm tự động nạp thư viện hỗ trợ cho việc truy xuất các lớp đối tượng cần sử dụng trong quá trình hệ thống chạy
 * 
 * @param string $tenLopDoiTuong (giá trị truyền vào hàm là chuỗi tên lớp đối tượng cần sử dụng)
 * @return boolean (trả về giá trị đúng hoặc sai)
 */
function tuDongNapThuVien($tenLopDoiTuong) {
    $duongDanThuVien = array(
        DUONGDANTHUMUCTRANG . "thuvien" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "hienthi" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "dieuhuong" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "xuly" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "phutro" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "tronang" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thuvien" . DS . "hethong" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "thietlap" . DS . $tenLopDoiTuong . ".php",
        DUONGDANTHUMUCTRANG . "tienich" . DS . $tenLopDoiTuong . ".php"
    );
    foreach ($duongDanThuVien as $duongDanDoiTuong) {
        if (file_exists($duongDanDoiTuong)) {
            require_once($duongDanDoiTuong);
            return true;
        }
    }
    return false;
}

/* Thực hiện khởi động chế độ tự động nạp thư viện hệ thống thông qua hàm tuDongNapThuVien */
spl_autoload_register('tuDongNapThuVien');

/* Thực hiện khởi động hệ thống trên nền tảng Việt Framework */
new vietFrameWork;
