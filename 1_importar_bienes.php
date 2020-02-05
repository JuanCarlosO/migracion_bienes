<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $sql = "SELECT serie, noinventario,descripcion FROM registro_bienes";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $key => $bien) {
    	$sql_nvo = "INSERT INTO bieness(id,serie,inventario,descripcion) VALUES ('',?,?,?)";
    	$stmt 	 = $pdo->prepare($sql_nvo);
    	$stmt->bindParam(1,$bien->serie);
    	$stmt->bindParam(2,$bien->noinventario);
    	$stmt->bindParam(3,$bien->descripcion);
    	$stmt->execute();
    	echo "Bien con serie: ".$bien->serie." e inventario: ".$bien->noinventario."<hr>";
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

?>