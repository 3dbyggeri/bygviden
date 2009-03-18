<?php
/**********************************************************************/
/*                                                                    */
/*   File name: menu.php                                              */
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*   Date: 28-11-2001 15:36:39                                        */
/*                                                                    */
/**********************************************************************/

        require("../../util/dba.php");
        require("../../util/html.php");
        require("../../util/display_label.php");
        session_start();

        $dba = new dba();
        $prefix = $dba->getPrefix();
    
    
    if(!empty($id))
    {
        $sql = "SELECT name ";
        if( $type == "media" ) $sql.= " FROM ".$prefix."mediatree WHERE id=$id ";
        else $sql.= " FROM ".$prefix."tree WHERE id=$id ";

        $result = $dba->exec( $sql );
        $record = $dba->getRecord( $result );

        $name = $record[0];
        $type=='page'?$type='intern':$type='media';
    }
    else
    {
        $type="intern";
    }
?>
<HTML>
	<HEAD>
		<TITLE>menu</TITLE>
		<LINK REL="stylesheet" HREF="../../wizi.css" TYPE="text/css">
		<SCRIPT>
			function canceling()
            {
				parent.window.close();
			}
			function accepting()
            {
                var link_indhold;

                //get link indhold
                if( parent.opener )
                {
                    if(parent.opener.a_indhold)
                    {
                        link_indhold = parent.opener.a_indhold;
                    }
                }

                if(link_indhold=='' || link_indhold==null)
                {
                    link_indhold = 'link';
                }
                    
                //check for open window
                target = ' target="_blank" ';
                anchor = '';

                //check link type
                if(document.my_form.typeOfLink.value=='extern')
                {
                    str = '<a href='+ document.my_form.adress.value + anchor +' ';
                    str+= target +' class=myLink>'+ link_indhold +'</a>'; 

                }
                else if(document.my_form.typeOfLink.value=='intern')
                {
                    str = '<a href=index.php?id='+ document.my_form.item_id.value + anchor +' ';
                    str+= target +' class=myLink>'+ link_indhold +'</a>'; 
                }
                else if(document.my_form.typeOfLink.value=='media')
                {
                    str = '<a href=media.php?id='+ document.my_form.item_id.value +' ';
                    str+= target +' class=myLink>'+ link_indhold +'</a>'; 
                }
                else
                {
                    str = '<a href='+ document.my_form.adress.value + anchor +' ';
                    str+= target +' class=myLink>'+ link_indhold +'</a>'; 
                }

                if(parent.opener)
                {
                    if(parent.opener.new_link)
                    {
                        parent.opener.new_link(str);
                        parent.window.close();
                    }
                }
                return;
			}

            function radio( name )
            {
                if(name=='extern')
                {
                    parent.canvas.location.href = 'about:blank';
                    document.my_form.adress.value = 'http://';
                    document.my_form.typeOfLink.value = 'extern';
                }
                if(name=='intern')
                {
                    parent.canvas.location.href = '../../tree/tree.php?mode=select';
                    document.my_form.adress.value = '';
                    document.my_form.typeOfLink.value = 'intern';
                }
                if(name=='media')
                {
                    parent.canvas.location.href = '../../mediatree/tree.php?mode=select';
                    document.my_form.adress.value = '';
                    document.my_form.typeOfLink.value = 'media';
                }
                if(name=='mail')
                {
                    parent.canvas.location.href = 'about:blank';
                    document.my_form.adress.value = 'mailto:';
                    document.my_form.typeOfLink.value = 'mail';
                }
                if(name=='ftp')
                {
                    parent.canvas.location.href = 'about:blank';
                    document.my_form.adress.value = 'ftp://';
                    document.my_form.typeOfLink.value = 'ftp';
                }
                if(name=='anchor')
                {
                    parent.canvas.location.href = 'about:blank';
                    document.my_form.adress.value = '';
                    document.my_form.typeOfLink.value = 'anchor';
                }
            }

		</script>
	</head>
	<body bgcolor="#999999">
	      <form name="my_form">
              <input type="hidden" name="item_id" value="<?php echo $id; ?>">
              <input type="hidden" name="typeOfLink" value="<?php echo $type; ?>">
		      <table  width="100%" border="0" cellpadding=0 cellspacing=0>
				<!--ADRESS-->
				<tr style="background-color:#cdcdcd">
					<td colspan="2">&nbsp;&nbsp;Link adress</td>
				</tr>
				<tr style="background-color:#cdcdcd">
					<td colspan="2">
						<input type="text" name="adress" style="width:200px;" value="<?php echo $name; ?>">
					</td>
				</tr>

				<!--ANCHOR NAME-->
                <!--
				<tr style="background-color:#cdcdcd">
					<td colspan="2">&nbsp;&nbsp;Anchor name</td>
				</tr>
				<tr style="background-color:#cdcdcd">
					<td colspan="2">
						<input type="text" name="anchor" style="width:200px;">
					</td>
				</tr>
                -->
				<tr style="background-color:#cdcdcd">
					<td colspan="2">&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
                
                <!--link type-->
				<tr style="background-color:#e3e3e3">
					<td colspan="2">&nbsp;&nbsp;Link type</td>
				</tr>
                <!--Internt link-->
				<tr style="background-color:#cdcdcd">
					<td>
						<input type="radio" name="linkType" value="intern" checked  onClick="radio(this.value)">
					</td>
					<td>&nbsp;&nbsp;Intern </td>
				</tr>

                <!--Extern link-->
				<tr style="background-color:#e3e3e3">
					<td>
						<input type="radio" name="linkType" value="extern" onClick="radio(this.value)">
					</td>
					<td>&nbsp;&nbsp;Extern </td>
				</tr>

                <!--Media link-->
				<tr style="background-color:#cdcdcd">
					<td>
						<input type="radio" name="linkType" value="media" onClick="radio(this.value)">
					</td>
					<td>&nbsp;&nbsp;Media</td>
				</tr>

                <!--Mail link-->
				<tr style="background-color:#e3e3e3">
					<td>
						<input type="radio" name="linkType" value="mail" onClick="radio(this.value)">
					</td>
					<td>&nbsp;&nbsp;Mail </td>
				</tr>

                <!--ftp link-->
				<tr style="background-color:#cdcdcd">
					<td>
						<input type="radio" name="linkType" value="ftp" onClick="radio(this.value)">
					</td>
					<td>&nbsp;&nbsp;FTP</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="2"><br><br></td>
				</tr>

				<tr>
					<td>
                         <input type="button" onClick="canceling()" style="width:100px" value=" CANCEL ">
                    </td>
					<td>
                         <input type="button" onClick="accepting()" style="width:100px" value=" OK ">
                    </td>
				</tr>

              </table>
		</form>
	</body>
</html>
