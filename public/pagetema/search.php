<?
class Search extends View 
{
  var $aci;
  var $dba;
  var $MBKAnvisningMatch;
  function Search()
  {
    $this->headLine = ''; 
    $this->current_search = ($_POST['search'])? $_POST['search']:$_GET['search'];
    if(!$this->current_search) $this->current_search = '1';
    $this->action = 'search';
    $this->current_action = 'index.php?action='.$this->action;

    $this->aci = new ACI();
    $this->aci->debug = false;
    $this->dba = new dba();
    $this->MBKAnvisningMatch = "/(.*\s?)(v)\s?(\d+)(\s?.*)/i";
  }

  function Content()
  {
    $meat = '';
    if($_REQUEST['query']) $meat= $this->Results();

    return '
            <style>#left_content {margin:0;padding:0 }
            </style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" >

                        <h1>S&oslash;gning</h1>
                        <div style="margin:30px">'.$meat.'</div>
                    </td>
               </tr>
            </table>
            ';

	return $content;
  }
  function rightMenu() { return ''; }

  function LeftMenu()
  {
    return '<div id="left_menu">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                    </tr>
                </table>
            </div>';

    return '
            <div id="navitree">
              <div class="node">
                <a href="'.$_SERVER['PHP_SELF'].'?action='.$this->action.'&search=1"> Enkelt s&oslash;gning</a>
              </div>
              <div class="node">
                <a href="'.$_SERVER['PHP_SELF'].'?action='.$this->action.'&search=2">Avanceret s&oslash;gning</a>
              </div>
            </div>
          ';
  }

  function SummaryOptions()
  {
    $options = array('concept'=>'Concept',
                     'context'=>'Context',
                     'quick'=>'Quick summary',
                     'paragraphContext'=>'Paragraph context',
                     'paragraphConcept'=>'Paragraph concept');
    $str ='';
    foreach($options as $k=>$v)
    {
      $str.='<input type="radio" name="summary" ';
      if($_REQUEST['summary'] ==$k) $str.=' checked ';
      $str.= 'value="'.$k.'">'.$v.'<br>';
    }
    return $str;
  }
  function Form($description,$form_elements)
  {
    return '
            <form name="myform" action="'.$_SERVER['PHP_SELF'].'?action='. $this->action.'" method="post"> 
              <input type="hidden" name="action" value="'.$this->action.'">
              <input type="hidden" name="search" value="'.$this->current_search.'">
              <p>
                '.$description.'
              </p>
              <table cellpadding="0" cellspacing="0" border="0" class="formtable">
                '.$form_elements.'
                <tr>
                  <td align="right">
                      <input name="submit" type="submit" class="button" value="S&oslash;g" />
                  </td>
                </tr>
              </table>
            </form>
            ';
  }

  function branchOptions()
  {
    global $brancher;
    $str = '<strong>S&oslash;g i valgte fag</strong><br>';
    foreach($brancher as $key=>$value)
    {
      $str.= '<br>
              <input type="checkbox" onclick="controlCheckbox(\'fag\',this)" name="fag[]" value="'. $key.'" '. $this->isChecked($key,'fag'). '>
              '. $value;
    }
    return $str;
  }
  function isChecked($name,$field='db')
  {
    if($name == 'alle' || $name == 'general')
    {
      if(!$_REQUEST[$field]) return 'checked';
    }
    for($i=0;$i < count($_REQUEST[$field]);$i++)
    {
      if($_REQUEST[$field][$i]== $name) return 'checked';
    }
  }
  function categoryOptions()
  {
    global $categories,$paint_categories;
    $str = '<strong>S&oslash;g i valgte kategorier</strong><br><br>';
    $str.= '<table>
             <tr>
              <td>  
                <input type="checkbox" name="db[]" value="alle" '.$this->isChecked('alle').'>
              </td>
              <td>Alle kategorier</td>
             </tr>';

    foreach($categories as $key=>$value)
    {
      if($key=='producenter') continue;
      $str.= '<tr>
                <td valign="top">
                  <input onclick="controlCheckbox(\'db\',this)" type="checkbox" name="db[]" value="'.$key.'" '.$this->isChecked($key).'>
                </td>
                <td valign="top">
                  '. $value.'
                </td>
              </tr>
              ';
    }
    $str.='</table>';
    
    $display = ($_SESSION['branche'] == 'maling')? '':'display:none';
    $str.='<table id="painting" style="'.$display.'">';
    foreach($paint_categories as $key=>$value)
    {
      $str.= '<tr>
                <td colspan="2">
                  <input onclick="controlCheckbox(\'db\',this)" type="checkbox" name="db[]" value="'.$key.'" '.$this->isChecked($key).'>
                  '. $value .'
                </td>
              </tr>';
    }
    return $str.'</table>';
  }

  function advanceOptions()
  {
    return '<tr>
             <td>
              <table cellpadding="3" cellspacing="0" border="0">
                <tr>  
                  <td valign="top" 
                    style="padding-right:10px;width:50%">
                     '. $this->branchOptions() .'</td>
                  <td valign="top" 
                    style="border-left:1px solid #fff;padding-left:10px;width:50%">
                     '. $this->categoryOptions() .'</td>
                </tr>
              </table>
             </td>
            </tr>';
  }

  function Advance()
  {
    return $this->Form('
                  Du kan s&oslash;ge via n&oslash;gleord eller du kan
                  formulere dit sp&oslash;rgsm&aring;l som en s&aelig;tning.
                ',
                '
                  <tr>
                    <td>
                      <input type="text" name="query" class="textfield" value="'.$_REQUEST['query'].'" />
                    </td>
                  </tr>
                '.
                $this->advanceOptions()
                );
  }
  function Simple()
  {
    return $this->Form('
                    Du kan s&oslash;ge via n&oslash;gleord eller du kan
                    formulere dit sp&oslash;rgsm&aring;l som en s&aelig;tning.
                  ',
                  '
                    <tr>
                      <td>
                        <input type="text" name="query" class="textfield" value="'.$_REQUEST['query'].'" />
                      </td>
                    </tr>
                  ');
  }

  function renderHit($hit)
  {
    return  '
              <!--'.$hit['reference'].'--><a href="'. $hit['reference'].'" target="_blank">'. $hit['title'].'</a>
              <p>
              '.$hit['summary'].'
              </p>
              <br />';
  }
  function getPublications($hits)
  {
    $ids = $this->aci->getPublicationIDString($hits); 
    if(!$ids) return array();

    $sql = "SELECT
              k.id                 AS kildeid,
              k.name               AS kildename,
              k.logo_url           AS kilde_logo,
              k.description        AS kilde_description,
              k.observation        AS kilde_observation,
              k.forlag_url         AS kilde_forlag,
              k.betaling           AS betaling,
              k.enkelt_betaling    AS enkelt_betaling,
              k.abonament_betaling AS abonament_betaling,
              k.digital_udgave     AS digital_udgave,
              k.brugsbetingelser   AS brugsbetingelser,
              k.overrule_betaling  AS overrule,
              k.betegnelse         AS betegnelse,
              k.enkelt_pris        AS enkelt_pris,
              k.abonament_pris     AS abonament_pris,
              k.abonament_periode  AS abonament_periode,
              k.bruger_rabat       AS bruger_rabat,      
              k.db                 AS db,

              kb.id         AS kat_id,
              kb.id         AS kilde_kategory_id,
              kb.name       AS kilde_kategory_name,
              kb.logo_url   AS kilde_kategory_logo,
              kb.forlag_url AS kilde_kategory_forlag,
              kb.betegnelse AS kilde_kategory_betegnelse,

              kb.betaling           AS cat_betaling,
              kb.enkelt_betaling    AS cat_enkelt_betaling,
              kb.abonament_betaling AS cat_abonament_betaling,
              kb.overrule_betaling  AS cat_overrule,
              kb.enkelt_pris        AS cat_enkelt_pris,
              kb.abonament_pris     AS cat_abonament_pris,
              kb.abonament_periode  AS cat_abonament_periode,
              kb.bruger_rabat       AS cat_bruger_rabat,      
              kb.indholdsfortegnelse AS samlet_publication,

              kc.id         AS kid,
              kc.id         AS kilde_leverandor_id,
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
              k.id IN ( ". $ids ." )
            AND
                ( k.timepublish < NOW() OR k.timepublish IS NULL )
            AND
                ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) ";
      

      //hash the hits by their publication id 
      $hitsPB = $this->hitsByPublicationId($hits);

      $publications = array();
      $result = $this->dba->exec( $sql );
      $n = $this->dba->getN( $result );

      //group the publications by the database (or category)
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );

        //add the autonomy fields to the publication
        $hit = $hitsPB[$rec['kildeid']];
        foreach($hit as $k=>$v) $rec[$k] = $v; 

        if($rec['db'] == 'supplerende_tek') $rec['db'] = 'diverse';
        $publications[ $rec['db'] ][count($publications[$rec['db']])] = $rec;
      }
      
      return $publications;
  }
  function hitsByPublicationId($hits)
  {
    $hitsbp = array();
    for($i=0;$i <count($hits);$i++) $hitsbp[$hits[$i]['pub_id']]= $hits[$i];
    return $hitsbp;
  }
  function isMBKSearch()
  {
    if(in_array($_REQUEST['query'],array('MBK','Mbk','mbk','Malerbehandling','malerbehandling'))) return true;
    if(preg_match($this->MBKAnvisningMatch,$_REQUEST['query'])) return true;
  }
  function renderResults($str)
  {
    global $categories,$paint_categories;

    $hits = $this->aci->getHits($str);
    $publications = $this->getPublications($hits);

    if(!$publications) return 'Ingen resultater';
    $pub = new publication();

    $result.= '<table cellpadding="3" cellspacing="1" border="0" id="poster" width="95%">';
    
    if($this->isMBKSearch() && array_key_exists('MBK',$publications) )
    {
      $m = array('MBK'=>$publications['MBK']);
      unset($publications['MBK']);

      if(in_array($_REQUEST['query'],array('MBK','Mbk','mbk'))) $m['MBK'][0]['reference']=''; 
      $publications = array_merge($m,$publications);
    }

    //loop trought the categories
    foreach($publications as $key=>$value)
    {
      $category_name = $paint_categories[$key]; 
      if(!$category_name) $category_name = $categories[$key];
      
      $result .= '<tr><td colspan="2" class="category" align="right">'.$category_name.'</td></tr>';

      //loop trough the publications
      for($i=0;$i < count($value);$i++)
      {
        $result.= $pub->renderPublication($value[$i]);
      }
    }
    $result.= '</table>';
    return  $result;
  }
  function MBKAnvisningCheck($str)
  {
    $replacement = '\1\2 \3 \4';
    if(preg_match($this->MBKAnvisningMatch,$str))
    {
      $str = preg_replace($this->MBKAnvisningMatch,$replacement,$str);
    }
    return $str;
  }
  function getParams()
  {
    $params = array();
    $params['text'] = $_REQUEST['query'];
    $params['summary'] = 'context';
    $params['Print'] = 'All';
    $params['MaxResults'] = 50;
    $params['MinScore'] = 60;

    $params['text'] = $this->MBKAnvisningCheck($params['text']);

    if($_REQUEST['db'])
    {
      $db = '';
      $all = false;
      for($i=0;$i<count($_REQUEST['db']);$i++)
      {
        if($db) $db.='+';
        $db.= trim($_REQUEST['db'][$i]);
        if($_REQUEST['db'][$i]=='alle') $all = true;
      }
      if(!$all) $params['DatabaseMatch'] = $db;
    }

    if($_REQUEST['fag'])
    {
      $fg = '';
      $all = false;
      for($i=0;$i<count($_REQUEST['fag']);$i++)
      {
        if($fg) $fg.='+';
        $fg.= 'y:*/'.trim($_REQUEST['fag'][$i]);
        if($_REQUEST['fag'][$i]=='general') $all = true;
      }
      if(!$all) $params['FieldText'] = $fg;
    }
    return $params;
  }
  function logQuery($params)
  {
    $sql = "INSERT INTO 
               ". $this->dba->getPrefix() ."search_stats
               ( requested,query,results_count)
            VALUES
                ( NOW(),'". addslashes($params['text']) ."',0)";
    $this->dba->exec($sql);
  }
  function Results()
  {
    if(!$_REQUEST['query']) return '';
    $params = $this->getParams();
    $contents = $this->aci->query($params);
    //$this->logQuery($params); 
    if(!$contents) return '';
    return $this->renderResults($contents);
  }
}
?>
