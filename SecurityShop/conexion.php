<?php
require_once __DIR__ . '/configuracion.php';
try{
  $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
  ]);
}catch(PDOException $e){ die('Error de conexión: '.$e->getMessage()); }
session_start();
function autenticado(){ return isset($_SESSION['id_usuario']); }
function nombre_usuario(){ return autenticado()? htmlspecialchars($_SESSION['nombre_usuario']) : 'Invitado'; }
?>