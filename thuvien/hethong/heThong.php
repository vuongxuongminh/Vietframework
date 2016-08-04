<?php

class heThong {

    /**
     * Phương thức layIpMayKhach xử lý giúp lấy IP máy khách
     * 
     * CHỨC NĂNG:
     * Xử lý và trả về IP máy khách truy cập vào hệ thống
     * 
     * @return string phương thức trả về kiểu giá trị chuỗi là IP máy khách truy cập
     */
    public static function layIpMayKhach() {
        $ip = "Không xác định !";
        if (isset($_SERVER)) {
            $mayChu = $_SERVER;
            if (isset($mayChu['HTTP_CLIENT_IP'])) {
                $ip = $mayChu['HTTP_CLIENT_IP'];
            } elseif (isset($mayChu['HTTP_X_FORWARDED_FOR'])) {
                $ip = $mayChu['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($mayChu['HTTP_X_FORWARDED'])) {
                $ip = $mayChu['HTTP_X_FORWARDED'];
            } elseif (isset($mayChu['HTTP_FORWARDED_FOR'])) {
                $ip = $mayChu['HTTP_FORWARDED_FOR'];
            } elseif (isset($mayChu['HTTP_FORWARDED'])) {
                $ip = $mayChu['HTTP_FORWARDED'];
            } elseif (isset($mayChu['REMOTE_ADDR'])) {
                $ip = $mayChu['REMOTE_ADDR'];
            }
        }
        if ($ip === "::1") {
            $ip = "127.0.0.1";
        }
        return $ip;
    }

    /**
     * Phương thức layTenMienTruyCap xử lý giúp lấy tên miền máy khách sử dụng truy cập vào hệ thống
     * 
     * CHỨC NĂNG:
     * Xử lý và trả về tên miền máy khách sử dụng truy cập vào hệ thống
     * 
     * @return string phương thức trả về kiểu giá trị chuỗi là tên miền máy khách truy cập
     */
    public static function layTenMienTruyCap() {
        $tenMien = "unknow";
        if (isset($_SERVER)) {
            $mayChu = $_SERVER;
            if (isset($mayChu["HTTP_HOST"])) {
                $tenMien = $mayChu["HTTP_HOST"];
            } elseif (isset($mayChu["SERVER_NAME"])) {
                $tenMien = $mayChu["SERVER_NAME"];
            }
        }
        return $tenMien;
    }

}
