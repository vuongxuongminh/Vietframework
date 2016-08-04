<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                22/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng thiết lập hòm thư (email)
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập thuộc tính cho lớp đối tượng tiện ích hòm thư
 */
class thietLapHomThu {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Thiết lập các thuộc tính cho đối tượng tiện ích hòm thư thông qua các giá trị phần tử trong mảng
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $thongSo = array(
        "Địa Chỉ SMTP" => "ssl://hostcuaban.com", //Địa chỉ SMTP server để gửi mail
        "Tên Gửi Thư" => "Viet Framework", //Tên gửi thư đi (FROM NAME) hiển thị tại tiêu đề của thư
        "Địa Chỉ Dùng Để Gửi Thư" => "admin@vietframework.com", // Địa chỉ email dùng để gửi thư đi
        "Tài Khoản SMTP" => "taikhoan@hostcuaban.com", //Tài khoản SMTP trên server để gửi mail
        "Mật Khẩu SMTP" => "matkhau", //Mật khẩu SMTP trên server để gửi mail
        "Cổng SMTP" => 465 //Cổng PORT SMTP server         
    );

}

?>
