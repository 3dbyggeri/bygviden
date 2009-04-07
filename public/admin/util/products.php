<?
class products
{
  var $dba;
  var $p;
  var $table;
  var $producentList;
  var $producentId;
  var $producent;
  var $produkter;

  var $name;
  var $user_name;
  var $user_password;
  var $description;
  var $observation;
  var $publish;
  var $home_page;
  var $logo_url;
  var $adresse;
  var $CVR;
  var $telefon;
  var $fax;
  var $mail;
  var $admin_mail;
  var $admin_name;
  var $admin_telefon;
  var $advertise_deal;
  var $updateDeal;

  function products( $dba )
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'producer';
     $this->updateDeal= false;
  }
  function getAnvisningerTopLevel()
  {
    $anvisninger = $this->getAnvisninger();
    $topLevel = array();
    for($i=0;$i< count($anvisninger);$i++)
    {
      $anvisning = $anvisninger[$i];
      $id = $anvisning['kilde_leverandor_id'];
      if($topLevel[$id]) continue;

      $tmp = array(); 
      $tmp['id'] = $id;
      $tmp['producer_id'] = $anvisning['producer_id'];
      $tmp['title'] = $anvisning['kc_name'];
      $tmp['kilde_forlag'] = $anvisning['kilde_leverandor_forlag'];

      $topLevel[$id] = $tmp;
    }
    return $topLevel;
  }
  function getAnvisningerGroupByProducenter()
  {
    $anvisninger = $this->getAnvisninger();
    $prod = array();
    for($i=0;$i< count($anvisninger);$i++)
    {
      $anvisning = $anvisninger[$i];
      $id = $anvisning['producer_id'];
      $k_id = $anvisning['kildeid'];
      $prod[$id][$k_id]= $anvisning;
    }
    return $prod;
  }
  function getAnvisninger()
  {
    $sql = "SELECT
              k.id                 AS kildeid,
              k.name               AS title,
              k.logo_url           AS kilde_logo,
              k.description        AS kilde_description,
              k.observation        AS kilde_observation,
              k.forlag_url         AS kilde_forlag,

              k.betaling           AS betaling,
              k.enkelt_betaling    AS enkelt_betaling,
              k.abonament_betaling AS abonament_betaling,
              k.overrule_betaling  AS overrule,

              k.digital_udgave     AS digital_udgave,
              k.brugsbetingelser   AS brugsbetingelser,
              k.betegnelse         AS betegnelse,

              kb.id         AS kilde_kategory_id,
              kb.name       AS kilde_kategory_name,
              kb.logo_url   AS kilde_kategory_logo,
              kb.forlag_url AS kilde_kategory_forlag,
              kb.betegnelse AS kilde_kategory_betegnelse,

              kb.betaling           AS cat_betaling,
              kb.enkelt_betaling    AS cat_enkelt_betaling,
              kb.abonament_betaling AS cat_abonament_betaling,
              kb.overrule_betaling  AS cat_overrule,

              kc.id         AS kilde_leverandor_id,
              kc.name       AS kc_name,
              kc.logo_url   AS kilde_leverandor_logo,
              kc.forlag_url AS kilde_leverandor_forlag,
              kc.betegnelse AS kilde_leverandor_betegnelse,

              kc.betaling           AS lev_betaling,
              kc.enkelt_betaling    AS lev_enkelt_betaling,
              kc.abonament_betaling AS lev_abonament_betaling,
              kc.overrule_betaling  AS lev_overrule,

              producer.id AS producer_id,
              producer.name AS kilde_leverandor_name

            FROM
              ". $this->p ."kildestyring as k,
              ". $this->p ."kildestyring as kb,
              ". $this->p ."kildestyring as kc,
              ". $this->table ." as producer 
            WHERE
              k.parent = kb.id
            AND
              kb.parent = kc.id
            AND
              k.element_type = 'publikation'
            AND
              producer.kilde_id = kc.id
            AND
                ( k.timepublish < NOW() OR k.timepublish IS NULL )
            AND
                ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) 
            ORDER BY k.name";
        $publikationer = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );

      $publications = array();
      for( $i = 0; $i < $n; $i++ ) $publications[$i] = $this->dba->fetchArray( $result );
      return $publications;
  }
  function removeCategory($categoryId)
  {
    $sql = "DELETE FROM ". $this->p."products WHERE category_id=". $categoryId;
    $this->dba->exec( $sql );

    $sql = "DELETE FROM ". $this->p."product_category WHERE id=". $categoryId;
    $this->dba->exec( $sql );
  }
  function productPublishRequest($product_id)
  {
    $sql = "UPDATE ".$this->p."products SET publish_request=NOW() WHERE id=$product_id";

    $this->dba->exec( $sql );
  }
  function productUnPublishRequest($product_id)
  {
    $sql = "UPDATE ".$this->p."products SET publish_request=NULL , publish='n' WHERE id=$product_id";
    $this->dba->exec( $sql );
  }
  function moveProduct($product_id,$category_id)
  {
    if(!$product_id or !is_numeric($category_id)) return;
    $sql = "UPDATE ".$this->p."products SET category_id=$category_id WHERE id=". $product_id;
    $this->dba->exec($sql);
  }
  function removeProduct($productId)
  {
    $sql = "DELETE FROM ".$this->p."products WHERE id=". $productId;
    $this->dba->exec( $sql );
  }
  function addProductCategory($producent_id)
  {
    if(!$producent_id) return;
    $sql = "INSERT INTO ". $this->p ."product_category ( name,producer_id) VALUES( 'New category',". $producent_id.")";
    $this->dba->exec( $sql );
    return  $this->dba->last_inserted_id();
  }
  function loadCategory($cat_id)
  {
    if(!$cat_id) return;
    $sql = "SELECT * FROM ".$this->p."product_category WHERE id=". $cat_id;
    return $this->dba->singleArray($sql); 
  }
  function loadProducts()
  {
    $sql = "SELECT 
              p.id as id,
              p.name as name,
              p.description as description,
              p.producer_id as producer_id,
              p.category_id as category_id,
              p.varegruppe_id as varegruppe_id,
              p.home_page as home_page,
              p.logo_url as logo_url,
              p.usage_description as usage_description,
              pr.name as producer_name,
              pr.advertise_deal
            FROM 
              ".$this->p."products as p ,
              ".$this->p."producer as pr 
            WHERE
              pr.id = p.producer_id
            ORDER BY name";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $produkter = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $produkter[$i]= $this->dba->fetchArray( $result );
    }
    return $produkter;
  }
  function getProductName($prod_id)
  {
    $sql = "SELECT name FROM ".$this->p."products WHERE id=".$prod_id;
    return $this->dba->singleQuery($sql);
  }
  function getCategoryName($cat_id)
  {
    $sql = "SELECT name FROM ".$this->p."product_category WHERE id=".$cat_id;
    return $this->dba->singleQuery($sql);
  }
  function loadProduct($prod_id)
  {
    $sql = "SELECT * FROM ".$this->p."products WHERE id=". $prod_id;
    return $this->dba->singleArray($sql); 
  }
  function updateCategory($cat_id,$cat_name,$home_page,$description)
  {
    $sql = "UPDATE ".$this->p."product_category 
            SET 
              name = '". addslashes($cat_name) ."',
              home_page = '". addslashes($home_page) ."',
              description= '". addslashes($description) ."'
            WHERE
              id=$cat_id";
    $this->dba->exec( $sql );
  }
  function updateProduct($prod_id,$name,$home_page,$usage_description,
                         $logo,$description,$observation,$publish='n')
  {
    if(!$varegruppe) $varegruppe = 0;
    $sql = "UPDATE ".$this->p."products
            SET
              name = '". addslashes($name) ."',
              home_page = '". addslashes($home_page) ."',
              usage_description = '". addslashes($usage_description) ."',
              logo_url= '". addslashes($logo) ."',
              description= '". addslashes($description) ."',
              observation= '". addslashes($observation) ."',
              publish = '".$publish."'
            WHERE
              id=$prod_id";
    $this->dba->exec( $sql );
  }
  function relateToVaregrupper($prod_id,$varegrupper)
  {
    $sql = "DELETE FROM ".$this->p."product2varegruppe WHERE product_id=".$prod_id;
    $this->dba->exec($sql);

    if(!$varegrupper) return;
    $varegrupper = explode(",",$varegrupper);
    if(count($varegrupper) == 0) return;
    for($i=0;$i<count($varegrupper);$i++)
    {
      $sql="INSERT INTO ".$this->p."product2varegruppe 
            (product_id,varegruppe_id) VALUES (".$prod_id.",".$varegrupper[$i].")";

      $this->dba->exec($sql);
    }
  }
  function getVaregrupper($prod_id)
  {
    $sql = "SELECT vg.id, vg.name FROM 
            ".$this->p."product2varegruppe AS p2v,
            ".$this->p."varegrupper AS vg
            WHERE
              p2v.varegruppe_id = vg.id
            AND
              p2v.product_id=$prod_id";
    
    $varegrupper = array();
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $varegrupper[ count( $varegrupper) ] = $this->dba->fetchArray( $result );
    }
    return $varegrupper;
  }
  function loadPlacements($product_id)
  {
    $products = array();
    $sql = "SELECT
              bl.name as element_name,
              p2e.publish,
              p2e.publish_request,
              p2e.element_id,
              p2e.branche_name,
              p2e.node_id
            FROM
              ".$this->p."product2element as p2e,
              ".$this->p."buildingelements as bl
            WHERE
              bl.id = p2e.element_id
            AND
              p2e.product_id = $product_id";
    
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $products[ count( $products) ] = $this->dba->fetchArray( $result );
    }
    return $products;
  }
  function saveElementReferenceToProduct($product_id,$node_id,$element_id,$branche)
  {
    //control if the reference allready exists
    $sql = "SELECT * FROM ".$this->p."product2element
            WHERE product_id=$product_id 
            AND node_id=$node_id
            AND element_id=$element_id
            AND branche_name='$branche'";
    if( $this->dba->singleArray($sql) ) return;

    $sql = "INSERT INTO 
              ".$this->p."product2element
            ( 
              product_id,
              element_id,
              branche_name,
              node_id
            )
            VALUES
            (
              $product_id,
              $element_id,
              '$branche',
              $node_id
            )";

     $this->dba->exec($sql); 
  }

  function publishPlacement($product_id,$element_id,$node_id,$branche_name)
  {
    $sql = "UPDATE ".$this->p."product2element
            SET 
              publish = 'y',
              publish_request = NULL
            WHERE
              product_id = $product_id
            AND
              element_id = $element_id
            AND
              branche_name = '$branche_name'
            AND
              node_id = $node_id";
    $this->dba->exec($sql);
  }
  function publishProduct($product_id)
  {
    $sql = "UPDATE ".$this->p."products 
            SET 
              publish = 'y',
              publish_request = NULL
            WHERE
              id = $product_id";
    $this->dba->exec($sql);
  }
  function publishElementReferenceToProduct($product_id,$element_id,$branche,$node)
  {
    $sql ="UPDATE 
            ".$this->p."product2element
           SET 
            publish_request = NOW()
           WHERE
            product_id = $product_id
           AND
            element_id = $element_id
           AND
            branche_name = '$branche'
           AND
            node_id = $node";
    $this->dba->exec($sql);
  }
  function unpublishElementReferenceToProduct($product_id,$element_id,$branche,$node)
  {
    $sql ="UPDATE 
            ".$this->p."product2element
           SET 
            publish = 'n', 
            publish_request = NULL
           WHERE
            product_id = $product_id
           AND
            element_id = $element_id
           AND
            branche_name = '$branche'
           AND
            node_id = $node";
    $this->dba->exec($sql);

  }
  function removeElementReferenceToProduct($product_id,$element_id,$branche,$node)
  {
    $sql = "DELETE FROM 
              ".$this->p."product2element 
            WHERE 
              product_id=$product_id
            AND
              branche_name='$branche'
            AND
              node_id=$node
            AND
              element_id=$element_id";
    $this->dba->exec($sql);

  }
  function addProduct($producent_id,$product_category=0)
  {
    if(!$producent_id) return;
    $sql = "INSERT INTO ". $this->p ."products 
              ( name, producer_id, category_id ) 
            VALUES 
            ( 
              'New product',
              ". $producent_id.",";

    if($product_category) $sql.= $product_category; 
    else $sql.= "NULL";

    $sql.= ")";
    $this->dba->exec( $sql );
    return $this->dba->last_inserted_id();
  }
  function getVaregruppeName($gID)
  {
    if(!$gID) return '';
    $sql = "SELECT 
                g.name as group_name
              FROM
                ".$this->p."varegrupper as g
              WHERE
                g.id = $gID";
    return $this->dba->singleQuery($sql);
  }
  function isVareGroupALeaf($gID)
  {
    
    $sql = "SELECT COUNT(ch.id)
            FROM
                ".$this->p."varegrupper as ch, 
                ".$this->p."varegrupper as v 
            WHERE
                ch.parent = v.id
            AND v.id= $gID";
    return ($this->dba->singleQuery($sql))? false:true;
  }
  function getProductsForVaregruppeGroupByProducer($varegruppe_id)
  {
    $group = $this->getProductsForVaregruppe($varegruppe_id);
    if(!$group) return;
    $groupByProducer = array();

    foreach($group as $key=>$value)
    {
      $pid = $value['producer_id'];
      if(!array_key_exists($pid,$groupByProducer)) $groupByProducer[$pid] = array();
      if(!array_key_exists('products',$groupByProducer[$value['producer_id']]))
      {
        $groupByProducer[$value['producer_id']]['producer_id'] = $value['producer_id'];
        $groupByProducer[$value['producer_id']]['producer_name'] = $value['producer_name'];
        $groupByProducer[$value['producer_id']]['advertise_deal'] = $value['advertise_deal'];
        $groupByProducer[$value['producer_id']]['products'] = array();
      }
      unset($value['producer_name']);
      $groupByProducer[$value['producer_id']]['products'][$value['id']] = $value; 
    }
    return $groupByProducer;
  }
  function getProductsForVaregruppe($varegruppe_id)
  {
    $sql = "SELECT 
                p.id as id,
                p.name as name,
                p.description as description,
                p.producer_id as producer_id,
                p.category_id as category_id,
                p.varegruppe_id as varegruppe_id,
                p.home_page as home_page,
                p.logo_url as logo_url,
                p.usage_description as usage_description,

                prod.id  as producer_id,
                prod.name as producer_name,
                prod.advertise_deal as advertise_deal,

                g.id as group_id,
                g.name as group_name
              FROM
                ".$this->p."products as p,
                ".$this->p."producer as prod,
                ".$this->p."varegrupper as g,
                ".$this->p."product2varegruppe as p2v
              WHERE
                p2v.varegruppe_id = $varegruppe_id
              AND
                p2v.product_id = p.id
              AND
                g.id = p2v.varegruppe_id
              AND
                p.producer_id = prod.id
              ORDER BY
                g.name, p.name";
    
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $group = array();
    
    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      $group[ $row['id'] ] = $row;
    }
    return $group;
  }
  
  function getGroup($gID)
  {
    $sql = "SELECT 
                p.id as id,
                p.name as name,
                p.description as description,
                p.producer_id as producer_id,
                p.category_id as category_id,
                p.varegruppe_id as varegruppe_id,
                p.home_page as home_page,
                p.logo_url as logo_url,
                p.usage_description as usage_description,

                prod.id  as producer_id,
                prod.name as producer_name,

                g.id as group_id,
                g.name as group_name
              FROM
                ".$this->p."products as p,
                ".$this->p."producer as prod,
                ".$this->p."varegrupper as g
              WHERE
                p.varegruppe_id = $gID
              AND
                p.varegruppe_id = g.id
              AND
                p.producer_id = prod.id
              ORDER BY
                p.name";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $group = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      $group[ count($group)] = $row;
    }
    return $group;
  }
  function antalProdukterForProducent()
  {
    $sql = "SELECT producer_id, count(*) AS antal FROM 
           ".$this->p."products WHERE publish='y' GROUP BY producer_id";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $antal = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      $antal[ $row['producer_id'] ]= $row['antal'];
    }
    return $antal;
  }
  function antalProdukterForGruppe()
  {
    $sql = "SELECT p2v.varegruppe_id, count(*) AS antal FROM 
           ".$this->p."product2varegruppe as p2v 
           GROUP BY p2v.varegruppe_id";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $antal = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      $antal[ $row['varegruppe_id'] ]= $row['antal'];
    }
    return $antal;
  }
  function loadProducenter()
  {
    $sql = "SELECT * FROM ".$this->p."producer ORDER BY name";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $produkter = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $produkter[$i]= $this->dba->fetchArray( $result );
    }
    return $produkter;
  }
  function getVaregruppeNavn($id)
  {
    if(!is_numeric($id)) return '';
    $sql = "SELECT name FROM ".$this->p."varegrupper WHERE id=$id";
    return $this->dba->singleQuery($sql);
  }
  function loadVaregrupper($id=0)
  {
    //second level only
    $sql ="SELECT  
            v.id,v.name,ch.parent
           FROM  
              ".$this->p."varegrupper as v
           LEFT JOIN
              ".$this->p."varegrupper as ch 
           ON 
            ch.parent = v.id
           WHERE 
            ch.parent IS NULL
           ORDER BY 
            v.name";

    if($id)
    {
      $sql = "SELECT 
                ch.id,ch.name,ch.parent
              FROM
                ".$this->p."varegrupper as ch, 
                ".$this->p."varegrupper as v 
              WHERE
                ch.parent = v.id
              AND v.id= $id
              ORDER BY
              ch.name";
    }

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );
    $varegrupper = array();

    for( $i = 0; $i < $n; $i++ )
    {
      $varegrupper[$i]= $this->dba->fetchArray( $result );
    }
    return $varegrupper;
  }
  function loadCategories($producent_id)
  {
    $sql = "SELECT 
              id,
              name 
            FROM 
              ".$this->p."product_category 
            WHERE 
              producer_id=". $producent_id." 
            ORDER BY name";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    $cats = array();
    for( $i = 0; $i < $n; $i++ )
    {
      $cats[$i] = $this->dba->fetchArray( $result );
    }
    return $cats;
  }
  function loadCategoriesAndProducts($producent_id)
  {
    $categoriesAndProducts = array( 0=>array('name'=>'root','products'=>array() ) );

    $sql = "SELECT 
              id,
              name 
            FROM 
              ".$this->p."product_category 
            WHERE 
              producer_id=". $producent_id." 
            ORDER BY name";
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      $categoriesAndProducts[ $row['id'] ] = array('name'=>$row['name'],'products'=>array());
    }

    $sql = "SELECT 
              p.id, 
              p.name,
              p.description,
              p.observation,
              p.home_page,
              p.usage_description,
              p.logo_url,
              p.category_id,
              p.publish,
              p.publish_request,
              prod.advertise_deal
            FROM 
              ".$this->p."products  as p,
              ".$this->p."producer as prod
            WHERE
              p.producer_id=".$producent_id ."
            AND
              p.producer_id = prod.id
            ORDER BY category_id, name";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $row = $this->dba->fetchArray( $result );
      if( $row['category_id'] && $categoriesAndProducts[$row['category_id']]) 
      {
        $categoriesAndProducts[$row['category_id']]['products'][] = $row;
      }
      else
      {
        $categoriesAndProducts[0]['products'][] = $row;
      }
    }

    return $categoriesAndProducts; 
  }
  function getProductsRequests()
  {
    $requests = array();
    $sql = "SELECT 
              p.id,
              p.name as product_name,
              p.publish_request,
              p.producer_id,
              pp.name as producer_name
            FROM
              ".$this->p."products as p,
              ".$this->p."producer as pp
            WHERE
              p.producer_id = pp.id
            AND
              p.publish_request IS NOT NULL";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ ) $requests[count($requests)] = $this->dba->fetchArray($result);

    return $requests;
  }
  function getPlacementRequests()
  {
    $requests = array();
    $sql = "SELECT 
            p.name as product_name,
            pp.name as producer_name,
            pp.id as producer_id,
            p2e.product_id, 
            p2e.element_id,
            p2e.branche_name, 
            p2e.node_id,
            p2e.publish_request, 
            bel.name as element_name
          FROM 
            ".$this->p."product2element AS p2e,
            ".$this->p."products AS p,
            ".$this->p."producer AS pp,
            ".$this->p."buildingelements AS bel
          WHERE 
            p2e.publish_request IS NOT NULL
          AND
            p2e.product_id = p.id
          AND 
           p.producer_id = pp.id
          AND
           bel.id = p2e.element_id";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ ) $requests[count($requests)] = $this->dba->fetchArray($result);

    return $requests;
  }
  function getProductsForElement($element_id,$branche_name)
  {
    $products = array();
    if(!$element_id || !$branche_name) return $products;
    $sql = "SELECT
              p.id as p_id,
              p.name as p_name,
              p.publish,
              p.publish_request,
              p2e.publish,
              p2e.publish_request
            FROM
              ". $this->p. "products as p,
              ".$this->p."product2element as p2e
            WHERE
              p.producer_id = ". $this->producentId ."
            AND
              p2e.product_id = p.id
            AND
              p2e.element_id = $element_id
            AND
              p2e.branche_name = '$branche_name'
              ";
              
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $products[ count( $products) ] = $this->dba->fetchArray( $result );
    }
    return $products;
  }
  function getProducts()
  {
    $sql = "SELECT
              id,
              name,
              kilde_url,
              logo_url,
              description,
              observation
            FROM
              ". $this->p. "product
            WHERE
              producent = ". $this->producentId;
              
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $this->produkter[ count( $this->produkter ) ] = $this->dba->fetchArray( $result );
    }
    return $this->produkter;
  }
  function toggle_publish( $publish_state )
  {
    if( $publish_state == 'y' ) $this->publish();
    else $this->unpublish();
  }
  function removeNew($id)
  {
    $this->dba->exec("DELETE FROM ".$this->p."adnews WHERE id=".$id);
  }
  function updateNew($id,$title,$body,$url)
  {
    $sql = "UPDATE ".$this->p."adnews 
            SET 
                title='". addslashes($title)."',
                body ='".addslashes($body)."',
                website='".addslashes($url)."'
            WHERE id=".$id;
    $this->dba->exec($sql);
  }
  function loadNew($id)
  {
    $sql = "SELECT * FROM ".$this->p."adnews WHERE id=".$id;
    return $this->dba->singleArray($sql);
  }
  function addNew($id)
  {
    if( !is_numeric( $id ) ) return;

    $sql = "INSERT INTO ". $this->p."adnews (producent,created) VALUES(".$id.",NOW())";
    $this->dba->exec( $sql );
    return $this->dba->last_inserted_id();
  }
  function loadProfiles()
  {
    $r = array();
    $sql = "SELECT
              *,(TO_DAYS(NOW()) - TO_DAYS(advertise_signup)) AS signup
            FROM
              ".$this->table;

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $r[$i] = $this->dba->fetchArray( $result );
      $r[$i]['description'] = stripslashes($r[$i]['description']);  
    }
    return $r;
  }
  function loadNews()
  {
    $r = array();
    $sql = "SELECT
              n.id,
              n.title,
              n.body,
              n.website,
              n.image,
              n.created,
              n.producent,
              p.name as producer
            FROM
              ".$this->p."adnews as n,
              ".$this->p."producer as p
            WHERE
              p.id = n.producent
            ORDER 
              BY created DESC";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $r[$i] = $this->dba->fetchArray( $result );
    }
    return $r;
  }
  function producerNews($id)
  {
    if( !is_numeric( $id ) ) return;
    $this->producentId = $id;
    $r = array();
    $sql = "SELECT
              *
            FROM
              ".$this->p."adnews
            WHERE
              producent = ". $this->producentId ." ORDER BY created DESC";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $r[$i] = $this->dba->fetchArray( $result );
    }
    return $r;
  }
  function loadProducer( $id )
  {
    if( !is_numeric( $id ) ) return;
    $this->producentId = $id;
    $sql = "SELECT
              *,(TO_DAYS(NOW()) - TO_DAYS(advertise_signup)) AS signup
            FROM
              ".$this->table ."
            WHERE
              id = ". $this->producentId;

        return $this->dba->singleArray($sql); 
  }
  function getTotal()
  {
    return $this->dba->singleQuery( "SELECT count(*) FROM ". $this->table );
  }
  function getProducenter( $offset = 0, $antal= 10, $sort_order='asc',$sort_column='name' )
  {
    $sql = "SELECT 
              *,(TO_DAYS(NOW()) - TO_DAYS(advertise_signup)) AS signup
            FROM
              ". $this->table;
    $sql.=  " ORDER BY $sort_column $sort_order "; 

    if($antal)  $sql.=  " LIMIT $offset , $antal "; 

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $this->producentList[ count( $this->producentList ) ] = $this->dba->fetchArray( $result );
    }
    return $this->producentList;
  }
  function addProducent()
  {
    $sql = "INSERT INTO ". $this->table ." ( name ) VALUES( 'Ny producent' )";
    $this->dba->exec( $sql );

    return $this->dba->last_inserted_id();
  }
  function removeProducent( $id )
  {
    $sql = "DELETE FROM ". $this->table ." WHERE id=". $id;
    $this->dba->exec( $sql );
  }
  function setProducent( $id )
  {
    $this->producentId = $id;
  }
  function setName( $name )
  {
    if( !trim( $name ) ) $name = 'Producent';
    $this->name = $name;
  }
  function setUserName($user_name)
  {
    $this->user_name = $user_name;
  }
  function setUserPassword($user_password)
  {
    $this->user_password = $user_password; 
  }
  function setHomepage( $home_page )
  {
    $this->home_page = $home_page; 
  }
  function setLogo_url($logo_url)
  {
    $this->logo_url = $logo_url;
  }
  function setAdresse($adresse)
  {
    $this->adresse = $adresse;
  }
  function setCVR($CVR)
  {
    $this->CVR = $CVR;
  }
  function setTelefon($telefon)
  {
    $this->telefon = $telefon;
  }
  function setFax($fax)
  {
    $this->fax = $fax;
  }
  function setAdminMail( $admin_mail)
  {
    $this->admin_mail = $admin_mail; 
  }
  function setMail( $mail )
  {
    $this->mail = $mail; 
  }
  function setPublish( $publish)
  {
    $this->publish = ($publish == 'y')?'y':'n'; 
  }
  function setDescription($description)
  {
    $this->description = $description;
  }
  function setObservation($observation)
  {
    $this->observation = $observation;
  }
  function getProducentIdForKilde($kilde_id)
  {
    $sql = "SELECT * FROM ".$this->table." WHERE kilde_id=$kilde_id";
    return $this->dba->singleArray($sql); 
  }
  function removeKildeForProducent($id)
  {
    if(!is_numeric($id)) return;
    $sql = "UPDATE ".$this->table." SET kilde_id = NULL WHERE id=$id";
    $this->dba->exec($sql);
  }
  function setKildeForProducent($kilde_id,$id)
  {
    if(!is_numeric($kilde_id)) return;
    if(!is_numeric($id)) return;
    $sql = "UPDATE ".$this->table." SET kilde_id=$kilde_id WHERE id=$id";
    $this->dba->exec($sql);
  }
  function updateProducent()
  {
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              name = '". addslashes($this->name) ."',
              user_name = '". addslashes($this->user_name) ."',
           ";

    if(trim($this->user_password) ) $sql.= "user_password= '". addslashes($this->user_password) ."',";
    if($this->updateDeal) $sql.="advertise_signup = NOW(), advertise_deal ='". addslashes($this->advertise_deal)."',";;

    $sql.="   description= '". addslashes($this->description) ."',
              observation = '". addslashes($this->observation) ."',
              home_page = '". addslashes($this->home_page) ."',
              logo_url = '". addslashes($this->logo_url) ."',
              adresse= '". addslashes($this->adresse) ."',
              CVR= '". addslashes($this->CVR) ."',
              telefon= '". $this->telefon ."',
              fax= '". $this->fax."',
              mail= '". addslashes($this->mail) ."',
              admin_mail= '". addslashes($this->admin_mail) ."',
              admin_name = '". addslashes($this->admin_name) ."',
              admin_telefon = '". addslashes($this->admin_telefon) ."',
              publish = '". $this->publish ."',
              telefon= '". $this->telefon ."'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
}
?>
