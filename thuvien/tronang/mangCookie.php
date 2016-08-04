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
 * Lớp đối tượng mảng cookie
 * 
 * CHỨC NĂNG CHÍNH:
 * Có các thuộc tính và phương thức hổ trợ xử lý các phần tử trên mảng $_COOKIE
 */

class mangCookie {

    /**
     * Phương thức thiết lập phần tử trên mảng $_COOKIE
     * 
     * CHỨC NĂNG:
     * Khởi tạo và gán giá trị cho phần tử trên mảng $_COOKIE
     * 
     * @param string $tenCookie (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng $_COOKIE)
     * @param mixed $giaTri (tham trị thứ 2 truyền vào là dữ liệu muốn gán cho phần tử trên mảng $_COOKIE)
     * @param float $thoiGianTonTai (tham trị thứ 3 truyền vào dạng số là giá trị thời gian tồn tại của phần tử thiết lập trên mảng $_COOKIE)
     * @param string $dat (tham trị thứ 4 truyền vào dạng chuỗi là đường dẫn đặt phần tử trên mảng $_COOKIE)
     * @param string $tenMien (tham trị thứ 5 truyền vào dạng chuỗi là chuỗi tên miền mà phần tử trên mảng $_COOKIE sẽ hoạt động)
     * @param type $baoMat (tham trị thứ 6 truyền vào dạng đúng (TRUE) khi bật tính năng bảo mật phần tử trên mảng $_COOKIE ngược lại (FALSE) thì tắt đi)
     * @param type $cookieTonTaiTrenHttp (tham trị thứ 7 truyền vào dạng đúng (TRUE) khi chỉ chấp nhận phần tử $_COOKIE thiết lập tồn tại trên giao thức kết nối HTTP và ngược lại là sai (FALSE) khi muốn cho tồn tại ở mọi giao thức)
     */
    public static function thietLap($tenCookie, $giaTri = null, $thoiGianTonTai = null, $dat = null, $tenMien = null, $baoMat = false, $cookieTonTaiTrenHttp = false) {
        $khoaCookie = isset(thietLapCha::$cauHinhMayChu["COOKIE"], thietLapCha::$cauHinhMayChu["COOKIE"]["Khóa Mã Hóa 2 Chiều"]) ? thietLapCha::$cauHinhMayChu["COOKIE"]["Khóa Mã Hóa 2 Chiều"] : "";
        if ((is_string($tenCookie) || is_numeric($tenCookie))) {
            $thoiGianTonTai = (is_null($thoiGianTonTai) && isset(thietLapCha::$cauHinhMayChu["COOKIE"], thietLapCha::$cauHinhMayChu["COOKIE"]["Thời Gian Tự Hủy Phần Tử Mặc Định"])) ? thietLapCha::$cauHinhMayChu["COOKIE"]["Thời Gian Tự Hủy Phần Tử Mặc Định"] + time() : $thoiGianTonTai;
            $giaTri = (is_string($giaTri) || is_numeric($giaTri)) ? maHoa::maHoaBlowFish($giaTri, maHoa::maHoaChuoi($khoaCookie, "md5")) : $giaTri;
            $luuCookie = setcookie($tenCookie, $giaTri, $thoiGianTonTai, $dat, $tenMien, $baoMat, $cookieTonTaiTrenHttp);
            return is_bool($luuCookie) ? $luuCookie : false;
        }
        return false;
    }

    /**
     * Phương thức lấy giá trị phần tử trên mảng $_COOKIE
     * 
     * CHỨC NĂNG:
     * Lấy ra giá trị phần tử trên mảng $_COOKIE dựa trên tên phần tử truyền vào phương thức
     * 
     * @param string $tenCookie (tham trị truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng $_COOKIE cần lấy giá trị)
     * @return mixed (giá trị trả về là sai (FALSE) khi không lấy được giá trị phần tử trên mảng ngược lại sẽ trả về giá trị phần tử trên mảng nếu lấy được)
     */
    public static function lay($tenCookie) {
        $khoaCookie = isset(thietLapCha::$cauHinhMayChu["COOKIE"], thietLapCha::$cauHinhMayChu["COOKIE"]["Khóa Mã Hóa 2 Chiều"]) ? thietLapCha::$cauHinhMayChu["COOKIE"]["Khóa Mã Hóa 2 Chiều"] : "";
        if (is_string($tenCookie) || is_numeric($tenCookie)) {
            if (isset($_COOKIE) && is_array($_COOKIE) && isset($_COOKIE[$tenCookie])) {
                return maHoa::giaiMaBlowFish($_COOKIE[$tenCookie], maHoa::maHoaChuoi($khoaCookie, "md5"));
            }
        }
        return false;
    }

    /**
     * Phương thức xóa phần tử trên mảng $_COOKIE
     * 
     * CHỨC NĂNG:
     * Xóa phần tử trên mảng $_COOKIE dựa trên tên phần tử truyền vào cần xóa
     * 
     * @param string $tenCookie (tham trị truyền vào dạng chuỗi là chuỗi tên phần tử trên mảng $_COOKIE cần xóa)
     * @return boolean (giá trị trả về đúng (TRUE) khi đã xóa phần tử thành công và sai (FALSE) khi xóa phần tử trên mảng $_COOKIE thất bại)
     */
    public static function xoa($tenCookie) {
        if (is_string($tenCookie) || is_numeric($tenCookie)) {
            if (isset($_COOKIE) && is_array($_COOKIE) && isset($_COOKIE[$tenCookie])) {
                unset($_COOKIE[$tenCookie]);
                self::thietLap($tenCookie, NULL, -1);
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức xóa toàn bộ phần tử trên mảng $_COOKIE
     * 
     * CHỨC NĂNG:
     * Xóa toàn bộ phần tử trên mảng $_COOKIE
     * 
     * @return boolean (giá trị trả về đúng (TRUE) khi toàn bộ dữ liệu trên mảng $_COOKIE bị xóa thành công ngược lại sai (FALSE) khi không xóa được)
     */
    public static function xoaToanBo() {
        if (isset($_COOKIE)) {
            $mangCookieTam = $_COOKIE;
            foreach ($mangCookieTam as $k => $v) {
                if ($k !== "session_id") {
                    self::thietLap($k, NULL, -1);
                }
            }
            return true;
        }
        return false;
    }

}
