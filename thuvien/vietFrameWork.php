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
 * Lớp đối tượng vietFrameWork nhân xử lý hệ thống (SYSTEM CORE)
 * 
 * CHỨC NĂNG CHÍNH:
 * Xử lý đường dẫn truy cập yêu cầu từ đó gọi về đúng đối tượng điều hướng, xử lý, hiển thị theo yêu cầu
 * Khai báo và khởi tạo hệ thống báo lỗi
 * Khai báo và thiết lập các thông số cơ sở dữ liệu, tường lửa, hòm thư, thông tin máy chủ, đường dẫn truy cập v..v
 */

class vietFrameWork {

    public static $heThong;

    /**
     * Thuộc tính diaChiTruyCap dùng để gán địa chỉ truy cập của người dùng sau khi đc htaccess xử lý
     * @var array (kiểu mảng)
     */
    private $diaChiTruyCap;

    /**
     * Thuộc tính dieuHuong dùng để gán lớp điều hướng tương ứng với yêu cầu gửi về
     * @var dieuHuongCha (kiểu đối tượng)
     */
    private $dieuHuong;

    /**
     * Thuộc tính baoLoi dùng để gán lớp đối tượng báo lỗi nhằm có thể kiểm soát và xử lý các lỗi ở framework, php, sql
     * @var baoLoiCha (kiểu đối tượng)
     */
    private $baoLoi;

    /**
     * Phương thức khởi tạo lớp đối tượng vietFrameWork
     * 
     * CHỨC NĂNG:
     * Xử lý địa chỉ truy cập yêu cầu từ đó chuyển sang mô hình 3 lớp xử lý - hiển thị - điều hướng (M-V-C)
     * Thiết lập hệ thống cơ sở dữ liệu, tường lửa, cấu hình máy chủ, hòm thư v..v
     * 
     * Lưu ý: không thay đổi gì tại phương thức này nếu chưa thực sự hiểu rõ nó vì sự thay đổi của bạn có thể gây ra lỗi cho toàn bộ hệ thống
     */
    public function __construct() {
        self::$heThong = new heThong();
        $this->baoLoi = new baoLoiCha();
        $thietLapCauHinh = new thietLapCha();
        $thietLapCauHinh->heThong();
        $this->xuLyDiaChiTruyCap();
        $mangDuongDan = array();
        if (is_bool($this->xuLyDuongDanChiDinh())) {
            if ($this->diaChiTruyCap[0] == "") {
                $mangDuongDan["Điều Hướng"] = thietLapCha::$cauHinhMayChu["Điều Hướng Đầu Tiên"];
                $mangDuongDan["Phương Thức"] = thietLapCha::$cauHinhMayChu["Phương Thức Đầu Tiên"];
                $mangDuongDan["Tham Trị"] = thietLapCha::$cauHinhMayChu['Tham Trị Đầu Tiên'];
            } else {
                $mangDuongDan["Điều Hướng"] = $this->diaChiTruyCap[0];
                $mangDuongDan["Phương Thức"] = isset($this->diaChiTruyCap[1]) ? $this->diaChiTruyCap[1] : "trangchinh";
                unset($this->diaChiTruyCap[0]);
                unset($this->diaChiTruyCap[1]);
                $mangDuongDan["Tham Trị"] = $this->diaChiTruyCap;
            }
        } else {
            $mangDuongDan = $this->xuLyDuongDanChiDinh();
        }
        $this->truyCapTheoYeuCau($mangDuongDan);
    }

    /**
     * Phương thức xuLyDiaChiTruyCap xử lý địa chỉ truy cập yêu cầu
     * 
     * CHỨC NĂNG:
     * Xử lý địa chỉ truy cập yêu cầu thành mảng gán giá trị cho thuộc tính diaChiTruyCap
     * 
     * Lưu ý: không thay đổi gì tại phương thức này nếu chưa thực sự hiểu rõ nó vì sự thay đổi của bạn có thể gây ra lỗi cho toàn bộ hệ thống
     */
    private function xuLyDiaChiTruyCap() {
        $layDiaChiGuiVe = isset($_GET['duongDanTruyCap']) ? $_GET['duongDanTruyCap'] : "";
        $xuLyLamSachDiaChi = rtrim($layDiaChiGuiVe, '/');
        $diaChiGuiVeDaXuLy = filter_var($xuLyLamSachDiaChi, FILTER_SANITIZE_URL);
        $this->diaChiTruyCap = explode('/', $diaChiGuiVeDaXuLy);
    }

    /**
     * Phương thức xuLyDuongDanChiDinh xử lý kiểm tra đường dẫn yêu cầu với tập đường dẫn chỉ định trong thư mục thietlap/thietLapDuongDanTruyCap.php
     * 
     * CHỨC NĂNG:
     * Kiểm tra đường dẫn yêu cầu nếu tồn tại trong cấu hình thiết lập trong tệp thietLapDuongDanTruyCap.php
     * thì sẽ trả về giá trị mảng đường dẫn truy cập theo mô hình hệ thống để xử lý ngược lại nếu không tồn tại sẽ trả về giá trị sai (FALSE)
     * 
     * @return mixed trả về giá trị trị sai (FALSE) hoặc trả về giá trị kiểu mảng (ARRAY)
     */
    private function xuLyDuongDanChiDinh() {
        $mangDuongDanChiDinh = thietLapCha::$cauHinhDuongDanTruyCap;
        foreach ($mangDuongDanChiDinh as $k => $v) {
            $layDiaChiGuiVe = implode('/', $this->diaChiTruyCap);
            $diaChiGuiVe = $this->diaChiTruyCap;
            if ($k == $layDiaChiGuiVe) {
                return $v;
            } else {
                $xuLyDuongDanChiDinh = explode('/', $k);
                $viTriKiTuDacBiet = $this->viTriTrongMangDuongDanChiDinh($xuLyDuongDanChiDinh, '*');
                if ($viTriKiTuDacBiet > -1) {
                    if ($viTriKiTuDacBiet == 0) {
                        $v["Tham Trị"] = $diaChiGuiVe;
                        return $v;
                    } elseif ($viTriKiTuDacBiet > 0) {
                        $demPhanTuMangTruocKhiXuLy = count($diaChiGuiVe);
                        for ($i = 0; $i < $viTriKiTuDacBiet; $i++) {
                            if (isset($diaChiGuiVe[$i])) {
                                if ($diaChiGuiVe[$i] == $xuLyDuongDanChiDinh[$i]) {
                                    unset($diaChiGuiVe[$i]);
                                }
                            }
                        }
                        if (($viTriKiTuDacBiet + count($diaChiGuiVe)) === $demPhanTuMangTruocKhiXuLy) {
                            $v["Tham Trị"] = $diaChiGuiVe;
                            return $v;
                        }
                    }
                }
                $viTriKiTuDacBiet = $this->viTriTrongMangDuongDanChiDinh($xuLyDuongDanChiDinh, '**');
                if ($viTriKiTuDacBiet > -1) {
                    if ($viTriKiTuDacBiet == 0) {
                        $v["Tham Trị"] = array($layDiaChiGuiVe);
                        return $v;
                    } elseif ($viTriKiTuDacBiet > 0) {
                        $demPhanTuMangTruocKhiXuLy = count($diaChiGuiVe);
                        for ($i = 0; $i < $viTriKiTuDacBiet; $i++) {
                            if (isset($diaChiGuiVe[$i])) {
                                if ($diaChiGuiVe[$i] == $xuLyDuongDanChiDinh[$i]) {
                                    unset($diaChiGuiVe[$i]);
                                }
                            }
                        }
                        if (($viTriKiTuDacBiet + count($diaChiGuiVe)) === $demPhanTuMangTruocKhiXuLy) {
                            $v["Tham Trị"] = array(implode("/", $diaChiGuiVe));
                            return $v;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Phương thức viTriTrongMangDuongDanChiDinh xủ lý trả về vị trí phần tử (KEY) trong mảng
     * 
     * CHỨC NĂNG:
     * Xử lý tra về vị trí phần tử (KEY) trong mảng với 2 tham trị mảng và giá trị phần tử
     * 
     * Phương thức hỗ trợ cho xuLyDuongDanChiDinh
     * 
     * @param array $mang tham trị thứ nhất truyền vào phải là kiểu mảng
     * @param string $kiTuXacDinh tham trị thứ hai truyền vào phải là kiểu chuỗi
     * @return int phương thức trả về giá trị kiểu số nguyên (INTERGER)
     */
    private function viTriTrongMangDuongDanChiDinh($mang, $kiTuXacDinh) {
        $khoa = array_search($kiTuXacDinh, $mang);
        if ($khoa === false) {
            return -1;
        }
        return $khoa;
    }

    /**
     * Phương thức truyCapTheoYeuCau xử lý mảng đường dẫn truy cập hệ thống và phân quyền
     * 
     * CHỨC NĂNG:
     * Xử lý mảng đường dẫn truy cập hệ thống. Thông qua mảng xác định được lớp điều hướng, xử lý, phương thức trong lớp điều hướng và tham trị của phương thức
     * 
     * Từ đó hệ thống sẽ tự động khai báo các lớp đối tượng và gọi phương thức cho phù hợp với mảng đường dẫn truy cập
     * 
     * Ngoài ra phương thức còn đảm nhiệm việc phân quyền hệ thống (AUTHENTICATE) thông qua lớp đối tượng phanQuyen
     * 
     * @param array $mangChiDinh tham trị truyền vào là mảng chứa phần tử khai báo đường dẫn truy cập hệ thống cần xử lý
     */
    private function truyCapTheoYeuCau($mangChiDinh) {
        $mangBaoLoiTruyCap = array(
            "Tệp điều hướng không tồn tại", //0
            "Lớp điều hướng không tồn tại", //1
            "Phương thức điều hướng không tồn tại", //2
            "Lớp điều hướng chưa khai báo kế thừa"
        );
        $dieuHuongChiDinh = null;
        $phuongThucChiDinh = null;
        $thamTriChiDinh = null;
        isset($mangChiDinh['Điều Hướng']) ? $dieuHuongChiDinh = $mangChiDinh['Điều Hướng'] : $dieuHuongChiDinh = "macdinh";
        isset($mangChiDinh['Phương Thức']) ? $phuongThucChiDinh = $mangChiDinh['Phương Thức'] : $phuongThucChiDinh = "trangchinh";
        isset($mangChiDinh['Tham Trị']) ? $thamTriChiDinh = $mangChiDinh['Tham Trị'] : $thamTriChiDinh = array();
        dieuHuongCha::$dieuHuongPhuongThucVaThamTri = array("Điều Hướng" => $dieuHuongChiDinh, "Phương Thức" => $phuongThucChiDinh, "Tham Trị" => $thamTriChiDinh);
        $tepDieuHuongHeThong = DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_hethong.php";
        $tepDieuHuong = DUONGDANTHUMUCTRANG . "dieuhuong" . DS . "dieuhuong_" . $dieuHuongChiDinh . ".php";
        $dieuHuongHeThong = "dieuhuong_hethong";
        $dieuHuong = "dieuhuong_" . $dieuHuongChiDinh;
        if (file_exists($tepDieuHuongHeThong)) {
            require_once($tepDieuHuongHeThong);
            if (class_exists($dieuHuongHeThong)) {
                if (is_subclass_of($dieuHuongHeThong, "dieuHuongCha")) {
                    if (file_exists($tepDieuHuong)) {
                        require_once($tepDieuHuong);
                        if (class_exists($dieuHuong)) {
                            $this->dieuHuong = new $dieuHuong;
                            if (method_exists($this->dieuHuong, $phuongThucChiDinh)) {
                                if (is_subclass_of($this->dieuHuong, $dieuHuongHeThong)) {
                                    $this->dieuHuong->phanQuyen->phanQuyenTruyCap();
                                    call_user_func_array(array($this->dieuHuong, $phuongThucChiDinh), $thamTriChiDinh);
                                    $this->dieuHuong->khoiTaoHienThi();
                                    if (empty($this->dieuHuong->hienThi->tepHienThi)) {
                                        $this->dieuHuong->hienThi->tepHienThi($phuongThucChiDinh);
                                    }
                                    $this->dieuHuong->hienThi->napMauTrang();
                                } else {
                                    baoLoiCha::chayTrangBaoLoi($mangBaoLoiTruyCap[3]);
                                }
                            } else {
                                baoLoiCha::chayTrangBaoLoi($mangBaoLoiTruyCap[2]);
                            }
                        } else {
                            baoLoiCha::chayTrangBaoLoi($mangBaoLoiTruyCap[1]);
                        }
                    } else {
                        baoLoiCha::chayTrangBaoLoi($mangBaoLoiTruyCap[0]);
                    }
                } else {
                    return trigger_error("Tệp nền (source) bị lỗi: " . $tepDieuHuongHeThong . " bạn có thể lấy lại nó ở source gốc !", E_WARNING);
                }
            } else {
                return trigger_error("Tệp nền (source) bị lỗi: " . $tepDieuHuongHeThong . " bạn có thể lấy lại nó ở source gốc !", E_WARNING);
            }
        } else {
            return trigger_error("Thiếu tệp nền (source): " . $tepDieuHuongHeThong . " bạn có thể lấy nó ở source gốc !", E_NOTICE);
        }
    }

    /**
     * Phương thức layIpMayKhach xử lý giúp lấy IP máy khách
     * 
     * CHỨC NĂNG:
     * Xử lý và trả về IP máy khách truy cập vào hệ thống
     * 
     * @return string phương thức trả về kiểu giá trị chuỗi là IP máy khách truy cập
     */
    public static function layIpMayKhach() {
        $ip = "Không xác định !";
        if (isset($_SERVER)) {
            $mayChu = $_SERVER;
            if (isset($mayChu['HTTP_CLIENT_IP'])) {
                $ip = $mayChu['HTTP_CLIENT_IP'];
            } elseif (isset($mayChu['HTTP_X_FORWARDED_FOR'])) {
                $ip = $mayChu['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($mayChu['HTTP_X_FORWARDED'])) {
                $ip = $mayChu['HTTP_X_FORWARDED'];
            } elseif (isset($mayChu['HTTP_FORWARDED_FOR'])) {
                $ip = $mayChu['HTTP_FORWARDED_FOR'];
            } elseif (isset($mayChu['HTTP_FORWARDED'])) {
                $ip = $mayChu['HTTP_FORWARDED'];
            } elseif (isset($mayChu['REMOTE_ADDR'])) {
                $ip = $mayChu['REMOTE_ADDR'];
            }
        }
        if ($ip === "::1") {
            $ip = "127.0.0.1";
        }
        return $ip;
    }

    /**
     * Phương thức layTenMienTruyCap xử lý giúp lấy tên miền máy khách sử dụng truy cập vào hệ thống
     * 
     * CHỨC NĂNG:
     * Xử lý và trả về tên miền máy khách sử dụng truy cập vào hệ thống
     * 
     * @return string phương thức trả về kiểu giá trị chuỗi là tên miền máy khách truy cập
     */
    public static function layTenMienTruyCap() {
        $tenMien = "unknow";
        if (isset($_SERVER)) {
            $mayChu = $_SERVER;
            if (isset($mayChu["HTTP_HOST"])) {
                $tenMien = $mayChu["HTTP_HOST"];
            } elseif (isset($mayChu["SERVER_NAME"])) {
                $tenMien = $mayChu["SERVER_NAME"];
            }
        }
        return $tenMien;
    }

}

?>