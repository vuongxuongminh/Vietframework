<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                15/05/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng kiểm tra lỗi
 * 
 * CHỨC NĂNG CHÍNH:
 * Hỗ trợ cho phương thức luuDuLieu tài tầng xử lý (MODEL) trong việc bắt ra các lỗi dữ liệu quy định trong mảng kiemTraLoi (validate)
 * 
 * 1 số phương thức kiểm tra lỗi như là: soNguyen, chuoiChuVaSo, khongBoTrong, thoiGian, homThu, soNamTrongKhoang v..v
 * 
 */

class kiemTraLoi {

    /**
     * Đối tượng xử lý
     * 
     * CHỨC NĂNG:
     * Trong đối tượng này tương đương với tầng xử lý (model) chứa cac thuộc tính và phương thức xử lý nhằm hổ trợ cho việc tương tác với các thuộc tính và phương thức khi cần thiết
     * 
     * @var xuLyCha (kiểu đối tượng)
     */
    private $xuLy;

    /**
     * Thuộc tính mảng dữ liệu kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Là mảng thuộc tính chứa các phần tử cần kiểm tra lỗi dữ liệu chỉ định trong mảng kiemTraLoi ở tầng xử lý (model). Lớp đối tượng sẽ xử lý lỗi dựa trên mảng dữ liệu này
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    private $duLieuKiemLoi = array();

    /**
     * Thuộc tính mảng thuộc tính
     * 
     * CHỨC NĂNG:
     * Là mảng thuộc tính được gửi về từ phương thức luuDuLieu ở tầng xử lý (model). Hỗ trợ cho việc truyền vào phương thức gọi ngược (callback) nếu có
     * 
     * @var type 
     */
    private $mangThuocTinh = array();

    /**
     * Phương thức khởi tạo lớp đối tượng kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Dùng để thiết lập giá trị cho các thuộc tính như là xuLy, duLieuKiemLoi, mangThuocTinh được truyền về tại tầng xử lý (model)
     * 
     * @param xuLyCha $doiTuongXuLy (tham trị thức 1 được truyền vào dạng đối tượng là đối tượng xử lý)
     * @param array $duLieuKiemLoi (tham trị thứ 2 được truyền vào dạng mảng là mảng dữ liệu để kiểm tra lỗi)
     * @param array $mangThuocTinh (tham trị thứ 3 được truyền vào dạng mảng là mảng thuộc tính tại phương thức luuDuLieu ở tầng xử lý (model))
     */
    public function __construct($doiTuongXuLy, $duLieuKiemLoi, $mangThuocTinh) {
        $this->xuLy = $doiTuongXuLy;
        $this->duLieuKiemLoi = $duLieuKiemLoi;
        $this->mangThuocTinh = $mangThuocTinh;
    }

    /**
     * Phương thức kiểm tra các lỗi chỉ định
     * 
     * CHỨC NĂNG:
     * Phương thức này sẽ được gọi tại tầng xử lý (model) trong phương thức luuDuLieu trước khi thực hiện các hành động xử lý lưu dữ liệu
     * 
     * Hổ trợ cho tầng xử lý (model) kiểm tra lỗi dữ liệu trước khi lưu
     * 
     * @return boolean (kiểu dữ liệu trả về đúng (TRUE) khi quá trính kiểm tra lỗi không xảy ra bất cứ lỗi nào và phương thức luuDuLieu tại tầng xử lý tiếp tục thực hiện các hành động của nó ngược lại thì không)
     */
    public function kiemTraCacLoiChiDinh() {
        $xuLy = $this->xuLy;
        $duLieu = $this->duLieuKiemLoi;
        $mangThuocTinh = $this->mangThuocTinh;
        if ($mangThuocTinh["kiểm tra lỗi"] === true) {
            if ($mangThuocTinh["gọi ngược"] === true) {
                if (!$xuLy->thucHienTruocKhiKiemTraLoi($mangThuocTinh)) {
                    return false;
                }
            }
            $mangBaoLoi = $this->xuLyTraVeMangLoi($xuLy, $duLieu);
            if ($mangThuocTinh["gọi ngược"] === true) {
                $xuLy->thucHienSauKhiKiemTraLoi();
            }
            if ((count($mangBaoLoi) == 0) && (is_array($xuLy->mangBaoLoi) && empty($xuLy->mangBaoLoi))) {
                return true;
            }
            if (is_array($xuLy->mangBaoLoi)) {
                if (isset($xuLy->mangBaoLoi[$xuLy->tenBangCSDL])) {
                    $xuLy->mangBaoLoi[$xuLy->tenBangCSDL] += $mangBaoLoi;
                } else {
                    $xuLy->mangBaoLoi[$xuLy->tenBangCSDL] = $mangBaoLoi;
                }
                return false;
            }
        }
        return true;
    }

    /**
     * Phương thức xử lý trả về mảng lỗi
     * 
     * CHỨC NĂNG:
     * Xử lý các lỗi dữ liệu dựa trên mảng dữ liệu gửi về.
     * 
     * Phương thức này là phương thức hỗ trợ xử lý của phương thức kiemTraCacLoiChiDinh
     * 
     * @param xuLyCha $xuLy (tham trị thứ 1 truyền vào kiểu đối tượng là đối tượng xử lý (model))
     * @param array $duLieu (tham trị thứ 2 truyền vào kiểu mảng là mảng dữ liệu cần xử lý kiểm tra lỗi)
     * @return array (kiểu dữ liệu trả về dạng mảng là mảng báo lỗi nếu xử lý được các lỗi xảy ra)
     */
    private function xuLyTraVeMangLoi($xuLy, $duLieu) {
        $mangLoi = array();
        if (isset($xuLy->kiemTraLoi[$xuLy->tenBangCSDL])) {
            $mangDieuKien = $xuLy->kiemTraLoi[$xuLy->tenBangCSDL];
            if (is_array($mangDieuKien)) {
                foreach ($mangDieuKien as $tenTruong => $mangKiemTra) {
                    if (isset($duLieu[$tenTruong])) {
                        if (is_array($mangKiemTra)) {
                            $kiemTraLoi = true;
                            $cauBaoLoi = "Có lỗi xảy ra !";
                            if (isset($mangKiemTra["Kiểm Tra"])) {
                                $cauBaoLoi = isset($mangKiemTra["Câu Báo Lỗi"]) ? $mangKiemTra["Câu Báo Lỗi"] : $cauBaoLoi;
                                $kiemTraLoi = $this->kiemTraLoi($mangKiemTra, $xuLy, $duLieu, $tenTruong);
                            } else {
                                foreach ($mangKiemTra as $v) {
                                    if (is_array($v)) {
                                        if ($kiemTraLoi === true) {
                                            if (isset($v['Kiểm Tra'])) {
                                                $cauBaoLoi = isset($v["Câu Báo Lỗi"]) ? $v["Câu Báo Lỗi"] : $cauBaoLoi;
                                                $kiemTraLoi = $this->kiemTraLoi($v, $xuLy, $duLieu, $tenTruong);
                                            }
                                        }
                                    }
                                }
                            }
                            if (!$kiemTraLoi) {
                                $mangLoi[$tenTruong] = $cauBaoLoi;
                            }
                        }
                    }
                }
            }
        }
        return $mangLoi;
    }

    /**
     * Phương thức kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Kiểm tra dữ liệu gửi về xử lý dựa trên các phương thức kiểm tra tại hệ thống hoặc các phương thức do bạn tự chế tạo.
     * 
     * Phương thức này là phương thức hỗ trợ xử lý của phương thức xuLyTraVeMangLoi
     * 
     * @param array $mangKiemTra (tham trị thứ 1 dạng mảng là mảng kiểm tra dữ liệu phần tử)
     * @param type $xuLy (tham trị thứ 2 dạng đối tượng là đối tượng xử lý (model))
     * @param type $duLieu (tham trị thứ 3 dạng mảng là mảng chứa các thành phần dữ liệu kiểm tra lỗi)
     * @param type $tenTruong (tham trị thứ 4 dạng chuỗi là tên trường cần xử lý kiểm tra lỗi)
     * @return boolean (kiểu trả về đúng (TRUE) khi không có lỗi xảy ra sai (FALSE) khi có lỗi xảy ra)
     */
    private function kiemTraLoi($mangKiemTra, $xuLy, $duLieu, $tenTruong) {
        $kiemTraLoi = true;
        if (is_string($mangKiemTra["Kiểm Tra"])) {
            if (method_exists($this, $mangKiemTra["Kiểm Tra"])) {
                $kiemTraLoi = call_user_func(array($this, $mangKiemTra["Kiểm Tra"]), $duLieu[$tenTruong]);
            } elseif (method_exists($xuLy, $mangKiemTra["Kiểm Tra"])) {
                $kiemTraLoi = call_user_func(array($xuLy, $mangKiemTra["Kiểm Tra"]), $duLieu[$tenTruong]);
            }
        } elseif (is_array($mangKiemTra["Kiểm Tra"])) {
            if (isset($mangKiemTra["Kiểm Tra"][0]) && is_string($mangKiemTra["Kiểm Tra"][0])) {
                $mangThamTriKiemTra = $mangKiemTra["Kiểm Tra"];
                $mangThamTriKiemTra[0] = $duLieu[$tenTruong];
                if (method_exists($this, $mangKiemTra["Kiểm Tra"][0])) {
                    $kiemTraLoi = call_user_func_array(array($this, $mangKiemTra["Kiểm Tra"][0]), $mangThamTriKiemTra);
                } elseif (method_exists($xuLy, $mangKiemTra["Kiểm Tra"][0])) {
                    $kiemTraLoi = call_user_func_array(array($xuLy, $mangKiemTra["Kiểm Tra"][0]), $mangThamTriKiemTra);
                }
            }
        } elseif (is_callable($mangKiemTra["Kiểm Tra"])) {
            $kiemTraLoi = $mangKiemTra["Kiểm Tra"]($duLieu[$tenTruong]);
        }
        return $kiemTraLoi;
    }

    /**
     * Phương thức kiểm tra dữ liệu số nguyên
     * 
     * CHỨC NĂNG:
     * Kiểm tra dữ liệu truyền vào có phải là số nguyên hay không
     * 
     * @param mixed $duLieu (tham trị truyền vào là dữ liệu cần kiểm tra)
     * @return boolean (kiểu trả về đúng (TRUE) khi dữ liệu truyền vào là số nguyên ngược lại sai (FALSE) khi nó không phải là số nguyên)
     */
    public static function soNguyen($duLieu = null) {
        return is_int($duLieu);
    }

    /**
     * Phương thức kiểm tra dữ liệu chỉ có chuỗi kí tự và số nguyên
     * 
     * CHỨC NĂNG:
     * Kiểm tra dữ liệu truyền vào có phải chỉ có chuỗi kí tự và số nguyên hay không
     * 
     * @param mixed $duLieu (tham trị truyền vào là dữ liệu cần kiểm tra)
     * @return boolean boolean (kiểu trả về đúng (TRUE) khi dữ liệu truyền vào là chuỗi kí tự và số nguyên ngược lại sai (FALSE) khi nó không phải là chuỗi kí tự và số nguyên)
     */
    public static function chuoiSoVaChu($duLieu = null) {
        if (!is_array($duLieu)) {
            $chuoiKiemTra = "/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/Du";
            if (preg_match($chuoiKiemTra, $duLieu)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu có phải là rổng hay không
     * 
     * CHỨC NĂNG:
     * Kiểm tra dữ liệu truyền vào có phải là rỏng hay không
     * 
     * 
     * @param mixed $duLieu (tham trị truyền vào là dữ liệu cần kiểm tra)
     * @return boolean (kiểu trả về đúng (TRUE) khi dữ liệu truyền vào không phải là rổng ngược lại sai (FALSE) khi nó là rổng)
     */
    public static function khongBoTrong($duLieu = null) {
        if (!is_array($duLieu)) {
            $chuoiKiemTra = "/[^\s]+/m";
            if (preg_match($chuoiKiemTra, $duLieu)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu thời gian
     * 
     * CHỨC NĂNG:
     * Kiểm tra dữ liệu truyền vào có phải theo định dạng thời gian hay không
     * 
     * MỞ RỘNG:
     * Bạn có thể tùy biến kiểu kiểm tra dữ liệu thời gian kiểm tra thông qua tham trị thứ 2 là kiểu dữ liệu kiểm tra như là "ngày tháng năm". Mặc định kiểu kiểm tra sẽ là "ngày tháng măm"
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi dữ liệu cần kiểm tra thời gian)
     * @param string $kieu (tham trị thứ 2 truyền vào dạng chuỗi là chuỗi kiểu kiểm tra dữ liệu thời gian)
     * @return boolean (kiểu trả về đúng (TRUE) khi dữ liệu truyền vào là dữ liệu thời gian ngược lại sai (FALSE) khi nó không phải là dữ liệu thời gian)
     */
    public static function thoiGian($duLieu = null, $kieu = "ngày tháng năm") {
        if (is_string($kieu)) {
            $kieu = mb_strtolower($kieu, "utf-8");
            $chuoiThoiGian = "";
            $kiTuPhanCach = "([-])";
            $mangKiemTra = array(
                "ngày tháng năm" => "%^(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)" .
                $kiTuPhanCach . "(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29" .
                $kiTuPhanCach . "0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])" .
                $kiTuPhanCach . "(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%",
                "năm tháng ngày" => "%^(?:(?:(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))" .
                $kiTuPhanCach . "(?:0?2\\1(?:29)))|(?:(?:(?:1[6-9]|[2-9]\\d)?\\d{2})" .
                $kiTuPhanCach . "(?:(?:(?:0?[13578]|1[02])\\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\\2(?:0?[1-9]|1\\d|2[0-8]))))$%",
                "tháng ngày năm" => "%^(?:(?:(?:0?[13578]|1[02])(\\/|-|\\.|\\x20)31)\\1|(?:(?:0?[13-9]|1[0-2])" .
                $kiTuPhanCach . "(?:29|30)\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:0?2" . $kiTuPhanCach . "29\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))" .
                $kiTuPhanCach . "(?:0?[1-9]|1\\d|2[0-8])\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%",
                "tháng năm" => "%^((0?[123456789]|10|11|12)" . $kiTuPhanCach . "(?:(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))|([0-9]{2})))$%",
                "năm tháng" => "%^((?:(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))|([0-9]{2}))" . $kiTuPhanCach . "(0?[123456789]|10|11|12))$%",
                "năm" => "%^((?:(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))|([0-9]{2})))$%"
            );
            if (is_array($duLieu)) {
                if ((isset($duLieu["Thang"]) && isset($duLieu["Ngay"]) && isset($duLieu["Nam"])) ||
                        (isset($duLieu["Thang"]) && isset($duLieu["Nam"])) ||
                        isset($duLieu["Nam"])
                ) {
                    switch ($kieu) {
                        case "ngày tháng năm":
                            $chuoiThoiGian = $duLieu["Ngay"] . "-" . $duLieu["Thang"] . "-" . $duLieu["Nam"];
                            break;
                        case "năm tháng ngày":
                            $chuoiThoiGian = $duLieu["Nam"] . "-" . $duLieu["Thang"] . "-" . $duLieu["Ngay"];
                            break;
                        case "tháng ngày năm":
                            $chuoiThoiGian = $duLieu["Thang"] . "-" . $duLieu["Ngay"] . "-" . $duLieu["Nam"];
                            break;
                        case "tháng năm":
                            $chuoiThoiGian = $duLieu["Thang"] . "-" . $duLieu["Nam"];
                            break;
                        case "năm tháng":
                            $chuoiThoiGian = $duLieu["Nam"] . "-" . $duLieu["Thang"];
                            break;
                        case "năm":
                            $chuoiThoiGian = $duLieu["Nam"];
                            break;
                    }
                }
            } elseif (is_string($duLieu)) {
                $chuoiThoiGian = $duLieu;
            }
            if (isset($mangKiemTra[$kieu])) {
                if (preg_match($mangKiemTra[$kieu], $chuoiThoiGian)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu hòm thư
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra xem dữ liệu truyền vào có phải là hòm thư (email) hay không
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần kiểm tra xem có phải là hòm thư hay không)
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu truyền vào là hòm thư ngược lại sai (FALSE) khi nó không phải là hòm thư)
     */
    public static function homThu($duLieu = null) {
        if (filter_var($duLieu, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu số nằm trong khoảng
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra số dữ liệu truyền vào có nằm trong khoảng chỉ định hay không
     * 
     * @param mixed $duLieu (tham trị thứ 1 truyền vào dạng số là số cần kiểm tra xem có nằm trong khoảng chỉ định hay không)
     * @param mixed $nhoNhat (tham trị thứ 2 truyền vào dạng số là số giới hạn nhỏ nhất cần kiểm tra)
     * @param mixed $lonNhat (tham trị thứ 3 truyền vào dạng số là số giới hạn lớn nhất cần kiểm tra)
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu truyền vào nằm trong khoảng chỉ định ngược lại sai (FALSE) khi nó không nằm trong khoảng chỉ định)
     */
    public static function soNamTrongKhoang($duLieu = null, $nhoNhat = 0, $lonNhat = 0) {
        if (is_numeric($duLieu)) {
            if ($duLieu >= $nhoNhat && $duLieu <= $lonNhat) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu số lớn hơn
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra số dữ liệu truyền vào có lớn hơn số chỉ định hay không
     * 
     * @param mixed $duLieu (tham trị thứ 1 truyền vào dạng số là số cần kiểm tra xem có lớn hơn số chỉ định hay không)
     * @param mixed $lonHon (tham trị thứ 2 truyền vào dạng số là số chỉ định lớn hơn)
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu truyền vào lớn hơn số chỉ định ngược lại sai (FALSE) khi nó không lớn hơn số chỉ định)
     */
    public static function soLonHon($duLieu = null, $lonHon = 0) {
        if (is_numeric($duLieu)) {
            if ($duLieu > $lonHon) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu số nhỏ hơn
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra số dữ liệu truyền vào có nhỏ hơn số chỉ định hay không
     * 
     * @param mixed $duLieu (tham trị thứ 1 truyền vào dạng số là số cần kiểm tra xem có nhỏ hơn số chỉ định hay không)
     * @param mixed $nhoHon (tham trị thứ 2 truyền vào dạng số là số chỉ định nhỏ hơn)
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu truyền vào nhỏ hơn số chỉ định ngược lại sai (FALSE) khi nó không lớn hơn số chỉ định)
     */
    public static function soNhoHon($duLieu = null, $nhoHon = 0) {
        if (is_numeric($duLieu)) {
            if ($duLieu < $nhoHon) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra dữ liệu số
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra dữ liệu truyền vào có phải là số hay không
     * 
     * @param mixed $duLieu (tham trị truyền vào là dữ liệu kiểm tra xem có phải là số hay không)
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu truyền vào là số ngược lại sai (FALSE) khi nó không phải là số)
     */
    public static function duLieuSo($duLieu = null) {
        return is_numeric($duLieu);
    }

    /**
     * Phương thức kiểm tra độ dài chuỗi nằm trong khoảng
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi dữ liệu truyền vào có độ dài nằm trong khoảng chỉ định hay không
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần kiểm tra độ dài nằm trong khoảng chỉ định)
     * @param int $nhoNhat (tham trị thứ 2 truyền vào là số nguyên là số giới hạn nhỏ nhất)
     * @param int $lonNhat (tham trị thứ 3 truyền vào là số nguyên là số giới hạn lớn nhất)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về có độ dài nằm trong khoảng chỉ định ngược lại sai (FALSE) khi độ dài nó không nằm trong khoảng chỉ định)
     */
    public static function doDaiChuoiTrongKhoang($duLieu = null, $nhoNhat = 0, $lonNhat = 0) {
        if (is_string($duLieu)) {
            $doDaiChuoi = mb_strlen($duLieu, "utf-8");
            if ($doDaiChuoi >= $nhoNhat && $doDaiChuoi <= $lonNhat) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra độ dài chuỗi lớn hơn
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi dữ liệu truyền vào có độ dài lớn hơn số chỉ định hay không
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần kiểm tra độ dài lớn hơn số chỉ định)
     * @param int $lonHon (tham trị thứ 2 truyền vào là số nguyên là số giới hạn phải lớn hơn)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về có độ dài lớn hơn số chỉ định ngược lại sai (FALSE) khi độ dài nó không lớn hơn số chỉ định)
     */
    public static function doDaiChuoiLonHon($duLieu = null, $lonHon = 0) {
        if (is_string($duLieu)) {
            $doDaiChuoi = mb_strlen($duLieu, "utf-8");
            if ($doDaiChuoi > $lonHon) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra độ dài chuỗi nhỏ hơn
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi dữ liệu truyền vào có độ dài nhỏ hơn số chỉ định hay không
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần kiểm tra độ dài nhỏ hơn số chỉ định)
     * @param int $nhoHon (tham trị thứ 2 truyền vào là số nguyên là số giới hạn phải nhỏ hơn)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về có độ dài nhỏ hơn số chỉ định ngược lại sai (FALSE) khi độ dài nó không nhỏ hơn số chỉ định)
     */
    public static function doDaiChuoiNhoHon($duLieu, $nhoHon = 0) {
        if (is_string($duLieu)) {
            $doDaiChuoi = mb_strlen($duLieu, "utf-8");
            if ($doDaiChuoi < $nhoHon) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra địa chỉ IP
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi truyền vào có phải là địa chỉ IP hay không
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần kiểm tra xem có phải là địa chỉ IP không)
     * @param string $dangIP (tham trị thứ 2 truyền vào là dạng IP kiểm tra ipv4 hoặc ipv6 mặc định nếu không truyền tham trị này thì phương thức sẽ kiểm tra cả 2 dạng IP)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về là địa chỉ IP ngược lại sai (FALSE) khi nó không phải là địa chỉ IP)
     */
    public static function diaChiIP($duLieu = null, $dangIP = 0) {
        if (is_string($duLieu)) {
            $kiemTra = strtolower($dangIP);
            if ($kiemTra === 'ipv4') {
                $dangIP = FILTER_FLAG_IPV4;
            } elseif ($kiemTra === 'ipv6') {
                $dangIP = FILTER_FLAG_IPV6;
            } else {
                $dangIP = 0;
            }
            if (filter_var($duLieu, FILTER_VALIDATE_IP, array('flags' => $dangIP)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra đường dẫn liên kết
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi truyền về có phải là địa chỉ liên kết (URL) hay không
     * 
     * @param string $duLieu (tham trị truyền vào dạng chuỗi là chuỗi cần xác định có phải là địa chỉ liên kết hay không)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về là địa chỉ liên kết ngược lại sai (FALSE) khi nó không phải là địa chỉ liên kết)
     */
    public static function duongDanLienKet($duLieu = null) {
        if (is_string($duLieu)) {
            if (filter_var($duLieu, FILTER_VALIDATE_URL) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức kiểm tra số điện thoại Việt Nam
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra chuỗi truyền về có phải là số điện thoại Việt Nam hay không
     * 
     * MỞ RỘNG:
     * Bạn có thể truyền tham trị thứ 2 cho phương thức này để xác định nhà mạng muốn kiểm tra.
     * 
     * Ví dụ: tôi muốn kiểm tra dữ liệu chuỗi truyền vào là số của viettel thì tôi gọi như sau "soDiemThoaiVN(dữ liệu kiểm tra, "viettel")"
     * 
     * Mặc định phương thức này sẽ kiểm tra mọi nhà mạng cho đến số bàn (viettel, mobifone, sphone, beeline, vinaphone, vnmobile, evn, số bàn)
     * 
     * @param string $duLieu (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi cần xác định có phải là số điện thoại Việt Nam hay không)
     * @param type $dang (tham trị thứ 2 truyền vào dạng chuỗi là chuỗi cần xác định nhà mạng)
     * @return boolean (giá trị trả về đúng (TRUE) khi chuỗi truyền về là số điện thoại ngược lại sai (FALSE) khi nó không phải là số điện thoại)
     */
    public static function soDienThoaiVN($duLieu = null, $dang = null) {
        $mangKiemTra = array(
            "viettel" => "/^(09[7-8]|016[2-9])[\d]{7}$/",
            "mobifone" => "/^(090|093|012[0-2]|0126|0128)[\d]{7}$/",
            "vinaphone" => "/^(091|094|012[3-5]|0127|0129)[\d]{7}$/",
            "beeline" => "/^(099|0199)[\d]{7}$/",
            "vnmobile" => "/^(092|0188)[\d]{7}$/",
            "sphone" => "/^095[\d]{7}$/",
            "evn" => "/^096[\d]{7}$/",
            "soban" => "/^(\(?[\d]{1,3}\)?)?3[\d]{7}$/"
        );
        if (is_string($duLieu)) {
            if ($dang === null) {
                foreach ($mangKiemTra as $dangDT) {
                    if (preg_match($dangDT, $duLieu)) {
                        return true;
                    }
                }
            } elseif (is_string($dang)) {
                $dang = strtolower($dang);
                if (isset($mangKiemTra[$dang])) {
                    if (preg_match($mangKiemTra[$dang], $duLieu)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}

?>