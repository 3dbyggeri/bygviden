<?php
class About extends View
{
  var $menu;
  var $items;
  function About()
  {
    $this->help_menu = array( 
                         'tema'=>'Temaer',
                         'building'=>'Bygningsdele',
                         'prod'=>'Byggevarer',
                         'bib'=>'Bibliotek',
                         'search'=>'S&oslash;gning',
                         'pay'=>'Betaling',
                         'minside'=>'Profil',
                         'log'=>'Log in'
                        );

    $this->om_menu = array( 
                         'om'=>'Om bygviden.dk',
                         'annon'=>'Annoncer',
                         'sam'=>'Samarbejdspartnere',
                         'his'=>'Historien bag'
                        );
    $this->items = array('copy'=>'Copyright',
                       'ansvar'=>'Ansvarsfraskrivelse',
                       'kontakt'=>'Kontakt',
                       'om'=>'Om bygviden.dk',
                       'help'=>'Hj&aelig;lp');
  }
  function rightMenu()
  {
    return '';
  }
  
  function LeftMenu()
  {
    $str = '<div id="left_menu">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                        <td valign="top">';

    $menu = array();
    if($this->getCurrentItem() == 'help') $menu = $this->help_menu;
    if($this->getCurrentItem() == 'om') $menu = $this->om_menu;

    if($menu) 
    {
        $str.='<ul id="tema_menu">';
        foreach($menu as $k=>$v)
        {
          $sel = ($_GET['id']==$k)? 'class="selected"':''; 
          $str.='<li><a '.$sel.' href="?action=about&item='.$this->getCurrentItem().'&id='.$k.'">'.$v.'</a></li>';
        }
        $str.='</ul>';
    }

    $str .= '           </td>
                    </tr>
                </table>
            </div>';
    return $str;
  }
  
  function getCurrentItem()
  {
    if($_REQUEST['item']) return $_REQUEST['item'];
    return 'om';
  }
  function Content()
  {
    $paragraph = new Paragraph(new dba() );

    $item = $this->getCurrentItem();
    if(!$item) $item = 'om';
    if($item =='copy') $meat = $paragraph->body('Copyright');
    if($item == 'ansvar') $meat = $paragraph->body('Ansvarsfraskrivelse');
    if ($item =='kontakt') $meat = $paragraph->body('Kontakt');

    if($item == 'om') 
    {
        $sel = $_GET['id'];
        if(!$sel) $sel = 'om';
        $meat = $paragraph->body('about - '. $sel);
    }

    if($item == 'help') 
    {
        $sel = $_GET['id'];
        if(!$sel) $sel = 'tema';
        $meat = $paragraph->body('help - '. $sel);
    }

    return '
            <style>#left_content {margin:0;padding:0 }
            </style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" >

                        <h1>'.$this->items[$item].'</h1>
                        <div style="margin:30px">'.$meat.'</div>
                    </td>
               </tr>
            </table>
            ';
  }
}
?>
