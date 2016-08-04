<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                11/07/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng hòm thư
 * 
 * CHỨC NĂNG CHÍNH:
 * Hổ trợ các phương thức gửi thư (email) tiện lợi nhất theo thông tin cấu hình bạn nhập vào
 * 
 * Lưu ý:
 * Khi sử dụng lớp đối tượng này bạn phải chắc rằng bạn đã mở giao thức kết nối ssl tại file cấu hinh php.ini (kiểm tra các file dll trong phần extension có php_openssl.dll hay chưa)
 * 
 * Lớp đối tượng này hoạt động dựa trên bộ thư viện PHP MAILER
 */

class homThu {

    /**
     * Thuộc tính chuỗi thông báo lỗi
     * 
     * CHỨC NĂNG:
     * Dùng để lưu thông báo lỗi khi thư gửi đi có xảy ra lỗi bất kì
     * 
     * @var string (kiểu dữ liệu dạng chuỗi)
     */
    private static $thongBaoLoi = "";

    /**
     * Phương thức gửi thư
     * 
     * CHỨC NĂNG:
     * Dùng để gửi thư theo thông số thiết lập tại lớp thietLapHomThu trong tệp "thietlap/thietLapHomThu.php"
     * 
     * Ví dụ: tôi muốn gửi thư đến xxx@xxx.com với tiêu đề là abc nội dung là vietFrameWork thì tại bất kì nơi trong 3 tầng (điều hướng, xử lý, hiển thị) tôi gọi như sau: homThu::guiThu("xxx@xxx.com", "abc", "vietFrameWork");
     * 
     * Ví dụ: tối muốn gửi thư đến xxx@xxx.com với tiêu đề là abc nội dung là vietFrameWork và đính kèm tệp tin "abc.txt" có đường dẫn tệp là "c:\abc.txt" thì tại bất kì nơi trong 3 tầng (điều hướng, xử lý, hiển thị) tôi gọi như sau: homThu::guiThu("xxx@xxx.com", "abc", "vietFrameWork", array("c:\abc.txt", "abc.txt"));
     *
     * Ví dụ: tối muốn gửi thư đến xxx@xxx.com với tiêu đề là abc nội dung là vietFrameWork đính kèm tệp tin "abc.txt" có đường dẫn tệp là "c:\abc.txt" và có chèn hình ảnh sử dụng trong nội dung thư thì tại bất kì nơi trong 3 tầng (điều hướng, xử lý, hiển thị) tôi gọi như sau: homThu::guiThu("xxx@xxx.com", "abc", "vietFrameWork", array("c:\abc.txt", "abc.txt"), array("duong dan hinh anh", "cid su dung trong the img noi dung"));
     * 
     * MỞ RỘNG:
     * Ngoài ra phương thức này còn hổ trợ bạn gửi thư 1 lúc nhiều địa chỉ và phân dạng BCC và CC.
     * 
     * Tài liệu về BCC và CC:
     * @link http://genk.vn/thu-thuat/tim-hieu-hai-che-do-cc-va-bcc-khi-gui-email-2012110310392115.chn
     * 
     * Ví dụ: tôi muốn gửi thư đến xxx@xxx.com, thư sao chép (cc) là: abc@abc.com, thư sao chép dấu tên nhận (bcc) là: vfw@vietframework.com với tiêu đề là abc nội dung là vietFrameWork thì tại bất kì nơi trong 3 tầng (điều hướng, xử lý, hiển thị) tôi gọi như sau: homThu::guiThu(array('to' => 'xxx@xxx.com', 'cc' => 'abc@abc.com', 'bcc' => 'vfw@vietframework.com'), "abc", "vietFrameWork");
     * 
     * Ví dụ: tôi muốn gửi thư đến xxx@xxx.com, thư sao chép (cc) là: abc@abc.com, cdd@zing.vn thư sao chép dấu tên nhận (bcc) là: vfw@vietframework.com với tiêu đề là abc nội dung là vietFrameWork thì tại bất kì nơi trong 3 tầng (điều hướng, xử lý, hiển thị) tôi gọi như sau: homThu::guiThu(array('to' => 'xxx@xxx.com', 'cc' => array('abc@abc.com', 'cdd@zing.vn'), 'bcc' => 'vfw@vietframework.com'), "abc", "vietFrameWork");
     * 
     * @param mixed $diaChiNhanThu (tham trị thứ 1 truyền vào dạng chuỗi hoặc mảng các hòm thư nhận thư gửi)
     * @param string $tieuDe (tham trị thứ 2 truyền vào dạng chuỗi là tiêu để của thư muốn gửi)
     * @param string $noiDung (tham trị thứ 3 truyền vào dạng chuỗi là nội dung của thư muốn gửi)
     * @param array $tepDinhKem (tham trị thứ 4 truyền vào dạng mảng chứa các phần tử mang giá trị khai báo đính kèm tệp tin vào thư gửi đi)
     * @return mixed (giá trị trả về là đúng (TRUE) khi gửi thư thành công ngược lại là sai (FALSE) khi gửi thất bại)
     */
    public static function gui($diaChiNhanThu = null, $tieuDe = "", $noiDung = "", $tepDinhKem = array(), $chenHinhAnh = array()) {
        if (extension_loaded('openssl')) {
            if ((is_array($diaChiNhanThu) || is_string($diaChiNhanThu)) && is_string($tieuDe) && is_string($noiDung)) {
                $tepTuDongNapThuVienPHPMailer = DUONGDANTHUMUCTRANG . "thuvien" . DS . "tronang" . DS . "homthu" . DS . "PHPMailerAutoload.php";
                if (file_exists($tepTuDongNapThuVienPHPMailer)) {
                    require_once($tepTuDongNapThuVienPHPMailer);
                    $guiThu = new PHPMailer;
                    $guiThu->IsSMTP();
                    $guiThu->Priority = 1;
                    $guiThu->SMTPDebug = 0;
                    $guiThu->Host = thietLapCha::$cauHinhHomThu["Địa Chỉ SMTP"];
                    $guiThu->Port = thietLapCha::$cauHinhHomThu["Cổng SMTP"];
                    $guiThu->SMTPAuth = true;
                    $guiThu->Username = thietLapCha::$cauHinhHomThu["Tài Khoản SMTP"];
                    $guiThu->Password = thietLapCha::$cauHinhHomThu["Mật Khẩu SMTP"];
                    $guiThu->FromName = thietLapCha::$cauHinhHomThu["Tên Gửi Thư"];
                    $guiThu->From = thietLapCha::$cauHinhHomThu["Địa Chỉ Dùng Để Gửi Thư"];
                    if (is_array($diaChiNhanThu)) {
                        if (isset($diaChiNhanThu['to'])) {
                            if (is_array($diaChiNhanThu['to'])) {
                                foreach ($diaChiNhanThu['to'] as $v) {
                                    if (!kiemTraLoi::homThu($v)) {
                                        self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                        return false;
                                    }
                                    $guiThu->addAddress($v);
                                }
                            } elseif (is_string($diaChiNhanThu['to'])) {
                                if (!kiemTraLoi::homThu($diaChiNhanThu['to'])) {
                                    self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                    return false;
                                }
                                $guiThu->addAddress($diaChiNhanThu['to']);
                            } else {
                                self::$thongBaoLoi = "Tham trị địa chỉ nhận thư không hợp lệ";
                                return false;
                            }
                        } elseif (isset($diaChiNhanThu['bcc'])) {
                            if (is_array($diaChiNhanThu['bcc'])) {
                                foreach ($diaChiNhanThu['bcc'] as $v) {
                                    if (!kiemTraLoi::homThu($v)) {
                                        self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                        return false;
                                    }
                                    $guiThu->addBCC($v);
                                }
                            } elseif (is_string($diaChiNhanThu['bcc'])) {
                                if (!kiemTraLoi::homThu($diaChiNhanThu['bcc'])) {
                                    self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                    return false;
                                }
                                $guiThu->addBCC($diaChiNhanThu['bcc']);
                            } else {
                                self::$thongBaoLoi = "Tham trị địa chỉ nhận thư không hợp lệ";
                                return false;
                            }
                        } elseif (isset($diaChiNhanThu['cc'])) {
                            if (is_array($diaChiNhanThu['cc'])) {
                                foreach ($diaChiNhanThu['cc'] as $v) {
                                    if (!kiemTraLoi::homThu($v)) {
                                        self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                        return false;
                                    }
                                    $guiThu->addCC($v);
                                }
                            } elseif (is_string($diaChiNhanThu['cc'])) {
                                if (!kiemTraLoi::homThu($diaChiNhanThu['cc'])) {
                                    self::$thongBaoLoi = 'Email gửi đi không hợp lệ !';
                                    return false;
                                }
                                $guiThu->addCC($diaChiNhanThu['cc']);
                            } else {
                                self::$thongBaoLoi = "Tham trị địa chỉ nhận thư không hợp lệ";
                                return false;
                            }
                        }
                    } elseif (is_string($diaChiNhanThu)) {
                        $guiThu->addAddress($diaChiNhanThu);
                    } else {
                        self::$thongBaoLoi = "Tham trị địa chỉ nhận thư không hợp lệ";
                        return false;
                    }
                    if (is_array($tepDinhKem)) {
                        if ((count(array_filter($tepDinhKem, function($a) {
                                            return is_array($a);
                                        })) === count($tepDinhKem)) && count($tepDinhKem) > 0) {
                            foreach ($tepDinhKem as $v) {
                                if ((isset($v[0]) && isset($v[1])) && (is_string($v[0]) && is_string($v[1]))) {
                                    $guiThu->addAttachment($v[0], $v[1]);
                                } else {
                                    self::$thongBaoLoi = "Có lỗi xảy ra trong tệp đính kèm vui long kiểm tra lại";
                                    return false;
                                }
                            }
                        } elseif ((count(array_filter($tepDinhKem, function($a) {
                                            return is_array($a);
                                        })) === 0) && count($tepDinhKem) > 0) {
                            if ((isset($tepDinhKem[0]) && isset($tepDinhKem[1])) && (is_string($tepDinhKem[0]) && is_string($tepDinhKem[1]))) {
                                $guiThu->addAttachment($tepDinhKem[0], $tepDinhKem[1]);
                            } else {
                                self::$thongBaoLoi = "Có lỗi xảy ra trong tệp đính kèm vui long kiểm tra lại";
                                return false;
                            }
                        }
                    } else {
                        self::$thongBaoLoi = "Tham trị tệp đính kèm không hợp lệ";
                        return false;
                    }
                    if (is_array($chenHinhAnh)) {
                        if ((count(array_filter($chenHinhAnh, function($a) {
                                            return is_array($a);
                                        })) === count($chenHinhAnh)) && count($chenHinhAnh) > 0) {
                            foreach ($chenHinhAnh as $v) {
                                if ((isset($v[0]) && isset($v[1])) && (is_string($v[0]) && is_string($v[1]))) {
                                    $guiThu->addEmbeddedImage($v[0], $v[1]);
                                } else {
                                    self::$thongBaoLoi = "Có lỗi xảy ra trong tệp đính kèm vui long kiểm tra lại";
                                    return false;
                                }
                            }
                        } elseif ((count(array_filter($chenHinhAnh, function($a) {
                                            return is_array($a);
                                        })) === 0) && count($chenHinhAnh) > 0) {
                            if ((isset($chenHinhAnh[0]) && isset($chenHinhAnh[1])) && (is_string($chenHinhAnh[0]) && is_string($chenHinhAnh[1]))) {
                                $guiThu->addEmbeddedImage($chenHinhAnh[0], $chenHinhAnh[1]);
                            } else {
                                self::$thongBaoLoi = "Có lỗi xảy ra trong tệp đính kèm vui long kiểm tra lại";
                                return false;
                            }
                        }
                    } else {
                        self::$thongBaoLoi = "Tham trị tệp đính kèm không hợp lệ";
                        return false;
                    }
                    $guiThu->CharSet = "utf-8";
                    $guiThu->setLanguage("vi");
                    $guiThu->Subject = $tieuDe;
                    $guiThu->Body = $noiDung;
                    $guiThu->isHTML(true);
                    $guiThu->WordWrap = 50;
                    if (!$guiThu->Send()) {
                        self::$thongBaoLoi = 'Có lỗi xảy ra trong quá trình gửi thư: ' . $guiThu->ErrorInfo;
                        return false;
                    }
                    self::$thongBaoLoi = "";
                    return true;
                } else {
                    self::$thongBaoLoi = "Thiếu bộ thư viện PHP Mailer bạn có thể lấy lại nó ở source gốc !";
                    return false;
                }
            }
            self::$thongBaoLoi = 'Dữ liệu truyền vào phương thức gửi thư không hợp lệ';
            return false;
        }
        self::$thongBaoLoi = 'Máy chủ của bạn chưa khai báo extension php_openssl.dll trong tệp php.ini ! Vui lòng kiểm tra lại.';
        return false;
    }

    public static function kiemTraKetNoiSMTP() {
        if (extension_loaded('openssl')) {
            $tepTuDongNapThuVienPHPMailer = DUONGDANTHUMUCTRANG . "thuvien" . DS . "tronang" . DS . "homthu" . DS . "PHPMailerAutoload.php";
            if (file_exists($tepTuDongNapThuVienPHPMailer)) {
                require_once($tepTuDongNapThuVienPHPMailer);
                $guiThu = new PHPMailer;
                $guiThu->IsSMTP();
                $guiThu->SMTPDebug = 0;
                $guiThu->Host = thietLapCha::$cauHinhHomThu["Địa Chỉ SMTP"];
                $guiThu->Port = thietLapCha::$cauHinhHomThu["Cổng SMTP"];
                $guiThu->SMTPAuth = true;
                $guiThu->Username = thietLapCha::$cauHinhHomThu["Tài Khoản SMTP"];
                $guiThu->Password = thietLapCha::$cauHinhHomThu["Mật Khẩu SMTP"];
                return $guiThu->smtpConnect();
            }
        }
        return false;
    }

    /**
     * Phương thức thông báo lỗi
     * 
     * CHỨC NĂNG:
     * Dùng để lấy giá trị của thuộc tính thông báo lỗi
     * 
     * @return string (kiểu trả về dạng chuỗi là chuỗi báo lỗi khi gửi thư nếu có)
     */
    public static function thongBaoLoi() {
        return self::$thongBaoLoi;
    }

}

?>
