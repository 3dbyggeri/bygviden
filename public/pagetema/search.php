<?
class Search extends View 
{
  function Search()
  {
    $this->headLine = ''; 
    // $this->current_search = ($_POST['search'])? $_POST['search']:$_GET['search'];
    // if(!$this->current_search) $this->current_search = '1';
    $this->action = 'search';
    $this->current_action = 'index.php?action='.$this->action;
  }

  function Content()
  {
    $google_cse_result = '<div id="cse-search-results"></div>
    <script type="text/javascript">
      var googleSearchIframeName = "cse-search-results";
      var googleSearchFormName = "cse-search-box";
      var googleSearchFrameWidth = 600;
      var googleSearchDomain = "www.google.com";
      var googleSearchPath = "/cse";
    </script>
    <script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>';

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
                        <div style="margin:30px">'.$google_cse_result.'</div>
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
}
?>
