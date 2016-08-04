<?php
/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                02/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * CHỨC NĂNG TỆP:
 * Tệp báo lỗi khi xảy ra lỗi php lưu ý (notice)
 */
?>
<div style="font-family: tahoma, arial; font-size:14px; width:96%; margin:1%; background-color: #c60000; border-radius: 5px; padding: 1%; color: #fff; word-wrap: break-word; box-shadow: 0px 0px 7px rgba(0,0,0,0.5);">
    <span><?php echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/error-img-02.png", array("style" => "margin-right:10px; vertical-align: middle")); ?>Lưu ý (NOTICE): <?php echo $mangLoiPHP[1]; ?> - Tệp: <?php echo $mangLoiPHP[2]; ?> - Dòng: <?php echo $mangLoiPHP[3]; ?></span>
</div>