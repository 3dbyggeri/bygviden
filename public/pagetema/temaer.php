<?
include_once('admin/util/dba.php');

class Tema extends View 
{
  var $page;
  var $menu;
  var $dba;
  var $bruger;
  var $message;
  var $user_id;
  var $producenter;
  var $productID;
  var $categoryID;

  function Tema()
  {
    $this->action = 'tema';
    $this->page = $_SERVER['PHP_SELF'].'?action=';
    $this->message = 'Rediger dine personlige oplysninger herunder';
    $this->dba = new dba();
    $this->headLine = '';
    $this->icons = array('bath'=>'787878','energi'=>'959595','floor'=>'B0B0B0','obs'=>'3C3C3C','roof'=>'BCBCBC','stairs'=>'636363');
  }
  function LeftMenu()
  {
    return 'left menu..';
  }
  function rightMenu()
  {
    return '';
  }
  function currentTema()
  {
    return 'current...';
  }

  function Content()
  {
    return '
            <table width="100%" cellpadding="0" cellspacing="0" border="1">
                <tr>
                    <td valign="top">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top">
                        '.$this->currentTema().'
                    </td>
               </tr>
            </table>
            ';
  }
}
?>
