<?php
include_once "init.php";
$data=date("Y-m-d");
set_time_limit(25200);#7h
$your_id=""; # Put here your id on Telegram, you can have it by querying @RawDataBot on telegram.
$is=true;
while ($is){
    if (substr($latestnaz[0]["data"],0,10)==$data){
    file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=".your_id."&parse_mode=html&text=AggiornamentoDATI PUSH FUNZIONA");
    $is=false;
    }   
    sleep(10);

}

