<?php
/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                01/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * CHỨC NĂNG TỆP:
 * Tệp báo lỗi khi xảy ra lỗi thiếu tệp hiển thị khi đường dẫn truy cập hệ thống yêu cầu
 */
?>
<div class="header">
	<span>Không tìm thấy tệp hiển thị: <?php echo hienThiCha::$duongDanTepHienThiPhucVuBaoLoi; ?></span>
</div>
<div class="article">
	<p><strong>Hãy khởi tạo tệp hiển thị theo đường dẫn sau: </strong><?php echo hienThiCha::$duongDanTepHienThiPhucVuBaoLoi; ?></p>
</div>
<div class="footer">
	<p><strong>Lưu ý: </strong> bạn có thể tạo 1 trang báo lỗi này tùy thích theo ý bạn bằng cách khởi tạo 1 tệp theo đường dẫn: <?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . "baoloi" . DS . $mangLoi[$maLoi]['Tệp Báo Lỗi'] . ".php"; ?></p>
</div>