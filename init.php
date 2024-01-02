<?php
$bot_id=" "; # Put here your bot id generated with Bot Father on Telegram.
#Invia i dati ogni volta che si fa il comando /start
$update = json_decode(file_get_contents("php://input"), TRUE);
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$nome = $update["message"]["chat"]["first_name"];
$cognome = $update["message"]["chat"]["last_name"];
$username = $update["message"]["chat"]["username"];
#[+]Descrizione bot
#Gli aggiornamenti sulla situazione epidemiologica sono aggiornati solitamente alle 17 precise di ogni giorno dal Ministero Della Salute. I dati che riceverai con /start sono gli ultimi dati ufficiali disponibili.
#[+]Ad ogni aggiornamento, storico con dati totali e + per aumenti.
#fai richiesta per aggiornamento alle 17 di ogni giorno e da li in poi fino alle 20
#[+]Change Webhook
#https://api.telegram.org/bot<bot_id>/setwebhook?url=<url>

#[+]DATI DA PRENDERE
#Ultimo aggiornamento = > https://github.com/pcm-dpc/COVID-19/blob/master/dati-json/dpc-covid19-ita-andamento-nazionale-latest.json
#Ulitmo aggiornamento Regioni => https://github.com/pcm-dpc/COVID-19/blob/master/dati-json/dpc-covid19-ita-regioni-latest.json
#Ultimo aggiornamento CON Regione + Provincia https://github.com/pcm-dpc/COVID-19/blob/master/dati-json/dpc-covid19-ita-province-latest.json
#Province https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province.json

$regioni=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni.json"),true);
$nazionale=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-andamento-nazionale.json"),true);
$latestnaz=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-andamento-nazionale-latest.json"),true);
$latestreg=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni-latest.json"),true);
$latestprov=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province-latest.json"),true);
$province=json_decode(file_get_contents("https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-province.json"),true);
#Modifica data in base all'ultimo aggiornamento dei dati
if (date("Y-m-d")>$latestnaz[0]["data"]){
    $ieri=date("Y-m-d", strtotime("yesterday")-1);
}
else{
$ieri=date("Y-m-d", strtotime("yesterday"));
}


$regione="Sardegna";
#Basta scrivere la regione e ti mostra la rispettiva regione. NON FUNZIONA
for ($i=0;$i<count($latestreg);$i++){
    echo strtolower(substr($latestreg[$i]["denominazione_regione"],0,1)).strtolower(substr($latestreg[$i]["denominazione_regione"],1,strlen($latestreg[$i]["denominazione_regione"])));
    if ($latestreg[$i]["denominazione_regione"]==$message){
        $regione=$message;
    }
    else if(strtolower(substr($latestreg[$i]["denominazione_regione"],0,1)).substr($latestreg[$i]["denominazione_regione"],1,strlen($latestreg[$i]["denominazione_regione"]))==$message){
         $regione=strtoupper(substr($latestreg[$i]["denominazione_regione"],0,1)).substr($latestreg[$i]["denominazione_regione"],1,strlen($latestreg[$i]["denominazione_regione"]));
    }
    else{
        $regione="Sardegna";
    }
}
for ($j=0;$j<count($nazionale);$j++)
{
    if (substr($nazionale[$j]["data"],0,10)==$ieri){
        $deceduti=$nazionale[$j]["deceduti"];
        $ric_con_sintomi=$nazionale[$j]["ricoverati_con_sintomi"];
        $terapia_intensiva=$nazionale[$j]["terapia_intensiva"];
        $tot_ospedalizzati=$nazionale[$j]["totale_ospedalizzati"];
        $isolamento_domiciliare=$nazionale[$j]["isolamento_domiciliare"];
        $totale_positivi=$nazionale[$j]["totale_positivi"];
        $dimessi=$nazionale[$j]["dimessi_guariti"];
        $sospetti=$nazionale[$j]["casi_da_sospetto_diagnostico"];
        $screening=$nazionale[$j]["casi_da_screening"];
        $tamponi=$nazionale[$j]["tamponi"];
        $casi_testati=$nazionale[$j]["casi_testati"];
    }
}

for ($l=0;$l<count($regioni);$l++)
{
    if (substr($regioni[$l]["data"],0,10)==$ieri&&$regioni[$l]["denominazione_regione"]==$regione){
        $dimessiSar=$regioni[$l]["dimessi_guariti"];
        $decedutiSar=$regioni[$l]["deceduti"];
        $casitestatiSar=$regioni[$l]["casi_testati"];
        $totalecasiSar=$regioni[$l]["totale_casi"];
        $tamponiSar=$regioni[$l]["tamponi"];
    }

}
$id_regione=0;
for ($i=0;$i<count($latestreg);$i++){
    echo "<br>ID ".$i."<br>";
    echo $latestreg[$i]['denominazione_regione']."<br>";
    #echo " ".$data." ".$latestreg[$i]['nuovi_positivi']." ".$latestreg[$i]['dimessi_guariti']." ".$latestreg[$i]['deceduti']." ".$latestreg[$i]['casi_testati'];
    #Memorizza id sardegna in latest Regioni
    if ($latestreg[$i]['denominazione_regione']==$regione){
        $id_regione=$i;
    }
}

#Province, dati del giorno prima
$id_province=0;
for ($i=0;$i<count($province);$i++){
    if ($province[$i]["denominazione_regione"]==$regione&&$province[$i]["totale_casi"]>0&&substr($province[$i]["data"],0,10)==$ieri){
        $id_province++;
        $array[$id_province]=$i;
        echo $province[$i]["denominazione_provincia"].": ".$province[$i]["totale_casi"]."%0A";#stringa
    }
}

$k=1;
for ($i=0;$i<count($latestprov);$i++){
    if ($latestprov[$i]["denominazione_regione"]==$regione&& $latestprov[$i]["totale_casi"]>0){        
        $msgprov.="📌".$latestprov[$i]["denominazione_provincia"].": ".$latestprov[$i]["totale_casi"]." %2B<b>".($latestprov[$i]["totale_casi"]-$province[$array[$k]]["totale_casi"])."</b>%0A";
        $k++;
    }
}   
echo $msgprov;
#." ".($latestprov[$i]["totale_casi"]-$province[$array[$k]]["totale_casi"])
$msg="<b>Dati Aggiornati al ".substr($latestnaz[0]["data"],0,10)."</b>%0A%0A🔴Nuovi Casi: ".$latestnaz[0]["nuovi_positivi"]."%0ADecessi: ".$latestnaz[0]["deceduti"]." %2B".($latestnaz[0]["deceduti"]-$deceduti)."%0ARicoverati con sintomi: ".$latestnaz[0]["ricoverati_con_sintomi"]." %2B".($latestnaz[0]["ricoverati_con_sintomi"]-$ric_con_sintomi)."%0ATerapia Intensiva: ".$latestnaz[0]["terapia_intensiva"]." %2B".($latestnaz[0]["terapia_intensiva"]-$terapia_intensiva)."%0ATotale Ospedalizzati: ".$latestnaz[0]["totale_ospedalizzati"]." %2B".($latestnaz[0]["totale_ospedalizzati"]-$tot_ospedalizzati)."%0AIsolamento Domiciliare: ".$latestnaz[0]["isolamento_domiciliare"]." %2B".($latestnaz[0]["isolamento_domiciliare"]-$isolamento_domiciliare)."%0ATotale Positivi: ".$latestnaz[0]["totale_positivi"]." %2B".($latestnaz[0]["totale_positivi"]-$totale_positivi)."%0ADimessi: ".$latestnaz[0]["dimessi_guariti"]." %2B".($latestnaz[0]["dimessi_guariti"]-$dimessi)."%0ASospetti: ".$latestnaz[0]["casi_da_sospetto_diagnostico"]." %2B".($latestnaz[0]["casi_da_sospetto_diagnostico"]-$sospetti)."%0ACasi da Screening: ".$latestnaz[0]["casi_da_screening"]." %2B".($latestnaz[0]["casi_da_screening"]-$screening)."%0ACasi Testati: ".$latestnaz[0]["casi_testati"]." %2B".($latestnaz[0]["casi_testati"]-$casi_testati)."%0ATamponi: ".$latestnaz[0]["tamponi"]." %2B".($latestnaz[0]["tamponi"]-$tamponi)."%0A%0A🇮🇹Regione: ".$latestreg[$id_regione]['denominazione_regione']."%0A%0A🔴Nuovi Positivi: ".$latestreg[$id_regione]['nuovi_positivi']."%0ADimessi: ".$latestreg[$id_regione]['dimessi_guariti']." %2B".($latestreg[$id_regione]['dimessi_guariti']-$dimessiSar)."%0ADeceduti: ".$latestreg[$id_regione]['deceduti']." %2B".($latestreg[$id_regione]['deceduti']-$decedutiSar)."%0ACasi Testati: ".$latestreg[$id_regione]['casi_testati']." %2B".($latestreg[$id_regione]['casi_testati']-$casitestatiSar)."%0ATotale Casi: ".$latestreg[$id_regione]['totale_casi']."%0ATamponi: ".$latestreg[$id_regione]['tamponi']." %2B".($latestreg[$id_regione]['tamponi']-$tamponiSar)."%0A".$msgprov;


if ($message=="/start"){
    file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=".$chatId."&parse_mode=html&text=".$msg);
    # if you want to see who is using your bot, put your chat id inside "<id>  and uncomment it
    #file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=<id>&parse_mode=html&text=<b>Chat ID: </b>".$chatId."%0A<b>Nome: </b>".$nome."%0A<b>Cognome: </b>".$cognome."%0A<b>Username: </b>".$username);
    //mysqli_query($conn,"insert into utenti(id,nome,cognome,data)values(".$chatId.",\"".$nome."\",\"".$cognome."\",\"".$username."\",\"".date("Y-m-d H:i:s")."\")");
    //mysqli_query($conn,"insert into log(id,comando,data)values(".$chatId.",\"".$message."\",\"".date("Y-m-d H:i:s")."\")");   
}
else if($message =="/info"){
    file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=".$chatId."&parse_mode=html&text=Gli aggiornamenti sulla situazione epidemiologica sono aggiornati solitamente alle 17 precise di ogni giorno dal Ministero Della Salute. I dati che riceverai con /start sono gli ultimi dati ufficiali disponibili.");
}   
else{
    #file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=".$chatId."&parse_mode=html&text=".$msg);
    file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=".$chatId."&parse_mode=html&text=Comando Sconosciuto");
    file_get_contents("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=21087347&parse_mode=html&text=<b>Chat ID: </b>".$chatId."%0A<b>Nome: </b>".$nome."%0A<b>Cognome: </b>".$cognome."%0A<b>Username: </b>".$username."<b>%0AMessaggio: </b>".$message);
}
