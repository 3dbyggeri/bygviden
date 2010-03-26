<?php 
ini_set("include_path", ini_get("include_path") . ":../lib");
require_once 'HTTP/Request2.php';

Class BuildInRESTClient
{
  var $protocol;
  var $host;
  var $auth_string;
  var $api_key;
  var $products_url;
  var $companies_url;
  var $bv_product_groups_url;

  function buildinrestclient($host = BUILDIN_HOST, $user = BUILDIN_USER, $password = BUILDIN_PASS, $api_key = BUILDIN_API_KEY){
    $this->protocol = 'http://';
    $this->host = $host;
    if($user) $this->auth_string = $user .':' . $password . '@';
    $this->api_key = $api_key;
    $this->products_url = $this->protocol . $this->auth_string . $this->host . "/products.xml?api_key=" . $this->api_key;
    $this->companies_url = $this->protocol . $this->auth_string . $this->host . "/companies.xml?api_key=" . $this->api_key;
    $this->bv_product_groups_url = $this->protocol . $this->auth_string . $this->host . "/bv_product_groups.xml?api_key=" . $this->api_key;
  }
  function loadProducts($letter){
    try {
      if($letter==''){ $letter = "A";}
      $request = new HTTP_Request2($this->products_url."&l=".$letter);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      
      $xml = new SimpleXMLElement($response);
      $result = array();
      foreach ($xml->product as $product) {
        $logo_url = ''; // flush variabel from last iteration
        if($product->{"product-image-list-url"}!='') $logo_url = $this->protocol . $this->auth_string . $this->host . $product->{"product-image-list-url"};
        array_push($result, array(
          'id' => $product->id.'', // make sure it's a string
          'name' => utf8_decode($product->name.''), // make sure it's a string
          'description' => utf8_decode($product->{"short-description"}.''), // make sure it's a string
          'home_page' => $_SERVER['REQUEST_URI'] . "&buildin=/products/" . $product->id,
          'logo_url' => $logo_url,
          'producer_name' => utf8_decode($product->{"owner-name"}.''), // make sure it's a string
          'producer_id' => $product->{"owner-id"}.'' // make sure it's a string
          ));
      }
      return $result;
      
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function loadProducenter($letter){
    try {
      if($letter==''){ $letter = "A";}
      $request = new HTTP_Request2($this->companies_url."&l=".$letter);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      
      $xml = new SimpleXMLElement($response);
      $result = array();
      foreach ($xml->organization as $organization) {
        $logo_url = ''; // flush variabel from last iteration
        if($organization->{"logo-list-url"}!='') $logo_url = $this->protocol . $this->auth_string . $this->host . $organization->{"logo-list-url"};
        array_push($result, array(
          'id' => $organization->id.'', // make sure it's a string
          'name' => utf8_decode($organization->name.''), // make sure it's a string
          'logo_url' => $logo_url,
          'antal_produkter' => $organization->{"product-count"}.'' // make sure it's a string
          ));
      }
      return $result;
      
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function loadProducerByProductId($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/products/" . $id . ".xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      
      $xml = new SimpleXMLElement($response);
      return $this->loadProducer($xml->{"owner-id"}.''); // make sure it's a string
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function loadProducer($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/companies/" . $id . ".xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      
      $xml = new SimpleXMLElement($response);
      if($xml->{"logo-list-url"}!='') $logo_url = $this->protocol . $this->auth_string . $this->host . $xml->{"logo-list-url"};
      $result = array(
        'id' => $xml->id.'', // make sure it's a string
        'name' => utf8_decode($xml->name.''), // make sure it's a string
        'description' => utf8_decode($xml->description.''), // make sure it's a string
        'adresse' => utf8_decode($xml->{"street-address"}.' '.$xml->{"extended-address"}.', '.$xml->{"postal-code"}.' '.$xml->{"city"}), // make sure it's a string
        'telefon' => utf8_decode($xml->tel.''), // make sure it's a string
        'mail' => utf8_decode($xml->{"primary-email"}.''), // make sure it's a string
        'home_page' => utf8_decode($xml->{"primary-url"}.''), // make sure it's a string
        'fax' => utf8_decode($xml->fax.''), // make sure it's a string
        'CVR' => utf8_decode($xml->{"vat-number"}.''), // make sure it's a string
        'logo_url' => $logo_url
        );
      return $result;
      
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function loadCategoriesAndProducts($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/companies/" . $id . "/products.xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      $xml = new SimpleXMLElement($response);

      $detected_groups = array();
      $result = array( 0=>array('name'=>'root','products'=>array() ) );

      foreach ($xml->product as $product) {
        $logo_url = ''; // flush variabel from last iteration
        if($product->{"product-image-list-url"}!='') $logo_url = $this->protocol . $this->auth_string . $this->host . $product->{"product-image-list-url"};
        $group_name = utf8_decode($product->{"product-group-name"}.'');
        if($group_name=='') $group_name = 'root';
        $product = array(
          'id' => $product->id.'', // make sure it's a string
          'name' => utf8_decode($product->name.''), // make sure it's a string
          'description' => utf8_decode($product->{"short-description"}.''), // make sure it's a string
          'home_page' => $_SERVER['HOST'] . "/index.php?action=products&section=produkter&buildin=/products/" . $product->id,
          'logo_url' => $logo_url
          );
        if($detected_groups[$group_name] !== null){
          array_push($result[$detected_groups[$group_name]]['products'],$product);
        } else {
          array_push($result, array('name' => $group_name, 'products' => array($product)));
          $detected_groups[$group_name] = count($result)-1;
        }
      }
      // echo show_php($_SERVER);die();
      return $result;

    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function loadVaregrupper($id){
    try {
      $request = new HTTP_Request2($this->bv_product_groups_url);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
      
      $xml = new SimpleXMLElement($response);
      $result = array();
      foreach ($xml->{"bv-product-group"} as $group) {
        array_push($result, array(
          'id' => $group->label.'', // make sure it's a string
          'name' => utf8_decode($group->description.''), // make sure it's a string
          'antal_produkter' => $group->{"product-count"}.'' // make sure it's a string
          ));
      }
      return $result;
      
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function isVareGroupALeaf($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/bv_product_groups/" . $id . ".xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
    
      $xml = new SimpleXMLElement($response);
      return $xml->{"is-leaf"}.''=='true' ? true : false; // make sure it's a string
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function getVaregruppeName($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/bv_product_groups/" . $id . ".xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
    
      $xml = new SimpleXMLElement($response);
      return utf8_decode($xml->description.''); // make sure it's a string
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  function getProductsForVaregruppeGroupByProducer($id){
    try {
      $request = new HTTP_Request2($this->protocol . $this->auth_string . $this->host . "/bv_product_groups/" . $id . "/products.xml?api_key=" . $this->api_key);
      $request -> setHeader('Accept: text/xml');
      $response = $request->send()->getBody();
    
      $xml = new SimpleXMLElement($response);
      $detected_orgs = array();
      $result = array();

      foreach ($xml->product as $product) {
        $logo_url = ''; // flush variabel from last iteration
        if($product->{"product-image-list-url"}!='') $logo_url = $this->protocol . $this->auth_string . $this->host . $product->{"product-image-list-url"};
        $org_name = utf8_decode($product->{"owner-name"}.'');
        $product = array(
          'id' => $product->id.'', // make sure it's a string
          'name' => utf8_decode($product->name.''), // make sure it's a string
          'description' => utf8_decode($product->{"short-description"}.''), // make sure it's a string
          'home_page' => $_SERVER['HOST'] . "/index.php?action=products&section=produkter&buildin=/products/" . $product->id,
          'logo_url' => $logo_url
          );
        if($detected_orgs[$org_name] !== null){
          array_push($result[$detected_orgs[$org_name]]['products'],$product);
        } else {
          array_push($result, array('producer_name' => $org_name, 'products' => array($product)));
          $detected_orgs[$org_name] = count($result)-1;
        }
      }
      return $result;
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
  }
}
?>