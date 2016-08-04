<?php

class dieuhuong_macdinh extends dieuhuong_hethong {

    public function trangchinh() {
        $kiemTraTuongLua = (mb_strtolower(thietLapCha::$cauHinhTuongLua['Bật Tường Lửa'], "utf-8") === "bật") ? true : false;
        $kiemTraGhiLuocSu = is_writable(DUONGDANTHUMUCTRANG . "luocsu") ? true : false;
        $kiemTraKetNoiCSDL = coSoDuLieu::kiemTraKetNoiCSDL();
        $kiemTraPhienBanPHP = version_compare(PHP_VERSION, '5.2.8', '>=');
        $this->duLieuGuiDi->bien(array("kiemTraTuongLua" => $kiemTraTuongLua, "kiemTraGhiLuocSu" => $kiemTraGhiLuocSu, "kiemTraKetNoiCSDL" => $kiemTraKetNoiCSDL, "kiemTraPhienBanPHP" => $kiemTraPhienBanPHP));
    }

}

?>