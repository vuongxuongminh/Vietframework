<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                28/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng dieuHuongCha (controller) chứa các thuộc tính và phương thức liên quan đến điều hướng
 * 
 * CHỨC NĂNG CHÍNH:
 * Là bộ thư viện điều hướng thông qua đó khởi tạo và gọi đến lớp đối tượng xử lý và sau khi xử lý (MODEL) xong khởi tạo và gọi tầng hiển thị (VIEW) để xuất kết quả ra màn hình
 * 
 */

class dieuHuongCha {

    /**
     * Đối tượng hiển thị (VIEW) chứa các thuộc tính và phương thức xử lý các thành phần hiển thị.
     * 
     * CHỨC NĂNG:
     * Chứa các thuộc tính và phương thức hiển thị
     * 
     * Hỗ trợ việc xuất các thành phần HTML ra màn hình người dùng
     * 
     * @var hienThiCha (kiểu đối tượng)
     */
    public $hienThi;

    /**
     * Đối tượng xử lý (MODEL) chứa các thuộc tính và phương thức xử lý các thành phần CSDL
     * 
     * CHỨC NĂNG:
     * Xử lý thành phần cơ sở dữ liệu, kiểm tra lỗi v..v
     * 
     * @var xuLyCha (kiểu đối tượng)
     */
    public $xuLy;

    /**
     * Mảng thuộc tính điều hướng phương thức và tham trị
     * 
     * CHỨC NĂNG:
     * Mảng chứa các phần tử mang giá trị điều hướng (controller) phương thức (action) tham trị (param) yêu cầu từ đường dẫn
     * 
     * @var array (kiểu mảng)
     */
    public static $dieuHuongPhuongThucVaThamTri = array("Điều Hướng" => "", "Phương Thức" => "", "Tham Trị" => array());

    /**
     * Đối tượng duLieuGuiVe chứa các thuộc tính và phương thức xử lý các yêu cầu gửi về từ client
     * 
     * CHỨC NĂNG:
     * Lấy dữ liệu biểu mẩu gửi về, 
     * Lấy điều hướng, phương thức, tham trị gửi về, 
     * Kiểm tra dạng dữ liệu gửi về
     * 
     * @var duLieuGuiVe (kiểu đối tượng)
     */
    public $duLieuGuiVe;

    /**
     * Đối tượng duLieuGuiDi chứa các thuộc tính và phương thức xử lý các yêu cầu gửi đi từ server
     * 
     * CHỨC NĂNG:
     * Truyền dữ liệu biểu mẫu, 
     * Truyền câu thông báo, 
     * Truyền tệp hiển thị, mẫu trang (layout), báo lỗi v..v
     * 
     * @var duLieuGuiDi (kiểu đối tượng)
     */
    public $duLieuGuiDi;

    /**
     * Đối tượng phanQuyen chứa các thuộc tính và phương thức xử lý việc phân quyền truy cập hệ thống
     * 
     * CHỨC NĂNG:
     * Xử lý phân quyền truy cập hệ thống
     * 
     * @var phanQuyen (kiểu đối tượng)
     */
    public $phanQuyen;

    /**
     * Mảng thuocTinhPhanQuyen chứa các giá trị là thuộc tính phân quyền thiết lập cho chức năng phân quyền hệ thống
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử mang giá trị là thuộc tính phân quyền như: 
     * "Đường Dẫn Đăng Nhập", 
     * "Đường Dẫn Trang Sau Khi Đăng Nhập", 
     * "Đường Dẫn Trang Sau Khi Đăng Xuất", 
     * "Biểu Mẫu", 
     * "Câu Báo Lỗi Phân Quyền"
     * 
     * @var array (kiểu mảng)
     */
    public $thuocTinhPhanQuyen = array();

    /**
     * Phương thức khởi tạo lớp đối tượng điều hướng cha (controller)
     * 
     * CHỨC NĂNG:
     * Khởi tạo các đối tượng phân quyền, dữ liệu gửi về, dữ liệu gửi đi cùng lúc khi lớp được khởi tạo
     */
    public function __construct() {
        $this->phanQuyen = new phanQuyen($this);
        $this->duLieuGuiVe = new duLieuGuiVe;
        $this->duLieuGuiDi = new duLieuGuiDi;
        $this->khoiTaoXuLy();
    }

    /**
     * Phương thức khoiTaoXuLy khởi tạo đối tượng xử lý (MODEL)
     * 
     * CHỨC NĂNG:
     * Khởi tạo đối tượng xử lý một cách thông minh
     * 
     * Nếu có tồn tại tệp xử lý của bạn thì sẽ cập nhật và nạp thêm thuộc tính và phương thức từ đó
     * 
     * Nếu không tồn tại tệp xử lý của bạn thì sẽ khởi tạo theo các thuộc tính và phương thức mặc định trong framework
     */
    public function khoiTaoXuLy() {
        $tepXuLyHeThong = DUONGDANTHUMUCTRANG . "xuly" . DS . "xuly_hethong.php";
        $tepXuLy = DUONGDANTHUMUCTRANG . "xuly" . DS . "xuly_" . self::$dieuHuongPhuongThucVaThamTri['Điều Hướng'] . ".php";
        $tenDoiTuongXuLyHeThong = "xuly_hethong";
        $tenDoiTuongXuLy = "xuly_" . self::$dieuHuongPhuongThucVaThamTri['Điều Hướng'];
        if (file_exists($tepXuLy)) {
            if (file_exists($tepXuLyHeThong)) {
                require_once($tepXuLyHeThong);
                if (class_exists($tenDoiTuongXuLyHeThong)) {
                    if (is_subclass_of($tenDoiTuongXuLyHeThong, "xuLyCha")) {
                        require_once($tepXuLy);
                        if (class_exists($tenDoiTuongXuLy)) {
                            $this->xuLy = new $tenDoiTuongXuLy($this->duLieuGuiVe);
                            if (!is_subclass_of($this->xuLy, $tenDoiTuongXuLyHeThong)) {
                                baoLoiCha::chayTrangBaoLoi("Lớp xử lý chưa khai báo kế thừa");
                                die;
                            }
                        } else {
                            baoLoiCha::chayTrangBaoLoi("Lớp xử lý không tồn tại");
                            die;
                        }
                    } else {
                        return trigger_error("Tệp nền (source) bị lỗi: " . $tepXuLyHeThong . " bạn có thể lấy lại nó ở source gốc !", E_NOTICE);
                    }
                } else {
                    return trigger_error("Tệp nền (source) bị lỗi: " . $tepXuLyHeThong . " bạn có thể lấy lại nó ở source gốc !", E_NOTICE);
                }
            } else {
                return trigger_error("Thiếu tệp nền (source): " . $tepXuLyHeThong . " bạn có thể lấy nó ở source gốc !", E_NOTICE);
            }
        } else {
            $this->xuLy = new xuLyCha($this->duLieuGuiVe);
        }
    }

    /**
     * Phương thức khoiTaoHienThi khởi tạo đối tượng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * Khởi tạo đối tượng hiển thị đồng thời truyền dữ liệu gửi đi sau khi thực hiện các thao tác xử lý thuộc tính và phương thức trên đối tượng $this->duLieuGuiDi
     */
    public function khoiTaoHienThi() {
        $mangBaoLoi = $this->xuLy->mangBaoLoi;
        if (count($mangBaoLoi) == 0) {
            $mangKiemTraLoi = $this->xuLy->kiemTraLoi;
            foreach ($mangKiemTraLoi as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        $mangBaoLoi[$k][$k1] = "";
                    }
                }
            }
        }
        $this->duLieuGuiDi->mangBaoLoiHtml = $mangBaoLoi;
        $this->hienThi = new hienThiCha($this->duLieuGuiDi);
    }

    /**
     * Phương thức chuyenTrang dùng để xử lý chuyển trang
     * 
     * CHỨC NĂNG:
     * Dùng để chuyển liên kết trong trang (ví dụ: truyền array("Điều Hướng" => ?, "Phương Thức => ?, array(?))
     * 
     * Dùng để chuyển liên kết ngoài trang (ví dụ: http://google.com)
     */
    public function chuyenTrang($chuyenTrang = null) {
        $dayThamTri = func_get_args();
        if (count($dayThamTri) > 0) {
            $duongDan = doiTuongHtml::_xuLyDuongDanTuYeuCau($dayThamTri);
            header("location: " . $duongDan);
            exit;
        }
    }

    /**
     * Phương thức thucHienTruocKhiPhanQuyen được gọi thực hiện trước khi việc phân quyền xảy ra
     * 
     * CHỨC NĂNG:
     * Giúp bạn thêm các phương thức cho phép truy cập
     * 
     * Giúp bạn thiết lập các giá trị hệ thống, biến hiển thị trước khi phương thức yêu cầu (action) trong lớp điều hướng được gọi
     */
    public function thucHienTruocKhiPhanQuyen() {
        
    }

}

?>