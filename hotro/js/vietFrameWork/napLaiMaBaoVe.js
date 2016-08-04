

function napLaiMaBaoVe(ev) {
    maXacDinh = $(ev).index();
    ngay = new Date();
    lopCha = $(ev).parent().children();
    duongDanHinhBaoVe = lopCha.eq(maXacDinh - 1).attr("src");
    lopCha.eq(maXacDinh - 1).attr("src", duongDanHinhBaoVe + "&" + ngay.getTime());
}
