<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $sql = "SELECT UPPER(marca) AS marca FROM registro_bienes GROUP BY marca";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($result as $key => $bien) {
    	$sql_nvo = "INSERT INTO marcas(id,nombre) VALUES ('',?)";
    	$stmt_nvo 	 = $pdo->prepare($sql_nvo);
    	$stmt_nvo->bindParam(1,$bien->marca);
    	$stmt_nvo->execute();
    	
        $last_id = $pdo->lastInsertId();
        $sql_b = "SELECT serie,noinventario FROM registro_bienes WHERE marca = ?";
        $stmt_b   = $pdo->prepare($sql_b);
        $stmt_b->bindParam(1,$bien->marca,PDO::PARAM_STR);
        $stmt_b->execute();
        $result_b = $stmt_b->fetchAll(PDO::FETCH_OBJ);
        
        foreach ($result_b as $key => $rb) {
            $sql_upd = "UPDATE bieness SET marca_id = ? WHERE serie = ? AND inventario = ?";
            $stmt_b_nvo   = $pdo->prepare($sql_upd);
            $stmt_b_nvo->bindParam(1,$last_id,PDO::PARAM_INT);
            $stmt_b_nvo->bindParam(2,$rb->serie,PDO::PARAM_INT);
            $stmt_b_nvo->bindParam(3,$rb->noinventario,PDO::PARAM_INT);
            $stmt_b_nvo->execute();
        }
        echo "Marca importada: ".$bien->marca."<hr>";
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

?>