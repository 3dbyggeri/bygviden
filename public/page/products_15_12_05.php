<?
require_once("admin/util/products.php");

class Produkter extends View 
{
  var $menu;
  var $dba;
  var $products;
  var $producer;
  var $alfabet;
  var $isVareGroupALeaf;

  function Produkter()
  {
    $this->pub = new publication();
    $this->action='products';
    $this->headLine = 'Produkter';
    $this->dba = new dba();
    $this->branche = ($_SESSION['branche'])? $_SESSION['branche']:'general';
    $this->page = $_SERVER['PHP_SELF'].'?action=';

    $this->menu = array(
                        'varegrupper'=>'Varegrupper',
                        'producenter'=>'Producenter',
                        'produkter'=>'Produkter',
                        'anvisninger'=>'Byggeteknisk vejledning'
                       );
    $this->products = new products( $this->dba );
    $this->loadPage();
  }
  function getAlphabet()
  {
    $alfa = range('A','Z');
    $alfa[count($alfa)] = '&AElig;';
    $alfa[count($alfa)] = '&Oslash;';
    $alfa[count($alfa)] = '&Aring;';
    

    $this->alfabet = Array();
    for($i=0;$i<count($alfa);$i++) $this->alfabet[$alfa[$i]] = array();
    
    $a = '';
    foreach($this->alfabet as $key=>$value) $a.='<a href="#'.$key.'">'.$key.'</a>&nbsp;&nbsp;';
    return $a; 
  }
  function getFloaterBoxJavascript()
  {
    return '
            <style type="text/css">
            #alfabet{
              font-size: 12px;
              font-weight: bold;
              background-color: #F6F7E7;
              padding:4px;
              position:absolute; visibility:hidden;
              height:auto;
              margin:0; padding:4px;
            }
            </style>
            <script type="text/javascript">
            window.onload = function()
            {
                if (window.winOnLoad) window.winOnLoad();
            }
            </script>
            <script type="text/javascript" src="x_core.js"></script>
            <script type="text/javascript" src="x_slide.js"></script>
            <script type="text/javascript" src="x_event.js"></script>
            <script type="text/javascript">
            var slideTime = 700;
            var topMargin;
            var leftMargin;

            function winOnLoad()
            {
              topMargin = xPageY(\'content\');
              leftMargin = xPageX(\'content\');
              winOnResize(); // set initial position
              xAddEventListener(window, \'resize\', winOnResize, false);
              xAddEventListener(window, \'scroll\', winOnScroll, false);
              return;
            }
            function winOnResize() {
              xMoveTo(\'alfabet\', (leftMargin), topMargin);
              xShow(\'alfabet\');
              winOnScroll(); // initial slide
            }
            function winOnScroll() {
              y =  (xPageY(\'floater\') < xScrollTop() )? xScrollTop():xScrollTop() + topMargin;
              xSlideTo(\'alfabet\', (leftMargin), y, slideTime);
            }
            </script>';
  }
  function loadPage()
  {
    if($_REQUEST['section']=='varegrupper')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
      if($_REQUEST['id']) 
      {
        $this->headLine = $this->convertCase($this->products->getVaregruppeName($_REQUEST['id']));
        $this->isVareGroupALeaf = $this->products->isVareGroupALeaf($_REQUEST['id']);
        if($this->isVareGroupALeaf) return;
      }
    }
    if($_REQUEST['section']=='producenter')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
      if($_REQUEST['id']) 
      {
        $this->producer = $this->products->loadProducer($_REQUEST['id']);
        $this->headLine = $this->producer['name'];
        return;
      }
    }
    if($_REQUEST['section']=='anvisninger')
    {
      $this->headLine = $this->menu[$_REQUEST['section']];
    }
    $this->headLine.= '</div><div id="alfabet">'. $this->getAlphabet();
    $this->headLine.= $this->getFloaterBoxJavascript();
  }
  function getVareGruppeIllustration()
  {
    $str = '<map name="varegrupper">
              <!--tag -->
              <area shape="rect" coords="15,15,149,44" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=153">

              <!-- lofter -->
              <area shape="rect" coords="354,15,489,44" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=154">

              <!-- Vægge -->
              <area shape="rect" coords="15,154,106,183" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=155">

              <!--vådrum-->
              <area shape="rect" coords="15,224,106,253" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=159">

              <!--vinduer dore-->
              <area shape="rect" coords="15,269,107,296" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=161">

              <!-- kloak -->
              <area shape="rect" coords="15,314,107,344" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=160">

              <!-- etagedæk -->
              <area shape="rect" coords="354,183,489,212" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=156">

              <!-- gulve -->
              <area shape="rect" coords="354,281,489,310" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=162">

              <!-- fundament -->
              <area shape="rect" coords="354,407,489,437" 
                href="'.$this->page.$this->action.'&section='.$this->getCurrentItem().'&id=157">

            </map>';
    $str.='<center>';
    $str.='<img usemap="#varegrupper" width="500" height="450" src="product_images/tegning_internet.png" border="0">';
    $str.='</center>';
    return $str;
  }
  function isURL($str)
  {
    if(!trim($str)) return false;
    if(substr($str,3) =='www') return 'http://'.$str;
    return $str;
  }
  function getVareGruppe($varegruppe_id)
  {
    $group = $this->products->getProductsForVaregruppeGroupByProducer($varegruppe_id);
    if(!$group) return 'Ingen produkter under denne varegruppe';
    $p = ''; 
    foreach($group as $key=>$producenterne)
    {

      $p.= $this->pub->renderCategory($producenterne['producer_id'],$producenterne['producer_name'],'close');
      foreach($producenterne['products'] as $p_id=>$products)
      {
        $p.= $this->renderProduct($products);
      }
      $p.='</div></div>';
    }
    return $p;
  }
  function getProducentInfo()
  {
    $info = array('adresse'=>'Adresse',
                  'telefon'=>'Telefon',
                  'mail'=>'E-mail',
                  'home_page'=>'Website',
                  'fax'=>'Fax',
                  'CVR'=>'CVR'
                  );
    $str='';
    foreach($info as $key=>$value)
    {
      if(!$this->producer[$key]) continue;
      $str.= '<tr>
                <td valign="top">'.$value.':</td>
                <td>';

      if($key=='home_page') $str.='<a target="_blank" href="'.$this->isURL($this->producer[$key]).'">';
      if($key=='mail') $str.='<a href="mailto:'. $this->producer[$key].'">';
      $str.= $this->producer[$key];

      if($key=='mail' || $key=='home_page') $str.='</a>'; 
      $str.='</td></tr>';
    }
    
    if($str) $str='<table cellpadding="0" cellspacing="0" border="0" class="formtable">'.$str.'</table>';
    return $str;
  }
  function renderProduct($product)
  {
    $url = $this->isURL($product['home_page']);
    $usage = $this->isURL($product['usage_description']);
    $producer = $product['producer_name'];

    $str = '<div class="catcontent"><p><strong>';
    if($url) $str.='<a href="'.$url.'" target="_blank">';
    $str.= $product['name'];
    if($url) $str.='</a>';  
    $str.='</strong></p>';
    
    if($product['description']) $str.='<p>'. $product['description'] .'</p>';
    if($product['observation']) $str.='<p bgcolor="#ff9900">'. $product['observation'] .'</p>';

    if($usage) $str.= $this->pub->renderLink($usage,'Udf&oslash;relses vejledning','_blank');
    if($producer) $str.= $this->pub->renderLink('index.php?action=products&section=producenter&id='.$product['producer_id'],$producer); 

    $str.='</div>';
    return $str;
  }
  function getProducent($id)
  {
    $str = '';

    if($this->producer['description']) $str = stripslashes($this->producer['description']).'<br><br>';
    $str.= $this->getProducentInfo(); 

    // load the categories and products
    $categories_and_products = $this->products->loadCategoriesAndProducts($id);
    
    $cat = '';
    foreach($categories_and_products as $key=>$value)
    {
      if(!count($value['products'])) continue;
      $catName = $value['name'];
      if($catName =='root') $catName = 'Diverse';

      
      $cat .= $this->pub->renderCategory($key,$catName,'close');

      for($i=0;$i<count($value['products']);$i++)
      {
        $cat.= $this->renderProduct($value['products'][$i]);
      }
      $cat.='</div></div>';
    }
    return $str.'<br>'.$cat;
  }
  function producenter()
  {
    if($_REQUEST['id']) return $this->getProducent($_REQUEST['id']); 

    $producentArray = $this->products->loadProducenter();
    $productCountForProducent = $this->products->antalProdukterForProducent();

    for($i=0;$i<count($producentArray);$i++)
    {
      $name = $producentArray[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $producentArray[$i];
    }

    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);
      for($i=0;$i<count($value);$i++)
      {
        $hasProducts = $productCountForProducent[$value[$i]['id']];

        $s.='<a href="'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $value[$i]['name']; 
        $s.='</a>';

        if($hasProducts)
        {
            $s.='<span style="font-size:9px;font-weight:900">('.
                $productCountForProducent[ $value[$i]['id'] ].
               ')</span>';
        }

        $s.='<br>';
      }

      $s.= '</div></div>';
    }
    return '<br>'.$s;
  }
  function vareGrupper()
  {
    $id = 0;
    $s = '';
    if($_REQUEST['id']) 
    {
      //find out if this is a second level group
      if($this->isVareGroupALeaf) return $this->getVareGruppe($_REQUEST['id']); 
      $id = $_REQUEST['id'];
    }

    $varegrupper = $this->products->loadVaregrupper($id);
    $productCountForVareGroup = $this->products->antalProdukterForGruppe();

    for($i=0;$i<count($varegrupper);$i++)
    {
      $name = $varegrupper[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $varegrupper[$i];
    }
    
    if(!$_REQUEST['id']) $s = $this->getVareGruppeIllustration();
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        $hasProducts = $productCountForVareGroup[$value[$i]['id']];

        $s.='<a href="'.$this->page.$this->action.'&section='.
            $this->getCurrentItem().'&id='.$value[$i]['id'].'">';
        $s.= $this->convertCase($value[$i]['name']); 
        $s.='</a>';

        if($hasProducts)
        {
            $s.='<span style="font-size:9px;font-weight:900">('.
                $productCountForVareGroup[ $value[$i]['id'] ].
               ')</span>';
        }

        $s.='<br>';
      }

      $s.= '</div></div>';
    }
    return '<br>'.$s;
  }
  function producter()
  {
    $produkter = $this->products->loadProducts();
    for($i=0;$i<count($produkter);$i++)
    {
      $name = $produkter[$i]['name'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $produkter[$i];
    }

    $s = '';
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        $s.= $this->renderProduct($value[$i]);
      }

      $s.= '</div></div>';
    }
    return $s;
  }

  function anvisninger()
  {
    //pull first level
    $anvisninger = $this->products->getAnvisninger();

    for($i=0;$i<count($anvisninger);$i++)
    {
      $name = $anvisninger[$i]['title'];   
      $initial = strtoupper($name{0});
      $this->alfabet[$initial][count($this->alfabet[$initial])] = $anvisninger[$i];
    }
    foreach($this->alfabet as $key=>$value)
    {
      $s.= $this->pub->renderCategory($key,$key);

      for($i=0;$i<count($value);$i++)
      {
        //render videns leverandor
        $s.= $this->pub->renderPublication($value[$i]);
      }

      $s.= '</div></div>';
    }
    return $s;
  }
  function convertCase($str)
  {
    $str = str_replace('&AElig;','&aelig;',$str);
    $str = str_replace('&Oslash;','&oslash;',$str);
    $str = str_replace('&Aring;','&Aring;',$str);
    $str = str_replace(' & ',' og ',$str);
    $str = strtolower($str);
    return $str;
  }
  function Content()
  {
    switch($this->getCurrentItem())
    {
      case('varegrupper'): return $this->vareGrupper();
      case('producenter'): return $this->producenter();
      case('produkter'): return $this->producter();
      case('anvisninger'): return $this->anvisninger();
    }
  }
  function getCurrentItem()
  {
    if($_REQUEST['section']) return $_REQUEST['section'];
    return 'varegrupper';
  }
  function LeftMenu()
  {
    $str = '<div id="navitree">';
    foreach($this->menu as $key=>$value)
    {
      $str.= '<div class="node">';
      if($key == $this->getCurrentItem()) $str.= '<strong>';
      
      $str.= '<a href="'.$this->page.$this->action.'&section='.$key.'">'. $value .'</a>';
      if($key == $this->getCurrentItem() ) $str.= '</strong>';

      $str.= '</div>';
    }
    $str.='</div>';
    return $str;
  }
}
?>
