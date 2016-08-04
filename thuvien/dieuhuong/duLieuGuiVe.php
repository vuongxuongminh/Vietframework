<?php

/*
 * VIỆT FRAME WORK
 * FRAMEWORK VIỆT CHO NGƯỜI VIỆT
 * 
 * NHÓM PHÁT TRIỂN          Việt Framework Group
 * NĂM PHÁT TRIỂN           2014
 * TRANG CHỦ                http://vietframework.com
 * HÒM THƯ                  admin@vietframework.com
 * NGÀY VIẾT                22/05/2014
 * PHIÊN BẢN                2.0.0
 * BẢN QUYỀN                HỆ THỐNG MÃ NGUỒN MỞ NHƯNG VUI LÒNG TÔN TRỌNG CÁC THÔNG TIN NHÓM PHÁT TRIỂN
 */

/*
 * Lớp đối tượng duLieuGuiVe chứa các thuộc tính và phương thức liên quan đến dữ liệu từ client gửi về
 * 
 * CHỨC NĂNG CHÍNH:
 * Lấy điều hướng gửi về
 * Lấy phương thức gửi về
 * Lấy tham trị gửi về
 * Lấy dữ liệu biểu mẫu gửi về
 */

class duLieuGuiVe {

    /**
     * Mảng duLieuPostGuiVe chứa các giá trị dữ liệu gửi về theo phương thức POST
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử là giá trị dữ liệu POST gửi về từ client
     * 
     * Giúp cho việc thao tác với đối tượng xử lý (MODEL) trong việc cập nhật, thêm, xóa (update, delete, insert)
     * 
     * @var array (kiểu mảng)
     */
    public $duLieuPostGuiVe = array();

    /**
     * Mảng duLieuGetGuiVe chứa các giá trị dữ liệu gửi về theo phương thức GET
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử là giá trị dữ liệu GET gửi về từ client
     * 
     * Giúp cho việc thao tác với đối tượng xử lý (MODEL) trong việc cập nhật, thêm, xóa (update, delete, insert)
     * 
     * @var array (kiểu mảng)
     */
    public $duLieuGetGuiVe = array();

    /**
     * Mảng duLieuFileGuiVe chứa các giá trị dữ liệu các tệp đính kèm gửi về
     * 
     * CHỨC NĂNG:
     * Chứa các phần tử là các giá trị thông tin tệp đính kèm gửi về từ client
     * 
     * Giúp cho việc thao tác với đối tượng xử lý (MODEL)
     * @var array (kiểu mảng)
     */
    public $duLieuFileGuiVe = array();

    /**
     * Phương thức khởi tạo lớp đối tượng dữ liệu gửi về
     * 
     * CHỨC NĂNG:
     * Gán giá trị cho mảng thuộc tính dữ liệu biểu mẫu gửi về khi lớp đối tượng duLieuGuiVe được khởi tạo
     */
    public function __construct() {
        $this->duLieuPostGuiVe = $this->duLieuPostGuiVe();
        $this->duLieuGetGuiVe = $this->duLieuGetGuiVe();
        $this->duLieuFileGuiVe = $this->duLieuFileGuiVe();
    }

    /**
     * Phương thức xử lý mảng POST gửi về từ client
     * 
     * CHỨC NĂNG:
     * Trả về giá trị mảng POST dữ liệu gửi về từ client nếu khác rỗng
     * 
     * Trả về giá trị sai (FALSE) nếu dữ liệu mảng POST là rỗng
     * 
     * @return boolean (kiểu đúng (TRUE) hoặc sai (FALSE))
     */
    private function duLieuPostGuiVe() {
        if (!empty($_POST)) {
            return $_POST;
        }
        return false;
    }

    /**
     * Phương thức xử lý mãng FILES gửi về từ client
     * 
     * CHỨC NĂNG:
     * Trả về giá trị mảng FILES dữ liệu gửi về từ client nếu khác rỗng
     * 
     * Trả về giá trị sai (FALSE) nếu dữ liệu mảng FILES là rỗng
     * 
     * @return boolean (kiểu đúng (TRUE) hoặc sai (FALSE))
     */
    private function duLieuFileGuiVe() {
        if (!empty($_FILES)) {
            return $_FILES;
        }
        return false;
    }

    /**
     * Phương thức xử lý mảng GET gửi về từ client
     * 
     * CHỨC NĂNG:
     * Trả về giá trị mảng GET dữ liệu gửi về từ client nếu khác rỗng
     * 
     * Trả về giá trị sai (FALSE) nếu dữ liệu mảng GET là rỗng
     * 
     * @return boolean (kiểu đúng (TRUE) hoặc sai (FALSE))
     */
    private function duLieuGetGuiVe() {
        $giaTriMangGet = $_GET;
        if (isset($giaTriMangGet['duongDanTruyCap'])) {
            unset($giaTriMangGet['duongDanTruyCap']);
        }
        if (!empty($giaTriMangGet)) {
            return $giaTriMangGet;
        }
        return false;
    }

    /**
     * Phương thức phuongThucYeuCau xử lý trả về phương thức yêu cầu từ client
     * 
     * CHỨC NĂNG:
     * Trả về phương thức yêu cầu từ client
     * 
     * @return string (dữ liệu trả về kiểu chuỗi)
     */
    public function phuongThucYeuCau() {
        return dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Phương Thức"];
    }

    /**
     * Phương thức dieuHuongYeuCau xử lý trả về điều hướng yêu cầu từ client
     * 
     * CHỨC NĂNG:
     * Trả về điều hướng yêu cầu từ client
     * 
     * @return string (dữ liệu trả về kiểu chuỗi)
     */
    public function dieuHuongYeuCau() {
        return dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Điều Hướng"];
    }

    /**
     * Phương thức thamTriYeuCau xử lý trả về tham trị yêu cầu từ client
     * 
     * CHỨC NĂNG:
     * Trả về tham trị yêu cầu từ client
     * 
     * @return array (dữ liệu trả về kiểu mảng)
     */
    public function thamTriYeuCau() {
        if (is_string(dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Tham Trị"])) {
            return array(dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Tham Trị"]);
        }
        return dieuHuongCha::$dieuHuongPhuongThucVaThamTri["Tham Trị"];
    }

    /**
     * Phương thức kiểm tra dạng dữ liệu gửi về từ client
     * 
     * CHỨC NĂNG:
     * Kiểm tra dạng dữ liệu gửi về từ client (POST, GET, AJAX) xem đúng hoặc sai với tham trị truyền vào
     * 
     * @param string $loai (tham trị truyền vào kiểu chuỗi)
     * @return boolean dữ liệu trả về là đúng (TRUE) nếu tham trị truyền vào khớp với dạng dữ liệu gửi về và sai (FALSE) ngược lại.
     */
    public function dang($loai = null) {
        if (is_string($loai)) {
            $loai = strtolower($loai);
            if ($loai === "ajax") {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    return true;
                }
            } else {
                if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === $loai) {
                    return true;
                }
            }
        }
        return false;
    }

}

?>
