<?
include_once('admin/util/dba.php');
include_once('admin/util/bruger.php');
include_once('chimp/inc/store-address.php');

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
  function temaBox($label,$category,$msg,$nr,$icon='',$tema_id='')
  {
    global $ICONS;
    $icons = array_keys($ICONS);
    if(!$icon) $icon = $icons[($nr-1)];

    if($nr== '6') $icon = 'materialer';
    if($nr== '7') $icon = 'nyhed';
    $color = $ICONS[$icon];
    
    $link = '';
    $link_edit = '';
    $edit = '';
    $news = '';
    $height = '';
    if($nr== '7')
    {
      $icon = 'nyhed';
      //$height = ' style="height:528px" ';
      /*
      $news = '
              <div id="subscribe" style="background-color:#e3e3e3;padding:4px;margin-top:20px">
		          <form id="signup" action="'.$_SERVER['PHP_SELF'].'" method="get">
                <label for="email" id="address-label">Din email adresse</label>
                <div>
                <input type="text" name="email" id="email" style="width:150px"/>
                <input type="submit" name="submit" value="Tilmeld" class="btn" alt="Tilmeld" />
                </div>
                <div id="response" style="margin-top:5px">
                </div>
              
              <div id="no-spam" style="margin-top:10px"><i>Vi h&aring;ndterer din email adresse som en fortrolig oplysning, 
              og viderebringer den derfor ikke til andre.</i></div>
            </form>      
            <script type="text/javascript" src="/chimp/js/prototype.js"></script>
            <script type="text/javascript" src="/chimp/js/mailing-list.js"></script>
            </div>
      ';*/
      $news = '<p><iframe allowtransparency="true" vspace="5" hspace="5" marginheight="5" marginwidth="5" src="/nyhedsbrev/index.php" name="nyhedsbrev" frameborder="0" height="400px" scrolling="no" width="325px"></iframe></p>';
    }

    $tema = new temaDoc(new dba() );
    if($tema_id )
    {
        $l ='onclick="document.location.href=\'?action=tema&id='.$tema_id.'\'" style="cursor:pointer"';
        if($nr=='6') $l ='onclick="document.location.href=\'?action=bygningsdel\'" style="cursor:pointer"';
        if($nr=='7') $l = '';

        if($tema->isEditor()) { $link_edit =$l; } else { $link = $l; }
    }
    $edit = ($tema->isEditor())? '<a href="javascript:editTemaBox('.$nr.')" style="float:right"><img src="tema/graphics/edit.png" border="0"></a>':'';

    
     return '
            <table width="100%" border="0" class="temabox" cellspacing="0" cellpadding="0" '. $height .$link.' id="box_'.$nr.'">
               <tr>
                    <td class="brief" valign="top" 
                        style="border-left:1px solid #'.$color.';border-right:1px solid #'.$color.';border-top:1px solid #'.$color.'; ">
                        '.$edit.' 
                        <div class="meat">
                        <h2>'.$label.'</h2>
                        '.$news.'
                        '.$msg.'
                        </div>
                        <img src="graphics/transp.gif" width="250" height="1" border="0"/><br>
                    </td>
               </tr> 
              <tr>
                    <td valign="bottom" style="background-color:#'.$color.';" class="cat" nowrap>
                        
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" '.$link_edit.'>
                                <tr>
                                    <td valign="bottom" style="padding-left:2px"> '.$category.' </td>
                                    <td align="right"><img src="tema/graphics/icons/'.$icon.'.gif" style="float:right"></td>
                               </tr>
                            </table>
                    </td>
              </tr>
            </table>';
  }
  function getTema($nr,$temaer)
  {
    $rec = $temaer[$nr];
    if(!$rec) return $this->temaBox('Overskrift','Kategori' ,'Kommentar',$nr);

    return $this->temaBox($rec['forside_name'],$rec['forside_category'],$rec['forside_resume'],$nr,$rec['icon'],$rec['id']);
  }

  function Content()
  {
    $tema = new temaDoc(new dba() );
    $temaer = $tema->getForsideTema();

    return '<table border="0" id="temagrid" width="100%">
                <tr>
                    <td class="rpad" width="33%">'.$this->getTema('1',$temaer).'</td>
                    <td class="rpad" width="33%">'.$this->getTema('2',$temaer).'</td>
                    <td width="33%" rowspan="3" valign="top">'.$this->getTema('7',$temaer).'</td>
                </tr>
                <tr>
                    <td class="rpad">'.$this->getTema('3',$temaer).'</td>
                    <td class="rpad">'.$this->getTema('4',$temaer).'</td>
                </tr>
                <tr>
                    <td class="rpad">'.$this->getTema('5',$temaer).'</td>
                    <td class="rpad">'.$this->getTema('6',$temaer).'</td>
                </tr>
            </table>';

    return '<table border="0" id="temagrid" width="100%">
                <tr>
                    <td class="rpad" width="33%">'.$this->getTema('1',$temaer).'</td>
                    <td class="rpad" width="33%">'.$this->getTema('2',$temaer).'</td>
                    <td width="33%">'.$this->getTema('3',$temaer).'</td>
                </tr>
                <tr>
                    <td class="rpad">'.$this->getTema('4',$temaer).'</td>
                    <td class="rpad">'.$this->getTema('5',$temaer).'</td>
                    <td>'.$this->getTema('6',$temaer).'</td>
                </tr>
            </table>';
  }
}
?>
