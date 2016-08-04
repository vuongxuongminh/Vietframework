<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                25/05/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng HTML chứa các phương thức hỗ trợ tương tác với các thành phần HTML trên tệp mẫu trang (layout) hoặc tệp hiển thị
 * 
 * CHỨC NĂNG CHÍNH:
 * Tương tác với các thành phần biểu mẫu (FORM) như phương thức napNhapLieu, napThoiGian, napTuyChon, napBieuMau, dongBieuMau v..v
 * Tương tác với các thành phần HTML như phương thức napCSS, napJavaScript, napCSS, napTuKhoaTrang, napMoTaTrang v..v
 * Ngoài ra lớp đối tượng này còn hỗ trợ tương tác với HTML5 như phương thức napHinhAnh, napAmThanh
 */

class doiTuongHtml {

    /**
     * Mảng thuộc tính dữ liệu biểu mẫu gửi đi có các phần từ mang giá trị tương đương với dữ liệu sau khi xử lý tại tầng điều hướng (controller) và muốn tải về nạp lên giao diện biểu mẫu client
     * 
     * CHỨC NĂNG:
     * Hỗ trợ việc đẩy dữ liệu lên giao diện biểu mẫu client
     * 
     * Thuộc tính này được tương tác bởi thuộc tính duLieuBieuMauGuiDi tại lớp đối tượng dữ liệu gửi đi (duLieuGuiDi) ở tầng điều hướng (controller)
     * 
     * Ví dụ tôi muốn thiết lập giá trị cho thuộc tính này thì tại tầng điều hướng (controller) trong phương thức chỉ định tôi gọi nhu sau "$this->duLieuGuiDi->duLieuBieuMauGuiDi = array(...)"
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $duLieuBieuMauGuiDi;

    /**
     * Thuộc tính tên biểu mẫu để lưu tên bảng CSDL mà ta muốn tương tác
     * 
     * CHỨC NĂNG:
     * Lưu tên bảng CSDL mà ta muốn tương tác
     * 
     * Mục đích hỗ trợ các hàm thêm, xóa, sữa, hiển thị (insert, update, delete, show) tại tầng xử lý (MODEL)
     * 
     * Thuộc tính này được tương tác thông qua phương thức napBieuMau và dongBieuMau cùng lớp
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    private $tenBieuMau = "dulieu";

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
     * Mảng thuộc tính lưu các phần tử mang giá trị là các chuỗi báo lỗi
     * 
     * CHỨC NĂNG:
     * Lưu các chuỗi thông báo lỗi từ tầng xử lý (MODEL) khi xử lý các tính năng thêm hoặc cập nhật (insert, update) khi bạn có khai báo mảng kiểm tra lỗi mà nếu có xảy ra lỗi thì hệ thống sẽ tự động nạp các lỗi đó vào mảng thuộc tính này
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $mangBaoLoi;

    /**
     * Phương thức khởi tạo lớp đối tượng HTML
     * 
     * CHỨC NĂNG:
     * 
     * Gán giá trị cho thuộc tính mảng báo lỗi (sẽ là mảng rổng nếu không xảy ra lỗi từ tầng xử lý)
     * 
     * Gán giá trị cho thuộc tính dữ liệu biểu mẫu gửi đi (sẽ là mảng rổng nếu không có dữ liệu gửi đi từ tầng điều hướng)
     * 
     * @param array $mangBaoLoi (tham trị thứ nhất truyền vào có kiểu mảng)
     * @param array $duLieuBieuMauGuiDi (tham trị thứ hai truyền vào có kiểu mảng)
     */
    public function __construct(duLieuGuiDi $duLieuGuiDi = null) {
        if (!is_null($duLieuGuiDi)) {
            $this->mangBaoLoi = $duLieuGuiDi->mangBaoLoiHtml;
            $this->duLieuBieuMauGuiDi = $duLieuGuiDi->duLieuBieuMauGuiDi;
            $this->thuMucChinh = $duLieuGuiDi->thuMucChinh;
        }
    }

    /**
     * Phương thức nạp hình ảnh
     * 
     * CHỨC NĂNG:
     * Nạp hình ảnh lên mẫu trang (layout) hoặc tệp hiển thị
     * 
     * Ví dụ: tôi muốn nạp 1 hình ảnh "abc.jpg" thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napHinhAnh("abc.jpg");"
     * 
     * Các tệp hình ảnh bạn bắt buộc phải đặt trong thư mục "hotro/hinhanh/"
     * 
     * MỞ RỘNG 1:
     * Bạn còn có thể truyền tiếp các tham trị mảng thuộc tính phía sau nhằm thiết lập các thuộc tính (attrib) cho các thành phần thẻ img
     * 
     * Ví dụ: tôi muốn nạp 1 hình ảnh "abc.jpg" có thuộc tính class là "def" thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napHinhAnh("abc.jpg", array("class" => "def"));"
     * 
     * MỞ RỘNG 2:
     * Trong mảng thuộc tính tham trị phía sau bạn còn có thể thiết lập đường dẫn liên kết cho hình ảnh
     * 
     * Ví dụ: tôi muốn nạp 1 hình ảnh "abc.jpg" có đường dẫn gọi đến trang điều hướng (controller) "sanpham" và phương thức (action) "them" thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napHinhAnh("abc.jpg", array("Đường Dẫn Liên Kết" => array("Điều Hướng" => "sanpham", "Phương Thức" => "them")));"
     * 
     * Ví dụ: tôi muốn nạp 1 hình ảnh "abc.jpg" có đường dẫn gọi đến trang http://google.com thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napHinhAnh("abc.jpg", array("Đường Dẫn Liên Kết" => "http://google.com"));"
     * 
     * @param string $duongDanHinh (tham trị thứ nhất truyền vào là tên hình ảnh kiểu chuỗi)
     * @param array $thuocTinh (tham trị thứ hai truyền vào là mảng thuộc tính kiểu mảng)
     * @return string (giá trị trả về là đường dẫn liên kết đến hình ảnh kiểu chuỗi)
     */
    public function napHinhAnh($duongDanHinh = null, $thuocTinh = array()) {
        if (is_string($duongDanHinh)) {
            $dayThamTri = func_get_args();
            $chuoiHinhAnh = "";
            unset($dayThamTri[0]);
            if (!filter_var($duongDanHinh, FILTER_VALIDATE_URL)) {
                $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANHINH : DUONGDANHOTRO . $this->thuMucChinh . "/hinhanh/";
                $chuoiHinhAnh .= "<img src='" . $thuMucChinh . $duongDanHinh . "' ";
            } else {
                $chuoiHinhAnh .= "<img src='" . $duongDanHinh . "' ";
            }
            foreach ($dayThamTri as $v) {
                $chuoiHinhAnh .= $this->xuLyThanhPhanBenTrong($v, array("Xác Nhận", "Đường Dẫn Liên Kết"));
            }
            $chuoiHinhAnh .= " />";
            foreach ($dayThamTri as $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if (is_string($k1) && $k1 === "Đường Dẫn Liên Kết") {
                            $chuoiHinhAnh = $this->napLienKet($chuoiHinhAnh, $v1);
                        }
                    }
                }
            }
            return $chuoiHinhAnh;
        }
        return "Lỗi cú pháp napHinhAnh vui lòng xem lại !";
    }

    /**
     * Phương thức nạp tiêu đề trang
     * 
     * CHỨC NĂNG:
     * Phương thức này tương đương với thẻ <title> trên HTML. Nó hổ trợ bạn nạp tiêu đề trang theo code PHP với tiêu đề là chuỗi tham trị truyền vào phương thức
     * 
     * @param string $chuoiTieuDe (tham trị truyền vào dạng chuỗi là chuỗi tiêu đề trang cần nạp lên tầng hiển thị (VIEW))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napTieuDeTrang($chuoiTieuDe = "") {
        if (is_string($chuoiTieuDe) || is_numeric($chuoiTieuDe)) {
            $tieuDe = "Việt Framework - Framework việt cho người Việt";
            if ($chuoiTieuDe !== "") {
                $tieuDe = $chuoiTieuDe;
            }
            return "<title>" . $tieuDe . "</title>";
        }
        return "Lỗi cú pháp napTieuDeTrang vui lòng xem lại !";
    }

    /**
     * Phương thức meta
     * 
     * CHỨC NĂNG:
     * Dùng để nạp thẻ meta lên tầng hiển thị (VIEW). Với các thuộc tính truyền vào theo yêu cầu của bạn
     * 
     * @param array $dayThuocTinh (tham trị truyền vào là dãy thuộc tính của thẻ meta theo ý bạn)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function meta($dayThuocTinh = array()) {
        if (is_array($dayThuocTinh) && !empty($dayThuocTinh)) {
            $dayMeta = "<meta ";
            $dayThamTri = func_get_args();
            foreach ($dayThamTri as $v) {
                $dayMeta .= $this->xuLyThanhPhanBenTrong($v, array("Xác Nhận"));
            }
            $dayMeta .= " />";
            return $dayMeta;
        }
        return "Lỗi cú pháp meta vui lòng xem lại !";
    }

    /**
     * Phương thức nạp từ khóa trang
     * 
     * CHỨC NĂNG:
     * Phương thức này trả về chuỗi tương đương với thẻ "<meta name='keywords' content='???'>"
     * 
     * Dùng để nạp từ khóa trang tại tệp mẫu trang (layout) hoặc tệp hiển thị
     * 
     * Ví dụ: tôi muốn nạp 1 dãy từ khóa "Việt Frame Work" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napTuKhoaTrang("Việt Frame Work");"
     * 
     * MỞ RỘNG:
     * Bạn có thể nạp 1 lúc nhiều dãy từ khóa liên tiếp nhau 1 lúc
     * 
     * Ví dụ: tôi muốn nạp 2 dãy từ khóa "Việt Frame Work", "Framework Việt cho người Việt" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napTuKhoaTrang("Việt Frame Work", "Framework Việt cho người Việt");"
     * 
     * @param string $dayTuKhoaTrang (lưu ý bạn có thể truyền bao nhiêu tham trị tùy thích mỗi tham trị tương đương với 1 cụm từ khóa kiểu dữ liệu cho các tham trị là dạng chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napTuKhoaTrang($dayTuKhoaTrang = null) {
        $dayThamTri = func_get_args();
        $dayTuKhoa = "";
        foreach ($dayThamTri as $giaTri) {
            if (is_string($giaTri) || is_numeric($giaTri)) {
                $dayTuKhoa .= "<meta name='keywords' content='$giaTri' />";
            } else {
                return "Lỗi cú pháp napTuKhoaTrang vui lòng xem lại !";
            }
        }
        return $dayTuKhoa;
    }

    /**
     * Phương thức nạp biểu tượng trang
     * 
     * CHỨC NĂNG:
     * Phương thức này trả về chuỗi tương đương với thẻ "<link rel='icon' type='image/x-icon' href='???.ico'>"
     * 
     * Dùng để nạp biểu tượng trang
     * 
     * Ví dụ tôi muốn nạp biểu tượng "abc.ico" thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napBieuTuongTrang("abc.ico");"
     * 
     * Các tệp biểu tượng bạn bắt buộc phải đặt trong thư mục "hotro/hinhanh/"
     * 
     * @param string $duongDanBieuTuong (tham trị đường dẫn biểu tương dạng chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napBieuTuongTrang($duongDanBieuTuong = null) {
        if (is_string($duongDanBieuTuong)) {
            $chuoiBieuTuong = "";
            if (!filter_var($duongDanBieuTuong, FILTER_VALIDATE_URL)) {
                $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANHINH : DUONGDANHOTRO . $this->thuMucChinh . "/hinhanh/";
                $chuoiBieuTuong .= "<link rel='icon' type='image/x-icon' href='" . $thuMucChinh . $duongDanBieuTuong . "' />";
            } else {
                $chuoiBieuTuong .= "<link rel='icon' type='image/x-icon' href='" . $duongDanBieuTuong . "' />";
            }
            return $chuoiBieuTuong;
        }
        return "Lỗi cú pháp napBieuTuongTrang vui lòng xem lại !";
    }

    /**
     * Phương thức nạp mô tả trang
     * 
     * CHỨC NĂNG:
     * Phương thức này trả về chuỗi tương đương với thẻ "<meta name='description' content='????'>"
     * 
     * Dùng để nạp mô tả trang
     * 
     * Ví dụ: tôi muốn nạp 1 dãy mô tả "Việt Frame Work" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napMoTaTrang("Việt Frame Work");"
     * 
     * MỞ RỘNG:
     * Bạn có thể nạp 1 lúc nhiều dãy mô tả liên tiếp nhau 1 lúc
     * 
     * Ví dụ: tôi muốn nạp 2 dãy mô tả "Việt Frame Work", "Framework Việt cho người Việt" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napMoTaTrang("Việt Frame Work", "Framework Việt cho người Việt");"
     * 
     * @param string $dayMoTaTrang  (lưu ý bạn có thể truyền bao nhiêu tham trị tùy thích mỗi tham trị tương đương với 1 cụm mô tả kiểu dữ liệu cho các tham trị là dạng chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napMoTaTrang($dayMoTaTrang = null) {
        $dayThamTri = func_get_args();
        $dayMoTa = "";
        foreach ($dayThamTri as $giaTri) {
            if (is_string($giaTri) || is_numeric($giaTri)) {
                $dayMoTa .= "<meta name='description' content='$giaTri' />";
            } else {
                return "Lỗi cú pháp napMoTaTrang vui lòng xem lại !";
            }
        }
        return $dayMoTa;
    }

    /**
     * Phương thức nạp CSS
     * 
     * CHỨC NĂNG:
     * Phương thức này trả về chuỗi tương đương với thẻ "<link rel='stylesheet' type='text/css' href='???'>"
     * 
     * Dùng để nạp các định dạng css lên trang
     * 
     * Ví dụ: tôi muốn nạp 1 tệp css "abc.css" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napCSS("abc");"
     * 
     * Các tệp CSS bạn bắt buộc phải đặt trong thư mục "hotro/css/"
     * 
     * MỞ RÔNG 1:
     * Bạn có thể nạp nhiều tệp css cùng 1 lúc
     * 
     * Ví dụ: tôi muốn nạp 2 tệp css "abc.css" và "http://api.google.com/css.css" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napCSS("abc", "http://api.google.com/css.css");"
     * 
     * MỞ RỘNG 2:
     * Bạn có thể thiết lập các thuộc tính (attrib) cho thẻ link
     * 
     * Ví dụ: tôi muốn nạp 1 tệp css "abc.css" có class là "vietFrameWork" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napCSS(array("abc", "class"=>"vietFrameWork"));"
     * 
     * Ví dụ: tôi muốn nạp 2 tệp css "abc.css" có class là "vietFrameWork" và "http://api.google.com/css.css" có id là "vietFrameWork" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tui gọi như sau "echo $this->napCSS(array("abc", "class"=>"vietFrameWork"), array("http://api.google.com/css.css", "id"=>"vietFrameWork"));"
     * 
     * @param mixed $dayCSSNap (các tham trị truyền vào có kiểu dữ liệu chuỗi hoặc mảng)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napCSS($dayCSSNap = null) {
        $dayThamTri = func_get_args();
        $dayCSS = "";
        $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANCSS : DUONGDANHOTRO . $this->thuMucChinh . "/css/";
        foreach ($dayThamTri as $giaTri) {
            if (is_string($giaTri) || is_numeric($giaTri)) {
                if (!filter_var($giaTri, FILTER_VALIDATE_URL)) {
                    $dayCSS .= "<link rel='stylesheet' type='text/css' href='" . $thuMucChinh . $giaTri . ".css' />";
                } else {
                    $dayCSS .= "<link rel='stylesheet' type='text/css' href='$giaTri' />";
                }
            } elseif (is_array($giaTri)) {
                if (isset($giaTri[0])) {
                    if (is_string($giaTri[0]) || is_numeric($giaTri[0])) {
                        if (!filter_var($giaTri[0], FILTER_VALIDATE_URL)) {
                            $dayCSS .= "<link rel='stylesheet' type='text/css' href='" . $thuMucChinh . $giaTri[0] . ".css' ";
                        } else {
                            $dayCSS .= "<link rel='stylesheet' type='text/css' href='" . $giaTri[0] . "' ";
                        }
                        foreach ($giaTri as $v) {
                            $dayCSS .= $this->xuLyThanhPhanBenTrong($v, array("Xác Nhận"));
                        }
                        $dayCSS .= " />";
                    } else {
                        return "Lỗi cú pháp napCSS vui lòng xem lại !";
                    }
                } else {
                    return "Lỗi cú pháp napCSS vui lòng xem lại !";
                }
            }
        }
        return $dayCSS;
    }

    /**
     * Phương thức nạp JavaScript
     * 
     * CHỨC NĂNG:
     * Nạp các tệp javascript lên mẫu trang (layout) hoặc tệp hiển thị
     * 
     * Ví dụ: tôi muốn nạp 1 tệp javascript có tên là "abc.js" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tôi gọi "echo $this->napJavaScript("abc")"
     * 
     * Các tệp JS bạn bắt buộc phải đặt trong thư mục "hotro/js/"
     * 
     * MỞ RỘNG:
     * Bạn có thể nạp 1 lúc nhiều tệp CSS
     * 
     * Ví dụ: tôi muốn nạp 2 tệp css có tên là "abc.js" và "edf.js" lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tôi gọi "echo $this->napJavaScript("abc", "edf")"
     * 
     * @param string $dayJavaScript (các tham trị truyền vào có kiểu dữ liệu chuỗi là tên tệp js)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napJavaScript($dayJavaScript = null) {
        $dayThamTri = func_get_args();
        $dayJS = "";
        $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANJS : DUONGDANHOTRO . $this->thuMucChinh . "/js/";
        foreach ($dayThamTri as $giaTri) {
            if (is_string($giaTri) || is_numeric($giaTri)) {
                if (!filter_var($giaTri, FILTER_VALIDATE_URL)) {
                    $dayJS .= "<script type='text/javascript' src='" . $thuMucChinh . $giaTri . ".js'></script>";
                } else {
                    $dayJS .= "<script type='text/javascript' src='$giaTri'></script>";
                }
            } elseif (is_array($giaTri)) {
                if (isset($giaTri[0])) {
                    if (is_string($giaTri[0]) || is_numeric($giaTri[0])) {
                        if (!filter_var($giaTri[0], FILTER_VALIDATE_URL)) {
                            $dayJS .= "<script type='text/javascript' src='" . $thuMucChinh . $giaTri[0] . ".js' ";
                        } else {
                            $dayJS .= "<script type='text/javascript' src='" . $giaTri[0] . "' ";
                        }
                        foreach ($giaTri as $v) {
                            $dayJS .= $this->xuLyThanhPhanBenTrong($v, array("Xác Nhận"));
                        }
                        $dayJS .= "></script>";
                    } else {
                        return "Lỗi cú pháp napJavaScript vui lòng xem lại !";
                    }
                } else {
                    return "Lỗi cú pháp napJavaScript vui lòng xem lại !";
                }
            }
        }
        return $dayJS;
    }

    /**
     * Phương thức nạp liên kết
     * 
     * CHỨC NĂNG:
     * Nạp liên kết lên tệp mẫu trang (layout) hoặc tệp hiển thị. 
     * Phương thức này hỗ trợ chuyển trang trong hệ thống thông qua mảng điều hướng, phương thức và tham trị
     * 
     * Ví dụ: tôi muốn nạp liên kết chuyển trang đến tầng điều hướng (controller) "sanpham" gọi phương thức (action) "nap" và truyền tham trị cho phương thức là 5 thì tôi ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napLienKet("chuỗi hiễn thị trong cặp thẻ <a>", array("Điều Hướng" => "sanpham", "Phương Thức" => "nap", 5));"
     * 
     * Ví dụ: tôi muốn nạp liên kết chuyển trang đến http://google.com thì thì tôi ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napLienKet("chuỗi hiễn thị trong cặp thẻ <a>", "http://google.com");"
     * 
     * MỞ RỘNG 1:
     * Phương thức còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <a>
     * 
     * Ví dụ: tôi muốn nạp liên kết chuyển trang đến tầng điều hướng (controller) "sanpham" gọi phương thức (action) "nap" có class là "vietFrameWork" thì tôi ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napLienKet("chuỗi hiễn thị trong cặp thẻ <a>", array("Điều Hướng" => "sanpham", "Phương Thức" => "nap"), array("class"=>"vietFrameWork"));"
     * 
     * MỞ RỘNG 2:
     * Phương thức hỗ trợ bạn thuộc tính "Xác Nhận" để hiển thị hộp thoại javascript (confirm) lên màn hình để xác nhận có muốn thực thi chuyển trang hay không
     * 
     * Ví dụ: tôi muốn nạp liên kết chuyển trang đến http://google.com và muốn xác nhận khi nhấn vào thì thì tôi ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napLienKet("chuỗi hiễn thị trong cặp thẻ <a>", "http://google.com", array("Xác Nhận" => "Câu thông báo của bạn"));"
     *
     * @param string $tenLienKetHienThi (tham trị kiểu chuỗi là nội dung nằm trong thẻ <a> có thể là hình ảnh cũng có thể là chuỗi hiển thị tùy ở bạn)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napLienKet($tenLienKetHienThi = null) {
        if (is_string($tenLienKetHienThi) || is_numeric($tenLienKetHienThi)) {
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $chuoiLienKet = "<a ";
            foreach ($dayThamTri as $v) {
                $chuoiLienKet .= $this->xuLyThanhPhanBenTrong($v, array("Phương Thức", "Điều Hướng"));
            }
            $lienKet = "href='" . self::_xuLyDuongDanTuYeuCau($dayThamTri) . "'";
            $chuoiLienKet .= $lienKet . ">" . $tenLienKetHienThi . "</a>";
            return $chuoiLienKet;
        }
        return "Lỗi cú pháp napLienKet vui lòng xem lại !";
    }

    /**
     * Phương thức nạp phim ảnh
     * 
     * CHỨC NĂNG:
     * Nạp phim ảnh (video) lên tệp mẫu trang (layout) hoặc tệp hiển thị
     * 
     * Ví dụ: tôi muốn nạp 1 đoạn phim ảnh (video) có tên là "phim.mp4" lên trang thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi "echo $this->napPhimAnh("phim.mp4")"
     * 
     * MỞ RỘNG:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <video>
     * 
     * Ví dụ: tôi muốn nạp 1 đoạn phim ảnh (video) có tên là "phim.mp4" và thuộc tính width là 300 height là 300 lên trang thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi "echo $this->napPhimAnh("phim.mp4", array("width" => "300", "height" => "300))"
     * 
     * @param string $duongDanPhim (tham trị truyền vào là tên tệp phim kiểu dữ liệu chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napPhimAnh($duongDanPhim = null) {
        if (is_string($duongDanPhim)) {
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANPHIM : DUONGDANHOTRO . $this->thuMucChinh . "/phimanh/";
            $chuoiPhimAnh = "<video controls ";
            foreach ($dayThamTri as $v) {
                $chuoiPhimAnh .= $this->xuLyThanhPhanBenTrong($v, array("controls", "Xác Nhận"));
            }
            $chuoiPhimAnh .= ">";
            $layDuoiPhim = end(explode(".", $duongDanPhim));
            if (strlen($layDuoiPhim) > 0) {
                $chuoiPhimAnh .= "<source src='" . $thuMucChinh . $duongDanPhim . "' type='video/" . $layDuoiPhim . "'>";
                $chuoiPhimAnh .= "</video>";
                return $chuoiPhimAnh;
            } else {
                return "Không xác định được loại tệp PHIM";
            }
        }
        return "Lỗi cú pháp napPhimAnh vui lòng xem lại !";
    }

    /**
     * Phương thức nạp âm thanh
     * 
     * CHỨC NĂNG:
     * Nạp âm thanh (audio) lên tệp mẫu trang (layout) hoặc tệp hiển thị
     * 
     * Ví dụ: tôi muốn nạp 1 đoạn âm thanh (audio) có tên là "nhac.mp3" lên trang thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi "echo $this->napAmThanh("nhac.mp3")"
     * 
     * MỞ RỘNG:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <audio>
     * 
     * Ví dụ: tôi muốn nạp 1 đoạn âm thanh (audio) có tên là "nhac.mp3" và thuộc tính width là 300 height là 300 lên trang thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi "echo $this->napAmThanh("nhac.mp3", array("width" => "300", "height" => "300))"
     * 
     * @param string $duongDanAmThanh (tham trị truyền vào là tên tệp âm thanh kiểu dữ liệu chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napAmThanh($duongDanAmThanh) {
        if (is_string($duongDanAmThanh)) {
            $dayThamTri = func_get_args();
            $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANAMTHANH : DUONGDANHOTRO . $this->thuMucChinh . "/amthanh/";
            unset($dayThamTri[0]);
            $chuoiAmThanh = "<audio controls ";
            foreach ($dayThamTri as $v) {
                $chuoiAmThanh .= $this->xuLyThanhPhanBenTrong($v, array("controls", "Xác Nhận"));
            }
            $chuoiAmThanh .= ">";
            $layDuoiAmThanh = end(explode(".", $duongDanAmThanh));
            if (strlen($layDuoiAmThanh) > 0) {
                $chuoiAmThanh .= "<source src='" . $thuMucChinh . $duongDanAmThanh . "' type='audio/" . $layDuoiAmThanh . "'>";
                $chuoiAmThanh .= "</audio>";
                return $chuoiAmThanh;
            } else {
                return "Không xác định được loại tệp ÂM THANH";
            }
        }
        return "Lỗi cú pháp napAmThanh vui lòng xem lại !";
    }

    /**
     * Phương thức nạp biểu mẫu
     * 
     * CHỨC NĂNG:
     * Tạo biểu mẫu (FORM) hổ trợ tương tác với dữ liệu gửi về trên biểu mẫu hoặc dữ liệu gửi đi từ server
     * 
     * Tên biểu mẫu cũng tương đương với tên bảng cơ sở dữ liệu
     * 
     * Ví dụ: tôi muốn tạo một biểu mẫu (FORM) để nạp dữ liệu lên bảng "sanpham" thì ở tệp mẫu trang (layout) hoặc hiển thị tôi gọi như sau "echo $this->napBieuMau("sanpham")"
     * 
     * MỞ RỘNG 1:
     * Mặc định nếu bạn không yêu cầu đường dẫn liên kết mà biểu mẫu gửi dữ liệu về thì hệ thống sẽ tự hiệu bạn sẽ gửi dữ liệu đến phương thức (action) và điều hướng (controller) hiện tại
     * 
     * Ví dụ: tôi đang ở điều hướng (controller) "sanpham" và phương thức (action) "them" và tại đây tôi có 1 biểu mẫu (FORM) hiển thị trên trình duyệt và trong hàm tạo biểu mẫu này tôi không yêu cầu đường dẫn chỉ định thì mặc nhiên nó sẽ trỏ đến điều hướng "sanpham" và phương thức "them"
     * 
     * Để quy định đường dẫn liên kết mà biểu mẫu gửi dữ liệu về thì ta gọi như sau:
     * 
     * Ví dụ: tôi muốn dữ liệu biểu mẫu gửi về vào điều hướng (controller) "taikhoan" phương thức (action) "dangky" thì ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napBieuMau("tên bảng csdl", array("Điều Hướng" => "taikhoan", "Phương Thức" => "Đăng Ký"))"
     * 
     * MỞ RỘNG 2:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <form>
     * 
     * Ví dụ: tôi muốn nạp 1 biểu mẫu (form) có class là "vietFrameWork" thao tác với bảng sanpham và gửi dữ liệu vào điều hướng (controller) "sanpham" phương thức (action) "sua" thì ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau: "echo $this->napBieuMau("sanpham", array("Điều Hướng" => "taikhoan", "Phương Thức" => "Đăng Ký"), array("class" => "vietFrameWork))"
     * 
     * @param string $tenBieuMau (tham trị truyền vào là tên bảng cơ sở dữ liệu kiểu chuỗi)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napBieuMau($tenBieuMau = null) {
        if (is_string($tenBieuMau) || is_numeric($tenBieuMau)) {
            $this->tenBieuMau = $tenBieuMau;
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $chuoiBieuMau = "<form accept-charset='utf-8' ";
            foreach ($dayThamTri as $v) {
                $chuoiBieuMau .= $this->xuLyThanhPhanBenTrong($v, array("action", "Phương Thức", "Điều Hướng", "Xác Nhận"));
            }
            if (!stristr($chuoiBieuMau, "method=")) {
                $chuoiBieuMau .= "method='POST' ";
            }
            $bieuMauGuiVe = "action='" . self::_xuLyDuongDanTuYeuCau($dayThamTri) . "'";
            $chuoiBieuMau .= $bieuMauGuiVe . ">";
            return $chuoiBieuMau;
        }
        return "Lỗi cú pháp napBieuMau vui lòng xem lại !";
    }

    /**
     * Phương thức đóng biểu mẫu
     * 
     * CHỨC NĂNG:
     * Đóng biểu mẫu sau khi kết thúc các thành phần biểu mẫu (form)
     * 
     * Ví dụ: ở tệp mẫu trang (layout) hoặc tệp hiển thị sau khi tôi gọi phương thức "echo $this->napBieuMau("tenBangCSDL")" và viết các thuộc tính trên biễu mẫu (form) để kết thúc nó thì tôi gọi "echo $this->dongBieuMau()"
     * 
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function dongBieuMau() {
        $this->tenBieuMau = "dulieu";
        return "</form>";
    }

    /**
     * Phương thức nạp nhập liệu
     * 
     * CHỨC NĂNG:
     * Phương thức này tương đương với bạn đang thao tác với thành phần thẻ <input>
     * 
     * Ví dụ: tôi muốn nạp một nhập liệu lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tôi gọi như sau "echo $this->napNhapLieu("tên nhập liệu");"
     * 
     * Lưu ý: trong các biễu mẫu (form) tương tác với CSDL thì tên nhập liệu cũng chính là tên cột trên CSDL (database). Ví dụ trên bảng sanpham tôi có cột tensanpham thì sau khi tạo biểu mẫu có tên là sanpham thì tôi nạp nhập liệu như sau "echo $this->napNhapLieu("tensanpham");" nhằm hỗ trợ hệ thống tương tác với CSDL (database)
     * 
     * MỞ RỘNG 1:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <input>
     * 
     * Ví dụ: tôi muốn thiết lập "type" cho thẻ <input> là "password" và có class là "vietFrameWork" thì ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napNhapLieu("tên nhập liệu", array("type" => "password", "class" => "vietFrameWork"));"
     * 
     * MỞ RỘNG 2:
     * Đối với các "type" có dạng là "button" như "submit", "button", "reset" thì trong mảng thuộc tính có hỗ trợ ta một thuộc tính "Xác Nhận" nhằm xác nhận lại thao tác người dùng có chắc chắn muốn ấn vào không.
     * 
     * Ví dụ: tôi có một nút "submit" để gửi dữ liệu về server để xóa và tôi muốn chắc chắn lại việc xác thức của người dùng thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napNhapLieu("tên nhập liệu", array("type" => "submit", "Xác Nhận", => "câu thông báo"))"
     * 
     * @param string $tenThanhPhanNhapLieu (tham trị thứ 1 truyền vào dạng chuỗi là tên (thuộc tính attrib "name") của thành phần nhập liệu)
     * @param array $mangThuocTinh (tham trị thứ 2 truyền vào dạng mảng là mảng thuộc tính (attrib))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napNhapLieu($tenThanhPhanNhapLieu = null, $mangThuocTinh = array()) {
        if (is_string($tenThanhPhanNhapLieu) || is_numeric($tenThanhPhanNhapLieu)) {
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $kiemTraLoi = $this->kiemTraLoiThanhPhanBieuMau($tenThanhPhanNhapLieu);
            $chuoiNhapLieu = "<input ";
            $dangNhapLieu = "";
            $giaTriNhapLieu = "";
            $thongBaoLoi = true;
            foreach ($dayThamTri as $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if (is_string($k1)) {
                            if ((strtolower($k1) === "type")) {
                                $dangNhapLieu = $v1;
                            } elseif (strtolower($k1) === "value") {
                                $giaTriNhapLieu = $v1;
                            } elseif ($k1 === "Báo Lỗi" && $v1 === false) {
                                $thongBaoLoi = false;
                            }
                        }
                    }
                }
                $chuoiNhapLieu .= $this->xuLyThanhPhanBenTrong($v, array("name", "Báo Lỗi"));
            }
            $mangTenThanhPhanNhapLieu = explode(".", $tenThanhPhanNhapLieu);
            $tenNhapLieu = $this->tenBieuMau;
            foreach ($mangTenThanhPhanNhapLieu as $v) {
                $tenNhapLieu .= "[" . $v . "]";
            }
            $chuoiNhapLieu .= "name='" . $tenNhapLieu . "' ";
            if ($dangNhapLieu == "radio" || $dangNhapLieu == "checkbox") {
                $chuoiNhapLieu .= $this->doDuLieuLenThanhPhanBieuMau($tenThanhPhanNhapLieu, "checked", $giaTriNhapLieu);
            } else {
                $chuoiNhapLieu .= $this->doDuLieuLenThanhPhanBieuMau($tenThanhPhanNhapLieu);
            }
            if (!is_bool($kiemTraLoi)) {
                $chuoiNhapLieu .= "required ";
            }
            $chuoiNhapLieu .= " />";
            if (!is_bool($kiemTraLoi) && $kiemTraLoi != "" && $thongBaoLoi) {
                $chuoiNhapLieu .= "<br/><strong id='cau-bao-loi-bieu-mau'>" . $kiemTraLoi . "</strong>";
            }
            return $chuoiNhapLieu;
        }
        return "Lỗi cú pháp napNhapLieu vui lòng xem lại !";
    }

    /**
     * Phương thức nạp bảng nhập liệu
     * 
     * CHỨC NĂNG:
     * Phương thức này tương đương với bạn đang thao tác với thành phần thẻ <textarea>
     * 
     * Ví dụ: tôi muốn nạp một bảng nhập liệu lên tệp mẫu trang (layout) hoặc tệp hiển thị thì ở 1 trong 2 tệp này tôi gọi như sau "echo $this->napBangNhapLieu("tên bảng nhập liệu");"
     * 
     * Lưu ý: trong các biễu mẫu (form) tương tác với CSDL thì tên bảng nhập liệu cũng chính là tên cột trên CSDL (database). Ví dụ trên bảng sanpham tôi có cột chitietsanpham thì sau khi tạo biểu mẫu có tên là sanpham thì tôi nạp bảng nhập liệu như sau "echo $this->napBangNhapLieu("chitietsanpham");" nhằm hỗ trợ hệ thống tương tác với CSDL (database)
     * 
     * MỞ RỘNG:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <textarea>
     * 
     * Ví dụ: tôi muốn thiết lập 3 hàng (rows) 5 cột (cols) cho nội dung thẻ <textarea> có class là "vietFrameWork" thì ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napBangNhapLieu("tên bảng nhập liệu", array("cols" => "5", "rows" => "3", "class" => "vietFrameWork"));"
     * 
     * @param string $tenBangNhapLieu (tham trị thứ 1 truyền vào dạng chuỗi là tên (thuộc tính attrib "name") của bảng nhập liệu)
     * @param array $mangThuocTinh (tham trị thứ 2 truyền vào dạng mảng là mảng thuộc tính (attrib))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napBangNhapLieu($tenBangNhapLieu = null, $mangThuocTinh = array()) {
        if (is_string($tenBangNhapLieu) || is_numeric($tenBangNhapLieu)) {
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $kiemTraLoi = $this->kiemTraLoiThanhPhanBieuMau($tenBangNhapLieu);
            $thongBaoLoi = true;
            $chuoiNhapLieu = "<textarea ";
            foreach ($dayThamTri as $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if ($k1 === "Báo Lỗi" && $v1 === false) {
                            $thongBaoLoi = false;
                        }
                    }
                }
                $chuoiNhapLieu .= $this->xuLyThanhPhanBenTrong($v, array("name", "Xác Nhận", "Báo Lỗi"));
            }
            if (!is_bool($kiemTraLoi)) {
                $chuoiNhapLieu .= "required ";
            }
            $mangTenBangNhapLieu = explode(".", $tenBangNhapLieu);
            $tenNhapLieu = $this->tenBieuMau;
            foreach ($mangTenBangNhapLieu as $v) {
                $tenNhapLieu .= "[" . $v . "]";
            }
            $chuoiNhapLieu .= "name='" . $tenNhapLieu . "'>";
            $chuoiNhapLieu .= $this->doDuLieuLenThanhPhanBieuMau($tenBangNhapLieu, "textbox");
            $chuoiNhapLieu .= "</textarea>";
            if (!is_bool($kiemTraLoi) && $kiemTraLoi != "" && $thongBaoLoi) {
                $chuoiNhapLieu .= "<br/><strong id='cau-bao-loi-bieu-mau'>" . $kiemTraLoi . "</strong>";
            }
            return $chuoiNhapLieu;
        }
        return "Lỗi cú pháp napBangNhapLieu vui lòng xem lại !";
    }

    /**
     * Phương thức nạp tùy chọn
     * 
     * CHỨC NĂNG:
     * Phương thức này tương đương với bạn đang thao tác trên thẻ <select> của HTML
     * 
     * Tại phương thức này để thao tác với các thẻ <option> bên trông <select> thì phương thức hỗ trợ chúng ta 1 thuộc tính trong mảng thuộc tính đó là "Mảng Tùy Chọn"
     * 
     * Ví dụ: Tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napTuyChon("tên tùy chọn", array("Mảng Tùy Chọn" => array("giá trị truyền về server" => "giá trị hiển thị trên trình duyệt", ....)))"
     * 
     * Lưu ý: trong các biễu mẫu (form) tương tác với CSDL thì tên tùy chọn cũng chính là tên cột trên CSDL (database). Ví dụ trên bảng taikhoan tôi có cột ngaysinh thì sau khi tạo biểu mẫu có tên là taikhoan thì tôi nạp tùy chọn như sau "echo $this->napTuyChon("ngaysinh", array("Mảng Tùy Chọn" => array("giá trị truyền về server" => "giá trị hiển thị trên trình duyệt", ....)))" nhằm hỗ trợ hệ thống tương tác với CSDL (database)
     * 
     * MỞ RỘNG 1:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <select>
     * 
     * Ví dụ: tôi muốn thiết lập thuộc tính class là "vietFrameWork" cho thẻ select thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napTuyChon("tên tùy chọn", array("Mảng Tùy Chọn" => array("giá trị truyền về server" => "giá trị hiển thị trên trình duyệt", ....)), array("class" => "vietFrameWork"))"
     * 
     * MỞ RỘNG 2:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <option>
     * 
     * Ví dụ tôi muốn thiết lập thuộc tính class là "vietFrameWork" cho thẻ option bên trong thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napTuyChon("tên tùy chọn", array("Mảng Tùy Chọn" => array("giá trị truyền về server" => array("giá trị hiển thị trên trình duyệt",  array("class" => "vietFrameWork")) ....)))"
     * 
     * @param type $tenTuyChon (tham trị thứ 1 truyền vào dạng chuỗi là tên (thuộc tính attrib "name") của tùy chọn)
     * @param array $mangThuocTinh (tham trị thứ 2 truyền vào dạng mảng là mảng thuộc tính (attrib))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napTuyChon($tenTuyChon = null, $mangThuocTinh = array()) {
        if (is_string($tenTuyChon) || is_numeric($tenTuyChon)) {
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            $kiemTraLoi = $this->kiemTraLoiThanhPhanBieuMau($tenTuyChon);
            $thongBaoLoi = true;
            $mangTenTuyChon = explode(".", $tenTuyChon);
            $tenThanhPhanTuyChon = $this->tenBieuMau;
            foreach ($mangTenTuyChon as $v) {
                $tenThanhPhanTuyChon .= "[" . $v . "]";
            }
            $chuoiTuyChon = "<select name='$tenThanhPhanTuyChon' ";
            if (!is_bool($kiemTraLoi)) {
                $chuoiTuyChon .= "required ";
            }
            foreach ($dayThamTri as $v) {
                $chuoiTuyChon .= $this->xuLyThanhPhanBenTrong($v, array("Mảng Tùy Chọn", "name", "Xác Nhận", "Báo Lỗi"));
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if ($k1 === "Báo Lỗi" && $v1 === false) {
                            $thongBaoLoi = false;
                        }
                    }
                }
            }
            $chuoiTuyChon .= ">";
            foreach ($dayThamTri as $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if (is_string($k2)) {
                            if ($k2 === "Mảng Tùy Chọn") {
                                if (is_array($v2)) {
                                    foreach ($v2 as $k3 => $v3) {
                                        $chonTuyChonNay = ($this->doDuLieuLenThanhPhanBieuMau($tenTuyChon, "checked", $k3) != "") ? "selected" : "";
                                        if (!is_array($v3)) {
                                            $chuoiTuyChon .= "<option value='" . $k3 . "' " . $chonTuyChonNay . ">" . $v3 . "</option>";
                                        } elseif (is_array($v3)) {
                                            if (isset($v3[0])) {
                                                if (is_string($v3[0])) {
                                                    $tenHienThiHtml = $v3[0];
                                                    $chuoiTuyChon .= "<option value='" . $k3 . "' " . $chonTuyChonNay . " ";
                                                    $chuoiTuyChon .= $this->xuLyThanhPhanBenTrong($v3, array("Xác Nhận"));
                                                    $chuoiTuyChon .= ">" . $tenHienThiHtml . "</option>";
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
            $chuoiTuyChon .= "</select>";
            if (!is_bool($kiemTraLoi) && $kiemTraLoi != "" && $thongBaoLoi) {
                $chuoiTuyChon .= "<br/><strong id='cau-bao-loi-bieu-mau'>" . $kiemTraLoi . "</strong>";
            }
            return $chuoiTuyChon;
        }
        return "Lỗi cú pháp napTuyChon vui lòng xem lại !";
    }

    /**
     * Phương thức nạp thời gian
     * 
     * CHỨC NĂNG:
     * Để nạp các hộp tùy chọn (select) mang tính chất thể hiện thời gian như: ngày, tháng, năm, giờ, phút, giây
     * 
     * Ví dụ: tôi muốn nạp các hộp tùy chọn mang tính chất thể hiện thời gian ở tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napThoiGian("tên thành phần thời gian");"
     * 
     * Lưu ý: trong các biễu mẫu (form) tương tác với CSDL thì tên thời gian cũng chính là tên cột trên CSDL (database). Ví dụ trên bảng taikhoan tôi có cột ngaysinh thì sau khi tạo biểu mẫu có tên là taikhoan thì tôi nạp tùy chọn như sau "echo $this->napThoiGian("ngaysinh")" nhằm hỗ trợ hệ thống tương tác với CSDL (database)
     * 
     * MỞ RỘNG 1:
     * Ngoài ra bạn có thể tùy chỉnh số lượng hộp thoại hiển thị thời gian.
     * 
     * Ví dụ: tôi chỉ muốn nạp một hộp thoại thời gian chỉ hiện tháng và ngày thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napThoiGian("tên thành phần thời gian", "tháng ngày")"
     * 
     * MỞ RỘNG 2:
     * Ngoài ra phương thức này còn hỗ trợ bạn thiết lập các thuộc tính (attrib) cho thẻ <div> bao bên ngoài các hộp <select>
     * 
     * Ví dụ: tôi muốn thiết lập thuộc tính class có giá trị là "vietFrameWork" cho khung chứa chung thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napThoiGian("tên thành phần thời gian", "ngày tháng năm giờ phút giây", array("class" => "vietFrameWork"));"
     * 
     * @param string $tenTruongTG (tham trị thứ 1 truyền vào dạng chuỗi là tên (thuộc tính attrib "name") của thành phần thời gian)
     * @param string $kieuTG (tham trị thứ 2 truyền vào dạng chuỗi thể hiện các hộp thời gian muốn hiện lên mặc định là ngày tháng năm)
     * @param array $mangThuocTinh (tham trị thứ 3 truyền vào dạng mảng là mảng thuộc tính (attrib))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napThoiGian($tenTruongTG = null, $kieuTG = "Ngày Tháng Năm", $mangThuocTinh = array()) {
        if (is_string($tenTruongTG) || is_numeric($tenTruongTG)) {
            $kiemTraLoi = $this->kiemTraLoiThanhPhanBieuMau($tenTruongTG);
            $thongBaoLoi = true;
            $dayThamTri = func_get_args();
            unset($dayThamTri[0]);
            unset($dayThamTri[1]);
            $xuLyThoiGian = trim($kieuTG);
            $xuLyThoiGian = mb_strtolower($xuLyThoiGian, "utf-8");
            $mangXuLyTG = explode(" ", $xuLyThoiGian);
            $chuoiThoiGian = "<div ";
            $mangNam = array();
            foreach ($dayThamTri as $v) {
                $chuoiThoiGian .= $this->xuLyThanhPhanBenTrong($v, array("name", "Xác Nhận", "Mảng Năm", "Báo Lỗi"));
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if ($k1 == "Mảng Năm") {
                            if (is_array($v1)) {
                                $mangNam = $v1;
                            }
                        } elseif ($k1 === "Báo Lỗi" && $v1 === false) {
                            $thongBaoLoi = false;
                        }
                    }
                }
            }
            $chuoiThoiGian .= ">";
            $chuoiHopThoiGian = "";
            $mangTenThoiGian = explode(".", $tenTruongTG);
            $tenThanhPhanThoiGian = $this->tenBieuMau;
            foreach ($mangTenThoiGian as $v) {
                $tenThanhPhanThoiGian .= "[" . $v . "]";
            }
            foreach ($mangXuLyTG as $phanTuTG) {
                $chuoiYeuCau = ($kiemTraLoi !== false) ? "required" : "";
                switch ($phanTuTG) {
                    case "ngày":
                        $tenDoiTuongNgay = $tenThanhPhanThoiGian . "[Ngay]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Ngay' name='$tenDoiTuongNgay' $chuoiYeuCau>";
                        for ($i = 1; $i <= 31; $i++) {
                            $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Ngay")) === $i) ? "selected" : "";
                            $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>" . $i . "</option>";
                        }
                        $chuoiHopThoiGian .= "</select> - ";
                        break;
                    case "tháng":
                        $tenDoiTuongThang = $tenThanhPhanThoiGian . "[Thang]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Thang' name='$tenDoiTuongThang' $chuoiYeuCau>";
                        $mangTenThang = array("một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín", "mười", "mười một", "mười hai");
                        for ($i = 1; $i <= 12; $i++) {
                            $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Thang")) === $i) ? "selected" : "";
                            $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>Tháng " . $mangTenThang[$i - 1] . "</option>";
                        }
                        $chuoiHopThoiGian .= "</select> - ";
                        break;
                    case "năm":
                        $tenDoiTuongNam = $tenThanhPhanThoiGian . "[Nam]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Nam' name='$tenDoiTuongNam' $chuoiYeuCau>";
                        $namHienTai = intval(date('Y'));
                        if (empty($mangNam)) {
                            for ($i = ($namHienTai - 100); $i <= $namHienTai; $i++) {
                                $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Nam")) === $i) ? "selected" : "";
                                $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>" . $i . "</option>";
                            }
                        } else {
                            foreach ($mangNam as $k => $v) {
                                $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Nam")) === $k) ? "selected" : "";
                                $chuoiHopThoiGian .= "<option value='$k' $chonTuyChonNay>" . $v . "</option>";
                            }
                        }
                        $chuoiHopThoiGian .= "</select> - ";
                        break;
                    case "giờ":
                        $tenDoiTuongGio = $tenThanhPhanThoiGian . "[Gio]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Gio' name='$tenDoiTuongGio' $chuoiYeuCau>";
                        for ($i = 1; $i <= 24; $i++) {
                            $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Gio")) === $i) ? "selected" : "";
                            $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>" . $i . "</option>";
                        }
                        $chuoiHopThoiGian .= "</select> : ";
                        break;
                    case "phút":
                        $tenDoiTuongPhut = $tenThanhPhanThoiGian . "[Phut]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Phut' name='$tenDoiTuongPhut' $chuoiYeuCau>";
                        for ($i = 1; $i <= 60; $i++) {
                            $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Phut")) === $i) ? "selected" : "";
                            $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>" . $i . "</option>";
                        }
                        $chuoiHopThoiGian .= "</select> : ";
                        break;
                    case "giây":
                        $tenDoiTuongGiay = $tenThanhPhanThoiGian . "[Giay]";
                        $chuoiHopThoiGian .= "<select id='" . $this->tenBieuMau . implode("", $mangTenThoiGian) . "Giay' name='$tenDoiTuongGiay' $chuoiYeuCau>";
                        for ($i = 1; $i <= 60; $i++) {
                            $chonTuyChonNay = (intval($this->doDuLieuLenThanhPhanBieuMau($tenTruongTG, "checked", "Giay")) === $i) ? "selected" : "";
                            $chuoiHopThoiGian .= "<option value='$i' $chonTuyChonNay>" . $i . "</option>";
                        }
                        $chuoiHopThoiGian .= "</select> : ";
                        break;
                    default:
                        return "Lỗi cú pháp vui lòng xem lại !";
                }
            }
            $chuoiHopThoiGian = rtrim($chuoiHopThoiGian, " : ");
            $chuoiHopThoiGian = rtrim($chuoiHopThoiGian, " - ");
            $chuoiThoiGian .= $chuoiHopThoiGian;
            if (!is_bool($kiemTraLoi) && $kiemTraLoi != "" && $thongBaoLoi) {
                $chuoiThoiGian .= "<br/><strong id='cau-bao-loi-bieu-mau'>" . $kiemTraLoi . "</strong>";
            }
            $chuoiThoiGian .= "</div>";
            return $chuoiThoiGian;
        }
        return "Lỗi cú pháp napThoiGian vui lòng xem lại !";
    }

    /**
     * Phương thức nạp mã bảo vệ
     * 
     * CHỨC NĂNG:
     * Để nạp mã bảo vệ hay còn gọi là mã xác nhận (captcha). Múc đích hỗ trợ việc validate chống spam hệ thống
     * 
     * Ví dụ: tôi muốn nạp một mã bảo vệ có số kí tự từ 4 - 6 có chiều rộng là 140px chiều cao là 40px thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napMaBaoVe("tên thành phần mã bảo vệ", rand(4,6), 140, 40)"
     * 
     * Các giá trị mặc định của các tham trị: số kí tự 4, chiều rộng 120, chiều cao 40
     * 
     * MỞ RỘNG:
     * Ngoài ra bạn có thể thiết lập các thuộc tính (attrib) cho thẻ <div> bao bên ngoài toàn bộ thành phần của mã bảo vệ
     * 
     * Ví dụ: tôi muốn nạp một mã bảo về có class là "vietFrameWork" thì tại tệp mẫu trang (layout) hoặc tệp hiển thị tôi gọi như sau "echo $this->napMaBaoVe("tên thành phần mã bảo vệ", rand(4,6), 140, 40, array("class" => "vietFrameWork"))"
     * 
     * @param string $tenTruongMaXacNhan (tham trị thứ 1 truyền vào dạng chuỗi là tên (thuộc tính attrib "name") của thành phần mã bảo vệ)
     * @param int $kiTu (tham trị thứ 2 truyền vào dạng số nguyên là số kí tự hiển thị trong hộp mã bảo vệ (captcha))
     * @param int $chieuRong (tham trị thứ 3 truyền vào dạng số nguyên là chiều rộng của hộp mã bảo vệ)
     * @param int $chieuCao (tham trị thứ 4 truyền vào dạng số nguyên là chiều cao của hộp mã bảo vệ)
     * @param array $mangThuocTinh (tham trị thứ 5 truyền vào dạng mảng là mảng thuộc tính (attrib))
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function napMaBaoVe($tenTruongMaXacNhan = null, $kiTu = 4, $chieuRong = 120, $chieuCao = 40, $mangThuocTinh = null) {
        if (is_numeric($chieuRong) && is_numeric($chieuCao) && is_numeric($kiTu) && (is_string($tenTruongMaXacNhan) || is_numeric($tenTruongMaXacNhan))) {
            $duongDanTepMaBaoVe = DUONGDANTHUMUCTRANG . "hotro" . DS . "hinhanh" . DS . "vietFrameWork" . DS . "mabaove.php";
            $dayThamTri = func_get_args();
            for ($i = 0; $i < 4; $i++) {
                unset($dayThamTri[$i]);
            }
            $thuMucChinh = empty($this->thuMucChinh) ? DUONGDANHINH : DUONGDANHOTRO . $this->thuMucChinh . "/hinhanh/";
            if (file_exists($duongDanTepMaBaoVe)) {
                $chuoiMaBaoVe = "<div ";
                $maBaoVe = $thuMucChinh . "vietFrameWork/mabaove.php?chieuRong=" . $chieuRong . "&chieuCao=" . $chieuCao . "&kiTu=" . $kiTu . "&tenTruongMaXacNhan=" . $tenTruongMaXacNhan . "&tenDoiTuong=" . $this->tenBieuMau;
                foreach ($dayThamTri as $v) {
                    $chuoiMaBaoVe .= $this->xuLyThanhPhanBenTrong($v, array("Xác Nhận"));
                }
                $chuoiMaBaoVe .= "><img src='" . $maBaoVe . "' />";
                $chuoiMaBaoVe .= "<img src='" . $thuMucChinh . "vietFrameWork/naplaimabaove.png'" . " style='cursor:pointer' onclick='napLaiMaBaoVe(this)' height='" . $chieuCao . "px'/></div>";
                $chuoiMaBaoVe .= $this->napJavaScript("jquery-1.11.1.min", "vietFrameWork/napLaiMaBaoVe");
                return $chuoiMaBaoVe;
            } else {
                return trigger_error("Thiếu tệp nền (source): " . $duongDanTepMaBaoVe . " .Bạn có thể lấy lại tệp đó ở source gốc", E_NOTICE);
            }
        } else {
            return "Lỗi cú pháp napMaBaoVe vui lòng xem lại !";
        }
    }

    /**
     * Phương thức xử lý thành phần bên trong
     * 
     * CHỨC NĂNG:
     * Xử lý các thuộc tính (attrib) của các thẻ HTML
     * 
     * @param array $dayThamTri (tham trị thứ 1 truyền vào dạng mảng là dãy truyền vào để xử lý thành phần)
     * @param array $mangNgoaiLe (tham trị thứ 2 truyền vào dạng mảng là dãy xử lý ngoại lệ tức là khi gặp thành phần có tên trùng với các phần tử trong mảng này thì bỏ qua không xử lý)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public function xuLyThanhPhanBenTrong($dayThamTri = array(), $mangNgoaiLe = array()) {
        $ghepChuoi = "";
        if (is_array($dayThamTri)) {
            foreach ($dayThamTri as $k1 => $v1) {
                if (is_string($k1)) {
                    $dieuKienHopLe = true;
                    foreach ($mangNgoaiLe as $v) {
                        if ($k1 == $v) {
                            $dieuKienHopLe = false;
                        }
                    }
                    if ($dieuKienHopLe) {
                        if ($k1 == "Xác Nhận") {
                            $ghepChuoi .= "onclick='return confirm(" . '"' . $v1 . '"' . ")' ";
                        } else {
                            $ghepChuoi .= $k1 . "='" . $v1 . "'" . " ";
                        }
                    }
                }
            }
        }
        return $ghepChuoi;
    }

    /**
     * Phương thức kiểm tra lỗi thành phần biểu mẫu
     * 
     * CHỨC NĂNG:
     * Dùng để kiểm tra lỗi thành phần biểu mẫu gửi về từ tầng xử lý (model) khi thực hiện các chức năng thêm, sửa (insert, update)
     * 
     * @param string $tenThanhPhanBieuMau (tham trị truyền vào dạng chuỗi là tên thành phần nằm trên biểu mẫu)
     * @return mixed (kiễu dữ liệu trả về dạng chuỗi báo lỗi (khi có lỗi xảy ra) dạng sai (FALSE) khi không có lỗi) 
     */
    private function kiemTraLoiThanhPhanBieuMau($tenThanhPhanBieuMau) {
        if (isset($this->mangBaoLoi[$this->tenBieuMau])) {
            $mangBaoLoi = $this->mangBaoLoi[$this->tenBieuMau];
            if (isset($mangBaoLoi[$tenThanhPhanBieuMau])) {
                return $mangBaoLoi[$tenThanhPhanBieuMau];
            }
        }
        return false;
    }

    /**
     * Phương thức đỗ dữ liệu lên thành phần biểu mẫu
     * 
     * CHỨC NĂNG:
     * Đỗ dữ liệu lên thành phần biễu mẩu thông qua mảng thuộc tính duLieuBieuMauGuiDi được truyền về từ tầng điều hướng (controller)
     * 
     * @param string $tenThanhPhanBieuMau (tham trị thứ 1 truyền vào dạng chuỗi là tên thành phần biểu mẫu trên biểu mẫu (form))
     * @param string $dang (tham trị thứ 2 truyền vào dạng chuỗi để xác định dạng thành phần biểu mẫu)
     * @param string $chonLua (tham trị thứ 3 truyền vào để xác định xem đó có phải là radio, checkbox, select)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    private function doDuLieuLenThanhPhanBieuMau($tenThanhPhanBieuMau, $dang = "input", $chonLua = "") {
        $duLieuGuiDi = $this->duLieuBieuMauGuiDi;
        $chuoiTraVe = "";
        if (is_array($duLieuGuiDi)) {
            foreach ($duLieuGuiDi as $k => $v) {
                if ($k == $this->tenBieuMau) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if (is_string($k2)) {
                                if ($k2 == $tenThanhPhanBieuMau) {
                                    if ($chonLua != "") {
                                        if ($chonLua == $v2 && !is_array($v2)) {
                                            $chuoiTraVe .= "checked";
                                        } elseif (is_array($v2)) {
                                            if (isset($v2[$chonLua])) {
                                                $chuoiTraVe .= $v2[$chonLua];
                                            }
                                        }
                                    } else {
                                        if ($dang == "input") {
                                            $chuoiTraVe .= "value='" . $v2 . "'";
                                        } elseif ($dang == "textbox") {
                                            $chuoiTraVe .= $v2;
                                        }
                                    }
                                    return $chuoiTraVe;
                                }
                            }
                        }
                    }
                }
            }
        }
        return "";
    }

    /**
     * Phương thức xử lý đường dẫn yêu cầu
     * 
     * CHỨC NĂNG:
     * Xử lý đường dẫn yêu cầu trong hệ thống và ngoài hệ thống
     * 
     * Ví dụ: khi bạn muốn xử lý đường dẫn yêu cầu trong hệ thống muốn trỏ về tầng điều hướng (controller) sanpham và phương thức (action) them thì bạn gọi như sau
     * 
     * Đối với xử lý cùng lớp đối tượng html "self::_xuLyDuongDanTuYeuCau(array(array("Điều Hướng" => "sanpham", "Phương Thức" => "them")))"
     * 
     * Đối với xử lý khác lớp đối tượng html "doiTuongHtml::_xuLyDuongDanTuYeuCau(array(array("Điều Hướng" => "sanpham", "Phương Thức" => "them")))"
     * 
     * Ví dụ: khi bạn muốn xử lý đường dẫn yêu cầu trong hệ thống muốn trỏ về "http://google.com" thì bạn gọi như sau
     * 
     * Đối với xử lý cùng lớp đối tượng html "self::_xuLyDuongDanTuYeuCau(array("http://google.com"))"
     * 
     * Đối với xử lý khác lớp đối tượng html "doiTuongHtml::_xuLyDuongDanTuYeuCau(array("http://google.com"))"
     * 
     * @param array $dayThamTri (tham trị truyền vào dạng mảng là các mảng hoặc chuỗi đường dẫn cần xử lý)
     * @return string (kiễu dữ liệu trả về dạng chuỗi)
     */
    public static function _xuLyDuongDanTuYeuCau($dayThamTri = array()) {
        $dieuHuong = dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Điều Hướng"];
        $trangThaiLuuDieuHuong = false;
        $phuongThuc = dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Phương Thức"];
        $trangThaiLuuPhuongThuc = false;
        $thamTri = implode("/", dieuHuongCha::$dieuHuongPhuongThucVaThamTri['Tham Trị']);
        $trangThaiLuuThamTri = false;
        $chuoiLienKet = "";
        if (is_array($dayThamTri)) {
            foreach ($dayThamTri as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        if (is_string($k1)) {
                            if ($k1 == "Điều Hướng" && !$trangThaiLuuDieuHuong) {
                                $dieuHuong = $v1;
                                $phuongThuc = "";
                                $thamTri = "";
                                $trangThaiLuuDieuHuong = true;
                            } elseif ($k1 == "Phương Thức" && !$trangThaiLuuPhuongThuc) {
                                $phuongThuc = $v1;
                                $thamTri = "";
                                $trangThaiLuuPhuongThuc = true;
                                if (isset($dayThamTri[$k][0])) {
                                    $trangThaiLuuThamTri = true;
                                    if (!is_array($dayThamTri[$k][0])) {
                                        $thamTri .= $dayThamTri[$k][0] . "/";
                                    } else {
                                        foreach ($dayThamTri[$k][0] as $thamTriPhuongThuc) {
                                            $thamTri .= $thamTriPhuongThuc . "/";
                                        }
                                    }
                                }
                            }
                        } else {
                            if (!$trangThaiLuuThamTri) {
                                $chuoiLienKet .= ($v1 != "") ? $v1 . "/" : "";
                            }
                        }
                    }
                    if ($chuoiLienKet != "") {
                        $dieuHuong .= ($dieuHuong != "") ? "/" : "";
                        $phuongThuc .= ($phuongThuc != "") ? "/" : "";
                        $chuoiLienKet = DUONGDANTRANG . trim(preg_replace("/(\/)+/", "/", $dieuHuong . $phuongThuc . $chuoiLienKet), "/");
                        break;
                    }
                } elseif (is_string($v)) {
                    if (!filter_var($v, FILTER_VALIDATE_URL)) {
                        $dieuHuong .= ($dieuHuong != "") ? "/" : "";
                        $phuongThuc .= ($phuongThuc != "") ? "/" : "";
                        $thamTri .= ($thamTri != "") ? "/" : "";
                        $chuoiLienKet = DUONGDANTRANG . trim(preg_replace("/(\/)+/", "/", $dieuHuong . $phuongThuc . $thamTri . $v), "/");
                    } else {
                        return $v;
                    }
                    break;
                }
            }
        }
        if (is_array(thietLapCha::$cauHinhDuongDanTruyCap)) {
            foreach (thietLapCha::$cauHinhDuongDanTruyCap as $k => $duongDanYeuCau) {
                $dieuHuongYeuCau = isset($duongDanYeuCau['Điều Hướng']) ? $duongDanYeuCau['Điều Hướng'] : "macdinh";
                $phuongThucYeuCau = isset($duongDanYeuCau['Phương Thức']) ? $duongDanYeuCau['Phương Thức'] : "trangchinh";
                if ($dieuHuongYeuCau == $dieuHuong && $phuongThucYeuCau == $phuongThuc) {
                    $chuoiLienKet = trim($k, "/");
                    $chuoiLienKet = rtrim($chuoiLienKet, "*");
                    $chuoiLienKet .= ($chuoiLienKet != "") ? "/" : "";
                    return DUONGDANTRANG . trim(preg_replace("/(\/)+/", "/", $chuoiLienKet . $thamTri), "/");
                }
            }
        }
        if ($chuoiLienKet != "") {
            return $chuoiLienKet;
        }
        $dieuHuong .= ($dieuHuong != "") ? "/" : "";
        $phuongThuc .= ($phuongThuc != "") ? "/" : "";
        return DUONGDANTRANG . trim(preg_replace("/(\/)+/", "/", $dieuHuong . $phuongThuc . $thamTri), "/");
    }

}
