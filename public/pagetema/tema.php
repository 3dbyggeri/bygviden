<?
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
    $tema = new temaDoc(new dba()); 
    $temaer = (is_numeric($_SESSION['bruger_id']))? $tema->allPublicAndOwned($_SESSION['bruger_id']):$tema->allPublic();
    $priv=''; 


    $str = '<div id="left_menu">
                <table cellpadding="0" cellspacing="0" border="0" >
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                        <td valign="top">';

    $str.='<ul id="tema_menu">';

    for($i=0;$i< count($temaer);$i++) 
    {
        if($temaer[$i]['id']=='0') continue;
        if(!$temaer[$i]['name']) continue;

        $sel=($temaer[$i]['id']==$_REQUEST['tema'])?'class="selected"':'';
        if($temaer[$i]['private']=='y')
        {
            $priv.='<li>';
            $priv.='<a href="?tema='.$temaer[$i]['id'].'" '.$sel.'>'. $temaer[$i]['name'].'</a>';
            $priv.='</li>';
            continue;
        }
        $str.='<li>';
        $str.='<a href="?tema='.$temaer[$i]['id'].'" '.$sel.'>'. $temaer[$i]['name'].'</a>';
        $str.='</li>';
    }
    if($priv)
    {
        $str.='<li><br><li><h2>Egne Temaer</h2></li>'.$priv;
    }
                               
    $str.='</ul>';

    if($tema->isEditor() || $priv != '')
    {
        $str.='<a href="javascript:editTemaMenu()" class="editing"><img src="tema/graphics/edit.gif" align="absmiddle" border="0" hspace="4" vspace="10"/>Rediger Temaer Menu</a>';
    }
                
    $str.='</td> </tr> </table> </div>';

    return $str;
  }
  function rightMenu()
  {
    $paragraph = new Paragraph(new dba() );
    $tema = new temaDoc(new dba()); 

    $url = (isAnyoneLogged())? '?tema=-1':'javascript:opretTema()';
    
    $str = '';
    if(is_numeric($_REQUEST['tema'])) 
    {
        $tema->load($_REQUEST['tema']);

        if($tema->editing() && $tema->isNormalUser())
        {
            $str.='<h2>Fremgangsm&aring;den</h2>
                  <ol>
                       <li>Navngiv dit tema og v&aelig;lg ikon til bj&aelig;lken</li>
                       <li>Skriv din tekst til temaet</li>
                       <li>V&aelig;lg de kilder du vil linke til - fra bygvidens Bibliotek eller fra internettet</li>
                       <li>V&aelig;lg de Bygningsdele der er relevante for dit tema</li>
                       <li>Skriv dit navn og kontaktdata</li>
                    </ol>
                    <br>';
        }
        $str.= $tema->author($tema->editing());
    }

    
    if(!$tema->editing() && !$_REQUEST['page'])
    {
        //to load the front page author
        if(!is_numeric($_REQUEST['tema'])) 
        {
            $tema->load('0');
            if($tema->isEditor()) $str.= '<form name="aform" method="post">
                                            <input type="hidden" id="author_id" name="author_id" value="'.$tema->prop('author_id','').'" />';
            $str.= $tema->author($tema->isEditor());

            if($tema->isEditor()) $str.='<br><a class="redbutton" href="javascript:set_author()">GEMT</a></form>';
        }

        if(!is_numeric($_REQUEST['tema'])) 
        {
            $str.= '<div class="right_section">
                        <h2>Opret eget tema</h2>
                        <div style="margin-bottom:5px"> 
                        '. $paragraph->body('Opret eget tema forklaring').'
                        </div>
                        <a href="'.$url.'" class="redbutton">Opret</a>
                    </div>';
        }
    }

   $str.= '<div id="right_section">
                <div style="margin-top:10px;padding-top:10px"> 
                '. $paragraph->body('Right Column '.$_REQUEST['tema'].' '.$_REQUEST['page']).'
                </div>
            </div>';

    if(is_numeric($_REQUEST['tema'])) $str.= '<div id="right_section">'.$this->adds().'</div>';
    return $str;
  }
  function adds()
  {
      return '<style>#rightgoogleadd {display:none; }</style>
              <div style="margin-top:10px;margin-left:15px;">
              <script type="text/javascript"><!--
              google_ad_client = "pub-6194810790403167";
              /* LandingsPageRightHightFormat */
              google_ad_slot = "7468654187";
              google_ad_width = 160;
              google_ad_height = 600;
              //-->
              </script>
              <script type="text/javascript"
              src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
              </script></div>';
  }
  
  function intro()
  {
    $paragraph = new Paragraph(new dba() );
    return $paragraph->body('Introduktion til temaer');
  }
  function TemaPage($page_id)
  {
    $tema = new temaDoc(new dba()); 
    $props = $tema->pageExtendedProperties($page_id);

    $prev='';
    $next = '';
    if($props['prev'])
    {
        $prev = '<a href="?tema='.$props['tema_id'].'&page='.$props['prev']['id'].'" class="prev_page">'.$props['prev']['name'].'</a>';
    }
    if($props['next'])
    {
        $next= '<a href="?tema='.$props['tema_id'].'&page='.$props['next']['id'].'" class="next_page">'.$props['next']['name'].'</a>';
    }

    $edit = '';
    if($tema->isEditor()) $edit ='<br><script>var page_edit = true;</script><a class="redbutton" href="javascript:displayListEditor(\'Page\','.$props['tema_id'].','.$page_id.',700,600)">Rediger</a><br>';

    return '<div id="tema_header" class="icon_'.$props['tema_icon'].'">
                <table border="0" style="height:38px" >
                    <tr>
                        <td valign="bottom">
                                '.$props['tema_name'].'
                        </td>
                    </tr>
                </table>
            </div>

           <h2>'.$props['name'].'</h2>
           <p>'.$props['body'].'</p>

           <div id="page_bottom_links">
           '.$prev.'
           :: <a href="?tema='.$props['tema_id'].'">'. $props['tema_name'] .'</a> ::
           '.$next.'
           </div>
           '.$edit;
           
  }

  function currentTema()
  {
    if(is_numeric($_REQUEST['page'])) return $this->TemaPage($_REQUEST['page']);
    if(!is_numeric($_REQUEST['tema'])) return $this->intro();

    $tema = new temaDoc(new dba()); 
    $tema->load($_REQUEST['tema']);
    if(!$tema->props['id']) die('<script>document.location.href="?action=tema";</script>');
    
    $str =''; 
    if($tema->editing()) $str.= '<form name="myform" method="post">
                                <script src="tema/doubleListBox.js"></script>
                                <input type="hidden" name="save" value="1" />
                                <input type="hidden" id="author_id" name="author_id" value="'.$tema->prop('author_id','').'" />
                                <input type="hidden" name="tema" value="'.$_REQUEST['tema'].'" />';
    $kilder = $tema->kildeList($_REQUEST['tema']);
    if($kilder) $kilder = '<div class="kilder"><h2>Kilder</h2>'.$kilder.'</div>';

    $bygningsdel = $tema->bygningsdel($_REQUEST['tema']);
    if($bygningsdel) $bygningsdel = '<div class="kilder"><h2>Relaterede Bygningsdele</h2>'.$bygningsdel.'</div>';

    $str.= '
            <div id="tema_header" class="icon_'.$tema->className().'">
                <table border="0" style="height:40px" >
                    <tr>
                        <td valign="bottom">
                            '.$tema->name().'
                            '.$tema->classChooser().'
                        </td>
                    </tr>
                </table>
            </div>
            <div id="resume">
                '.$tema->resume().'
            </div>

            '.$tema->siderAdministration($_REQUEST['tema']).'
            '.$kilder.'
            '.$bygningsdel.'
           ';

    if($tema->editing()) 
    {
        if($tema->isOwner()) $str.= $tema->ownerEdit();
        
        $str.= '<br><a class="redbutton" 
                        href="javascript:document.myform.submit()">GEMT</a>   
                 <br><br>
                 </form>';
    }
    else
    {
        if($tema->isEditor() || $tema->isOwner() ) $str.='<br><a class="redbutton" href="?tema='.$_REQUEST['tema'].'&edit=1">Rediger</a><br/><br/>';
    }
    return $str;
  }
  
  function checkEdit()
  {
    if($_REQUEST['removing']) 
    {
        $tema = new temaDoc(new dba()); 
        if($tema->isEditor()) $tema->remove($_REQUEST['removing']);
    }
    if($_GET['set_author']) 
    {
        $tema = new temaDoc(new dba()); 
        if($tema->isEditor()) $tema->frontPageAuthor($_GET['set_author']);
    }

    if($_REQUEST['tema']!='-1' && !$_REQUEST['edit']) return;

    $js = "<script>document.location.href=SERVER_NAME+'/'+SCRIPT_NAME+'?action=tema%s'</script>";
    if(!isAnyoneLogged()) die(printf($js,'')); 

    $tema = new temaDoc(new dba()); 
    if($_REQUEST['tema']=='-1') die(printf($js,'&tema='.$tema->create('Ny Tema').'&edit=1')); 
    if($_REQUEST['save']) die(printf($js,'&tema='.$tema->save($_REQUEST['tema'])));
  }
  function Content()
  {
    $this->checkEdit();
    return '
            <style>#left_content {margin:0;padding:0;padding-right:30px }</style>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" id="maincontent">
                        '.$this->currentTema().'
                    </td>
               </tr>
            </table>
            <br />
            ';
  }
}
?>
