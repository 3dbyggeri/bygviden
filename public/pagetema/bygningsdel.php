<?
include_once('admin/util/tree.php');
include_once('admin/util/page.php');
include_once('admin/util/agent.php');
include_once('admin/util/brancheTreeNew.php');
include_once('admin/util/tema.php');

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
  var $current_byg;

  function Bygningsdel()
  {
    $this->pub = new publication();
    $this->action ='bygningsdel';
    $this->dba = new dba();
    
    if($_SESSION['branche']) $this->branche = $_SESSION['branche'];
    if($_GET['branche']) $this->branche = $_GET['branche'];
    if(!$this->branche) $this->branche = 'general';

    if(is_numeric($_REQUEST['byg']))
    {
       $this->current_byg = $this->dba->singleArray("SELECT 
                                                        dev_branche_tree.*,
                                                        dev_brancher.name as branche_name,
                                                        dev_buildingelements.name as element_name,
                                                        dev_buildingelements.goderaad as goderaad
                                                     FROM 
                                                        dev_branche_tree,dev_brancher,dev_buildingelements
                                                     WHERE 
                                                        dev_branche_tree.branche_id=dev_brancher.id
                                                     AND
                                                        dev_branche_tree.element_id = dev_buildingelements.id
                                                     AND 
                                                        dev_branche_tree.id=".$_REQUEST['byg']); 
       if($this->current_byg) $this->branche = $this->current_byg['branche_name'];
    }
    $_SESSION['branche'] = $this->branche;
    
    $this->tree = new brancheTree2( $this->dba, session_id(), $this->branche );
    $this->node_id = $_GET['node'];

    $this->loadPage();
  }
  function rightmenu()
  {

    return '
            <div id="right_menu" style="padding:0">
                '.$this->goderaad().'
                '.$this->getTemaer().'
                '.$this->getVaregrupper().'
                '.$this->adds().'
            </div>';
  }
  function adds()
  {
      // return '<style>#rightgoogleadd {display:none; }</style>
      //         <div style="margin-top:10px;margin-left:15px;">
      //          <script type="text/javascript"><!--
      //                google_ad_client = "pub-9171964576337297";
      //                /* LandingsPageRightHightFormat */
      //                google_ad_slot = "6156729202";
      //                google_ad_width = 160;
      //                google_ad_height = 600;
      //                //-->
      //                </script>
      //                <script type="text/javascript"
      //                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
      //                </script></div>';
  }
  function goderaad()
  {
    if(!$this->current_byg) return;
    if(!$this->current_byg['goderaad']) return;
    return '<div id="goderaad">
                <h2>Gode r&aring;d</h2>
            ' .str_replace('<p>&nbsp;</p>','',stripslashes($this->current_byg['goderaad'])) .'
            </div>';
  }
  function loadPage()
  {
    if($_GET['gohome']) unset($_SESSION['element']);
    if(!$_SESSION['element']) $_SESSION['element'] = 1;
    if($_GET['element']) $_SESSION['element'] = $_GET['element'];
    
    $this->page = new Page( $this->dba, $_SESSION['element'], $this->node_id, $this->branche);
    $this->props       = $this->page->load();
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
    return (!is_numeric($_REQUEST['byg'])); 
  }
  function housing()
  {
    include_once( "admin/util/house.php" );
    $h = new house($this->dba);
    $entries = $h->listing($this->branche);
    $markers = '';

    for($i=0;$i<count($entries);$i++)
    {
        $img ='graphics/transp.gif';
        $left = 0;
        $top = 0;
        $p =$entries[$i]['pointer']; 
        if($p > 0 )
        {
            $img = 'tema/graphics/arrows/'.$h->arrowImg($p); 
            $left = $entries[$i]['x'] + $h->arrows[$p]['x'];
            $top = $entries[$i]['y'] + $h->arrows[$p]['y'];
        }
        $markers.= '<div class="pointer"
                        id="pointer_'.$entries[$i]['id'].'" 
                        style="left:'.$left.'px;top:'.$top.'px"
                        ><img src="'.$img.'" /></div>';

        $markers.= '<div id="marker_'.$entries[$i]['id'] .'"
                        onclick="javascript:top.location.href=\'/'.stripslashes($entries[$i]['link']).'\'"
                        style="left:'.$entries[$i]['x'].'px;top:'.$entries[$i]['y'].'px"
                        class="marker">'.stripslashes($entries[$i]['label']).'</div>';
    }

    $img = 'tema/graphics/houses/'.$this->branche.'.jpg';
    return '<center>
                <div id="hus">
                    <img src="'.$img.'">
                    '.$markers.'
                </div>
            </center>';
  }
  function Content()
  {
    $meat = '';
    $headline = '';
    
    if($this->current_byg) 
    {
        $meat = $this->getKategories(); 
        $headline = $this->current_byg['element_name'];
    }
    if($this->isFrontPage()) 
    {
        $meat = '<iframe src="house.php?branche='. $this->branche .'" frameborder="0" scrolling="no" style="width:600px;height:500px;border:0px solid #000"></iframe>';
        //$meat = $this->housing();
    }
    return '
            <style>#left_content {margin:0;padding:0 }</style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" >
                        <h1>'. $headline .'</h1>
                        '.$meat.'
                    </td>
               </tr>
            </table>
            ';

  }
  function getKategories()
  {
      $sql = "SELECT 
                cat.name as category,

                k.id                 AS kildeid,
                k.name               AS title,
                k.logo_url           AS kilde_logo,
                k.description        AS kilde_description,
                k.observation        AS kilde_observation,
                k.forlag_url         AS kilde_forlag,
                k.betaling           AS betaling,
                k.enkelt_betaling    AS enkelt_betaling,
                k.abonament_betaling AS abonament_betaling,
                k.overrule_betaling  AS overrule,
                k.digital_udgave     AS digital_udgave,
                k.brugsbetingelser   AS brugsbetingelser,
                k.betegnelse         AS betegnelse,
                k.bruger_rabat       AS bruger_rabat,      

                kb.id         AS kat_id,
                kb.id         AS kilde_kategory_id,
                kb.name       AS kilde_kategory_name,
                kb.logo_url   AS kilde_kategory_logo,
                kb.forlag_url AS kilde_kategory_forlag,
                kb.betegnelse AS kilde_kategory_betegnelse,
                kb.indholdsfortegnelse AS samlet_publication,
                kb.betaling           AS cat_betaling,
                kb.enkelt_betaling    AS cat_enkelt_betaling,
                kb.abonament_betaling AS cat_abonament_betaling,
                kb.overrule_betaling  AS cat_overrule,
                kb.bruger_rabat       AS cat_bruger_rabat,      

                kc.id         AS kid,
                kc.id         AS kilde_leverandor_id,
                kc.kilde_url  AS kilde_leverandor_url,
                kc.name       AS kilde_leverandor_name,
                kc.logo_url   AS kilde_leverandor_logo,
                kc.forlag_url AS kilde_leverandor_forlag,
                kc.betegnelse AS kilde_leverandor_betegnelse,
                kc.betaling           AS lev_betaling,
                kc.enkelt_betaling    AS lev_enkelt_betaling,
                kc.abonament_betaling AS lev_abonament_betaling,
                kc.overrule_betaling  AS lev_overrule,
                kc.bruger_rabat       AS lev_bruger_rabat      
              FROM 
                ". $this->dba->getPrefix() ."bygcatpubbranch AS mx,
                ".$this->dba->getPrefix()."categori AS cat,

                ". $this->dba->getPrefix() ."kildestyring as k,
                ". $this->dba->getPrefix() ."kildestyring as kb,
                ". $this->dba->getPrefix() ."kildestyring as kc
              WHERE 
                element_id=". $this->current_byg['element_id']."
              AND mx.category_id = cat.id
              AND mx.publication_id = k.id AND k.parent = kb.id AND kb.parent = kc.id 
              AND ( k.timepublish < NOW() OR k.timepublish IS NULL ) AND ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) 
              AND branche='".$this->branche."'
              ORDER BY cat.position, k.name";
      
      $publikationer = array();
      $result = $this->dba->exec($sql);
      $n = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $rec = $this->dba->fetchArray( $result );
          $publikationer[ $rec['category'] ][ count( $publikationer[$rec['category'] ] ) ] = $rec;
      }

    
    $str = '<table id="poster" border="0" cellspacing="1" cellpadding="0">';
    foreach($publikationer as $key=>$value)
    {
        $str.= '<tr><td colspan="2" class="category" align="right">'.$key.'</td></tr>';
        for($i=0;$i<count($value);$i++)
        {
            $str.= $this->pub->renderPublication($value[$i]);
        }
    }

    $str.='<tr><td colspan="2" style="color:#fff">';
    for($i=0;$i<400;$i++) $str.=' . ';
    $str.='</td></tr>';

    $str.='</table>';
    return $str;
  }

  function getTemaer()
  {
      if(!$this->current_byg) return;
      $tema = new temaDoc($this->dba); 
      $temaer = $tema->temaForBygningsdel($this->current_byg['id']);

      $str = '';
      $priv = '';
    
      $n = count($temaer);
     
      for( $i = 0; $i < $n; $i++ )
      {
          $url = '?tema='.$temaer[$i]['id']; 

          if($temaer[$i]['private'] =='y')
          {
            $priv.= '<div style="margin-top:5px;margin-bottom:5px"><a href="'.$url.'" class="link">'. $temaer[$i]['name'] .'</a></div>';
            continue;
          }
          $str.= '<div style="margin-top:5px;margin-bottom:5px"><a href="'.$url.'" class="link">'. $temaer[$i]['name'] .'</a></div>';
      }
      $s='';
      if($str) $s = '<div id="varegrupper"> <h2>Temaer</h2> '.$str.' </div>';
      if($priv) $s.='<div id="varegrupper"> <h2>Egne Temaer</h2> '.$priv.' </div>';
      return $s;
  }
  function getVaregrupper()
  {
      if(!$this->current_byg) return;
      $sql = "SELECT 
                v.id    as id,
                v.name  as name,
                v2e.category as category
              FROM
                ". $this->dba->getPrefix(). "varegrupper as v,
                ". $this->dba->getPrefix(). "varegruppe2element as v2e
              WHERE
                v.id = v2e.varegruppe
              AND
                v2e.element = ".  $this->current_byg['element_id'];
        
        $varegrupper = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );

        $str = '';
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $name = $rec['name'];
          $url = '?action=products&section=varegrupper'; 
          $url.= '&id='. $rec['id'];
          $str.= '<div style="margin-top:5px;margin-bottom:5px"><a href="'.$url.'" class="link">'. $name .'</a></div>';
        }

        if(!$str) return ;
        return '<div id="varegrupper">
                    <h2>Varegrupper</h2>
                    '.$str.'
                </div>';
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
    global $brancher;
    $brancher['general'] = 'Generelt';
    $brancher['tolerancer'] = 'Tolerancer';

    $this->tree->openElement($_REQUEST['byg']);
    $nodes =  $this->tree->getNodeArray();
    $n = count( $nodes );

    $l = '<div id="navitree">';
    foreach($brancher as $key=>$value)
    {
        $l.= '<table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td><img src="graphics/transp.gif" height="15" width="0"></td>
                    <td valign="top"><img width="8" height="8" src="tema/graphics/bluearrow.gif" style="margin-right:3px;margin-top:3px"></td>
                    <td class="topbranche"><a href="?action=bygningsdel&branche='.$key.'">'. $value.'</a></td>
                </tr>
            </table>';
        if($key != $this->branche ) continue;
        if(!$_REQUEST['branche'] && !is_numeric($_REQUEST['byg'])) continue;
        for($i=1;$i< $n;$i++)
        {
          $node = $nodes[$i];
          $arrow = 'transp';
          $selected = '';
          $level = (($node['level'] - 1) * 10);
          
          //if( [!$node['node']) { $arrow = 'bluearrownochild'; } //in case we want to differentiate nodes sans - child
          if($node['id'] == $_REQUEST['byg'] ) 
          {
            $selected= 'class="selected"';
            $arrow = 'bluearrownochild';
          }
          
          $l.= '<table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><img src="graphics/transp.gif" height="15" width="'.$level.'"></td>
                        <td valign="top"><img width="8" height="8" src="tema/graphics/'.$arrow.'.gif" style="margin-right:3px;margin-top:3px"></td>
                        <td class="node" ><a href="?byg='.$node['id'].'" '.$selected.'>'. $node['name'].'</a></td>
                    </tr>
                </table>';
        }
    }
    $l.= '</div>';

    return '<div id="building_left">
                <table cellpadding="0" cellspacing="0" border="0" >
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                        <td valign="top">'.$l.'
                    </tr>
                </table>
            </div>';
  }
}
