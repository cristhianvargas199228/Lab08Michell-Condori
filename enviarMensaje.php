<?php
if (!isset($_GET['codigo'])) {
    header('Location: index.php?mensaje=error');
    exit();
}

include 'model/conexion.php';
$codigo = $_GET['codigo'];

$sentencia = $bd->prepare("SELECT pro.promocion, pro.duracion , pro.id_usuarios, per.nombre_completo ,per.telefono , per.fecha_de_nacimiento 
  FROM promociones pro 
  INNER JOIN usuarios per ON per.id = pro.id_usuarios 
  WHERE pro.id = ?;");
$sentencia->execute([$codigo]);
$usuarios = $sentencia->fetch(PDO::FETCH_OBJ);

    $url = 'https://api.green-api.com/waInstance1101817407/SendMessage/151ad8814d8849d99d0db198713e10a897f0119afc0c49f5bd';
    $data = [
        "chatId" => "51".$usuarios->telefono."@c.us",
        "message" =>  'Estimado(a) *'.strtoupper($usuarios->nombre_completo).'* No se pierda *'.strtoupper($usuarios->promocion).'* valido solo *'.$usuarios->duracion.'*'
    ];
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode($data),
            'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);
   // header('Location: agregarPromocion.php?codigo='.$usuarios->id_usuarios);
?> 

