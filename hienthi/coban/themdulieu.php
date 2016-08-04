<?php
/* @var $this hienThiCha */
?>
<?= $this->doiTuongHtml->napBieuMau("user", ["role" => "form"]); ?>
<?= "Username: " . $this->doiTuongHtml->napNhapLieu("username", ["class" => "form-control"]) . "<br>"; ?>
<?= "Password: " . $this->doiTuongHtml->napNhapLieu("password", ["class" => "form-control"]) . "<br>"; ?>
<?= $this->doiTuongHtml->napNhapLieu("submit", [
    "type" => "submit",
    "value" => "Xác nhận",
    "class" => "btn-primary"
]); ?>
<?= $this->doiTuongHtml->dongBieuMau(); ?>