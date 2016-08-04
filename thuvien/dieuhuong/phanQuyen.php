<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                01/07/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng phân quyền chứa các thuộc tính và phương thức xử lý các vấn đề phân quyền truy cập hệ thống
 * 
 * CHỨC NĂNG CHÍNH:
 * Phân quyền truy cập trang web những trang cho phép truy cập
 * Phân quyền truy cập sau khi đăng nhập đối với những trang web không cho phép truy cập
 * 
 */

class phanQuyen {

    /**
     * Mảng thuộc tính mangChoPhepTruyCap có các phần tử mang các giá trị là phương thức cho phép truy cập
     * 
     * CHỨC NĂNG:
     * Mảng lưu các phần tử mang giá trị tương đương với các phương thức cho phép truy cập
     * 
     * Lưu ý: nên sử dụng phương thức choPhepTruyCap trong lớp để tương tác với mảng không nên tương tác trực tiếp
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $mangChoPhepTruyCap = array();

    /**
     * Mảng thuộc tính đường dẫn trang đang nhập có các phần từ mang giá trị xử lý truy cập trang đăng nhập
     * 
     * CHỨC NĂNG:
     * Dùng để convert qua đường dẫn liên kết và chuyển trang khi người dùng truy cập vào trang mà không có quyền truy cập
     * 
     * Mảng này cũng là mảng liên kết của trang đăng nhập hệ thống (nếu có)
     * 
     * Ví dụ: mảng đường dẫn trang đăng nhập của tôi là "array("Điều Hướng" => "taikhoan", "Phương Thức" => "dangnhap", array());" 
     * 
     * Thì khi tôi vào trang mà không có quyền truy cập thì tôi sẽ bị chuyển đến trang hostcuaban/taikhoan/dangnhap/
     * 
     * Lưu ý: không nên tương tác trực tiếp với mảng
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $duongDanTrangDangNhap = array("Điều Hướng" => "taikhoan", "Phương Thức" => "dangnhap", array());

    /**
     * Mảng thuộc tính đường dẫn trang sau khi đăng nhập có các phần tử mang giá trị xử lý truy cập trang sau khi đăng nhập
     * 
     * CHỨC NĂNG:
     * Dùng để convert qua đường dẫn liên kết trang sau khi đăng nhập
     * 
     * Mảng hỗ trợ cho phương thức chuyển trang (chuyenTrang) cùng lớp để chuyển trang sau khi đăng nhập
     * 
     * Lưu ý: không nên tương tác trực tiếp với mảng
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $duongDanSauKhiDangNhap = array("Điều Hướng" => "macdinh", "Phương Thức" => "trangchinh", array());

    /**
     * Mảng thuộc tính đường dẫn trang sau khi đăng xuất có các phần tử mang giá trị xử lý truy cập trang sau khi đăng xuất
     * 
     * CHỨC NĂNG:
     * Dùng để convert qua đường dẫn liên kết trang sau khi đăng xuất
     * 
     * Mảng hỗ trợ cho phương thức chuyển trang (chuyenTrang) cùng lớp để chuyển trang sau khi đăng xuất
     * 
     * Lưu ý: không nên tương tác trực tiếp với mảng
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $duongDanSauKhiDangXuat = array("Điều Hướng" => "macdinh", "Phương Thức" => "trangchinh", array());

    /**
     * Thuộc tính lưu chuỗi báo lỗi phân quyền (nếu có)
     * 
     * CHỨC NĂNG:
     * Để lưu chuỗi báo lỗi truy cập phân quyền và từ đó truyền về dữ liệu gửi đi đến tầng hiển thị (VIEW)
     * 
     * Tại tệp mẫu trang (layout) hoặc tệp hiển thị để gọi câu báo lỗi ra ta sử dụng "$this->cauBaoLoiTruyCap()"
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public $cauBaoLoiTruyCap = "";

    /**
     * Mảng thuộc tính chứa các phần tử mang giá trị liên quan đến biểu mẫu đăng nhập
     * 
     * CHỨC NĂNG:
     * Khai báo các thuộc tính như bảng tài khoản tại hệ thống, tên trường tài khoản, tên trường mật khẩu
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $bieuMau = array(
        "Tên Bảng" => "taikhoan",
        "Tên Trường" => array("Tài Khoản" => "taikhoan", "Mật Khẩu" => "matkhau")
    );

    /**
     * Đối tượng điều hướng (controller)
     * 
     * CHỨC NĂNG:
     * Truyền vào lớp phanQuyen nhằm hỗ trợ thao tác với các lớp đối tượng và thuộc tính bên ngoài
     * 
     * @var dieuHuongCha (kiểu đối tượng)
     */
    private $dieuHuong;

    /**
     * Phương thức cho phép truy cập
     * 
     * CHỨC NĂNG:
     * Phân quyền hệ thông qua tên các phương thức chỉ định trong mảng thuộc tính
     * 
     * Giúp tương tác với mảng cho phép truy cập
     * 
     * Ví dụ: tôi muốn hệ thống chỉ cho phép người dùng truy cập vào phương thức trangchinh còn lại yêu cầu đăng nhập
     * thì tôi khai bao như sau choPhepTruyCap("trangchinh") vậy tất cả các trang còn lai bắt buộc đăng nhập để kiểm tra tính cho phép
     * 
     * @param: string $tenPhuongThuc (tham trị kiểu chuỗi mang giá trị là tên phương thức cho phép truy cập)
     */
    public function choPhepTruyCap($tenPhuongThuc = null) {
        $dayThamTri = func_get_args();
        foreach ($dayThamTri as $v) {
            if (is_string($v)) {
                $this->mangChoPhepTruyCap[] = $v;
            }
        }
    }

    /**
     * Phương thức từ chối truy cập
     * 
     * CHỨC NĂNG:
     * Từ chối truy hệ thông qua tên các phương thức chỉ định trong mảng thuộc tính
     * 
     * Giúp tương tác với mảng cho phép truy cập
     * 
     * Ví dụ: tôi muốn từ chối truy cập vào phương thức trangchinh
     * thì tôi khai bao như sau tuChoiTruyCap("trangchinh") vậy tất cả các yêu cầu về phương thức trangchinh sẽ bị từ chối truy cập
     * 
     * @param: string $tenPhuongThuc (tham trị kiểu chuỗi mang giá trị là tên phương thức cho phép truy cập)
     */
    public function tuChoiTruyCap($tenPhuongThuc = null) {
        $dayThamTri = func_get_args();
        foreach ($dayThamTri as $v) {
            if (is_string($v)) {
                foreach ($this->mangChoPhepTruyCap as $k2 => $v2) {
                    if ($v2 === $v) {
                        unset($this->mangChoPhepTruyCap[$k2]);
                    }
                }
            }
        }
    }

    /**
     * Phương thức khởi tạo lớp đối tượng phanQuyen]
     * 
     * CHỨC NĂNG:
     * Thiết lập các thuộc tính trong lớp theo thuộc tính của bạn truyền về
     * 
     * Nếu bạn không truyền về các thuộc tính của lớp phân quyền thì mặc định nó sẽ nạp thuộc tính ngẫu nhiên
     * 
     * @param object $dieuHuong (tham trị truyền vào là lớp điều hướng)
     */
    public function __construct($dieuHuong) {
        $this->dieuHuong = $dieuHuong;
        $thuocTinhPhanQuyen = $this->dieuHuong->thuocTinhPhanQuyen;
        if (isset($thuocTinhPhanQuyen["Đường Dẫn Đăng Nhập"]) && is_array($thuocTinhPhanQuyen["Đường Dẫn Đăng Nhập"])) {
            foreach ($thuocTinhPhanQuyen["Đường Dẫn Đăng Nhập"] as $k => $v) {
                if (isset($this->duongDanTrangDangNhap[$k])) {
                    $this->duongDanTrangDangNhap[$k] = $v;
                }
            }
        }
        if (isset($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Nhập"]) && is_array($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Nhập"])) {
            foreach ($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Nhập"] as $k => $v) {
                if (isset($this->duongDanSauKhiDangNhap[$k])) {
                    $this->duongDanSauKhiDangNhap[$k] = $v;
                }
            }
        }
        if (isset($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Xuất"]) && is_array($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Xuất"])) {
            foreach ($thuocTinhPhanQuyen["Đường Dẫn Sau Khi Đăng Xuất"] as $k => $v) {
                if (isset($this->duongDanSauKhiDangXuat[$k])) {
                    $this->duongDanSauKhiDangXuat[$k] = $v;
                }
            }
        }
        if (isset($thuocTinhPhanQuyen["Biểu Mẫu"]) && is_array($thuocTinhPhanQuyen["Biểu Mẫu"])) {
            foreach ($thuocTinhPhanQuyen["Biểu Mẫu"] as $k => $v) {
                if (isset($this->bieuMau[$k])) {
                    if (is_string($v)) {
                        $this->bieuMau[$k] = $v;
                    } elseif (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if (isset($this->bieuMau[$k][$k2])) {
                                $this->bieuMau[$k][$k2] = $v2;
                            }
                        }
                    }
                }
            }
        }
        $this->cauBaoLoiTruyCap = isset($thuocTinhPhanQuyen["Câu Báo Lỗi Truy Cập"]) ? $thuocTinhPhanQuyen["Câu Báo Lỗi Truy Cập"] : "";
    }

    /**
     * Phương thức phân quyền truy cập hệ thống
     * 
     * CHỨC NĂNG:
     * Phân quyền truy cập trước khi gọi đến phương thức chỉ định từ phía người dùng
     * 
     * Dựa trên mảng mangChoPhepTruyCap mà xác định tên các phương thức chỉ định được phép truy cập. Các phương thức nào không nằm trong mảng mangChoPhepTruyCap đều bị từ chối truy cập
     * 
     * Qúa trình từ chối truy cập diễn ra như sau: hệ thống sẽ bắt bạn đăng nhập vào nếu sau khi đăng nhập phương thức phanQuyenSauKhiDangNhap trả về giá trị là đúng (TRUE) thì bạn sẽ được cấp phép truy cập trang đó ngược lại sẽ không vào được
     */
    public function phanQuyenTruyCap() {
        $this->dieuHuong->thucHienTruocKhiPhanQuyen();
        $tuChoiTruyCap = false;
        if (is_array($this->mangChoPhepTruyCap) && !empty($this->mangChoPhepTruyCap)) {
            $dieuHuongYeuCau = $this->dieuHuong->duLieuGuiVe->dieuHuongYeuCau();
            $phuongThucYeuCau = $this->dieuHuong->duLieuGuiVe->phuongThucYeuCau();
            $thamTriYeuCau = $this->dieuHuong->duLieuGuiVe->thamTriYeuCau();
            if (!in_array($phuongThucYeuCau, $this->mangChoPhepTruyCap)) {
                if ($this->trangThaiDangNhap()) {
                    if (method_exists($this->dieuHuong, "phanQuyenSauKhiDangNhap")) {
                        if (!$this->dieuHuong->phanQuyenSauKhiDangNhap($this->thongTinDangNhap())) {
                            $this->tuChoiTruyCapLienKet();
                        }
                    } else {
                        if (!$this->phanQuyenSauKhiDangNhap($this->thongTinDangNhap())) {
                            $this->tuChoiTruyCapLienKet();
                        }
                    }
                } else {
                    if (isset($this->duongDanTrangDangNhap['Điều Hướng']) && isset($this->duongDanTrangDangNhap['Phương Thức'])) {
                        if ($dieuHuongYeuCau !== $this->duongDanTrangDangNhap['Điều Hướng'] || $phuongThucYeuCau !== $this->duongDanTrangDangNhap['Phương Thức'] || $thamTriYeuCau !== $this->duongDanTrangDangNhap[0]) {
                            $this->tuChoiTruyCapLienKet();
                        }
                    }
                }
            }
        }
    }

    /**
     * Phương thức trạng thái đăng nhập
     * 
     * CHỨC NĂNG:
     * Kiểm tra trạng thái người dùng đã đăng nhập hay chưa
     * 
     * @return boolean (kiểu trả về đúng (TRUE) khi người dùng đã đăng nhập ngược lại là sai (FALSE) khi người dùng chưa đăng nhập)
     */
    public function trangThaiDangNhap() {
        return (bool) $this->thongTinDangNhap();
    }

    /**
     * Phương thức thông tin đăng nhập
     * 
     * CHỨC NĂNG:
     * Phương thức sẽ trả về mảng thông tin đăng nhập mà khi người dùng đăng nhập vào
     * 
     * Mảng này có các phần tử mang giá trị là thông tin dòng cơ sở dữ liệu sử dụng để đăng nhập vào hệ thống
     * 
     * @return array (kiểu trả về dạng mảng nếu người dùng chưa đăng nhập mảng này sẽ là rỗng)
     */
    public function thongTinDangNhap() {
        $mangFrameWork = mangSession::lay("vietFrameWork");
        if (is_array($mangFrameWork) && isset($mangFrameWork['Thông Tin Đăng Nhập'])) {
            if (is_array($mangFrameWork['Thông Tin Đăng Nhập'])) {
                return $mangFrameWork['Thông Tin Đăng Nhập'];
            }
        }
        return array();
    }

    /**
     * Phương thức cập nhật thông tin đăng nhập
     * 
     * CHỨC NĂNG:
     * Phương thức sẽ cập nhật thông tin đăng nhập nếu có thay đổi tương tác
     * 
     * @return array (kiểu trả về dạng mảng nếu người dùng chưa đăng nhập mảng này sẽ là rỗng)
     */
    public function capNhatThongTinDangNhap() {
        $thongTinDangNhap = $this->thongTinDangNhap();
        if (!empty($thongTinDangNhap)) {
            $bangTaiKhoan = $this->bieuMau['Tên Bảng'];
            $duLieuPost = $this->dieuHuong->duLieuGuiVe->duLieuPostGuiVe;
            $this->dieuHuong->duLieuGuiVe->duLieuPostGuiVe = array($bangTaiKhoan => $thongTinDangNhap);
            $dangNhap = $this->dangNhap(false);
            $this->dieuHuong->duLieuGuiVe->duLieuPostGuiVe = $duLieuPost;
            return $dangNhap;
        }
        return false;
    }

    /**
     * Phương thức phân quyền sau khi đăng nhập
     * 
     * CHỨC NĂNG:
     * Thực hiện kiểm tra phân quyền sau khi đăng nhập đối với các phương thức không nằm trong mảng cho phép truy cập
     * 
     * Nếu phương thức trả về đúng (TRUE) thì người dùng sẽ được phép truy cập vào phương thức bị cấm ngược lại nếu là (FALSE) người dùng sẽ không truy cập được
     * 
     * @param array $thongTinDangNhap (tham trị truyền vào dạng mảng thông tin đăng nhập)
     * @return boolean (kiểu đúng hoặc sai)
     */
    public function phanQuyenSauKhiDangNhap($thongTinDangNhap = array()) {
        return $this->trangThaiDangNhap();
    }

    /**
     * Phương thức chuyển trang của phân quyền
     * 
     * CHỨC NĂNG:
     * Dùng để chuyển trang sau khi thực hiện phương thức đăng nhập hoặc đăng xuất
     * 
     * Ví dụ: tại tầng điều hướng (controller) sau khi tôi gọi phương thức "$this->phanQuyen->dangNhap()" nếu đăng nhập thành công tôi gọi tiếp phương thức "$this->phanQuyen->chuyenTrang()"
     * thì hệ thống sẽ chuyển tôi đến trang chỉ định với thuộc tính $duongDanSauKhiDangNhap cùng lớp
     */
    public function chuyenTrang() {
        if ($this->trangThaiDangNhap()) {
            $mangFrameWork = mangSession::lay('vietFrameWork');
            $duongDanTruyCapBiTuChoi = array();
            if (is_array($mangFrameWork) && isset($mangFrameWork["Đường Dẫn Truy Cập Bị Từ Chối"])) {
                $duongDanTruyCapBiTuChoi = $mangFrameWork["Đường Dẫn Truy Cập Bị Từ Chối"];
                unset($mangFrameWork["Đường Dẫn Truy Cập Bị Từ Chối"]);
                mangSession::thietLap("vietFrameWork", $mangFrameWork);
            }
            $duongDanChuyenDen = $this->duongDanSauKhiDangNhap;
            if (!empty($duongDanTruyCapBiTuChoi)) {
                $duongDanChuyenDen = $duongDanTruyCapBiTuChoi + array_values($duongDanTruyCapBiTuChoi["Tham Trị"]);
            }
            $this->dieuHuong->chuyenTrang($duongDanChuyenDen);
        } else {
            $this->dieuHuong->chuyenTrang($this->duongDanSauKhiDangXuat);
        }
    }

    /**
     * Phương thức đăng xuất
     * 
     * CHỨC NĂNG:
     * Đăng xuất người dùng ra khỏi hệ thống
     * 
     * @return boolean (kiểu trả về đúng (TRUE) khi đăng xuất thành công và sai (FALSE) khi đăng xuất thất bại)
     */
    public function dangXuat() {
        $mangFrameWork = mangSession::lay("vietFrameWork");
        if (is_array($mangFrameWork) && isset($mangFrameWork['Thông Tin Đăng Nhập'])) {
            unset($mangFrameWork['Thông Tin Đăng Nhập']);
            mangSession::thietLap('vietFrameWork', $mangFrameWork);
            return true;
        }
        return false;
    }

    /**
     * Phương thức đăng nhập
     * 
     * CHỨC NĂNG:
     * Giúp người dùng đăng nhập hệ thống đơn giản hóa các phương thức truy vấn CSDL của bạn
     * 
     * @return boolean (kiểu trả về đúng (TRUE) khi đăng nhập thành công và sai (FALSE) khi đăng nhập thất bại)
     */
    public function dangNhap($tuDongMaHoaMatKhau = true) {
        $this->dieuHuong->xuLy->ketNoiCSDL();
        $bangTaiKhoan = $this->bieuMau['Tên Bảng'];
        $tenBangCSDLXuLy = $this->dieuHuong->xuLy->tenBangCSDL;
        $this->dieuHuong->xuLy->tenBangCSDL = $bangTaiKhoan;
        $truongTaiKhoan = is_array($this->bieuMau['Tên Trường']['Tài Khoản']) ? $this->bieuMau['Tên Trường']['Tài Khoản'] : array($this->bieuMau['Tên Trường']['Tài Khoản']);
        $truongMatKhau = is_array($this->bieuMau['Tên Trường']['Mật Khẩu']) ? $this->bieuMau['Tên Trường']['Mật Khẩu'] : array($this->bieuMau['Tên Trường']['Mật Khẩu']);
        $duLieuBieuMauGuiVe = $this->dieuHuong->duLieuGuiVe->duLieuPostGuiVe;
        $taiKhoanKiemTra = isset($duLieuBieuMauGuiVe[$bangTaiKhoan]) ? $this->kiemTraThanhPhanBieuMauTrongMangDangNhap($duLieuBieuMauGuiVe[$bangTaiKhoan], $truongTaiKhoan) : null;
        $matKhauKiemTra = isset($duLieuBieuMauGuiVe[$bangTaiKhoan]) ? $this->kiemTraThanhPhanBieuMauTrongMangDangNhap($duLieuBieuMauGuiVe[$bangTaiKhoan], $truongMatKhau) : null;
        if ($this->dieuHuong->xuLy->coSoDuLieu->kiemTraBangTonTai($bangTaiKhoan) > 0) {
            if ($taiKhoanKiemTra !== null && $matKhauKiemTra !== null) {
                $cauTruyVan = "SELECT * FROM {$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot1}$bangTaiKhoan{$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot2} WHERE ";
                $thamTriKiemTra = array();
                $cauTruyVan .= "(";
                foreach ($truongTaiKhoan as $k => $v) {
                    $k += 100;
                    $cauTruyVan .= "{$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot1}$v{$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot2} = :$k OR";
                    $thamTriKiemTra[$k] = $taiKhoanKiemTra;
                }
                $cauTruyVan = rtrim($cauTruyVan, " OR") . ") AND (";
                foreach ($truongMatKhau as $k => $v) {
                    $k += 1000;
                    $cauTruyVan .= "{$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot1}$v{$this->dieuHuong->xuLy->kiTuPhanCachTenBangCot2} = :$k OR";
                    $thamTriKiemTra[$k] = $tuDongMaHoaMatKhau ? maHoa::maHoaChuoi($matKhauKiemTra) : $matKhauKiemTra;
                }
                $cauTruyVan = rtrim($cauTruyVan, " OR") . ")";
                if ($this->dieuHuong->xuLy->coSoDuLieu->kiemTraCotTonTai($bangTaiKhoan, $truongTaiKhoan) &&
                        $this->dieuHuong->xuLy->coSoDuLieu->kiemTraCotTonTai($bangTaiKhoan, $truongMatKhau)) {
                    $thongTinDangNhap = $this->dieuHuong->xuLy->SQL($cauTruyVan, $thamTriKiemTra)->fetchAll();
                    if (!empty($thongTinDangNhap) && is_array($thongTinDangNhap)) {
                        $mangFrameWork = mangSession::lay('vietFrameWork');
                        $thongTinDangNhap = isset($thongTinDangNhap[0]) ? $thongTinDangNhap[0] : array();
                        if (is_array($mangFrameWork)) {
                            $mangFrameWork['Thông Tin Đăng Nhập'] = array();
                            $mangFrameWork['Thông Tin Đăng Nhập'] = $thongTinDangNhap;
                        } else {
                            $mangFrameWork = array('Thông Tin Đăng Nhập' => $thongTinDangNhap);
                        }
                        mangSession::thietLap('vietFrameWork', $mangFrameWork);
                        $this->dieuHuong->xuLy->tenBangCSDL = $tenBangCSDLXuLy;
                        return true;
                    }
                }
            }
        }
        if (isset($duLieuBieuMauGuiVe[$bangTaiKhoan]) && is_array($duLieuBieuMauGuiVe[$bangTaiKhoan])) {
            foreach ($duLieuBieuMauGuiVe[$bangTaiKhoan] as $k => $v) {
                if (in_array($k, $truongMatKhau)) {
                    unset($duLieuBieuMauGuiVe[$bangTaiKhoan][$k]);
                }
            }
        }
        $this->dieuHuong->duLieuGuiDi->duLieuBieuMauGuiDi = $duLieuBieuMauGuiVe;
        $this->dieuHuong->xuLy->tenBangCSDL = $tenBangCSDLXuLy;
        return false;
    }

    /**
     * Phương thức kiểm tra thành phần biểu mẫu trong mảng đăng nhập
     * 
     * CHỨC NĂNG:
     * Hổ trợ cho phương thức đăng nhập kiểm tra xem mảng biểu mẫu gửi về từ client có tồn tại các phần tử trong mảng biểu mẫu đăng nhập trong hệ thống phân quyền hay không
     * 
     * @param array $mangPhanTuKiemTra (tham trị thứ 1 truyền vào là mảng là mảng có các phần tử cần xác định có thuộc trong mảng thứ 2 hay không)
     * @param array $mangKiemTra (tham trị thứ 2 truyền vào là mảng là mảng để xác định các phần tử trong mảng thứ 1 truyền vào có nằm trong nó hay không)
     * @return mixed (kiểu trả về là chuỗi khi thỏa điều kiện và chuỗi đó tương ứng với chuỗi dữ liệu để kiểm tra đăng nhập ngược lại NULL khi không thỏa)
     */
    private function kiemTraThanhPhanBieuMauTrongMangDangNhap($mangPhanTuKiemTra = array(), $mangKiemTra = array()) {
        if (is_array($mangPhanTuKiemTra) && is_array($mangKiemTra)) {
            foreach ($mangPhanTuKiemTra as $k => $v) {
                if (in_array($k, $mangKiemTra) && is_string($k) && !empty($v)) {
                    return $v;
                }
            }
        }
        return null;
    }

    /**
     * Phương thức từ chối truy cập liên kết
     * 
     * CHỨC NĂNG:
     * Chuyển trang khi phương thức yêu cầu không nằm trong mảng phương thức cho phép truy cập
     */
    private function tuChoiTruyCapLienKet() {
        $duongDanTraVe = null;
        if ($this->trangThaiDangNhap()) {
            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $duongDanTraVe = $_SERVER['HTTP_REFERER'];
                }
            }
        } else {
            if (is_array($this->duongDanTrangDangNhap)) {
                $mangFrameWork = mangSession::lay('vietFrameWork');
                if (is_array($mangFrameWork)) {
                    $mangFrameWork['Đường Dẫn Truy Cập Bị Từ Chối'] = dieuHuongCha::$dieuHuongPhuongThucVaThamTri;
                } else {
                    $mangFrameWork = array('Đường Dẫn Truy Cập Bị Từ Chối' => dieuHuongCha::$dieuHuongPhuongThucVaThamTri);
                }
                mangSession::thietLap('vietFrameWork', $mangFrameWork);
                $duongDanTraVe = $this->duongDanTrangDangNhap;
            }
        }
        if ($duongDanTraVe === null) {
            $duongDanTraVe = DUONGDANTRANG;
        }
        $this->dieuHuong->duLieuGuiDi->cauBaoLoiTruyCap($this->cauBaoLoiTruyCap);
        $this->dieuHuong->chuyenTrang($duongDanTraVe);
    }

}

?>