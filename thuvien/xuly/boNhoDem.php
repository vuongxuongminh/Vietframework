<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                21/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng bộ nhớ đệm (cache)
 * Mã nguồn: http://phpfastcache.com/ | Email tác giả: khoaofgod@gmail.com
 * 
 * CHỨC NĂNG CHÍNH:
 * Hổ trợ các phương thức lưu, lấy, xóa, sửa các vấn đề về bộ nhớ đệm
 * 
 * Lớp đối tượng này được tích hợp vào tầng xử lý để giúp tối ưu hóa website khi truy vấn dữ liệu database (select)
 * Hoặc lưu toàn bộ dữ liệu tầng VIEW hiển thị
 * 
 */
require_once(DUONGDANTHUMUCTRANG . "thuvien" . DS . "xuly" . DS . "bonhodem" . DS . "3.0.0" . DS . "phpfastcache.php");

// short function
if (!function_exists("__boNhoDem")) {

    function __boNhoDem() {
        return new boNhoDem;
    }

}

class boNhoDem extends phpFastCache {

    /**
     * Thời gian đáo hạn mặc định vùng nhớ đệm
     * 
     * CHỨC NĂNG:
     * Dùng để thiết lập thời gian đáo hạn mặc định cho các vùng nhớ đệm nếu người dùng không yêu cầu thời gian
     * 
     * @var int 
     */
    private $thoiGianDaoHan;

    /**
     * Phương Thức khởi tạo lớp đối tượng bộ nhớ đệm
     * 
     * CHỨC NĂNG:
     * Khởi tạo và thiết lập một số thuộc tính mặc định cho lớp đối tượng cha (phpFastCache)
     */
    public function __construct() {
        $kieuLuuTru = thietLapCha::$cauHinhBoNhoDem["Kiểu Lưu"];
        $thoiGianHetHan = thietLapCha::$cauHinhBoNhoDem["Thời Gian Lưu Mặc Định"];
        $this->thoiGianDaoHan = $thoiGianHetHan;
        $chuyenDoiKieuLuu = (mb_strtolower($kieuLuuTru, "utf-8") === "tự động") ? "auto" : mb_strtolower($kieuLuuTru, "utf-8");
        parent::setup("path", DUONGDANTHUMUCTRANG . "luocsu" . DS . "bonhodem");
        parent::__construct($chuyenDoiKieuLuu);
    }

    /**
     * Phương thức thống kê bộ nhớ đệm
     * 
     * CHỨC NĂNG:
     * Thống kê tổng quan về bộ nhớ đệm (tổng dung lượng bộ nhớ, số lượng vùng nhớ bị xóa, dữ liệu ...)
     * @return array (kiểu trả về dạng mảng)
     */
    public function thongKe() {
        return $this->stats();
    }

    /**
     * Phương thức thiết lập vùng nhớ đệm
     * 
     * CHỨC NĂNG:
     * Thiết lập môt vùng nhớ đệm trên hệ thống
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn tạo 1 vùng nhớ tên là "a" có giá trị là 1 và thời gian tự hủy là 5 giây thì tôi gọi như sau ($this->xuLy->boNhoDem->thietLapVungNho("a", 1, 5)) ở tầng điều hướng, ($this->boNhoDem->thietLapVungNho("a", 1, 5)) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn tạo một lúc 2 hoặc nhiều vùng nhớ thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->thietLapVungNho(array("vùng nhớ 1", giá trị 1, thời gian tự hủy 1), array("vùng nhớ n", giá trị n, thời gian tự hủy n))) ở tầng điều hướng, ($this->boNhoDem->thietLapVungNho(array("vùng nhớ 1", giá trị 1, thời gian tự hủy 1), array("vùng nhớ n", giá trị n, thời gian tự hủy n))) ở tầng xử lý
     * 
     * @param string $vungNho (tham trị thứ 1 dạng chuỗi là tên vùng nhớ của bạn)
     * @param mixed $giaTri (tham trị thứ 2 dạng chuỗi|số|đối tượng|mảng là giá trị cho vùng nhớ)
     * @param int $thoiGianDaoHan (tham trị thứ 1 dạng số nguyên là thời gian tự hủy của vùng nhớ nếu không nhập thì nó sẽ tự động lấy thời gian tự hủy mặc định)
     * @return boolean (kiểu trả về dạng ĐÚNG (TRUE) khi thiết lập thành công, SAI (FLASE) khi có lỗi xảy ra)
     */
    public function thietLapVungNho($vungNho, $giaTri, $thoiGianDaoHan = "Tự Động") {
        if (!is_array($vungNho) && func_num_args() >= 2) {
            if (!is_numeric($thoiGianDaoHan)) {
                $thoiGianDaoHan = $this->thoiGianDaoHan;
            }
            $this->set($vungNho, $giaTri, $thoiGianDaoHan);
            return true;
        } else {
            $mangThamTri = func_get_args();
            foreach ($mangThamTri as $khoa => $thamTri) {
                if (is_array($thamTri) && count($thamTri) >= 2) {
                    $thoiGianDaoHan = $this->thoiGianDaoHan;
                    if (isset($thamTri[2]) && is_numeric($thamTri[2])) {
                        $thoiGianDaoHan = $thamTri[2];
                    }
                    $thamTri[2] = $thoiGianDaoHan;
                } else {
                    return false;
                }
            }
            call_user_func_array(array($this, "setMulti"), $mangThamTri);
            return true;
        }
        return false;
    }

    /**
     * Phương thức lấy giá trị vùng nhớ
     * 
     * CHỨC NĂNG:
     * Hổ trợ lấy giá trị vùng nhớ thông qua tên vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn lấy giá trị vùng nhớ có tên là "abc" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->giaTriVungNho("abc")) ở tầng điều hướng, ($this->boNhoDem->thietLapVungNho("abc")) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn lấy 1 lúc 3 giá trị vùng nhớ có tên là "abc" "def" "fed" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->giaTriVungNho("abd", "def", "fed)) ở tầng điều hướng, ($this->boNhoDem->giaTriVungNho("abd", "def", "fed)) ở tầng xử lý. Kiểu trả về của nó sẽ là 1 mảng chứa các phần tử có khóa là các tên vùng nhớ cần lấy có giá trị là giá trị của các vùng nhớ cần lấy (ví dụ array("abc" => 1, "def" => 2, "efg" => 3))
     * 
     * @param string $vungNho (Các tham trị mang kiểu dữ liệu chuỗi là tên của các vùng nhớ cần lấy giá trị)
     * @return mixed (Gía trị trả về là giá trị của vùng nhớ cần lấy hoặc một mảng tập hợp các giá trị của các vùng nhớ cần lấy)
     */
    public function giaTriVungNho($vungNho) {
        $demSoThamTri = func_num_args();
        if ($demSoThamTri === 1) {
            return $this->get($vungNho);
        } elseif ($demSoThamTri > 1) {
            return $this->getMulti(func_get_args());
        }
        return false;
    }

    /**
     * Phương thức lấy thông tin vùng nhớ
     * 
     * CHỨC NĂNG:
     * Hổ trợ lấy thông tin vùng nhớ thông qua tên vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn lấy thông tin vùng nhớ có tên là "abc" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->thongTinVungNho("abc")) ở tầng điều hướng, ($this->boNhoDem->thongTinVungNho("abc")) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn lấy 1 lúc 3 thông tin vùng nhớ có tên là "abc" "def" "fed" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->thongTinVungNho("abd", "def", "fed)) ở tầng điều hướng, ($this->boNhoDem->thongTinVungNho("abd", "def", "fed)) ở tầng xử lý. Kiểu trả về của nó sẽ là 1 mảng chứa các phần tử có khóa là các tên vùng nhớ cần lấy có giá trị là mảng thông tin vùng nhớ
     * 
     * @param string $vungNho (Các tham trị mang kiểu dữ liệu chuỗi là tên của các vùng nhớ cần lấy thông tin)
     * @return mixed (Gía trị trả về là thông tin của vùng nhớ cần lấy hoặc một mảng tập hợp các thông tin của các vùng nhớ cần lấy)
     */
    public function thongTinVungNho($vungNho) {
        $demSoThamTri = func_num_args();
        if ($demSoThamTri === 1) {
            $thongTinVungNho = $this->getInfo($vungNho);
            if (!empty($thongTinVungNho)) {
                return array("Giá Trị" => $thongTinVungNho["value"], "Thời Gian Khởi Tạo" => $thongTinVungNho["write_time"], "Thời Gian Đáo Hạn" => $thongTinVungNho["expired_time"], "Số Giây Tồn Tại" => $thongTinVungNho["expired_in"]);
            }
        } elseif ($demSoThamTri > 1) {
            $mangThongTinVungNho = $this->getInfoMulti(func_get_args());
            return array_map(function($thongTinVungNho) {
                return array("Giá Trị" => $thongTinVungNho["value"], "Thời Gian Khởi Tạo" => $thongTinVungNho["write_time"], "Thời Gian Đáo Hạn" => $thongTinVungNho["expired_time"], "Số Giây Tồn Tại" => $thongTinVungNho["expired_in"]);
            }, $mangThongTinVungNho);
        }
        return false;
    }

    /**
     * Phương thức xóa vùng nhớ
     * 
     * CHỨC NĂNG:
     * Hổ trợ xóa vùng nhớ thông qua tên vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn xóa vùng nhớ có tên là "abc" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->xoaVungNho("abc")) ở tầng điều hướng, ($this->boNhoDem->xoaVungNho("abc")) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn xóa 1 lúc 3 vùng nhớ có tên là "abc" "def" "fed" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->xoaVungNho("abd", "def", "fed)) ở tầng điều hướng, ($this->boNhoDem->xoaVungNho("abd", "def", "fed)) ở tầng xử lý. Kiểu trả về của nó sẽ là 1 mảng chứa các phần tử có khóa là các tên vùng nhớ cần lấy có giá trị là giá trị đúng hoặc sai tương đương với trạng thái xóa
     * 
     * @param string $vungNho (Các tham trị mang kiểu dữ liệu chuỗi là tên của các vùng nhớ cần xóa)
     * @return mixed (Gía trị trả về ĐÚNG (TRUE) khi vùng nhớ được xóa thành công SAI (FALSE) khi xóa thất bại hoặc một mảng có khóa là tên của các vùng nhớ cần xóa có giá trị kiểu đúng sai tùy theo trạng thái xóa của mỗi vùng nhớ)
     */
    public function xoaVungNho($vungNho) {
        $demSoThamTri = func_num_args();
        if ($demSoThamTri === 1) {
            return $this->delete($vungNho);
        } elseif ($demSoThamTri > 1) {
            return $this->deleteMulti(func_get_args());
        }
        return false;
    }

    /**
     * Phương thức xóa tất cả vùng nhớ
     * 
     * CHỨC NĂNG:
     * Hổ trợ xóa tất cả vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn xóa tất cả vùng nhớ thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->xoaTatCaVungNho()) ở tầng điều hướng, ($this->boNhoDem->xoaTatCaVungNho()) ở tầng xử lý
     * 
     * @return boolean (Gía trị trả về ĐÚNG (TRUE) khi toàn bộ vùng nhớ được xóa thành công SAI (FALSE) khi xóa thất bại)
     */
    public function xoaTatCaVungNho() {
        return $this->clean();
    }

    /**
     * Phương thức kiểm tra vùng nhó tồn tại
     * 
     * CHỨC NĂNG:
     * Hổ trợ kiểm tra vùng nhớ có tồn tại hay không thông qua tên vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn kiểm tra vùng nhớ có tên là "abc" có tồn tại hay không thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->kiemTraVungNhoTonTai("abc")) ở tầng điều hướng, ($this->boNhoDem->kiemTraVungNhoTonTai("abc")) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn kiểm tra 1 lúc 3 vùng nhớ có tồn tại hay không thông qua tên là "abc" "def" "fed" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->kiemTraVungNhoTonTai("abd", "def", "fed)) ở tầng điều hướng, ($this->boNhoDem->kiemTraVungNhoTonTai("abd", "def", "fed)) ở tầng xử lý. Kiểu trả về của nó sẽ là 1 mảng chứa các phần tử có khóa là các tên vùng nhớ cần lấy có giá trị là giá trị đúng hoặc sai tương đương với trạng thái có hoặc không
     * 
     * @param string $vungNho (Các tham trị mang kiểu dữ liệu chuỗi là tên của các vùng nhớ cần kiểm tra sự tồn tại)
     * @return mixed (Gía trị trả về ĐÚNG (TRUE) khi vùng nhớ tồn tại SAI (FALSE) khi không tồn tại hoặc một mảng có khóa là tên của các vùng nhớ cần xóa có giá trị kiểu đúng sai tùy theo trạng thái tồn tại của các vùng nhớ)
     */
    public function kiemTraVungNhoTonTai($vungNho) {
        $demSoThamTri = func_num_args();
        if ($demSoThamTri === 1) {
            return $this->isExisting($vungNho);
        } elseif ($demSoThamTri > 1) {
            return $this->isExistingMulti(func_get_args());
        }
        return false;
    }

    /**
     * Phương thức thiết lập thời gian đáo hạn
     * 
     * CHỨC NĂNG:
     * Hổ trợ tăng hoặc giảm thời gian đáo hạn vùng nhớ của bạn
     * 
     * HƯỚNG DẪN:
     * Ví dụ tôi muốn tăng thời gian đáo hạn của vùng nhớ có tên là "abc" thêm 5 giây thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->thietLapThoiGianDaoHan("abc", 5)) ở tầng điều hướng, ($this->boNhoDem->thietLapThoiGianDaoHan("abc", 5)) ở tầng xử lý
     * 
     * MỞ RỘNG:
     * Ví dụ tôi muốn giảm thời gian đáo hạn 1 lúc 3 vùng nhớ thông qua tên là "abc" "def" "fed" thì tôi sẽ gọi phương thức như sau ($this->xuLy->boNhoDem->thietLapThoiGianDaoHan(array("abd", -5), array("abc", -5))) ở tầng điều hướng, ($this->boNhoDem->thietLapThoiGianDaoHan(array("abd", -5), array("abc", -5))) ở tầng xử lý. Kiểu trả về của nó sẽ là 1 mảng chứa các phần tử có khóa là các tên vùng nhớ cần lấy có giá trị là giá trị đúng hoặc sai tương đương với trạng thái 
     * 
     * @param string $vungNho (Các tham trị mang kiểu dữ liệu chuỗi là tên của các vùng nhớ cần kiểm tra sự tồn tại)
     * @return mixed (Gía trị trả về ĐÚNG (TRUE) tăng/giảm thời gian đáo hạn thành công SAI (FALSE) khi thất bại hoặc một mảng có khóa là tên của các vùng nhớ cần tăng/giảm thời gian đáo hạn có giá trị kiểu đúng sai tùy theo trạng thái của các vùng nhớ)
     */
    public function thietLapThoiGianDaoHan($vungNho, $thoiGianDaoHan = "Tự Động") {
        if (!is_array($vungNho)) {
            if (!is_numeric($thoiGianDaoHan)) {
                $thoiGianDaoHan = $this->thoiGianDaoHan;
            }
            return $this->touch($vungNho, $thoiGianDaoHan);
        } else {
            $mangThamTri = func_get_args();
            foreach ($mangThamTri as $khoa => $thamTri) {
                if (is_array($thamTri) && count($thamTri) >= 1) {
                    $thoiGianDaoHan = $this->thoiGianDaoHan;
                    if (isset($thamTri[1]) && is_numeric($thamTri[1])) {
                        $thoiGianDaoHan = $thamTri[1];
                    }
                    $thamTri[1] = $thoiGianDaoHan;
                } else {
                    unset($mangThamTri[$khoa]);
                }
            }
            return $this->touchMulti($mangThamTri);
        }
        return false;
    }

}
