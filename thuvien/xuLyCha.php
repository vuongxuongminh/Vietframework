<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                20/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng xử lý cha (model) chứa các thuộc tính và phương thức xử lý thao tác trên CSDL
 * 
 * CHỨC NĂNG CHÍNH:
 * Thêm dữ liệu (insert). Xóa dữ liệu (delete). Cập nhật dữ liệu (update). Lấy dữ liệu (select)
 * Xử lý kiểm tra lỗi trong quá trình thêm hoặc cập nhật dữ liệu
 * 
 */

class xuLyCha {

    /** Đối tượng bộ nhớ đệm
     *
     * CHỨC NĂNG:
     * Tạo vùng nhớ ở các phiên làm việc của các phương thức lấy dữ liệu từ cơ sở dữ liệu nhằm tối ưu hóa tốc độ xử lý của máy chủ (cache)
     * 
     * Ngoài ra bộ nhớ đệm còn hổ trợ lưu lại toàn bộ dữ liệu trả về (tầng hiển thị VIEW) trong một khoảng thời gian tùy ý
     * 
     * @var boNhoDem (kiểu đối tượng) 
     */
    public $boNhoDem;

    /** Đối tượng phân trang
     *
     * CHỨC NĂNG:
     * Giúp phân trang cơ sở dữ liệu cho người dùng tương tác (pagination) khi gọi cơ sở dữ liệu
     * 
     * @var phanTrang (kiểu đối tượng) 
     */
    public $phanTrang;

    /**
     * Đối tượng cơ sở dữ liệu
     * 
     * CHỨC NĂNG:
     * Thao tác kết nối và truy vấn trên cơ sở dữ liệu nhằm hỗ trợ các phương thức bên dưới có thể thao tác với csdl (database)
     * 
     * @var coSoDuLieu (kiểu đối tượng)
     */
    public $coSoDuLieu;

    /**
     * Thuộc tính chuỗi tên bảng cơ sở dữ liệu
     * 
     * CHỨC NĂNG:
     * Giúp hệ thống xác định được tên bảng CSDL muốn xử lý
     * 
     * Ví dụ: tôi muốn xử lý thao tác dữ liệu trên bảng sanpham thì tại tầng xử lý (model) tôi khai báo như sau "public $tenBangCSDL = 'sanpham'" còn tại tầng điều hướng (controller) thì khai báo như sau "$this->xuLy->tenBangCSDL = 'sanpham'"
     * 
     * Nếu bạn không thiết lập tên bảng CSDL thì mặc định tên bảng cũng là tên phương thức yêu cầu gửi về từ client
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public $tenBangCSDL;

    /**
     * Thuộc tính chuỗi khóa chính
     * 
     * CHỨC NĂNG:
     * Hỗ trợ các phương thức lưu (cập nhật dữ liệu) hoặc xóa dựa trên mảng dữ liệu gửi về phương thức luuDuLieu và xoaDuLieu sẽ dựa trên khóa chính của bảng để xác định dòng cần xóa hoặc cần lưu
     * 
     * Cách thiết lập khóa chính: tôi muốn thiết lập khóa chính la id thì tại tầng xử lý (model) tôi khai báo như sau "public $khoaChinh = 'id'" còn tại tầng điều hướng (controller) thì khai báo như sau "$this->xuLy->khoaChinh = 'id'"
     * 
     * Ví dụ: mảng dữ liệu gửi về để xóa của tôi là array("sanpham" => array("id" => 1)) trong đó khóa chính tôi thiết lập là id thì phương thức xoaDuLieu sẽ dựa trên giá trị id đó để xóa dòng dữ liệu thóa điều kiện id = 1
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public $khoaChinh = "id";

    /**
     * Mảng thuộc tính kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Giúp bạn kiểm tra lỗi (validate) dữ liệu cần xử lý trong phương thức luuDuLieu (update hoặc insert)
     * 
     * Ví dụ: tôi muốn kiểm tra lỗi trường tensanpham chỉ có kí tự và số trên bảng sanpham thì tại tầng xử lý (model) tôi khai báo như sau "public $kiemTraLoi = array("sanpham" => array("tensanpham" => array("Kiểm Tra Lỗi" => "chuoiSoVaChu")));"
     * 
     * Trong đó "sanpham" là tên bảng cần kiểm tra. "tensanpham" là tên trường cần kiểm tra. "chuoiChuVaSo" là phương thức kiểm tra dữ liệu
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $kiemTraLoi = array();

    /**
     * Mảng thuộc tính báo lỗi
     * 
     * CHỨC NĂNG:
     * Khi thực hiện phương thức lưu mà có bật tính năng báo lỗi thì trong quá trình kiểm lỗi nếu có sẽ được nạp vào mảng này
     * 
     * Mục đích sau khi xử lý xong mảng này sẽ được truyền về tầng hiển thị (VIEW) để xuất các lỗi ra màn hình
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $mangBaoLoi = array();

    /**
     * Đối tượng dữ liệu gửi về
     * 
     * CHỨC NĂNG:
     * Có các thuộc tính và phương thức quản lý dữ liệu gửi về từ client
     * Ví dụ: lấy tên điều hướng, phương thức, dãy tham trị, dạng dữ liệu
     * 
     * @var duLieuGuiVe (kiểu đối tượng)
     */
    protected $duLieuGuiVe;

    /**
     * Phương thức khởi tạo lớp đối tượng xử lý (MODEL)
     * 
     * CHỨC NĂNG:
     * Thiết lập thuộc tính duLieuGuiVe là đối tượng dữ liệu gửi về tại tầng điều hướng (controller)
     * @param type $duLieuGuiVe
     */
    public function __construct($duLieuGuiVe = null) {
        $this->duLieuGuiVe = $duLieuGuiVe;
        $this->boNhoDem = new boNhoDem;
        $this->phanTrang = new phanTrang($this, $duLieuGuiVe);
    }

    /**
     * Phương thức tự sinh
     * 
     * CHỨC NĂNG:
     * Dùng để tùy biến phương thức layDuLieu hoac xoaDuLieu đi theo đó là chuỗi điều kiện
     * 
     * Ví dụ: tôi muốn lấy dữ liệu nơi mà có trường id = 1 và trường username = "vietFrameWork" thì tại tầng điều hướng (controller) tôi gọi như sau "$this->xuLy->layDuLieuBoiidVAusername(array(array("=" => 1), array("=" => "vietFrameWork"))"
     * 
     * Ví dụ: tôi muốn xóa dữ liệu nơi mà có trường id = 1 và trường username = "vietFrameWork" thì tại tầng điều hướng (controller) tôi gọi như sau "$this->xuLy->xoaDuLieuBoiidVAusername(array(array("=" => 1), array("=" => "vietFrameWork"))"
     * 
     * Trong mảng tham trị các giá trị tương ấn với vị trí trong tên phương thức
     * 
     * MỞ RỘNG: 
     * Các thuộc tính trong mảng thuộc tính:
     * 
     * + "Sắp Xếp" có 2 giá trị là "Từ Trên Xuống" hoặc "Từ Dưới Lên" mặc đình là "Từ Trên Xuống"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiLay và thucHienSauKhiLay ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * + "Kiểu Trả Về" có 2 giá trị là "Tập Hợp" hoặc "Phần Tử" đối với "Tập Hợp" phương thức sẽ trả về mảng theo dạng các phần từ được đánh dấu từ 0 -> n 
     * đối với dạng "Phần Tử" thì phương thức sẽ trả về duy nhất giá trị dòng đầu tiên lấy được trên bảng dữ liệu và dạng phần tử không đánh số (gới ý: dùng để nạp dữ liệu lên biểu mãu)
     * 
     * + "Giới Hạn"  là số giới hạn phần tử trả về có 2 dạng dữ liệu 1 là số nguyên ví dụ tôi để giới hạn là "5" thì trong mảng phần tử dữ liệu trả về chỉ lấy ra 5 phần từ đầu tiên.
     * Ví dụ tôi đề giới hạn là "5,10" thì trong mảng phần tử dữ liệu trả về chỉ lấy phần tử thứ 5 đến phần tử thứ 10 còn lại bỏ qua  
     * 
     * @param string $tenPhuongThuc (tham trị thứ 1 dạng chuỗi là tên phương thức gọi về)
     * @param array $thamTri (tham trị thứ 2 dạng mảng là dãy các tham trị truyền về)
     * @return array (kiểu trả về dạng mảng hoặc báo lỗi)
     */
    public function __call($tenPhuongThuc, $thamTri = array()) {
        $mangXuLyPhuongThuc = array("layDuLieuBoi", "xoaDuLieuBoi", "demDuLieuBoi");
        foreach ($mangXuLyPhuongThuc as $v) {
            if (strpos($tenPhuongThuc, $v) === 0) {
                $this->ketNoiCSDL();
                $xuLyTenPhuongThuc = explode($v, $tenPhuongThuc);
                if (isset($thamTri[0]) && is_array($thamTri[0]) && count($xuLyTenPhuongThuc) === 2 && $xuLyTenPhuongThuc[0] == "" && $xuLyTenPhuongThuc[1] != "") {
                    $mangKhoa = $this->layMangKhoaPhuongThucTuSinh($xuLyTenPhuongThuc[1]);
                    if (count($mangKhoa) === count($thamTri[0])) {
                        $cauTruyVan = "";
                        $mangThamTri = array();
                        foreach ($mangKhoa as $k1 => $v1) {
                            $xuLyMangKhoa = explode("|", $v1);
                            if (is_array($thamTri[0][$k1]) && !empty($thamTri[0][$k1])) {
                                $viTriThamTri = 0;
                                $cauTruyVanPhanTu = "";
                                foreach ($thamTri[0][$k1] as $toanTu => $giaTri) {
                                    if (!is_array($giaTri)) {
                                        $cauTruyVanPhanTu .= "{$this->kiTuPhanCachTenBangCot1}" . $xuLyMangKhoa[0] . "{$this->kiTuPhanCachTenBangCot2} " . trim($toanTu) . " :" . $k1 . $viTriThamTri . " ";
                                        if ($cauTruyVanPhanTu !== "") {
                                            $cauTruyVanPhanTu .= "HOAC ";
                                        }
                                        $mangThamTri[$k1 . $viTriThamTri] = $giaTri;
                                    } else {
                                        $giaTriPhanTu = array_filter($giaTri, function($giaTriPhanTu) {
                                            return !is_array($giaTriPhanTu);
                                        });
                                        if (strtolower(trim($toanTu)) === "between" && count($giaTriPhanTu) >= 2) {
                                            $cauTruyVanPhanTu .= "{$this->kiTuPhanCachTenBangCot1}" . $xuLyMangKhoa[0] . "{$this->kiTuPhanCachTenBangCot2} " . trim($toanTu) . " :" . $k1 . $viTriThamTri . " VA :$k1" . ($viTriThamTri + 1) . " HOAC ";
                                            $mangThamTri[$k1 . $viTriThamTri] = $giaTriPhanTu[0];
                                            $mangThamTri[$k1 . (1 + $viTriThamTri)] = $giaTriPhanTu[1];
                                        } else {
                                            foreach ($giaTriPhanTu as $giaTri2) {
                                                $cauTruyVanPhanTu .= "{$this->kiTuPhanCachTenBangCot1}" . $xuLyMangKhoa[0] . "{$this->kiTuPhanCachTenBangCot2} " . trim($toanTu) . " :" . $k1 . $viTriThamTri . " ";
                                                if ($cauTruyVanPhanTu !== "") {
                                                    $cauTruyVanPhanTu .= "HOAC ";
                                                }
                                                $mangThamTri[$k1 . $viTriThamTri] = $giaTri2;
                                                $viTriThamTri += 1;
                                            }
                                        }
                                    }
                                    $viTriThamTri += 1;
                                }
                                $cauTruyVan .= "(" . rtrim($cauTruyVanPhanTu, "HOAC ") . ") ";
                                $cauTruyVan .= isset($xuLyMangKhoa[1]) ? $xuLyMangKhoa[1] . " " : "";
                            } else {
                                return trigger_error("Lỗi cú pháp tại phương thức xử lý " . $tenPhuongThuc . " trong lớp " . get_class($this) . "  vui lòng kiểm tra lại bạn nhé !", E_USER_WARNING);
                            }
                        }
                        $mangThayDoiChuoi = array(" VA " => " AND ", " HOAC " => " OR ");
                        foreach ($mangThayDoiChuoi as $canDoi => $thanh) {
                            $cauTruyVan = str_replace($canDoi, $thanh, $cauTruyVan);
                        }
                        $mangThuocTinh = isset($thamTri[1]) ? $thamTri[1] : array();
                        return $this->{$v}($cauTruyVan, $mangThamTri, $mangThuocTinh);
                    }
                }
                return trigger_error("Lỗi cú pháp tại phương thức xử lý " . $tenPhuongThuc . " trong lớp " . get_class($this) . "  vui lòng kiểm tra lại bạn nhé !", E_USER_WARNING);
            }
        }
        return trigger_error("Phương thức " . $tenPhuongThuc . " không tồn tại trong lớp xử lý " . get_class($this) . " vui lòng kiểm tra lại bạn nhé !", E_USER_WARNING);
    }

    /**
     * Kí tự phân cách tên bảng và cột 1
     * 
     * CHỨC NĂNG: dùng để phân cách tên bảng và cột trong câu truy vấn SQL
     *
     * @var string
     */
    public $kiTuPhanCachTenBangCot1 = "`";

    /**
     * Kí tự phân cách tên bảng và cột 1
     * 
     * CHỨC NĂNG: dùng để phân cách tên bảng và cột trong câu truy vấn SQL
     *
     * @var string
     */
    public $kiTuPhanCachTenBangCot2 = "`";

    /**
     * Phương thức kết nối cơ sở dữ liệu
     * 
     * CHỨC NĂNG:
     * Kết nối cơ sở dữ liệu giúp khởi tạo giá trị cho thuộc tính coSoDuLieu cùng lớp
     * 
     * Khởi tạo giá trị cho thuộc tính tenBangCSDL nếu chưa có
     */
    public function ketNoiCSDL() {
        if (empty($this->coSoDuLieu)) {
            if (empty($this->tenBangCSDL)) {
                $this->tenBangCSDL = dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Điều Hướng'];
            }
            $this->coSoDuLieu = new coSoDuLieu;
            if (thietLapCha::$cauHinhCSDL['Loại CSDL'] === "sqlsrv") {
                $this->kiTuPhanCachTenBangCot1 = "[";
                $this->kiTuPhanCachTenBangCot2 = "]";
            }
        }
    }

    /**
     * Phương thức lấy dữ liệu
     * 
     * CHỨC NĂNG:
     * Tương đương câu lệnh select * trên bảng dữ liệu chỉ định
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieu()" thì giá trị trả về sẽ là một mảng dữ liệu chứa thông tin bảng chỉ định
     * 
     * MỞ RỘNG:
     * Phương thức có hỗ trợ mảng thuộc tính tham trị nhằm hổ trợ cho câu truy vấn lấy dữ liệu thêm phần đa dạng hơn
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và sắp xếp dữ liệu từ dưới lên thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieu(array("Sắp Xếp" => "Từ Dưới Lên"))" thì giá trị trả về sẽ là một mảng dữ liệu chứa thông tin bảng chỉ định
     * 
     * Các thuộc tính trong mảng thuộc tính:
     * 
     * + "Sắp Xếp" có 2 giá trị là "Từ Trên Xuống" hoặc "Từ Dưới Lên" mặc đình là "Từ Trên Xuống"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiLay và thucHienSauKhiLay ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * + "Kiểu Trả Về" có 2 giá trị là "Tập Hợp" hoặc "Phần Tử" đối với "Tập Hợp" phương thức sẽ trả về mảng theo dạng các phần từ được đánh dấu từ 0 -> n 
     * đối với dạng "Phần Tử" thì phương thức sẽ trả về duy nhất giá trị dòng đầu tiên lấy được trên bảng dữ liệu và dạng phần tử không đánh số (gới ý: dùng để nạp dữ liệu lên biểu mãu)
     * 
     * + "Giới Hạn"  là số giới hạn phần tử trả về có 2 dạng dữ liệu 1 là số nguyên ví dụ tôi để giới hạn là "5" thì trong mảng phần tử dữ liệu trả về chỉ lấy ra 5 phần từ đầu tiên.
     * Ví dụ tôi đề giới hạn là "5,10" thì trong mảng phần tử dữ liệu trả về chỉ lấy phần tử thứ 5 đến phần tử thứ 10 còn lại bỏ qua
     * 
     * + "Bộ Nhớ Đệm" là cơ chế tạo vùng nhớ đệm cho các lần truy vấn sau có 2 dạng dữ liệu đó là ĐÚNG, SAI hoặc số nguyên
     * Ví dụ khi tôi truy vấn lần đầu và tôi muốn tối ưu tốc độ cho các lần truy vấn sau thì tôi sẽ để giá trị cho thuộc tính này là ĐÚNG (TRUE) hoặc SAI (FALSE) nếu muốn vô hiệu hóa, ngoài ra bạn có thể chủ động thiết lập thời gian đáo hạn cho vùng nhớ đềm bằng cách đưa giá trị số nguyên vào.
     *
     * @param array $mangThuocTinh (mảng chứa các thuộc tính được liệt kê bên trên)
     * @return array (kiểu trả về dạng mảng)
     */
    public function layDuLieu($mangThuocTinh = array()) {
        $this->ketNoiCSDL();
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $cauTruyVan = "";
        $mangLuu = array();
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . json_encode($mangThuocTinh), "sha1");
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiLay($mangThuocTinh)) {
                return $mangLuu;
            }
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
            }
        }
        $cauTruyVan = "SELECT * FROM {$this->kiTuPhanCachTenBangCot1}{$this->tenBangCSDL}{$this->kiTuPhanCachTenBangCot2} " . $mangThuocTinh["sắp xếp"];
        $cauTruyVan .= (!is_null($mangThuocTinh["giới hạn"])) ? " LIMIT " . $mangThuocTinh["giới hạn"] : "";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan);
        $mangTraVe = $truyVan->fetchAll(PDO::FETCH_ASSOC);
        if (count($mangTraVe) > 0) {
            if ($mangThuocTinh["kiểu trả về"] === "tập hợp") {
                foreach ($mangTraVe as $k => $v) {
                    $mangLuu[$k] = array($this->tenBangCSDL => $v);
                }
            } else {
                $mangLuu[$this->tenBangCSDL] = $mangTraVe[0];
            }
        }
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiLay($mangLuu, $mangThuocTinh);
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $mangLuu, $mangThuocTinh["bộ nhớ đệm"]);
        }
        return $mangLuu;
    }

    /**
     * Phương thức đếm dữ liệu
     * 
     * CHỨC NĂNG:
     * Tương đương câu lệnh select count(*) trên bảng dữ liệu chỉ định
     * 
     * Ví dụ: tôi muốn lấy số dòng dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->demDuLieu()" thì giá trị trả về sẽ là một số nguyên tương đương số dòng tồn tại trên bảng.
     * 
     * MỞ RỘNG:
     * Phương thức có hỗ trợ mảng thuộc tính tham trị nhằm hổ trợ cho câu truy vấn lấy dữ liệu thêm phần đa dạng hơn
     * 
     * Ví dụ: tôi muốn đếm dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và giới hạn dữ liệu (limit) thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->demDuLieu(array("Giới Han" => 100))" thì giá trị trả về sẽ là một số nguyên tương đương số dòng tồn tại trên bảng.
     * 
     * Các thuộc tính trong mảng thuộc tính:
     * 
     * + "Giới Hạn" có giá trị là một số nguyên giới hạn số dòng khi đếm hoặc là 1 chuỗi số dạng "1,10" số đầu là vị trí đầu tiên số kế là vị trí kết thúc trên bảng dữ liệu 
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiDem và thucHienSauKhiDem ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     *      * 
     * + "Bộ Nhớ Đệm" là cơ chế tạo vùng nhớ đệm cho các lần truy vấn sau có 2 dạng dữ liệu đó là ĐÚNG, SAI hoặc số nguyên
     * Ví dụ khi tôi truy vấn lần đầu và tôi muốn tối ưu tốc độ cho các lần truy vấn sau thì tôi sẽ để giá trị cho thuộc tính này là ĐÚNG (TRUE) hoặc SAI (FALSE) nếu muốn vô hiệu hóa, ngoài ra bạn có thể chủ động thiết lập thời gian đáo hạn cho vùng nhớ đềm bằng cách đưa giá trị số nguyên vào.
     *
     * @param array $mangThuocTinh (mảng chứa các thuộc tính được liệt kê bên trên)
     * @return array (kiểu trả về dạng mảng)
     */
    public function demDuLieu($mangThuocTinh = array()) {
        $this->ketNoiCSDL();
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $cauTruyVan = "";
        $tongSoDong = 0;
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . " demdulieu " . json_encode($mangThuocTinh), "sha1");
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiDem($mangThuocTinh)) {
                return $tongSoDong;
            }
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
            }
        }
        $cauTruyVan = "SELECT COUNT(*) AS {$this->kiTuPhanCachTenBangCot1}sodong{$this->kiTuPhanCachTenBangCot2} FROM {$this->kiTuPhanCachTenBangCot1}{$this->tenBangCSDL}{$this->kiTuPhanCachTenBangCot2} ";
        $cauTruyVan .= (!is_null($mangThuocTinh["giới hạn"])) ? " LIMIT " . $mangThuocTinh["giới hạn"] : "";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan);
        $mangTraVe = $truyVan->fetchAll(PDO::FETCH_ASSOC);
        $tongSoDong = $mangTraVe[0]["sodong"];
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiDem($tongSoDong, $mangThuocTinh);
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $tongSoDong, $mangThuocTinh["bộ nhớ đệm"]);
        }
        return $tongSoDong;
    }

    /**
     * Phương thức lấy dữ liệu bởi
     * 
     * CHỨC NĂNG:
     * Hỗ trợ cho phương thức tự sinh (__call) phía trên
     * 
     * @param string $chuoiTimKiem (tham trị thứ 1 dạng chuỗi là điều kiện trong câu lệnh delete)
     * @param array $mangBien (tham trị thứ 2 dạng mảng là mảng chứa các phần tử là biến trong câu lệnh truy vấn PDO)
     * @param array $mangThuocTinh (tham trị thứ 3 là mảng thuộc tính truy vấn)
     * @return int (kiểu trả về dạng số nguyên là số dòng đã xóa được)
     */
    private function layDuLieuBoi($chuoiTimKiem, $mangBien, $mangThuocTinh = array()) {
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $cauTruyVan = "";
        $mangLuu = array();
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . $chuoiTimKiem . json_encode($mangBien) . json_encode($mangThuocTinh), "sha1");
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiLay($mangThuocTinh)) {
                return $mangLuu;
            }
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
            }
        }
        $cauTruyVan = "SELECT * FROM {$this->kiTuPhanCachTenBangCot1}{$this->tenBangCSDL}{$this->kiTuPhanCachTenBangCot2} WHERE $chuoiTimKiem " . $mangThuocTinh["sắp xếp"];
        $cauTruyVan .= (!is_null($mangThuocTinh["giới hạn"])) ? " LIMIT " . $mangThuocTinh["giới hạn"] : "";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan, $mangBien);
        $mangTraVe = $truyVan->fetchAll(PDO::FETCH_ASSOC);
        if (count($mangTraVe) > 0) {
            if ($mangThuocTinh["kiểu trả về"] === "tập hợp") {
                foreach ($mangTraVe as $k => $v) {
                    $mangLuu[$k] = array($this->tenBangCSDL => $v);
                }
            } else {
                $mangLuu[$this->tenBangCSDL] = $mangTraVe[0];
            }
        }
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiLay($mangLuu, $mangThuocTinh);
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $mangLuu, $mangThuocTinh["bộ nhớ đệm"]);
        }
        return $mangLuu;
    }

    /**
     * Phương thức đếm dữ liệu bởi
     * 
     * CHỨC NĂNG:
     * Hỗ trợ cho phương thức tự sinh (__call) phía trên
     * 
     * @param string $chuoiTimKiem (tham trị thứ 1 dạng chuỗi là điều kiện trong câu lệnh delete)
     * @param array $mangBien (tham trị thứ 2 dạng mảng là mảng chứa các phần tử là biến trong câu lệnh truy vấn PDO)
     * @param array $mangThuocTinh (tham trị thứ 3 là mảng thuộc tính truy vấn)
     * @return int (kiểu trả về dạng số nguyên là số dòng đã xóa được)
     */
    private function demDuLieuBoi($chuoiTimKiem, $mangBien, $mangThuocTinh = array()) {
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $cauTruyVan = "";
        $tongSoDong = 0;
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . " demdulieu " . $chuoiTimKiem . json_encode($mangBien) . json_encode($mangThuocTinh), "sha1");
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiDem($mangThuocTinh)) {
                return $tongSoDong;
            }
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
            }
        }
        $cauTruyVan = "SELECT COUNT(*) AS {$this->kiTuPhanCachTenBangCot1}sodong{$this->kiTuPhanCachTenBangCot2} FROM {$this->kiTuPhanCachTenBangCot1}{$this->tenBangCSDL}{$this->kiTuPhanCachTenBangCot2} WHERE $chuoiTimKiem ";
        $cauTruyVan .= (!is_null($mangThuocTinh["giới hạn"])) ? " LIMIT " . $mangThuocTinh["giới hạn"] : "";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan, $mangBien);
        $mangTraVe = $truyVan->fetchAll(PDO::FETCH_ASSOC);
        $tongSoDong = $mangTraVe[0]["sodong"];
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiDem($tongSoDong, $mangThuocTinh);
        }
        if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
            $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $tongSoDong, $mangThuocTinh["bộ nhớ đệm"]);
        }
        return $tongSoDong;
    }

    /**
     * Phương thức lấy dữ liệu có liên kết bảng khóa phụ
     * 
     * CHỨC NĂNG:
     * Là phương thức mở rộng của phương thức lấy dữ liệu tích hợp bảng liên kết vào bảng dữ liệu trả về
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và có dữ liệu các bảng liên kết là "abc", "def" thông qua khóa liên kết là "idAbc" "idDef" với thuộc tính khoaChinh thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieuCoLienKetBangKhoaPhu(array('abc' => array('idAbc', array(mang thuoc tinh)), (array('def' => array('idDef', array(mang thuoc tinh)))" thì giá trị trả về sẽ là một mãng dữ liệu chứa thông tin bảng chỉ định và bên trong mảng đó có 1 phần tử có khóa là tên bảng liên kết có giá trị là dữ liệu mảng của bảng liên kết
     * 
     * MỞ RỘNG:
     * Phương thức có hỗ trợ mãng thuộc tính tham trị nhằm hổ trợ cho câu truy vấn lấy dữ liệu thêm phần đa dạng hơn
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và sắp xếp dữ liệu từ dưới lên thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieu(array("Sắp Xếp" => "Từ Dưới Lên"))" thì giá trị trả về sẽ là một mãng dữ liệu chứa thông tin bảng chỉ định
     * 
     * Các thuộc tính trong mãng thuộc tính:
     * 
     * + "Sắp Xếp" có 2 giá trị là "Từ Trên Xuống" hoặc "Từ Dưới Lên" mặc đình là "Từ Trên Xuống"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiLay và thucHienSauKhiLay ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * + "Kiểu Trả Về" có 2 giá trị là "Tập Hợp" hoặc "Phần Tử" đối với "Tập Hợp" phương thức sẽ trả về mãng theo dạng các phần từ được đánh dấu từ 0 -> n 
     * đối với dạng "Phần Tử" thì phương thức sẽ trả về duy nhất giá trị dòng đầu tiên lấy được trên bảng dữ liệu và dạng phần tử không đánh số (gới ý: dùng để nạp dữ liệu lên biểu mãu)
     * 
     * + "Giới Hạn"  là số giới hạn phần tử trả về có 2 dạng dữ liệu 1 là số nguyên ví dụ tôi để giới hạn là "5" thì trong mãng phần tử dữ liệu trả về chỉ lấy ra 5 phần từ đầu tiên.
     * Ví dụ tôi đề giới hạn là "5,10" thì trong mãng phần tử dữ liệu trả về chỉ lấy phần tử thứ 5 đến phần tử thứ 10 còn lại bỏ qua
     * 
     * + "Bộ Nhớ Đệm" là cơ chế tạo vùng nhớ đệm cho các lần truy vấn sau có 2 dạng dữ liệu đó là ĐÚNG, SAI hoặc số nguyên
     * Ví dụ khi tôi truy vấn lần đầu và tôi muốn tối ưu tốc độ cho các lần truy vấn sau thì tôi sẽ để giá trị cho thuộc tính này là ĐÚNG (TRUE) hoặc SAI (FALSE) nếu muốn vô hiệu hóa, ngoài ra bạn có thể chủ động thiết lập thời gian đáo hạn cho vùng nhớ đềm bằng cách đưa giá trị số nguyên vào.
     * 
     * + "Bảng Liên Kết Phụ" là cơ chế lấy dữ liệu bảng liên kết nhiều nhiều (n-n) thông qua 1 bảng trung gian
     * Ví dụ tôi có bảng tài khoản liên kết (n-n) với bảng sản phẩm và tôi muốn lấy dữ liêu tích hợp liên kết thông qua bảng trung gian tôi sẽ truyền thuộc tính này như sau: "Bảng Liên Kết Phụ" => array("trunggian", "taikhoan", "sanpham", mảng thuộc tính)
     * 
     * @param array $mangCacBang (mảng chứa thông tin các bảng có các phần tử có khóa là tên bảng có giá trị là mảng thông tin bên trong mảng thông tin giá trị đầu tiên là tên cột khóa phụ, giá trị thứ 2 là mảng thuộc tính lấy dữ liệu cho từng bảng)
     * @param array $mangThuocTinh (mảng chứa các thuộc tính được liệt kê bên trên)
     * @return array (kiểu trả về dạng mãng)
     */
    public function layDuLieuCoLienKetBangKhoaPhu($mangCacBang, $mangThuocTinh = array()) {
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $mangLuu = array();
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . json_encode($mangCacBang) . json_encode($mangThuocTinh), "sha1");
        if (is_array($mangCacBang) && is_array($mangThuocTinh)) {
            if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
                if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                    return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
                }
            }
            $tenBangCSDL = $this->tenBangCSDL;
            $khoaChinh = $this->khoaChinh;
            unset($mangThuocTinh["kiểu trả về"]);
            $mangThuocTinh["bộ nhớ đệm"] = false;
            $mangLuu = $this->layDuLieu($mangThuocTinh);
            foreach ($mangLuu as $khoa => $giaTri) {
                foreach ($mangCacBang as $tenBangLienKet => $thongTinBangLienKet) {
                    if (is_array($thongTinBangLienKet)) {
                        $this->tenBangCSDL = $tenBangLienKet;
                        if (count($thongTinBangLienKet) > 0) {
                            $tenKhoaLienKet = (!is_array($thongTinBangLienKet[0])) ? $thongTinBangLienKet[0] : "";
                            $mangThuocTinhLienKet = (isset($thongTinBangLienKet[1]) && is_array($thongTinBangLienKet[1])) ? $thongTinBangLienKet[1] : array();
                            if (!isset($mangLuu[$khoa][$tenBangCSDL][$tenBangLienKet])) {
                                if ($this->coSoDuLieu->kiemTraBangTonTai($tenBangLienKet)) {
                                    if ($this->coSoDuLieu->kiemTraCotTonTai($tenBangLienKet, $tenKhoaLienKet)) {
                                        if (!isset($mangThuocTinhLienKet["Bảng Phụ Liên Kết"])) {
                                            $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaLienKet;
                                            $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                            $mangThuocTinhLienKet["bộ nhớ đệm"] = false;
                                            unset($mangThuocTinhLienKet["kiểu trả về"]);
                                            $duLieuLienKet = $this->{$phuongThucTruyVan}(array(array("=" => $giaTri[$tenBangCSDL][$khoaChinh])), $mangThuocTinhLienKet);
                                            $mangLuu[$khoa][$tenBangCSDL][$tenBangLienKet] = $duLieuLienKet;
                                        } elseif (is_array($mangThuocTinhLienKet["Bảng Phụ Liên Kết"])) {
                                            $mangBangPhuLienKet = $mangThuocTinhLienKet["Bảng Phụ Liên Kết"];
                                            $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                            if (count($mangBangPhuLienKet) >= 3) {
                                                $tenBangPhuLienKet = (!is_array($mangBangPhuLienKet[0])) ? $mangBangPhuLienKet[0] : "";
                                                $tenKhoaPhuLienKet1 = (!is_array($mangBangPhuLienKet[1])) ? $mangBangPhuLienKet[1] : "";
                                                $tenKhoaPhuLienKet2 = (!is_array($mangBangPhuLienKet[2])) ? $mangBangPhuLienKet[2] : "";
                                                $mangThuocTinhPhuLienKet = (isset($mangBangPhuLienKet[3]) && is_array($mangBangPhuLienKet[3])) ? $mangBangPhuLienKet[3] : array();
                                                if ($this->coSoDuLieu->kiemTraBangTonTai($tenBangPhuLienKet)) {
                                                    if ($this->coSoDuLieu->kiemTraCotTonTai($tenBangPhuLienKet, $tenKhoaPhuLienKet1)) {
                                                        $mangThuocTinhPhuLienKet = $this->xuLyMangThuocTinh($mangThuocTinhPhuLienKet);
                                                        $mangThuocTinhPhuLienKet["bộ nhớ đệm"] = false;
                                                        unset($mangThuocTinhPhuLienKet["kiểu trả về"]);
                                                        $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaPhuLienKet1;
                                                        $this->tenBangCSDL = $tenBangPhuLienKet;
                                                        $duLieuLienKetPhu = $this->{$phuongThucTruyVan}(array(array("=" => $giaTri[$tenBangCSDL][$khoaChinh])), $mangThuocTinhPhuLienKet);
                                                        $this->tenBangCSDL = $tenBangLienKet;
                                                        $duLieuLienKet = array();
                                                        foreach ($duLieuLienKetPhu as $giaTriLienKetPhu) {
                                                            if (isset($giaTriLienKetPhu[$tenBangPhuLienKet][$tenKhoaPhuLienKet2])) {
                                                                $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaLienKet;
                                                                $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                                                $mangThuocTinhLienKet["bộ nhớ đệm"] = false;
                                                                $mangThuocTinhLienKet["kiểu trả về"] = "Phần Tử";
                                                                $duLieuLienKet[] = $this->{$phuongThucTruyVan}(array(array("=" => $giaTriLienKetPhu[$tenBangPhuLienKet][$tenKhoaPhuLienKet2])), $mangThuocTinhLienKet);
                                                            }
                                                        }
                                                        $mangLuu[$khoa][$tenBangCSDL][$tenBangLienKet] = $duLieuLienKet;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
                $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $mangLuu, $mangThuocTinh["bộ nhớ đệm"]);
            }
            $this->tenBangCSDL = $tenBangCSDL;
        }
        return $mangLuu;
    }

    /**
     * Phương thức lấy dữ liệu có liên kết bảng khóa phụ chỉ định
     * 
     * CHỨC NĂNG:
     * Là phương thức thu hẹp phạm vi của phương thức lấy dữ liệu có liên kết bảng khóa phụ
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và giá trị khóa chính truyền vào là 5, có dữ liệu các bảng liên kết là "abc", "def" thông qua khóa liên kết là "idAbc" "idDef" với thuộc tính khoaChinh thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieuCoLienKetBangKhoaPhuChiDinh(5, array('abc' => array('idAbc', array(mang thuoc tinh)), (array('def' => array('idDef', array(mang thuoc tinh)))" thì giá trị trả về sẽ là một mãng dữ liệu chứa thông tin bảng chỉ định và bên trong mảng đó có 1 phần tử có khóa là tên bảng liên kết có giá trị là dữ liệu mảng của bảng liên kết
     * 
     * MỞ RỘNG:
     * Phương thức có hỗ trợ mãng thuộc tính tham trị nhằm hổ trợ cho câu truy vấn lấy dữ liệu thêm phần đa dạng hơn
     * 
     * Ví dụ: tôi muốn lấy dữ liệu từ bảng chỉ định thông qua thuộc tính tenBangCSDL và sắp xếp dữ liệu từ dưới lên thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->layDuLieu(array("Sắp Xếp" => "Từ Dưới Lên"))" thì giá trị trả về sẽ là một mãng dữ liệu chứa thông tin bảng chỉ định
     * 
     * Các thuộc tính trong mãng thuộc tính:
     * 
     * + "Sắp Xếp" có 2 giá trị là "Từ Trên Xuống" hoặc "Từ Dưới Lên" mặc đình là "Từ Trên Xuống"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiLay và thucHienSauKhiLay ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * + "Kiểu Trả Về" có 2 giá trị là "Tập Hợp" hoặc "Phần Tử" đối với "Tập Hợp" phương thức sẽ trả về mãng theo dạng các phần từ được đánh dấu từ 0 -> n 
     * đối với dạng "Phần Tử" thì phương thức sẽ trả về duy nhất giá trị dòng đầu tiên lấy được trên bảng dữ liệu và dạng phần tử không đánh số (gới ý: dùng để nạp dữ liệu lên biểu mãu)
     * 
     * + "Giới Hạn"  là số giới hạn phần tử trả về có 2 dạng dữ liệu 1 là số nguyên ví dụ tôi để giới hạn là "5" thì trong mãng phần tử dữ liệu trả về chỉ lấy ra 5 phần từ đầu tiên.
     * Ví dụ tôi đề giới hạn là "5,10" thì trong mãng phần tử dữ liệu trả về chỉ lấy phần tử thứ 5 đến phần tử thứ 10 còn lại bỏ qua
     * 
     * + "Bộ Nhớ Đệm" là cơ chế tạo vùng nhớ đệm cho các lần truy vấn sau có 2 dạng dữ liệu đó là ĐÚNG, SAI hoặc số nguyên
     * Ví dụ khi tôi truy vấn lần đầu và tôi muốn tối ưu tốc độ cho các lần truy vấn sau thì tôi sẽ để giá trị cho thuộc tính này là ĐÚNG (TRUE) hoặc SAI (FALSE) nếu muốn vô hiệu hóa, ngoài ra bạn có thể chủ động thiết lập thời gian đáo hạn cho vùng nhớ đềm bằng cách đưa giá trị số nguyên vào.
     * 
     * + "Bảng Liên Kết Phụ" là cơ chế lấy dữ liệu bảng liên kết nhiều nhiều (n-n) thông qua 1 bảng trung gian
     * Ví dụ tôi có bảng tài khoản liên kết (n-n) với bảng sản phẩm và tôi muốn lấy dữ liêu tích hợp liên kết thông qua bảng trung gian tôi sẽ truyền thuộc tính này như sau: "Bảng Liên Kết Phụ" => array("trunggian", "taikhoan", "sanpham", mảng thuộc tính)
     *  
     * @param mixed $giaTriKhoaChinh (giá trị của khóa chính chỉ định vị trí để lấy thông tin trên bảng khóa chính và cũng là giá trị xác định thông tin dữ liệu ở các bảng liên kết)
     * @param array $mangCacBang (mảng chứa thông tin các bảng có các phần tử có khóa là tên bảng có giá trị là mảng thông tin bên trong mảng thông tin giá trị đầu tiên là tên cột khóa phụ, giá trị thứ 2 là mảng thuộc tính lấy dữ liệu cho từng bảng)
     * @param array $mangThuocTinh (mảng chứa các thuộc tính được liệt kê bên trên)
     * @return array (kiểu trả về dạng mãng)
     */
    public function layDuLieuCoLienKetBangKhoaPhuChiDinh($giaTriKhoaChinh, $mangCacBang, $mangThuocTinh = array()) {
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        $mangLuu = array();
        $tenVungNhoDem = maHoa::maHoaChuoi($this->tenBangCSDL . $giaTriKhoaChinh . json_encode($mangCacBang) . json_encode($mangThuocTinh), "sha1");
        if (is_array($mangCacBang) && !is_array($giaTriKhoaChinh) && is_array($mangThuocTinh)) {
            if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
                if ($this->boNhoDem->kiemTraVungNhoTonTai($tenVungNhoDem)) {
                    return $this->boNhoDem->giaTriVungNho($tenVungNhoDem);
                }
            }
            $tenBangCSDL = $this->tenBangCSDL;
            $khoaChinh = $this->khoaChinh;
            $phuongThucTruyVan = "layDuLieuBoi" . $khoaChinh;
            $mangThuocTinh["kiểu trả về"] = "Phần Tử";
            $mangThuocTinh["bộ nhớ đệm"] = false;
            $mangLuu = $this->{$phuongThucTruyVan}(array(array("=" => $giaTriKhoaChinh)), $mangThuocTinh);
            if (!empty($mangLuu)) {
                foreach ($mangCacBang as $tenBangLienKet => $thongTinBangLienKet) {
                    if (is_array($thongTinBangLienKet)) {
                        $this->tenBangCSDL = $tenBangLienKet;
                        if (count($thongTinBangLienKet) > 0) {
                            $tenKhoaLienKet = (!is_array($thongTinBangLienKet[0])) ? $thongTinBangLienKet[0] : "";
                            $mangThuocTinhLienKet = (isset($thongTinBangLienKet[1]) && is_array($thongTinBangLienKet[1])) ? $thongTinBangLienKet[1] : array();
                            if (!isset($mangLuu[$tenBangCSDL][$tenBangLienKet])) {
                                if ($this->coSoDuLieu->kiemTraBangTonTai($tenBangLienKet)) {
                                    if ($this->coSoDuLieu->kiemTraCotTonTai($tenBangLienKet, $tenKhoaLienKet)) {
                                        if (!isset($mangThuocTinhLienKet["Bảng Phụ Liên Kết"])) {
                                            $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaLienKet;
                                            $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                            $mangThuocTinhLienKet["bộ nhớ đệm"] = false;
                                            unset($mangThuocTinhLienKet["kiểu trả về"]);
                                            $duLieuLienKet = $this->{$phuongThucTruyVan}(array(array("=" => $giaTriKhoaChinh)), $mangThuocTinhLienKet);
                                            $mangLuu[$tenBangCSDL][$tenBangLienKet] = $duLieuLienKet;
                                        } elseif (is_array($mangThuocTinhLienKet["Bảng Phụ Liên Kết"])) {
                                            $mangBangPhuLienKet = $mangThuocTinhLienKet["Bảng Phụ Liên Kết"];
                                            $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                            if (count($mangBangPhuLienKet) >= 3) {
                                                $tenBangPhuLienKet = (!is_array($mangBangPhuLienKet[0])) ? $mangBangPhuLienKet[0] : "";
                                                $tenKhoaPhuLienKet1 = (!is_array($mangBangPhuLienKet[1])) ? $mangBangPhuLienKet[1] : "";
                                                $tenKhoaPhuLienKet2 = (!is_array($mangBangPhuLienKet[2])) ? $mangBangPhuLienKet[2] : "";
                                                $mangThuocTinhPhuLienKet = (isset($mangBangPhuLienKet[3]) && is_array($mangBangPhuLienKet[3])) ? $mangBangPhuLienKet[3] : array();
                                                if ($this->coSoDuLieu->kiemTraBangTonTai($tenBangPhuLienKet)) {
                                                    if ($this->coSoDuLieu->kiemTraCotTonTai($tenBangPhuLienKet, $tenKhoaPhuLienKet1)) {
                                                        $mangThuocTinhPhuLienKet = $this->xuLyMangThuocTinh($mangThuocTinhPhuLienKet);
                                                        $mangThuocTinhPhuLienKet["bộ nhớ đệm"] = false;
                                                        unset($mangThuocTinhPhuLienKet["kiểu trả về"]);
                                                        $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaPhuLienKet1;
                                                        $this->tenBangCSDL = $tenBangPhuLienKet;
                                                        $duLieuLienKetPhu = $this->{$phuongThucTruyVan}(array(array("=" => $giaTriKhoaChinh)), $mangThuocTinhPhuLienKet);
                                                        $this->tenBangCSDL = $tenBangLienKet;
                                                        $duLieuLienKet = array();
                                                        foreach ($duLieuLienKetPhu as $giaTriLienKetPhu) {
                                                            if (isset($giaTriLienKetPhu[$tenBangPhuLienKet][$tenKhoaPhuLienKet2])) {
                                                                $phuongThucTruyVan = "layDuLieuBoi" . $tenKhoaLienKet;
                                                                $mangThuocTinhLienKet = $this->xuLyMangThuocTinh($mangThuocTinhLienKet);
                                                                $mangThuocTinhLienKet["bộ nhớ đệm"] = false;
                                                                $mangThuocTinhLienKet["kiểu trả về"] = "Phần Tử";
                                                                $duLieuLienKet[] = $this->{$phuongThucTruyVan}(array(array("=" => $giaTriLienKetPhu[$tenBangPhuLienKet][$tenKhoaPhuLienKet2])), $mangThuocTinhLienKet);
                                                            }
                                                        }
                                                        $mangLuu[$tenBangCSDL][$tenBangLienKet] = $duLieuLienKet;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($mangThuocTinh["bộ nhớ đệm"] !== false) {
                $this->boNhoDem->thietLapVungNho($tenVungNhoDem, $mangLuu, $mangThuocTinh["bộ nhớ đệm"]);
            }
            $this->tenBangCSDL = $tenBangCSDL;
        }
        return $mangLuu;
    }

    /**
     * Phương thức xóa dữ liệu
     * 
     * CHỨC NĂNG:
     * Dùng để xóa dữ liệu trên bảng CSDL (database) đã khai báo thông qua thuộc tính tenBangCSDL cùng lớp. Và dựa trên khóa chính để xóa dữ liệu đó
     * 
     * Ví dụ: tại tầng điều hướng (controller) tôi muốn xóa dữ liệu mà có khóa chính là id mang giá trị ngẫu nhiên thì tôi gọi như sau "$this->xuLy->xoaDuLieu(array("tên bảng CSDL" => array("id" => "giá trị"))"
     * 
     * MỞ RỘNG:
     * Phương thức này còn hổ trợ bạn thuộc tính "gọi ngược"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiXoa và thucHienSauKhiXoa ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * Ví dụ: tôi muốn xóa dữ liệu mà tắt tính năng gọi ngược thì tại tầng điều hướng (controller) tôi gọi như sau "$this->xuLy->xoaDuLieu(mảng dữ liệu, array("Gọi Ngược" => false))"
     * 
     * @param array $duLieu (tham trị thứ 1 có dạng mạng có chưa thông tin bảng CSDL mà trong đó lại chứa thông tin giá trị khóa chính cần xóa)
     * @param array $mangThuocTinh (tham trị thứ 2 là mảng thuộc tính)
     * @return int (trả về dạng số nguyên là số dòng xóa được trên bảng)
     */
    public function xoaDuLieu($duLieu, $mangThuocTinh = array()) {
        $this->ketNoiCSDL();
        $soDongDaXoa = 0;
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        if (is_array($duLieu)) {
            if ($mangThuocTinh["gọi ngược"] === true) {
                if (!$this->thucHienTruocKhiXoa($mangThuocTinh)) {
                    return $soDongDaXoa;
                }
            }
            if (isset($duLieu[$this->tenBangCSDL])) {
                $thongTinDuLieuGuiVe = $duLieu[$this->tenBangCSDL];
                if (isset($thongTinDuLieuGuiVe[$this->khoaChinh])) {
                    $cauTruyVan = "DELETE FROM {$this->kiTuPhanCachTenBangCot1}" . $this->tenBangCSDL . "{$this->kiTuPhanCachTenBangCot2} WHERE {$this->kiTuPhanCachTenBangCot1}" . $this->khoaChinh . "{$this->kiTuPhanCachTenBangCot2} = :" . $this->khoaChinh;
                    $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan, array($this->khoaChinh => $thongTinDuLieuGuiVe[$this->khoaChinh]));
                    $soDongDaXoa += $truyVan->rowCount();
                }
            }
        }
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiXoa($soDongDaXoa, $mangThuocTinh);
        }
        return $soDongDaXoa;
    }

    /**
     * Phương thức xóa toàn bộ dữ liệu
     * 
     * CHỨC NĂNG:
     * Dùng để xóa toàn bộ dữ liệu trên bảng CSDL (database) đã khai báo thông qua thuộc tính tenBangCSDL cùng lớp.
     * 
     * Ví dụ: tại tầng điều hướng (controller) tôi muốn xóa toàn bộ dữ liệu trên bảng thì tôi gọi như sau "$this->xuLy->xoaToanBoDuLieu()"
     * 
     * MỞ RỘNG:
     * Phương thức này còn hổ trợ bạn thuộc tính "gọi ngược"
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiXoa và thucHienSauKhiXoa ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * Ví dụ: tôi muốn xóa toàn bộ dữ liệu mà tắt tính năng gọi ngược thì tại tầng điều hướng (controller) tôi gọi như sau "$this->xuLy->xoaToanBoDuLieu(array("Gọi Ngược" => false))"
     * 
     * @return int (trả về dạng số nguyên là số dòng xóa được trên bảng)
     */
    public function xoaToanBoDuLieu() {
        $this->ketNoiCSDL();
        $soDongDaXoa = 0;
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiXoa($mangThuocTinh)) {
                return 0;
            }
        }
        $cauTruyVan = "DELETE FROM {$this->kiTuPhanCachTenBangCot1}" . $this->tenBangCSDL . "{$this->kiTuPhanCachTenBangCot2}";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan);
        $soDongDaXoa += $truyVan->rowCount();
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiXoa($soDongDaXoa, $mangThuocTinh);
        }
        return $soDongDaXoa;
    }

    /**
     * Phương thức truy vấn SQL
     * 
     * CHỨC NĂNG:
     * Hỗ trợ cho các truy vấn phức tạp mà các phương thức trong framework không thể đáp ứng nhà lập trình phải sử dụng câu lệnh SQL để xử lý
     * 
     * @param string $cauTruyVan (tham trị thứ 1 dạng chuỗi là câu lệnh SQL PDO ví dụ: select * from taikhoan where cTaiKhoan=:cTaiKhoan)
     * @param array $mangThamTri (tham trị thứ 2 là mảng chứa các tham trị tham gia trong câu truy vấn mặc định là rổng ví dụ array("cTaiKhoan" => 1))
     * @return obj (kiểu trả về dạng đối tượng PDO sau khi thực hiện phương thức prepare và execute)
     */
    public function SQL($cauTruyVan = null, $mangThamTri = array()) {
        $this->ketNoiCSDL();
        if (!is_null($cauTruyVan)) {
            return $this->coSoDuLieu->truyVan($cauTruyVan, $mangThamTri);
        }
        return false;
    }

    /**
     * Phương thức xóa dữ liệu bởi
     * 
     * CHỨC NĂNG:
     * Hỗ trợ cho phương thức tự sinh (__call) phía trên
     * 
     * @param string $chuoiTimKiem (tham trị thứ 1 dạng chuỗi là điều kiện trong câu lệnh delete)
     * @param array $mangBien (tham trị thứ 2 dạng mảng là mảng chứa các phần tử là biến trong câu lệnh truy vấn PDO)
     * @param array $mangThuocTinh (tham trị thứ 3 là mảng thuộc tính truy vấn)
     * @return int (kiểu trả về dạng số nguyên là số dòng đã xóa được)
     */
    private function xoaDuLieuBoi($chuoiTimKiem, $mangBien, $mangThuocTinh = array()) {
        $soDongDaXoa = 0;
        $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
        if ($mangThuocTinh["gọi ngược"] === true) {
            if (!$this->thucHienTruocKhiXoa($mangThuocTinh)) {
                return $soDongDaXoa;
            }
        }
        $cauTruyVan = "DELETE FROM {$this->kiTuPhanCachTenBangCot1}{$this->tenBangCSDL}{$this->kiTuPhanCachTenBangCot2} WHERE $chuoiTimKiem";
        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan, $mangBien);
        $soDongDaXoa += $truyVan->rowCount();
        if ($mangThuocTinh["gọi ngược"] === true) {
            $this->thucHienSauKhiXoa($soDongDaXoa, $mangThuocTinh);
        }
        return $soDongDaXoa;
    }

    /**
     * Mảng thuộc tính dữ liệu lưu
     *
     * CHỨC NĂNG:
     * Là mảng lưu dữ liệu gửi về phương thức luuDuLieu. Mục đích dùng để hổ trợ việc tương tác với dữ liệu lưu thông qua phương thức gọi ngược thucHienTruocKhiLuu
     * 
     * @var array (kiểu dữ liệu dạng chuỗi)
     */
    public $duLieuLuu = array();

    /**
     * Phương thức lưu dữ liệu
     * 
     * CHỨC NĂNG:
     * Dùng để nạp dữ liệu hoặc cập nhật dữ liệu trên bảng CSDL chỉ định thông qua thuộc tính tenBangCSDL.
     * 
     * Phương thức sẽ thực hiện tính năng cập nhật (update) nếu trong mảng dữ liệu gửi về có tồn tại phần tử khóa chính và giá trị của nó tồn tại trên bảng CSDL ngược lại thì phương thức sẽ thực hiện tính năng nạp (insert)
     * 
     * Ví dụ: trên bảng CSDL "sanpham" của tôi có tồn tại khóa chính là id mang giá trị 1 thì sau khi tôi truyền mảng tương ứng vào phương thức sẽ thực hiện tính năng cập nhật (update) vì trên bảng đã tồn tại khóa chính id là 1. Ngược lại thì sẽ thực hiện nạp (insert) vì trên bảng chưa tồn tại.
     * 
     * Cách gọi phương thức này ở tầng điều hướng (controller) như sau "$this->xuLy->luuDuLieu(array("tên bảng csdl" => array("tên trường trên bảng csdl" => "giá trị")))"
     * 
     * MỞ RỘNG:
     * Phương thức có hỗ trợ mảng thuộc tính tham trị nhằm hổ trợ cho câu truy vấn lấy dữ liệu thêm phần đa dạng hơn
     * 
     * Ví dụ: tôi muốn nạp dữ liệu vào bảng chỉ định thông qua thuộc tính tenBangCSDL và tắt tính năng kiểm tra lỗi dữ liệu (validate) thì ở tầng điều hướng tôi gọi như sau "$this->xuLy->luuDuLieu(array("Kiểm Tra Lỗi" => false))" thì tính năng này sẽ bị tắt
     * 
     * Các thuộc tính trong mảng thuộc tính:
     * 
     * + "Kiểm Tra Lỗi" có 2 giá trị là đúng (TRUE) để kiểm tra các lỗi dữ liệu lưu được nạp hoặc cập nhật hoặc sai để tắt tính năng này. Gía trị mặc định là đúng (TRUE)
     * 
     * + "Gọi Ngược" có giá trị là đúng hoặc sai nếu đúng thì cho phép thực hiện 2 phương thức thucHienTruocKhiLuu và thucHienSauKhiLuu ngược lại thì không. Gía trị mặc định là đúng (TRUE)
     * 
     * @param array $duLieu (tham trị thứ 1 mang kiểu dữ liệu mảng là mảng dữ liệu muốn lưu hoặc cập nhật)
     * @param array $mangThuocTinh (tham trị thứ 2 là mảng thuộc tính)
     * @return boolean (kiểu trả về đúng (TRUE) hoặc là ID thêm vào bảng cuối cùng (khóa tự động) khi lưu dữ liệu thành công sai (FALSE) khi lưu thất bại)
     */
    public function luuDuLieu($duLieu = array(), $mangThuocTinh = array()) {
        if (is_array($duLieu)) {
            $this->ketNoiCSDL();
            $this->duLieuLuu = $duLieu;
            if (isset($duLieu[$this->tenBangCSDL])) {
                $mangThuocTinh = $this->xuLyMangThuocTinh($mangThuocTinh);
                $kiemTraLoi = $this->kiemTraLoi($duLieu[$this->tenBangCSDL], "", $mangThuocTinh);
                if ($kiemTraLoi) {
                    if ($mangThuocTinh["gọi ngược"] === true) {
                        if (!$this->thucHienTruocKhiLuu($mangThuocTinh)) {
                            return false;
                        }
                    }
                    $duLieu = $this->duLieuLuu;
                    $khoaChinh = $this->khoaChinh;
                    $cacCotDuLieuGuiVe = $this->layCacCotDuLieuBieuMauGuiVe($duLieu);
                    $cauTruyVan = "";
                    if (count($cacCotDuLieuGuiVe) > 0) {
                        if (isset($cacCotDuLieuGuiVe[$khoaChinh])) {
                            $giaTriKhoaChinh = ($cacCotDuLieuGuiVe[$khoaChinh] != "") ? $cacCotDuLieuGuiVe[$khoaChinh] : NULL;
                            $tenPhuongThucLayDuLieu = "layDuLieuBoi" . $khoaChinh;
                            if (!empty($giaTriKhoaChinh) && (count($this->{$tenPhuongThucLayDuLieu}(array(array("=" => $giaTriKhoaChinh)))) > 0)) {
                                $chuoiCapNhat = "";
                                foreach (array_keys($cacCotDuLieuGuiVe) as $v) {
                                    if ($v !== $khoaChinh) {
                                        $chuoiCapNhat .= "{$this->kiTuPhanCachTenBangCot1}$v{$this->kiTuPhanCachTenBangCot2} = :" . $v . ",";
                                    }
                                }
                                $chuoiCapNhat = rtrim($chuoiCapNhat, ",");
                                $cauTruyVan = "UPDATE {$this->kiTuPhanCachTenBangCot1}" . $this->tenBangCSDL . "{$this->kiTuPhanCachTenBangCot2} SET $chuoiCapNhat WHERE {$this->kiTuPhanCachTenBangCot1}" . $khoaChinh . "{$this->kiTuPhanCachTenBangCot2} = :$khoaChinh";
                            } else {
                                $xuLyTenCacCot = implode("{$this->kiTuPhanCachTenBangCot2}, {$this->kiTuPhanCachTenBangCot1}", array_keys($cacCotDuLieuGuiVe));
                                $xuLyGiaTriCacCot = implode(", :", array_keys($cacCotDuLieuGuiVe));
                                $cauTruyVan = "INSERT INTO {$this->kiTuPhanCachTenBangCot1}" . $this->tenBangCSDL . "{$this->kiTuPhanCachTenBangCot2} ({$this->kiTuPhanCachTenBangCot1}$xuLyTenCacCot{$this->kiTuPhanCachTenBangCot2}) VALUES(:$xuLyGiaTriCacCot)";
                            }
                        } else {
                            $xuLyTenCacCot = implode("{$this->kiTuPhanCachTenBangCot2}, {$this->kiTuPhanCachTenBangCot1}", array_keys($cacCotDuLieuGuiVe));
                            $xuLyGiaTriCacCot = implode(", :", array_keys($cacCotDuLieuGuiVe));
                            $cauTruyVan = "INSERT INTO {$this->kiTuPhanCachTenBangCot1}" . $this->tenBangCSDL . "{$this->kiTuPhanCachTenBangCot2} ({$this->kiTuPhanCachTenBangCot1}$xuLyTenCacCot{$this->kiTuPhanCachTenBangCot2}) VALUES(:$xuLyGiaTriCacCot)";
                        }
                        $truyVan = $this->coSoDuLieu->truyVan($cauTruyVan, $cacCotDuLieuGuiVe);
                        $idLuuCuoiCung = $this->coSoDuLieu->lastInsertId();
                        if ($mangThuocTinh["gọi ngược"] === true) {
                            $this->thucHienSauKhiLuu($idLuuCuoiCung, $mangThuocTinh);
                        }
                        return ($idLuuCuoiCung > 0) ? $idLuuCuoiCung : true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Phương thức thực hiện kiểm tra lỗi dữ liệu đầu vào
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi thực hiện kiểm tra lỗi dữ liệu đầu vào dựa trên thuộc tính $kiemTraLoi quy định. Mục đích dùng để kiểm tra tính hợp lệ của dữ liệu.
     * 
     * @param array $duLieu mảng chứa các phần tử có khóa tương đương với khóa cần kiểm tra trên mảng $kiemTraLoi có giá trị là giá trị thực thi kiểm tra
     * @param string $tenBangCSL tên bảng CSDL cần kiểm tra ở đây cũng là khóa trên mảng $kiemTraLoi
     * @param array $mangThuocTinh chứa các thuộc tính thực thi khi kiểm tra lỗi chi tiết xem hướng dẫn tại trang chủ
     * @return boolean (giá trị trả về đúng (TRUE) khi dữ liệu hợp lệ hoặc sai (FALSE) ngược lại)
     */
    public function kiemTraLoi($duLieu, $tenBangCSDL = "", $mangThuocTinh = array()) {
        $tenBangCSDLHienTai = $this->tenBangCSDL;
        if (is_string($tenBangCSDL) && !empty($tenBangCSDL)) {
            $this->tenBangCSDL = $tenBangCSDL;
        }
        $kiemTraLoi = new kiemTraLoi($this, $duLieu, $this->xuLyMangThuocTinh($mangThuocTinh));
        $ketQua = $kiemTraLoi->kiemTraCacLoiChiDinh();
        $this->tenBangCSDL = $tenBangCSDLHienTai;
        return $ketQua;
    }

    /**
     * Phương thức thực hiện trước khi lưu dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi thực hiện trước khi thực hiển các hành động tại phương thức lưu dữ liệu. Mục đích dùng để thao tác và kiểm tra dữ liệu trước khi lưu.
     * 
     * Chú ý: phương thức phải trả về kiểu đúng (TRUE) để có thể thực hiện tiếp phương thức lưu dữ liệu nếu trả về sai (FALSE) thì phương thức lưu dữ liệu sẽ bị hủy bỏ
     * 
     * @param array $mangThuocTinh (mảng thuộc tính chứa các thuộc tính gửi về phương thức lưu dữ liệu)
     * @return boolean (giá trị trả về đúng (TRUE) hoặc sai (FALSE))
     */
    public function thucHienTruocKhiLuu($mangThuocTinh = array()) {
        return true;
    }

    /**
     * Phương thức thực hiện sau khi lưu dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi sau khi lưu dữ liệu vào bảng CSDL thành công. Mục đích dùng để kiểm tra và xử lý các chức năng khác tùy ở bạn
     * 
     * @param int $soDongDaLuu (tham trị đầu tiên kiểu số nguyên truyền vào là số dòng đã lưu thành công)
     * @param array $mangThuocTinh (tham trị thứ hai kiểu mảng là mảng thuộc tính gửi về từ phương thức lưu dữ liệu)
     */
    public function thucHienSauKhiLuu($soDongDaLuu = 0, $mangThuocTinh = array()) {
        
    }

    /**
     * Phương thức thực hiện trước khi đếm số hàng dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi thực hiện trước khi thực hiện các hành động tại phương thức đếm dữ liệu. Mục đích dùng để thao tác và kiểm tra dữ liệu trước khi đếm.
     * 
     * Chú ý: phương thức phải trả về kiểu đúng (TRUE) để có thể thực hiện tiếp phương thức đếm dữ liệu nếu trả về sai (FALSE) thì phương thức lưu dữ liệu sẽ bị hủy bỏ
     * 
     * @param array $mangThuocTinh (mảng thuộc tính chứa các thuộc tính gửi về phương thức lưu dữ liệu)
     * @return boolean (giá trị trả về đúng (TRUE) hoặc sai (FALSE))
     */
    public function thucHienTruocKhiDem($mangThuocTinh = array()) {
        return true;
    }

    /**
     * Phương thức thực hiện sau khi đếm dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi sau khi đếm dữ liệu. Mục đích dùng để kiểm tra và xử lý các chức năng khác tùy ở bạn
     * 
     * @param int $tongSoDong (tham trị đầu tiên kiểu số nguyên truyền vào là số dòng đếm được)
     * @param array $mangThuocTinh (tham trị thứ hai kiểu mảng là mảng thuộc tính gửi về từ phương thức lưu dữ liệu)
     */
    public function thucHienSauKhiDem($tongSoDong, $mangThuocTinh = array()) {
        
    }

    /**
     * Phương thức thực hiện trước khi xóa dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi trước khi thực hiện các hành động trong phương thức xoaDuLieu và xoaDuLieuBoi... . Mục đích dùng để kiểm tra và xử lý các chức năng kiểm tra và thao tác v..v
     * 
     * Chú ý: phương thức phải trả về kiểu đúng (TRUE) để có thể thực hiện tiếp phương thức xóa dữ liệu nếu trả về sai (FALSE) thì phương thức xóa dữ liệu sẽ bị hủy bỏ
     * 
     * @param array $mangThuocTinh (mảng thuộc tính gửi về từ phương thức xóa dữ liệu hoặc xóa dữ liệu bởi)
     * @return boolean (giá trị trả về đúng (TRUE) hoặc sai (FALSE))
     */
    public function thucHienTruocKhiXoa($mangThuocTinh = array()) {
        return true;
    }

    /**
     * Phương thức thực hiện sau khi xóa dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức được gọi sau khi xóa thành công dữ liệu từ phương thức xoaDuLieu hoặc xoaDuLieuBoi. Mục đích dùng để kiểm tra và thực hiện tiếp các hành động khác
     * 
     * @param int $soDongDaXoa (tham trị thứ 1 truyền về là số dòng đã xóa thành công)
     * @param array $mangThuocTinh (tham trị thứ 2 truyền về là mảng thuộc tính được truyền vào ở phương thức xoaDuLieu hoặc xoaDuLieuBoi)
     */
    public function thucHienSauKhiXoa($soDongDaXoa = 0, $mangThuocTinh = array()) {
        
    }

    /**
     * Phương thức thực hiện trước khi lấy dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức này được gọi trước khi thực hiện các hành động trong phương thức layDuLieu hoặc layDuLieuBoi. Mục đích để kiểm tra hoặc thực hiện các hành động khác
     * 
     * Chú ý: phương thức phải trả về kiểu đúng (TRUE) để có thể thực hiện tiếp phương thức lấy dữ liệu nếu trả về sai (FALSE) thì phương thức lấy dữ liệu sẽ bị hủy bỏ
     * 
     * @param array $mangThuocTinh (tham trị truyền về là mảng thuộc tính được truyền vào ở phương thức layDuLieu hoặc layDuLieuBoi)
     * @return boolean (giá trị trả về đúng (TRUE) hoặc sai (FALSE))
     */
    public function thucHienTruocKhiLay($mangThuocTinh = array()) {
        return true;
    }

    /**
     * Phương thức thực hiện sau khi lấy dữ liệu
     * 
     * CHỨC NĂNG:
     * Phương thức này được gọi sau khi lấy xong dữ liệu từ bảng cơ sở dữ liệu. Múc đích để kiểm tra và thực hiện tiếp các hành động khác
     * 
     * @param array $mangKetQua (tham trị thứ 1 truyền về là mảng dữ liệu lấy được)
     * @param type $mangThuocTinh (tham trị thứ 2 truyền về là mảng thuộc tính được truyền vào ở phương thức layDuLieu hoặc layDuLieuBoi)
     */
    public function thucHienSauKhiLay($mangKetQua = array(), $mangThuocTinh = array()) {
        
    }

    /**
     * Phương thức thực hiện trước khi kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Phương thức này được gọi trước khi thực hiện các hành động kiểm tra lỗi từ mảng thuộc tính kiemTraLoi. Mục đích để kiểm tra và thực hiện các hành động khác trước khi thực hiện các phương thức kiểm tra lỗi mặc định
     * 
     * Chú ý: phương thức phải trả về kiểu đúng (TRUE) để có thể thực hiện tiếp hành động kiểm tra lỗi dữ liệu nếu trả về sai (FALSE) thì hành động kiểm tra lỗi dữ liệu sẽ bị hủy bỏ
     * 
     * @param array $mangThuocTinh (tham trị truyền về là mảng thuộc tính được truyền vào phương thức luuDuLieu)
     * @return boolean (giá trị trả về đúng (TRUE) hoặc sai (FALSE))
     */
    public function thucHienTruocKhiKiemTraLoi($mangThuocTinh = array()) {
        return true;
    }

    /**
     * Phương thức thực hiện sau khi kiểm tra lỗi
     * 
     * CHỨC NĂNG:
     * Phương thức này được gọi sau khi thực hiện các hành động kiểm tra lỗi từ mảng thuộc tính kiemTraLoi. Mục đích để thực hiện các hành động khác sau khi phiên kiểm tra lỗi hoàn tất
     */
    public function thucHienSauKhiKiemTraLoi() {
        
    }

    /**
     * Phương thức thêm báo lỗi
     * 
     * CHỨC NĂNG:
     * Hổ trợ lớp đối tượng kiểm tra lỗi xử lý nạp giá trị các phần tử lên mảng thuộc tính mangBaoLoi
     * 
     * @param string $tenTruong (tham trị thứ 1 dạng chuỗi là tên trường báo lỗi gửi về)
     * @param string $cauBaoLoi (tham trị thứ 2 dạng chuỗi là câu báo lỗi gửi về)
     * @return boolean (giá trị trả về đúng (TRUE) khi thêm báo lỗi thành công hoặc sai (FALSE) khi thêm báo lỗi thất bại)
     */
    public function themBaoLoi($tenTruong = null, $cauBaoLoi = null) {
        if (is_string($tenTruong) && is_string($cauBaoLoi)) {
            if (is_array($this->mangBaoLoi)) {
                if (isset($this->mangBaoLoi[$this->tenBangCSDL])) {
                    $this->mangBaoLoi[$this->tenBangCSDL] += array($tenTruong => $cauBaoLoi);
                } else {
                    $this->mangBaoLoi[$this->tenBangCSDL] = array($tenTruong => $cauBaoLoi);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Phương thức xử lý mảng thuộc tính
     * 
     * CHỨC NĂNG:
     * Xử lý mảng thuộc tính gửi về tử phương thức xoaDuLieuBoi, xoaToanBoDuLieu, xoaDuLieu, layDuLieuBoi, layDuLieu, luuDuLieu. Nhằm khởi tạo các thuộc tính mặc định và cập nhật lại giá trị thuộc tính (nếu có)
     * 
     * @param array $thuocTinh (tham trị truyền vào là mảng các phần tử thuộc tính)
     * @return array (giá trị trả về dạng mảng là mảng thuộc tính sau khi xử lý)
     */
    private function xuLyMangThuocTinh($thuocTinh = array()) {
        $mangTraVe = array("sắp xếp" => "ORDER BY (SELECT NULL)", "gọi ngược" => true, "kiểm tra lỗi" => true, "giới hạn" => null, "kiểu trả về" => "tập hợp", "bộ nhớ đệm" => false);
        if (is_array($thuocTinh)) {
            foreach ($thuocTinh as $k => $v) {
                $k = mb_strtolower($k, "utf-8");
                switch ($k) {
                    case "gọi ngược":
                    case "kiểm tra lỗi":
                        $mangTraVe[$k] = (is_bool($v)) ? $v : true;
                        break;
                    case "sắp xếp":
                        $dangSapXep = array("Từ Dưới Lên" => "DESC", "Từ Trên Xuống" => "ASC", "DESC" => "DESC", "ASC" => "ASC");
                        if (is_array($v)) {
                            foreach ($v as $k1 => $v1) {
                                $sapXep = isset($dangSapXep[$v1]) ? $dangSapXep[$v1] : "ASC";
                                $mangTraVe[$k] = "ORDER BY {$this->kiTuPhanCachTenBangCot1}$k1{$this->kiTuPhanCachTenBangCot2} $sapXep";
                            }
                        }
                        break;
                    case "giới hạn":
                        $mangTraVe[$k] = (is_numeric(str_replace(",", "", $v))) ? $v : null;
                        break;
                    case "kiểu trả về":
                        $mangTraVe[$k] = (is_string($v)) ? $v : "tập hợp";
                        break;
                    case "bộ nhớ đệm":
                        $mangTraVe[$k] = (is_numeric($v) || is_bool($v)) ? $v : false;
                        break;
                }
            }
        }
        return $mangTraVe;
    }

    /**
     * Phương thức lấy mảng khóa phương thức tự sinh
     * 
     * CHỨC NĂNG:
     * Phương thức hổ trợ lấy và xử lý chuỗi phía sau tên phương thức bắt đầu bằng layDuLieuBoi và xoaDuLieuBoi
     * 
     * @param array $cauLenh (tham trị truyền vào là chuỗi phía sau cụm từ tên phương thức layDuLieuBoi và xoaDuLieuBoi)
     * @return array (kiểu trả về dạng mảng)
     */
    private function layMangKhoaPhuongThucTuSinh($cauLenh) {
        $mangPhanCach = array("VA", "HOAC");
        $mangKhoa = $cauLenh;
        foreach ($mangPhanCach as $v) {
            $mangKhoa = explode($v, $mangKhoa);
            $mangKhoa = implode("|" . $v . " ", $mangKhoa);
        }
        $mangKhoa = explode(" ", $mangKhoa);
        return $mangKhoa;
    }

    /**
     * Phương thức lấy các cột dữ liệu biểu mẫu gửi về
     * 
     * CHỨC NĂNG:
     * Dùng để lấy ra các trường dữ liệu gửi về trên biểu mẫu mà trên bảng csdl (database) chỉ định có tồn tại. Mục đích xử lý cho phương thức luuDuLieu
     * 
     * @param array $duLieuGuiVe (tham trị truyền về là mảng dữ liệu lưu)
     * @return array (giá trị trả về là mảng bao gồm các phần tử có khóa là tên trường giá trị là giá trị cần lưu hoặc cập nhật)
     */
    private function layCacCotDuLieuBieuMauGuiVe($duLieuGuiVe) {
        $bangTraVe = array();
        $bangDuLieu = $duLieuGuiVe[$this->tenBangCSDL];
        if (is_array($bangDuLieu)) {
            $mangTenCot = $this->coSoDuLieu->layTenCotTrenBang($this->tenBangCSDL);
            if (is_array($mangTenCot)) {
                foreach ($mangTenCot as $tenCot) {
                    foreach ($bangDuLieu as $k => $v) {
                        if ($tenCot === $k) {
                            $bangTraVe[$k] = (!is_array($v)) ? $v : "";
                        }
                    }
                }
            }
        }
        return $bangTraVe;
    }

}
