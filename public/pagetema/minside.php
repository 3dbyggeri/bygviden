<?
include_once('admin/util/dba.php');
include_once('admin/util/bruger.php');
include_once('admin/util/products.php');

class MinSide extends View 
{
  var $page;
  var $menu;
  var $dba;
  var $bruger;
  var $message;
  var $user_id;
  var $producenter;
  var $productID;
  var $categoryID;
  var $logo_msg;

  function MinSide()
  {
    if(!$_SESSION['bruger_id']) Header('Location:index.php');

    $this->action = 'minside';
    $this->page = $_SERVER['PHP_SELF'].'?action=';
    $this->message = 'Rediger dine personlige oplysninger herunder';
    if($_SESSION['producent']) $this->message = 'Rediger din firma profil';

    $this->menu = array( 
                         'indstillinger'=>'Indstillinger',
                         'users'=>'Brugerstyring',
                         'usage'=>'Forbrug'
                        );

    $this->dba = new dba();
    $this->bruger = new bruger($this->dba);

    $this->initProducent();

    $this->bruger->setId( $_SESSION['bruger_id'] );
    $this->h1 = $this->menu[$this->getCurrentItem()];
    $this->checkSettingsUpdate();
    $this->checkSvendSettingsUpdate();
    $this->checkAddUser();
    $this->checkDeleteUser();

    if($_GET['user_id']) $this->user_id = $_GET['user_id'];
  }
  function initProducent()
  {
    if(!$_SESSION['producent']) return;

    $this->menu = Array('indstillinger'=>'Indstillinger','products'=>'Mine produkter','news'=>'Mine nyheder');

    $this->productID = ($_REQUEST['productID'])? $_REQUEST['productID']:'';
    $this->categoryID = ($_REQUEST['categoryID'])? $_REQUEST['categoryID']:'';

    $this->producenter = new products($this->dba);
    $this->producenter->setProducent($_SESSION['bruger_id']);

    $this->checkDeleteProduct();
    $this->checkDeleteProductCategory();
    $this->checkUpdateProducent();
  }
  function checkUpdateProducent()
  {
    if(!$_REQUEST['save_producer_settings']) return;

    $this->producenter->setName( $_POST['name'] );
    $this->producenter->setUserName($_POST['user_name']);
    $this->producenter->setHomepage( $_POST['home_page'] );
    $this->producenter->setAdresse( $_POST['adresse'] );
    $this->producenter->setCVR( $_POST['CVR'] );
    $this->producenter->setTelefon( $_POST['telefon'] );
    $this->producenter->setFax( $_POST['fax'] );
    $this->producenter->setMail( $_POST['mail'] );
    $this->producenter->setAdminMail( $_POST['admin_mail'] );
    $this->producenter->setDescription( $_POST['description'] );
    $this->producenter->admin_name = $_POST['admin_name'];
    $this->producenter->admin_telefon = $_POST['admin_telefon'];

    $_SESSION['bruger_navn'] = $_POST['name'];
    $this->message = 'Indstillinger er gemt ( '. date('H:i:s') .' )';

    if( $_POST['password1'] == $_POST['password2'] )
    {
      if( trim( $_POST['password1'] ) ) $this->producenter->setUserPassword($_POST['password1']);
    }
    else
    {
      $this->message = "Password er ikke ens";
    }
    if($_FILES['logo']['name'])
    {
        $path_info = pathinfo($_FILES['logo']['name']);
        $logo = $this->producenter->producentId.'.'.$path_info['extension'];
        $target_path = realpath('logo').'/'.$logo;

        list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']); 

        if ((($_FILES["logo"]["type"] == "image/gif")
        || ($_FILES["logo"]["type"] == "image/jpeg")
        || ($_FILES["logo"]["type"] == "image/png")
        || ($_FILES["logo"]["type"] == "image/pjpeg"))
        && ($_FILES["logo"]["size"] < 20000)
        && ($width<200 || $height<150))
        {
          if ($_FILES["logo"]["error"] > 0)
          {
            $this->logo_msg ="Det var ikke mulig at uploade filen " . $_FILES["logo"]["error"];
          }
          else
          {

            if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) 
            {
                $this->logo_msg ="Filen ".  basename( $_FILES['logo']['name'])." er blevet uploaded";
                $this->producenter->setLogo_url($logo);
            } 
            else
            {
                $this->logo_msg ="Det var ikke mulig at uploade filen";
            }
          }
        }
        else
        {
            $this->logo_msg = "<p>Billedet skal v&aelig;re under 200 pixel i brede og 150 pixel i h&oslash;jde.</p>";
            $this->logo_msg.= "<p>Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 20KB.</p>";
            $this->logo_msg.= "<p>Formatet skal v&aelig;re enten gif, png eller jpg.</p>";
        }
    }

    $this->producenter->updateProducent();
  }
  function checkDeleteProduct()
  {
    if(!$_POST['remove_product']) return;
    $this->producenter->removeProduct($_POST['remove_product']);
  }
  function checkDeleteProductCategory()
  {
    if(!$_POST['remove_category']) return;
    $this->producenter->removeCategory($_POST['remove_category']);
  }
  function checkAddUser()
  {
    if(!$_GET['adduser']) return;
    $this->user_id = $this->bruger->addSvend();
  }
  function checkDeleteUser()
  {
    if(!$_GET['deleteuser']) return;
    $this->bruger->deleteSvend($_GET['deleteuser']);
  }
  function checkSvendSettingsUpdate()
  {
    if(!$_POST['save_svend_settings']) return;

    $extraklips = ( is_numeric( $_POST['klipkort_extra'] ) )? 
                  $_POST['clipkort_amount'] + $_POST['klipkort_extra']:
                  $_POST['clipkort_amount'];

    $this->bruger->updateSvend(  $_POST['user_id']
                                ,$_POST['bruger_navn'] 
                                ,$_POST['firma'] 
                                ,$_POST['navn'] 
                                ,$_POST['titel'] 
                                ,$_POST['email']
                                ,$_POST['password']
                                ,$_POST['restricted_shop']
                                ,$extraklips
                              );
  }
  function checkSettingsUpdate()
  {
    if(!$_POST['save_personal_settings']) return;

    $this->bruger->setBrugerNavn( $_POST['bruger_navn'] );
    $this->bruger->setFirma( $_POST['firma'] );
    $this->bruger->setNavn( $_POST['navn'] );
    $this->bruger->setTitel( $_POST['titel'] );
    $this->bruger->setGade( $_POST['gade'] );
    $this->bruger->setSted( $_POST['sted'] );
    $this->bruger->setPostnr( $_POST['postnr'] );
    $this->bruger->setCity( $_POST['city'] );
    $this->bruger->setLand( $_POST['land'] );
    $this->bruger->setTlf( $_POST['tlf'] );
    $this->bruger->setEmail( $_POST['email'] );

    $full_name =  $_POST['firma'] .' '. $_POST['navn'] .' '. $_POST['titel'];
    $_SESSION['bruger_navn'] = ( trim( $full_name ) )? $full_name:$_POST['bruger_navn'];

    $this->message = '&AElig;ndringerne er blevet gemt ( '. date('H:i:s') .' )';

    if( trim( $_POST['password'] ) ) $this->bruger->setPassword( $_POST['password'] );
  }
  function getCurrentItem()
  {
    if($_REQUEST['item']) return $_REQUEST['item'];
    return 'indstillinger';
  }
  function getProductName()
  {
    return $this->producenter->getProductName($this->productID);
  }
  function getCategoryName()
  {
    return $this->producenter->getCategoryName($this->categoryID);
  }
  function BreadCrumb()
  {
    $str = '<a href="index.php?action='.$this->action.'">Min side</a>
            &gt; ';
    if($this->productID || $this->categoryID) $str.='<a href="index.php?action=minside&item=products">';
    $str.=$this->menu[$this->getCurrentItem()];
    if($this->productID || $this->categoryID) $str.='</a> &gt;'; 

    if($this->productID) $str.= $this->getProductName();
    if($this->categoryID) $str.= $this->getCategoryName();

    return $str;
  }
  function rightMenu() 
  { 
    if(!$_SESSION['producent']) return '';
    if($this->getCurrentItem() == 'indstillinger')
    {
      return '
              <div class="helptext">
                <div class="help_top"></div>
                <p>
                  Dette er siden hvor du kan inds&aelig;tte og redigere dine stamdata og indstillinger. 
                  Du kan ogs&aring; inds&aelig;tte din virksomheds logo. 
                  Kontaktpersonens profil vil ikke v&aelig;re synlig p&aring; nettet. 
                  Kun her i dine indstillinger.
                </p>
                <p>
                  Klik Gem n&aring;r du har udfyldt eller rettet i felterne.
                </p>

                <p>
                  <a target="_blank" href="http://www.bygviden.dk/download/minside/Guide_Producentdata.pdf">Mere hj&aelig;lp &#187;</a>
                </p>
              </div>
             ';
    }

    if($this->getCurrentItem() == 'products')
    {
      return '
              <div class="helptext">
                <div class="help_top"></div>
                  <p>
                  Her opretter du dine produkter og de kategorier du vil organisere dem under. 
                  Opret f&oslash;rst dine kategorier: Klik p&aring; Opret kategori.
                  </p>
                  <p>
                  Opret derefter dine produkter: Klik p&aring; Opret produkt.
                  </p>

                  <p>
                    Hvad betyder ikonerne?
                    <ul>
                      <li>Blyant: rediger </li>
                      <li>Plus: opret et produkt i kategorien</li>
                      <li>Affaldsspand: slet</li>
                    </ul>
                  
                  </p>

                  <p>
                  <a target="_blank" href="http://www.bygviden.dk/download/minside/Guide_Producentdata.pdf">Mere hj&aelig;lp &#187;</a>
                </p>
                
              </div>
              ';
    }

    if($this->getCurrentItem() == 'news')
    {
      return '
              <div class="helptext">
                <div class="help_top"></div>
                <p>
                 Klik p&aring; opret nyhed, hvis du vil inds&aelig;tte en nyhed p&aring; 
                 bygviden.dk og i nyhedsbrevet.
                </p>
                <p>
                  <a target="_blank" href="http://www.bygviden.dk/download/minside/Guide_Producentdata.pdf">Mere hj&aelig;lp &#187;</a>
                </p>
              </div>
             ';
    }
    return ''; 
  }
  
  function LeftMenu()
  {
    $str = '<div id="left_menu">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><img src="graphics/transp.gif" width="10" height="300" /></td>
                        <td valign="top">
                            <ul id="tema_menu">';
    foreach($this->menu as $key=>$value)
    {
      $sel = ($key == $this->getCurrentItem())? 'class="selected"':''; 
      $str.= '<li '.$sel.'><a href="'.$this->page.$this->action.'&item='.$key.'">'. $value .'</a></li>';
    }
    $str.='</ul></td></tr></table></div>';
    return $str;
  }
  function producerForm()
  {
    $props = $this->producenter->loadProducer($_SESSION['bruger_id']);
    $fields = array('name'=>'Firma navn',
                    'home_page'=>'Web adresse (hjemmeside)',
                    'adresse'=>'Adresse',
                    'CVR'=>'CVR nr.',
                    'telefon'=>'Telefon',
                    'fax'=>'Fax nr.',
                    'mail'=>'Firma mail');
    $str = '';
    
    $str.='<table border="0" cellpadding="0" cellspacing="0">';
    $str.='<tr><td valign="top" style="padding-right:10px">';
    $str.='<div style="font-size:12px;font-weight:900">Beskriv dit firma</div>';
    $str .= '<textarea name="description" 
              style="font-size:10px;font-family:verdana;width:300px;height:75px;"
              >'. trim($props['description']).'</textarea>
             </p>';
    $str.='</td><td valign="top" style="padding-left:10px;border-left:1px dashed #e3e3e3">';
    $str.='<div style="font-size:12px;font-weight:900">Inds&aelig;t firma logo</div>';
    if($props['logo_url'] && file_exists(realpath('logo').'/'.$props['logo_url']) )
    {
        $str.='<img src="logo/'.$props['logo_url'].'" /><br />';
    }
    $str.='<input type="file" name="logo" />';
    $str.='<div style="margin-top:5px;font-size:10px;color:#999">';
    $str.= "Billedet skal v&aelig;re under 200 pixel i brede og 150 pixel i h&oslash;jde.<br>";
    $str.= "Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 20KB.<br>";
    $str.= "Formatet skal v&aelig;re enten gif, png eller jpg.<br>";
    $str.='</div>';
    $str.='</td></tr></table><br /><br />';

    $str.='<table border="0" cellpadding="0" cellspacing="0">';
    $str.='<tr><td valign="top" style="padding-right:10px">';
    $str.='<div style="font-size:12px;font-weight:900">Rediger din firma profil</div>';


    foreach($fields as $key=>$value)
    {
        $str.='<p>'.$value.'<br />
               <input type="text" name="'.$key.'" class="textfield" value="'.$props[$key].'"/>
               </p>';
    }
        

    $str.='</td><td valign="top" style="padding-left:10px;border-left:1px dashed #e3e3e3">';
    $str.='<div style="font-size:12px;font-weight:900">Rediger kontaktpersonens profil</div>';

    $fields = array(
                    'admin_name'=>'Navn',
                    'user_name'=>'Bruger navn',
                    'admin_telefon'=>'Direkte telefon',
                    'admin_mail'=>'Mail');

    foreach($fields as $key=>$value)
    {
        $str.='<p>'.$value.'<br />
               <input type="text" name="'.$key.'" class="textfield" value="'.$props[$key].'"/>
               </p>';
    }

    $str.=' <p>Nyt password<br />
              <input type="password" name="password1" class="textfield" />
            </p>
            <p>Bekr&aelig;ft password<br />
              <input type="password" name="password2" class="textfield" />
            </p>';

    $str.='</td></tr></table>';
     return $str;
  }
  function userForm($user_id='')
  {
    if($_SESSION['producent']) return $this->producerForm();

    $props = ($user_id)? $this->bruger->loadSvend($user_id):$this->bruger->loadBruger(); 

    $str  = '
            <p>Brugernavn<span style="color:red">*</span>:<br />
               <input type="text" name="bruger_navn" class="textfield" value="'.$props['bruger_navn'].'"/>
            </p>
            <p>Firmanavn<span style="color:red">*</span>:<br />
               <input type="text" name="firma" class="textfield" value="'.$props['firma'].'"/>
            </p>
            <p>Navn:<br />
              <input type="text" name="navn" class="textfield" value="'.$props['navn'].'" />
            </p>
            <p>Titel:<br />
              <input type="text" name="titel" class="textfield" value="'.$props['titel'].'" />
            </p>
            <p>E-mail:<br />
              <input type="text" name="email" class="textfield" value="'.$props['email'].'"/>
            </p>
            <p>Telefon:<br />
              <input type="text" name="tlf" class="textfield" value="'.$props['tlf'].'"/>
            </p>';
    if(!$user_id) $str.= $this->formAdress($props);

    $str.= '
            <p>Password:<br />
              <input type="text" name="password" value="'.$props['password'].'" class="textfield" /></p>';

     if($user_id) $str .= $this->svendExtras($props);
     return $str;
  }
  function formAdress($props)
  {
    return '
            <p>Adresse:<br />
              <input type="text" name="gade" class="textfield" value="'.$props['gade'].'"/>
            </p>
            <p>Postnr.:<br />
              <input type="text" name="postnr" class="textfield" value="'.$props['postnr'].'"/>
            </p>
            <p>By:<br />
              <input type="text" name="city" class="textfield" value="'.$props['city'].'"/>
            </p>';
  }
  function svendExtras($props)
  {
    if($props['restricted_shop'] =='n') $shop_n = 'checked';
    if($props['restricted_shop'] !='n') $shop_y = 'checked';

    $str = '<input type="hidden" name="clipkort_amount" value="'. $props['clipkort_amount'].'">';
    $str.= '<p>
              <input type="radio" name="restricted_shop" value="n" '.$shop_n.' /> 
              Brugeren kan frit k&oslash;be publikationer
            </p>
            <p>
              <input type="radio" name="restricted_shop" value="y" '.$shop_y.' /> 
              Brugeren kan kun k&oslash;be ved at bruge et klippekort 
            </p>
            ';
    
    $str.= '<p>';
    $str.= ($props['clipkort_amount'])? $props['clipkort_amount']:0;
    $str.= 'kr. er disponibel p&aring; klippekortet';
    $str.= '</p>';

    $str.= '<p>Tilf&oslash;j bel&oslash; (kr.)<br />
            <input type="login_input" type="text" name="klipkort_extra">
            </p>';
    return $str;
  }

  function indstillingerForm($id=0)
  {
    $save = 'save_personal_settings';
    if($id)
    {
       $uid= '<input type="hidden" name="user_id" value="'.$id.'">';
       $save ='save_svend_settings';
    }

    $msg ='<p>'.$this->message.'</p>';
    if($_SESSION['producent']) 
    {
        $save='save_producer_settings';
        $msg='';
    }
    return  '
             <style>
                .leftformcol { font-size:11px; }
                .leftformcol input { font-size:10px; }
             </style>
             <form name="myform" method="post" enctype="multipart/form-data">
             <input type="hidden" name="action" value="'.$this->action.'">
             <input type="hidden" name="item" value="'.$this->getCurrentItem().'">
             '.$uid.'
             '.$msg.'

             <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="leftformcol">
                    '. $this->userForm($id) .'
                    <p align="right">
                      <input name="reset" type="reset" class="button" value="Annuller" /> 
                      <input name="'.$save.'" type="submit" class="button" value="Gem" /> 
                    </p>
                  </td>
                </tr>
              </table>
              </form>';
  }
  function usersList()
  {
    $svende = $this->bruger->getSvende();
    $url = '?action='.$this->action.'&item='.$this->getCurrentItem();

    $str = " 
            <script>
              function slett( id, name )
              {
                if( confirm( 'Vil du slette brugeren '+ name +'?' ) )
                {
                  document.location.href='".$url."&deleteuser='+ id;
                }
              }
            </script>";

    $str.= '<table cellpadding="5" cellspacing="1" border="0" width="90%" class="form_grid">';
    for($i=0;$i<count($svende);$i++)
    {
      $id = $svende[$i]['id'];
      $navn = ( $svende[$i]['navn'] )? $svende[$i]['navn']:$svende[$i]['bruger_navn'];

      $shop = 'Klippekort ( ';
      $shop.=($svende[$i]['clipkort_amount'])? $svende[$i]['clipkort_amount']:0;
      $shop.=' kr. )';

      if( $svende[$i]['restricted_shop'] =='n') $shop = 'Frit forbrug';

      $str.='<tr>
              <td>
                <a href="'.$url.'&user_id='.$id.'">
                '.$navn.'</a>
              </td>
              <td>'.$shop.'</td>
              <td align="right">
                [<a class="infolinks"
                  href="javascript:slett(\''.$id.'\',\''. $navn.'\')">Slet bruger</a>]
                [<a class="infolinks" 
                  href="'.$url.'&user_id='.$id.'">Rediger bruger</a>]
              </td>
            </tr>';
    }
    $str.= '<tr>  
              <td colspan="3" align="right">
                <br><br>
                <input type="button" class="button" value="Tilf&oslash;j bruger" 
                  onclick="document.location.href=\''.$url.'&adduser=1\'">
              </td>
            </tr>';
    $str.= '</table>';
    return $str;
  }
  function usersForm()
  {
    if($this->user_id) return $this->indstillingerForm($this->user_id);
    return $this->usersList();
  }
  function productScripts()
  {
    return '<script>
              function addProduct(category_id)
              {
                GB_show("Ny Produkt", 
                        SERVER_NAME +"/pagetema/editProduct.php?product_id=-1&cat="+ category_id,525,600);
              }
              function removeProduct(product_id)
              {
                if(!confirm(\'Slett produkt?\')) return;
                document.produkter.remove_product.value = product_id;
                document.produkter.submit();
              }
              function removeCategori(category_id)
              {
                if(!confirm(\'Slet kategori og dets produkter?\')) return;
                document.produkter.remove_category.value = category_id;
                document.produkter.submit();
              }
              function toggleCategory(category_id)
              {
                var el = document.getElementById("container_"+ category_id);
                var img = document.getElementById("img_"+ category_id);
                if(el.style.display=="none")
                {
                    el.style.display=\'\';
                    img.src = img.src.replace("_right","_down");
                }
                else
                {
                    el.style.display=\'none\';
                    img.src = img.src.replace("_down","_right");
                }
              }
              function editProductCategory(category_id)
              {
                GB_show("Redigere Produkt Kategori", 
                        SERVER_NAME +"/pagetema/editProductCategori.php?category_id="+ category_id,500,500);
              }
              function editProduct(product_id)
              {
                GB_show("Redigere Produkt", 
                        SERVER_NAME +"/pagetema/editProduct.php?product_id="+ product_id,525,600);
              }
              function addCategory()
              {
                GB_show("Ny Produkt Kategori", 
                        SERVER_NAME +"/pagetema/editProductCategori.php?category_id=-1",500,500);
              }
              function updateCategory(category_id,name)
              {
                var el = document.getElementById(\'cat_\'+ category_id);
                el.innerHTML = name;
              }
              function updateProduct(product_id,name)
              {
                var el = document.getElementById(\'prod_\'+ product_id);
                el.innerHTML = name;
              }

            </script>';
  }
  function productNews()
  {
    if($_GET['rem']) $this->producenter->removeNew($_GET['rem']);

    $str.='<script>
            function removing(id) 
            { 
              if(!confirm("Er du sikker?")) return;document.location.href="index.php?action=minside&item=news&rem="+ id; 
            }
            function editNews(news_id)
            {
              GB_show("Edit nyhed",SERVER_NAME +"/pagetema/editNews.php?news_id="+ news_id,500,625);
            }
          </script>';
    $str.= '<div style="margin-right:10px">
            <p>Her kan du oprette og administrere dine produktnyheder,
               der udsendes i bygviden.dks nyhedsbrev</p>
            <div style="text-align:right">
            <a style="text-decoration:none" href="javascript:editNews(\'-1\')">
            <img src="tema/graphics/plus.gif" align="absbottom" border="0" /> Opret nyhed</a>
           </div>';

    $news = $this->producenter->producerNews($_SESSION['bruger_id']);

    for( $i = 0; $i < count( $news); $i++ )
    {
        $str.='<div style="margin-top:10px;padding-top:4px;border-top:1px dashed #e3e3e3">';
        $str.='<span style="float:right">';
        $str.='<a href="javascript:editNews('.$news[$i]['id'].')"><img src="tema/graphics/edit.png" border="0" /></a>';
        $str.='&nbsp;<a href="javascript:removing('.$news[$i]['id'].')"><img src="admin/graphics/delete.png" border="0" /></a>';
        $str.='</span>';
        $str.='<h3 style="margin:0">'.$news[$i]['title'].'</h3>';
        $str.='<div style="font-size:10px;color:#666">'.$news[$i]['created'].'</div>';
        $str.='<table width="100%" border="0"><tr><td valign="top">';
        $str.='<p>'.$news[$i]['body'].'</p>';
        $str.='</td><td valign="top" align="right" width="250">';
        
        if($news[$i]['image'] && file_exists(realpath('logo').'/'.$news[$i]['image']) )
        {
          $str.='<img src="/logo/'.$news[$i]['image'].'" style="margin:10px" />';
        }

        $str.='</td></tr></table>';
        if($news[$i]['website'])
        {
            $str.='<a style="font-size:10px;text-decoration:none" href="'.$news[$i]['website'].'" target="_blank">&#187; L&aelig;s mere ['.$news[$i]['website'].']</a>';
        }
        $str.='</div>';
    }
    $str.='</div>';
    return $str;
  }
  function productsOverview()
  {
    $str = '<div style="margin-right:10px"><p>Du kan administrere din virksomheds produkter her. 
              Tilf&oslash;j produkter til en eksisterende kategori, eller opret en ny kategori til et eller flere produkter.
            </p>';

    $categories_and_products = $this->producenter->loadCategoriesAndProducts($_SESSION['bruger_id']);

    $str.= '<div style="text-align:right">';
    $str.='<a style="text-decoration:none" href="javascript:addProduct(\'0\')"><img src="tema/graphics/plus.gif" align="absbottom" border="0" /> Opret produkt</a> &nbsp;&nbsp;';
    $str.='<a style="text-decoration:none" href="javascript:addCategory()"><img src="tema/graphics/plus.gif" align="absbottom" border="0" /> Opret produkt kategori</a></div>';

    if(!count( $categories_and_products ) )
    {
      $str.= '<div>
                  <strong>Ingen produkter eller produkt kategorier</strong>
              </div>';
      return $str;
    }

    $str.= $this->productScripts();

    $str.='<style>
            .imgspan
            {
              position:relative;
            }
            .imgplaceholder
            {
              position:absolute;
              background-image:url(/graphics/imageicon.png);
              background-repeat:no-repeat;
              background-position:50% 50%;
              display:block;
              width:14px;
              height:14px;
              float:left;
            }
           </style>';
    $str.= '<form name="produkter" action="'.$_SERVER['PHP_SELF'].'" method="post">
              <input type="hidden" name="action" value="minside"> 
              <input type="hidden" name="item" value="products"> 
              <input type="hidden" name="remove_product">
              <input type="hidden" name="remove_category">
              <input type="hidden" name="product_category" value="0">
              <input type="hidden" name="publish_product">
              <input type="hidden" name="unpublish_product">';

    $str.='<br><table style="background-color:#999;" cellspacing="1" cellpadding="4" width="100%">';
    foreach($categories_and_products as $key=>$value)
    {
      $hasChildrens = (count($value['products']))? true:false;
      if($key==0)
      {
        if(!$hasChildrens) continue;
        $str.= '<tr><td style="background-color:#fff">';

        for($i=0;$i<count($value['products']);$i++)
        {
          $str.= $this->renderProduct($value['products'][$i]);
        }
        $str.= '</td></tr>';
        continue;
      }
      $str.='<tr><td style="background-color:#fff">';
      $str.='<span style="float:right">';
      $str.='<a  href="javascript:editProductCategory('.$key.')" 
                 style="font-size:10px;color:#666" title="Rediger kategorien"><img src="tema/graphics/edit2.gif" align="absbottom" border="0" /></a>
                 ';
      $str.='<a  href="javascript:addProduct(\''.$key.'\')" 
               style="font-size:10px;color:#666" title="Tilf&oslash;j produkt"><img src="tema/graphics/plus.gif" align="absbottom" border="0" /></a>';

      $str.='&nbsp;<a style="font-size:10px;color:#666" 
                          href="javascript:removeCategori(\''.$key.'\')" 
                          title="Slet kategorien"><img src="admin/graphics/delete.png" border="0" align="absbottom" /></a>';

      $str.='</span>';

      if($hasChildrens) 
      {
        $str.='<a href="javascript:toggleCategory(\''. $key .'\')" style="text-decoration:none">';

        $arrow = ($_GET['cat'] ==$key)? 'arrow_down':'arrow_right';
        $str.='<img src="graphics/'.$arrow.'.gif" height="14" width="14" 
                      id="img_'. $key .'" border="0" align="absmiddle" /><span id="cat_'.$key.'">'.$value['name']. '</span></a>';
      }
      else 
      {
        $str.= '<img src="graphics/transp.gif" height="14" width="14"><span id="cat_'.$key.'" style="color:#999">'.$value['name'].'</span>';
      }

      $display = ($_GET['cat'] ==$key)? '':'display:none';
      $str.= '<div id="container_'.$key.'" style="'.$display.'">';

      for($i=0;$i<count($value['products']);$i++)
      {
        $str.= $this->renderProduct($value['products'][$i]);
      }
      $str.= '</div>';
      $str.='</td></tr>';
    }
    $str.='</table>';

    return $str.'</div>';
  }
  function renderProduct($product)
  {
    $request = $product['publish_request'];
    $publish = $product['publish'];
    $str = '<div style="border-top:1px dashed #e3e3e3;margin-left:17px;padding-bottom:3px;padding-top:3px;margin-top:2px">';
    $str.='<span style="float:right;font-size:10px">';

    $str.='<a href="javascript:editProduct('. $product['id'].')" title="Rediger produkt">';
    $str.='<img src="tema/graphics/edit2.gif" align="absbottom" border="0" style="margin-right:3px" /></a>';

    $str.= '<a href="javascript:removeProduct(\''.$product['id'].'\')"><img src="admin/graphics/delete.png" border="0" align="absbottom" /></a>';

    $str.='</span>';

    $margin_left= '';

    if($product['logo_url'] && file_exists(realpath('logo').'/'.$product['logo_url']))
    {
      $margin_left = 'margin-left:20px;';
      $str.='<span class="imgspan"><a id="img_'.$product['id'].'" href="#" class="imgplaceholder"></a></span>';
      $str.='<style>
            #img_'.$product['id'].':hover
            {
              background-image:url(/logo/'.$product['logo_url'].');
              width:200px;
              height:250px;
              background-color:#fff;
              border:1px solid #999;
              padding-left:20px;
              top:-100px;
              z-index:100;
            }
            </style>';
    }

    $str.='<a href="javascript:editProduct('. $product['id'].')" 
            title="Rediger produkt" style="'.$margin_left.'text-decoration:none"><span id="prod_'.$product['id'].'">'.$product['name'].'</span></a>';


    $str.='</div>';
    return $str;
  }

  function renderNoForbrug($billing=0)
  {
    $billing = ($billing)? 'faktureret':'';
    return '<table cellpadding="4" cellspacing="1" border="0" width="100%" class="form_grid">
              <tr>
                <th>Intet '.$billing.' forbrug</th>
              </tr>
            </table>';
  }

  function renderForbrug($forbrug,$svend=0)
  {
    if($svend) $name = '<th>Bruger navn</th>';
    $str = '<table cellpadding="4" cellspacing="1" border="0" width="100%" class="form_grid">
              <tr>
                '.$name .' 
                <th>Titel</th>
                <th>Betingelser</th>
                <th>Dato</th>
                <th>Pris</th>
              </tr>';
              
    for($i=0;$i<count($forbrug);$i++)
    {
      $f = $forbrug[$i];
      $abn = ( $f['abonament_periode'])? $f['abonament_periode'].' Md. abonnement':'Enkelt visning';
      $pris = ( $f['pris'])? $f['pris']:0; 
      $total+= $f['pris'];
      $name = '';

      if($svend)
      {
        $n = $f['navn']? $f['navn']:$f['bruger_navn'];
        $name = '<td valign="top"><a 
                            href="?action=minside&item=users&user_id='.$f['id'].'">'.$n.'</a></td>';
      }

      $str.='<tr>
              '.$name.'
              <td valign="top"><a 
                href="javascript:displaydoc('.$f['publication_id'].',1)" 
                class="infolinks">'.$f['leverandor_name'] .' &#187; '.$f['kategory_name'].' &#187; <strong>'. $f['title'].'</strong></a></td>
              <td valign="top" width="100" NOWRAP>'.$abn.'</td>
              <td valign="top" width="75" NOWRAP>'.$f['readed'].'</td>
              <td valign="top" align="right" width="75" NOWRAP>'.$pris.' kr.</td>
            </tr>';
    }
    if(!$total) $total= 0;

    $colspan= ($svend)? 4:3; 
    $str.='<tfoot>
            <tr>
              <td colspan="'.$colspan.'" align="right">
                  <strong>I alt:</strong></td>
              <td align="right"><strong>'.$total.' kr.</strong></td>
            </tr>
           </tfoot>
        </table>';
    return $str;
  }

  function usageForm($billing=0)
  {
    $forbrug = $this->bruger->getForbrug($billing);
    $str.='';

    if($_SESSION['is_mester']) $str.= '<span id="headline" style="font-size:12px">Dit eget forbrug </span>';

    $str .= ($forbrug)? $this->renderForbrug($forbrug): $this->renderNoForbrug($billing);

    if(!$_SESSION['is_mester']) return $str;

	  $str.= '<br><br>
	          <span id="headline" style="font-size:12px">Dine brugeres forbrug </span>';

    $forbrug = $this->bruger->getSvendeForbrug($billing);

    $str.=($forbrug)? $this->renderForbrug($forbrug,1):$this->renderNoForbrug($billing);

    return $str;
  }
  function Content()
  {
    $meat = '';
    if($this->getCurrentItem()=='users') $meat = $this->usersForm();
    if($this->getCurrentItem()=='usage') $meat = $this->usageForm();
    if($this->getCurrentItem()=='billing') $meat = $this->usageForm(1);
    if($this->getCurrentItem()=='products') $meat = $this->productsOverview();
    if($this->getCurrentItem()=='news') $meat = $this->productNews();
    if(!$meat) $meat = $this->indstillingerForm();

    return '
            <style>#left_content {margin:0;padding:0 }
            </style>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="200">
                        '.$this->LeftMenu().'
                    </td>
                    <td valign="top" >
                        <h1>'.$this->h1.'</h1>
                        <div style="margin:20px;margin-top:10px;">'.$meat.'</div>
                    </td>
               </tr>
            </table>
            ';
  }
}
?>
