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
 * Lớp đối tượng thiết lập hệ thống
 * 
 * CHỨC NĂNG CHÍNH:
 * Thiết lập tường lửa (firewall)
 * Thiết lập thông tin máy chủ
 * Thiết lập hòm thử (email)
 * Thiết lập các đường dẫn truy cập (router)
 * Thiết lập cơ sở dữ liệu (database)
 * Thiết lập bộ nhớ đệm (cache)
 */

class thietLapCha {

    /**
     * Mảng thuộc tính cấu hình cơ sỡ dữ liệu
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập các thuộc tính cho lớp đối tượng cơ sỡ dữ liệu
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhCSDL = array(
        "Loại CSDL" => "mysql", // Loại CSDL sử dụng
        "Địa Chỉ CSDL" => "localhost", // Địa chỉ host đặt CSDL
        "Tên CSDL" => "", // Tên CSDL
        "Tên Đăng Nhập CSDL" => "", // Tên đăng nhập CSDL
        "Mật Khẩu CSDL" => "", // Mật khẩu đăng nhập CSDL
        "Cổng Kết Nối CSDL" => 3306, // Cổng truy cập CSDL
        "Kiểu Lưu Trữ CSDL" => "utf8" // Kiểu lưu trữ trên CSDL mặc định là UTF8
    );

    /**
     * Mảng thuộc tính cấu hình máy chủ
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập thông tin máy chủ
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhMayChu = array(
        "Điều Hướng Đầu Tiên" => "trangchu", //Khai báo tên tầng điều hướng hiển thị đâu tiên khi duyệt trang
        "Phương Thức Đầu Tiên" => "trangchinh", //Khai báo tên phương thức trong tầng điều hướng được gọi khi mới duyệt trang
        "Tham Trị Đầu Tiên" => array(), //Dãy các tham trị được truyền vào phương thức đầu tiên khi mới nạp trang
        "Dạng Kết Nối" => "http", //Dạng kết nối website có 2 dạng http và ssl
        "Cổng Kết Nối HTTP" => 80, //Cổng kết nối website theo dạng HTTP mặc định là 80
        "Cổng Kết Nối HTTPS" => 443, //Cổng kết nối website theo dạng HTTPS mặc định là 443
        "Khu Vực Giờ Hệ Thống" => "Asia/Ho_Chi_Minh", // Thiết lập múi giờ hệ thống mặc định chuẩn múi giờ Việt Nam
        "SESSION" => array(
            "Thời Gian Tự Hủy Toàn Bộ" => 1800,
            "Thời Gian Tự Hủy Phần Tử Mặc Định" => 15552000
        ),
        "COOKIE" => array(
            "Khóa Mã Hóa 2 Chiều" => "",
            "Thời Gian Tự Hủy Phần Tử Mặc Định" => 15552000
        )
    );

    /**
     * Mảng thuộc tính cấu hình hòm thư (email)
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập thông tin hòm thư (email)
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhHomThu = array(
        "Địa Chỉ SMTP" => "ssl://hostcuaban.com", //Địa chỉ SMTP server để gửi mail
        "Tên Gửi Thư" => "Viet Framework", //Tên gửi thư đi (FROM NAME) hiển thị tại tiêu đề của thư
        "Địa Chỉ Dùng Để Gửi Thư" => "admin@vietframework.com", // Địa chỉ email dùng để gửi thư đi
        "Tài Khoản SMTP" => "taikhoan@hostcuaban.com", //Tài khoản SMTP trên server để gửi mail
        "Mật Khẩu SMTP" => "matkhau", //Mật khẩu SMTP trên server để gửi mail
        "Cổng SMTP" => 465 //Cổng PORT SMTP server         
    );

    /**
     * Mảng thuộc tính cấu hình đường dẫn truy cập (router)
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập thông tin đường dẫn truy cập hệ thống
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhDuongDanTruyCap = array(
        "" => array("Điều Hướng" => "macdinh", "Phương Thức" => "trangchinh", "Tham Trị" => array())
    );

    /**
     * Mảng thuộc tính cấu hình tường lửa (firewall)
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập thông tin tường lửa
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhTuongLua = array(
        "Bật Tường Lửa" => "Bật", //Nếu muốn bật tính năng tường lửa thì điền Bật còn không thì điền Tắt
        "Thời Gian Mở Khóa" => "Không", //Sau số thời gian này sẽ mở khóa cho IP nếu không muốn mở khóa thì ghi vào "Không" thì sẽ không mở khóa
        "Số Lượng Kết Nối Tối Đa" => "15", //Số lượng kết nối tối đa trong khoảng thời gian kết nối bên dưới
        "Thời Gian Kết Nối" => "3", //Thời gian kết nối cho phép số lượng kết nối tối đa bên trên
        "Hòm Thư Liên Hệ Khi Bị Khóa" => "admin@vietframework.com" //Khi bị khóa IP vì 1 lý do nhầm lẫn gì đó người dùng sẽ liên hệ bạn thông quá hòm thư này để bạn mở khóa
    );

    /**
     * Mảng thuộc tính cấu hình bộ nhớ đệm (cache)
     * 
     * CHỨC NĂNG:
     * Cấu hình thiết lập thông tin bộ nhớ đệm
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public static $cauHinhBoNhoDem = array(
        "Kiểu Lưu" => "Tự Động", // Kiểu lưu bộ nhớ đệm (auto, files, sqlite, apc, cookie, memcache, memcached, predis, redis, wincache, xcache)
        "Thời Gian Lưu Mặc Định" => 10, // Thời gian cập nhật lại bộ nhớ đệm tính theo GIÂY
    );

    /**
     * Phương thức thiết lập hệ thống
     * 
     * CHỨC NĂNG:
     * Thiết lập giá trị các phần tử trong các mảng thuộc tính thiết lập cùng lớp nhằm cập nhật nội dung mảng sau khi bạn thao tác các lớp thiết lập trong thư mục "thietlap"
     * 
     */
    public function heThong() {
        $mangLopThietLap = array(
            "thietLapThongTinMayChu" => "cauHinhMayChu",
            "thietLapCoSoDuLieu" => "cauHinhCSDL",
            "thietLapHomThu" => "cauHinhHomThu",
            "thietLapTuongLua" => "cauHinhTuongLua",
            "thietLapDuongDanTruyCap" => "cauHinhDuongDanTruyCap",
            "thietLapBoNhoDem" => "cauHinhBoNhoDem"
        );
        foreach ($mangLopThietLap as $tenLopThietLap => $tenThuocTinhThietLap) {
            // Kiểm tra lớp thiết lập có tồn tại hay không để load thông tin cập nhật cho các thông tin mặc định
            if (class_exists($tenLopThietLap)) {
                // Khởi tạo lớp đối tượng thiết lập thông số
                $lopThietLap = new $tenLopThietLap;
                // Kiểm tra thuộc tính thông số tồn tại
                if (property_exists($lopThietLap, "thongSo")) {
                    $thongSoCauHinh = $lopThietLap->thongSo;
                    // Thiết lập các giá trị từ mảng thuộc tính vào mảng thiết lập mặc định
                    array_walk(self::${$tenThuocTinhThietLap}, function($v, $k) use($thongSoCauHinh, $tenThuocTinhThietLap) {
                        if (isset($thongSoCauHinh[$k])) {
                            unset(thietLapCha::${$tenThuocTinhThietLap}[$k]);
                        }
                    });
                    foreach ($thongSoCauHinh as $k => $v) {
                        self::${$tenThuocTinhThietLap}[$k] = $v;
                    }
                }
            }
        }
        $this->thietLapDuongDanTrang();
        $this->thietLapTuongLua();
        $this->thietLapDangKetNoiTrang();
        date_default_timezone_set(self::$cauHinhMayChu['Khu Vực Giờ Hệ Thống']);
    }

    /**
     * Phương thức thiết lập tường lửa
     * 
     * CHỨC NĂNG:
     * Dựa trên mảng thuộc tính cấu hình thiết lập tường lửa hệ thông sẽ tiến hành xử lý truy cập người dùng từ đó cho phép truy cập hoặc khóa tạm thời hoặc khóa vĩnh viên trừ khi bạn cho phép truy cập lại
     *
     * HƯỚNG DẪN:
     * Cách cho phép truy cập lại: bạn vào thư mục "thietlap/tuonglua/ipbikhoa" kiếm tệp có tên trùng với IP bạn muốn cho phép truy cập lại và xóa nó đi
     * 
     * @return boolean (kiểu trả về đúng (TRUE) khi truy cập hợp lệ và trả về sai (FALSE) khi truy cập không hợp lệ)
     */
    private function thietLapTuongLua() {
        if (mb_strtolower(self::$cauHinhTuongLua['Bật Tường Lửa'], "utf-8") === "bật") {
            $ipMayKhach = vietFrameWork::$heThong->layIpMayKhach();
            if (filter_var($ipMayKhach, FILTER_VALIDATE_IP)) {
                $thoiGianHienTai = time();
                $duongDanTepIpBiKhoa = DUONGDANTHUMUCTRANG . "luocsu" . DS . "tuonglua" . DS . "ipbikhoa" . DS . $ipMayKhach . ".bikhoa";
                $duongDanTepIpLuocSuTruyCap = DUONGDANTHUMUCTRANG . "luocsu" . DS . "tuonglua" . DS . $ipMayKhach . ".truycap";
                if (file_exists($duongDanTepIpBiKhoa)) {
                    $thoiGianIpBiKhoa = file_get_contents($duongDanTepIpBiKhoa);
                    if (is_numeric(self::$cauHinhTuongLua['Thời Gian Mở Khóa'])) {
                        if (is_numeric($thoiGianIpBiKhoa)) {
                            if ((($thoiGianIpBiKhoa + self::$cauHinhTuongLua['Thời Gian Mở Khóa']) - $thoiGianHienTai) < 0) {
                                unlink($duongDanTepIpBiKhoa);
                                $docTep = fopen($duongDanTepIpLuocSuTruyCap, "w+");
                                fwrite($docTep, $thoiGianHienTai . "|" . "1");
                                fclose($docTep);
                                return true;
                            }
                        }
                    }
                    if (!is_numeric(self::$cauHinhTuongLua['Thời Gian Mở Khóa'])) {
                        baoLoiCha::chayTrangBaoLoi("Ip Bị Khóa");
                    } else {
                        baoLoiCha::chayTrangBaoLoi("Ip Bị Khóa", ($thoiGianIpBiKhoa + self::$cauHinhTuongLua['Thời Gian Mở Khóa']) - $thoiGianHienTai);
                    }
                    die;
                } else {
                    if (!file_exists($duongDanTepIpLuocSuTruyCap)) {
                        $docTep = fopen($duongDanTepIpLuocSuTruyCap, "w+");
                        fwrite($docTep, $thoiGianHienTai . "|" . "1");
                        fclose($docTep);
                    } else {
                        $docTep = file_get_contents($duongDanTepIpLuocSuTruyCap);
                        $xuLyDuLieuTep = explode("|", $docTep);
                        $thoiGianTruyCapCuoiCung = isset($xuLyDuLieuTep[0]) ? $xuLyDuLieuTep[0] : $thoiGianHienTai;
                        $soLanTruyCap = isset($xuLyDuLieuTep[1]) ? $xuLyDuLieuTep[1] : 1;
                        $soLanTruyCap++;
                        if (($soLanTruyCap > self::$cauHinhTuongLua['Số Lượng Kết Nối Tối Đa']) && (($thoiGianHienTai - $thoiGianTruyCapCuoiCung) < self::$cauHinhTuongLua['Thời Gian Kết Nối'])) {
                            $docTep = fopen($duongDanTepIpBiKhoa, "w+");
                            fwrite($docTep, $thoiGianHienTai);
                            fclose($docTep);
                            baoLoiCha::chayTrangBaoLoi("Ip Bị Khóa", self::$cauHinhTuongLua['Thời Gian Mở Khóa']);
                            die;
                        } elseif (($soLanTruyCap < self::$cauHinhTuongLua['Số Lượng Kết Nối Tối Đa']) && (($thoiGianHienTai - $thoiGianTruyCapCuoiCung) > self::$cauHinhTuongLua['Thời Gian Kết Nối'])) {
                            $docTep = fopen($duongDanTepIpLuocSuTruyCap, "w+");
                            fwrite($docTep, $thoiGianHienTai . "|" . "1");
                            fclose($docTep);
                            return true;
                        }
                        $docTep = fopen($duongDanTepIpLuocSuTruyCap, "w+");
                        fwrite($docTep, $thoiGianHienTai . "|" . $soLanTruyCap);
                        fclose($docTep);
                    }
                    return true;
                }
            } else {
                die("Truy cập bị từ chối do không xác định được IP của bạn");
            }
        }
    }

    /**
     * Phương thức thiết lập kết nối trang
     * 
     * CHỨC NĂNG:
     * Dựa trên phần tử "Dạng Kết Nối" ở mảng thuộc tính cấu hình máy chủ mà hệ thống sẽ tự động chuyển giao thức theo đúng "Dạng Kết Nối" chỉ định
     */
    private function thietLapDangKetNoiTrang() {
        if (!isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "off")) {
            if ((strtolower(self::$cauHinhMayChu['Dạng Kết Nối']) == "https") || (self::$cauHinhMayChu['Cổng Kết Nối HTTP'] != $_SERVER['SERVER_PORT'])) {
                header("Location: " . DUONGDANTRANG);
            }
        } else {
            if (strtolower(self::$cauHinhMayChu['Dạng Kết Nối']) == "http" || (self::$cauHinhMayChu['Cổng Kết Nối HTTPS'] != $_SERVER['SERVER_PORT'])) {
                header("Location: " . DUONGDANTRANG);
            }
        }
    }

    /**
     * Phương thức thiết lập đường dẫn trang
     * 
     * CHỨC NĂNG:
     * Thiết lập các hằng (define) mang giá trị là chuỗi đường dẫn trang chính, đường dẫn hình ảnh, đường dẫn phim ảnh v..v
     * 
     * Mục đích dùng để hổ trợ các phương thức khác nằm trong các lớp đối tượng trong hệ thống
     */
    private function thietLapDuongDanTrang() {
        $dangKetNoi = strtolower(self::$cauHinhMayChu["Dạng Kết Nối"]);
        $congKetNoi = null;
        if ($dangKetNoi == "http") {
            $congKetNoi = self::$cauHinhMayChu["Cổng Kết Nối HTTP"];
        } else {
            $congKetNoi = self::$cauHinhMayChu["Cổng Kết Nối HTTPS"];
        }
        $lamSachLienKet = trim($_SERVER['PHP_SELF'], '/');
        $catChuoiLienKet = explode("/", $lamSachLienKet);
        $thuMucTrang = substr($lamSachLienKet, 0, strpos($lamSachLienKet, $catChuoiLienKet[count($catChuoiLienKet) - 1]));
        $congKetNoi = ($congKetNoi == 80 || $congKetNoi == 443) ? "" : ":" . $congKetNoi;
        define("DUONGDANTRANG", $dangKetNoi . "://" . $_SERVER['SERVER_NAME'] . $congKetNoi . "/" . $thuMucTrang);
        define("DUONGDANTRANGHINH", DUONGDANTRANG . "hotro/hinhanh/");
        define("DUONGDANTRANGCSS", DUONGDANTRANG . "hotro/css/");
        define("DUONGDANTRANGJS", DUONGDANTRANG . "hotro/js/");
        define("DUONGDANTRANGPHIM", DUONGDANTRANG . "hotro/phimanh/");
        define("DUONGDANTRANGAMTHANH", DUONGDANTRANG . "hotro/amthanh/");
        define("DUONGDANTRANGASSET", DUONGDANTRANG, "hotro/asset/");
        define("DUONGDANHINH", "/$thuMucTrang" . "hotro/hinhanh/");
        define("DUONGDANCSS", "/$thuMucTrang" . "hotro/css/");
        define("DUONGDANJS", "/$thuMucTrang" . "hotro/js/");
        define("DUONGDANASSET", "/$thuMucTrang" . "hotro/asset/");
        define("DUONGDANPHIM", "/$thuMucTrang" . "hotro/phimanh/");
        define("DUONGDANAMTHANH", "/$thuMucTrang" . "hotro/amthanh/");
        define("DUONGDANHOTRO", "/$thuMucTrang" . "hotro/");
    }

}

?>
