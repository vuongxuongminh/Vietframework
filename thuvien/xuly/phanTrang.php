<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                08/08/2015
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng xử lý cha (model) chứa các thuộc tính và phương thức xử lý thao tác trên CSDL
 * 
 * CHỨC NĂNG CHÍNH:
 * Hổ trợ việc phân trang dữ liệu (paginator)
 * 
 */

class phanTrang {

    /** Đối tượng xử lý
     *
     * CHỨC NĂNG:
     * Giúp tương tác với cơ sở dữ liệu
     * 
     * @var xuLyCha (kiểu đối tượng) 
     */
    private $xuLy;

    /** Đối tượng dữ liệu gửi về từ điều hướng
     *
     * CHỨC NĂNG:
     * Giúp lấy ra các thuộc tính đường dẫn hổ trợ cho việc phân trang
     * 
     * @var duLieuGuiVe (kiểu đối tượng) 
     */
    private $duLieuGuiVe;

    /** Thuộc tính tên biến số trang
     *
     * CHỨC NĂNG:
     * Quy định tên biến số trang truyền về nằm trên đường dẫn
     * 
     * @var string (kiểu chuỗi) 
     */
    private $tenBienSoTrang = "trang";

    /** Thuộc tính tên biến số dòng mỗi trang
     *
     * CHỨC NĂNG:
     * Quy định tên biến số dòng mỗi trang truyền về nằm trên đường dẫn
     * 
     * @var string (kiểu chuỗi) 
     */
    private $tenBienSoDongMoiTrang = "soDong";

    /** Thuộc tính số dòng mỗi trang
     *
     * CHỨC NĂNG:
     * Quy định số dòng dữ liệu trên mỗi trang sẽ xuất ra
     * 
     * @var int (kiểu số nguyên) 
     */
    private $soDongMoiTrang;

    /** Thuộc tính số dòng mỗi trang mặc định
     *
     * CHỨC NĂNG:
     * Khi thuộc tính số dòng mỗi trang không được thiết lập hoặc trên đường dẫn không có tham trị yêu câu số dòng mỗi trang thì thuộc tính này sẽ là thuộc tính mặc định.
     * 
     * @var int (kiểu số nguyên) 
     */
    private $soDongMoiTrangMacDinh = 20;

    /** Thuộc tính tổng số trang
     *
     * CHỨC NĂNG:
     * Có giá trị là giá trị tổng số trang dữ liệu dựa trên dữ liệu đầu vào truy vấn
     * 
     * @var int (kiểu số nguyên) 
     */
    private $tongSoTrang;

    /** Thuộc tính tổng số dòng
     *
     * CHỨC NĂNG:
     * Có giá trị là tổng số dòng dữ liệu dựa trên câu truy vấn đầu vào
     * 
     * @var int (kiểu số nguyên) 
     */
    private $tongSoDong;

    /** Thuộc tính mảng số lượng dòng giới hạn
     *
     * CHỨC NĂNG:
     * Quy định số dòng giới hạn của mỗi trang có giá trị đầu tiên trong mảng là giá trị số lượng dòng thấp nhất và giá trị tiếp theo trong mảng là giá trị số lượng dòng tối đa.
     * 
     * @var int (kiểu số nguyên) 
     */
    private $soLuongDongGioiHan = array(1, 50);

    /** Thuộc tính trang hiện tại
     *
     * CHỨC NĂNG:
     * Có giá trị là số trang hiện tại
     * 
     * @var int (kiểu số nguyên) 
     */
    private $trangHienTai;

    /** Thuộc tính kiểm tra biến số trang hợp lệ
     *
     * CHỨC NĂNG:
     * Quy định việc đối soát trang hiện tại có hợp lệ đối với kết quả lấy được từ CSDL hay không. Khuyến khích nên thiết lập thuộc tính này luôn TRUE.
     * 
     * @var boolean (kiểu đúng sai) 
     */
    private $kiemTraBienSoTrangHopLe = true;

    /** Thuộc tính kiểm tra biến số dòng hợp lệ
     *
     * CHỨC NĂNG:
     * Quy định việc đối soát số dòng mỗi trang có hợp lệ đối với 2 phần tử của mảng thuộc tính số lượng dòng giới hạn. Khuyến khích nên thiết lập thuộc tính này luôn TRUE.
     * 
     * @var boolean (kiểu đúng sai) 
     */
    private $kiemTraBienSoDongHopLe = true;

    /** Mảng thuộc tính tham trị đường dẫn liên kết
     *
     * CHỨC NĂNG:
     * Hổ trợ việc chèn các thuộc tính vào thẻ a của các nút chuyển trang. Ví dụ tôi muốn thiết lập class cho các thẻ a chứa số trang ["class" => "ten class]
     * 
     * @var array (kiểu mảng dữ liệu) 
     */
    private $thamTriTrenDuongDanLienKet = array();

    /** Mảng Thuộc tính đường dẫn phân trang
     *
     * CHỨC NĂNG:
     * Quy định lớp đối tượng điều hướng, phương thức xử lý sẻ trỏ đến khi tạo đường dẫn cho các nút trang. Ví dụ ["Điều Hướng" => "phantrang", "Phương Thức" => "trang"]. Nếu không thiết lập thuộc tính này sẽ lấy điều hương và phương thức hiện tại
     * 
     * @var array|string (kiểu đúng sai) 
     */
    private $duongDanPhanTrang = array();

    /** Mảng thuộc tính dữ liệu phân trang
     *
     * CHỨC NĂNG:
     * Có giá trị là kết quả trả về khi truy vấn CSDL cũng chính là nội dung của trang hiện tại.
     * 
     * @var array (kiểu mảng dữ liệu) 
     */
    private $mangDuLieuPhanTrang = array();

    /**
     * Phương thức tổng số trang
     * 
     * CHỨC NĂNG:
     * Trả về tổng số trang dựa trên câu truy vấn yêu cầu phân trang.
     * 
     * @return int (kiểu trả về dạng số nguyên)
     */
    public function tongSoTrang() {
        return $this->tongSoTrang;
    }

    /**
     * Phương thức trang hiện tại
     * 
     * CHỨC NĂNG:
     * Trả về số trang hiện tại.
     * 
     * @return int (kiểu trả về dạng số nguyên)
     */
    public function trangHienTai() {
        return $this->trangHienTai;
    }

    /**
     * Phương thức số dòng mỗi trang
     * 
     * CHỨC NĂNG:
     * Trả về số dòng mỗi trang trang.
     * 
     * @return int (kiểu trả về dạng số nguyên)
     */
    public function soDongMoiTrang() {
        return $this->soDongMoiTrang;
    }

    /**
     * Phương thức tổng số dòng
     * 
     * CHỨC NĂNG:
     * Trả về tổng số dòng dựa trên câu truy vấn yêu cầu phân trang.
     * 
     * @return int (kiểu trả về dạng số nguyên)
     */
    public function tongSoDong() {
        return $this->tongSoDong;
    }

    /**
     * Phương thức thiết lập trang hiện tại
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định xử lý vị trí trang dựa trên tham trị $trangHienTai truyền vào
     * 
     * @param int $trangHienTai (tham trị truyền vào dạng số nguyên)
     */
    public function thietLapTrangHienTai($trangHienTai) {
        $trangHienTai = (int) $trangHienTai;
        $this->trangHienTai = $trangHienTai;
    }

    /**
     * Phương thức thiết lập đường dẫn phân trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định lớp điều hướng và phương thức xử lý khi tạo đường dẫn các trang
     * 
     * @param mixed $mangDuongDan (tham trị truyền vào dạng chuỗi hoặc mảng)
     */
    public function thietLapDuongDanPhanTrang($mangDuongDan) {
        if (is_array($mangDuongDan) || is_string($mangDuongDan)) {
            $this->duongDanPhanTrang = $mangDuongDan;
        }
    }

    /**
     * Phương thức thiết lập tham trị trên đường dẫn liên kết
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định các biến năm trên địa chỉ truy vấn đến máy chủ nhằm để phân biệt và xử lý tùy biến
     * 
     * @param array $mangThamTri (tham trị truyền vào dạng mảng)
     */
    public function thietLapThamTriTrenDuongDanLienKet($mangThamTri) {
        if (is_array($mangThamTri)) {
            $mangThamTri = array_filter($mangThamTri, function($v) {
                return !is_array($v);
            });
            if (!empty($mangThamTri)) {
                $this->thamTriTrenDuongDanLienKet = $mangThamTri;
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức thiết lập số lượng dòng giới hạn
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định số lượng dòng giới hạn trên mỗi trang.
     * 
     * @param int $gioiHanNhoNhat (tham trị thứ 1 truyền vào dạng số nguyên là giới hạn dòng nhỏ nhất)
     * @param int $gioiHanLonNhat (tham trị thứ 2 truyền vào dạng số nguyên là giới hạn dòng lớn nhất)
     */
    public function thietLapSoLuongDongGioiHan($gioiHanNhoNhat, $gioiHanLonNhat) {
        $gioiHanNhoNhat = (int) $gioiHanNhoNhat;
        $gioiHanLonNhat = (int) $gioiHanLonNhat;
        $this->soLuongDongGioiHan = array($gioiHanNhoNhat, $gioiHanLonNhat);
    }

    /**
     * Phương thức thiết lập số lượng mỗi trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định số dòng cho mỗi trang.
     * 
     * @param int $soDongMoiTrang (tham trị truyền vào dạng số nguyên là số dòng mỗi trang)
     */
    public function thietLapSoDongMoiTrang($soDongMoiTrang = null) {
        if (!is_numeric($soDongMoiTrang)) {
            $this->soDongMoiTrang = $this->soDongMoiTrangMacDinh;
        } else {
            $soDongMoiTrang = (int) $soDongMoiTrang;
            if ($this->kiemTraBienSoDongHopLe) {
                if ($soDongMoiTrang < $this->soLuongDongGioiHan[0]) {
                    return $this->soDongMoiTrang = $this->soLuongDongGioiHan[0];
                } elseif ($soDongMoiTrang > $this->soLuongDongGioiHan[1]) {
                    return $this->soDongMoiTrang = $this->soLuongDongGioiHan[1];
                }
            }
            $this->soDongMoiTrang = $soDongMoiTrang;
        }
    }

    /**
     * Phương thức thiết lập tên biến số trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định tên biến số trang trên URL gửi về.
     * 
     * @param string $tenBien (tham trị truyền vào dạng chuỗi là tên biến số trang)
     */
    public function thietLapTenBienSoTrang($tenBien) {
        if (is_numeric($tenBien) || is_string($tenBien)) {
            $this->tenBienSoTrang = $tenBien;
        }
    }

    /**
     * Phương thức thiết lập tên biến số dòng mỗi trang trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định tên biến số dòng mỗi trang trang trên URL gửi về.
     * 
     * @param string $tenBien (tham trị truyền vào dạng chuỗi là tên biến số dòng mỗi trang)
     */
    public function thietLapTenBienSoDongMoiTrang($tenBien) {
        if (is_numeric($tenBien) || is_string($tenBien)) {
            $this->tenBienSoDongMoiTrang = $tenBien;
        }
    }

    /**
     * Phương thức thiết lập số dòng mỗi trang mặc định
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc quy định số dòng mỗi trang mặc định khi số dòng mỗi trang không xác định sẽ sử dụng thuộc tính này.
     * 
     * @param int $soDongMoiTrang (tham trị truyền vào dạng số nguyên là số dòng mỗi trang mặc định)
     */
    public function thietLapSoDongMoiTrangMacDinh($soDongMoiTrang = null) {
        $soDongMoiTrang = (int) $soDongMoiTrang;
        if ($this->kiemTraBienSoDongHopLe) {
            if ($soDongMoiTrang < $this->soLuongDongGioiHan[0]) {
                $this->soDongMoiTrang = $this->soLuongDongGioiHan[0];
            } elseif ($soDongMoiTrang > $this->soLuongDongGioiHan[1]) {
                $this->soDongMoiTrang = $this->soLuongDongGioiHan[1];
            }
        }
        $this->soDongMoiTrang = $soDongMoiTrang;
    }

    /**
     * Phương thức khởi tạo đối tượng phân trang
     * 
     * CHỨC NĂNG:
     * Thiết lập một số thuộc tính mặc định nhằm hổ trợ cho các phương thức xử lý phân trang bên dưới.
     * 
     * @param obj $xuLy (tham trị thứ 1 truyền vào là đối tượng xử lý)
     * @param obj $duLieuGuiVe (tham trị thứ 2 truyền vào là đối tượng dữ liệu gửi về)
     */
    public function __construct($xuLy, $duLieuGuiVe) {
        if (is_object($xuLy) && is_object($duLieuGuiVe)) {
            $this->xuLy = $xuLy;
            $this->duLieuGuiVe = $duLieuGuiVe;
            $duLieuGet = $this->duLieuGuiVe->duLieuGetGuiVe;
            $this->trangHienTai = (isset($duLieuGet[$this->tenBienSoTrang]) && intval($duLieuGet[$this->tenBienSoTrang]) > 0) ? intval($duLieuGet[$this->tenBienSoTrang]) : 1;
            $this->thietLapSoDongMoiTrang(isset($duLieuGet[$this->tenBienSoDongMoiTrang]) ? $duLieuGet[$this->tenBienSoDongMoiTrang] : null);
            $this->duongDanPhanTrang = dieuHuongCha::$dieuHuongPhuongThucVaThamTri;
        }
    }

    /**
     * Phương thức khởi tạo các phương thức tự sinh bắt đầu bằng boi
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc truy vấn phân trang dữ liệu CÓ ĐIỀU KIỆN.
     * 
     * @param string $tenPhuongThuc (tham trị thứ 1 truyền vào tên phương thức yêu cầu xử lý)
     * @param array $thamTri (tham trị thứ 2 truyền vào mảng tham trị truyền vào phương thức yêu cầu xử lý)
     */
    public function __call($tenPhuongThuc, $thamTri = array()) {
        if (strpos($tenPhuongThuc, "boi") === 0) {
            $this->tongSoDong = call_user_func_array(array($this->xuLy, str_replace("boi", "demDuLieuBoi", $tenPhuongThuc)), $thamTri);
            $mangThuocTinhTruyVan = (isset($thamTri[1]) && is_array($thamTri[1])) ? $thamTri[1] : array();
            $mangDieuKienSQL = (isset($thamTri[0]) && is_array($thamTri[0])) ? $thamTri[0] : array();
            return $this->xuLyLayDuLieuTrangHienTai(str_replace("boi", "layDuLieuBoi", $tenPhuongThuc), $mangThuocTinhTruyVan, $mangDieuKienSQL);
        }
        return trigger_error("Phương thức " . $tenPhuongThuc . " không tồn tại trong lớp phân trang " . get_class($this) . " vui lòng kiểm tra lại bạn nhé !", E_USER_WARNING);
    }

    /**
     * Phương thức phân trang toàn bộ
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc truy vấn phân trang dữ liệu KHÔNG ĐIỀU KIỆN.
     * 
     * @param array $mangThamTri (tham trị truyền vào mảng tham trị truyền vào phương thức lấy dữ liệu)
     */
    public function toanBo($mangThamTri = array()) {
        $this->tongSoDong = $this->xuLy->demDuLieu($mangThamTri);
        return $this->xuLyLayDuLieuTrangHienTai("layDuLieu", $mangThamTri);
    }

    /**
     * Phương thức phân trang toàn bộ
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc truy vấn phân trang dựa trên câu truy vấn SQL
     * 
     * @param string $cauTruyVan (câu truy vấn dữ liệu SQL)
     * @param array $mangThamTri (mảng tham trị hổ trợ truy vấn SQL)
     */
    public function SQL($cauTruyVan, $thamTri = array()) {
        $cauTruyVanDemSoDong = "SELECT COUNT(*) as demdulieu FROM ($cauTruyVan) as DemDuLieu";
        $tongSoDong = $this->xuLy->SQL($cauTruyVanDemSoDong, $thamTri)->fetchAll(PDO::FETCH_ASSOC);
        $this->tongSoDong = $tongSoDong[0]["demdulieu"];
        if (is_numeric($this->tongSoDong) && $this->tongSoDong > 0) {
            if (!$this->kiemTraBienSoTrangHopLe || (($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang) <= $this->tongSoDong) {
                $this->tongSoTrang = ceil($this->tongSoDong / $this->soDongMoiTrang);
                preg_match("/limit\s+([0-9]+)(,([0-9]+))?/i", $cauTruyVan, $gioiHan);
                $gioiHan1 = isset($gioiHan[1]) ? $gioiHan[1] : $this->tongSoDong - 1;
                $gioiHan2 = isset($gioiHan[3]) ? $gioiHan[3] : false;
                if ($gioiHan2 !== false) {
                    $gioiHan1 = $gioiHan1 + (($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang);
                } else {
                    $gioiHan1 = ($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang;
                }
                $cauTruyVan = preg_replace("/limit\s+([0-9]+)(,([0-9]+))?/i", "", $cauTruyVan);
                $cauTruyVan .= " LIMIT $gioiHan1," . $this->soDongMoiTrang;
                $mangKetQua = $this->xuLy->SQL($cauTruyVan, $thamTri)->fetchAll(PDO::FETCH_ASSOC);
                $this->mangDuLieuPhanTrang = (is_array($mangKetQua) && !empty($mangKetQua)) ? $mangKetQua : array();
                return $this->tongHopKetQuaTraVe();
            }
        }
        return array();
    }

    /**
     * Phương thức xử lý dữ liệu trang hiện tại
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc xử lý lấy ra dữ liệu của trang hiện tại.
     * 
     * @param string $tenPhuongThucXuLy (tham trị thứ 1 truyền vào dạng chuỗi là tên phương thức lấy dữ liệu)
     * @param array $mangThuocTinhTruyVan (tham trị thứ 2 truyền vào dạng mảng thuộc tính truy vấn)
     * @param array $mangDieuKienSQL (tham trị thứ 3 truyền vào dạng mảng điều kiện truy vấn SQL không bắt buộc)
     * @return array (kiểu trả về dạng mảng là mảng dữ liệu của trang hiện tại)
     */
    private function xuLyLayDuLieuTrangHienTai($tenPhuongThucXuLy, $mangThuocTinhTruyVan, $mangDieuKienSQL = array()) {
        if (is_numeric($this->tongSoDong) && $this->tongSoDong > 0) {
            if (!$this->kiemTraBienSoTrangHopLe || (($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang) <= $this->tongSoDong) {
                $this->tongSoTrang = ceil($this->tongSoDong / $this->soDongMoiTrang);
                foreach ($mangThuocTinhTruyVan as $thuocTinh => $giaTri) {
                    $khoaVietThuong = mb_strtolower($thuocTinh, "utf-8");
                    unset($mangThuocTinhTruyVan[$thuocTinh]);
                    $mangThuocTinhTruyVan[$khoaVietThuong] = $giaTri;
                }
                $mangThuocTinhTruyVan["giới hạn"] = isset($mangThuocTinhTruyVan["giới hạn"]) ? $mangThuocTinhTruyVan["giới hạn"] : $this->tongSoDong - 1;
                $phanTichGioiHan = explode(",", $mangThuocTinhTruyVan["giới hạn"]);
                array_walk($phanTichGioiHan, function(&$giaTri, $khoa) {
                    $giaTri = intval(trim($giaTri));
                });
                $gioiHan1 = $phanTichGioiHan[0];
                $gioiHan2 = isset($phanTichGioiHan[1]) ? $phanTichGioiHan[1] : false;
                if ($gioiHan2 !== false) {
                    $gioiHan1 = $gioiHan1 + (($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang);
                } else {
                    $gioiHan1 = ($this->soDongMoiTrang * $this->trangHienTai) - $this->soDongMoiTrang;
                }
                $mangThuocTinhTruyVan["giới hạn"] = "$gioiHan1," . $this->soDongMoiTrang;
                $mangTruyVan = (is_array($mangDieuKienSQL) && !empty($mangDieuKienSQL)) ? array($mangDieuKienSQL, $mangThuocTinhTruyVan) : array($mangThuocTinhTruyVan);
                $mangKetQua = call_user_func_array(array($this->xuLy, $tenPhuongThucXuLy), $mangTruyVan);
                $this->mangDuLieuPhanTrang = (is_array($mangKetQua) && !empty($mangKetQua)) ? $mangKetQua : array();
                return $this->tongHopKetQuaTraVe();
            }
        }
        return array();
    }

    /**
     * Phương thức khởi tạo đường dẫn các trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc xử lý khởi tạo đường dẫn URL cho từng trang dựa trên dữ liệu truy vấn được.
     * 
     * @return array (kiểu trả về dạng mảng là mảng dữ liệu đường dẫn các trang)
     */
    private function khoiTaoDuongDanCacTrang() {
        $duongDanChinh = kiemTraLoi::duongDanLienKet($this->duongDanPhanTrang) ? $this->duongDanPhanTrang : doiTuongHtml::_xuLyDuongDanTuYeuCau(array($this->duongDanPhanTrang));
        $tongSoTrang = $this->tongSoTrang;
        $thamTriDuongDan = $this->thamTriTrenDuongDanLienKet;
        $danhSachDuongDan = array();
        if (strpos($duongDanChinh, '?') === false) {
            $duongDanChinh .= '?';
        } else if (substr($duongDanChinh, strlen($duongDanChinh) - 1, 1) != '&') {
            $duongDanChinh .= '&';
        }
        for ($i = 1; $i <= $tongSoTrang; $i++) {
            $thamTriDuongDan[$this->tenBienSoTrang] = $i;
            $danhSachDuongDan[$i] = $duongDanChinh . http_build_query($thamTriDuongDan, "&");
        }
        return $danhSachDuongDan;
    }

    /**
     * Phương thức tổng hợp kết quả trả về
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc tổng hợp các dữ liệu phân trang trả về cho lập trình viên.
     * 
     * @return array (kiểu trả về dạng mảng là mảng dữ liệu tổng hợp phân trang)
     */
    private function tongHopKetQuaTraVe() {
        $mangDuongDanCacTrang = $this->khoiTaoDuongDanCacTrang();
        return array(
            "Mảng Dữ Liệu Trang Hiện Tại" => $this->mangDuLieuPhanTrang,
            "Tổng Số Trang" => $this->tongSoTrang,
            "Mảng Đường Dẫn" => array(
                "Trang Đầu" => $mangDuongDanCacTrang[1],
                "Trang Cuối" => end($mangDuongDanCacTrang),
                "Trang Trước" => isset($mangDuongDanCacTrang[$this->trangHienTai - 1]) ? $mangDuongDanCacTrang[$this->trangHienTai - 1] : "#",
                "Trang Sau" => isset($mangDuongDanCacTrang[$this->trangHienTai + 1]) ? $mangDuongDanCacTrang[$this->trangHienTai + 1] : "#",
                "Tất Cả" => $mangDuongDanCacTrang
            ),
            "Trang Hiện Tại" => $this->trangHienTai,
            "Tổng Số Dòng" => $this->tongSoDong,
            "Số Dòng Mỗi Trang" => $this->soDongMoiTrang,
        );
    }

    /**
     * Phương thức tạo các nút chuyển trang HTML
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc tạo các nút chuyển trang bên tầng hiển thị (VIEW) cho lập trình viên.
     * 
     * @param array $mangPhanTrang (Tham trị thứ 1 là mảng phân trang có được khi gọi phương thức xử lý toàn bộ hoặc bởi phía trên)
     * @param array $mangThuocTinh (Tham trị thứ 2 là mảng thuộc tính tạo nút chi tiết xem tại tài liệu hướng dẫn)
     * @return string (kiểu trả về chuỗi là dữ liệu để xuất ra cho người dùng)
     */
    public static function taoCacNutChuyenTrangHTML($mangPhanTrang, $mangThuocTinh = array()) {
        if (!is_array($mangPhanTrang) || empty($mangPhanTrang)) {
            return "";
        }
        $mangThuocTinhMacDinh = array(
            "html" => array(
                "bao quát" => "<ul style='list-style-type:none'>{{HTMLCACNUT}}</ul>",
                "các nút" => "<li style='float:left'>{{NOIDUNG}}</li>",
            ),
            "thuộc tính thẻ a" => array("style" => "margin:2px"),
            "nội dung nút trang sau" => "next",
            "nội dung nút trang trước" => "pre",
            "nội dung nút trang cuối" => "last",
            "nội dung nút trang đầu" => "first",
            "lớp css nút trang hiện tại" => "active",
            "lớp css nút trang sau" => "next-paginator",
            "lớp css nút trang trước" => "pre-paginator",
            "lớp css nút trang cuối" => "last-paginator",
            "lớp css nút trang đầu" => "first-paginator",
            "hiển thị nút đầu cuối" => true,
            "hiển thị nút sau trước" => true,
            "ẩn khi chỉ một trang" => true,
            "giới hạn nút" => 10
        );
        $mangThuocTinh = is_array($mangThuocTinh) ? $mangThuocTinh : array();
        foreach ($mangThuocTinh as $thuocTinh => $giaTri) {
            if (is_string($giaTri) && !isset($mangThuocTinh[mb_strtolower($thuocTinh, "utf-8")])) {
                $mangThuocTinh[mb_strtolower($thuocTinh, "utf-8")] = $giaTri;
                unset($mangThuocTinh[$thuocTinh]);
            } elseif (is_array($giaTri)) {
                foreach ($giaTri as $thuocTinh2 => $giaTri2) {
                    if (!isset($mangThuocTinh[mb_strtolower($thuocTinh2, "utf-8")])) {
                        $mangThuocTinh[mb_strtolower($thuocTinh2, "utf-8")] = $giaTri2;
                        unset($mangThuocTinh[$thuocTinh2]);
                    }
                }
            }
        }
        $mangThuocTinh = mangTroNang::gopMang($mangThuocTinhMacDinh, $mangThuocTinh);
        foreach ($mangPhanTrang as $phanTuPhanTrang => $duLieuPhanTuPhanTrang) {
            if (!in_array($phanTuPhanTrang, array("Mảng Dữ Liệu Trang Hiện Tại", "Tổng Số Trang", "Mảng Đường Dẫn", "Trang Hiện Tại", "Tổng Số Dòng", "Số Dòng Mỗi Trang"))) {
                return "";
            } elseif ($phanTuPhanTrang === "Mảng Đường Dẫn" && is_array($duLieuPhanTuPhanTrang)) {
                foreach (array("Trang Đầu", "Trang Cuối", "Trang Trước", "Trang Sau", "Tất Cả") as $phanTuDuongDan) {
                    if (!array_key_exists($phanTuDuongDan, $duLieuPhanTuPhanTrang)) {
                        return "";
                    }
                }
            }
        }
        $doiTuongHtml = new doiTuongHtml;
        $trangHienTai = $mangPhanTrang["Trang Hiện Tại"];
        $tongSoTrang = $mangPhanTrang["Tổng Số Trang"];
        $mangThuocTinhTheA = is_array($mangThuocTinh["thuộc tính thẻ a"]) ? $mangThuocTinh["thuộc tính thẻ a"] : array();
        $mangThuocTinhTheA["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " theAPhanTrangVFTuSinh" : "theAPhanTrangVFTuSinh";
        if ($trangHienTai == 1 && $tongSoTrang == 1 && $mangThuocTinh["ẩn khi chỉ một trang"]) {
            return "";
        }
        $cacNutSoTrang = self::themCacNutSoTrang($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml);
        return preg_replace("/\{\{HTMLCACNUT\}\}/", $cacNutSoTrang, $mangThuocTinh["html"]["bao quát"]);
    }

    /**
     * Thuộc tính vị trí bắt đầu nút và vị trí kết thúc nút
     * 
     * CHỨC NĂNG:
     * Hổ trợ cho 2 phương thức themNutTruocDau và themNutSauCuoi ở phía dưới
     * 
     */
    private static $viTriBatDauNut = null, $viTriKetThucNut = null;

    /**
     * Phương thức thêm nút sau cuối
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc tạo hai nút sau và cuối cùng.
     * 
     * @param array $mangPhanTrang (Tham trị thứ 1 là mảng phân trang có được khi gọi phương thức xử lý toàn bộ hoặc bởi phía trên)
     * @param array $mangThuocTinh (Tham trị thứ 2 là mảng thuộc tính tạo nút chi tiết xem tại tài liệu hướng dẫn)
     * @param array $mangThuocTinhTheA (Tham trị thứ 3 là mảng thuộc tính của thẻ a dùng để gắn vào thẻ a khi sinh ra thẻ)
     * @param obj $doiTuongHtml (Tham trị thứ 4 là đối tượng HTML tại tầng VIEW hổ trợ cho việc tạo liên kết và xử lý thuộc tính thẻ a)
     * @return string (kiểu trả về chuỗi là dữ liệu để xuất ra cho người dùng)
     */
    private static function themNutSauCuoi($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml) {
        $mangThuocTinhTheASau = $mangThuocTinhTheA;
        $mangThuocTinhTheASau["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " " . $mangThuocTinh["lớp css nút trang sau"] : $mangThuocTinh["lớp css nút trang sau"];
        $mangThuocTinhTheACuoi = $mangThuocTinhTheA;
        $mangThuocTinhTheACuoi["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " " . $mangThuocTinh["lớp css nút trang cuối"] : $mangThuocTinh["lớp css nút trang cuối"];
        $noiDungTheA = "";
        $tongSoTrang = $mangPhanTrang["Tổng Số Trang"];
        if (self::$viTriKetThucNut <= $tongSoTrang) {
            if ($mangThuocTinh["hiển thị nút sau trước"]) {
                $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a href='{$mangPhanTrang["Mảng Đường Dẫn"]["Trang Sau"]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheASau) . ">{$mangThuocTinh["nội dung nút trang sau"]}</a>", $mangThuocTinh["html"]["các nút"]);
            }
            if ($mangThuocTinh["hiển thị nút đầu cuối"]) {
                $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a href='{$mangPhanTrang["Mảng Đường Dẫn"]["Trang Cuối"]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheACuoi) . ">{$mangThuocTinh["nội dung nút trang cuối"]}</a>", $mangThuocTinh["html"]["các nút"]);
            }
        }
        return $noiDungTheA;
    }

    /**
     * Phương thức thêm nút trước đầu
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc tạo hai nút trước và đầu tiên
     * 
     * @param array $mangPhanTrang (Tham trị thứ 1 là mảng phân trang có được khi gọi phương thức xử lý toàn bộ hoặc bởi phía trên)
     * @param array $mangThuocTinh (Tham trị thứ 2 là mảng thuộc tính tạo nút chi tiết xem tại tài liệu hướng dẫn)
     * @param array $mangThuocTinhTheA (Tham trị thứ 3 là mảng thuộc tính của thẻ a dùng để gắn vào thẻ a khi sinh ra thẻ)
     * @param obj $doiTuongHtml (Tham trị thứ 4 là đối tượng HTML tại tầng VIEW hổ trợ cho việc tạo liên kết và xử lý thuộc tính thẻ a)
     * @return string (kiểu trả về chuỗi là dữ liệu để xuất ra cho người dùng)
     */
    private static function themNutTruocDau($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml) {
        $noiDungTheA = "";
        $mangThuocTinhTheADau = $mangThuocTinhTheA;
        $mangThuocTinhTheADau["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " " . $mangThuocTinh["lớp css nút trang đầu"] : $mangThuocTinh["lớp css nút trang đầu"];
        $mangThuocTinhTheATruoc = $mangThuocTinhTheA;
        $mangThuocTinhTheATruoc["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " " . $mangThuocTinh["lớp css nút trang trước"] : $mangThuocTinh["lớp css nút trang trước"];
        if (self::$viTriBatDauNut > 1) {
            if ($mangThuocTinh["hiển thị nút đầu cuối"]) {
                $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a href='{$mangPhanTrang["Mảng Đường Dẫn"]["Trang Đầu"]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheADau) . ">{$mangThuocTinh["nội dung nút trang đầu"]}</a>", $mangThuocTinh["html"]["các nút"]);
            }
            if ($mangThuocTinh["hiển thị nút sau trước"]) {
                $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a href='{$mangPhanTrang["Mảng Đường Dẫn"]["Trang Trước"]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheATruoc) . ">{$mangThuocTinh["nội dung nút trang trước"]}</a>", $mangThuocTinh["html"]["các nút"]);
            }
        }
        return $noiDungTheA;
    }

    /**
     * Phương thức thêm nút sau cuối
     * 
     * CHỨC NĂNG:
     * Hổ trợ việc tạo các nút số trang.
     * 
     * @param array $mangPhanTrang (Tham trị thứ 1 là mảng phân trang có được khi gọi phương thức xử lý toàn bộ hoặc bởi phía trên)
     * @param array $mangThuocTinh (Tham trị thứ 2 là mảng thuộc tính tạo nút chi tiết xem tại tài liệu hướng dẫn)
     * @param array $mangThuocTinhTheA (Tham trị thứ 3 là mảng thuộc tính của thẻ a dùng để gắn vào thẻ a khi sinh ra thẻ)
     * @param obj $doiTuongHtml (Tham trị thứ 4 là đối tượng HTML tại tầng VIEW hổ trợ cho việc tạo liên kết và xử lý thuộc tính thẻ a)
     * @return string (kiểu trả về chuỗi là dữ liệu để xuất ra cho người dùng)
     */
    private static function themCacNutSoTrang($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml) {
        $noiDungTheA = "";
        $trangHienTai = $mangPhanTrang["Trang Hiện Tại"];
        $chiaNua = floor($mangThuocTinh["giới hạn nút"] / 2);
        $chiaDu = $mangThuocTinh["giới hạn nút"] % 2;
        $viTriBatDauNut = $trangHienTai - $chiaNua;
        $viTriKetThucNut = $trangHienTai + $chiaNua;
        $mangThuocTinhTheAHienTai = $mangThuocTinhTheA;
        $mangThuocTinhTheAHienTai["class"] = isset($mangThuocTinhTheA["class"]) ? $mangThuocTinhTheA["class"] . " " . $mangThuocTinh["lớp css nút trang hiện tại"] : $mangThuocTinh["lớp css nút trang hiện tại"];
        if ($viTriBatDauNut < 1) {
            $viTriKetThucNut += (1 - $viTriBatDauNut) + $chiaDu;
            $viTriBatDauNut = 1;
        }
        $mangDuongDanCacTrang = $mangPhanTrang["Mảng Đường Dẫn"]["Tất Cả"];
        $viTriNutLui = 1;
        for ($i = $viTriBatDauNut; $i < $viTriKetThucNut; $i++) {
            if (isset($mangDuongDanCacTrang[$i])) {
                if ($i == $trangHienTai) {
                    $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a phantrang-vf='$i' href='{$mangDuongDanCacTrang[$i]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheAHienTai) . ">$i</a>", $mangThuocTinh["html"]["các nút"]);
                } else {
                    $noiDungTheA .= preg_replace("/\{\{NOIDUNG\}\}/", "<a phantrang-vf='$i' href='{$mangDuongDanCacTrang[$i]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheA) . ">$i</a>", $mangThuocTinh["html"]["các nút"]);
                }
            } elseif (isset($mangDuongDanCacTrang[$viTriBatDauNut - $viTriNutLui])) {
                self::$viTriKetThucNut = is_null(self::$viTriKetThucNut) ? $i : self::$viTriKetThucNut;
                $noiDungTheA = preg_replace("/\{\{NOIDUNG\}\}/", "<a phantrang-vf='" . ($viTriBatDauNut - $viTriNutLui) . "' href='{$mangDuongDanCacTrang[$viTriBatDauNut - $viTriNutLui]}' " . $doiTuongHtml->xuLyThanhPhanBenTrong($mangThuocTinhTheA) . ">" . ($viTriBatDauNut - $viTriNutLui) . "</a>", $mangThuocTinh["html"]["các nút"]) . $noiDungTheA;
                self::$viTriBatDauNut = $viTriBatDauNut - $viTriNutLui;
                $viTriNutLui += 1;
            }
        }
        self::$viTriBatDauNut = is_null(self::$viTriBatDauNut) ? $viTriBatDauNut : self::$viTriBatDauNut;
        self::$viTriKetThucNut = is_null(self::$viTriKetThucNut) ? $viTriKetThucNut : self::$viTriKetThucNut;
        return self::themNutTruocDau($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml) . $noiDungTheA . self::themNutSauCuoi($mangPhanTrang, $mangThuocTinh, $mangThuocTinhTheA, $doiTuongHtml);
    }

}
