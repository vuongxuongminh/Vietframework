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
 * Tệp báo lỗi khi xảy ra lỗi từ chối truy cập hệ thống
 */

/* @var $this hienThiCha */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        echo $this->doiTuongHtml->meta(array("charset" => "utf-8"));
        echo $this->doiTuongHtml->napBieuTuongTrang("bieutuong");
        echo $this->doiTuongHtml->napTieuDeTrang("Việt Framework - Báo lỗi");
        echo $this->doiTuongHtml->napCSS("bootstrap.min", "vietFrameWork/baoLoi", "vietFrameWork/phongChu");
        ?>
    </head>
    <body>
        <div class="container">
            <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <div class="row main">
                    <div class="col-xs-4 main-right">
                        <?php echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/tuonglua.png"); ?>
                    </div>
                    <div class="col-xs-8">
                        <div class="box">
                            <div class="header">
                                <span><center>Truy cập bị từ chối</center></span>
                            </div>
                            <div class="article">
                                <?php if (!is_numeric(thietLapCha::$cauHinhTuongLua['Thời Gian Mở Khóa'])) { ?>
                                    <p><strong>IP: <?php echo vietFrameWork::$heThong->layIpMayKhach() ?> </strong>đã gửi tín hiệu kết nối vượt quá mức cho phép của hệ thống dẫn đến bị khóa IP vĩnh viễn.</p>
                                    <p>Vui lòng liên hệ với quản trị để biết thêm thông tin, hòm thư liên hệ: <strong><?php echo thietLapCha::$cauHinhTuongLua['Hòm Thư Liên Hệ Khi Bị Khóa']; ?></strong></p>
                                <?php } else { ?>
                                    <p><strong>IP: <?php echo vietFrameWork::$heThong->layIpMayKhach() ?> </strong>đã gửi tín hiệu kết nối vượt quá mức cho phép của hệ thống dẫn đến bị khóa IP tạm thời.</p>
                                    <p>Hệ thống tự động kết nối truy cập lại sau: <strong><span id="soGiayCho"><?php echo $mangLoiPHP; ?></span> giây</strong></p>
                                    <?php echo $this->doiTuongHtml->napJavaScript("jquery-1.11.1.min", "vietFrameWork/tuongLua"); ?>
                                <?php } ?>
                            </div>
                            <div class="footer">
                                <center>Tường lửa Việt Framework phiên bản V1.0.1</center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>