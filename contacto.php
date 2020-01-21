<?php

$nombre= $_POST['name'];
$email= $_POST['email'];
$mensaje= $_POST['message'];
$headers= "De: ".$nombre ."\r\n"."Email: ".$email ."\r\n".  "Mensaje: ".$mensaje ."\r\n";
mail('comunicaciones@4dev.cl','Contacto 4Dev SpA',$headers);

$headers2= "Hola ".$nombre."\r\n". "Gracias por contactarnos, en un momento recibirás respuesta de uno de nuestros colaboradores "."\r\n". "Que tengas un muy buen día"
."\r\n". "\r\n". "atte : 4dev.cl" ."\r\n";
mail($email,'4Dev.cl',$headers2);



 ?>

