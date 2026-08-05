<html>
    <script type="text/javascript" src="funciones_javascript.js"></script>
    <?php 
        include_once 'auth.php';
        include_once 'conexion_db.php';
        include ('funciones.php');

        require_login();

        echo "<pre>";print_r($_GET);print_r($_POST);echo "</pre>";

        $metodo = trim($_GET['metodo']);

        // AQUI SE GUARDA UN NUEVO ARTICULO
        if ($metodo == 'articulosguardar')
        {
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $ins = "insert into articulos (nombre) values ('$descripcion')";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('articulos','INSERT','Creó artículo: $descripcion',$ins);
        
    ?>
            <script language=javascript>
                alert('AGREGADO EL ARTICULO');
                window.location.href='articulosvista.php';
            </script>  
	<?php 
        }

         // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DEL  ARTICULO
        if ($metodo == 'articulosmodificar')
        {
            $id = trim($_POST['hiddenid']);    
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $upd = "update articulos set nombre = '$descripcion' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
            registro_auditoria('articulos','UPDATE','Modificó artículo ID $id a: $descripcion',$upd);

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DEL ARTICULO');
                window.location.href='articulosvista.php';
            </script>  
	<?php 
        }

                // AQUI SE GUARDA UN NUEVO COLOR
        if ($metodo == 'coloresguardar')
        {
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $ins = "insert into colores (nombre) values ('$descripcion')";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('colores','INSERT','Creó color: $descripcion',$ins);
        
    ?>
            <script language=javascript>
                alert('AGREGADO EL COLOR');
                window.location.href='coloresvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DEL  COLOR
        if ($metodo == 'coloresmodificar')
        {
            $id = trim($_POST['hiddenid']);    
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $upd = "update colores set nombre = '$descripcion' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
            registro_auditoria('colores','UPDATE','Modificó color ID $id a: $descripcion',$upd);

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DEL COLOR');
                window.location.href='coloresvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA UNA NUEVA TALLA
        if ($metodo == 'tallasguardar')
        {
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $ins = "insert into tallas (nombre) values ('$descripcion')";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('tallas','INSERT','Creó talla: $descripcion',$ins);
        
    ?>
            <script language=javascript>
                alert('AGREGADA LA TALLA');
                window.location.href='tallasvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DE LA  TALLA
        if ($metodo == 'tallasmodificar')
        {
            $id = trim($_POST['hiddenid']);    
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $upd = "update tallas set nombre = '$descripcion' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DE LA TALLA');
                window.location.href='tallasvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA UN NUEVO CARGO
        if ($metodo == 'cargosguardar')
        {
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $ins = "insert into cargos (nombre) values ('$descripcion')";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('cargos','INSERT','Creó cargo: $descripcion',$ins);
        
    ?>
            <script language=javascript>
                alert('AGREGADO EL CARGO');
                window.location.href='cargosvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DEL CARGO
        if ($metodo == 'cargosmodificar')
        {
            $id = trim($_POST['hiddenid']);    
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $upd = "update cargos set nombre = '$descripcion' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DEL CARGO');
                window.location.href='cargosvista.php';
            </script>  
	<?php 
        }

         // AQUI SE GUARDA UNA NUEVA UBICACION
        if ($metodo == 'ubicacionesguardar')
        {
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $ins = "insert into ubicaciones (nombre) values ('$descripcion')";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('ubicaciones','INSERT','Creó ubicación: $descripcion',$ins);
        
    ?>
            <script language=javascript>
                alert('AGREGADA LA UBICACION');
                window.location.href='ubicacionesvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DE LA UBICACION
        if ($metodo == 'ubicacionesmodificar')
        {
            $id = trim($_POST['hiddenid']);    
            $descripcion = strtoupper(trim($_POST['descripcion']));

            $upd = "update ubicaciones set nombre = '$descripcion' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DE LA UBICACION');
                window.location.href='ubicacionesvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LA MODIFICACION DE LOS DATOS DEL EMPLEADO
        if ($metodo == 'empleadosactivosmodificar')
        {
            $id = trim($_POST['hiddenid']);  
            $cedula = trim($_POST['cedula']);  
            $nombres = strtoupper(trim($_POST['nombres']));
            $apellidos = strtoupper(trim($_POST['apellidos']));
            $cargo = trim($_POST['seleccioncargos']); 
            $ubicacion = trim($_POST['seleccionubicaciones']); 
            $fechaingreso = trim($_POST['ingreso']); 
            $ingreso = invierte_fecha(($fechaingreso),1);

            $upd = "update empleados set cedula='$cedula',nombres='$nombres',apellidos='$apellidos',cargo=$cargo,ingreso='$ingreso',ubicacion=$ubicacion where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
            registro_auditoria('empleados','UPDATE','Modificó empleado ID $id','$upd');

        
    ?>
            <script language=javascript>
                alert('MODIFICADO LOS DATOS DE LA UBICACION');
                window.location.href='empleadosactivosvista.php';
            </script>  
	<?php 
        }

        // AQUI SE ACTIVA O RETIRA UN EMPLEADO
        if ($metodo == 'empleadosactivarretirar')
        {
            $id = trim($_POST['hiddenid']); 
            $status = trim($_POST['hiddenstatus']);  

            if ($status == 'RETIRAR')
            { $upd = "update empleados set activo=0 where id=$id";}
            else
            { $upd = "update empleados set activo=1 where id=$id";}
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
            registro_auditoria('empleados','UPDATE','Cambio estado empleado ID $id a $status','$upd');

        
    ?>
            <script language=javascript>
                alert('SE HA CAMBIADO EL STATUS DEL EMPLEADO');
                window.location.href='empleadosactivosvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA UN NUEVO EMPLEADO
        if ($metodo == 'empleadosagregar')
        {
            $expediente = trim($_POST['expediente']);  
            $cedula = trim($_POST['cedula']);  
            $nombres = strtoupper(trim($_POST['nombres']));
            $apellidos = strtoupper(trim($_POST['apellidos']));
            $cargo = trim($_POST['seleccioncargos']); 
            $ubicacion = trim($_POST['seleccionubicaciones']); 
            $ingreso = trim($_POST['ingreso']); 

            $ins = "insert into empleados (expediente,cedula,nombres,apellidos,cargo,ingreso,ubicacion,activo) values ('$expediente','$cedula','$nombres','$apellidos',$cargo,'$ingreso','$ubicacion',1)";
            $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
            registro_auditoria('empleados','INSERT','Creó empleado $nombres $apellidos con expediente $expediente','$ins');
        
      
    ?>
            <script language=javascript>
                alert('AGREGADO UN NUEVO EMPLEADO');
                window.location.href='empleadosactivosvista.php';
            </script>  
	<?php 
        }

         // AQUI SE GUARDA UN NUEVO PRODUCTO
        if ($metodo == 'productosagregar')
        {
            $articulos = trim($_POST['seleccionarticulos']);  
            $colores = trim($_POST['seleccioncolores']);  
            $tallas = trim($_POST['selecciontallas']);
            $generos = trim($_POST['selecciongeneros']);
            $minimos = trim($_POST['minimos']); 
            $existencias = trim($_POST['existencias']); 
            $precio = trim($_POST['precios']); 
            $precio = filter_var( str_replace(',', '.', $precio), FILTER_VALIDATE_FLOAT);

            $condicion = 'idarticulos='.$articulos.' and idcolores='.$colores.' and idtallas='.$tallas.' and idgeneros='.$generos;

            if (is_float($precio))
            { 
                if (existeproducto('inventario',$condicion))
                {
    ?>
                    <script language=javascript>
                        alert('PRODUCTO YA EXISTE');
                        window.location.href='productosagregar.php';
                    </script>  
	<?php
                }else{
                    $ins = "insert into inventario (idarticulos,idcolores,idtallas,idgeneros,minimo,existencia,precio) values ($articulos,$colores,$tallas,$generos,$minimos,$existencias,$precio)";
                    $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
                    registro_auditoria('inventario','INSERT','Creó producto inventario idarticulos=$articulos,color=$colores,talla=$tallas,genero=$generos','$ins');
        
      
    ?>
                <script language=javascript>
                    alert('AGREGADO UN NUEVO PRODUCTO');
                    window.location.href='productosvista.php';
                </script>  
	<?php 
                }
            }else{ 
    ?>
            <script language=javascript>
                alert('EL PRECIO NO ES UN VALOR VALIDO');
                window.location.href='productosagregar.php';
            </script>  
	<?php
            }
        }

         // AQUI SE MODIFICA EL PRECIO DEL PRODUCTO
        if ($metodo == 'productospreciosmodificar')
        {
            $id = trim($_POST['hiddenid']);
            $nombre = trim($_POST['hiddennombre']);   
            $precio = trim($_POST['precios']); 
            $precio = filter_var( str_replace(',', '.', $precio), FILTER_VALIDATE_FLOAT);

            if (is_float($precio))
            { 
                $upd = "update inventario set precio = '$precio' where id=$id";
                $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
 
    ?>
                <script language=javascript>
                    alert('ACTUAIZADO EL PRECIO DEL PRODUCTO');
                    window.location.href='productosvista.php';
                </script>  
	<?php 
            }else{ 
    ?>
            <script language=javascript>
                alert('EL PRECIO NO ES UN VALOR VALIDO');
                window.location.href='productosprecios.php?id=<?php echo $id?>&nombre=<?php echo $nombre?>';
            </script>  
	<?php
            }
        }

         // AQUI SE MODIFICA LAS UNIDADES DEL PRODUCTO
        if ($metodo == 'productosunidadesmodificar')
        {
            $id = trim($_POST['hiddenid']);
            $nombre = trim($_POST['hiddennombre']);   
            $unidades = trim($_POST['unidades']); 

            $upd = "update inventario set existencia = '$unidades' where id=$id";
            $result = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
            registro_auditoria('inventario','UPDATE','Actualizó existencias producto ID $id a $unidades','$upd');
 
    ?>
            <script language=javascript>
                alert('ACTUALIZADA LA CANTIDAD DE UNIDADES DEL PRODUCTO');
                window.location.href='productosvista.php';
            </script>  
	<?php 
        }

        // AQUI SE GUARDA LAS ORDENES
        if ($metodo == 'ordenesguardar')
        {
            $guardar = 1;
            $idempleados = trim($_GET['idempleados']);
            $hoy = date('Y-m-d');
            $nombreUsuario = trim($_POST['nombre_usuario'] ?? '');
            if ($nombreUsuario === '') {
                $nombreUsuario = !empty($_SESSION['nombre_completo']) ? trim($_SESSION['nombre_completo']) : (!empty($_SESSION['usuario']) ? trim($_SESSION['usuario']) : '');
            }
            if ($nombreUsuario !== '') {
                $checkColumn = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'ordenes' AND column_name = 'usuario_nombre'";
                $resColumn = mysqli_query($mysqli, $checkColumn);
                if ($resColumn && mysqli_num_rows($resColumn) === 0) {
                    mysqli_query($mysqli, "ALTER TABLE ordenes ADD COLUMN usuario_nombre VARCHAR(150) DEFAULT NULL");
                }
            }
            //echo $hoy;

            //$ultimaorden = ult_id_insert($mysqli,'ordenes','orden');
            //if ($ultimaorden == NULL) {$ultimaorden = 1;}else{$ultimaorden = $ultimaorden + 1; }
            //echo $ultimaorden;
            $cantidad = $_POST['cantidad'];
            $total = count($cantidad);
            //echo $total;

            // PRIMERO VERIFICAMOS LA EXISTENCIA DE CADA ARTICULO
    
            for ($i = 1; $i <= $total; $i++)
            {
                if ($cantidad[$i] <> '')
                {
                    $existencia = b_combo($mysqli,'existencia','inventario','id',$i);
                    //echo $existencia;
                    if ($cantidad[$i] <= $existencia)
                    {
                        //echo "El producto es: $i y la cantidad entregada es: $cantidad[$i] <br>";
                    }else{
                        $guardar = 0;
    ?>
                        <script language=javascript>
                            alert('NO HAY SUFICIENTES UNIDADES DE UNO DE LOS PRODUCTOS');
                            window.location.href='ordenesseleccionarticulos.php?idempleados=<?php echo $idempleados?>';
                        </script> 
    <?php  
                    }
                }
            }

            // SEGUNDO GUARDAMOS DATOS DE LA ORDEN
            if ($guardar == 1)
            {
                // AQUI GUARDAMOS LA ORDEN
                $nombreUsuarioEsc = mysqli_real_escape_string($mysqli, $nombreUsuario);
                $ins = "insert into ordenes (fecha,empleado,usuario_nombre) values ('$hoy',$idempleados,'$nombreUsuarioEsc')";
                $result = mysqli_query($mysqli,$ins) or die('Agregar falló: '.$mysqli -> error);
                registro_auditoria('ordenes','INSERT','Creó orden para empleado ID $idempleados por $nombreUsuarioEsc','$ins');

                $ultimaorden = ult_id_insert($mysqli,'ordenes','id');
                // AQUI GUARDAMOS LOS DETALLES DE LA ORDEN
                for ($i = 1; $i <= $total; $i++)
                {
                    if ($cantidad[$i] <> '')
                    {

                        $ins2 = "insert into detalles (orden,inventario,cantidad) values ($ultimaorden,$i,$cantidad[$i])";
                        $result2 = mysqli_query($mysqli,$ins2) or die('Agregar falló: '.$mysqli -> error);
                        registro_auditoria('detalles','INSERT','Agregó detalle orden $ultimaorden, inventario ID $i, cantidad $cantidad[$i]','$ins2');

                        $existencia = b_combo($mysqli,'existencia','inventario','id',$i);
                        $resta = $existencia - $cantidad[$i];

                        $upd = "update inventario set existencia = '$resta' where id=$i";
                        $result3 = mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
                        registro_auditoria('inventario','UPDATE','Reducido inventario ID $i a $resta tras orden $ultimaorden','$upd');
                        //echo $resta;
                        
                    }
                }
            
 
    ?>
            <script language=javascript>
                alert('ORDEN CREADA');
                window.location.href='ordenesvista.php';
            </script>  
	<?php 
            }
        }

        // AQUI SE MODIFICA LA ORDEN
        if ($metodo == 'ordenesmodificar')
        {
            $guardar = 1;
            $idorden = intval($_GET['id'] ?? 0);
            $nombreUsuario = trim($_POST['nombre_usuario'] ?? '');
            if ($nombreUsuario === '') {
                $nombreUsuario = !empty($_SESSION['nombre_completo']) ? trim($_SESSION['nombre_completo']) : (!empty($_SESSION['usuario']) ? trim($_SESSION['usuario']) : '');
            }

            $qryOrden = "select * from ordenes where id=$idorden";
            $resultOrden = mysqli_query($mysqli,$qryOrden) or die('La consulta falló: '.$mysqli -> error);
            $rowOrden = mysqli_fetch_array($resultOrden, MYSQLI_ASSOC);
            $idempleados = trim($rowOrden['empleado']);

            $detallesActuales = array();
            $qryDetalles = "select * from detalles where orden=$idorden";
            $resultDetalles = mysqli_query($mysqli,$qryDetalles) or die('La consulta falló: '.$mysqli -> error);
            while($rowDetalle = mysqli_fetch_array($resultDetalles, MYSQLI_ASSOC))
            {
                $detallesActuales[$rowDetalle['inventario']] = $rowDetalle['cantidad'];
            }

            $cantidad = $_POST['cantidad'];
            $total = count($cantidad);

            for ($i = 1; $i <= $total; $i++)
            {
                if ($cantidad[$i] <> '')
                {
                    $existencia = b_combo($mysqli,'existencia','inventario','id',$i);
                    $cantidadAnterior = isset($detallesActuales[$i]) ? $detallesActuales[$i] : 0;
                    $disponible = $existencia + $cantidadAnterior;
                    if ($cantidad[$i] > $disponible)
                    {
                        $guardar = 0;
    ?>
                        <script language=javascript>
                            alert('NO HAY SUFICIENTES UNIDADES DE UNO DE LOS PRODUCTOS');
                            window.location.href='ordenesmodificar.php?id=<?php echo $idorden?>';
                        </script>
    <?php
                    }
                }
            }

            if ($guardar == 1)
            {
                foreach ($detallesActuales as $idinventario => $cantidadAnterior)
                {
                    $existencia = b_combo($mysqli,'existencia','inventario','id',$idinventario);
                    $nuevaExistencia = $existencia + $cantidadAnterior;
                    $updInventario = "update inventario set existencia = '$nuevaExistencia' where id=$idinventario";
                    mysqli_query($mysqli,$updInventario) or die('El Update falló: '.$mysqli -> error);
                    registro_auditoria('inventario','UPDATE','Restauró inventario ID $idinventario a $nuevaExistencia por modificación de orden $idorden','$updInventario');
                }

                $del = "delete from detalles where orden=$idorden";
                mysqli_query($mysqli,$del) or die('El Delete falló: '.$mysqli -> error);
                registro_auditoria('detalles','DELETE','Eliminó detalles previos de la orden $idorden','$del');

                for ($i = 1; $i <= $total; $i++)
                {
                    if ($cantidad[$i] <> '')
                    {
                        $ins2 = "insert into detalles (orden,inventario,cantidad) values ($idorden,$i,$cantidad[$i])";
                        mysqli_query($mysqli,$ins2) or die('Agregar falló: '.$mysqli -> error);
                        registro_auditoria('detalles','INSERT','Actualizó detalle orden $idorden, inventario ID $i, cantidad $cantidad[$i]','$ins2');

                        $existencia = b_combo($mysqli,'existencia','inventario','id',$i);
                        $resta = $existencia - $cantidad[$i];

                        $upd = "update inventario set existencia = '$resta' where id=$i";
                        mysqli_query($mysqli,$upd) or die('El Update falló: '.$mysqli -> error);
                        registro_auditoria('inventario','UPDATE','Reducido inventario ID $i a $resta tras modificación de orden $idorden','$upd');
                    }
                }

                if ($nombreUsuario !== '') {
                    $nombreUsuarioEsc = mysqli_real_escape_string($mysqli, $nombreUsuario);
                    $updOrden = "update ordenes set usuario_nombre = '$nombreUsuarioEsc' where id=$idorden";
                    mysqli_query($mysqli,$updOrden) or die('El Update falló: '.$mysqli -> error);
                    registro_auditoria('ordenes','UPDATE','Modificó orden ID $idorden por $nombreUsuarioEsc','$updOrden');
                }

    ?>
            <script language=javascript>
                alert('ORDEN MODIFICADA');
                window.location.href='ordenesvista.php';
            </script>
	<?php
            }
        }