<?php
?>
<html>
<head>
  <title>Link</title>
  <script>
    function selected_picture(id)
    {
        //find if its page or media
        if(botom_menu.document.my_form.typeOfLink.value=='intern')
        {
            type='page';
        }
        else
        {
            type='media';
        }
        botom_menu.location.href = '../buildingsection/link_editor/menu.php?id='+ id +'&type='+type;
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
                    
                str = '<a href="'+ document.my_form.url.value;
                str+= '" class="myLink" target="_blank">'+ link_indhold +'</a>'; 


                if(opener)
                {
                    if(opener.new_link)
                    {
                        opener.new_link(str);
                        window.close();
                    }
                }
                return;
			}

    window.focus();

  </script>
</head>  
  <body bgcolor="#cdcdcd">
    <form name="my_form">
      URL:
      <input type="text" size="50" name="url" style="width:190px" value="<?=$_GET['url']?>">
      <br>
      <input type="button" value="Cancel" onclick="window.close()" style="width:50px">
      <input type="button" value="OK" onclick="accepting()" style="width:50px">
    </form>
  </body>
</html>
