<?php
if( $gemt || $_GET['gemt'] )
{
  //backup current cfg file
  //flytte temp cfg over til produktionen
  //$fp = fopen("fetch_backup/HTTPFetch.cfg","w");
  $destination = '/home/autonomy/autonomy/httpfetch/HTTPFetch.cfg';
  //$destination = 'HTTPFetch.cfg';
  if(!copy('HTTPFetch.cfg', $destination ) ) 
  {
    $message = "Kunne ikke gemme configurations filen";
  }
  else
  {
    $message = 'Konfigurations filen er blevet gemt';
  }
  $message = 'Konfigurations filen er blevet gemt';
}
if( $generere || $_GET['generere'] )
{
  include_once( "../util/tree.php" );
  require_once("../util/kildeTree.php");
  include_once( "../util/user.php" );

  session_start();
  $dba = new dba();
  $user = new user( $dba );
  $tree = new kildeTree( $dba, session_id(), 'kildestyring' );

  $ip    = '127.0.0.1';
  $port  = 7001;
  $batch = 'import';
  $default_db = 'diverse';

  //read the master file 
  $filename = "templates/fetch_master_cfg.txt";
  $fd = fopen ($filename, "r");
  if( $fd )
  {
      $fetch_cfg = fread ($fd, filesize ($filename));
      fclose ($fd);
  }

  //read the spider file
  $filename = "templates/spider_master_cfg.txt";
  $fd = fopen ($filename, "r");
  if( $fd )
  {
      $spider_cfg = fread ($fd, filesize ($filename));
      fclose ($fd);
  }
  
  $brancher = $tree->getBrancher();
  $spiders = $tree->getSpiders();
  $spider_string = '';
  $spider_count = 0;
  for( $i = 0; $i < count( $spiders ); $i++ )
  {
    /*
    //if($spiders[$i]['parent'] != '1507') continue;
    //if($spiders[$i]['id'] < '4889') continue;
    //if($spiders[$i]['id'] > '4912') continue;
    if( stristr($spiders[$i]['kilde_url'],'http://uranus.ramboll.dk') )
    {
      $spiders[$i]['kilde_url'] = 'http://www.bygviden.dk/fetchBips.php?u='. $spiders[$i]['kilde_url'];
    }
    else
    {
      continue;
    }
    */

    $spider_count++;
    $fixedFieldCounter = 0;
    $str = $spider_cfg;
    $spider_names.= $spider_count .'='. 'spider_'. $spiders[$i]['id'] ."\n";

    $str.= "FixedFieldName". $fixedFieldCounter ."=pub_id\n";
    $str.= "FixedFieldValue". $fixedFieldCounter ."=". $spiders[$i]['id'] ."\n";
    $fixedFieldCounter++;

    $str = str_replace( '{url}', $spiders[$i]['kilde_url'], $str );
    $str = str_replace( '{spider_name}', 'spider_'. $spiders[$i]['id'], $str );
    $str = str_replace( '{dir_name}', 'spider_'. $spiders[$i]['id'], $str );
    $str = str_replace( '{log_name}', 'spider_'. $spiders[$i]['id'] .'.log', $str );

    if( is_numeric( $spiders[$i]['crawling_depth'] ) ) $str.= 'DEPTH='. $spiders[$i]['crawling_depth'] ."\n";
    if( is_numeric( $spiders[$i]['crawling_cuantitie'] ) ) $str.= 'MAXPAGES='. $spiders[$i]['crawling_cuantitie'] ."\n";
    if( trim( $spiders[$i]['db'] ) && $spiders[$i]['db'] != $default_db ) $str.= 'DATABASE='. $spiders[$i]['db'] ."\n"; 
    if( trim( $spiders[$i]['forbidden_words'] ) )
    {
      $str.= 'CANTHAVECHECK=129'."\n";
      $str.= 'CANTHAVECSVS='. $spiders[$i]['forbidden_words'] ."\n";
    }
    if( trim( $spiders[$i]['required_words'] ) )
    {
      $str.= 'MUSTHAVECHECK=129'."\n";
      $str.= 'MUSTHAVECSVS='. $spiders[$i]['required_words'] ."\n";
    }

    if( stristr($spiders[$i]['kilde_url'],"www.bygviden.dk/TIL_BYG_CONTENT/" ) )
    {
        //add authentication
        $spider_str.= "LOGINUSERVALUE=autonomy \n";
        $spider_str.= "LOGINPASSVALUE=32ReCvQa \n";

        $str.= 'LOGINURL= '. $spiders[$i]['kilde_url'] ."\n";
        $str.= "LOGINMETHOD=AUTHENTICATE \n";
        $str.= "LOGINUSERVALUE=autonomy\n";
        $str.= "LOGINPASSVALUE=32ReCvQa\n";

        $str.= 'FixedFieldName'. $fixedFieldCounter ."=log_name \n";
        $str.= 'FixedFieldValue'. $fixedFieldCounter ."=autonomy\n";
        $fixedFieldCounter++;

        $str.= 'FixedFieldName'. $fixedFieldCounter ."=log_password \n";
        $str.= 'FixedFieldValue'. $fixedFieldCounter ."=32ReCvQa\n";
        $fixedFieldCounter++;
    }
    else if( $spiders[$i]['log_in'] == 'y' )
    {
        //add authentication
        $str.= 'LOGINURL= '. $spiders[$i]['kilde_url'] ."\n";
        $str.= "LOGINMETHOD=AUTHENTICATE \n";
        $str.= 'LOGINUSERVALUE= '. $spiders[$i]['log_name'] ." \n";
        $str.= 'LOGINPASSVALUE= '. $spiders[$i]['log_password'] ." \n";

        $str.= "FixedFieldName". $fixedFieldCounter ."=log_name \n";
        $str.= "FixedFieldValue". $fixedFieldCounter ."=". $spiders[$i]['log_name'] ."\n";
        $fixedFieldCounter++;

        $str.= "FixedFieldName". $fixedFieldCounter ."=log_password \n";
        $str.= "FixedFieldValue". $fixedFieldCounter ."=". $spiders[$i]['log_password'] ."\n";
        $fixedFieldCounter++;
    }
    
    for( $j = 0; $j < count( $brancher ); $j++ )
    {
      if( in_array( $brancher[$j]['id'], $spiders[$i]['brancher'] ) )
      {
        $str.= 'FixedFieldName'. $fixedFieldCounter .'='. $brancher[$j]['name'] ."\n";
        $str.= 'FixedFieldValue'. $fixedFieldCounter .'=';
        $str.= ( in_array( $brancher[$j]['id'], $spiders[$i]['brancher'] ) )? 'y':'n';
        $str.= "\n";
        $fixedFieldCounter++;
      }
    }
    $spider_string.= $str;
  }

  $fetch_cfg = str_replace('{host}', $ip , $fetch_cfg );
  $fetch_cfg = str_replace('{port}', $port, $fetch_cfg );
  $fetch_cfg = str_replace('{default_db}', $default_db, $fetch_cfg );
  $fetch_cfg = str_replace('{batch}', $batch, $fetch_cfg );

  $fetch_cfg = str_replace('{number_of_spiders}', $spider_count, $fetch_cfg );
  $fetch_cfg.= $spider_names ."\n";
  $fetch_cfg.= $spider_string;
  $config = $fetch_cfg ;

  //write file to disk
  $fp = fopen("HTTPFetch.cfg","w");
  if( $fp )
  {
      fwrite( $fp, $config );
  }

}
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">
      Kildstyring
      <input 
        onclick="window.open('log.php?id=fetch')" style="width:120px;margin-left:20px;" 
        type="button" class="knap" size="30px" 
        value="HTTPFetch Log"> 
      <input 
        onclick="window.open('log.php?id=spider')" style="width:120px;"
        type="button" class="knap" size="30px" 
        value="Spider Log"> 
    </td>
	</tr>
  <tr> 
    <td align="right"><img src="../graphics/transp.gif" width="10" height="25">
    </td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-top: 5px;">&nbsp;</td>
           </tr>
           <?if( $gemt || $_GET['gemt'] ):?>
             <tr>
                  <td class="alert_message" style="padding-left: 10px; padding-top: 5px;"><?=$message?></td>
             </tr>
           <?endif?>
           <?if( $generere || $_GET['generere'] ):?>
             <tr>
                  <td class="tdpadtext">
                    <textarea name="config" cols="80" rows="12"><?=$config?></textarea>
                  </td>
             </tr>
            <tr >
              <td class="tdpadtext">
                <input type="button" class="stor_knap" style="width:250px" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?gemt=1&pane=<?=$pane?>'"  value="Gemt konfigurationen" >
              </td>
            </tr>
           <?else:?>
             <tr>
                  <td class="tdpadtext">Genere konfigurations fil for fetchen</td>
             </tr>
              <tr >
                <td class="tdpadtext">
                  <input type="button" class="stor_knap" style="width:250px" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?generere=1&pane=<?=$pane?>'"  value="Generere" >
                </td>
              </tr>
           <?endif?>
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-bottom: 20px;">&nbsp;</td>
           </tr>
        </table>
    </td>
  </tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top:10px; padding-left:10px;">
        &nbsp;
				</td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
   	<td><img src="../graphics/transp.gif" height="15" width="15"></td>
  </tr>
</table>
