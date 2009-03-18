<?
include_once('admin/util/tree.php');
include_once('admin/util/page.php');
include_once('admin/util/agent.php');
include_once('admin/util/brancheTree.php');

class Bygningsdel extends View 
{
  var $dba;
  var $tree;
  var $byg_id;
  var $element_id;

  var $page;
  var $props;
  var $categories;
  var $kilder;
  var $agents;
  var $products;
  var $varegrupper;
  var $branche;

  function Bygningsdel()
  {
    $this->pub = new publication();
    $this->action ='bygningsdel';
    $this->headLine = 'Bygningsdel';
    $this->dba = new dba();
    $this->branche = ($_SESSION['branche'])? $_SESSION['branche']:'general';
    $this->tree = new brancheTree( $this->dba,session_id(),$this->branche);
    $this->node_id = $_GET['node'];

    $this->loadPage();
  }
  function loadPage()
  {
    if($_GET['gohome']) unset($_SESSION['element']);
    if(!$_SESSION['element']) $_SESSION['element'] = 1;
    if($_GET['element']) $_SESSION['element'] = $_GET['element'];
    
    $this->page = new Page( $this->dba, $_SESSION['element'], $this->node_id, $this->branche);
    $this->props       = $this->page->load();
    $this->headLine = $this->props['element_name'];
  }
  function id()
  {
    if(!is_numeric($this->element_id)) return 0;
    return $this->element_id;
  }
  function full_title()
  {
    $title = '';
    $path = $this->page->path($_GET['node'],$this->branche);
    $path = array_reverse($path);
    
    foreach($path as $key=>$value)
    {
        if($title != '' ) $title.=' &#187; ';
        $title.= $value['name'];
    }
    
    if($title != '' ) $title.=' &#187; ';
    $title.= $this->Headline();
    return $title;
  }
  function BreadCrumb()
  {
    $path = $this->page->path($_GET['node'],$this->branche);
    $path = array_reverse($path);
    if(!$path) return;

    $str = '';
    foreach($path as $key=>$value)
    {
      if($str) $str.=' &gt; ';
      $str.= '<a href="index.php?action='.$this->action;
      $str.= '&element='.$value['element_id'];
      $str.= '&branche='.$this->branche;
      $str.= '&node='. $value['id'];
      $str.= '">'. $value['name'].'</a>';
    }
    return $str;
  }
  function isFrontPage()
  {
    return ($_SESSION['element'] == 1);
  }
  function frontPage()
  {
    return '
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="center">
            
                        <img src="graphics/tegning_'. $_SESSION['branche'] .'.jpg" border="0" 
                          usemap="#tegning_'.$_SESSION['branche'].'">

                    </td>
               </tr>
              </table>
            <br><br>
            <map name="tegning_beton">
              <area shape="rect" coords="4,86,114,122" href="index.php?element=247&node=143&toggle=140"><!--letklinke-blokke-->
              <area shape="rect" coords="4,196,114,222" href="index.php?element=558&branche=beton&node=217&level=2"><!--betonelement dæk-->
              <area shape="rect" coords="4,234,114,270" href="index.php?element=207&node=142&toggle=142"><!--letklinkebetonelement-->
              <area shape="rect" coords="4,282,114,307" href="index.php?toggle=2&element=2&node=2"><!--vådrum -->
              <area shape="rect" coords="4,314,114,337" href="index.php?element=318&branche=beton&node=204&level=2"><!--pladsstop dæk-->

              <area shape="rect" coords="384,176,494,199" href="index.php?element=499&branche=beton&node=21&level=2&toggle=215"><!--filigrandæk-->
              <area shape="rect" coords="384,208,494,232" href="index.php?element=210&node=9&toggle=28"><!--betonelement-->
              <area shape="rect" coords="384,311,494,335" href="index.php?element=131&node=202&toggle=189"><!--radonsikring-->
            </map>

            <map name="tegning_general">
              <!--missing references...-->
              <area shape="rect" coords="4,26,114,50" href="index.php?element=119&node=158&toggle=106"><!--gennembrydning-->
              <area shape="rect" coords="4,82,114,106" href="index.php?element=67&node=150&toggle=144"><!--forankring-->
              <area shape="rect" coords="4,181,114,205" href="index.php?toggle=246&element=96&node=246"><!-- regnskærm-->
              

              <area shape="rect" coords="4,225,114,265" href=""><!--betondæk og gulvkonstruktion -->
              <area shape="rect" coords="4,299,114,324" href="index.php?toggle=2&element=2&node=2"><!--vådrum -->
              <area shape="rect" coords="387,25,496,49" href=""><!--tagbelægning-->
              <area shape="rect" coords="387,79,496,103" href="index.php?toggle=106&element=6&node=106"><!--tagkonstruktion-->
              <area shape="rect" coords="387,173,496,197" href=""><!--nedhængt loft-->
              <area shape="rect" coords="387,213,496,250" href="index.php?element=233&node=233&toggle=231"><!--u-værdig, let ydervæg-->
              <area shape="rect" coords="387,266,496,304" href="index.php?element=127&node=212&toggle=203"><!--u-værdig, tung ydervæg-->
              <area shape="rect" coords="387,324,496,350" href="index.php?element=131&node=202&toggle=189"><!--radonsikring-->

            </map>

            <map name="tegning_mur">
              <area shape="rect" coords="4,34,113,70" href="index.php?element=119&node=111&toggle=6"><!--skorsten på konsol-->
              <area shape="rect" coords="4,97,113,120" href="index.php?toggle=25&element=56&node=25"><!--binding af tagsten-->
              <area shape="rect" coords="4,184,113,222" href="index.php?element=106&node=130&toggle=45"><!--overflade behandling-->
              <area shape="rect" coords="4,267,113,290" href="index.php?toggle=2&element=2&node=2"><!--vådrum-->
              <area shape="rect" coords="4,307,113,332" href="index.php?action=bygningsdel&element=564&branche=mur&node=206&level=1&toggle=206"><!--fundament-->

              <area shape="rect" coords="385,87,496,112" href="index.php?toggle=9&element=55&node=9"><!--tegltag -->
              <area shape="rect" coords="385,163,496,185" href="index.php?toggle=44&element=110&node=44"><!--Miljøklasse -->
              <area shape="rect" coords="385,218,496,242" href="index.php?element=125&node=50&toggle=44"><!--Fuger-->
              <area shape="rect" coords="385,263,496,298" href="index.php?element=3&node=37&toggle=37"><!--Kombinationsvægge-->
              <area shape="rect" coords="385,312,496,337" href="index.php?element=348&branche=mur&node=203&level=2"><!--Klinkegulv-->
              <area shape="rect" coords="385,379,496,402" href="index.php?element=130&node=55&toggle=44"><!--Fugtisolering-->
              <!--<area shape="rect" coords="385,87,496,112" href="index.php?element=556&branche=mur&node=188&toggle=188">--><!--Terrændæk-->
            </map>

            <map name="tegning_trae">
              <area shape="rect" coords="1,57,112,82" href="index.php?element=119&node=118&toggle=2"> <!--inddækning-->
              <area shape="rect" coords="2,92,112,116" href="index.php?element=58&node=17&toggle=16"> <!--lægning-->
              <area shape="rect" coords="2,175,112,199" href="index.php?element=147&node=52&toggle=49"> <!--gipsplader-->
              <area shape="rect" coords="2,204,112,241" href="index.php?element=347&branche=trae&node=129&level=2"> <!--svømmende gulv-->
              <area shape="rect" coords="2,291,112,315" href="index.php?element=28&branche=trae&node=52&level=1&toggle=52"> <!--vådrum-->

              <area shape="rect" coords="386,23,495,47" href="index.php?element=48&node=3&toggle=3"> <!--tagdækning -->
              <area shape="rect" coords="386,59,495,83" href="index.php?toggle=36&element=64&node=36"><!-- tagkunstruktion -->
              <area shape="rect" coords="386,92,496,116" href="index.php?toggle=26&element=79&node=26"><!-- undertag -->
              <area shape="rect" coords="386,165,495,190" href="index.php?toggle=41&element=69&node=41"><!-- Nedhængt loft-->
              <area shape="rect" coords="386,204,496,227" href="index.php?toggle=43&element=96&node=43"><!-- facadebehandling -->
              <area shape="rect" coords="386,291,495,314" href="index.php?element=331&node=130&level=2"><!-- gulv på strør -->
              <area shape="rect" coords="386,321,496,344" href="index.php?element=246&node=93&toggle=45"><!-- fugtisolering -->

            </map>

            <map name="tegning_maling">
              <area shape="rect" coords="5,10,113,33" href="index.php?element=202&branche=malin&node=111&level=2&toggle=111"><!--MBK-->
              <area shape="rect" coords="5,88,113,124" href="index.php?element=167&node=128&toggle=127"><!--tree masivt høvlet-->
              <area shape="rect" coords="5,163,113,199" href="#"><!--treegulv lak-lud-->

              <area shape="rect" coords="5,210,113,233" href="index.php?element=85&level=2&toggle=53"><!--betonloft-->

              <area shape="rect" coords="5,256,113,292" href="index.php?element=170&node=131&toggle=127"><!--træmasiv savskåret-->
              <area shape="rect" coords="5,303,113,326" href="index.php?toggle=2&element=2&node=2"><!--vådrum -->
              <area shape="rect" coords="5,376,113,412" href="index.php?element=200&node=109&level=2"><!--overflade betongulv-->

              <area shape="rect" coords="385,88,495,123" href="index.php?element=147&node=52&toggle=49"><!--gipsplade med fosænket kant-->
              <area shape="rect" coords="385,172,495,196" href="index.php?element=147&node=52&toggle=49"><!--gips akustik plade-->
              <area shape="rect" coords="385,212,495,248" href="index.php?element=106&node=118&toggle=113"><!--tegl sækkeskuret-->
              <area shape="rect" coords="385,263,495,286" href="index.php?element=108&node=119&toggle=113"><!--tegl pudset-->
              <area shape="rect" coords="385,406,495,432" href="index.php?element=86&node=54&toggle=54"><!--letklinket loft-->
            </map>
            <map name="tegning_kloak">
              <!--missin label...-->
            </map>

            <!--
              missing:
              Maling - trægulv lak-lud
              General - betondæk og gulvkonstruktion 
              General -tagbelægning
              General - nedhængt loft
            -->
          ';
  }
  function Content()
  {
    global $GODE_RAAD_FROM_BYGVIDEN;
    global $RELATED_PRODUCTS;

    if($this->isFrontPage()) return $this->frontPage();

    $GODE_RAAD_FROM_BYGVIDEN = $this->goderaad();
    $RELATED_PRODUCTS = $this->getVaregrupper();

    $str = '<div id="posts">
                <table width="100%" cellpadding="3" cellspacing="1" border="0">
                    <tr >
                        <td style="background-color:#fff"></td>
                        <td style="background-color:#fff"><img src="index.php_files/transp.gif" width="320" height="1"></td>
                    </tr>';
                
    $str.= $this->getKategories();

    $str.= '    </table>
            </div>';
    return $str;
  }
  function getKategories()
  {
    $this->categories = $this->page->loadCategories();

    $kilder  = $this->page->loadKilder();

    $str='';

    for( $i = 0; $i < count( $this->categories ); $i++ )
    {
      $cat = $this->categories[$i]['category_id'];
      $cat_kilder   = $kilder[  $cat ];
      $kilde_n    = count( $cat_kilder );

      if(!$kilde_n && !$agents_n ) continue;

      $str.= '<tr >
                <td colspan="2" align="right" class="kategori" style="background-color:#fff">
                    '.$this->categories[$i]['category_name'].'
                </td>
            </tr>';

      for($j=0;$j<$kilde_n;$j++)
      {
        $pub = $cat_kilder[$j];
        $str.= $this->pub->renderPublication($cat_kilder[$j]);
      }
    }
    return $str;
  }
  function getVaregrupper()
  {
    $varegrupper = $this->page->loadVaregrupper();
    if(!$varegrupper) return '';

    $str = '';
    foreach( $varegrupper as $key=>$value )
    {
      $name = $value['name'];
      $url = 'index.php?action=products&section=varegrupper'; 
      $url.= '&id='. $value['id'];
      $str.= '<div style="margin-bottom:3px"><a href="'.$url.'"><img src="graphics/link_arrows.gif" class="imgarrow" 
                    height="5" width="7" border="0" align="left" />'. $name .'</a></div>';
    }

    return $str; 
  }
  function goderaad()
  {
    if(!$this->props['goderaad']) return;
    return '
            <div id="goderaad" style="margin:0;padding:0">
            ' .str_replace('<p>&nbsp;</p>','',$this->props['goderaad']) .'
            </div>
            ';
  }
  function enforceSingleOpenLevel()
  {
    if(!$_GET['level']) return;
    if(!$_GET['toggle']) return;

    if(!is_array($_SESSION['openlevels'])) $_SESSION['openlevels'] = array();
    $levelID = $_SESSION['openlevels'][$_GET['level']];
    if(is_numeric($levelID) && $levelID != $_GET['toggle']) $this->tree->toggle($levelID);
    $_SESSION['openlevels'][$_GET['level']] = $_GET['toggle'];
  }
  function LeftMenu()
  {
    if($_GET['toggle']) $this->tree->toggle($_GET['toggle']);
    if($_GET['element']==1 || !$_GET['element']) $this->tree->clearState();
    $this->enforceSingleOpenLevel();
    $nodes =  $this->tree->getNodeArray();
    $n = count( $nodes );

    $l = '<div id="navitree">';
    for($i=0;$i< $n;$i++)
    {
      $node = $nodes[$i];
      if($node['id'] == 1) continue;

      $link = 'index.php?action=bygningsdel&element='.$node['element_id'];
      $link.= '&branche='.$_SESSION['branche'];
      $link.= '&node='.$node['id'];
      $link.= ($node['open'])? '':'&level='.$node['level'];

      if($node['node']) 
      {
        $link.='&toggle='.$node['id'];
        $iconLink = '<a href="index.php?action=bygningsdel&toggle='.$node['id'];
        $iconLink.='&element='.$node['element_id'];
        $iconLink.= ($node['open'])? '':'&level='.$node['level'];
        $iconLink.='&branche='.$_SESSION['branche'].'">';
        $iconLinkEnd = '</a>';
        $stateIcon = ($node['open'])? 'minus':'plus';
        $stateLabel = ($node['open'])? 'Gem':'Vis';
      }
      else
      {
        $iconLink = '';
        $iconLinkEnd = '';
        $stateIcon = 'transp';
        $stateLabel = '';
      }
      
      $l.= '<div style="padding-left:'. (($node['level'] -1) * 10).'px;">';
      $l.= '<div><a href="'. $link.'"><img src="graphics/'.$stateIcon.'.gif" class="imgnode" 
            alt="'.$stateLabel.'" height="9" width="9" border="0" align="left" /></a></div>';
      $l.= '<div class="node"><a href="'.$link.'">'. $node['name'].'</a></div>';
      $l.= '</div>';
    }
    $l.= '</div>';
    return $l; 
  }
}
