<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                18/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng mảng tiện ích session
 * 
 * CHỨC NĂNG CHÍNH:
 * Chứa cac phương thức xử lý thao tác trên thành phần mảng $_SESSION
 * 
 */

class mangSession {

    /**
     * Phương thức thiết lập phần tử trên mảng $_SESSION
     * 
     * CHỨC NĂNG:
     * Thiết lập phần tử trên mảng $_SESSION
     * 
     * @param string $ten (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng $_SESSION)
     * @param mixed $giaTri (tham trị thứ 2 truyền vào là dữ liệu thiết lập cho phần tử trên mảng $_SESSION)
     * @return boolean (giá trị trả về đúng (TRUE) khi thiết lập phần tử trên mảng $_SEESION thành công ngược lại (FALSE) khi thiết lập thất bại)
     */
    public static function thietLap($ten = null, $giaTri = null, $thoiGianTonTai = null) {
        self::kiemTraVoHieuHoaSession();
        $thoiGianTonTai = is_null($thoiGianTonTai) ? thietLapCha::$cauHinhMayChu["SESSION"]["Thời Gian Tự Hủy Phần Tử Mặc Định"] : $thoiGianTonTai;
        if ((is_string($ten) || is_numeric($ten)) && is_numeric($thoiGianTonTai)) {
            $_SESSION[$ten] = array($giaTri, time() + $thoiGianTonTai);
            return true;
        } elseif (is_array($ten)) {
            foreach ($ten as $k => $v) {
                if (is_string($k) || is_numeric($ten)) {
                    if (!is_array($v) || !isset($v[0], $v[1])) {
                        $_SESSION[$k] = array($v, $thoiGianTonTai);
                    } else {
                        $_SESSION[$k] = array($v[0], $v[1]);
                    }
                } else {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Phương thức lấy giá trị phần tử trên mảng $_SESSION
     * 
     * CHỨC NĂNG:
     * Dùng để lấy giá trị phần tử trên mảng $_SESSION thông qua tên phần tử truyền vào phương thức
     * 
     * @param mixed $ten (tham trị truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng $_SESSION cần lấy dữ liệu hoặc một mảng tập hợp chưa nhiều tên cần lấy)
     * @return mixed (giá trị trả về là dữ liệu phần tử nếu tên phần tử có tồn tại trong mảng $_SESSION ngược lại sẽ trả về sai (FALSE) khi tên phần tử không tồn tại hoặc một mảng tập hợp các giá trị phần tử cần lấy)
     */
    public static function lay($ten = null) {
        self::kiemTraVoHieuHoaSession();
        if (is_string($ten) || is_numeric($ten)) {
            if (isset($_SESSION[$ten]) && is_array($_SESSION[$ten]) && isset($_SESSION[$ten][0], $_SESSION[$ten][1])) {
                $thoiGianHienTai = time();
                if ($thoiGianHienTai >= $_SESSION[$ten][1]) {
                    unset($_SESSION[$ten]);
                    return false;
                }
                return $_SESSION[$ten][0];
            }
        } elseif (is_array($ten)) {
            $mangGiaTriTraVe = array();
            foreach ($ten as $khoa) {
                if (isset($_SESSION[$khoa]) && is_array($_SESSION[$khoa]) && isset($_SESSION[$khoa][0], $_SESSION[$khoa][1])) {
                    $thoiGianHienTai = time();
                    if ($thoiGianHienTai >= $_SESSION[$khoa][1]) {
                        unset($_SESSION[$khoa]);
                        $mangGiaTriTraVe[$khoa] = false;
                    } else {
                        $mangGiaTriTraVe[$khoa] = $_SESSION[$khoa][0];
                    }
                } else {
                    $mangGiaTriTraVe[$khoa] = false;
                }
            }
            return $mangGiaTriTraVe;
        }
        return false;
    }

    /**
     * Phương thức xóa phần tử trên mảng $_SESSION
     * 
     * CHỨC NĂNG:
     * Dùng để xóa dữ liệu phần tử trên mảng $_SESSION thông qua tên phần tử truyền vào phương thức
     * 
     * @param mixed $ten (tham trị truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng  $_SESSION cần xóa hoặc là một tập hợp mảng các tên phần tử cần xóa trên mảng $_SESSION)
     * @return boolean (giá trị trả về là đúng (TRUE) khi phần tử bị xóa khỏi mảng $_SESSION thành công, ngược lại là sai (FALSE) khi xóa thất bại)
     */
    public static function xoa($ten = null) {
        self::kiemTraVoHieuHoaSession();
        if (is_string($ten) || is_numeric($ten)) {
            unset($_SESSION[$ten]);
        } elseif (is_array($ten)) {
            foreach ($ten as $khoa) {
                unset($_SESSION[$khoa]);
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * Phương thức lấy mã bảo vệ
     * 
     * CHỨC NĂNG:
     * Hổ trợ lấy mã bảo về khi gọi phương thức napMaBaoVe tại tầng hiển thị (view). Dùng để kiểm tra lỗi (validate) tại phương thức chỉ định trong mảng kiemTraLoi ở tầng xử lý (model)
     * 
     * @param string $tenBang (tham trị truyền vào là tên bảng biểu mẫu (form) gửi về từ client)
     * @return array (giá trị trả về là mảng bao gồm các phần tử mang giá trị là chuỗi mã bảo vệ có khóa trùng với tên trường dữ liệu)
     */
    public static function layMaBaoVe($tenBang = null) {
        self::kiemTraVoHieuHoaSession();
        $mangMaBaoVe = array();
        if (isset($_SESSION['vietFrameWork']['Mã Bảo Vệ'][$tenBang])) {
            $maBaoVe = $_SESSION['vietFrameWork']['Mã Bảo Vệ'][$tenBang];
            if (is_array($maBaoVe)) {
                foreach ($maBaoVe as $k => $v) {
                    $mangMaBaoVe[$k] = $v;
                }
            }
            unset($_SESSION['vietFrameWork']['Mã Bảo Vệ'][$tenBang]);
        }
        return $mangMaBaoVe;
    }

    /**
     * Phương thức xóa toàn bộ mảng $_SESSION
     * 
     * CHỨC NĂNG:
     * Dùng để xóa toàn bộ mảng $_SESSION
     * 
     * @return boolean (kiểu trả về là đúng khi xóa toàn bộ mảng $_SESSION ngược lại là sai (FALSE) khi không thực hiện được hàm xóa toàn bộ mảng $_SESSION)
     */
    public static function xoaToanBo() {
        if (isset($_SESSION)) {
            $_SESSION = array();
            return true;
        }
        return false;
    }

    /**
     * Phương thức kiểm tra vô hiệu hóa mảng $_SESSION
     * 
     * CHỨC NĂNG:
     * Dựa trên thời gian tồn tại của mảng $_SESSION thông qua thuộc tính thoiGianVoHieuHoa. Nếu người dùng truy cập vượt quá số giây quy định thì mảng $_SESSION tự động bị xóa
     */
    private static function kiemTraVoHieuHoaSession() {
        $thoiGianLuuTru = thietLapCha::$cauHinhMayChu["SESSION"]["Thời Gian Tự Hủy Toàn Bộ"];
        if (isset($_SESSION['vietFrameWork'], $_SESSION['vietFrameWork']['Thời Gian Lưu Trữ Session'])) {
            if ((time() >= ($_SESSION['vietFrameWork']['Thời Gian Lưu Trữ Session']))) {
                self::xoaToanBo();
            }
        }
        if (!isset($_SESSION['vietFrameWork']) || !is_array($_SESSION['vietFrameWork'])) {
            $_SESSION['vietFrameWork'] = array();
        }
        $_SESSION['vietFrameWork']['Thời Gian Lưu Trữ Session'] = time() + $thoiGianLuuTru;
    }

}
