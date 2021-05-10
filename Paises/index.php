<?php
// variables
$txtId=(isset($_POST['txtId']))?$_POST['txtId']:"";
$txtPais=(isset($_POST['txtPais']))?$_POST['txtPais']:"";
$txtCapital=(isset($_POST['txtCapital']))?$_POST['txtCapital']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";
$accionAgregar="";
$accionModificar="";
$mostrarModal=false;
// import conexion
include_once "../conexion/conexion.php";

// estructura de control segun la accion (create,read,delete,update)
switch($accion){
    case "btnAgregar": 
        $sentencia=$pdo->prepare("INSERT INTO paises(nombre_pais, nombre_capital) 
        VALUES (:nombre_pais, :nombre_capital)");
        $sentencia->bindparam('nombre_pais',$txtPais);
        $sentencia->bindparam('nombre_capital',$txtCapital);
        $sentencia->execute();
        header('Location: index.php');
        echo $txtId;
        echo "Presionaste btnAgregar";
    break;
    case "btnModificar":
        $sentencia=$pdo->prepare("UPDATE paises SET nombre_pais=:nombre_pais, nombre_capital=:nombre_capital WHERE id=:id"); 
        $sentencia->bindparam('id',$txtId);
        $sentencia->bindparam('nombre_pais',$txtPais);
        $sentencia->bindparam('nombre_capital',$txtCapital);
        $sentencia->execute();
        header('location: index.php');
        echo "Presionaste btnModificar";
    break;
    case "btnEliminar":
        $sentencia=$pdo->prepare("SELECT * FROM paises WHERE id=:id"); 
        $sentencia->bindparam('id',$txtId);
        $sentencia->execute();
        $paises=$sentencia->fetch(PDO::FETCH_LAZY);
        print_r($paises);
        echo $txtId;
        $sentencia=$pdo->prepare("DELETE FROM paises WHERE id=:id"); 
        $sentencia->bindparam('id',$txtId);
        $sentencia->execute();
        header('Location: index.php');
        echo$txtId;
        echo "Presionaste btnEliminar";    
    break;
    case "btnCancelar":
        echo $txtId;
        echo "Presionaste btnCancelar";
    break;
    case "Seleccionar":
        $accionAgregar="disable";
        $accionModificar=$accionEliminar=$accionCancelar="";
    break;
    case "Actualizar":
        //$accionAgregar="disabled";
        $accionModificar=$accionEliminar=$accionCancelar="";
        $mostrarModal=true;

        $sentencia=$pdo->prepare("SELECT * FROM paises WHERE id=:id"); 
        $sentencia->bindparam(':id',$txtId);
        $sentencia->execute();
        $paises=$sentencia->fetch(PDO::FETCH_LAZY);
        $txtPais=$paises['nombre_pais'];
        $txtCapital=$paises['nombre_capital'];
    break;
}
    $sentencia= $pdo->prepare("SELECT * FROM `paises` WHERE 1");
    $sentencia->execute();
    $listaPaises=$sentencia->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- codificacion uft-8 caracteres especiales -->
    <meta charset="UTF-8">
    <!-- compatibilidad navegadores -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- ayuda al responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paises</title>
    <!-- hoja de estilos bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- js bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</head>
<body>
    <!-- contenedor principal -->
    <div class="container">
        <!-- Formulario -->
        <form action="" method="post" enctype="multipart/form-data" class="form-data">  
            <!-- ID -->
            <input class="form-control" type="hidden" required name="txtForm" placeholder="" id="txtForm" required value="<?php echo$txtForm;?>">
            <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <!-- contenido modal -->
                        <div class="modal-content">
                            <!-- encabezado modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Paises</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- cuerpo modal -->
                            <div class="modal-body">
                                <div class="form-row">
                                    <!-- Id -->
                                    <input type="hidden" required name="txtId" value="<?php echo $txtId?>" placeholder="" id="txtId" required>
                                    <!-- Pais -->
                                    <div class="Form-group col-md-4">
                                        <label for="txtPais">Pais:</label>
                                        <input class="form-control" type="text" name="txtPais" placeholder="" id="txtPais" value="<?php echo$txtPais;?>" required>
                                        <br>
                                    </div>
                                    <!-- Capital -->
                                    <div class="Form-group col-md-4">
                                        <label for="txtCapital">Capital:</label>
                                        <input class="form-control" type="text" name="txtCapital" placeholder="" id="txtCapital" value="<?php echo$txtCapital;?>" required>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <!-- footer modal -->
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
                        <th>Pais</th>
                        <th>Capital</th>
                    </tr>
                </thead>

                <?php foreach ($listaPaises as $paises){ ?>
                    <tr>
                        <td><?php echo $paises['nombre_pais']; ?></td> 
                        <td><?php echo $paises['nombre_capital']; ?></td>     
                        <td>          
                            <form action="" method="post">
                                <input type="hidden" name="txtId" value="<?php echo $paises['id']; ?>">
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