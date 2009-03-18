<?if(!$SERVER_SOFTWARE) $SERVER_SOFTWARE = $_SERVER["SERVER_SOFTWARE"]?>
<?if(!$HTTP_USER_AGENT) $HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"]?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Velkommen <?=($user->full_name)? $user->full_name: $user->name?></td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-top: 5px;"><strong>System detalier</strong></td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px;">CMS version: 3.0</td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px;">Server: <?=$SERVER_SOFTWARE?></td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">Klient: <?=$HTTP_USER_AGENT?></td>
           </tr>
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">
                  <br /><!--<a href="../../rss.php" target="_blank" title="Newfeed for this website"><img src="../graphics/xml.gif" alt="News feeds" border="0"></a> (View the XML RSS feed for the website.)-->
                </td>
           </tr>
        </table>

    </td>
  </tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top:10px; padding-left:10px;">
					<br /><!--<input type="button" value="Åbn bygviden i en ny vindue" onclick="window.open('../../index.php')" style="width:300px;" class="medium_knap">--> 
				</td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
   	<td><img src="../graphics/transp.gif" height="15" width="15"></td>
  </tr>
</table>
