<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $sql = "SELECT DISTINCT(nom_completo) AS nombre_full, nombres,app,apm FROM personal_ins WHERE nom_completo != '' GROUP BY nom_completo ORDER BY id_person ASC";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $nombres = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($nombres as $key => $nombre) {
    	$sql_insert = " INSERT INTO personal (id,nombre,ap_pat,ap_mat) VALUES ('',?,?,?) ";
    	$stmt 	 = $pdo->prepare($sql_insert);
    	$stmt->bindParam(1,$nombre->nombres);
    	$stmt->bindParam(2,$nombre->app);
    	$stmt->bindParam(3,$nombre->apm);
    	$stmt->execute();
    	echo "Nombre: ".$nombre->nombre_full."<hr>";
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}
