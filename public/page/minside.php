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

  function MinSide()
  {
    if(!$_SESSION['bruger_id']) Header('Location:index.php');

    $this->action = 'minside';
    $this->page = $_SERVER['PHP_SELF'].'?action=';
    $this->message = 'Rediger dine personlige oplysninger herunder';

    $this->menu = array( 
                         'indstillinger'=>'Indstillinger',
                         'users'=>'Brugerstyring',
                         'usage'=>'Forbrug'
                        );

    $this->dba = new dba();
    $this->bruger = new bruger($this->dba);

    $this->initProducent();

    $this->bruger->setId( $_SESSION['bruger_id'] );
    $this->headLine = $this->menu[$this->getCurrentItem()];
    $this->checkSettingsUpdate();
    $this->checkSvendSettingsUpdate();
    $this->checkAddUser();
    $this->checkDeleteUser();

    if($_GET['user_id']) $this->user_id = $_GET['user_id'];
  }
  function initProducent()
  {
    if(!$_SESSION['producent']) return;

    $this->menu = Array('indstillinger'=>'Indstillinger','products'=>'Mine produkter');

    $this->productID = ($_REQUEST['productID'])? $_REQUEST['productID']:'';
    $this->categoryID = ($_REQUEST['categoryID'])? $_REQUEST['categoryID']:'';

    $this->producenter = new products($this->dba);
    $this->producenter->setProducent($_SESSION['bruger_id']);

    $this->checkDeleteProduct();
    $this->checkDeleteProductCategory();
    $this->checkUpdateProduct();
    $this->checkUpdateProducent();
    $this->checkUpdateCategory();
    $this->checkAddCategory();
    $this->checkAddProduct();
  }
  function checkUpdateCategory()
  {
    if(!$_REQUEST['save_category']) return;
    
    $this->producenter->updateCategory($this->categoryID,
                                       $_POST['name'],
                                       $_POST['home_page'],
                                       $_POST['description']); 

    die('<script>document.location.href=\'index.php?action=minside&item=products\';</script>'); 
  }
  function checkUpdateProducent()
  {
    if(!$_REQUEST['save_producer_settings']) return;

    $this->producenter->setName( $_POST['name'] );
    $this->producenter->setUserName($_POST['user_name']);
    $this->producenter->setHomepage( $_POST['home_page'] );
    $this->producenter->setLogo_url( $_POST['logo_url'] );
    $this->producenter->setAdresse( $_POST['adresse'] );
    $this->producenter->setCity($_POST['city']);
    $this->producenter->setCVR( $_POST['CVR'] );
    $this->producenter->setTelefon( $_POST['telefon'] );
    $this->producenter->setFax( $_POST['fax'] );
    $this->producenter->setMail( $_POST['mail'] );
    $this->producenter->setAdminMail( $_POST['admin_mail'] );
    $this->producenter->setDescription( $_POST['description'] );

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

    $this->producenter->updateProducent();
  }
  function checkUpdateProduct()
  {
    if(!$_REQUEST['save_product']) return;

    $this->producenter->updateProduct($_REQUEST['productID'],
                                  $_POST['name'],
                                  $_POST['home_page'],
                                  $_POST['usage_description'],
                                  $_POST['logo_url'],
                                  $_POST['description'],
                                  $_POST['observation']);

    $this->producenter->relateToVaregrupper($_REQUEST['productID'],$_POST['varegrupper_id']);
    die('<script>document.location.href="index.php?action=minside&item=products";</script>');
  }
  function checkAddProduct()
  {
    if(!$_REQUEST['add_product']) return;
    $productID = $this->producenter->addProduct($_SESSION['bruger_id'], $_REQUEST['parent']);

    //redirect to the new product
    $s = '<script>document.location.href=\'index.php?';
    $s.= 'action=minside&item=products&productID='.$productID.'\';</script>'; 
    die($s);
  }
  function checkAddCategory()
  {
    if(!$_REQUEST['new_category']) return;
    $categoryID = $this->producenter->addProductCategory($_SESSION['bruger_id']);

    //redirect to the new category
    $s = '<script>document.location.href=\'index.php?';
    $s.= 'action=minside&item=products&categoryID='.$categoryID.'\';</script>'; 
    die($s);
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
                                ,$_POST['firmanavn1'] 
                                ,$_POST['firmanavn2'] 
                                ,$_POST['firmanavn3'] 
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
    $this->bruger->setFirmanavn1( $_POST['firmanavn1'] );
    $this->bruger->setFirmanavn2( $_POST['firmanavn2'] );
    $this->bruger->setFirmanavn3( $_POST['firmanavn3'] );
    $this->bruger->setGade( $_POST['gade'] );
    $this->bruger->setSted( $_POST['sted'] );
    $this->bruger->setPostnr( $_POST['postnr'] );
    $this->bruger->setCity( $_POST['city'] );
    $this->bruger->setLand( $_POST['land'] );
    $this->bruger->setEmail( $_POST['email'] );

    $full_name =  $_POST['firmanavn1'] .' '. $_POST['firmanavn2'] .' '. $_POST['firmanavn3'];
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
  function LeftMenu()
  {
    $str = '<div id="navitree">';
    foreach($this->menu as $key=>$value)
    {
      $str.= '<div class="node">';
      if($key == $this->getCurrentItem()) $str.= '<strong>';
      
      $str.= '<a href="'.$this->page.$this->action.'&item='.$key.'">'. $value .'</a>';
      if($key == $this->getCurrentItem() ) $str.= '</strong>';

      $str.= '</div>';
    }
    $str.='</div>';
    return $str;
  }
  function producerForm()
  {
    $props = $this->producenter->loadProducer($_SESSION['bruger_id']);
    $fields = array('name'=>'Producent navn',
                    'user_name'=>'Bruger navn',
                    'home_page'=>'Web adresse (hjemmeside)',
                    'adresse'=>'Adresse',
                    'CVR'=>'CVR nr.',
                    'telefon'=>'Telefon',
                    'fax'=>'Fax nr.',
                    'mail'=>'Firma mail',
                    'admin_mail'=>'Mail til ansvarlig for opdateringer');
    $str = '';
    foreach($fields as $key=>$value)
    {
        $str.='<p>'.$value.'<br />
               <input type="text" name="'.$key.'" class="textfield" value="'.$props[$key].'"/>
               </p>';
    }
    $str .= '<p>Beskrivelse<br />
            <textarea name="description" 
              style="font-size:10px;font-family:verdana;width:100%;height:75px"
              >'. trim($props['description']).'</textarea>
             </p>
        
            <p>Nyt password<br />
              <input type="password" name="password1" class="textfield" />
            </p>
            <p>Bekr&aelig;ft password<br />
              <input type="password" name="password2" class="textfield" />
            </p>';
     return $str;
  }
  function userForm($user_id='')
  {
    if($_SESSION['producent']) return $this->producerForm();

    $props = ($user_id)? $this->bruger->loadSvend($user_id):$this->bruger->loadBruger(); 

    $str  = '
            <p>Bruger navn<span style="color:red">*</span>:<br />
               <input type="text" name="bruger_navn" class="textfield" value="'.$props['bruger_navn'].'"/>
            </p>
            <p>Firma navn (1)<span style="color:red">*</span>:<br />
               <input type="text" name="firmanavn1" class="textfield" value="'.$props['firmanavn1'].'"/>
            </p>
            <p> Firma navn (2):<br />
              <input type="text" name="firmanavn2" class="textfield" value="'.$props['firmanavn2'].'" />
            </p>
            <p>Firma navn (3):<br />
              <input type="text" name="firmanavn3" class="textfield" value="'.$props['firmanavn3'].'" />
            </p>
            <p>E-mail:<br />
              <input type="text" name="email" class="textfield" value="'.$props['email'].'"/>
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
    if($_SESSION['producent']) $save='save_producer_settings';

    return  '
             <form name="myform" method="post" action="index.php">
             <input type="hidden" name="action" value="'.$this->action.'">
             <input type="hidden" name="item" value="'.$this->getCurrentItem().'">
             '.$uid.'
             <p>'.$this->message .'.</p>

             <table cellpadding="0" cellspacing="0" border="0" class="formtable">
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
    $url = 'index.php?action='.$this->action.'&item='.$this->getCurrentItem();

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

    $str.= '<table cellpadding="0" cellspacing="0" border="0" class="formtable">';
    for($i=0;$i<count($svende);$i++)
    {
      $id = $svende[$i]['id'];
      $navn = ( $svende[$i]['firmanavn2'] )? $svende[$i]['firmanavn2']:$svende[$i]['bruger_navn'];

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
                var url =\'index.php?action=minside&item=products&add_product=1&parent=\';
                url+= category_id;
                document.location.href=url;
              }
              function removeProduct(product_id)
              {
                if(!confirm(\'Slett produkt?\')) return;
                document.produkter.remove_product.value = product_id;
                document.produkter.submit();
              }
              function moveProduct(product_id)
              {
                props = \'width=500,height=600\';
                url = \'moving_product.php?producent_id='.$_SESSION['bruger_id'].'&product_id=\'+product_id;
                w= window.open(url,\'moving\',props);
                w.focus();
              }
              function removeCategori(category_id)
              {
                if(!confirm(\'Slet kategori og dets produkter?\')) return;
                document.produkter.remove_category.value = category_id;
                document.produkter.submit();
              }
            </script>';
  }
  function productView()
  {
    $props = $this->producenter->loadProduct($this->productID);
    $varegrupper = $this->producenter->getVaregrupper($this->productID);

    $str="<script>
            function chooseVareGroup()
            {
              
              style = 'scrollbars=yes,toolbar=no,status=no,menubar=no,location=no';
              style+= ',directories=no,resizable=yes,width=400,height=600';
              w = window.open('admin/products/tree_select.php','select',style);
              w.focus();
            }
            function selectedVaregruppe(id,name)
            {
              addToList(document.bruger.varegrupper,name,id);
              return;
              document.bruger.varegruppe_id.value = id;
              document.bruger.varegruppe_name.value = name;
            }
            function errorImg(img)
            {
              img.src = 'graphics/transp.gif';
            }
          </script>";
     
     $img = $props['logo_url']? $props['logo_url']:'admin/graphics/transp.gif';

     $str.='<form name="bruger" method="post" action="index.php">
              <input type="hidden" name="action" value="minside"> 
              <input type="hidden" name="item" value="products"> 
              <input type="hidden" name="productID" value="'.$this->productID.'">
            <table cellpadding="3" cellspacing="0" class="formtable" border="0">
              <tr>
                <td>Produkt navn:</td>
              </tr>
              <tr>
                <td>
                  <input class="textfield" type="text" style="width:85%" name="name" value="'.$props['name'].'">
                </td>
              </tr>
              <tr>
                <td>Web adresse for produktet:</td>
              </tr><tr>
                <td>
                  <input class="textfield" type="text"  style="width:85%" 
                    name="home_page" value="'.$props['home_page'].'">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.home_page.value)"> 
                </td>
              </tr>
              <tr>
                <td>URL til produktets anvisning:</td>
              </tr><tr>
                <td>
                  <input type="text" name="usage_description" class="textfield" 
                    style="width:85%" 
                    value="'.$props['usage_description'].'">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.usage_description.value)"> 
                </td>
              </tr>
              <tr>
                <td>Beskrivelse:</td>
              <tr></tr>
                <td>
                  <textarea name="description" 
                    style="font-size:10px;font-family:verdana;width:85%;height:75px"
                    >'.trim($props['description']).'</textarea>
                </td>
              </tr>

              <tr>
                  <td>Varegrupper:</td>
              <tr></tr>
                  <td>
                  <script>
                    function addToList(listField, newText, newValue) 
                    {
                      if(!listField) listField = document.myform.varegrupper;
                      if ( ( newValue == "" ) || ( newText == "" ) ) return;
                      var len = listField.length++;  // Increase the size of list and return the size
                      listField.options[len].value = newValue;
                      listField.options[len].text = newText;
                      listField.selectedIndex = len;  // Highlight the one just entered (shows the user that it was entered)
                    }
                   
                    function removeFromList(listField) 
                    {
                      if(!listField) listField = document.bruger.varegrupper;
                      if ( listField.length == -1)  return;
                      var selected = listField.selectedIndex;
                      if (selected == -1) return;
                      var replaceTextArray = new Array(listField.length-1);
                      var replaceValueArray = new Array(listField.length-1);
                      for (var i = 0; i < listField.length; i++)
                      {
                        if ( i < selected) { replaceTextArray[i] = listField.options[i].text; }
                        if ( i > selected ) { replaceTextArray[i-1] = listField.options[i].text; }
                        if ( i < selected) { replaceValueArray[i] = listField.options[i].value; }
                        if ( i > selected ) { replaceValueArray[i-1] = listField.options[i].value; }
                      }
                      listField.length = replaceTextArray.length;
                      for (i = 0; i < replaceTextArray.length; i++)
                      {  
                        listField.options[i].value = replaceValueArray[i];
                        listField.options[i].text = replaceTextArray[i];
                      }
                    }
                    function saveVaregrupper()
                    {
                      var vg = "";
                      listField = document.bruger.varegrupper;
                      for (var i = 0; i < listField.length; i++)
                      {
                        if(vg!="")  vg+=",";
                        vg+= listField[i].value;
                      }
                      document.bruger.varegrupper_id.value = vg;
                    }
                  </script>
                  <input type="hidden" name="varegrupper_id" class="input">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td>
                        <select size="5" name="varegrupper" style="width:250px">';

                for($i=0;$i< count($varegrupper);$i++)
                {
                  $str.= '<option value="'.$varegrupper[$i]['id'].'">'.$varegrupper[$i]['name'].'</option>';
                }

                $str.='</select>
                      </td>
                      <td style="padding-left:3px">
                        <input class="knap" type="button" value="Fjern" onclick="javascript:removeFromList()">
                        <br>
                        <input style="margin-top:4px" class="knap" type="button"
                          value="Tilf&oslash;j" onclick="javascript:chooseVareGroup()">
                      </td>
                   </tr>
                  </table>
                  </td>
               </tr>

              <tr>
              <td>
                <br>
                <input class="button" type="button" 
                    onclick="document.location.href=\'index.php?action=minside&item=products\'" 
                    value="Fortryd">
                <input onclick="saveVaregrupper()" type="submit" name="save_product" class="button" value="Gem">

              </td>
            </tr>
            </table>
            </form>';
      return $str;
  }
  function categoryView()
  {
    $props = $this->producenter->loadCategory($this->categoryID);

    $str.='<form name="bruger" method="post" action="index.php">
              <input type="hidden" name="action" value="minside"> 
              <input type="hidden" name="item" value="products"> 
              <input type="hidden" name="categoryID" value="'.$this->categoryID.'">
            <table cellpadding="3" cellspacing="0" class="formtable" border="0">
              <tr>
                <td>Kategori navn:</td>
              </tr>
              <tr>
                <td>
                  <input class="textfield" type="text" style="width:85%" name="name" value="'.$props['name'].'">
                </td>
              </tr>
              <tr>
              <tr>
                <td>Web adresse for kategori:</td>
              </tr><tr>
                <td>
                  <input class="textfield" type="text"  style="width:85%" 
                    name="home_page" value="'.$props['home_page'].'">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.home_page.value)"> 
                </td>
              </tr>
              <tr>
                <td>Beskrivelse:</td>
              <tr></tr>
                <td>
                  <textarea name="description" 
                    style="font-size:10px;font-family:verdana;width:85%;height:75px"
                    >'.trim($props['description']).'</textarea>
                </td>
              </tr>
              <tr>
              <td>
                <br>
                <input class="button" type="button" 
                    onclick="document.location.href=\'index.php?action=minside&item=products\'" 
                    value="Fortryd">
                <input type="submit" name="save_category" class="button" value="Gem">

              </td>
            </tr>
            </table>
            </form>';
      return $str;
  }
  function productsOverview()
  {
    $str = '<p>Du kan administrere din virksomheds produkter her. 
              Tilf&oslash;j produkter til en eksisterende kategori, eller oprett ny produkt kategorier.
            </p>';

    if($this->productID) return $this->productView();
    if($this->categoryID) return $this->categoryView();

    $categories_and_products = $this->producenter->loadCategoriesAndProducts($_SESSION['bruger_id']);

    $str.= '<p><a href="index.php?action=minside&item=products&new_category=1"><img 
                    src="graphics/link_arrows.gif" 
                    class="imgarrow" height="5" width="7" 
                    border="0" align="left" />Nyt produkt kategori</a></p>';

    if(!count( $categories_and_products ) )
    {
      $str.= '<div>
                  <strong>Ingen produkter eller produkt kategorier</strong>
              </div>';
      return $str;
    }

    $str.= $this->productScripts();

    $str.= '<form name="produkter" action="'.$_SERVER['PHP_SELF'].'" method="post">
              <input type="hidden" name="action" value="minside"> 
              <input type="hidden" name="item" value="products"> 
              <input type="hidden" name="remove_product">
              <input type="hidden" name="remove_category">
              <input type="hidden" name="product_category" value="0">
              <input type="hidden" name="publish_product">
              <input type="hidden" name="unpublish_product">';

    foreach($categories_and_products as $key=>$value)
    {
      $name = ($key==0)? 'Diverse':$value['name'];
      $hasChildrens = (count($value['products']))? true:false;

      $str.='<div class="category">
									<div class="catheadline">
                    <table width="100%"><tr><td>';

      if($hasChildrens) $str.='<a href="javascript:toggleCategory(\''. $key .'\')"><img src="graphics/plus.gif" 
                              class="imgcategory" alt="Skjul" height="9" width="9" 
                              id="img_'. $key .'" border="0" align="left" />'.$name. '</a>';
      else $str.= $name;

      $str.='</td>
             <td align="right">
              <a  href="index.php?action=minside&item=products&categoryID='.$key.'" 
               style="font-size:10px;color:#666" title="Rediger kategorien">Rediger</a>
               &nbsp;
              <a  href="javascript:addProduct(\''.$key.'\')" 
               style="font-size:10px;color:#666" title="Tilf&oslash;j produkt">Tilf&oslash;j produkt</a>';

      if($key!=0) $str.='&nbsp;
                         <a style="font-size:10px;color:#666" 
                          href="javascript:removeCategori(\''.$key.'\')" 
                          title="Slet kategorien">Slet</a>';

      $str.= '</td></tr></table></div><div id="container_'.$key.'" style="display:none">';

      for($i=0;$i<count($value['products']);$i++)
      {
        $str.= $this->renderProduct($value['products'][$i]);
      }
      $str.= '</div></form>';
    }

    return $str;
  }
  function renderProduct($product)
  {
    $request = $product['publish_request'];
    $publish = $product['publish'];
    $str = '<table class="catcontent" width="100%" style="border-bottom:1px dashed #e3e3e3">
              <tr><td>';

    if($request) $str.='<div id="yellowbox" title="Publicering under behandling">&nbsp;</div>';
    if($publish=='y') $str.='<div id="greenbox" title="Publiceret">&nbsp;</div>';
    if($publish=='n' && !$request) $str.='<div id="redbox" title="Ikke publiceret">&nbsp;</div>';
    
    $str.= '&nbsp;</td>';

    $str.='<td><strong><a href="index.php?action=minside&item=products&productID='.
          $product['id'].'" title="Rediger produkt">'.$product['name'].'</a></strong></td>';
    
    $str.='<td align="right">';
    $str.= '<a href="javascript:removeProduct(\''.$product['id'].'\')"> Slette</a>';

    $str.= '&nbsp; <a href="javascript:moveProduct(\''.$product['id'].'\')"> Flytte</a>';

    if($request || $publish=='y') 
    {
      $str.= '&nbsp; <a href="javascript:unpublish_product(\''.$product['id'].'\')">Nedtag</a>';
    }
    else 
    {
      $str.= '&nbsp; <a href="javascript:publish_product(\''.$product['id'].'\')"> Publicere</a>';
    }
    $str.='</td><tr></table>';
    return $str;
  }

  function renderNoForbrug($billing=0)
  {
    $billing = ($billing)? 'faktureret':'';
    return '<table cellpadding="4" cellspacing="1" border="0" width="100%" id="table">
              <tr>
                <th>Intet '.$billing.' forbrug</th>
              </tr>
            </table>';
  }

  function renderForbrug($forbrug,$svend=0)
  {
    if($svend) $name = '<th>Bruger navn</th>';
    $str = '<table cellpadding="4" cellspacing="1" border="0" width="100%" id="table">
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
        $n = $f['firmanavn2']? $f['firmanavn2']:$f['bruger_navn'];
        $name = '<td valign="top"><a 
                            href="index.php?action=minside&item=users&user_id='.$f['id'].'">'.$n.'</a></td>';
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
    switch($this->getCurrentItem())
    {
      case('users'): return $this->usersForm();
      case('usage'): return $this->usageForm();
      case('billing'): return $this->usageForm(1);
      case('products'): return $this->productsOverview();
    }
    return $this->indstillingerForm();
  }
}
?>
