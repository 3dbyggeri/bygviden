<html>
    <head>
        <title><?=$title?></title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
        <form name="my_form" method="post" action="<?=$PHP_SELF?>">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="referer" value="<?=$referer?>">

        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td><img src="../graphics/transp.gif"></td>
              <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
              <td class="faneblad_selected">System bruger</td>
              <td><img src="../graphics/horisontal_button/right_selected.gif"></td>
          </tr>
        </table>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr> 
                      <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
                    </tr> 
                    <tr>
                      <td class="header"><?=$title?></td>
                    </tr> 
                    <tr>
                      <td align="center" class="alert_message"><?=$message?>&nbsp;</td>
                    </tr> 
                    <tr> 
                      <td bgcolor="#FFFFFF" class="plainText">

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                          <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="tdpadtext">Navn</td>
                                    </tr>
                                    <tr>
                                      <td class="tdpadtext"><input type="text" name="name" class="input"  value="<?=$editUser->name?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Fuld navn</td>
                                    </tr>
                                        <td class="tdpadtext"><input type="text" name="full_name" class="input" value="<?=$editUser->full_name?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Mail</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="text" name="mail" class="input" value="<?=$editUser->mail?>"></td>
                                    </tr>
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="20"></td> </tr>
                                    <tr>
                                        <td class="tdpadtext">Password</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="password" name="password" class="input"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Bekraft password</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="password" name="confirm_password" class="input"></td>
                                    </tr>
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                  </table>
                             </td>
                           </tr>
                          </table>
                          <br>

                          <table width="310" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td class="tdpadtext">
                                <?if( $referer ):?>
                                  <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
                                <?endif?>
                                &nbsp;
                              </td>
                              <td align="right"><input type="button" value="Fortryd" onclick="document.location.href='index.php?pane=users'" class="knap"> <input type="submit" value="Gemt" name="submited" class="knap"></td>
                            </tr>
                          </table>
                          <br>
                      </td>
                  </tr>
               </table>
              </td>
            </tr>
          </table>
      </form>
    </body>
</html>
