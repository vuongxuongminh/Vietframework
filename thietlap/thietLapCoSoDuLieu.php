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
 * Lớp đối tượng thiết lập cơ sỡ dữ liệu (database)
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập các thuộc tính cho lớp đối tượng cơ sỡ dữ liệu tại tầng xử lý (model)
 */
class thietLapCoSoDuLieu {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử mang các giá trị là thông số dùng để sử dụng trong kết nối đến cơ sỡ dữ liệu
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $thongSo = array(
        "Loại CSDL" => "mysql", // Loại CSDL sử dụng
        "Địa Chỉ CSDL" => "localhost", // Địa chỉ host đặt CSDL
        "Tên CSDL" => "test", // Tên CSDL
        "Tên Đăng Nhập CSDL" => "", // Tên đăng nhập CSDL
        "Mật Khẩu CSDL" => "", // Mật khẩu đăng nhập CSDL
        "Cổng Kết Nối CSDL" => 3306, // Cổng truy cập CSDL
        "Kiểu Lưu Trữ CSDL" => "utf8" // Kiểu lưu trữ trên CSDL mặc định là UTF8
    );

}

?>