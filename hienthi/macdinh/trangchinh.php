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
 * Tệp trang mặc định khi mới duyệt hệ thống
 */
/* @var $this hienThiCha */
?>
<div class="main-img"></div> <!-- end main-img -->
<div class="main-content">
    <div class="inner">
        <center><h2 class="text-tieude">Việt Framework phiên bản V1.0.1</h2></center>
        <div class="row-box">
            <div class="box box-first">
                <div class="box-round">
                    <?php
                    if (!$kiemTraPhienBanPHP) {
                        echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/cross.png", array("class" => "img-cross", array("style" => "display:block")));
                        echo '<div class="box-content">';
                    } else {
                        echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/tick.png", array("class" => "img-tick"), array("style" => "display:block"));
                        echo '<div class="box-content-hover">';
                    }
                    ?>
                    <center><span>Phiên bản PHP</span></center>
                    <center><?php echo $this->doiTuongHtml->napLienKet("Hướng dẫn", array("class" => "btn btn-primary btn-box-round"), "http://vietframework.com/huong-dan/phien-ban-php/"); ?></center>
                </div>
            </div> <!-- end box-round -->
        </div> <!-- end box -->
        <div class="box">
            <div class="box-round">
                <?php
                if (!$kiemTraTuongLua) {
                    echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/cross.png", array("class" => "img-cross", array("style" => "display:block")));
                    echo '<div class="box-content">';
                } else {
                    echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/tick.png", array("class" => "img-tick"), array("style" => "display:block"));
                    echo '<div class="box-content-hover">';
                }
                ?>
                <center><span>Tường lửa</span></center>
                <center><?php echo $this->doiTuongHtml->napLienKet("Hướng dẫn", array("class" => "btn btn-primary btn-box-round"), "http://vietframework.com/huong-dan/cau-hinh-tuong-lua/"); ?></center>
            </div>
        </div> <!-- end box-round -->
    </div> <!-- end box -->

    <div class="box">
        <div class="box-round">
            <?php
            if (!$kiemTraKetNoiCSDL) {
                echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/cross.png", array("class" => "img-cross", array("style" => "display:block")));
                echo '<div class="box-content">';
            } else {
                echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/tick.png", array("class" => "img-tick"), array("style" => "display:block"));
                echo '<div class="box-content-hover">';
            }
            ?>
            <center><span>Cơ sở dữ liệu</span></center>
            <center><?php echo $this->doiTuongHtml->napLienKet("Hướng dẫn", array("class" => "btn btn-primary btn-box-round"), "http://vietframework.com/huong-dan/cau-hinh-csdl/"); ?></center>
        </div>
    </div> <!-- end box-round -->
</div> <!-- end box -->
<div class="box">
    <div class="box-round">
        <?php
        if (!$kiemTraGhiLuocSu) {
            echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/cross.png", array("class" => "img-cross", array("style" => "display:block")));
            echo '<div class="box-content">';
        } else {
            echo $this->doiTuongHtml->napHinhAnh("vietFrameWork/tick.png", array("class" => "img-tick"), array("style" => "display:block"));
            echo '<div class="box-content-hover">';
        }
        ?>
        <center><span>Ghi lược sử</span></center>
        <center><?php echo $this->doiTuongHtml->napLienKet("Hướng dẫn", array("class" => "btn btn-primary btn-box-round"), "http://vietframework.com/huong-dan/ghi-luoc-su/"); ?></center>
    </div>
</div> <!-- end box-round -->
</div> <!-- end box -->

</div> <!-- end row-box -->


<div class="info" style="background-color:#6b418e">
    <center><h2>Framework Việt cho người Việt</h2></center>
</div>

<div class="info">
    <span>Bạn có thể thay đổi giao diện trang này tại: <strong><?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . dieuHuongCha::$dieuHuongPhuongThucVaThamTri ['Điều Hướng'] . DS . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Phương Thức'] . ".php"; ?></strong></span><br/><br/>
    <span>Bạn có thể thay đổi mẫu trang (layout) tại: <strong><?php echo DUONGDANTHUMUCTRANG . "hienthi" . DS . "mautrang" . DS . hienThiCha::$mauTrang; ?></strong></span><br/><br/>
    <span>Bạn có thể thay đổi các bảng định kiểu (css) tại: <strong><?php echo DUONGDANTHUMUCTRANG . "hotro" . DS . "css"; ?></strong></span>
</div>

</div> <!-- end inner --> 
</div> <!-- end main-content -->