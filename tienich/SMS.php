<?php

class SMS {

    private static $khoaUngDung = "FF879E2A3B40E95951548B3A7AD814";
    private static $khoaBaoMatUngDung = "E23E0CA29B678F2C4F489682D28EBE";

    public static function guiSMS($soDienThoai, $noiDung) {
        $SampleXml = "<RQST>"
                . "<APIKEY>" . self::$khoaUngDung . "</APIKEY>"
                . "<SECRETKEY>" . self::$khoaBaoMatUngDung . "</SECRETKEY>"
                . "<ISFLASH>0</ISFLASH>"
                . "<SMSTYPE>7</SMSTYPE>"
                . "<CONTENT>" . $noiDung . "</CONTENT>"
                . "<CONTACTS>"
                . "<CUSTOMER>"
                . "<PHONE>" . $soDienThoai . "</PHONE>"
                . "</CUSTOMER>"
                . "</CONTACTS>"
                . "</RQST>";

        $curl = curl_init("http://api.esms.vn/MainService.svc/xml/SendMultipleMessage_V2/");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $SampleXml);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $result = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($result);
        if ($xml === false) {
            die('Error parsing XML');
        }
        return $xml->CodeResult;
    }

}
