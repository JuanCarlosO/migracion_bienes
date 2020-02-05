<?php
define('DNS_IN', 'mysql:dbname=migracion_bienes;host=localhost;charset=utf8');
define('USER_DB_IN', 'root');
define('PASS_DB_IN', '');
#define('PASS_DB_IN', '7W+Th_+uTh2X');
#El siguiente script debe permitir asignar area de adscripcion al personal 
try {
    $pdo= new PDO(DNS_IN, USER_DB_IN, PASS_DB_IN);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT UPPER(color) AS color FROM registro_bienes GROUP BY color";
    $stmt 	= $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    #INSERTAR LOS COLORES EN LA TABLA DE CATALOGO DE COLORES
    foreach ($result as $key => $bien) {
        $sql_color = "INSERT INTO colores (id,nombre) VALUES ('',?);";
        $stmt = $pdo->prepare($sql_color);
        $stmt->bindParam(1,$bien->color);
        $stmt->execute();
    }

    #SELECCIONAR TODOS LOS BIENES
    $sql_bienes = "SELECT * FROM registro_bienes";
    $stmt 	= $pdo->prepare($sql_bienes);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    $id = 1;
    foreach ($result as $key => $bien) {
    	#BUSCAR EL ID DEL COLOR DE ESTE BIEN
    	$color = "%".$bien->color."%";
    	$sql_c = "SELECT * FROM colores WHERE nombre LIKE ? LIMIT 1";
    	$stmt = $pdo->prepare($sql_c);
        $stmt->bindParam(1,$color,PDO::PARAM_STR);
        $stmt->execute();
        $result_n = $stmt->fetch(PDO::FETCH_OBJ);

        $sql_up = "UPDATE bieness SET color_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql_up);
        $stmt->bindParam(1,$result_n->id,PDO::PARAM_INT);
        $stmt->bindParam(2,$id,PDO::PARAM_INT);
        $stmt->execute();
        $id++;
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}

?>