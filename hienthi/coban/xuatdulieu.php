<?php
/** @var $this hienThiCha */

foreach($duLieu as $user){
    print $user["user"]["username"] . " - " . $user["user"]["password"] . "<br>";
}