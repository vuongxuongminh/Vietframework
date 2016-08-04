<?php
/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                15/07/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * CHỨC NĂNG TỆP:
 * Tệp mẫu trang báo lỗi hệ thống (error layout)
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
                            <?php $this->napTrangChiDinh(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>