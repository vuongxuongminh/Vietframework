<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                21/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng thiết lập thông tin máy chủ
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập các thông tin cấu hình hệ thống
 */

class thietLapThongTinMayChu {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Dùng để thiết lập các thông số cấu hình hệ thống thông qua các giá trị phần tử trong mảng
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $thongSo = array(
        "Điều Hướng Đầu Tiên" => "trangchu", //Khai báo tên tầng điều hướng hiển thị đâu tiên khi duyệt trang
        "Phương Thức Đầu Tiên" => "trangchinh", //Khai báo tên phương thức trong tầng điều hướng được gọi khi mới duyệt trang
        "Tham Trị Đầu Tiên" => array(), //Dãy các tham trị được truyền vào phương thức đầu tiên khi mới nạp trang
        "Dạng Kết Nối" => "http", //Dạng kết nối website có 2 dạng http và https
        "Cổng Kết Nối HTTP" => 80, //Cổng kết nối website theo dạng HTTP mặc định là 80
        "Cổng Kết Nối HTTPS" => 443, //Cổng kết nối website theo dạng HTTPS mặc định là 443
        "Khu Vực Giờ Hệ Thống" => "Asia/Ho_Chi_Minh", // Thiết lập múi giờ hệ thống mặc định chuẩn múi giờ Việt Nam
        "SESSION" => array(
            "Thời Gian Tự Hủy Toàn Bộ" => 1800, // Sau thời gian này (giây) nếu máy khách không tương tác đến mảng SESSION thì sẽ tự động xóa toàn bộ
            "Thời Gian Tự Hủy Phần Tử Mặc Định" => 15552000 // Có chức năng như trên nhưng chỉ đối với các phần tử không áp dụng cho toàn bộ mảng SESSION
        ),
        "COOKIE" => array(
            "Khóa Mã Hóa 2 Chiều" => "", // Khóa (serect key) dùng để mã hóa dữ liệu cookie ở máy khách
            "Thời Gian Tự Hủy Phần Tử Mặc Định" => 15552000 // Có chức năng như trên nhưng chỉ đối với các phần tử không áp dụng cho toàn bộ mảng COOKIE
        )
    );

}

?>