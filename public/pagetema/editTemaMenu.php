<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../admin/util/tema.php');
require_once('../config.php');
session_start();        

function listWidget($name,$data,$add='alert(\'adding\')',$edit='alert(\'editing\')')
{
    $options ='';
    $list_values = '';
    for($i=0;$i<count($data);$i++)
    {
        if(!$data[$i]['value']) continue;
        $options.='<option value="'.$data[$i]['key'].'">'.$data[$i]['value'].'</option>';
        if($list_values!='') $list_values.=',';
        $list_values.= $data[$i]['key'];
    }

    $str = '
        <input type="hidden" name="list_'.$name.'"  id="list_'.$name.'" value="'.$list_values.'" />            
        <input type="hidden" name="original_list"  value="'.$list_values.'" />            
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <select size="16" style="width:500px" id="select_'.$name.'">
                        '.$options.'
                    </select>
                </td>
                <td valign="middle" style="padding-left:4px">

                    <table cellpadding="3" cellspacing="0" border="0">';
     if($edit)
     {
        $str.='<tr> <td><a href="javascript:'.$edit.'" title="Redigere"><img src="tema/graphics/edit.png" border="0"></a></td> </tr>';
     }
     if($add)
     {
        $str.=' <tr> <td><a href="javascript:'.$add.'" title="Tilf&oslash;j"><img src="admin/graphics/add.png" border="0"></a></td> </tr>';
     }
     $str.='<tr>
                <td><a href="javascript:doubleListBox.deleting(\'select_'.$name.'\',\'list_'.$name.'\')" title="Fjern"><img src="../admin/graphics/delete.gif" border="0"></a></td>
            </tr>
            <tr>
                <td><a href="javascript:doubleListBox.up(\'select_'.$name.'\',\'list_'.$name.'\')" title="Flyt op"><img src="../tema/graphics/move_up.gif" border="0"></a></td>
            </tr>
            <tr>
                <td><a href="javascript:doubleListBox.down(\'select_'.$name.'\',\'list_'.$name.'\')" title="Flyt ned"><img src="../tema/graphics/move_down.gif" border="0"></a></td>
            </tr>
        </table>
                </td>
           </tr>
        </table>';
    return $str;
}

$tema = new temaDoc(new dba());

if($_POST['saving']=='1')
{
    $tema->updateTemaList($_POST['list_temaer'],$_POST['original_list']);
    die('<script>top.location.reload(false);</script>');
}

$temaer = (is_numeric($_SESSION['bruger_id']) && !$tema->isEditor())? $tema->allOwned($_SESSION['bruger_id']):$tema->allPublic();
?>
<html>
    <head>
        <title>Rediger Tema Menu</title>
        <style>
            body { font-size:12px;font-family:arial,sans-serif; }
        </style>
        <script src="../tema/doubleListBox.js"></script>
    </head>
    <body bgcolor="#FFFFFF">
        <form name="myform" method="post">
            <input type="hidden" name="saving" value="1">
            <?=listWidget('temaer',$temaer,'','')?>
            <br />
            <input type="submit" value="Fortryd" 
                onclick="top.GB_hide()"
                name="submited" class="knap">
            <input type="submit" 
                value="Gemt" name="submited" class="knap">

        </form>
    </body>
</html>
