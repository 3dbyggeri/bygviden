<?php
/**
 * Statistics class for single documents and the complete site
 */
class statistics
{
    /** 
     * Connection object and database abstraction layer
     * @type dba 
     */
    var $dba;

    /** 
     * Prefix for tables in the current installation
     * @type String
     */
    var $p;

    /**
     * Node id ( document id )
     * @type int
     */
    var $id;

    /**
     * Interval used to fetch current people visiting the site or a expecific page
     * @type int
     */
    var $interval = 200;

    /**
     * statistics constructor
     * @param dba Connection object
     */
    function statistics( $dba )
    {
      $this->dba = $dba;
      $this->p   = $dba->getPrefix();
    }
    
    function getUserStat($f=0,$t=0,$user_id)
    {
      if(!$f) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $from = $f;
      if(!$t) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $to = $t;

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql="SELECT
              count(*) AS total
            FROM
              ".$this->p."stats as stat
            WHERE
               ( stat.user_id =$user_id OR stat.machine_id=$user_id )
            AND
               UNIX_TIMESTAMP(DATE_FORMAT(stat.timestamp,'%Y-%m-%d 00:00:00')) >= UNIX_TIMESTAMP('".$fromDate."') 
              AND
                UNIX_TIMESTAMP(DATE_FORMAT(stat.timestamp,'%Y-%m-%d 00:00:00')) <= UNIX_TIMESTAMP('".$toDate."') 
            GROUP BY 
                stat.sessid
            ORDER BY
              total DESC";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $s = $this->dba->fetchArray( $result ); 
        $hits[count($hits)] = $s['total']; 
      }

      return $hits;
    }
    function getUserPublications($f=0,$t=0,$user_id)
    {
      if(!$f) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $from = $f;
      if(!$t) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $to = $t;

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS total, 
                DATE_FORMAT( timestamp,'%a %d.%m.%Y') as mydate
              FROM 
                ".$this->p."stats  
              WHERE
               ( user_id =$user_id OR machine_id=$user_id )
              AND
                page_type='document'
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                mydate
              ORDER BY
                timestamp DESC";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }

      return $hits;
    }
    function getUserOverview($f=0,$t=0,$user_id)
    {
      if(!$f) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $from = $f;
      if(!$t) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $to = $t;

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS total, 
                DATE_FORMAT( timestamp,'%a %d.%m.%Y') as mydate
              FROM 
                ".$this->p."stats  
              WHERE
               ( user_id =$user_id OR machine_id=$user_id )
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                sessid,mydate
              ORDER BY
                timestamp DESC";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }

      return $hits;
    }
    function getTopReadingUsers($f=0,$t=0)
    {
      if(!$f) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $from = $f;
      if(!$t) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $to = $t;

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql= "SELECT
              stat.user_id, 
              bruger.firma,
              bruger.navn,
              bruger.titel,
              count(*) AS total
            FROM
              dev_stats as stat,
              dev_bruger as bruger
            WHERE
               bruger.id = stat.user_id
            AND 
              stat.page_type='document'
            AND
               bruger.gratist = 'n' ";
       if($f && $t) $sql .="
            AND
               UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '".$fromDate."' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '".$toDate."' ) 
            ";
       $sql.="
            GROUP BY 
                stat.user_id
            ORDER BY
              total DESC
            LIMIT 0,15";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $s = $this->dba->fetchArray( $result ); 
          $hits[count($hits)] = $s; //['session_hits']; 
        }
        return $hits;
    }
    function getTopActiveUsers($f=0,$t=0)
    {
      if(!$f) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $from = $f;
      if(!$t) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      else $to = $t;

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql= "SELECT
              stat.user_id, 
              bruger.firma,
              bruger.navn,
              bruger.titel,
              count(*) AS total
            FROM
              dev_stats as stat,
              dev_bruger as bruger

            WHERE
               bruger.id = stat.user_id
            AND
               bruger.gratist = 'n' ";
       if($f && $t) $sql .="
            AND
               UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '".$fromDate."' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '".$toDate."' ) 
            ";
       $sql.="
            GROUP BY 
                stat.user_id
            ORDER BY
              total DESC
            LIMIT 0,15";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $s = $this->dba->fetchArray( $result ); 
          $hits[count($hits)] = $s; //['session_hits']; 
        }
        return $hits;
    }
    
    function getDayStats($to=0)
    {
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS session_hits 
              FROM 
                ".$this->p."stats 
              WHERE 
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                sessid";


      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $s = $this->dba->fetchArray( $result ); 
        $hits[count($hits)] = $s['session_hits']; 
      }

      return $hits;
    }
    function getElementStats($from=0,$to=0,$id=0)
    {
      if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT
                count(stats.id) AS total
              FROM
                ".$this->p."stats as stats 
              WHERE
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
                AND
                  stats.page_type='elements' ";
       if($id) $sql.=" AND stats.element_id =".$id;

       return $this->dba->singleQuery($sql);
    }
    function getElementDayStats($to=0,$id=0)
    {
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql="SELECT 
              count(stats.id) as total
            FROM 
              ".$this->p."stats as stats 
            WHERE
              page_type='elements'
            AND
              UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) ";

      if($id) $sql.=" AND stats.element_id =".$id;

      return $this->dba->singleQuery($sql);
    }

    function getDocStats($from=0,$to=0,$parent_type='',$parent_id=0)
    {
      if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      if($parent_type)
      {
        $sql="SELECT 
                count(stats.id) as total
              FROM 
                ".$this->p."stats as stats, 
                ".$this->p."kildestyring as kilde,
                ".$this->p."kildestyring as kilde_b,
                ".$this->p."kildestyring as kilde_c
              WHERE";

        if($parent_type=='leverandor') $sql.=" kilde_c.id = ". $parent_id;
        if($parent_type=='kategori') $sql.=" kilde_b.id = ". $parent_id;
        if($parent_type=='publikation') $sql.=" kilde.id = ". $parent_id;

        $sql.="
              AND
                stats.publication_id = kilde.id
              AND
                kilde.parent = kilde_b.id
              AND
                kilde_b.parent = kilde_c.id
              AND";
       }
       else
       {
          $sql = "SELECT 
                    COUNT(*) AS session_hits 
                  FROM 
                    ".$this->p."stats 
                  WHERE "; 
       }
       $sql.= "  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
                AND
                  page_type='document'";
       return $this->dba->singleQuery($sql);
    }

    function getDocDayStats($to=0,$parent_type='',$parent_id=0)
    {
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql="SELECT 
              count(stats.id) as total
            FROM 
              ".$this->p."stats as stats, 
              ".$this->p."kildestyring as kilde,
              ".$this->p."kildestyring as kilde_b,
              ".$this->p."kildestyring as kilde_c
            WHERE
              stats.publication_id = kilde.id
            AND
              kilde.parent = kilde_b.id
            AND
              kilde_b.parent = kilde_c.id
            AND
              page_type='document'
            AND
              UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) ";

      if($parent_type=='leverandor') $sql.=" AND kilde_c.id = ". $parent_id;
      if($parent_type=='kategori') $sql.=" AND kilde_b.id = ". $parent_id;
      if($parent_type=='publikation') $sql.=" AND kilde.id = ". $parent_id;

      return $this->dba->singleQuery($sql);
    }

    function getLast24HoursDokuments($to=0,$parent_type='',$parent_id=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql="SELECT 
                count(*) as total,
                DATE_FORMAT( stats.timestamp,'%H') as myhour 
              FROM 
                ".$this->p."stats as stats, 
                ".$this->p."kildestyring as kilde,
                ".$this->p."kildestyring as kilde_b,
                ".$this->p."kildestyring as kilde_c
              WHERE
                stats.publication_id = kilde.id
              AND
                kilde.parent = kilde_b.id
              AND
                kilde_b.parent = kilde_c.id
              AND
                page_type='document'
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( stats.timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) ";

        if($parent_type=='leverandor') $sql.=" AND kilde_c.id = ". $parent_id;
        if($parent_type=='kategori') $sql.=" AND kilde_b.id = ". $parent_id;
        if($parent_type=='publikation') $sql.=" AND kilde.id = ". $parent_id;
        
        $sql.= " GROUP BY
                  myhour
                 ORDER BY
                  myhour 
                 DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }
        return $hits;
    }

    function getElementsOverview( $from=0,$to=0,$id=0)
    {
      if(!$from) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if(!$to) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql="SELECT 
              count(*) as total,
              DATE_FORMAT( stat.timestamp,'%a %d.%m.%Y') as mydate
            FROM 
              ".$this->p."stats as stat 
            WHERE
              page_type='elements' ";
      if($id) $sql.=' AND element_id='.$id;

      $sql.=" AND
              UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
            AND
              UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
            GROUP BY 
              mydate
            ORDER BY
              stat.timestamp DESC";
      
      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }
      return $hits;
    }

    function getLast24HoursElements($to=0,$id=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  COUNT(*) AS total, 
                  DATE_FORMAT( timestamp,'%H') as myhour 
                FROM 
                  ".$this->p."stats  
                WHERE
                  page_type='elements' ";
        $sql.=" AND element_id=". $id;
        $sql.="
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                GROUP BY 
                  myhour
                ORDER BY
                  myhour DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }

    function getDocsOverview( $from=0,$to=0,$parent_type='',$parent_id=0 )
    {
      if(!$from) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if(!$to) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql="SELECT 
              count(*) as total,
              DATE_FORMAT( stat.timestamp,'%a %d.%m.%Y') as mydate
            FROM 
              ".$this->p."stats as stat, 
              ".$this->p."kildestyring as kilde,
              ".$this->p."kildestyring as kilde_b,
              ".$this->p."kildestyring as kilde_c
            WHERE
                stat.publication_id = kilde.id
              AND
                kilde.parent = kilde_b.id
              AND
                kilde_b.parent = kilde_c.id ";

      if($parent_type=='leverandor') $sql.="AND kilde_c.id = ". $parent_id;
      if($parent_type=='kategori') $sql.="AND kilde_b.id = ". $parent_id;
      if($parent_type=='publikation') $sql.="AND kilde.id = ". $parent_id;


      $sql .=" AND
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
               AND
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                mydate
              ORDER BY
                stat.timestamp DESC";
      
      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }
      return $hits;
    }
    function getStats( $from=0,$to=0 )
    {
      if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS session_hits 
              FROM 
                ".$this->p."stats 
              WHERE 
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                sessid";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $s = $this->dba->fetchArray( $result ); 
        $hits[count($hits)] = $s['session_hits']; 
      }

      return $hits;
    }
    function getUserLast24Hours($to=0,$user_id)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  COUNT(*) AS total, 
                  DATE_FORMAT( timestamp,'%H') as myhour 
                FROM 
                  ".$this->p."stats  
                WHERE
                  ( user_id =$user_id OR machine_id=$user_id )
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                GROUP BY 
                  sessid,myhour
                ORDER BY
                  myhour DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }
    function getLastSearches($to=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  stats.id,
                  stats.query_terms AS query,
                  stats.results AS results,
                  stats2.publication_id AS pub_id,
                  DATE_FORMAT( stats.timestamp,'%a %d.%m.%Y %H:%M:%S') as mydate
                FROM 
                  ".$this->p."stats AS stats
                LEFT JOIN
                  ".$this->p."stats AS stats2
                ON
                  stats.id=stats2.referer_id
                WHERE
                  stats.page_type = 'results'
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( stats.timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                ORDER BY
                  mydate DESC
                LIMIT 0,30";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }
    function getLast24HoursSearch($to=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  COUNT(*) AS total, 
                  DATE_FORMAT( timestamp,'%H') as myhour 
                FROM 
                  ".$this->p."stats  
                WHERE
                  page_type = 'results'
                AND
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                GROUP BY 
                  myhour
                ORDER BY
                  myhour DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }

    function getLast24Hours($to=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  COUNT(*) AS total, 
                  DATE_FORMAT( timestamp,'%H') as myhour 
                FROM 
                  ".$this->p."stats  
                WHERE
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                GROUP BY 
                  sessid,myhour
                ORDER BY
                  myhour DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          //$s = $this->dba->fetchArray( $result ); 
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }
    function getTopVisitedBrancher24($to=0)
    {
        if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
        $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

        $sql = "SELECT 
                  branche_id,
                  COUNT(*) AS total, 
                  DATE_FORMAT( timestamp,'%H') as myhour 
                FROM 
                  ".$this->p."stats  
                WHERE
                  UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) = UNIX_TIMESTAMP( '$toDate' ) 
                GROUP BY 
                  branche_id,myhour
                ORDER BY
                  myhour DESC";

        $hits = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          //$s = $this->dba->fetchArray( $result ); 
          $hits[count($hits)] = $this->dba->fetchArray( $result ); 
        }

        return $hits;
    }
    function getTopVisitedBrancher($from=0,$to=0,$element_id=0,$branche='')
    {
       if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
       if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

       $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
       $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

       $temp = array();
       $sql = "SELECT 
                branche_id,
                count(*) AS total
              FROM 
                ".$this->p."stats  
              WHERE 
                branche_id IS NOT NULL ";
       if($element_id) $sql.=" AND element_id=". $element_id;
       if($branche) $sql.= " AND branche_id='". $branche ."' ";
       $sql.="
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP by 
                branche_id 
              ORDER BY total DESC";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }
       return $temp;
    }
    function getTopReadedVidensLev($from=0,$to=0)
    {
       if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
       if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

       $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
       $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

       $temp = array();

       $sql = "SELECT 
                kilde_c.name as viden_lev_name,
                count(kilde_c.id) as total
              FROM 
                ".$this->p."stats as stat,
                ".$this->p."kildestyring as kilde,
                ".$this->p."kildestyring as kilde_b,
                ".$this->p."kildestyring as kilde_c
              WHERE 
                page_type='document' 
              AND
                stat.publication_id = kilde.id
              AND
                kilde.parent = kilde_b.id
              AND
                kilde_b.parent = kilde_c.id
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '2004-01-06 00:00:00' ) 
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '2004-02-05 00:00:00' ) 
              GROUP BY 
                kilde_c.id
              ORDER BY 
                total  
              DESC
              LIMIT 0,10";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }
       return $temp;
    }
    function getTopReadedElements($from=0,$to=0)
    {
       if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
       if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

       $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
       $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

       $temp = array();

       $sql = "SELECT 
                stat.element_id, 
                elements.name,
                count(stat.element_id) as total
              FROM 
                ".$this->p."stats as stat,
                ".$this->p."buildingelements as elements
              WHERE 
                page_type='elements' 
              AND
                stat.element_id = elements.id
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '2004-01-06 00:00:00' ) 
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '2004-02-05 00:00:00' ) 
              GROUP BY 
                stat.element_id
              ORDER BY 
                total  
              DESC
              LIMIT 0,10";


       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }

       return $temp;
    }

    function getTopReadedDokuments($from=0,$to=0,$parent_type='',$parent_id=0)
    {
       if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
       if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

       $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
       $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

       $temp = array();

       $sql = "SELECT 
                stat.publication_id, 
                kilde.name,
                kilde_b.name as category_name,
                kilde_c.name as viden_lev_name, 
                count(stat.publication_id) as total
              FROM 
                ".$this->p."stats as stat,
                ".$this->p."kildestyring as kilde,
                ".$this->p."kildestyring as kilde_b,
                ".$this->p."kildestyring as kilde_c
              WHERE 
                page_type='document' ";

        if($parent_type=='kategori') $sql.=" AND kilde_b.id = ". $parent_id;
        if($parent_type=='leverandor') $sql.=" AND kilde_c.id = ". $parent_id;

        $sql.="
              AND
                stat.publication_id = kilde.id
              AND
                kilde.parent = kilde_b.id
              AND
                kilde_b.parent = kilde_c.id
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '2004-01-06 00:00:00' ) 
              AND 
                UNIX_TIMESTAMP( DATE_FORMAT( stat.timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '2004-02-05 00:00:00' ) 
              GROUP BY 
                stat.publication_id
              ORDER BY 
                total  
              DESC
              LIMIT 0,10";


       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }

       return $temp;
    }
    function getTopVisitedAreas($from=0,$to=0)
    {
       if( !$from ) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
       if( !$to ) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

       $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
       $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

       $temp = array();
       $sql = "SELECT 
                page_type,
                count(*) AS total
              FROM 
                ".$this->p."stats  
              WHERE 
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP by 
                page_type
              ORDER BY total DESC";

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $temp[ count( $temp ) ] = $this->dba->fetchArray( $result ); 
       }

       return $temp;
    }
    function getSearchOverview( $from=0,$to=0 )
    {
      if(!$from) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if(!$to) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS total, 
                DATE_FORMAT( timestamp,'%a %d.%m.%Y') as mydate
              FROM 
                ".$this->p."stats  
              WHERE
                page_type='results'
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 
              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                mydate
              ORDER BY
                timestamp DESC";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        //$s = $this->dba->fetchArray( $result ); 
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }

      return $hits;
    }
    function getVisitsOverview( $from=0,$to=0 )
    {
      if(!$from) $from = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );
      if(!$to) $to = array( "d"=> date("d"),"m"=>date("m"),"Y"=>date("Y") );

      $fromDate = $from["Y"] ."-". $from["m"] ."-". $from["d"] ." 00:00:00";
      $toDate = $to["Y"] ."-". $to["m"] ."-". $to["d"] ." 00:00:00";

      $sql = "SELECT 
                COUNT(*) AS total, 
                DATE_FORMAT( timestamp,'%a %d.%m.%Y') as mydate
              FROM 
                ".$this->p."stats  
              WHERE
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) >= UNIX_TIMESTAMP( '$fromDate' ) 

              AND
                UNIX_TIMESTAMP( DATE_FORMAT( timestamp,'%Y-%m-%d 00:00:00') ) <= UNIX_TIMESTAMP( '$toDate' ) 
              GROUP BY 
                sessid,mydate
              ORDER BY
                timestamp DESC";

      $hits = array();
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        //$s = $this->dba->fetchArray( $result ); 
        $hits[count($hits)] = $this->dba->fetchArray( $result ); 
      }

      return $hits;
    }
    function getHitsOnPageOnDay( $id = 0, $day = 0 )
    {
      /*
      //**********SCRAPBOOK***************
      //this gets the total number of hits for every hitet page on the
      //site on the given day 'd'

        SELECT COUNT( * ) 
        as total, DATE_FORMAT( timestamp, '%d.%m.%Y' ) 
        as date, id
        FROM w_stats
        WHERE TO_DAYS( NOW( ) ) - TO_DAYS( timestamp ) = 0 AND assettype = 'd'
        GROUP BY id LIMIT 0, 30 
      //**********************************
      */

      if( $id )
      {
        $sql = "SELECT 
                  COUNT( * ) as total,
                  DATE_FORMAT( timestamp, '%d.%m.%Y' ) as date
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  TO_DAYS(NOW()) - TO_DAYS( timestamp ) = $day
               AND

                id= $id
               AND
                assettype='d'
              GROUP BY 
                id";
       }
       else
       {
        $sql = "SELECT 
                  COUNT( * ) as total,
                  DATE_FORMAT( timestamp, '%d.%m.%Y' ) as date
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  TO_DAYS(NOW()) - TO_DAYS( timestamp ) = $day
               AND
                assettype='d'
              GROUP BY 
                assettype";
       }
       return $this->dba->singleArray( $sql );
    }
    function getUsersOnline( $id = 0 )
    {
      if( $id )
      {
        $sql = "SELECT 
                  COUNT( DISTINCT (sessid) ) 
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - ". $this->interval ." ) 
               AND
                id= $id
               AND
                assettype='d'
              GROUP BY 
                id";
      }
      else
      {
        $sql = "SELECT 
                  COUNT( DISTINCT (sessid) ) 
               FROM 
                  ". $this->p ."stats 
               WHERE 
                  UNIX_TIMESTAMP( timestamp ) > ( UNIX_TIMESTAMP() - ". $this->interval ." ) 
               AND
                assettype='d'
               GROUP BY 
                id";
      }
       return $this->dba->singleQuery( $sql );
    }
}

