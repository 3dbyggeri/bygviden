<?php
class Agent
{
    var $dba;
    var $p;
    var $id;
    var $table;
    var $MOD_NUMBER;
    var $AUTONOMY_PATH;
    var $pub_id_str= '';
    var $number_of_results;
    var $aci;

    function Agent( $dba, $id=0)
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->table = $this->p.'agentstyring';
        $this->aci = new ACI();
    }
    function refreshCache($id=0,$antal_results=10,$weight=70)
    {
      if($id) $this->id = $id;
      $id = $this->aci->getPublicationsiDForAgent($this->id,$antal_results,$weight);
      $this->fillCache($id);
      return $id;
    }
    function loadProperties()
    {
      $sql = "SELECT
                name,
                description,
                start_text,
                threshold,
                results,
                autonomy
              FROM
                ". $this->table ."
              WHERE
                id=". $this->id;
      $props = $this->dba->singleArray( $sql );
      $this->number_of_results = $props['results'];
      return $props;
    }
    function setStartText( $start_text )
    {
      $sql = "UPDATE
                ". $this->table . "
              SET
                start_text = '". addslashes( trim( $start_text ) ) . "',
                autonomy = 'y'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
      $this->createAgent( $start_text );
    }
    function setAntalResults( $antal )
    {
      if( !is_numeric($antal) ) return;
      $sql = "UPDATE
                ". $this->table . "
              SET
                results = $antal
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function createAgent( $start_text = 'byggeri' )
    {
      $this->aci->deleteUser($this->id); 
      $this->aci->createAgent($this->id,$start_text);
    }
    function getResults($db='',$number=10,$relevance=10)
    {
      if( is_numeric( $this->number_of_results ) ) $number = $this->number_of_results;
      $results = $this->aci->getAgentResults($this->id,$this->number_of_results,$relevance);
      if(!$results) return;
      
      //serialize the array
      //$this->fillCache( $results );
      return $results;
    }
    function loadCache()
    {
      $sql = "SELECT 
                chache 
              FROM
                ". $this->table . "
              WHERE
                id = ". $this->id;
      return $this->dba->singleQuery( $sql );
    }
    function fillCache($doc_ids)
    {
      if($doc_ids) return;

      $sql = "UPDATE
                ". $this->table . "
              SET
                cache = '$doc_ids'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function train(  $docs )
    {
      if( !count( $docs ) ) return;
      $docs = implode('+',$docs);

      if($this->aci->trainAgent($this->id,$docs))
      {
        echo "<br><span style='font-size:10px;padding:10px'>Agent training succesfully on $docs</span><br>"; 
      }
      else
      {
        echo "<br><span style='font-size:10px;padding:10px'>Could not train agents on $docs</span><br>"; 
      }
    }
    function forgetTraining()
    {
      $props = $this->loadProperties();
      $this->createAgent( $props['start_text'] );
    }
    function nullstill()
    {
      $sql = "UPDATE
                ". $this->table . "
              SET
                autonomy = 'n'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
}
?>
