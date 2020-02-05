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
    $id = 1;
    foreach ($result as $key => $bien) {
        $person = $bien->id_person;
        #BUSCAR EL NOMBRE DEL SP CORRECTO EN LA BASE VIEJA
        $sql_person_v = "SELECT CONCAT(nombres,' ',app,' ',apm) AS full_name FROM personal_ins WHERE id_person = ?"; 
        $stmt   = $pdo->prepare($sql_person_v);
        $stmt->bindParam(1,$person);
        $stmt->execute();
        $persona = $stmt->fetch(PDO::FETCH_OBJ)->full_name;
        $persona = "'%".$persona."%'";
        #Buscar el ID de la persona de la tabla NUeva de personal
        $sql_person_n = "SELECT * FROM personal WHERE CONCAT(nombre,' ',ap_pat,' ',ap_mat) LIKE $persona";
        $stmt   = $pdo->prepare($sql_person_n);
        $stmt->execute();
        $persona_n = $stmt->fetch(PDO::FETCH_OBJ)->id; #ID de la persona que tiene el bien
        $sql_asigna = "INSERT INTO asignacion (id,personal_id,bien_id,area_id,status,vigencia,tipo) 
        VALUES ('', ?,?, NULL, 1,1,1);";
        $stmt   = $pdo->prepare($sql_asigna);
        $stmt->bindParam(1,$persona_n);
        $stmt->bindParam(2,$id);
        $stmt->execute();
        echo "LA ASIGNACION A: ".$persona." SE A REALIZADO CON ÉXITO <hr>";
        $id++;
    }
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
}



if ( $bien->id_person > 0) {
            $sql_ins = "INSERT INTO asignacion (id,personal_id,bien_id,area_id,status,vigencia,tipo) 
            VALUES ('',?,?,NULL,1,1,1);";
            $stmt   = $pdo->prepare($sql_ins);
            $stmt->bindParam(1,$bien->id_person);
            $stmt->bindParam(2,$bien->idRegistro);
            $stmt->execute();
            echo "EL BIEN A SIDO ASIGNADO DE MANERA EXITOSA.<hr>";
        }else{
            echo "*****BIEN SIN USUARIO****** <hr>";
        }