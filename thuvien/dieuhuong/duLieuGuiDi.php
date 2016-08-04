<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                17/06/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng duLieuGuiDi chứa các thuộc tính và phương thức xử lý các dữ liệu gửi đi từ server
 * 
 * CHỨC NĂNG CHÍNH:
 * Xử lý các dữ liệu gửi đi ở tầng (VIEW):
 * Mẫu trang (layout)
 * Tệp hiển thị
 * Mảng báo lỗi
 * Mảng biến thiết lập
 * Xử lý câu thông báo v..v
 * 
 */

class duLieuGuiDi {

    /**
     * Thuộc tính thư mục chính
     * 
     * Quy định thư mục bao trùm tất cả các thư mục khác trong thư mục hiển thị VIEW (main path) nhằm tạo nên hệ thống quy chuẩn dành cho ai có nhu cầu sử dụng đa nền tảng hiển thị
     * 
     * Nếu không có tương tác gía trị mặc định của nó là rỗng
     * 
     * Ví dụ tôi muốn toàn bộ cấu trúc thư mục nằm trong thư mục tên abc thì tôi thiết lập thuộc tính này thông qua dữ liệu gửi đi là abc
     * 
     * @var string (kiểu dữ liệu chuỗi) 
     */
    public $thuMucChinh = "";

    /**
     * Mảng dữ liệu biểu mẫu gửi đi có các phần tử mang giá trị dữ liệu biểu mẫu gửi đi đến biểu mẫu hiển thị (VIEW)
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $duLieuBieuMauGuiDi = array();

    /**
     * Thuộc tính bộ nhớ đệm giúp lưu lại dữ liệu HTML xuất ra tại tầng VIEW
     * 
     * Mặc định thuộc tính này sẽ là false (tức là không tự động lưu bộ nhớ đệm nếu bạn không yêu cầu).
     * 
     * @var mixed (kiểu dữ liệu dạng ĐÚNG (TRUE) tức là sẽ lưu bộ nhớ đệm theo thời gian đáo hạn mặc định chỉnh tại thiết lập bộ nhớ đệm nếu kiểu dữ liệu dạng số nguyên thì tương ứng với số giây đáo hạn) 
     */
    public $boNhoDem = false;

    /**
     * Thuộc tính nạp trang hiển thị mang giá trị đúng (TRUE) hoặc sai (FALSE)
     * 
     * Mặc định thuộc tính này sẽ đúng nếu bạn thiết lập nó sai (FALSE) thì hệ thống sẽ không load trang hiển thị lên
     * 
     * Gợi ý: chỉ set thuộc tính này là false đối với các site không có tệp hiện thị như tính năng xóa chẳn hạn (delete)
     * 
     * @var boolean (kiểu dữ liệu đúng hoặc sai)
     */
    public $napTrangHienThi = true;

    /**
     * Thuộc tính mẫu trang hiển thị cho phép chung ta thiết lập mẫu trang (layout)
     * 
     * CHỨC NĂNG:
     * Thiết lập mẫu trang (layout) tùy theo ý của bạn
     * 
     * Mẫu trang (layout) được đặt trong thư mục hienthi/mautrang/
     * 
     * Mặc định sẽ mang giá trị là "macdinh"
     * 
     * Ví dụ tôi muốn nạp mẫu trang abc.php trong thư mục hienthi/mautrang thì tui gán giá trị cho thuộc tính này là "abc"
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public $mauTrangHienThi = "macdinh";

    /**
     * Thuộc tính tệp hiển thị cho phép chúng ta thiết lập tệp hiển thị
     * 
     * CHỨC NĂNG:
     * Thiết lập tệp hiển thị tùy theo ý bạn
     * 
     * Tệp hiển thị được đặt trong thư mục hienthi/"tên điều hướng"/
     * 
     * Mặc định sẽ mang giá trị trùng với tên phương thức yêu cầu từ client
     * 
     * Ví dụ tôi muốn thay đổi tệp hiển thị txt.php trong thư mục hienthi/"tên điều hướng"/ thì tui gán giá trị cho thuộc tính này là "txt"
     * 
     * @var string (kiểu dữ liệu chuỗi)
     */
    public $tepHienThi;

    /**
     * Mảng báo lỗi html cho phép chúng ta thiết lập các lỗi HTML gửi về tầng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * 
     * Là mảng thuộc tính của hệ thống sử dụng để truyền lỗi HTML về tầng hiển thị (VIEW) để xuất lỗi ra màn hình
     * 
     * Lưu ý: nếu không cần thiết thị bạn không nên thiết lập giá trị phần tử cho mảng thuộc tính này
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $mangBaoLoiHtml = array();

    /**
     * Mảng biến thiết lập các biến về tầng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * Có các phần tử mang khóa (key) tương đương với tên biến và mang giá trị (value) tương đương với giá trị của biến
     * 
     * Sau đó khi gọi đến tầng hiển thị mảng nãy sẽ tự động convert quá biến cho chúng ta sử dụng ở các tệp hiển thị hoặc mẫu trang (layout)
     * 
     * Mọi tương tác với mảng này bạn nên thông qua phương thức "$this->bien($bien, $giaTri)" chứ không nên can thiệp trực tiếp lên nó tránh gây ra lỗi
     * 
     * @var array (kiểu dữ liệu mảng)
     */
    public $mangBienThietLap = array();

    /**
     * Phương thức bien để thiết lập các biến về tầng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * Thiết lập biến về tầng hiển thị trong tệp mẫu trang (layout) hoặc tệp hiển thị để sử dụng
     * 
     * Ví dụ bạn muốn thiết lập biến $a mang giá trị là 1 thì tại tầng điều hướng bạn gọi "$this->duLieuGuiDi->bien("a", 1);"
     * 
     * Thì ở tầng hiển thị (VIEW) trong tệp mẫu trang (layout) hoặc tệp hiển thị thì tự động sinh biến $a mang giá trị là 1 cho bạn tương tác
     * 
     * MỞ RỘNG:
     * Ví dụ bạn muốn thiết lập nhiều biến 1 lúc thì tại tầng điều hướng bạn gọi "$this->duLieuGuiDi->bien(array("bien 1" => gia tri 1, "bien 2" => gia tri 2, "bien n" => gia tri n));"
     * 
     * Thì ở tầng hiển thị (VIEW) trong tệp mẫu trang (layout) hoặc tệp hiển thị thì tự động sinh ra mảng biến tương ứng
     * 
     * @param mixed $bien (kiểu dữ liệu chuỗi hoặc mảng)
     * @param mixed $giaTri (kiểu dữ liệu ngẫu nhiên lưu ý: nếu thiết lập 1 lúc nhiều biến thì không cần truyền tham trị này !)
     */
    public function bien($bien = null, $giaTri = null) {
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
     * Phương thức thiết lập câu thông báo ở tầng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * Thiết lập chuỗi thông báo ở tầng hiển thị (VIEW)
     * 
     * Chuỗi này sẽ được gọi ra tại tệp mẫu trang (layout) hoặc tệp hiển thị theo cú pháp "$this->cauThongBao();"
     * 
     * @param string $chuoiThongBao (tham trị truyền vào có dạng chuỗi)
     */
    public function cauThongBao($chuoiThongBao = null) {
        if (is_string($chuoiThongBao)) {
            $mangFrameWork = mangSession::lay("vietFrameWork");
            if (is_array($mangFrameWork)) {
                $mangFrameWork['Câu Thông Báo'] = $chuoiThongBao;
                mangSession::thietLap("vietFrameWork", $mangFrameWork);
            } else {
                mangSession::thietLap("vietFrameWork", array('Câu Thông Báo' => $chuoiThongBao));
            }
        }
    }

    /**
     * Phương thức thiết lập câu báo lỗi truy cập ở tầng hiển thị (VIEW)
     * 
     * CHỨC NĂNG:
     * Thiết lập chuỗi báo lỗi truy cập ở tầng hiển thị (VIEW)
     * 
     * Chuỗi này sẽ được gọi ra tại tệp mẫu trang (layout) hoặc tệp hiển thị theo cú pháp "$this->cauBaoLoiTruyCap();"
     * 
     * @param string $chuoiBaoLoi (tham trị truyền vào có dạng chuỗi)
     */
    public function cauBaoLoiTruyCap($chuoiBaoLoi = null) {
        if (is_string($chuoiBaoLoi)) {
            $mangFrameWork = mangSession::lay("vietFrameWork");
            if (is_array($mangFrameWork)) {
                $mangFrameWork['Câu Báo Lỗi Truy Cập'] = $chuoiBaoLoi;
                mangSession::thietLap("vietFrameWork", $mangFrameWork);
            } else {
                mangSession::thietLap("vietFrameWork", array('Câu Báo Lỗi Truy Cập' => $chuoiBaoLoi));
            }
        }
    }

}

?>