<?
require_once("admin/util/kilde.php");
class publication
{
  var $betaling = false;
  function publication()
  {
    $this->kilde = new kilde( new dba(),0);
  }
  function checkForlag($publication)
  {
    if( $publication['kilde_leverandor_forlag'] ) $url = $publication['kilde_leverandor_forlag'];
    if( $publication['kilde_kategory_forlag'] )   $url = $publication['kilde_kategory_forlag'];
    if( $publication['kilde_forlag'] )            $url = $publication['kilde_forlag'];

    if(!$url) return ''; 
    
    $ogsaa = ($publication['digital_udgave']=='y')? 'ogs&aring;':'';

    $txt = '<tr class="cat_'.$publication['kat_id'].'"> <td>Publikationen findes '.$ogssa .' i trykt udgave</td>
                 <td valign="top"><a href="'.$url.'" target="_blank" style="text-decoration:none;color:#333">Bes&oslash;g  ';
    $txt.= (stristr($url,'www.danskbyggeri.dk') || stristr($url,'servicebutik.danskbyggeri.dk'))? 'Servicebutikken':'forlag';
    $txt.= '</a></td></tr>';
    return $txt;
  }
  function resolveBetaling( $pub )
  {
    //check if only the resume is visible
    if( $pub['brugsbetingelser'] == 'resume_alle' ) return -1;
    if( $pub['lev_overrule'] == 'y' )
    {
      $betaling = ( $pub['lev_betaling'] == 'y' )? 1:0;
    }

    #check if betaling is define for the category
    if( $pub['cat_overrule'] == 'y' )
    {
      $betaling = ( $pub['cat_betaling'] == 'y' )? 1:0;
    }

    #check if betaling is define for the publication
    if( $pub['overrule']  == 'y' )
    {
      $betaling = ( $pub['betaling'] == 'y' )? 1:0;
    }
    return $betaling;
  }
  function displayLock($publication)
  {
    if($this->resolveBetaling($publication) != 1) return; 
    return '&nbsp;<img align="absbottom" src="graphics/lock.gif" 
            title="Visning kr&aelig;ver logging" border="0">';
  }
  function checkAccess($pub)
  {

    if($this->resolveBetaling($pub) == 1 && $pub['samlet_publication']=='y')
    {
        return '
                <tr class="cat_'.$pub['kat_id'].'">
                    <td valign="top">Adgang</td>
                    <td>K&oslash;b af et kapitel giver adgang til alle kapitler i publikationen</td>
                </tr>
               ';
    }

    if($pub['brugsbetingelser'] =='fuldtekst_medlemmer')
    {
        return '
                <tr class="cat_'.$pub['kat_id'].'">
                    <td valign="top">Adgang</td>
                    <td>Fuldtekst for brugere med log-in</td>
                </tr>
               ';
    }
    if($pub['brugsbetingelser'] =='resume_alle')
    {
        return '
                <tr class="cat_'.$pub['kat_id'].'">
                    <td valign="top">Adgang</td>
                    <td>Resume</td>
                </tr>
               ';
    }
  }
  function checkPris($pub)
  {
    if($this->resolveBetaling($pub) != 1) return; 

    $enkelt_pris = 0;
    $abonament_pris = 0;
    $abonament_periode = 0;
    $bruger_rabat = '';

    if( $pub['lev_overrule'] == 'y' )
    {
      $enkelt_pris = $pub['lev_enkelt_pris'];
      $abonament_pris = $pub['lev_abonament_pris'];
      $abonament_periode = $pub['lev_abonament_periode'];

      if($pub['lev_abonament_betaling'] =='n') $abonament_pris = '';
      if($pub['lev_enkelt_betaling'] =='n') $enkelt_pris = '';
      if($pub['lev_bruger_rabat']=='y') $bruger_rabat = $pub['kid'];
    }

    #check if betaling is define for the category
    if( $pub['cat_overrule'] == 'y' )
    {
      $enkelt_pris = $pub['cat_enkelt_pris'];
      $abonament_pris = $pub['cat_abonament_pris'];
      $abonament_periode = $pub['cat_abonament_periode'];

      if($pub['cat_abonament_betaling'] =='n') $abonament_pris = '';
      if($pub['cat_enkelt_betaling'] =='n') $enkelt_pris = '';
      if($pub['cat_bruger_rabat']=='y') $bruger_rabat = $pub['kat_id'];
    }

    #check if betaling is define for the publication
    if( $pub['overrule']  == 'y' )
    {
      $enkelt_pris = $pub['enkelt_pris'];
      $abonament_pris = $pub['abonament_pris'];
      $abonament_periode = $pub['abonament_periode'];

      if($pub['abonament_betaling'] =='n') $abonament_pris = '';
      if($pub['enkelt_betaling'] =='n') $enkelt_pris = '';
      if($pub['bruger_rabat']=='y') $bruger_rabat = $pub['kildeid'];
    }

    #check if betaling is define for the category
    /*
    if( $pub['samlet_publication'] == 'y' )
    {
      echo 'samlet pub...<br>';
      if( $pub['cat_overrule'] == 'y' )
      $enkelt_pris = $pub['cat_enkelt_pris'];
      $abonament_pris = $pub['cat_abonament_pris'];
      $abonament_periode = $pub['cat_abonament_periode'];

      if($pub['cat_abonament_betaling'] =='n') $abonament_pris = '';
      if($pub['cat_enkelt_pris'] =='n') $enkelt_pris = '';
    }
    */

    if($enkelt_pris == '0') $enkelt_pris = '';
    if($abonament_periode == '0') $abonament_periode = '';
    if(is_numeric($bruger_rabat))
    {
        $t = '<tr class="cat_'.  $pub['kat_id'] .'"><td valign="top">Pris</td><td>';

        $bruger_model = $this->kilde->getBrugerDataForID($bruger_rabat);
        $t.='<table id="bruger_rabat_table" cellpadding="5" cellspacing="0" border="0">';
        foreach($this->kilde->bruger_data_model as $k=>$v)
        {
            $t.="<tr><td>$v</td><td>$bruger_model[$k] kr.</td></tr>";
        }
        $t.='</table>';
        $t.='</td></tr>';
        return $t; 
    }

    if(!$enkelt_pris && !$abonament_pris) return;

    if($enkelt_pris) $enkelt_pris = $enkelt_pris .' kr. enkelt visning ';
    if($abonament_pris) 
    {
        $abonament_pris = $abonament_pris .' kr. '. $abonament_periode .' m&aring;neder abonnement';
        if($enkelt_pris) $abonament_pris = '<br>'.$abonament_pris;
    }

    return '
            <tr class="cat_'.$pub['kat_id'].'">
                <td valign="top">Pris</td>
                <td>'. $enkelt_pris .' '. $abonament_pris .'</td>
            </tr>
           ';
  }
  function displayParameters($publication)
  {
    $betaling  = $this->resolveBetaling( $publication);
    $str = $publication['kildeid'].',';
    $str.= ($betaling)? $betaling:0;
    if(stristr($publication['reference'],'fetchBips.php')) 
    {
      $str.= ",''"; 
    }
    else
    {
      $str.= ",'". urlencode($publication['reference'])."'";
    }
    return $str;
  }
  function summary($publication)
  {
    $str = '<p>';
    if($publication['kilde_description']) return stripslashes($str.$publication['kilde_description']) .'</p>';
    if($publication['summary']) return $str.$publication['summary'].'</p>';
  }
  function vidensLeverandor($publication)
  {
    $url = '';
    if($publication['producer_id']) $url ='index.php?action=products&section=producenter&id='.$publication['producer_id'];
    else $url = 'index.php?action=bibliotek&lev='.$publication['kilde_leverandor_id'];
    
    if($this->isBackEnd()) $url= '../../'.$url;
    return $url;
  }
  function isBackEnd()
  {
    return (strstr($_SERVER['PHP_SELF'],'admin/agents'));
  } 
  function kategoryName($publication)
  {
    if(!$publication['kilde_kategory_name']) return;
    return $publication['kilde_kategory_name'].' - ';
  }
  function backendCheckBox($publication)
  {
    if(!$this->isBackEnd()) return;
    return '<input type="checkbox" name="docs[]" value="'.$publication['autonomy_id'].'">';
  }
  function leverandor($publication)

  {
    if(!$publication['kilde_leverandor_id']) return;
    return '<a href="'.$this->vidensLeverandor($publication).'"
               style="text-decoration:none;color:#333;"
                >'. $publication['kilde_leverandor_name'].'</a></span>';
  }
  function displayWeight($publication)
  {
    if(!$this->isBackEnd()) return;
    return '('.$publication['weight'].'%) ';
  }
  function pubName($publication)
  {
    return $this->kategoryName($publication) . $publication['title'];
  }

  function renderSamletPublication($publication)
  {
    $txt = stripslashes($publication['samlet_description']); 
    if($txt) $txt = '<tr>
                        <td colspan="2" class="title">
                          <p>'. $txt.'</p>
                        </td>
                    </tr>';
    $txt = '<tr>
                <td colspan="2" class="title">
                  <span style="color:#074C8A;font-weight:900;font-size:12px;">'. $publication['kat_name'] .'</span>
                </td>
            </tr>'.$txt;
    
    $publication['overrule']='n'; 

    $t = $txt.'
            '.$lev.'
            '. $this->checkPris($publication).'
            '. $this->checkAccess($publication).'
            '. $this->checkForlag($publication).'
           ';
    return $t;
  }
  function kapitelPublication($publication)
  {
    return '
            <tr>
                <td colspan="2" class="title">
                    <div class="open"><a href="javascript:displaydoc('.$this->displayParameters($publication).')" 
                        style="color:#cc3300"><img src="graphics/open.png" border="0"></a></div>
                    <a href="javascript:displaydoc('.$this->displayParameters($publication).')">'. $this->pubName($publication). '</a>

                    '. $this->summary($publication).'
                </td>
            </tr>
           ';
  }
  function renderPublication($publication)
  {

    $lev = $this->leverandor($publication);
    if($lev)
    {
        $lev = '<tr class="cat_'. $publication['kat_id'].'">
                    <td>Fra </td>
                    <td>'.$lev.'</td>
                </tr>';
    }

    $pub_url = 'javascript:displaydoc('.$this->displayParameters($publication).')';

    if($publication['digital_udgave']=='n' && 
       $publication['kilde_forlag'] ) $pub_url = 'javascript:w=window.open(\''.$publication['kilde_forlag'].'\');w.focus()';


    $pub_link = '<div class="open"><a href="'. $pub_url.'" style="color:#cc3300"><img src="graphics/open.png" border="0"></a></div>';

    if($publication['digital_udgave']=='n' && !$publication['kilde_forlag'] ) 
    {   
        $pub_url ='';
        $pub_link='';
    }

    return '
            <tr class="cat_'.$publication['kat_id'].'">
                <td colspan="2" class="title">
                    '. $pub_link .'
                    '.$this->backendCheckBox($publication).'
                    '.$this->displayWeight($publication).'

                    <a href="'.$pub_url.'">'. $this->pubName($publication). '</a>

                    '. $this->summary($publication).'
                </td>
            </tr>
            '.$lev.'
            '. $this->checkPris($publication).'
            '. $this->checkAccess($publication).'
            '. $this->checkForlag($publication).'
            <tr class="cat_'.$publication['kat_id'].'">
                <td colspan="2" style="background-color:#fff"><br /></td>
            </tr>
           ';
  }
  function renderCategory($catID,$catName,$state='open')
  {
      $img = ($state=='open')?'minus':'plus';
      $style = ($state=='open')?'':'display:none"';

      return '<a name="'.$catID.'"><br><br>
              <div class="category" style="margin:0;padding:0">
                <div class="catheadline"> 
                  <a href="javascript:toggleCategory(\''. $catID.'\')" style="text-decoration:none;color:#333"><img 
                    src="graphics/'.$img.'.gif" class="imgcategory" alt="Skjul" 
                    id="img_'.$catID.'" height="9" width="9" border="0" align="left" />
                  '.$catName.'</a>
                </div>
                <div id="container_'.$catID.'" style="margin-top:4px;margin-bottom:4px;'.$style.'">';
  }
  function renderLink($href,$label,$target='_self')
  {
    return '<p><a href="'.$href.'" target="'.$target.'"><img 
                  src="graphics/link_arrows.gif" class="imgarrow" height="5" width="7" 
                  border="0" align="left" />'. $label.'</a></p>';
  }
}
?>
