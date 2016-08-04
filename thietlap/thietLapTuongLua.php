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

/*
 * Lớp đối tượng thiết lập tường lửa
 * 
 * CHỨC NĂNG:
 * Thiết lập các thuộc tính cho hệ thống tường lửa 
 */

class thietLapTuongLua {

    /**
     * Mảng thuộc tính thông số thiết lập
     * 
     * CHỨC NĂNG:
     * Thiết lập các thuộc tính cho hệ thống tường lửa thông qua các giá trị phần tử trong mảng
     * 
     * Chú ý: Đơn vị thời gian trong mảng thiết lập là giây
     */
    public $thongSo = array(
        "Bật Tường Lửa" => "Tắt", //Nếu muốn bật tính năng tường lửa thì điền Bật còn không thì điền Tắt
        "Thời Gian Mở Khóa" => "30", //Sau số thời gian này sẽ mở khóa cho IP nếu không muốn mở khóa thì ghi vào "Không" thì sẽ không mở khóa
        "Số Lượng Kết Nối Tối Đa" => "15", //Số lượng kết nối tối đa trong khoảng thời gian kết nối bên dưới
        "Thời Gian Kết Nối" => "3", //Thời gian kết nối cho phép số lượng kết nối tối đa bên trên
        //Tôi lấy ví dụ trong 5 giây hệ thống chỉ cho phép 50 lượt kết nối trên mỗi IP thì số lượng kết nối tối đa là 50 và thời gian kết nối là 5
        "Hòm Thư Liên Hệ Khi Bị Khóa" => "admin@vietframework.com" //Khi bị khóa IP vì 1 lý do nhầm lẫn gì đó người dùng sẽ liên hệ bạn thông quá hòm thư này để bạn mở khóa
    );

}

?>