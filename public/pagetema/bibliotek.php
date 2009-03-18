<?
require_once("admin/util/dba.php");
require_once("admin/util/bruger.php");
require_once("admin/util/tree.php");
require_once("admin/util/kildeTree.php");
require_once('admin/util/products.php');

class Bibliotek extends View 
{
  var $dba;
  var $readed = array();
  var $publikationer = array();
  var $videnslev_category = array();

  function Bibliotek()
  {
    $this->dba = new dba();
    $this->headLine = '';
    $this->action = 'bibliotek';
    $this->breadCrumb = '<a href="index.php?action='.$this->action.'">Bibliotek</a>
                         &gt; Videns leverand&oslash;r';

    if($_GET['lev']) $this->loadLeverandoer($_GET['lev']);
  }
  function leverandoerIntro($props)
  {

    $producenter = new products( new dba() );
    $prod = $producenter->getProducentIdForKilde($props['id']);

    if($prod)
    {
      $prod['kilde_url'] = $prod['home_page'];
      $props = $prod;
    }
    $fields = array('adresse'=>'Adresse',
                    'telefon'=>'Telefon',
                    'fax'=>'Fax',
                    'mail'=>'Mail',
                    'kilde_url'=>'Web side');
    $str = '<h1>'.$props['name'].'</h1>';
    if($props['description']) $str.='<p style="margin-left:20px;width:500px">'.$props['description'].'</p>';

    $str.= '<table cellpadding="0" cellspacing="1" border="0" id="poster" width="95%">';

    foreach($fields as $key=>$value)
    {
      if(!$props[$key]) continue;
      $str.= '<tr>
               <td valign="top" class="left" nowrap>'.$value.':</td>
               <td valign="top" class="right">';

      if($key =='mail' || $key == 'kilde_url') $str.= $this->checkURL($props[$key]);
      else $str.= $props[$key];
      
      $str.=' </td>
              </tr>';
    }
    $str.= '</table>';
    return $str;
  }
  function loadLeverandoer($lev)
  {
    $sql ="SELECT * FROM ".$this->dba->getPrefix() ."kildestyring WHERE id=$lev";
    $props = $this->dba->singleArray($sql);
    $this->breadCrumb = '<a href="index.php?action='.$this->action.'">Bibliotek</a>
                         &gt; <a href="index.php?action='.$this->action.'">Videns leverand&oslash;r</a>
                         &gt; '. $props['name'];
    
    $str = '<script src="tema/YellowFade.js"></script>';
    $str.= $this->leverandoerIntro($props);
    $str.= $this->loadPublications($lev);
    $this->content = $str;
  }
  function checkURL($url)
  {
    if(stristr($url,'@'))
    {
      $url = '<a href="mailto:'.$url.'">'. $url.'</a>';
    }
    else
    {
      $ref = $url;
      if(substr($url,0,3) == 'www') $ref = 'http://'.$url;
      $url = '<a href="'.$ref.'" target="_blank">'. $url.'</a>';
    }
    return $url;
  }
  function loadReadedPublications()
  {
    if(!is_numeric($_SESSION['bruger_id'])) return;
    $bruger = new bruger($this->dba);
    $bruger->setId($_SESSION['bruger_id']);
    $this->readed = $bruger->getPublikationer();
  }
  function loadPublications($lev)
  {
    $sql = "SELECT
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
              k.enkelt_pris        AS enkelt_pris,
              k.abonament_pris     AS abonament_pris,
              k.abonament_periode  AS abonament_periode,
              k.bruger_rabat       AS bruger_rabat,      

              k.digital_udgave     AS digital_udgave,
              k.brugsbetingelser   AS brugsbetingelser,
              k.betegnelse         AS betegnelse,
              k.position           AS position,

              kb.id         AS kat_id,
              kb.name       AS kat_name,
              kb.logo_url   AS kilde_kategory_logo,
              kb.forlag_url AS kilde_kategory_forlag,
              kb.betegnelse AS kilde_kategory_betegnelse,
              kb.indholdsfortegnelse AS samlet_publication,
              kb.description         AS samlet_description,
 

              kb.betaling           AS cat_betaling,
              kb.enkelt_betaling    AS cat_enkelt_betaling,
              kb.abonament_betaling AS cat_abonament_betaling,
              kb.overrule_betaling  AS cat_overrule,
              kb.enkelt_pris        AS cat_enkelt_pris,
              kb.abonament_pris     AS cat_abonament_pris,
              kb.abonament_periode  AS cat_abonament_periode,
              kb.bruger_rabat       AS cat_bruger_rabat,      

              kc.id         AS kid,
              kc.name       AS kilde_leverandor_name,
              kc.logo_url   AS kilde_leverandor_logo,
              kc.forlag_url AS kilde_leverandor_forlag,
              kc.betegnelse AS kilde_leverandor_betegnelse,

              kc.betaling           AS lev_betaling,
              kc.enkelt_betaling    AS lev_enkelt_betaling,
              kc.abonament_betaling AS lev_abonament_betaling,

              kc.overrule_betaling  AS lev_overrule,
              kc.enkelt_pris        AS lev_enkelt_pris,
              kc.abonament_pris     AS lev_abonament_pris,
              kc.abonament_periode  AS lev_abonament_periode,
              kc.bruger_rabat       AS lev_bruger_rabat      

            FROM
              ". $this->dba->getPrefix() ."kildestyring as k,
              ". $this->dba->getPrefix() ."kildestyring as kb,
              ". $this->dba->getPrefix() ."kildestyring as kc
            WHERE
              k.parent = kb.id
            AND
              kb.parent = kc.id
            AND
              k.element_type = 'publikation'
            AND
              kc.id = $lev 
            AND
                ( k.timepublish < NOW() OR k.timepublish IS NULL )
            AND
                ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) 
            ORDER BY kb.position, kb.name,k.name";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
      
    $cats = array();
    $pub = new publication();
    for( $i = 0; $i < $n; $i++ ) 
    {
      $rec = $this->dba->fetchArray( $result );

      if(!$cats[$rec['kat_id']]) 
      {
        $cats[$rec['kat_id']] = array('name'=>$rec['kat_name'],'publications'=>array());
      }
      $cats[$rec['kat_id']]['publications'][count($cats[$rec['kat_id']]['publications'])] = $rec;
    }
    $toggling = true; //($lev == '976'); 
    $toggling = ($lev == '976'); 
    $str ='';
    $str.='<table id="poster" width="95%" cellpadding="0" cellspacing="1" border="0">
                    <tr >
                        <td></td>
                        <td><img src="graphics/transp.gif" width="320" height="1"></td>
                    </tr>';
    foreach($cats as $key=>$value)
    {
          $sorted = $this->sortPublications($value['publications']);
          $img = '';
          $pub_count = '';
          $style = '';
          if($toggling && $value['publications'][0]['samlet_publication']!='y') 
          {
              $str.= '<tr >
                        <td colspan="2" align="left" class="category">
                            <a href="javascript:toggleCat(\''. $key.'\')">'. $pub_count . $img . $value['name'] .'
                            <img src="tema/graphics/arrowdown.gif" id="img_'.$key.'" align="absmiddle" border="0" /></a>
                            <span style="font-size:10px">['.count($sorted) .']</span>
                        </td>
                    </tr>
                    <tr id="pubs_'.$key.'" style="display:none"><td colspan="2">';
          }
          elseif($value['publications'][0]['samlet_publication']=='y') 
          {
              $str.= '<tr >
                    <td colspan="2" align="left" class="category">
                        <div class="single_category">'. $value['name'] .'</div>
                    </td>
                </tr>';
          }
          else
          {
              $str.= '<tr >
                    <td colspan="2" align="left" class="category">
                        '. $pub_count . $img . $value['name'] .'
                    </td>
                </tr>';
          }

          if($value['publications'][0]['samlet_publication']=='y')
          {
              $k = array_keys($sorted);
              $k = $sorted[$k[0]];

              $str.= $pub->renderSamletPublication($k);

              foreach($sorted as $k=>$v)
              {
                $str.= $pub->kapitelPublication($v);
              }

              $str.= '<tr > <td colspan="2" style="background-color:#fff"><br /></td></tr>';
          }
          else
          {
              foreach($sorted as $k=>$v)
              {
                if($toggling) $str.='<table id="poster" width="100%" cellpadding="3" cellspacing="1" border="0" style="margin:0">';
                $str.= $pub->renderPublication($v);
                if($toggling) $str.='</table>';
              }
           }
           $str.='</td></tr>';
    }
    $str.= '    </table>
            </div>';

    return $str;
  }
  function sortPublications($pubs)
  {
    $opubs = array(); 
    $sorted = array();
    $id_keyed = array();
    foreach($pubs as $k=>$v)
    {
      $opubs[$v['kildeid']] = $v['position'];
      $id_keyed[$v['kildeid']] = $v;
    }
    asort($opubs);
    reset($opubs);
    foreach($opubs as $k=>$v)
    {
      $sorted[$k] = $id_keyed[$k];
    }
    return $sorted;
  }
  function vidensLeverandoerList()
  {
    $leverandoer = array();

    $sql ="SELECT 
             videnlev.id AS id,
             videnlev.name AS name
           FROM 
             ".$this->dba->getPrefix() ."kildestyring AS publication,
             ".$this->dba->getPrefix() ."kildestyring AS kategory,
             ".$this->dba->getPrefix() ."kildestyring AS videnlev 
           WHERE
             publication.parent = kategory.id
           AND
            videnlev.element_type = 'leverandor'
           AND
            publication.indholdsfortegnelse = 'y'
           AND
             kategory.parent = videnlev.id
           ORDER BY
            name
             ";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
      
    for( $i = 0; $i < $n; $i++ ) 
    {
      $rec = $this->dba->fetchArray( $result );
      $leverandor[$rec['id']] = $rec['name'];
    }
    return $leverandor;
  }

  function getTemaer()
  {
      if(!$_GET['lev']) return;
      $tema = new temaDoc($this->dba); 
      $temaer = $tema->temaForLeverandor($_GET['lev']);

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
      $s ='';
      if($str) $s = '<div id="varegrupper"> <h2>Temaer</h2> '.$str.' </div>';
      if($priv ) $s .= '<div id="varegrupper"> <h2>Egne Temaer</h2> '.$priv.' </div>';
      return $s;
  }
  function rightmenu()
  {
    return '<div id="right_menu">
                '.$this->getTemaer().'
                '.$this->adds().'
            </div>';
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
  function Content()
  {
    $meat = '';
    if($_GET['lev'])
    {
         $meat = $this->content;
    }
    else
    {
        $lev = $this->vidensLeverandoerList();
        foreach($lev as $key=>$value)
        {
          $meat.= '<li>
                    <a href="?action='.$this->action.'&lev='.$key.'">'. $value .'</a>
                   </li>';
        }
        $meat = '<h1>Vidensleverand&oslash;r </h1>
                 <ul class="page_list">
                 '.$meat.'
                </ul>';
    }



    return '
            <style>#left_content {margin:0;padding:0;padding-right:30px }
                    .page_list { margin:20px; }
                    .page_list li { margin-bottom:10px; }
            </style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">

                        <div id="building_left">
                            <img src="graphics/transp.gif" width="10" height="300" />
                        </div>
                    </td>
                    <td valign="top" >
                        '.$meat.'
                    </td>
               </tr>
            </table>
            ';
  }
}
?>
