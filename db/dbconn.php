<?php
$conn=@mysqli_connect("localhost","root","","bot");
if (mysqli_connect_errno()){
    echo "Errore nella Connessione al Database.".die (mysqli_connect_error());
}