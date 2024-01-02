<?php
include "dbconn.php";
$ieri="2020-08-23";#date("Y-m-d", strtotime("yesterday"));

$latestnaz=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-andamento-nazionale-latest.json"),true);
$latestreg=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni-latest.json"),true);
$latestprov=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province-latest.json"),true);
$province=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province.json"),true);


$nazionale="insert into nazionale(data,ricoverati,terapia,totale_osp,totale_pos,dimessi,deceduti,sospetti,screening,casi_testati)values(\"";
echo substr($province[1]["data"],0,10);
for ($i=0;$i<count($province);$i++){
    if ($province[$i]["denominazione_regione"]=="Sardegna"&&$province[$i]["totale_casi"]>0&&substr($province[$i]["data"],0,10)==$ieri){
        $provincesql="insert into provincia(data,regione,provincia,nuovi_casi)values(\"".substr($province[$i]["data"],0,10)."\",\"".$province[$i]["denominazione_regione"]."\",\"".$province[$i]["denominazione_provincia"]."\",".$province[$i]["totale_casi"];
        mysqli_query($conn,$provincesql);
    }
}
?>