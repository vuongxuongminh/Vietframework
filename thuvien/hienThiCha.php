<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                23/04/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng hiển thị cha (VIEW) chứa các thuộc tính và phương thức liên quan đến việc xuất dữ liệu lên màn hình
 * 
 * CHỨC NĂNG CHÍNH:
 * Quản lý dữ liệu xuất lên màn hình như mẫu trang (layout) tệp hiển thị
 * Hổ trợ đói tượng HTML thao tác với thành phần HTML trên trang
 * Hỗ trợ các hàm xuất câu thông báo và lỗi truy cập lên màn hình
 */

class hienThiCha {

    /**
     * Thuộc tính mẫu trang hiển thị (layout)
     * 
     * CHỨC NĂNG:
     * Quy định mẫu trang hiển thị (layout) được nạp lên màn hình các tệp mẫu trang được chứa trong thư mục (hienthi/mautrang)
     * 
     * Thường thuộc tính này được tác động thông quá thuộc tính mẫu trang hiển thị ở dữ liệu gửi đi
     * 
     * Nếu không có tương tác gía trị mặc định của nó là "macdinh"
     * 
     * Ví dụ tôi muốn thay đổi mẫu trang (layout) abc cho trang thì tui thiết lập giá trị cho thuộc tính này là abc
     * 
     * @var string (kiểu dữ liệu chuỗi) 
     */
    public static $mauTrang = "macdinh";

    /**
     * Thuộc tính thư mục chính
     * 
     * CHỨC NĂNG:
     * Quy định thư mục bao trùm tất cả các thư mục khác trong thư mục hiển thị VIEW (main path) nhằm tạo nên hệ thống quy chuẩn dành cho ai có nhu cầu sử dụng đa nền tảng hiển thị
     * 
     * Thường thuộc tính này được tác động thông qua lớp đối tượng dữ liệu gửi đi ở thuộc tính thư thuMucChinh
     * 
     * Nếu không có tương tác gía trị mặc định của nó là rỗng
     * 
     * Ví dụ tôi muốn toàn bộ cấu trúc thư mục nằm trong thư mục tên abc thì tôi thiết lập thuộc tính này thông qua dữ liệu gửi đi là abc
     * 
     * @var string (kiểu dữ liệu chuỗi) 
     */
    private $thuMucChinh = "";

    /**
     * Thuộc tính tệp hiển thị
     * 
     * CHỨC NĂNG
     * Quy định tệp hiển thị được nạp lên màn hình các tệp hiển thị được chứa trong thư mục (hienthi/"tên điều hướng của bạn")
     * 
     * Thường thuộc tính này được tác động thông quá thuộc tính tệp hiển thị ở dữ liệu gửi đi
     * 
     * Nếu không có tương tác gía trị mặc định của nó là tên phương thức được gọi
     * 
     * Ví dụ nếu tôi muốn thay đổi tệp hiển thị abc cho trang thì tui thiết lập giá trị cho thuộc tính này là abc
     * 
     * @var string (kiểu dữ liệu chuỗi) 
     */
    public $tepHienThi = null;

    /**
     * Mảng biến thiết lập chứa các phần tử mang giá trị là các biến được thiết lập lên tệp mẫu trang (layout) hoặc tệp hiển thị nhằm hỗ trợ bạn tương tác dữ liệu
     * 
     * CHỨC NĂNG:
     * Thiết lập biến lên mẫu trang (layout) hoặc tệp hiển thị giúp bạn tương tác với dữ liệu gửi đi tại tầng điều hướng (controller)
     * 
     * Thường mảng này được tương tác thông qua đối tượng dữ liệu gửi đi phương thức "bien" tại tầng điều hướng (controller)
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    private $mangBienThietLap = array();

    /**
     * Thuộc tính nạp hiển thị để xác định có nạp hiển thị lên màn hình hay không
     * 
     * CHỨC NĂNG:
     * Quy định chế độ hiển thị giao diện (mẫu trang + tệp hiển thị) ra màn hình
     * 
     * Nếu giá trị là đúng (TRUE) thì giao diện được nạp lên ngược lại thì không
     * 
     * Gía trị mặc định là đúng (TRUE)
     * 
     * Thường thuộc tính này được tương tác thông qua đối tượng dữ liệu gửi đi thuộc tính "napTrangHienThi"
     * 
     * @var boolean (kiểu đúng hoặc sai)
     */
    public $napHienThi = true;

    /**
     * Đối tượng HTML hỗ trợ các phương thức thao tác đến thành phần HTML trên mẫu trang (layout) hoặc tệp hiển thị
     * 
     * CHỨC NĂNG:
     * Đơn giản hóa việc tương tác thành phần HTML trên trang như napCSS, nap JavaScript, napHinhAnh, napPhimAnh, napAmThanh, napTuKhoaTrang, napMoTaTrang, napBieuTuongTrang v..v
     * 
     * Hỗ trợ việc tương tác cơ sỡ dữ liệu trong vấn đề thêm, cập nhật, xóa, hiển thị (insert, update, delete, show)
     * 
     * @var doiTuongHtml
     */
    public $doiTuongHtml;

    /**
     * Phương thức khởi tạo lớp đối tượng hienThiCha
     * 
     * CHỨC NĂNG:
     * Truyền các giá trị cho các thuộc tính trong lớp đối tượng tương đương với các thuộc tính dữ liệu gửi đi được thiết lập ở lớp điều hướng
     * 
     * @param duLieuGuiDi $duLieuGuiDi
     */

    /**
     * Thuộc tính bộ nhớ đệm giúp lưu lại dữ liệu HTML xuất ra tại tầng VIEW
     * 
     * Mặc định thuộc tính này sẽ là false (tức là không tự động lưu bộ nhớ đệm nếu bạn không yêu cầu).
     * 
     * @var mixed (kiểu dữ liệu dạng ĐÚNG (TRUE) tức là sẽ lưu bộ nhớ đệm theo thời gian đáo hạn mặc định chỉnh tại thiết lập bộ nhớ đệm nếu kiểu dữ liệu dạng số nguyên thì tương ứng với số giây đáo hạn) 
     */
    private $boNhoDem = false;

    public function __construct(duLieuGuiDi $duLieuGuiDi = null) {
        if (!empty($duLieuGuiDi)) {
            self::$mauTrang = $duLieuGuiDi->mauTrangHienThi;
            $this->thayDoiThuMucChinh($duLieuGuiDi->thuMucChinh);
            $this->napHienThi = is_bool($duLieuGuiDi->napTrangHienThi) ? $duLieuGuiDi->napTrangHienThi : false;
            $this->doiTuongHtml = new doiTuongHtml($duLieuGuiDi);
            $this->mangBienThietLap = $duLieuGuiDi->mangBienThietLap;
            $this->boNhoDem = $duLieuGuiDi->boNhoDem;
            if (!empty($duLieuGuiDi->tepHienThi)) {
                $this->tepHienThi($duLieuGuiDi->tepHienThi);
            }
        } else {
            $this->doiTuongHtml = new doiTuongHtml;
        }
    }

    /**
     * Phương thức thay đổi tên thư mục chính
     * 
     * CHỨC NĂNG:
     * Quy định thư mục bao trùm tất cả các thư mục khác trong thư mục hiển thị VIEW (main path) nhằm tạo nên hệ thống quy chuẩn dành cho ai có nhu cầu sử dụng đa nền tảng hiển thị
     * 
     * Thay đổi giá trị các hằng qua giá trị mới phụ hợp để phục vụ tương tác ở tầng VIEW
     * 
     */
    private function thayDoiThuMucChinh($tenThuMucChinh = "") {
        if (!empty($tenThuMucChinh)) {
            $this->thuMucChinh = $tenThuMucChinh;
            
        }
    }

    /**
     * Thuộc tính duongDanTepHienThiPhucVuBaoLoi ghi lại đường dẫn tệp hiển thị
     * 
     * CHỨC NĂNG:
     * Phục vụ cho việc báo lỗi thiếu tệp hiển thị (nếu có)
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public static $duongDanTepHienThiPhucVuBaoLoi;

    public function tepHienThi($tenTep, $tuDongNoiDuongDan = true) {
        if ($tuDongNoiDuongDan) {
            $thuMucChinh = !empty($this->thuMucChinh) ? $this->thuMucChinh . DS : "";
            self::$duongDanTepHienThiPhucVuBaoLoi = DUONGDANTHUMUCTRANG . "hienthi" . DS . $thuMucChinh . dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Điều Hướng"] . DS . $tenTep . ".php";
        } else {
            self::$duongDanTepHienThiPhucVuBaoLoi = $tenTep;
        }
        $this->tepHienThi = self::$duongDanTepHienThiPhucVuBaoLoi;
    }

    /**
     * Phương thức nạp mẫu trang dùng để nạp trang hiển thị lên trình duyệt nếu như bạn cho phép thông qua thuộc tính napTrangHienThi tại đối tượng dữ liệu gửi đi tại tầng điều hướng (controller)
     * 
     * CHỨC NĂNG:
     * Nạp giao diện lên trình duyệt
     * 
     * Lưu ý: bạn tuyết đối không gọi phương thức này ở tệp mẫu trang (layout) hoặc tệp hiển thị. Vì nó sẽ gây ra lỗi giao diện
     * 
     * @return boolean trả về dạng đúng (TRUE) khi trang được nạp lên sai (FALSE) khi trang không được nạp
     */
    public function napMauTrang() {
        if ($this->napHienThi) {
            ini_set('display_errors', true);
            extract($this->mangBienThietLap);
            $tenVungNho = maHoa::maHoaChuoi("napMauTrang" . json_encode(dieuHuongCha::$dieuHuongPhuongThucVaThamTri), "sha1");
            require_once(DUONGDANTHUMUCTRANG."thuvien".DS."xuly".DS."boNhoDem.php");
            $boNhoDem = __boNhoDem();
            if ($this->boNhoDem !== false) {
                if ($boNhoDem->kiemTraVungNhoTonTai($tenVungNho)) {
                    echo $boNhoDem->giaTriVungNho($tenVungNho);
                    return;
                }
            }
            $thuMucChinh = !empty($this->thuMucChinh) ? $this->thuMucChinh . DS : "";
            $tepMauTrangTuyChon = DUONGDANTHUMUCTRANG . "hienthi" . DS . $thuMucChinh . "mautrang" . DS . self::$mauTrang . ".php";
            $tepMauTrangHeThong = DUONGDANTHUMUCTRANG . "thuvien" . DS . "hienthi" . DS . "mautrang" . DS . self::$mauTrang . ".php";
            $tepMauTrang = $tepMauTrangHeThong;
            if (file_exists($tepMauTrangTuyChon)) {
                $tepMauTrang = $tepMauTrangTuyChon;
            }
            if (file_exists($tepMauTrang)) {
                require($tepMauTrang);
                if ($this->boNhoDem !== false) {
                    $duLieuDuyetHtml = ob_get_contents();
                    $boNhoDem->thietLapVungNho($tenVungNho, $duLieuDuyetHtml, $this->boNhoDem);
                }
            } else {
                baoLoiCha::chayTrangBaoLoi("Tệp mẫu trang không tồn tại");
            }
        }
    }

    /**
     * Phương thức câu thông báo hỗ trợ xuất câu thông báo gửi từ tầng điều hướng (controller) về tệp mẫu trang (layout) hoặc tệp hiển thị
     * 
     * CHỨC NĂNG:
     * Xuất câu thông báo tại tệp mẫu trang (layout) hoặc tệp hiển thị (nếu có)
     * 
     * Cách sử dụng: tại tệp mẫu trang (layout) hoặc tệp hiển thị để gọi câu thông báo gửi từ tầng điều hướng (controller) bạn thực hiện cú pháp "echo $this->cauThongBao()"
     * 
     * @return string (kiểu dữ liệu trả về dạng chuỗi)
     */
    public function cauThongBao() {
        $mangFrameWork = mangSession::lay("vietFrameWork");
        if (isset($mangFrameWork["Câu Thông Báo"])) {
            $cauThongBao = $mangFrameWork["Câu Thông Báo"];
            unset($mangFrameWork["Câu Thông Báo"]);
            mangSession::thietLap("vietFrameWork", $mangFrameWork);
            return $cauThongBao;
        }
        return "";
    }

    /**
     * Phương thức câu báo lỗi truy cập hỗ trợ xuất câu báo lỗi truy cập gửi từ tầng điều hướng (controller) về tệp mẫu trang (layout) hoặc tệp hiển thị
     * 
     * CHỨC NĂNG:
     * Xuất câu báo lỗi truy cập tại tệp mẫu trang (layout) hoặc tệp hiển thị (nếu có)
     * 
     * Cách sử dụng:
     * Tại tệp mẫu trang (layout) hoặc tệp hiển thị để gọi câu báo lỗi truy cập gửi từ tầng điều hướng (controller) bạn thực hiện cú pháp "echo $this->cauBaoLoiTruyCap()"
     * 
     * @param array $mangThuocTinh (tham trị truyền vào là mảng thuộc tính chứa các phần tử là thuộc tính cần hiển thị lỗi truy cập)
     * @return string (kiểu dữ liệu trả về dạng chuỗi)
     */
    public function cauBaoLoiTruyCap() {
        $mangFrameWork = mangSession::lay("vietFrameWork");
        if (isset($mangFrameWork["Câu Báo Lỗi Truy Cập"])) {
            $cauBaoLoi = $mangFrameWork["Câu Báo Lỗi Truy Cập"];
            unset($mangFrameWork["Câu Báo Lỗi Truy Cập"]);
            mangSession::thietLap("vietFrameWork", $mangFrameWork);
            return $cauBaoLoi;
        }
        return "";
    }

    /**
     * Phương thức nạp trang chỉ định dùng để nạp trang hiển thị theo từng phương thức chỉ định riêng biệt
     * 
     * CHỨC NĂNG:
     * Xuất hiển thị theo từng phương thức chỉ định riêng biệt lên mẫu trang (layout) đã thiết lập nơi hiển thị ra màn hình
     * 
     * Ví dụ: tại tệp mẫu trang (layout) sau khi bố trí xong đến phần nội dung thay đổi tùy theo phương thức yêu cầu thì tại đó tôi gọi "$this->napTrangChiDinh()"
     * 
     * để nạp tệp hiển thị theo từng phương thức yêu cầu riêng biệt.
     * 
     * Mặc định trang chỉ định mang giá trị tương đương với tên phương thức chỉ định
     * 
     * Ví dụ: nếu tôi truy cập vào tầng điều hướng (controller) taikhoan và phương thức (action) doimatkhau thì mặc định tệp hiển thị tôi nằm trong thu mục "hienthi/taikhoan/doimatkhau.php"
     * 
     * @return boolean (trả về kiểu đúng (TRUE) khi trang hiển thị được nạp lên màn hình sai (FALSE) khi ngược lại)
     */
    public function napTrangChiDinh() {
        if ($this->napHienThi) {
            ini_set('display_errors', false);
            extract($this->mangBienThietLap);
            if (file_exists($this->tepHienThi)) {
                require_once($this->tepHienThi);
            } else {
                baoLoiCha::chayTrangBaoLoi("Tệp hiển thị không tồn tại");
            }
        }
    }

    /**
     * Phương thức thietLapBien để thiết lập các biến
     * 
     * CHỨC NĂNG:
     * Thiết lập biến về tầng hiển thị trong tệp mẫu trang (layout) hoặc tệp hiển thị để sử dụng
     * 
     * Ví dụ bạn muốn thiết lập biến $a mang giá trị là 1 thì tại tệp mẫu trang (layout) hoặc tệp hiển thị bạn gọi "$this->thietLapBien("a", 1);"
     * 
     * Thì ngay lập tức hệ thống sẽ tự động sinh biến $a mang giá trị là 1 cho bạn tương tác
     * 
     * MỞ RỘNG:
     * Ví dụ bạn muốn thiết lập nhiều biến 1 lúc thì tại tầng điều hướng bạn gọi "$this->thietLapBien(array("bien 1" => gia tri 1, "bien 2" => gia tri 2, "bien n" => gia tri n));"
     * 
     * Thì ngay lập tức hệ thống sẽ tự động sinh ra mảng biến tương ứng
     * 
     * @param mixed $bien (kiểu dữ liệu chuỗi hoặc mảng)
     * @param mixed $giaTri (kiểu dữ liệu ngẫu nhiên lưu ý: nếu thiết lập 1 lúc nhiều biến thì không cần truyền tham trị này !)
     */
    public function thietLapBien($bien, $giaTri = null) {
        if (is_string($bien)) {
            $this->mangBienThietLap[$bien] = $giaTri;
        } elseif (is_array($bien)) {
            foreach ($bien as $k => $v) {
                if (is_string($k)) {
                    $this->mangBienThietLap[$k] = $v;
                }
            }
        }
    }

    /**
     * Phương thức nạp thành phần trang
     * 
     * CHỨC NĂNG:
     * Hổ trợ bạn nạp các thành phần chung của trang (element) lên tệp hiển thị hoặc tệp mẫu trang (layout) giúp tái sử dụng lại các thành phần được lập đi lập lại ở nhiều trang
     * 
     * Ví dụ: tôi muốn nạp thành phần "thanhmenu" lên trang thì tại tệp hiển thị hoặc mẫu trang (layout) tôi gọi như sau: "$this->napThanhPhanTrang("thanhmenu");" thì ngay lập tức tệp thanhmenu.php được nạp lên trang
     * 
     * Lưu ý: các thành phần trang được chứa tại hienthi/thanhphan/
     * 
     * @param string $tenThanhPhan (tham trị truyền vào dạng chuỗi là chuỗi tên thành phần cần nạp lên trang hiển thị hoặc mẫu trang (layout))
     */
    public function napThanhPhanTrang($tenThanhPhan = "") {
        $dayThamTri = func_get_args();
        foreach ($dayThamTri as $v) {
            if (is_string($v)) {
                $v = str_replace("/", DS, $v);
                $tepThanhPhan = DUONGDANTHUMUCTRANG . "hienthi" . DS . "thanhphan" . DS . $v . ".php";
                ini_set('display_errors', false);
                extract($this->mangBienThietLap);
                if (file_exists($tepThanhPhan)) {
                    require($tepThanhPhan);
                } else {
                    trigger_error("Thành phần hiển thị: '$tepThanhPhan' không tồn tại !", E_USER_WARNING);
                }
            }
        }
    }

}

?>