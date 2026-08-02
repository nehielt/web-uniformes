<?php
    

    function b_combo($conn,$campo,$tabla,$id,$val)
    {
        $qry = "SELECT $campo FROM $tabla WHERE $id = '$val'";
        //echo $qry."<br><br><br>";
        $rcombo =  mysqli_query($conn,$qry) or die('La consulta falló: '.$mysqli -> error);
        //echo "se vino para aca...";
        $combo = mysqli_fetch_array($rcombo, MYSQLI_ASSOC);
        //echo "<pre>";print_r($combo);echo "</pre>";
        return $combo[$campo];
    }

    function ult_id_insert($conn,$tabla,$id) 
    {
        $qry_id = "select $id from $tabla order by $id desc";
        //echo $qry_id."<br><br><br>";
        $consulta = mysqli_query($conn,$qry_id) or die('La consulta falló: '.$mysqli -> error);
        $r_oper=mysqli_fetch_array($consulta, MYSQLI_ASSOC);
        return $r_oper[$id];
    }

     function sumar_dias_a_fecha($conn,$tabla,$id) 
    {
        $qry_id = "select DATE_ADD(fecha, INTERVAL 1 DAY) as ultimafecha FROM $tabla WHERE id = $id";
        //echo $qry_id."<br><br><br>";
        $consulta = mysqli_query($conn,$qry_id) or die('La consulta falló: '.$mysqli -> error);
        $r_oper=mysqli_fetch_array($consulta, MYSQLI_ASSOC);
        //echo "<pre>";print_r($r_oper);echo "</pre>";
        return $r_oper['ultimafecha'];
    }

    function invierte_fecha($fech,$opc)
    { // OPC TRUE para colocar en formato mysql : OPC FALSE para colocar fecha en formato para mostrar
        $fecha="";
        if (strlen($fech)>0)
        {
            if ($opc)
            {
                //01-02-2010 a 2010-02-01
                $yyyy = substr($fech, 6, 4);
                $mm = substr($fech, 3, 2);
                $dd = substr($fech, 0, 2);
                $fecha =$yyyy."-".$mm."-".$dd;
                //echo "AQUI---->".$fecha."<br>";
            }else{
                //2010-02-01 a 01-02-2010
                $yyyy = substr($fech, 0, 4);
                $mm = substr($fech, 5, 2);
                $dd = substr($fech, 8, 2);
                $fecha =$dd."/".$mm."/".$yyyy;
            }
        }
        return $fecha;
    }

    function saldomovimientos()
    {
        include ('conexion_db.php');
        $saldo = 0;
        $qry = "SELECT tbl_movimientos.id,tbl_movimientos.fecha,tbl_movimientos.idcuentas,tbl_movimientos.monto,tbl_cuentas.tipo 
        FROM tbl_movimientos 
        INNER JOIN tbl_cuentas ON tbl_movimientos.idcuentas = tbl_cuentas.id  
        WHERE tbl_movimientos.idcuentas <> 17 
        order by id";
    
        //echo $qry."<br>";
        $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $tipo = $row['tipo'];
            $monto = $row['monto'];
            if ($tipo == 'I') { $saldo = $saldo + $monto;} else { $saldo = $saldo - $monto;}
        }               

        $saldo = number_format($saldo , 2, '.', '');

        return $saldo;

    }

    function buscames($mes)
    {
        switch ($mes) {
            case 1:
                $nombre ='Enero';
                break; // Important to stop execution here
            case 2:
                $nombre ='Febrero';
                break; // Important to stop execution here
            case 3:
                $nombre ='Marzo';
                break; // Important to stop execution here
            case 4:
                $nombre ='Abril';
                break; // Important to stop execution here
            case 5:
                $nombre ='Mayo';
                break; // Important to stop execution here
            case 6:
                $nombre ='Junio';
                break; // Important to stop execution here
            case 7:
                $nombre ='Julio';
                break; // Important to stop execution here
            case 8:
                $nombre ='Agosto';
                break; // Important to stop execution here
            case 9:
                $nombre ='Septiembre';
                break; // Important to stop execution here
            case 10:
                $nombre ='Octubre';
                break; // Important to stop execution here
            case 11:
                $nombre ='Noviembre';
                break; // Important to stop execution here
            case 12:
                $nombre ='Diciembre';
                break; // Important to stop execution here
        }

        return $nombre;

    }

    function num_reg_total($qry) // Para una tabla cualquier bajo una condicion select determinar el nro de registros que trae
    {
        include ('conexion_db.php');
        //$qry = "select count(*) TOTAL from tbl_recibodepago where emision='$fecha'";
        //echo $qry."<br>";
        $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
        $val = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //echo "<pre>";print_r($val);echo "</pre>";
        return $val['TOTAL'];

    }

    function existe($tab, $id,$val) // Cod id directo sobre table
    {
        include ('conexion_db.php');
        $qry = "select count(*) t from $tab where $id = '$val'";
        //echo $qry."<br>";
        $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
        $reg = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //echo "<pre>";print_r($reg);echo "</pre>";
        if ($reg['t'] > 0){ return true;} else {return false;}
    }

    function existeproducto($tab, $condicion) // Cod id directo sobre table
    {
        include ('conexion_db.php');
        $qry = "select count(*) t from $tab where $condicion";
        //echo $qry."<br>";
        $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
        $reg = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //echo "<pre>";print_r($reg);echo "</pre>";
        if ($reg['t'] > 0){ return true;} else {return false;}
    }

    function redondear_dos_decimal($valor)
    {
        $m1 = $valor * 100;
       $float_redondeado=round($m1) / 100;
       return $float_redondeado;
    } 

    function busca_mes($mm)
    {
        $mes='';
        //echo $mes;
        switch ($mm) {
            case '01':
                $mes = 'ENERO';
                break;
            case '02':
                $mes = 'FEBRERO';
                break;
            case '03':
                $mes = 'MARZO';
                break;
            case '04':
                $mes = 'ABRIL';
                break;
            case '05':
                $mes = 'MAYO';
                break;
            case '06':
                $mes = 'JUNIO';
                break;
            case '07':
                $mes = 'JULIO';
                break;
            case '08':
                $mes = 'AGOSTO';
                break;
            case '09':
                $mes = 'SEPTIEMBRE';
                break;
            case '10':
                $mes = 'OCTUBRE';
                break;
            case '11':
                $mes = 'NOVIEMBRE';
                break;
            case '12':
                $mes = 'DICIEMBRE';
                break;
        }
        return $mes;
    }


    function saldos($id)
    {
        include ('conexion_db.php');
        $saldo = 0;
        $qry = "SELECT * FROM tbl_movimientos
         WHERE idapartamentos=$id and ((fecha <= '2026-01-01') OR (fecha >= '2026-02-18')) 
         ORDER BY fecha";
    
        //echo $qry."<br>";
        $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $idcuentas = $row['idcuentas'];
            $tipo = b_combo($mysqli,'tipo','tbl_cuentas','id',$idcuentas);;
            $monto = $row['monto'];
            if ($tipo == 'E') { $saldo = $saldo + $monto;} else { $saldo = $saldo - $monto;}
        }        
      
        $saldo = number_format($saldo , 2, '.', '');
         //echo $saldo."<br>";

        return $saldo;

    }