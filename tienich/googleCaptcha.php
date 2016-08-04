<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                03/03/2015
 * PHIÊN BẢN                1.0.5
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng hòm thư
 * 
 * CHỨC NĂNG CHÍNH:
 * Hổ trợ các phương thức khởi tạo khung captcha trên HTML và kiểm tra tính hợp lệ từ google
 * 
 * Lưu ý:
 * Khi sử dụng lớp đối tượng này bạn phải chắc rằng bạn đã cấu hình thư viện CURL tại file cấu hinh php.ini (kiểm tra các file dll trong phần extension có php_openssl.dll hay chưa)
 * 
 * Lớp đối tượng này hoạt động dựa trên GOOGLE CAPTCHA
 */

class googleCaptcha {

    /**
     * Thuộc tính khóa xác minh
     * 
     * CHỨC NĂNG:
     * Dùng để gửi lên google captcha để kiểm tra dữ liệu google captcha người dùng gửi về đúng hay sai
     * 
     * @var string (kiểu dữ liệu dạng chuỗi)
     */
    private static $khoaXacMinh = "nhập khóa serect của google vào đây";

    /**
     * Thuộc tính khóa công khai
     * 
     * CHỨC NĂNG:
     * Dùng để khởi tạo google captcha phía tầng hiển thị (VIEW)
     * 
     * @var string (kiểu dữ liệu dạng chuỗi)
     */
    private static $khoaCongKhai = "nhập khóa public của google vào đây";

    /**
     * Phương thức khởi tạo mã captcha
     * 
     * CHỨC NĂNG:
     * Dùng để khởi tạo google captcha phía tầng hiển thị (VIEW) một cách đơn giản
     * 
     * @param array $mangThuocTinh (tham trị thứ nhất truyền vào dạng mãng chứa các thuộc tính cho thẻ div tạo google captcha như là data-theme, data-callback...)
     * @param array $chieuDai (tham trị thứ 2 truyền vào dạng số nhằm định dạng kích thước bề ngang cho google captcha)
     * @param array $chieuCao (tham trị thứ 3 truyền vào dạng số nhằm định dạng kích thước chiều cao cho google captcha)
     * @return string (giá trị trả về là dạng chuỗi html để xuất lên tầng hiển thị VIEW)
     */
    public static function khoiTaoMaCaptCha($mangThuocTinh = array(
        "data-theme" => "light"
    ), $chieuDai = 304, $chieuCao = 78) {
        $chieuDaiMacDinh = 304;
        $chieuCaoMacDinh = 78;
        $scaleX = 1;
        $scaleY = 1;
        if ($chieuDai > $chieuDaiMacDinh) {
            $scaleX += ($chieuDai - $chieuDaiMacDinh) / $chieuDaiMacDinh;
        } elseif ($chieuDaiMacDinh > $chieuDai) {
            $scaleX -= ($chieuDaiMacDinh - $chieuDai) / $chieuDaiMacDinh;
        }
        if ($chieuCao > $chieuCaoMacDinh) {
            $scaleY += ($chieuCao - $chieuCaoMacDinh) / $chieuCaoMacDinh;
        } elseif ($chieuCaoMacDinh > $chieuCao) {
            $scaleY -= ($chieuCaoMacDinh - $chieuCao) / $chieuCaoMacDinh;
        }
        $thuVienJavaScript = "<script src='https://www.google.com/recaptcha/api.js?hl=vi&s=" . microtime() . "'></script>";
        $chuoiThuocTinh = "data-sitekey='" . self::$khoaCongKhai . "'";
        foreach ($mangThuocTinh as $thuocTinh => $giaTri) {
            $chuoiThuocTinh .= " $thuocTinh='$giaTri'";
        }
        $chuoiCaptcha = "<div style='transform-origin:0 0; -o-transform:scale($scaleX, $scaleY); -ms-transform:scale($scaleX, $scaleY); -webkit-transform:scale($scaleX, $scaleY); transform:scale($scaleX, $scaleY)' class='g-recaptcha' $chuoiThuocTinh></div>";
        return $thuVienJavaScript . $chuoiCaptcha;
    }

    /**
     * Phương thức kiểm tra tính hợp lệ của captcha người dùng gửi về
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra tính đúng sai của captcha người dùng trả về
     * 
     * @return string (giá trị trả về là đúng nếu người dùng nhập đúng captcha và ngược lại)
     */
    public static function kiemTraCaptcha($captcha = '') {
        $curl = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10000);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $layIpMayKhach = vietFrameWork::$heThong->layIpMayKhach();
        curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=" . self::$khoaXacMinh . "&response=" . urlencode($captcha) . "&remoteip=" . urlencode($layIpMayKhach));
        $phanHoi = curl_exec($curl);
        curl_close($curl);
        $xuLyPhanHoi = json_decode($phanHoi, true);
        if (isset($xuLyPhanHoi["success"])) {
            return $xuLyPhanHoi["success"];
        }
        return false;
    }

}

