<?php
    require_once("../util/autonomy.php");
    require_once("../../page/aci.php");
    require_once("../util/agent.php");

    if( !is_numeric( $id ) ) die("Parameter id expected");
    $agent = new Agent( $dba, $id );

    if( $_REQUEST['save'] )
    { 
      if( !$results ) $results = $_POST['results'];
      if( !$threshold ) $threshold = $_POST['threshold'];

      $agent->setStartText( $_POST['start_text'] );
      $agent->setAntalResults( $_POST['antal_results'] );
      $message = "Dinne ændringer er blevet gemt";
    }

    if( $_POST['nullstill'] ) $agent->nullstill();
    if( $_POST['train'] ) $agent->train( $_POST['docs'] );
    if( $_POST['forget_training'] ) $agent->forgetTraining();
    if( $_POST['update_number_results'] ) $agent->setAntalResults( $_POST['antal_results'] );

    $props = $agent->loadProperties();
?>
<link href="../../styles/main.css" rel="stylesheet" type="text/css" />
<input type="hidden" name="id" value="<?=$id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"><?=$props['name']?>  <span class="alert_message"><?=$message?></span>
</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="tdpadtext" >Start træning tekst</td>
           </tr>
           <tr>
                <?if( $props['autonomy'] == 'n' ):?>
                  <td class="tdpadtext" >
                      <textarea name="start_text" rows="6" cols="60" class="input" wrap="virtual" style="width:400px;height:175px"><?=$props['start_text']?></textarea>
                  </td>
                <?else:?>
                  <td class="color2">
                    <span class="plainText" style="padding:10px"><?=$props['start_text']?></span>
                  </td>
                <?endif?>
           </tr>
           <tr> 
            <td class="tdpadtext" >
              Antal resultater:
              <select name="antal_results">
                <?for($i=1;$i<20;$i++):?>
                  <option value="<?=$i?>" <?=($i==$props['results'])?'selected':''?>><?=$i?></option>
                <?endfor?>
              </select>
              <?if( $props['autonomy'] != 'n' ):?>
                <input type="submit" name="update_number_results" value="Opdatere" class="knap">
              <?endif?>
            </td> 
           </tr>
           <tr> <td>&nbsp;</td> </tr>
           <?if( $props['autonomy'] == 'y' ):?>
           <tr>
            <td bgcolor="#ffffff">
             <script language="javascript" src="../../script.js"></script>
             <?//fetch the documents?>
             <?
                $results = $agent->getResults();
             ?>
             <?if($results):?>
                <?
                  include_once('../../page/page.php');
                  include_once('../../page/search.php');
                  include_once('../../page/publication.php');
                  include_once('../../config.php');
                  $s = new Search(); 
                ?>
                <div style="margin:10px;margin-bottom:20px">
                  <?=$s->renderResults($results)?>
                </div>
             <?else:?>
                Ingen resultater for agenten
             <?endif?>
             </td>
            </tr>
           <?endif?>
           <tr>
                <td class="tdpadtext" >&nbsp;</td>
           </tr>
        </table>
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td >
		    <table cellpadding="0" cellspacing="0" border="0" >
          <tr>
            <td class="tdpadtext">
              <?if( $referer ):?>
                <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
              <?endif?>
            </td>
            <td  align="right">

           <?if( $props['autonomy'] == 'y' ):?>
              <input type="submit" value="Nullstill agenten" name="nullstill" class="knap" style="width:150px">
              <input type="submit" value="Glemt træning" name="forget_training" class="knap" style="width:150px">
              <input type="submit" value="Træn" name="train" class="knap" style="width:150px">
            <?else:?>
              <input type="submit" value="Cancel" name="cancel" class="knap">
              <input type="submit" value="Save" name="save" class="knap"> 
            <?endif?>
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>
</form>
