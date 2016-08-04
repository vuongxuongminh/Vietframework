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
 * Lớp đối tượng baoLoiCha chứa các phương thức xử lý các lỗi PHP và Framework
 * 
 * CHỨC NĂNG CHÍNH:
 * Xử lý các lỗi gây ra bởi PHP: tiền định, lưu ý, cảnh báo, phản đối (fatal error, notice, warning, deprecated)
 * Xử lý các lỗi gây ra bởi người dùng tương tác: thiếu điều hướng, hiển thị, xử lý v..v
 * 
 */

class baoLoiCha {

    /**
     * Phương thức xuLyCacLoiPHP xử lý các lỗi PHP gây ra
     * 
     * CHỨC NĂNG:
     * Xử lý các lỗi PHP tiền định, lưu ý, cảnh báo, phản đối (fatal error, notice, warning, deprecated)
     * 
     * @param int $maLoi Mã lỗi hệ thống gửi về vào phương thức (kiểu dữ liệu số nguyên)
     * @param string $chuoiLoi Chuỗi lỗi hệ thống gửi về vào phương thức (kiểu dữ liệu chuỗi)
     * @param string $tepLoi Tệp lỗi hệ thống gửi về vào phương thức (kiểu dữ liệu chuỗi)
     * @param int $dongLoi Dòng lỗi hệ thống gửi về vào phương thức (kiểu dữ liệu số nguyên)
     * @return boolean Phương thức trả về giá trị đúng (TRUE) khi không có lỗi xảy ra sai (FALSE) khi có lỗi xảy ra
     */
    public function xuLyCacLoiPHP($maLoi = null, $chuoiLoi = null, $tepLoi = null, $dongLoi = null) {
        if ($maLoi === null) {
            $loi = error_get_last();
            if (empty($loi)) {
                return true;
            }
            $maLoi = $loi['type'];
            $chuoiLoi = $loi['message'];
            $tepLoi = $loi['file'];
            $dongLoi = $loi['line'];
        }
        switch ($maLoi) {
            case E_PARSE:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                self::chayTrangBaoLoi("Lỗi Tiền Định ! (FATAL ERROR)", array($maLoi, $chuoiLoi, $tepLoi, $dongLoi));
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_RECOVERABLE_ERROR:
                self::chayTrangBaoLoi("Cảnh Báo ! (WARNING)", array($maLoi, $chuoiLoi, $tepLoi, $dongLoi));
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                self::chayTrangBaoLoi("Lưu Ý ! (NOTICE)", array($maLoi, $chuoiLoi, $tepLoi, $dongLoi));
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                self::chayTrangBaoLoi("Phản Đối ! (DEPRECATED)", array($maLoi, $chuoiLoi, $tepLoi, $dongLoi));
                break;
        }
        return false;
    }

    /**
     * Phương thức khởi tạo lớp đối tượng baoLoiCha
     * 
     * CHỨC NĂNG:
     * Kích hoạt chế độ bắt lỗi PHP của hệ thống
     * 
     * Tắt chế độ hiển thị lỗi thông thường
     */
    public function __construct() {
        error_reporting(E_ALL & ~E_DEPRECATED);
        register_shutdown_function(array($this, "xuLyCacLoiPHP"));
        set_error_handler(array($this, "xuLyCacLoiPHP"));
        ini_set('display_errors', false);
    }

    /**
     * Phương thức chayTrangBaoLoi xử lý và xuất lỗi ra màn hình người dùng
     * 
     * CHỨC NĂNG:
     * Xử lý và xuất lỗi ra màn hình người dùng
     * 
     * Thực hiện ghi lại lịch sử báo lỗi trong thư mục luocsu
     * 
     * @param string $maLoi Tham trị thứ 1 kiểu chuỗi mang giá trị là chuỗi lỗi truyền về phương thức xử lý
     * @param array $mangLoiPHP Tham trị thứ 2 kiểu mảng giá trị là mảng lỗi PHP truyền về (nếu có)
     */
    public static function chayTrangBaoLoi($maLoi, $mangLoiPHP = array()) {
        $mangLoi = array(
            "Tệp điều hướng không tồn tại" => array("Tệp Báo Lỗi" => "thieu_tepdieuhuong", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Lớp điều hướng không tồn tại" => array("Tệp Báo Lỗi" => "thieu_lopdieuhuong", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Phương thức điều hướng không tồn tại" => array("Tệp Báo Lỗi" => "thieu_phuongthuc", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Lớp điều hướng chưa khai báo kế thừa" => array("Tệp Báo Lỗi" => "kethua_lopdieuhuong", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Tệp hiển thị không tồn tại" => array("Tệp Báo Lỗi" => "thieu_tephienthi", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Không"),
            "Tệp mẫu trang không tồn tại" => array("Tệp Báo Lỗi" => "thieu_tepmautrang", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Không"),
            "Lớp xử lý không tồn tại" => array("Tệp Báo Lỗi" => "thieu_lopxuly", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Lớp xử lý chưa khai báo kế thừa" => array("Tệp Báo Lỗi" => "kethua_lopxuly", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Không"),
            "Lỗi Tiền Định ! (FATAL ERROR)" => array("Tệp Báo Lỗi" => "loiphp_tiendinh", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Ghi"),
            "Cảnh Báo ! (WARNING)" => array("Tệp Báo Lỗi" => "loiphp_canhbao", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Ghi"),
            "Lưu Ý ! (NOTICE)" => array("Tệp Báo Lỗi" => "loiphp_luuy", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Ghi"),
            "Phản Đối ! (DEPRECATED)" => array("Tệp Báo Lỗi" => "loiphp_phandoi", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Ghi"),
            "Ip Bị Khóa" => array("Tệp Báo Lỗi" => "tuchoitruycap", "Nạp Mẫu Trang" => "Không", "Ghi Lược Sử" => "Ghi"),
            "Lỗi Cơ Sở Dữ Liệu" => array("Tệp Báo Lỗi" => "loiphp_sql", "Nạp Mẫu Trang" => "Nạp", "Ghi Lược Sử" => "Ghi")
        );
        $duongDanTepHienThiLoiCuaHeThong = DUONGDANTHUMUCTRANG . "thuvien" . DS . "hienthi" . DS . "baoloi" . DS . $mangLoi[$maLoi]["Tệp Báo Lỗi"] . ".php";
        $duongDanTepHienThiLoiTuyChinh = DUONGDANTHUMUCTRANG . "hienthi" . DS . "baoloi" . DS . $mangLoi[$maLoi]["Tệp Báo Lỗi"] . ".php";
        $hienThi = new hienThiCha();
        $hienThi->thietLapBien(array("mangLoi" => $mangLoi, "mangLoiPHP" => $mangLoiPHP, "maLoi" => $maLoi));
        $duongDanTepHienThiLoi = $duongDanTepHienThiLoiCuaHeThong;
        if (file_exists($duongDanTepHienThiLoiTuyChinh)) {
            $duongDanTepHienThiLoi = $duongDanTepHienThiLoiTuyChinh;
        }
        if (file_exists($duongDanTepHienThiLoi)) {
            $hienThi->tepHienThi = $duongDanTepHienThiLoi;
            if ($mangLoi[$maLoi]['Nạp Mẫu Trang'] == "Nạp") {
                ob_end_clean();
                hienThiCha::$mauTrang = "baoloi";
                $hienThi->napMauTrang();
            } else {
                $hienThi->napTrangChiDinh();
            }
        } else {
            echo "Thiếu tệp nền (source): " . $duongDanTepHienThiLoi . " .Bạn có thể lấy lại tệp đó ở source gốc";
        }
        if ($mangLoi[$maLoi]['Ghi Lược Sử'] == "Ghi") {
            $duongDanTepGhiLoi = DUONGDANTHUMUCTRANG . "luocsu" . DS . "baoloi" . DS . $mangLoi[$maLoi]["Tệp Báo Lỗi"] . ".LOG";
            $chuoiBaoLoi = "Địa chỉ IP: " . vietFrameWork::$heThong->layIpMayKhach() . " vào lúc: " . date("H:i:s d/m/Y") . " gây ra lỗi: ";
            switch ($maLoi) {
                case "Tệp điều hướng không tồn tại":
                    $chuoiBaoLoi .= "yêu cầu tệp điều hướng không tồn tại đường dẫn tệp điều hướng: " . DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_" . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . ".php";
                    break;
                case "Lớp điều hướng không tồn tại":
                    $chuoiBaoLoi .= "yêu cầu lớp điều hướng không tồn tại đường dẫn tệp điều hướng: " . DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_" . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . ".php";
                    break;
                case "Phương thức điều hướng không tồn tại":
                    $chuoiBaoLoi .= "yêu cầu phương thức " . dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Phương Thức"] . " không tồn tại đường dẫn tệp điều hướng: " . DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_" . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . ".php";
                    break;
                case "Lớp điều hướng chưa khai báo kế thừa":
                    $chuoiBaoLoi .= "lớp điều hướng chưa khai báo kế thừa đường dẫn tệp điều hướng: " . DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_" . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . ".php";
                    break;
                case "Tệp hiển thị không tồn tại":
                    $chuoiBaoLoi .= "tệp hiển thị trang không tồn tại đường dẫn tệp: " . DUONGDANTHUMUCTRANG . "hienthi" . DS . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . DS . dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Phương Thức'] . ".php";
                    break;
                case "Tệp mẫu trang không tồn tại":
                    $chuoiBaoLoi .= "tệp mẫu trang không tồn tại đường dẫn tệp: " . DUONGDANTHUMUCTRANG . "hienthi" . DS . "mautrang" . DS . hienThiCha::$mauTrang . ".php";
                    break;
                case "Lỗi Tiền Định ! (FATAL ERROR)":
                    $chuoiBaoLoi .= "lỗi tiền định (fatal error) ! mã báo lỗi: " . $mangLoiPHP[0] . " - lỗi: " . $mangLoiPHP[1] . " - tệp lỗi: " . $mangLoiPHP[2] . " - dòng lỗi: " . $mangLoiPHP[3];
                    break;
                case "Cảnh Báo ! (WARNING)":
                    $chuoiBaoLoi .= "lỗi cảnh báo (warning) ! mã báo lỗi: " . $mangLoiPHP[0] . " - lỗi: " . $mangLoiPHP[1] . " - tệp lỗi: " . $mangLoiPHP[2] . " - dòng lỗi: " . $mangLoiPHP[3];
                    break;
                case "Lưu Ý ! (NOTICE)":
                    $chuoiBaoLoi .= "lỗi lưu ý (notice) ! mã báo lỗi: " . $mangLoiPHP[0] . " - lỗi: " . $mangLoiPHP[1] . " - tệp lỗi: " . $mangLoiPHP[2] . " - dòng lỗi: " . $mangLoiPHP[3];
                    break;
                case "Phản Đối ! (DEPRECATED)":
                    $chuoiBaoLoi .= "lỗi phản đối (deprecated) ! mã báo lỗi: " . $mangLoiPHP[0] . " - lỗi: " . $mangLoiPHP[1] . " - tệp lỗi: " . $mangLoiPHP[2] . " - dòng lỗi: " . $mangLoiPHP[3];
                    break;
                default:
                    break;
            }
            $moTepDeDocVaGhi = fopen($duongDanTepGhiLoi, "a+");
            fwrite($moTepDeDocVaGhi, $chuoiBaoLoi . "\n\n");
            fclose($moTepDeDocVaGhi);
            if (filesize($duongDanTepGhiLoi) > 10000) {
                unlink($duongDanTepGhiLoi);
            }
        }
    }

}

?>