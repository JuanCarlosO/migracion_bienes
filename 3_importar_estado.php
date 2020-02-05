<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $sql = "SELECT * FROM registro_bienes";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $key => $bien) {
    	$sql_nvo = "UPDATE bieness SET status = ? WHERE serie = ? AND inventario = ?";
    	$stmt 	 = $pdo->prepare($sql_nvo);
    	$stmt->bindParam(1,$bien->id_status);
    	$stmt->bindParam(2,$bien->serie);
    	$stmt->bindParam(3,$bien->noinventario);
    	$stmt->execute();
    	echo "Se actualizó el bien con serie: ".$bien->serie." e inventario: ".$bien->noinventario."<hr>";
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

?>