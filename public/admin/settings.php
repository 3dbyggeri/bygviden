<?php
    require_once("../util/date_widget.php");
    require_once("../util/document.php");
    if( !$referer ) $referer = $_GET["referer"];
    if( !$referer ) $referer = $_POST["referer"];

    $document  = new document( $dba, $id );

    if( $editproperties || $_POST["editproperties"] )
    {
    	if( !$title )              $title = $_POST["title"];
        if( !$description )        $description = $_POST["description"];
        if( !$meta )               $meta = $_POST["meta"];
        if( !$nav )                $nav = $_POST["nav"];
        if( !$publishSchedule )    $publishSchedule = $_POST["publishSchedule"];
        if( !$day_publish )        $day_publish = $_POST["day_publish"];
        if( !$month_publish )      $month_publish = $_POST["month_publish"];
        if( !$year_publish )       $year_publish = $_POST["year_publish"];
        if( !$unpublishSchedule )  $unpublishSchedule = $_POST["unpublishSchedule"];
        if( !$day_unpublish )      $day_unpublish = $_POST["day_unpublish"];
        if( !$month_unpublish )    $month_unpublish = $_POST["month_unpublish"];
        if( !$year_unpublish )     $year_unpublish = $_POST["year_unpublish"];
        if( !$publish )            $publish = $_POST["publish"];

        $document->setTitle( $title );
        $document->setDescription( $description );
        $document->setMeta( $meta );
        $document->setNav( $nav );
        //the order of this statements is important
        if( $publishSchedule   )
        {
            $document->setPublishDate( $day_publish, $month_publish, $year_publish );
        }
        else
        {
            $document->setPublishDate();
        }
        if( $unpublishSchedule ) 
        {
            $document->setUnPublishDate( $day_unpublish, $month_unpublish, $year_unpublish );
        }
        else
        {
            $document->setUnPublishDate();
        }
        $document->setPublish( $publish );
        $message = "Your changes has been saved";
    }
    $document->loadProperties();
    $publishDate = new date_widget("publish");

    if( $document->publishDate["y"] ) 
    {
        $publishDate->setDate( $document->publishDate["d"], $document->publishDate["m"], $document->publishDate["y"] );
    }

    $unpublishDate = new date_widget("unpublish");

    if( $document->unpublishDate["y"] ) 
    {
        $unpublishDate->setDate( $document->unpublishDate["d"], $document->unpublishDate["m"], $document->unpublishDate["y"] );
    }
?>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
    <input type="hidden" name="id" value="<?=$document->id?>">
    <input type="hidden" name="pane" value="settings">
    <input type="hidden" name="refere" value="<?=$referer?>">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr> 
        <td><img src="../graphics/transp.gif" height="20"></td>
      </tr> 
      <tr>
        <td class="header">
          Properties for "<?=$document->name?>"
              <?if( $referer ):?>
                &nbsp;
                <a href="<?=$referer?>" class="alert_message" style="font-weight:normal;font-size:11px;text-decoration:none">Back-&gt</a>
              <?endif?>
        </td>
      </tr>   
      <tr> 
           <td align="center" class="alert_message">
              <?=$message?>&nbsp;
           </td>
      </tr> 
      <tr> 
          <td>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                <tr>
                    <td class="tdpadtext">Document title</td>
				        </tr>	
                <tr>    
					          <td class="tdpadtext"><input type="text" size="53" name="title" class="input" value="<?=$document->title?>"></td>
			          </tr>
                <tr>
					          <td class="tdpadtext" valign="top">Description</td>
				        </tr>
                    <td class="tdpadtext"><textarea name="description" rows="4" cols="53" class="input"><?=$document->description?></textarea></td>
				        </tr>
                <tr>
                    <td class="tdpadtext"  valign="top">Meta keywords</td>
				        </tr>
                <tr>
					          <td class="tdpadtext"> <textarea name="meta" rows="4" cols="53" class="input"><?=$document->meta?></textarea></td>
               	</tr>
                <tr>
                    <td class="tdpadtext"><input type="checkbox" name="nav" class="plainText" <?=( $document->nav )? "checked":""?> >Display document on navigation </td>
				        </tr>
                <tr>
                    <td class="tdpadtext"><input type="checkbox" name="publish" class="plainText" <?=( $document->publish )?"checked":""?> >Publish the document</td>
               </tr>
			   <tr>
					<td>
			   		<table  cellpadding="0" cellspacing="0" border="0">
					<tr>
                    		<td class="tdpadtext" width="206"><input type="checkbox" name="publishSchedule" class="plainText" <?=( $document->publishDate["y"] )?"checked":""?>>Publish on date [ d.m.y ]</td>
							<td> <?=$publishDate->render()?></td>
					</tr>
					</td>
				    <tr>
                    		<td class="tdpadtext" width="206"><input type="checkbox" name="unpublishSchedule" class="plainText" <?=( $document->unpublishDate["y"] )?"checked":""?>>Unpublish on date [ d.m.y ] </td>
					<td><?=$unpublishDate->render()?></td>
					</table>
              <tr> 
                <td ><img src="../graphics/transp.gif" height="15"></td>
              </tr>
            </table>
        </td>
			<tr>
				<td>
				<br>
					<table width="349" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td  align="right"><input type="submit" value="Cancel" name="cancel" class="knap">
							<input type="submit" value="Save" name="editproperties" class="knap"> </td>
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
