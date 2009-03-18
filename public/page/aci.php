<?
class ACI
{
  var $aci_server;
  var $aci_port;
  var $nore_port;
  var $debug;

  function ACI($debug=false)
  {
    $this->aci_server = 'http://bygviden2.facility.dir.dk';
    $this->aci_port = '9000';
    $this->nore_port = '10000';
    $this->debug = $debug;
  }
  function addUser($id)
  {
    $parameters = array( 'UserName'=>$this->userName($id),
                         'Password'=>'none');

    $url = $this->buildCommand('UserAdd',$parameters,$false);
    $response = $this->request($url);

    //check if the response was a succes
    if($this->getTag($response,'response') != 'SUCCESS') return; 

    return $this->userName($id);
  }
  function deleteUser($id)
  {
    $parameters = array( 'UserName'=>$this->userName($id) );
    $url = $this->buildCommand('UserDelete',$parameters,$false);
    $response = $this->request($url);
    if($this->getTag($response,'response') == 'SUCCESS') return 'User deleted'; 
  }
  function addAgentForUser($userName,$id,$training)
  {
    $parameters = array( 'UserName'=>$userName,
                         'AgentName'=>$this->agentName($id),
                         'Training'=>$training);

    $url = $this->buildCommand('AgentAdd',$parameters,$false);
    $response = $this->request($url);

    //check if the response was a succes
    if($this->getTag($response,'response') != 'SUCCESS') return; 

    return $this->agentName($id);
  }
  function createAgent($id,$training)
  {
    $userName = $this->addUser($id); 
    if(!$userName) return 'Failed to create user';
    
    $agentName = $this->addAgentForUser($userName,$id,$training);
    if(!$agentName) return 'Failed to create agent';
  }
  function getAgentResults($id,$number=10,$relevance=20)
  {
    $parameters = array('UserName'=>$this->userName($id),
                        'AgentName'=>$this->agentName($id),
                        'DREMaxResults'=>$number,
                        'DREMinScore'=>$relevance
                       );
                        
    $url = $this->buildCommand('AgentGetResults',$parameters,$false);
    $response = $this->request($url);

    if($this->getTag($response,'response') != 'SUCCESS') return; 

    return $response;
  }
  function userName($id)
  {
    return 'user_'.$id;
  }
  function agentName($id)
  {
    return 'agent_'.$id;
  }
  function trainAgent($id,$docs)
  {
    $parameters = array('UserName'=>$this->userName($id),
                        'AgentName'=>$this->agentName($id),
                        'PositiveDocs'=>$docs);

    $url = $this->buildCommand('AgentRetrain',$parameters,$false);
    $response = $this->request($url);
    return ($this->getTag($response,'response') == 'SUCCESS');
  }
  function buildCommand($command,$parameters,$suir=true)
  {
    $port = ($suir)? $this->aci_port:$this->nore_port;

    $str = $this->aci_server.':'.$port;
    $str.= '/action='.$command;
    
    $params = '';
    foreach($parameters as $key=>$value)
    {
      $params.= '&'. $key .'='. $value;
    }
    $command = $str.$params;
    if($this->debug) echo ("$command <br>");
    return $command;
  }
  function request($url)
  {
    $contents = '';
    $fp= fopen($url,'rb');

    if(!$fp) return $contents;
    while(!feof($fp)) $contents .= fread($fp,1024);

    fclose($fp);
    return $contents;
  }
  function query($params)
  {
    if(!$params) return;
    $url = $this->buildCommand('query',$params);
    echo '<!--aci url:'.$url.'-->';
    return $this->request($url); 
  }
  function hitsfordoc($pub_id)
  {
    $url = $this->aci_server.':9000';
    $url.= '/action=query&FieldText='.$pub_id.':*/PUB_ID';
    return $this->request($url); 
  }

  function removeFromIndex($pub_id,$doc_id_string)
  {
    $url = $this->aci_server.':9000';
    $url.= '/action=query&FieldText='.$pub_id.':*/PUB_ID&MatchID='.$doc_id_string.'&Delete=True';
    return $this->request($url); 
  }
  function getHits($str)
  {
    $hits = array();

    //check if the response was a succes
    if($this->getTag($str,'response') != 'SUCCESS') return $hits; 

    $needle = '<autn:hit>';
    $needle2 = '</autn:hit>';
    $startTag = 0;
    $startTagLenght = strlen($needle);

    while(true)
    {
      $startTag = strpos($str,$needle,$startTag + 1);
      if($startTag === false) break;

      $endTag = strpos($str,'</autn:hit>',$startTag);
      if($endTag === false) break;
      
      $a = ($startTag + $startTagLenght);
      $b = $endTag - $a;
      $hit =  substr($str,$a,$b);
      $hit = $this->getHit($hit);
      array_push($hits,$hit);

      if($lp++>1000) break; //dont get cought on a loop
    }

    return $hits;
  }
  function getPublicationIDString($hits)
  {
    $id = array();
    for($i=0;$i <count($hits);$i++) 
    {
      if(!is_numeric($hits[$i]['pub_id'])) continue;
      array_push($id,$hits[$i]['pub_id']);
    }
    return implode($id,','); 
  }
  function getPublicationsIDForAgent($agentID,$number,$relevance)
  {
    $results = $this->getAgentResults($agentID,$number,$relevance);
    $hits = $this->getHits($results);
    return $this->getPublicationIDString($hits);
  }
  function getHitReference($hit)
  {
    return $this->getTag($hit,'autn:reference');
  }
  function getHitTitle($hit)
  {
    return $this->getTag($hit,'autn:title');
  }
  function getSummary($hit)
  {
    $summary = $this->getTag($hit,'autn:summary');
    return html_entity_decode($summary);
  }
  function getBygSummary($hit)
  {
    $summary = $this->getTag($hit,'C_SUMMARY');
    return html_entity_decode($summary);
  }
  function getWeight($hit)
  {
    return $this->getTag($hit,'autn:weight');
  }
  function getHit($str)
  {
    return array(
                  'reference'=> $this->getHitReference($str),
                  'autonomy_id'=>$this->getTag($str,'autn:id'),
                  'pub_id'=>$this->getTag($str,'PUB_ID'),
                  'title'=> $this->getHitTitle($str),

                  'byg_summary'=> $this->getBygSummary($str),
                  'summary'=> $this->getSummary($str),
                  'weight'=> $this->getWeight($str),
                );
  }

  function getTag($str,$tag)
  {
    //returns the text between the requested tag 
    $tagStart = '<'.$tag.'>';
    $tagEnd = '</'.$tag.'>';

    $a = strpos($str,$tagStart,0);
    $b = strpos($str,$tagEnd,1);

    if($a === false || $b === false) return '';

    $c = $a + strlen($tagStart);
    $text = substr($str,$c,($b - $c));
    return mb_convert_encoding($text,"ISO-8859-1");
  }
}
?>
