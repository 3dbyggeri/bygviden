<?php
require_once('admin/util/bruger.php');
require_once('admin/util/dba.php');
include_once('admin/util/paragraph.php');
include_once('admin/util/tema.php');
require_once('page/page.php');
//require_once('page/search.php');
include_once('page/aci.php');
require_once('pagetema/publication.php');
require_once('config.php');


$ICONS = array('bath'=>'787878','energi'=>'959595','floor'=>'B0B0B0',
               'obs'=>'3C3C3C','roof'=>'BCBCBC','stairs'=>'636363',
               'energi2'=>'B1B1B1','kloak'=>'888888','lov'=>'BFBFBF',
               'opmaaling'=>'9B9B9B','generic'=>'3C3C3C','nyhed'=>'3C3C3C','materialer'=>'3C3C3C');

function isLogged() { return $_SESSION['bruger_id']? true:false; }

function isAnyoneLogged() 
{ 
    return $_SESSION['bruger_id'] || $_SESSION['admin_id']; 
}

function checkLogOut() { if($_REQUEST['log_out']) session_unset() ; }

function checkBrancheValg()
{
  if(!$_SESSION['branche'] && $_COOKIE['branche'] ) $_SESSION['branche'] = $_COOKIE['branche'];

  if($_GET['fag'])
  {
      $_SESSION['branche'] = $_GET['fag'];
      unset($_SESSION['element']);
      setcookie ('branche', $_GET['fag'], time()+86400* 1000 ); //expire in a year
      return;
  }
  if(!$_SESSION['branche']) $_SESSION['branche'] ='general';
}

function findAction()
{
  if($_REQUEST['action']) return $_REQUEST['action'];
  if(is_numeric($_REQUEST['byg'])) return 'bygningsdel';
  if(is_numeric($_REQUEST['tema'])) return 'tema';
  return 'forside';
}
function getPage($action)
{
  switch($action)
  {
    case('forside'): return new Forside();
    case('tema'): return new Tema();
    case('bygningsdel'): return new Bygningsdel();
    case('search'): return new Search();
    case('minside'): return new MinSide();
    case('bibliotek'): return new Bibliotek();
    case('products'): return new Produkter();
    case('about'): return new About();
    case('tolerancer'): return new Tolerancer();
  }
  return new View();
}
function listWidget($name,$data,$add='alert(\'adding\')',$edit='alert(\'editing\')')
{
    $options ='';
    $list_values = '';
    for($i=0;$i<count($data);$i++)
    {
        $options.='<option value="'.$data[$i]['key'].'">'.$data[$i]['value'].'</option>';
        if($list_values!='') $list_values.=',';
        $list_values.= $data[$i]['key'];
    }

    $str = '
        <input type="hidden" name="list_'.$name.'"  id="list_'.$name.'" value="'.$list_values.'" />            
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <select size="8" style="width:400px" id="select_'.$name.'">
                        '.$options.'
                    </select>
                </td>
                <td valign="middle" style="padding-left:4px">

                    <table cellpadding="3" cellspacing="0" border="0">
                        <tr>
                            <td><a href="javascript:'.$edit.'" title="Redigere"><img src="tema/graphics/edit.png" border="0"></a></td>
                       </tr>
                        <tr>
                            <td><a href="javascript:'.$add.'" title="Tilf&oslash;j"><img src="admin/graphics/add.png" border="0"></a></td>
                       </tr>
                        <tr>
                            <td><a href="javascript:doubleListBox.deleting(\'select_'.$name.'\',\'list_'.$name.'\')" title="Fjern"><img src="admin/graphics/delete.gif" border="0"></a></td>
                       </tr>
                        <tr>
                            <td><a href="javascript:doubleListBox.up(\'select_'.$name.'\',\'list_'.$name.'\')" title="Flyt op"><img src="tema/graphics/move_up.gif" border="0"></a></td>
                       </tr>
                        <tr>
                            <td><a href="javascript:doubleListBox.down(\'select_'.$name.'\',\'list_'.$name.'\')" title="Flyt ned"><img src="tema/graphics/move_down.gif" border="0"></a></td>
                       </tr>
                      </table>
                </td>
           </tr>
        </table>';
    return $str;
}
?>
