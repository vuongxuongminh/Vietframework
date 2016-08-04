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
 * Lớp đối tượng thiết lập đường dẫn truy cập (router)
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập các đường dẫn truy cập vào đúng tầng điều hướng và gọi phương thức truyền tham trị theo mảng chỉ định
 */

class thietLapDuongDanTruyCap {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử mang khóa là chuỗi đường dẫn truy cập đến hệ thống mang giá trị là mảng đường dẫn điều hướng chỉ định
     * 
     * Ví dụ:
     * Tôi muốn khi người dùng truy cập đến hệ thống theo địa chỉ: "http://tenmiencuaban/san-pham/them-san-pham" thì hệ thống sẽ truyền đến điều hướng "sanpham" và gọi phương thức "them" thì tôi thiết lập 1 phần tử như sau
     * "'san-pham/them-san-pham' => array('Điều Hướng' => 'sanpham', 'Phương Thức' => 'them')"
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $thongSo = array(
        "" => array("Điều Hướng" => "macdinh"),
    );

}
