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
 * Lớp đối tượng thiết lập bộ nhớ đệm (cache)
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập các thuộc tính giúp việc lưu bộ nhớ đệm hiểu quả hơn
 */

class thietLapBoNhoDem {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử mang các giá trị là thông số dùng để sử dụng trong việc thiết lập bộ nhớ đệm
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $thongSo = array(
        "Kiểu Lưu" => "Tự Động", // Kiểu lưu bộ nhớ đệm (auto, files, sqlite, apc, cookie, memcache, memcached, predis, redis, wincache, xcache)
        "Thời Gian Lưu Mặc Định" => 10, // Thời gian cập nhật lại bộ nhớ đệm tính theo GIÂY
    );

}

?>