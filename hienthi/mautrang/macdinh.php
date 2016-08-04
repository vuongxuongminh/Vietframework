<?php
/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                23/07/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * CHỨC NĂNG TỆP:
 * Tệp mẫu trang mặc định khi mới thiết lập hệ thống
 */

/* @var $this hienThiCha */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        echo $this->doiTuongHtml->meta(array("charset" => "utf-8"));
        echo $this->doiTuongHtml->napBieuTuongTrang("bieutuong.ico");
        echo $this->doiTuongHtml->napTieuDeTrang("Việt Framework - Phiên bản 1.0.1");
        echo $this->doiTuongHtml->napCSS("bootstrap.min", "vietFrameWork/phongChu", "vietFrameWork/macDinh");
        ?>
    </head>
    <body>
        <div class="menu">
            <div class="inner">
                <span class="logo">
                    <?php
                    echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/logo-sm.png");
                    echo $this->doiTuongHtml->napLienKet("Việt Framework", "http://vietframework.com");
                    ?>
                </span>
                <?php echo $this->doiTuongHtml->napLienKet("Hướng dẫn", array("class" => "btn btn-success btn-trogiup"), "http://vietframework.com/huong-dan/"); ?>
            </div>
        </div> <!-- end menu -->
        <div class="content-vietframework">
            <?php
            $cauThongBao = $this->cauThongBao();
            if (!empty($cauThongBao)) {
                echo '<div class="message-box">' . $cauThongBao . '</div>';
            }
            $this->napTrangChiDinh();
            ?>
        </div>
        <div class="footer">
            <div class="inner">
                <div class="col-sm-6 col-sm-offset-3">
                    <center>
                        <?php
                        echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/icon-facebook.png", array("Đường Dẫn Liên Kết" => "http://facebook.com/vietframework/"));
                        echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/icon-youtube.png", array("Đường Dẫn Liên Kết" => "http://youtube.com/channel/UCwTs_1EOHRJptJXVeEOpCiA/feed"));
                        echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/icon-mail.png", array("Đường Dẫn Liên Kết" => "mailto:admin@vietframework.com"));
                        ?>
                    </center>
                </div>
                <div class="col-sm-6 col-sm-offset-3">
                    <center><span>&copy; 2014 Một sản phẩm của Việt Framework</span></center>
                </div>
            </div>
        </div> <!-- end footer -->
    </body>
</html>