<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM registro_bienes";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    #Inicio ID
    $id = 1;
    foreach ($result as $key => $bien) {
    	$sql_nvo = "UPDATE bieness SET grupo_id = ? WHERE id = ?";
    	$stmt 	 = $pdo->prepare($sql_nvo);
    	$stmt->bindParam(1,$bien->id_grupo);
    	$stmt->bindParam(2,$id);
    	$stmt->execute();
    	echo " SE AGREGÓ GRUPO A EL BIEN CON ID: $id <hr>";
    	$id++;
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

?>