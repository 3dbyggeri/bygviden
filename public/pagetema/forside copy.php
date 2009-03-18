<?
include_once('admin/util/dba.php');
include_once('admin/util/bruger.php');
include_once('admin/util/products.php');

class Forside extends View 
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

  function Forside()
  {
    $this->action = 'forside';
    $this->page = $_SERVER['PHP_SELF'].'?action=';
    $this->message = 'Rediger dine personlige oplysninger herunder';
    $this->dba = new dba();
    $this->headLine = 'Temaer';
    $this->icons = array('bath'=>'787878','energi'=>'959595','floor'=>'B0B0B0','obs'=>'3C3C3C','roof'=>'BCBCBC','stairs'=>'636363');
  }
  function LeftMenu()
  {
    return '';
  }
  function rightMenu()
  {
    require_once("admin/util/blog.php");
    $blog = new blog(new dba());
    $blogs = $blog->listing(1);

    if(!count($blogs)) return;
    
    $str = '<div id="posts">
                <h3> Nyt p&aring; Bygviden </h3>';

    for($i=0;$i<count($blogs);$i++)
    {
        $b = $blogs[$i]; 
        $str.='<div class="post">
                    <h4>'.$b['title'].'</h4>
                    <div class="date">'.$b['edited'].'</div>
                    <div class="post">'.$b['post'].'</div>
               </div>';
     }
    $str.= '</div>';
    return $str;
  }
  function temaBox($label,$category,$msg,$nr)
  {
   $icons = array_keys($this->icons);
   $icon = $icons[($nr-1)];
   $color = $this->icons[$icon];

    return '<table class="temabox" cellspacing="0" cellpadding="0">
               <tr>
                    <td class="brief" valign="top" 
                        style="border-left:1px solid #'.$color.';border-right:1px solid #'.$color.';border-top:1px solid #'.$color.'; ">
                        <h2>'.$label.'</h2>
                        '.$msg.'
                        '.$icon.'
                    </td>
               </tr> 
              <tr>
                    <td style="background-color:#'.$color.'" class="cat" nowrap>
                        
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td valign="bottom"> '.$category.' </td>
                                    <td align="right"><img src="tema/graphics/icons/'.$icon.'.gif" style="float:right"></td>
                               </tr>
                            </table>
                    </td>
              </tr>
            </table>';
  }
  function getTema($nr)
  {
    return $this->temaBox('tema:'.$nr,'tema:'.$nr,'bla,bla,bla',$nr);
  }
  function Content()
  {
    return '<table id="temagrid">
                <tr>
                    <td class="rpad">'.$this->getTema(1).'</td>
                    <td class="rpad">'.$this->getTema(2).'</td>
                    <td>'.$this->getTema(3).'</td>
                </tr>
                <tr>
                    <td class="rpad">'.$this->getTema(4).'</td>
                    <td class="rpad">'.$this->getTema(5).'</td>
                    <td>'.$this->getTema(6).'</td>
                </tr>
            </table>';
  }
}
?>
