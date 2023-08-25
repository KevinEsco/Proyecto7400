<?php
$servidor="localhost";
$baseDeDatos="proyecto7400";
$usuarios="root";
$clave="";
try{
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuarios, $clave);
}catch(Exception $ex){
    echo $ex->getMessage();
}
