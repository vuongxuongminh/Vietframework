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
 * Tệp báo lỗi khi xảy ra lỗi thiếu tệp mẫu trang (layout) khi đường dẫn truy cập hệ thống yêu cầu
 */
/* @var $this hienThiCha */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        echo $this->doiTuongHtml->meta(array("charset" => "utf-8"));
        echo $this->doiTuongHtml->napBieuTuongTrang("bieutuong.ico");
        echo $this->doiTuongHtml->napTieuDeTrang("Việt Framework - Báo lỗi");
        echo $this->doiTuongHtml->napCSS("bootstrap.min", "vietFrameWork/baoLoi", "vietFrameWork/phongChu");
        ?>
    </head>
    <body>
        <div class="container">
            <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                <div class="row main">
                    <div class="col-xs-4 main-right">
                        <?php echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/nv-loi.png"); ?>
                    </div>
                    <div class="col-xs-8">
                        <div class="box">
                            <div class="header">
                                <span>Không tìm thấy tệp mẫu trang: <?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . "mautrang" . DS . hienThiCha::$mauTrang . ".php"; ?></span>
                            </div>
                            <div class="article">
                                <p><strong>Hãy khởi tạo tệp mẫu trang theo đường dẫn sau: </strong><?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . "mautrang" . DS . hienThiCha::$mauTrang . ".php"; ?></p>
                            </div>
                            <div class="footer">
                                <p><strong>Lưu ý: </strong> bạn có thể tạo 1 trang báo lỗi này tùy thích theo ý bạn bằng cách khởi tạo 1 tệp theo đường dẫn: <?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . "baoloi" . DS . $mangLoi[$maLoi]['Tệp Báo Lỗi'] . ".php"; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>