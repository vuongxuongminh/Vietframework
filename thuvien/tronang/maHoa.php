<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                27/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng mã hóa dữ liệu
 * 
 * CHỨC NĂNG:
 * Hổ trợ phương thức và thuộc tính mã hóa dữ liệu như là md5, sha1, sha256
 */

class maHoa {

    /**
     * Thuộc tính kiểu mã hóa
     * 
     * CHỨC NĂNG:
     * Xác định kiểu mã hóa cho phương thức maHoaChuoi
     * 
     * @var string (kiểu dữ liệu dạng chuỗi)
     */
    private static $kieuMaHoa = "sha1";

    /**
     * Phương thức mã hóa chuỗi
     * 
     * CHỨC NĂNG:
     * Dùng để hổ trợ mã hóa các chuỗi truyền vào theo dạng mã hóa chỉ định
     * 
     * @param string $chuoi (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi dữ liệu cần mã hóa)
     * @param string $kieu (tham trị thứ 2 truyền vào dạng chuỗi là dạng mã hóa dữ liệu nếu không truyền tham trị này thì dạng mã hóa dữ liệu sẽ được quy định bởi thuộc tính kieuMaHoa
     * @return mixed (kiểu trả về dạng chuỗi là chuỗi đã mã hóa nếu thành công, ngược lại là sai (FALSE) nếu thất bại)
     */
    public static function maHoaChuoi($chuoi, $kieu = null) {
        if (is_string($chuoi) || is_numeric($chuoi)) {         
            if ($kieu === null) {
                $kieu = self::$kieuMaHoa;
            }
            $kieu = strtolower($kieu);
            if ($kieu === 'blowfish') {
                return self::maHoaBlowFish($chuoi);
            }               
            if (!$kieu || $kieu === 'sha1') {
                if (function_exists('sha1')) {
                    return sha1($chuoi);
                }
                $kieu = 'sha256';
            }
            if ($kieu === 'sha256' && function_exists('mhash')) {
                return bin2hex(mhash(MHASH_SHA256, $chuoi));
            }
            if (function_exists('hash')) {
                return hash($kieu, $chuoi);
            }
            if ($kieu === 'md5' && function_exists('md5')) {
                return md5($chuoi);
            }
        }
        return false;
    }

    /**
     * Phương thức thiết lập kiểu mã hóa
     * 
     * CHỨC NĂNG:
     * Dùng để thiết lập giá trị chuỗi cho thuộc tính kieuMaHoa
     * 
     * @param string $kieu (tham trị truyền vào dạng chuỗi là chuỗi quy định dạng mã hóa dữ liệu như md5, sha1, sha256)
     * @return boolean (kiểu trả về đúng (TRUE) khi thiết lập kiểu mã hóa cho thuộc tính kieuMaHoa thành công ngược lại sai (FALSE) khi thiết lập giá trị cho thuộc tính kieuMaHoa thất bại)
     */
    public static function thietLapKieuMaHoa($kieu = null) {
        if (is_string($kieu)) {
            self::$kieuMaHoa = $kieu;
            return true;
        }
        return false;
    }

    /*     * **************************************** MÃ HÓA 2 CHIỀU BLOW FISH HASH ***************************************** */

    /**
     * Khóa giải mã 2 chiều blowfish dùng để mã hóa và giải mã
     * @var string
     */
    public static $khoaMaHoaHaiChieu = "";

    /**
     * Phương thức mã hóa base64
     * 
     * Hổ trợ lọc đi những ký tự toán học hổ trợ an toàn dữ liệu khi đưa vào database
     * @param string $chuoiLocKyTu
     * @return string
     */
    public static function maHoaBase64($chuoiLocKyTu) {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($chuoiLocKyTu));
    }

    /**
     * Phương thức giải mã base64
     * 
     * Hổ trợ ghép lại những ký tự toán học đã lọc từ phương thức mã hóa base 64 nhằm giải mã được
     * @param string $chuoiMaHoa
     * @return string
     */
    public static function giaiMaBase64($chuoiMaHoa) {
        $chuoiMaHoa = str_replace(array('-', '_'), array('+', '/'), $chuoiMaHoa);
        $mod4 = strlen($chuoiMaHoa) % 4;
        if ($mod4) {
            $chuoiMaHoa .= substr('====', $mod4);
        }
        return base64_decode($chuoiMaHoa);
    }

    /**
     * Phương thức mã hóa blow fish
     * 
     * Hổ trợ mã hóa dạng 2 chiều có khóa an toàn (blow fish)
     * @param string $chuoiMaHoa
     * @return string
     */
    public static function maHoaBlowFish($chuoiMaHoa = false, $khoa = null) {
        if (!$chuoiMaHoa) {
            return false;
        }
        if ($khoa === null) {
            $khoa = self::$khoaMaHoaHaiChieu;
        }
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $khoa, $chuoiMaHoa, MCRYPT_MODE_ECB, $iv);
        return trim(self::maHoaBase64($crypttext));
    }

    /**
     * Phương thức giải mã blow fish
     * 
     * Hổ trợ giải mã dạng 2 chiều có khóa an toàn (blow fish)
     * @param string $chuoiMaHoa
     * @return string
     */
    public static function giaiMaBlowFish($chuoiMaHoa = false, $khoa = null) {
        if (!$chuoiMaHoa) {
            return false;
        }
        if ($khoa === null) {
            $khoa = self::$khoaMaHoaHaiChieu;
        }
        $crypttext = self::giaiMaBase64($chuoiMaHoa);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $khoa, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

}
