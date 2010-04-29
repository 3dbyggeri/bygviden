<?php
class bruger 
{
    var $dba;
    var $p;
    var $id;
    var $table;

    function bruger( $dba )
    {
      $this->dba = $dba;
      $this->p   = $this->dba->getPrefix();
      $this->table = $this->p.'bruger';
    }
    function updatePassword($uid,$password)
    {
        $sql = "UPDATE ".$this->table." SET password='". addslashes($password)."' WHERE id=".$uid;
        $this->dba->exec( $sql );

        $sql = "SELECT
                    id,
                    bruger_navn,
                    firma,
                    navn,
                    titel,
                    parent,
                    restricted_shop,
                    clipkort_amount,
                    organization
                  FROM
                    ". $this->table ."
                  WHERE
                    id =".$uid; 
          $record = $this->dba->singleArray( $sql );
          $_SESSION['bruger_id'] = $record['id'];
          $long_name = $record['firma'] .' '. $record['navn'] .' '. $record['titel'];
          $_SESSION['bruger_navn'] = ( trim( $long_name ) )? $long_name :$name;
          $_SESSION['is_mester'] = ( $record['parent'] )? 0:1; 
          $_SESSION['parent'] = $record['parent'];
          $_SESSION['clipkort'] = ( $record['restricted_shop'] != 'n' )? $record['clipkort_amount']:'nan';
          $_SESSION['producent'] = 0;
          $_SESSION['organization'] = $record['organization'];
    }
    function logIn( $name, $password )
    {
      if( !trim( $name ) ) return;
      if( !trim( $password ) ) return;

      $sql = "SELECT
                id,
                bruger_navn,
                firma,
                navn,
                titel,
                parent,
                restricted_shop,
                clipkort_amount,
                organization
              FROM
                ". $this->table ."
              WHERE
                LOWER(bruger_navn) = LOWER('". addslashes( $name ) ."')
              AND
                LOWER(password) = LOWER('". addslashes( $password ) ."') ";
      $record = $this->dba->singleArray( $sql );
      $_SESSION['bruger_id'] = $record['id'];
      $long_name = $record['firma'] .' '. $record['navn'] .' '. $record['titel'];
      $_SESSION['bruger_navn'] = ( trim( $long_name ) )? $long_name :$name;
      $_SESSION['is_mester'] = ( $record['parent'] )? 0:1; 
      $_SESSION['parent'] = $record['parent'];
      $_SESSION['clipkort'] = ( $record['restricted_shop'] != 'n' )? $record['clipkort_amount']:'nan';
      $_SESSION['producent'] = 0;
      $_SESSION['organization'] = $record['organization'];
        
      if($record) 
      {
        if($_SESSION['organization']=='ARK' && $password == 'DANSKEARK')
        {
            $_SESSION['update_password'] = $_SESSION['bruger_id'];
            $_SESSION['bruger_id'] = '';
            $_SESSION['bruger_navn'] = '';
            $_SESSION['is_mester'] = '';
            $_SESSION['parent'] = '';
            $_SESSION['clipkort'] = '';
            $_SESSION['producent'] = '';
        }
        return;
      }

      //Check if this is a producer
      $sql = "SELECT
                id,
                name,
                user_name
              FROM
                ".$this->p."producer
              WHERE
                user_name = '". addslashes( $name ) ."'
              AND
                user_password = '". addslashes( $password ) ."' ";
                
      $record = $this->dba->singleArray( $sql );
      $_SESSION['bruger_id'] = $record['id'];
      $long_name = $record['name'];
      $_SESSION['bruger_navn'] = ( trim( $long_name ) )? $long_name :$name;
      $_SESSION['is_mester'] =  0; 
      $_SESSION['parent'] = 0;
      $_SESSION['clipkort'] = 0;
      $_SESSION['producent'] = 1;

    }

    function getSvendeForbrug( $faktura = 0 )
    {
      $sql =" SELECT 
                 b.id,
                 b.bruger_navn,
                 b.navn,
                 u.publication_id,
                 u.url,
                 u.title,
                 u.pris,
                 u.abonament_periode,
                 u.readed
              FROM
                ". $this->p."usage AS u 
                ,". $this->table ." AS b
              WHERE
                u.bruger_id = b.id
              AND
                b.parent =".$this->id ."
              AND 
                u.archived ";

      $sql.= ( $faktura )?'IS NOT NULL ':'IS NULL ';
      $sql.= "ORDER BY 
                b.bruger_navn";



      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $forbrug = Array();
      for( $i = 0; $i < $n; $i++ )
      {
        $forbrug[$i] = $this->dba->fetchArray( $result );
      }
      return $forbrug;
    }
    function getPublikationer()
    {
      $sql =" SELECT 
                 publication_id
              FROM
                ". $this->p."usage
              WHERE
                bruger_id=".$this->id;
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $publikationer = Array();
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $publikationer[$i] = $rec['publication_id'];
      }
      return $publikationer;
    }
    function getForbrug( $faktura = 0 )
    {
        $sql ="SELECT 
                 p.kilde_url AS pub_url,
                 p.name      AS pub_title,

                 p2.id    AS kategory_id,
                 p2.name  AS kategory_name,

                 p3.id    AS kategory_id,
                 p3.name  AS leverandor_name,

                 u.publication_id,
                 u.url,
                 u.title,
                 u.pris,
                 u.abonament_periode,
                 u.readed
              FROM
                (". $this->p."usage as u,

                ". $this->p."kildestyring as p2,
                ". $this->p."kildestyring as p3)

              LEFT JOIN
                ". $this->p."kildestyring as p
              ON
                p.id = u.publication_id
              WHERE
                bruger_id=".$this->id ."
              AND
                p.parent = p2.id
              AND
                p2.parent = p3.id 
              AND 
                u.archived ";

      $sql.= ( $faktura )?'IS NOT NULL ':'IS NULL';
      
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $forbrug = Array();
      for( $i = 0; $i < $n; $i++ )
      {
        $forbrug[$i] = $this->dba->fetchArray( $result );
      }
      return $forbrug;
    }
    function debit( $pris )
    {
      $new_saldo = $_SESSION['clipkort'] - $pris; 
      $_SESSION['clipkort'] = $new_saldo;
      $sql = "UPDATE
                ". $this->table ."
              SET
                clipkort_amount = ".  $new_saldo ."
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function updateSvend( 
      $svendId, 
      $bruger_navn, 
      $firma, 
      $navn, 
      $titel, 
      $email,
      $pass, 
      $restricted_shop,
      $klipkort_extra
      )
    {
      if( !is_numeric( $svendId ) ) return;
      $bruger_navn = ( trim( $bruger_navn ) )? $bruger_navn:'bruger';
      $password = ",password = '$pass' ";

      $sql = "UPDATE
                ". $this->table ."
              SET
                bruger_navn ='". trim( $bruger_navn ) ."'
                ,firma ='". trim( $firma ) ."'
                ,navn ='". trim( $navn ) ."'
                ,titel ='". trim( $titel ) ."'
                ,email      ='". trim( $email ) ."'
                ,restricted_shop = '$restricted_shop'
                ";
      if( is_numeric( $klipkort_extra ) ) $sql.= ",clipkort_amount = $klipkort_extra ";
      $sql.= " $password
              WHERE
                parent =". $this->id ."
              AND
                id=". $svendId;
      $this->dba->exec( $sql );
    }
    function loadSvend( $svendId )
    {
      if( !is_numeric( $svendId ) ) return;
      $sql = "SELECT
                bruger_navn,
                firma,
                navn,
                titel,
                active,
                email,
                password,
                restricted_shop,
                clipkort_amount
              FROM
                ". $this->table ."
              WHERE
                parent = ". $this->id ."
              AND
                id=". $svendId;
        return $this->dba->singleArray( $sql );
    }
    function deleteSvend( $svendId )
    {
      if( !is_numeric( $svendId ) ) return;
      $sql = "DELETE FROM
              ". $this->table ."
              WHERE
                parent = ". $this->id ."
              AND
                id = ". $svendId;
      $this->dba->exec( $sql );
    }
    function addSvend()
    {
      $sql = "INSERT INTO 
                ". $this->table ."
              ( 
                bruger_navn,
                parent
              )
              VALUES
              (
                'Ny bruger',
                ". $this->id ."
              )";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function getSvende( )
    {
      $sql = "SELECT
                id,
                bruger_navn,
                navn,
                restricted_shop,
                clipkort_amount
              FROM
                ". $this->table ."
              WHERE
                parent =". $this->id ."
              ORDER BY
                navn";

       $svende = Array();         
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $svende[$i] = $this->dba->fetchArray( $result );
       }

       return $svende;
    }
    function findBrugerne($search)
    {
      $sql = "SELECT
                id,
                bruger_navn,
                medlemsnr,
                active,
                gratist,
                parent,
                firma,
                navn,
                titel
              FROM
                ". $this->table ."
              WHERE
                bruger_navn LIKE '%".$search."%'
              OR
                firma LIKE '%".$search."%'
              OR
                navn LIKE '%".$search."%'
              OR 
                titel LIKE '%".$search."%'";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $brugerne[$i] = $this->dba->fetchArray( $result );
       }
       return $brugerne;
    }
    function getBrugerne( $offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id' )
    {
      $sql = "SELECT
                id,
                bruger_navn,
                medlemsnr,
                active,
                gratist,
                parent,
                temaeditor,
                firma,
                navn,
                titel
              FROM
                ". $this->table ."
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $brugerne[$i] = $this->dba->fetchArray( $result );
       }
       return $brugerne;
    }
    function addBruger()
    {
      $max = $this->dba->singleQuery("SELECT MAX(id) FROM ". $this->table);
      $max++;

      $sql = "INSERT INTO ". $this->table ." ( bruger_navn ) VALUES ( 'bruger_". $max ."' )";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function setId( $id )
    {
      $this->id = $id;
    }
    function getAntalUsers()
    {
      $sql = "SELECT count(*) FROm ".$this->table." WHERE parent=".$this->id;
      return $this->dba->singleQuery($sql);
    }
    function loadBruger( )
    {
       $sql = "SELECT
                  *
              FROM
                ". $this->table ."
              WHERE
                id=". $this->id;
        return $this->dba->singleArray( $sql );
    }
    function getTotal()
    {
      return $this->dba->singleQuery("SELECT COUNT(*) FROM ". $this->table );
    }
    function deleteBruger( $id )
    {
      $this->dba->exec("DELETE FROM ". $this->table ." WHERE id = $id" );
    }
    function setBrugerNavn( $bruger_navn )
    {
      if( !$bruger_navn ) $bruger_navn = 'unnamed';
      $sql = "UPDATE 

                ". $this->table ."
              SET
                bruger_navn = '". addslashes( $bruger_navn ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setMedlemsNr( $medlemsnr )
    {
      if( !is_numeric( $medlemsnr ) ) return;
      $sql = "UPDATE 
                ". $this->table ."
              SET
                medlemsnr = '". addslashes( $medlemsnr ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setTemaEditor( $state )
    {
      $state = ( $state )?'y':'n';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                temaeditor = '". $state ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setGratist( $state )
    {
      $state = ( $state )?'y':'n';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                gratist = '". $state ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setActive( $state )
    {
      $state = ( $state )?'n':'y';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                active = '". $state ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setFirma( $firma )
    {
      if( !$firma ) $firma = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                firma = '". addslashes( $firma ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setNavn( $navn )
    {
      if( !$navn ) $navn = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                navn = '". addslashes( $navn ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setTitel( $titel )
    {
      if( !$titel ) $titel = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                titel = '". addslashes( $titel ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setGade( $gade )
    {
      if( !$gade ) $gade = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                gade = '". addslashes( $gade ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setSted( $sted )
    {
      if( !$sted ) $sted = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                sted = '". addslashes( $sted ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setPostnr( $postnr )
    {
      if( !$postnr ) $postnr = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                postnr = '". addslashes( $postnr ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setCity( $city )
    {
      if( !$city ) $city = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                city = '". addslashes( $city ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setOrganization( $org)
    {
      if( !$org ) $org = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                organization = '". addslashes( $org) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setLand( $land )
    {
      if( !$land ) $land = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                land = '". addslashes( $land ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setTlf( $tlf )
    {
      if( !$tlf ) $tlf = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                tlf = '". addslashes( $tlf ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setEmail( $email )
    {
      if( !$email ) $email = '';
      $sql = "UPDATE 
                ". $this->table ."
              SET
                email = '". addslashes( $email ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function setPassword( $password )
    {
      $sql = "UPDATE 
                ". $this->table ."
              SET
                password = '". addslashes( $password ) ."'
              WHERE
                id = ". $this->id; 
      $this->dba->exec( $sql );
    }
    function purchase( $publication_id,
                       $url,
                       $title,
                       $pris,
                       $periode )
    {
        $bruger_id = ($this->id == '') ? "NULL" : $this->id;
        $sql = "INSERT INTO 
                  ". $this->p."usage 
                (
                  bruger_id,
                  publication_id,
                  url,
                  title,
                  pris,
                  abonament_periode,
                  readed
                )
                VALUES
                (
                   ". $bruger_id ."
                  ,". $publication_id ."
                  ,'".$url."'
                  ,'".$title."'
                  ," .$pris ."
                  ,". $periode ."
                  ,NOW()
                )";
        $this->dba->exec( $sql );
    }
    function hasPaidBrugerRabat($kilde,$url)
    {
      //find the top user
      $uid = ($_SESSION['is_mester'])? $this->id:$_SESSION['parent'];
      if($kilde->samlet_publication=='y') 
      {
          //check if any document under the category has been bought
          $sql ="SELECT 
                      us.publication_id
                    FROM
                      ". $this->p."usage AS us,
                      ". $this->p."kildestyring AS k
                    WHERE
                      k.id = us.publication_id
                    AND 
                      k.parent =". $kilde->parent_id ."
                    AND
                      us.bruger_id=". $uid."
                    AND
                      TO_DAYS(NOW()) - TO_DAYS(us.readed) < ". ( 30 * 12 ) ." ";
      }
      else
      {
          //check if the document has been bought
          $sql ="SELECT 
                      us.publication_id
                    FROM
                      ". $this->p."usage AS us
                    WHERE
                      us.publication_id = ".$kilde->id ."
                    AND
                      us.bruger_id=". $uid."
                    AND
                      TO_DAYS(NOW()) - TO_DAYS(us.readed) < ". ( 30 * 12 ) ." ";
      }
      
      return ($this->dba->singleQuery( $sql ))? true:false;
    }
    function hasPaid( $kilde, $url )
    {
      //check if this implements bruger_rabat
      if($kilde->hasBrugerRabat()) 
      {
        return $this->hasPaidBrugerRabat($kilde,$url);
      }

      //check if the publication overrule the payment definition 
      if( $kilde->overrule_betaling == 'n' )
      {
        //check if it's parent category har definere et payment method 
        if( $kilde->cat_overrule_betaling == 'y' )
        {
          //echo "checking category<br>";
          //check if there are ny childrens of this category buyed with an abonament
          $sql ="SELECT 
                  us.publication_id
                FROM
                  ". $this->p."usage AS us,
                  ". $this->p."kildestyring AS ks
                WHERE
                  us.publication_id = ks.id
                AND
                  ks.parent = ". $kilde->parent_id ."
                AND
                  us.bruger_id=". $this->id ."
                AND
                  TO_DAYS(NOW()) - TO_DAYS(us.readed) < ". ( 30 * $kilde->cat_abonament_periode ) ." ";
            return ($this->dba->singleQuery( $sql ))? true:false;
        }
        
        //check viden leverendar define a payment method
        if( $kilde->lev_overrule_betaling == 'y' )
        {
          //check if there are descendants of this leverandor buyed with an abonament
          //echo "checking leverandor<br>";
          //echo $kilde->lev_abonament_periode."<br>";
          $sql ="SELECT 
                  us.publication_id
                FROM
                  ". $this->p."usage AS us,
                  ". $this->p."kildestyring AS ks,
                  ". $this->p."kildestyring AS ks2
                WHERE
                  us.publication_id = ks.id
                AND
                  ks.parent = ks2.id
                AND
                  ks2.parent = ". $kilde->lev_id ."
                AND
                  us.bruger_id=". $this->id ."
                AND
                  TO_DAYS(NOW()) - TO_DAYS(us.readed) < ". ( 30 * $kilde->lev_abonament_periode ) ." ";
            $res = $this->dba->singleQuery( $sql );
            //echo "res $res<br>";
            //echo "<xmp>$sql</xmp>";
            //die('so lang so godt');
            return ($res)? true:false;
            //return ($this->dba->singleQuery( $sql ))? true:false;
        }
        return false;
      }
      //check if the user is in the usage list
      //check if the publication url is there
      //check if its within the same day ( 24 hour )
      if( $kilde->enkelt_betaling =='y' )
      {
        $sql = "SELECT bruger_id FROM
               ". $this->p."usage
               WHERE
                bruger_id=". $this->id ."
               AND
                url = '". $url ."'
               AND
                TO_DAYS(NOW()) - TO_DAYS(readed) < 1 ";
        $enkelt = $this->dba->singleQuery( $sql );
        if( $enkelt ) 
        {
          return true;
        }
      }
      if( $kilde->abonament_betaling == 'y' )
      {
        //check if user is within the abonament_period
        $sql = "SELECT bruger_id FROM
               ". $this->p."usage
               WHERE
                bruger_id=". $this->id ."
               AND
                url = '". $url ."'
               AND
                TO_DAYS(NOW()) - TO_DAYS(readed) < ". ( 30 * $kilde->abonament_periode ) ." ";
        $abn = $this->dba->singleQuery( $sql );
        if( $abn ) 
        {
          return true;
        }
      }
      return false;
    }
    function levUsageFromLastFaktura( )
    {
      $usage = array();

      $sql = "SELECT
                    vidensleverandor.name AS videns_navn,
                    SUM( forbrug.pris ) AS pris,
                    COUNT(*)            AS number
                FROM
                     ".$this->p."usage AS forbrug,
                     ".$this->p."kildestyring AS publikation,
                     ".$this->p."kildestyring AS category,
                     ".$this->p."kildestyring AS vidensleverandor,
                     ".$this->table." AS bruger

                WHERE
                    forbrug.publication_id = publikation.id
                AND
                    publikation.parent = category.id
                AND
                    category.parent = vidensleverandor.id
                AND 
                    forbrug.archived IS NULL
                AND
                    bruger.id = forbrug.bruger_id 
                AND
                    bruger.gratist <> 'y'
                GROUP BY
                    vidensleverandor.id
                ORDER BY
                    vidensleverandor.id";
       $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $usage[$i] = $this->dba->fetchArray( $result );
      }
      return $usage;
    }
    function levUsageFromDayMonthYearToDayMonthYear( $day1,$month1,$year1,$day2,$month2,$year2 )
    {
      $usage = array();
      $from = $year1 .'-'. $month1 .'-'. $day1 .' 23:59:59';
      $to   = $year2 .'-'. $month2 .'-'. $day2 .' 23:59:59';

      $sql = "SELECT
                    vidensleverandor.name AS videns_navn,
                    SUM( forbrug.pris ) AS pris,
                    COUNT(*)            AS number
                FROM
                     ".$this->p."usage AS forbrug,
                     ".$this->p."kildestyring AS publikation,
                     ".$this->p."kildestyring AS category,
                     ".$this->p."kildestyring AS vidensleverandor,
                     ".$this->table." AS bruger

                WHERE
                    forbrug.publication_id = publikation.id
                AND
                    publikation.parent = category.id
                AND
                    category.parent = vidensleverandor.id
                AND 
                    forbrug.readed >= '$from'
                AND
                    forbrug.readed <= '$to'
                AND
                    bruger.id = forbrug.bruger_id 
                AND
                    bruger.gratist <> 'y'
                GROUP BY
                    vidensleverandor.id
                ORDER BY
                    vidensleverandor.id";
       $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $usage[$i] = $this->dba->fetchArray( $result );
      }
      return $usage;
    }
    function fakturereForDayMonthYear( $day2,$month2,$year2 )
    {
      //find the last billing date
      $sql = "SELECT MAX(archived) FROM ". $this->p ."usage";
      $last_billing_date = $this->dba->singleQuery( $sql );

      $to   = $year2 .'-'. $month2 .'-'. $day2 .' 23:59:59';
      $sql = "UPDATE ". $this->p ."usage
              SET 
                archived='$to'
              WHERE
                readed <= '$to' ";
      if( $last_billing_date )
      {
        $sql.="AND
                readed >'$last_billing_date'";
      }

      $this->dba->exec( $sql );
    }
    function usageFromLastFaktura()
    {
      $usage = array();

      $sql = "SELECT
                    bruger.id           AS user_id,
                    bruger.firma   AS name1,
                    bruger.navn   AS name2,
                    bruger.titel   AS name3,
                    bruger.medlemsnr    AS medlemesnr,
                    mester.id           AS mester_id,
                    mester.firma   AS mester_name1,
                    mester.navn   AS mester_name2,
                    mester.titel   AS mester_name3,
                    mester.medlemsnr    AS mester_medlemsnr,
                    SUM( forbrug.pris ) AS pris,
                    COUNT(*)            AS number
                FROM
                    ".$this->p."usage AS forbrug,
                    ".$this->table."  AS bruger
                LEFT JOIN
                    ".$this->table."  AS mester
                ON
                   bruger.parent = mester.id
                WHERE
                    bruger.id = forbrug.bruger_id 
                AND 
                    forbrug.archived IS NULL
                GROUP BY
                    bruger.id
                ORDER BY
                    bruger.id";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        //check first if this is svend or a mester
        $id = ( $rec['mester_id'] )? $rec['mester_id']:$rec['user_id'];

        //group the results by the master user id
        $usage[ $id ][ count( $usage[$id] ) ] =  $rec;
      }
      return $usage;
    }
    function usageFromDayMonthYearToDayMonthYear( $day1,$month1,$year1,$day2,$month2,$year2 )
    {
      $usage = array();

      $from = $year1 .'-'. $month1 .'-'. $day1 .' 23:59:59';
      $to   = $year2 .'-'. $month2 .'-'. $day2 .' 23:59:59';


      $sql = "SELECT
                    bruger.id           AS user_id,
                    bruger.firma   AS name1,
                    bruger.navn   AS name2,
                    bruger.titel   AS name3,
                    bruger.medlemsnr    AS medlemesnr,
                    mester.id           AS mester_id,
                    mester.firma   AS mester_name1,
                    mester.navn   AS mester_name2,
                    mester.titel   AS mester_name3,
                    mester.medlemsnr    AS mester_medlemsnr,
                    SUM( forbrug.pris ) AS pris,
                    COUNT(*)            AS number
                FROM
                    ".$this->p."usage AS forbrug,
                    ".$this->table."  AS bruger
                LEFT JOIN
                    ".$this->table."  AS mester
                ON
                   bruger.parent = mester.id
                WHERE
                    bruger.id = forbrug.bruger_id 
                AND 
                    forbrug.readed >= '$from'
                AND
                    forbrug.readed <= '$to'
                GROUP BY
                    bruger.id
                ORDER BY
                    bruger.id";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        //check first if this is svend or a mester
        $id = ( $rec['mester_id'] )? $rec['mester_id']:$rec['user_id'];

        //group the results by the master user id
        $usage[ $id ][ count( $usage[$id] ) ] =  $rec;
      }
      return $usage;
    }
}
?>
