<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                22/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng cơ sở dữ liệu
 * 
 * CHỨC NĂNG CHÍNH:
 * Hổ trợ cho tầng điều hướng (model) kết nối và truy vấn cơ sở dữ liệu (database).
 */

class coSoDuLieu extends PDO {

    /**
     * Phương thức khởi tạo lớp đối tượng cơ sở dữ liệu
     * 
     * CHỨC NĂNG:
     * Dựa trên các thông số thiết lập cấu hình CSDL mà kết nối đến cơ sở dữ liệu thông qua lớp đối tượng kế thừa PDO
     */
    public function __construct() {
        $thongTinCSDL = thietLapCha::$cauHinhCSDL;
        $loaiCSDL = $thongTinCSDL['Loại CSDL'];
        $diaChiCSDL = $thongTinCSDL['Địa Chỉ CSDL'];
        $tenDangNhapCSDL = $thongTinCSDL['Tên Đăng Nhập CSDL'];
        $matKhauCSDL = $thongTinCSDL['Mật Khẩu CSDL'];
        $tenCSDL = $thongTinCSDL['Tên CSDL'];
        $kieuLuuTru = $thongTinCSDL['Kiểu Lưu Trữ CSDL'];
        $congKetNoi = $thongTinCSDL['Cổng Kết Nối CSDL'];
        try {
            if ($loaiCSDL !== "sqlsrv") {
                $dsn = $loaiCSDL . ":host=" . $diaChiCSDL . ";port=" . $congKetNoi . ";dbname=" . $tenCSDL;
                parent::__construct($dsn, $tenDangNhapCSDL, $matKhauCSDL, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $kieuLuuTru"));
            } else {
                $dsn = $loaiCSDL . ":Server=" . $diaChiCSDL . "," . $congKetNoi . ";Database=" . $tenCSDL;
                parent::__construct($dsn, $tenDangNhapCSDL, $matKhauCSDL, array(PDO::SQLSRV_ATTR_ENCODING => $kieuLuuTru));
            }
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            baoLoiCha::chayTrangBaoLoi("Lỗi Cơ Sở Dữ Liệu", array($e->getCode(), $e->getMessage()));
            die;
        }
    }

    public static function kiemTraKetNoiCSDL() {
        $thongTinCSDL = thietLapCha::$cauHinhCSDL;
        $loaiCSDL = $thongTinCSDL['Loại CSDL'];
        $diaChiCSDL = $thongTinCSDL['Địa Chỉ CSDL'];
        $tenDangNhapCSDL = $thongTinCSDL['Tên Đăng Nhập CSDL'];
        $matKhauCSDL = $thongTinCSDL['Mật Khẩu CSDL'];
        $tenCSDL = $thongTinCSDL['Tên CSDL'];
        $congKetNoi = $thongTinCSDL['Cổng Kết Nối CSDL'];
        try {
            if ($loaiCSDL !== "sqlsrv") {
                $dsn = $loaiCSDL . ":host=" . $diaChiCSDL . ";port=" . $congKetNoi . ";dbname=" . $tenCSDL;
                new PDO($dsn, $tenDangNhapCSDL, $matKhauCSDL);
            } else {
                $dsn = $loaiCSDL . ":Server=" . $diaChiCSDL . "," . $congKetNoi . ";Database=" . $tenCSDL;
                new PDO($dsn, $tenDangNhapCSDL, $matKhauCSDL);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }

    /**
     * Phương thức truy vấn cơ sở dữ liệu
     * 
     * CHỨC NĂNG:
     * Dùng để truy vấn thực thi các câu lệnh trên cơ sở dữ liệu như thêm, xóa, sửa, hiển thị, tạo bảng, xóa bảng...
     * 
     * Ví dụ: tôi muốn lấy dữ liệu trên bảng sanpham nơi mà có trường id = 1 thì tui sẽ gọi như sau "truyVan("select * from `sanpham` where `id` = :id", array("id" => 1))"
     * 
     * Bạn tìm hiểu về cú pháp truy vấn blindValue của lớp đối tượng PDO sẽ hiểu rỏ hơn về cách sử dụng phương thức này
     * 
     * @link: http://php.net/manual/en/pdostatement.bindvalue.php
     * 
     * @param string $cauTruyVan (tham trị thứ 1 dạng chuỗi là chuỗi truy vấn csdl pdo)
     * @param array $thamTri (tham trị thứ 2 dạng mảng là mảng giá trị các thuộc tính trong câu truy vấn)
     * @return object (kiểu trả về là dạng đối tượng)
     */
    public function truyVan($cauTruyVan, $thamTri = array()) {
        try {
            $chuanBi = $this->prepare($cauTruyVan);
            foreach ($thamTri as $k => $v) {
                $chuanBi->bindValue(":$k", $v, $this->layKieuDuLieu($v));
            }
            $chuanBi->execute();
            return $chuanBi;
        } catch (PDOException $e) {
            baoLoiCha::chayTrangBaoLoi("Lỗi Cơ Sở Dữ Liệu", array($e->getCode(), $e->getMessage()));
            die;
        }
    }

    /**
     * Phương thức lấy kiểu dữ liệu
     * 
     * CHỨC NĂNG:
     * Giúp xác định được chính xác kiểu dữ liệu các giá trị của thuộc tính trong câu truy vấn.
     * 
     * @param mixed $bien (tham trị truyền vào là giá trị của các thuộc tính trong câu truy vấn)
     * @return boolean (kiểu trả về dạng số nguyên là mã dạng của các giá trị được quy định trong lớp đối tượng PDO nếu không xác định được phương thức này sẽ trả về giá trị là sai (FALSE))
     */
    private function layKieuDuLieu($bien) {
        if (is_int($bien)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($bien)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($bien)) {
            return PDO::PARAM_NULL;
        } elseif (is_string($bien)) {
            return PDO::PARAM_STR;
        }
        return false;
    }

    /**
     * Phương thức lấy tên cột trên bảng
     * 
     * CHỨC NĂNG:
     * Phương thức hổ trợ lấy toàn bộ tên cột trên bảng
     * 
     * @param string $bang (tham trị truyền vào dạng chuỗi là chuỗi tên bảng muốn lấy các cột trên bảng đó)
     * @return array (giá trị trả về dạng mảng là mảng tên cột đã lấy được trên bảng)
     */
    public function layTenCotTrenBang($bang) {
        try {
            $tenCSDL = thietLapCha::$cauHinhCSDL["Tên CSDL"];
            $dieuKien = (thietLapCha::$cauHinhCSDL["Loại CSDL"] === "sqlsrv") ? "WHERE TABLE_NAME like '$bang'" : "WHERE TABLE_SCHEMA like '$tenCSDL' and TABLE_NAME like '$bang'";
            $chuanBi = $this->prepare("Select Column_name from Information_schema.columns $dieuKien");
            $chuanBi->execute();
            return $chuanBi->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            baoLoiCha::chayTrangBaoLoi("Lỗi Cơ Sở Dữ Liệu", array($e->getCode(), $e->getMessage()));
            die;
        }
    }

    /**
     * Phương thức kiểm tra bảng tồn tại
     * 
     * CHỨC NĂNG:
     * Giúp bạn xác định được tên bảng truyền vào có tồn tại trên cơ sở dữ liệu (database) hay không
     * 
     * @param string $bang (tham trị truyền vào dạng chuỗi là chuỗi tên bảng muốn xác định có tồn tại hay không)
     * @return int (giá trị trả về dạng số nguyên nếu giá trị này là 1 nghĩa là bảng cần kiểm tra có tồn tại ngược lại nếu la 0 thì không tồn tại)
     */
    public function kiemTraBangTonTai($bang) {
        try {
            $tenCSDL = thietLapCha::$cauHinhCSDL["Tên CSDL"];
            $dieuKien = (thietLapCha::$cauHinhCSDL["Loại CSDL"] === "sqlsrv") ? "WHERE TABLE_NAME = '$bang'" : "WHERE TABLE_SCHEMA = '$tenCSDL' and TABLE_NAME = '$bang'";
            $chuanBi = $this->prepare("select * from INFORMATION_SCHEMA.TABLES $dieuKien");
            $chuanBi->execute();
            return count($chuanBi->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            baoLoiCha::chayTrangBaoLoi("Lỗi Cơ Sở Dữ Liệu", array($e->getCode(), $e->getMessage()));
            die;
        }
    }

    /**
     * Phương thức kiểm tra cột tồn tại trên bảng
     * 
     * CHỨC NĂNG:
     * Kiểm tra tên cột có tồn tại trong hệ thống các cột nằm trong bảng dữ liệu hay không
     * 
     * @param string $bang (tham trị thứ 1 truyền vào dạng chuỗi là chuỗi tên bảng cần xác định cột tồn tại)
     * @param type $tenCot (tham trị thứ 2 truyền vào dạng chuỗi là chuỗi tên cột cần xác định tồn tại)
     * @return boolean (kiểu trả về dạng đúng (TRUE) khi tên cột có tồn tại trên bảng ngược lại là sai (FALSE) khi tên cột không tồn tại trên bảng)
     */
    public function kiemTraCotTonTai($bang, $tenCot) {
        if (is_string($bang)) {
            $mangTenCot = $this->layTenCotTrenBang($bang);
            if (is_string($tenCot)) {
                return in_array($tenCot, $mangTenCot);
            } elseif (is_array($tenCot)) {
                foreach ($tenCot as $v) {
                    if (!in_array($v, $mangTenCot)) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

}
