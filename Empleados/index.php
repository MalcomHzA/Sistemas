<?php
// operador ternario--->  condicion ? verdadera : falso
$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtApellidoP=(isset($_POST['txtApellidoP']))?$_POST['txtApellidoP']:"";
$txtApellidoM=(isset($_POST['txtApellidoM']))?$_POST['txtApellidoM']:"";
$txtCorreo=(isset($_POST['txtCorreo']))?$_POST['txtCorreo']:"";
$txtPais=(isset($_POST['txtPais']))?$_POST['txtPais']:"";
$txtCiudad=(isset($_POST['txtCiudad']))?$_POST['txtCiudad']:"";
$txtFoto=(isset($_FILES['txtFoto']["name"]))?$_FILES['txtFoto']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

$error=array();
$accionAgregar="";
$accionModificar=$accionEliminar="";
$accionModificar=$accionEliminar=$accionCancelar="disabled";
$mostrarModal=false;

include_once "../conexion/conexion.php";

// estructura de control
switch($accion){
    case "btnAgregar":
        $sentencia=$pdo->prepare("INSERT INTO empleados(Nombre,ApellidoP,ApellidoM,Correo,Foto) 
        VALUES (:Nombre,:ApellidoP,:ApellidoM,:Correo,:Foto)");
        $sentencia->bindparam('Nombre',$txtNombre);
        $sentencia->bindparam('ApellidoP',$txtApellidoP);
        $sentencia->bindparam('ApellidoM',$txtApellidoM);
        $sentencia->bindparam('Correo',$txtCorreo);
        $Fecha= new DateTime();
        $nombreArchivo=($txtFoto!="")?$Fecha->getTimestamp()."_".$_FILES["txtFoto"]["name"]:"user_1.jpg";
        $tmpFoto= $_FILES["txtFoto"]["tmp_name"];
        if($tmpFoto!=""){
            move_uploaded_file($tmpFoto,"../Imagenes/".$nombreArchivo);
        }
     
        $sentencia->bindparam('Foto',$nombreArchivo);
        $sentencia->execute();
        header('Location: index.php');

    break;
    case "btnModificar":
        $sentencia=$pdo->prepare("UPDATE empleados SET 
        Nombre=:Nombre,
        ApellidoP=:ApellidoP,
        ApellidoM=:ApellidoM,
        Correo=:Correo WHERE ID=:ID"); 
        $sentencia->bindparam('Nombre',$txtNombre);
        $sentencia->bindparam('ApellidoP',$txtApellidoP);
        $sentencia->bindparam('ApellidoM',$txtApellidoM);
        $sentencia->bindparam('Correo',$txtCorreo);
        $sentencia->bindparam('ID',$txtID);
        $sentencia->execute();
        $Fecha= new DateTime();
        $nombreArchivo=($txtFoto!="")?$Fecha->getTimestamp()."_".$_FILES["txtFoto"]["name"]:"imagen.jpg";
        $tmpFoto= $_FILES["txtFoto"]["tmp_name"];
        if($tmpFoto!=""){
            $sentencia=$pdo->prepare("SELECT Foto FROM empleados WHERE ID=:ID"); 
            $sentencia->bindparam('ID',$txtID);
            $sentencia->execute();
            $empleados=$sentencia->fetch(PDO::FETCH_LAZY);
            print_r($empleados);

        if (isset($empleados["Foto"])){
            if (file_exists("../Imagenes/".$empleados["Foto"])){
                if($item['foto']!="imagen.jpg"){
                unlink("../Imagenes/".$empleados["Foto"]);
                }
            }
        }
            move_uploaded_file($tmpFoto,"../Imagenes/".$nombreArchivo);
            $sentencia=$pdo->prepare("UPDATE empleados SET Foto=:Foto WHERE ID=:ID"); 
            $sentencia->bindparam('Foto', $nombreArchivo);
            $sentencia->bindparam('ID', $txtID);
            $sentencia->execute();
        }

        header('location: index.php');
        echo $txtID;
        echo "Presionaste btnModificar";
    break;
    case "btnEliminar":

        $sentencia=$pdo->prepare("SELECT * FROM empleados WHERE ID=:ID"); 
        $sentencia->bindparam('ID',$txtID);
        $sentencia->execute();
        $empleados=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($empleados);

        if (isset($empleados["Foto"])&&($item['foto']!="imagen.jpg")){
            if (file_exists("../Imagenes/".$empleados["Foto"])){
                unlink("../Imagenes/".$empleados["Foto"]);
            }
        }

        echo $txtID;
        echo "Presionaste btnEliminar";
        $sentencia=$pdo->prepare(" DELETE FROM empleados WHERE ID=:ID"); 
        $sentencia->bindparam('ID',$txtID);
        $sentencia->execute();
        header('Location: index.php');
        echo$txtID;
        echo "Presionaste btnEliminar";    
    break;
    case "btnCancelar":
        header('Location: index.php');
    break;
    case "Actualizar":
        $accionAgregar="disabled";
        $accionModificar=$accionEliminar=$accionCancelar="";
        $mostrarModal=true;

        $sentencia=$pdo->prepare("SELECT * FROM empleados WHERE ID=:ID"); 
        $sentencia->bindparam(':ID',$txtID);
        $sentencia->execute();
        $empleados=$sentencia->fetch(PDO::FETCH_LAZY);

        $txtNombre=$empleados['Nombre'];
        $txtApellidoP=$empleados['ApellidoP'];
        $txtApellidoM=$empleados['ApellidoM'];
        $txtCorreo=$empleados['Correo'];
        $txtFoto=$empleados['Foto'];

    break;
}
    $sentencia= $pdo->prepare("SELECT * FROM `empleados` WHERE 1");
    $sentencia->execute();
    $listaEmpleados=$sentencia->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <!-- Formulario -->
        <form action="" method="post" enctype="multipart/form-data" class="form-data">  
            <!-- ID -->
            <input class="form-control" type="hidden" required name="txtID" placeholder="" id="txtID" require="" value="<?php echo$txtID;?>">
            <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">empleados</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <!-- ID -->
                                    <input type="hidden" required name="txtID" value="<?php echo $txtID?>" placeholder="" id="txtID" required>
                                    <!-- Nombre -->
                                    <div class="Form-group col-md-4">
                                        <label for="txtNombre">Nombre:</label>
                                        <input class="form-control" type="text" name="txtNombre" placeholder="" id="txtNombre" value="<?php echo$txtNombre;?>" required>
                                        <br>
                                    </div>
                                    <!-- 1er Paterno -->
                                    <div class="Form-group col-md-4">
                                        <label for="txtApellidoP">ApellidoP:</label>
                                        <input class="form-control" type="text" name="txtApellidoP" placeholder="" id="txtApellidoP" value="<?php echo$txtApellidoP;?>" required>
                                        <br>
                                    </div>
                                    <!-- 2do apellido -->
                                    <div class="Form-group col-md-4">
                                        <label for="txtApellidoM">ApelidoM:</label>
                                        <input class="form-control" type="text" name="txtApellidoM" placeholder="" id="txtApellidoM" value="<?php echo$txtApellidoM;?>" required>
                                        <br>
                                    </div>
                                    <!-- Email -->
                                    <div class="Form-group col-md-12">
                                        <label for="txtCorreo">Correo:</label>
                                        <input class="form-control" type="email" name="txtCorreo" placeholder="" id="txtCorreo" value="<?php echo$txtCorreo;?>" required>
                                        <br>
                                    </div>
                                    <!-- Foto -->
                                    <div class="Form-group col-md-12">
                                        <label for="txtFoto">Foto:</label>
                                        <?php if ($txtFoto!=""){?>
                                        <br/>
                                        <img class="img-thumbnail rounded mx-auto d-block" width="100px" src="../Imagenes/<?php echo $txtFoto;?>" />
                                        <br/>
                                        <?php }?>
                                        <input class="form-control" accept="image*" type="file" name="txtFoto" placeholder="" id="txtFoto" require="" value="<?php echo$txtFoto;?>">
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Botones -->
                                <button class="btn btn-success" value="btnAgregar" <?php echo $accionAgregar;?> type="submit" name="accion">Agregar</button>
                                <button class="btn btn-primary" value="btnModificar" <?php echo $accionModificar;?> type="submit" name="accion">Modificar</button>
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <br/>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Agregar Registro +
            </button>
        </form>

        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre completo</th>
                        <th>Correo</th>
                        <th>Acciones</th> 
                    </tr>
                </thead>

                <?php foreach ($listaEmpleados as $empleados){ ?>
                    <tr>
                        <td><img class="img-thumbnail" width="100px" src="../Imagenes/<?php echo $empleados['Foto']; ?>" /></td> 
                        <td><?php echo $empleados['Nombre']. " " .$empleados['ApellidoP']. "  " .$empleados['ApellidoM']; ?></td> 
                        <td><?php echo $empleados['Correo']; ?></td>     
                        <td>          
                            <form action="" method="post">
                                <input type="hidden" name="txtID" value="<?php echo $empleados['ID']; ?>">
                                <input type="submit" value="Actualizar" class="btn btn-success" name="accion">
                                <button value="btnEliminar" type="submit" class="btn btn-danger" name="accion">Eliminar</button>
                            </form>    
                        </td>
                    </tr>
                <?php } ?>   
            </table> 
        </div>
    <?php if($mostrarModal){?>
        <script>
            $('#exampleModal').modal('show');
        </script>
        <?php }?>
    </div>
</body>
</html>