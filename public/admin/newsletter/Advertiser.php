<?php
class advertiser
{
    var $dba;
    var $p;
    var $id;
    var $table;
    var $status;

    function advertiser( $dba )
    {
      $this->dba = $dba;
      $this->p   = $this->dba->getPrefix();
      $this->table = $this->p.'advertiser';
      $this->status = array('T'=>'Pr&oslash;ve periode','A'=>'Aktiv','P'=>'Pasiv');
    }
    function add()
    {
      $sql = "INSERT INTO ". $this->table ." ( created ) VALUES (NOW())";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function update($id)
    {
        $company_name = addslashes($_POST['company_name']); 
        $company_address = addslashes($_POST['company_address']); 
        $company_postbox = addslashes($_POST['company_postbox']);
        $company_city = addslashes($_POST['company_city']); 
        $company_cvr = addslashes($_POST['company_cvr']); 
        $company_telefon = addslashes($_POST['company_telefon']); 
        $company_fax = addslashes($_POST['company_fax']);
        $company_email = addslashes($_POST['company_email']);
        $company_website = addslashes($_POST['company_website']);
        $company_description =addslashes($_POST['company_description']);
        $company_logo =addslashes($_POST['company_logo']);
        $contact_name = addslashes($_POST['contact_name']);
        $contact_email =addslashes($_POST['contact_email']);
        $contact_password =addslashes($_POST['contact_password']);
        $contact_telefon =addslashes($_POST['contact_telefon']);
        $contact_subscribe_to_newsletter = ($_POST['contact_subscribe_to_newsletter'])? 'y':'n'; 
        $status = addslashes($_POST['status']); 

        $sql = "UPDATE ".$this->table."
                SET 
                    company_name = '".$company_name ."',
                    company_address= '".$company_address."',
                    company_postbox= '".$company_postbox."',
                    company_city= '".$company_city."',
                    company_cvr = '".$company_cvr."',
                    company_telefon= '".$company_telefon."',
                    company_fax= '".$company_fax."',
                    company_email= '".$company_email."',
                    company_website= '".$company_website."',
                    company_description= '".$company_description."',
                    company_logo= '".$company_logo."',
                    contact_name= '".$contact_name."',
                    contact_email= '".$contact_email."',
                    contact_password= '".$contact_password."',
                    contact_telefon= '".$contact_telefon."',
                    contact_subscribe_to_newsletter= '".$contact_subscribe_to_newsletter."',
                    status= '".$status."'
                WHERE
                    id=".$id;
        $this->dba->exec($sql);
    }
    function load($id)
    {
        $sql = "SELECT *,DATEDIFF(created,NOW()) AS triald FROM ".$this->table." WHERE id=".$id;
        $r = $this->dba->singleArray($sql);
        $r['company_name'] = stripslashes($r['company_name']);
        $r['company_address'] = stripslashes($r['company_address']);
        $r['company_postbox'] = stripslashes($r['company_postbox']);
        $r['company_city'] = stripslashes($r['company_city']);
        $r['company_cvr'] = stripslashes($r['company_cvr']);
        $r['company_telefon'] = stripslashes($r['company_telefon']);
        $r['company_fax'] = stripslashes($r['company_fax']);
        $r['company_email'] = stripslashes($r['company_email']);
        $r['company_website'] = stripslashes($r['company_website']);
        $r['company_description'] = stripslashes($r['company_description']);
        $r['company_logo'] = stripslashes($r['company_logo']);
        $r['contact_name'] = stripslashes($r['contact_name']);
        $r['contact_email'] = stripslashes($r['contact_email']);
        $r['contact_password'] = stripslashes($r['contact_password']);
        $r['contact_telefon'] = stripslashes($r['contact_telefon']);
        $r['contact_subscribe_to_newsletter'] = stripslashes($r['contact_subscribe_to_newsletter']);
        $r['status'] = stripslashes($r['status']);
        return $r;
    }
    function remove($id)
    {
        $this->dba->exec("DELETE FROM ".$this->table." WHERE id=".$id);

    }
    function total()
    {
       $total = array();
       $sql ="SELECT status,COUNT(*) AS  nr FROM ".$this->table." GROUP BY status";
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r = $this->dba->fetchArray( $result );
          $total[$r['status']] = $r['nr'];
       }
       if(!array_key_exists('T',$total)) $total['T'] = 0;
       if(!array_key_exists('A',$total)) $total['A'] = 0;
       if(!array_key_exists('P',$total)) $total['P'] = 0;
       return $total;
    }
    function news($id,$offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id')
    {
        $r = array();
        $sql ="SELECT 
                    *
              FROM
                ". $this->p."adnews
              WHERE advertiser=$id
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r[$i] = $this->dba->fetchArray( $result );
       }
       return $r;
    }
    function products($id,$offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id')
    {
        $r = array();
        $sql ="SELECT 
                    *
              FROM
                ". $this->p."adproduct
              WHERE advertiser=$id
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r[$i] = $this->dba->fetchArray( $result );
       }
       return $r;
    }
    function all( $offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id')
    {
        $r = array();
        $sql ="SELECT 
                    *
                    ,DATEDIFF(created,NOW()) AS triald
              FROM
                ". $this->table ."
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r[$i] = $this->dba->fetchArray( $result );
       }
       return $r;
    }
    function find($email)
    {
      $r = array();
      $sql = "SELECT  
                *,DATEDIFF(created,NOW()) AS triald
              FROM ". $this->table ." 
              WHERE 
                company_name LIKE '%".$search."%' 
              OR
                company_email LIKE '%".$search."%' 
              OR
                contact_email LIKE '%".$search."%' 
              OR
                company_address LIKE '%".$search."%' 
              OR
                contact_name LIKE '%".$search."%' ";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
         $r[$i] = $this->dba->fetchArray( $result );
      }
      return $r;
    }
    function setActive( $id,$state)
    {
      $sql = "UPDATE 
                ". $this->table ."
              SET
                active = '". $state ."'
              WHERE
                id = ". $id; 
      $this->dba->exec( $sql );
    }


 }
?>
