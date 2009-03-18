<?
class ValgFag extends View 
{
  var $current_search;

  var $brancher = array( 
                    'general'=>'Alle fag'
                    ,'trae'=>'Træfagene'
                    ,'mur'=>'Murerfaget'
                    ,'beton'=>'Struktører'
                    ,'maling'=>'Malerfaget'
                    ,'kloak'=>'Kloakfaget'
                   );

  var $brancherDesc = Array( 
                        'general'=>'Du søger byggeteknisk viden indenfor murer, træ, struktører og malerfaget'
                        ,'trae'=>'Du søger byggeteknisk viden primært indenfor træfagne'
                        ,'mur'=>'Du søger byggeteknisk viden primært indenfor murerfaget'
                        ,'beton'=>'Du søger byggeteknisk viden primært indenfor struktørerfaget'
                        ,'maling'=>'Du søger byggeteknisk viden primært indenfor malerfaget'
                        ,'kloak'=>'Du s&oslash;ger byggeteknisk viden prim&aelig;rt indenfor kloakfaget'
                      );

  function ValgFag()
  {
    $this->action = 'valgfag';
    $this->current_search = ($_POST['search'])? $_POST['search']:$_GET['search'];
    if(!$this->current_search) $this->current_search = '1';
    $this->headLine ='Velkommen til bygviden.dk';
    $this->leftMenu ='<div id="navitree">
                       <div class="node">&nbsp; </div>
                      </div>';

  }

  function isCurrentBranche($branche)
  {
    if($_SESSION['branche'] == $branche) return 'checked';
    return '';
  }
  function getBrancheOptions()
  {
    $opt = '';
    foreach($this->brancher as $key=>$value)
    {
      $opt.='<tr> 
              <td>
                <input type="radio" name="fag" value="'. $key .'"
                '.$this->isCurrentBranche($key).'></td>
              <td NOWRAP><strong>'. $value .' </strong></td>
              <td style="padding-left:15px;">'.$this->brancherDesc[$key].'</td>
            </tr>
            <tr> 
              <td colspan="4"><img src="graphics/transp.gif" width="10" height="10"></td>
            </tr>';
    }
    return $opt;
  }
  function Content()
  {
    return '
              <form name="myform" action="'.$this->CurrentPage().'" method="get">
                  <p>
                  Bygviden.dk drives af <a href="http://www.danskbyggeri.dk" target="_blank">Dansk Byggeri</a> 
                  og strukturerer og formidler byggeteknisk 
                  viden og erfaring. <br>
                  V&aelig;lg fag og klik eller s&oslash;g dig efterf&oslash;lgende frem 
                  til konkret materiale. 
                  </p>
                  <br>

                  <p>V&aelig;lg fag</p>
                  <table cellpadding="4" cellspacing="0" border="0" class="formtable">
                  <tr>
                    <td>
                      <table width="350" border="0" cellpadding="0" cellspacing="0">
                      '. $this->getBrancheOptions() .'
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input type="checkbox" name="huskvalgt" checked> Husk mit valg 
                    </td>
                  </tr>
                  <tr>
                    <td align="center">
                      <input type="submit" value="V&aelig;lg" class="button" name="valgfag"> 
                    </td>
                  </tr>
                </table>
              </form>
           ';
  }
}
?>
