<?
require_once("admin/util/dba.php");
require_once("admin/util/bruger.php");
require_once("admin/util/tree.php");
require_once("admin/util/kildeTree.php");

class Bibliotek extends View 
{
  var $dba;
  var $readed = array();
  var $publikationer = array();
  var $videnslev_category = array();

  function Bibliotek()
  {
    $this->dba = new dba();
    $this->headLine = 'Vidensleverand&oslash;r';
    $this->action = 'bibliotek';
    $this->breadCrumb = '<a href="index.php?action='.$this->action.'">Bibliotek</a>
                         &gt; Videns leverand&oslash;r';

    if($_GET['lev']) $this->loadLeverandoer($_GET['lev']);
  }
  function leverandoerIntro($props)
  {
    $fields = array('adresse'=>'Adresse',
                    'telefon'=>'Telefon',
                    'fax'=>'Fax',
                    'mail'=>'Mail',
                    'kilde_url'=>'Web side');
    $str = '';
    if($props['description']) $str.='<p>'.$props['description'].'</p>';

    $str.= '<table cellpadding="5" cellspacing="0" border="0" class="formtable">';

    foreach($fields as $key=>$value)
    {
      if(!$props[$key]) continue;
      $str.= '<tr>
               <td valign="top" nowrap>'.$value.':</td>
               <td valign="top">
                 <strong>';

      if($key =='mail' || $key == 'kilde_url') $str.= $this->checkURL($props[$key]);
      else $str.= $props[$key];
      
      $str.='     </strong>
                </td>
              </tr>';
    }
    $str.= '</table>';
    return $str;
  }
  function loadLeverandoer($lev)
  {
    $sql ="SELECT * FROM ".$this->dba->getPrefix() ."kildestyring WHERE id=$lev";
    $props = $this->dba->singleArray($sql);
    $this->headLine = $props['name'];
    $this->breadCrumb = '<a href="index.php?action='.$this->action.'">Bibliotek</a>
                         &gt; <a href="index.php?action='.$this->action.'">Videns leverand&oslash;r</a>
                         &gt; '. $props['name'];
    
    $str = $this->leverandoerIntro($props);
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
    
    $str .= '<div id="posts">
                <table width="100%" cellpadding="3" cellspacing="1" border="0">
                    <tr >
                        <td style="background-color:#fff"></td>
                        <td style="background-color:#fff"><img src="index.php_files/transp.gif" width="320" height="1"></td>
                    </tr>';
    foreach($cats as $key=>$value)
    {
      $sorted = $this->sortPublications($value['publications']);
      if($value['publications'][0]['samlet_publication']=='y')
      {
          $str.= '<tr >
                    <td colspan="2" align="right" class="kategori" style="background-color:#fff">
                        '.$value['name'].'
                    </td>
                </tr>';
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
          $str.= '<tr >
                    <td colspan="2" align="right" class="kategori" style="background-color:#fff">
                        '.$value['name'].'
                    </td>
                </tr>';
          foreach($sorted as $k=>$v)
          {
            $str.= $pub->renderPublication($v);
          }
       }
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
  function Content()
  {
    if($_GET['lev']) return $this->content;

    $lev = $this->vidensLeverandoerList();
    foreach($lev as $key=>$value)
    {
      $str .= '<p>
                <a href="index.php?action='.$this->action.'&lev='.$key.'">'. $value .'</a>
               </p>';
    }
    return $str;
  }
}
?>
