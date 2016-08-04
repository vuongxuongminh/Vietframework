$(function() {
    var milyGiay = 100;
    var soGiayCho = Number($("#soGiayCho").html());
    
    function giamThoiGian() {
        if (soGiayCho > 0) {
            milyGiay--;
            var thoiGianCho = soGiayCho + "." + milyGiay;
            $("#soGiayCho").html(thoiGianCho);   
            if(milyGiay == 0){
                milyGiay = 100;
                soGiayCho--;
            }            
            setTimeout(function(){ giamThoiGian(); },10);
        }else{
            $("#soGiayCho").html(0);
            setTimeout(function(){ location.reload(); },500);
        }
    }
    
    giamThoiGian();
    
});