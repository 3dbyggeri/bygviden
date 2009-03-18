<?php
/**
 * Class which maps to a single record of the tree table representing a document
 * whith all it's fields
 * @author Ronald
 */
class document
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
     * Unique document id and the table primary key 
     * @type int
     */
    var $id;

    /**
     * Document name
     * @type String
     */
    var $name;

    /**
     * Id for the current records 'parent'
     * @type int
     */
    var $parentId;

    /**
     * This document parents name
     * @type String
     */
    var $parentName;

    /**
     * Flag -The document should appear on navigation ( 1 for true, 0 for false )
     * @type int
     */
    var $nav;

    /**
     * Formated date for publishing time ( "%d.%m.%Y" )
     * @type String
     */
    var $timepublish;

    /**
     * Date when to publish the document -Date array ( "d"=>nn,"m"=>nn,"Y"=>nnnn )
     * @type mixedArray
     */
    var $publishDate;

    /**
     * Formated date for unpublishing time ( "%d.%m.%Y" )
     * @type String
     */
    var $timeunpublish;

    /**
     * Date when to unpublish the document -Date array ( "d"=>nn,"m"=>nn,"Y"=>nnnn )
     * @type mixedArray
     */
    var $unpublishDate;

    /**
     * Document content
     * @type String
     */
    var $content;

    /**
     * Document description
     * @type String
     */
    var $description;

    /**
     * Document meta keywords
     * @type String
     */
    var $meta;

    /**
     * Document title
     * @type String
     */
    var $title;

    /**
     * Document heading
     * @type String
     */
    var $heading;

    /**
     * Id of the user who created this document
     * @type int 
     */
    var $creatorId;

    /**
     * Name of the user who created this document
     * @type String 
     */
    var $creatorName;

    /**
     * Id of the user who last edited this document
     * @type int 
     */
    var $editorId;

    /**
     * Name of the user who last edited this document
     * @type String
     */
    var $edtitorName;

    /**
     * Formated date for document creation time ( "%d.%m.%Y" )
     * @type String
     */
    var $created;

    /**
     * Formated date for document last edition time ( "%d.%m.%Y" )
     * @type String
     */
    var $edited;

    /**
     * Flag for the document state ( Publish = true, Unpublish = false )
     * @type Boolean
     */
    var $publish;

    /**
     * Id of the topic for this document
     * @type int 
     */
    var $topic;

    /**
     * List of documents this document links to ( "id"=>int,"name"=>String )
     * @type mixedArray
     */
    var $links2Docs;

    /**
     * List of documents which links to this one ( "id"=>int,"name"=>String )
     * @type mixedArray
     */
    var $linksFromDocs;

    /**
     * List of media this document links to ( "id"=>int,"name"=>String )
     * @type mixedArray
     */
    var $links2Media;

    /**
     * List of revisions for this document
     * "rev"=>int,"uid"=>int,"editor"=>String,"editor_fullname"=>String,"date"=>String 
     * @type mixedArray
     */
    var $history;

    /**
     * List of document and media included by this document
     * "id"=>int,"name"=>String,format=>String,"type"=>Char ( 'd':'m' ), "incid"=>int 
     * @type mixedArray
     */
    var $includes;

    /**
     * Name of the template choosen for this document
     * @type String
     */
    var $template;

    /**
     * Name of the layout choosen for this document
     * @type String
     */
    var $layout;

    /**
     * Flag - Is the document a news item ( true = 'y', false = 'n' )
     * @type Boolean
     */
    var $news;

    var $anchors;
    var $allIncludes;
    var $includeLevel = 0;

    var $topIncludedBy;
    var $includedBy;

    function document( $dba, $id=1 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
    }
    function search( $query, $from, $to )
    {
        if( !trim( $query ) ) return;

         $words = explode( " ", $query );
         $n     = count( $words );
         for( $i = 0; $i < $n; $i++ )
         {
            if( $qwords ) $qwords.= ',';
            $qwords.= "'". strtolower( $words[ $i ] ) ."'";
         }

          $sql ="SELECT 
                  COUNT( s.word ) AS score,
                  doc.id          AS 'id',
                  doc.title       AS title,
                  doc.name        AS name,
                  doc.heading     AS heading,
                  doc.description AS description,
                  LEFT( doc.content, 100 ) AS summary
                FROM 
                  ".$this->p."search  AS s,
                  ".$this->p."tree    AS doc 
                WHERE 
                  s.id = doc.id 
                AND 
                  s.word IN( $qwords ) 
                GROUP BY 
                  s.id 
                ORDER BY 
                  score DESC
                LIMIT 
                  $from, $to";
			
          $result = $this->dba->exec( $sql );
          $n      = $this->dba->getN( $result );
          for( $i = 0; $i < $n; $i++ )
          {
            $rec = $this->dba->fetchArray( $result );
            $results[ $rec["id"] ] = $rec;
          }
          return $results;
    }
    function getContent()
    {
        $sql = "SELECT
                    content
                FROM
                    ".$this->p."tree
                WHERE
                    id=". $this->id;
        $this->content = $this->dba->singleQuery( $sql );

        return $this->content;
    }
    function getTranslatedContent()
    {
        return $this->translateLinksBack( $this->getContent() );
    }
    function addRevision()
    {
    	  global $user;
        $uid = $user->id;

            //save the old version
        $sql ="INSERT INTO
            ".$this->p."history
            (
                docid,
                content,
                description,
		            template,
		            layout,
                meta,
                topic,
                title,
                heading,
                edited,
                editor
            )
            SELECT
                id,
                content,
                description,
                template,
                layout,
                meta,
                topic,
                title,
                heading,
                NOW(),
                $uid
            FROM
                ".$this->p."tree
            WHERE
                id = ". $this->id;
	    $this->dba->exec( $sql );
      $this->edited();
    }
    function edited()
    {
        $sql = "UPDATE
                ".$this->p."tree
               SET
                edited = NOW()
               WHERE
                id=".$this->id;
	    $this->dba->exec( $sql );
    }
    function setContent( $content )
    {
	    $this->addRevision();			
			
        if( !trim( $content ) ) $content = "";
        else
        {
            //translate links
            $content = $this->translateLinks( $content );
            $this->content = $content;

            //index content
            $this->index( );

            //extract references 
            $this->extractDependencies();
        }

        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    content = '". addslashes( trim( $content ) )."'
                WHERE
                    id=". $this->id;
        $this->dba->exec( $sql );
    }
                    
    function loadProperties()
    {
        $sql = "SELECT
                    name,
                    nav,
                    DATE_FORMAT(timepublish,'%d %m %Y')   as timepublish,
                    DATE_FORMAT(timeunpublish,'%d %m %Y') as timeunpublish,
                    YEAR(timepublish)                     as publishY,
                    MONTH(timepublish)                    as publishM,
                    DAYOFMONTH(timepublish)               as publishD,
                    YEAR(timeunpublish)                   as unpublishY,
                    MONTH(timeunpublish)                  as unpublishM,
                    DAYOFMONTH(timeunpublish)             as unpublishD,
                    YEAR(fromnews)                        as fromnewsY,
                    MONTH(fromnews)                       as fromnewsM,
                    DAYOFMONTH(fromnews)                  as fromnewsD,
                    YEAR(tonews)                          as tonewsY,
                    MONTH(tonews)                         as tonewsM,
                    DAYOFMONTH(tonews)                    as tonewsD,
                    description,
                    meta,
                    title,
                    heading,
                    template,
		                layout,
                    topic,
                    news
                FROM
                    ".$this->p."tree
                WHERE
                    id=". $this->id;

        $record             = $this->dba->singleArray( $sql );

        $this->name         = stripslashes ( $record["name"] );
        $this->title        = stripslashes ( $record["title"] );
        $this->heading      = stripslashes ( $record["heading"] );
        $this->nav          = $record["nav"];
        $this->timepublish  = $record["timepublish"];
        $this->timeunpublish= $record["timeunpublish"];
        $this->description  = stripslashes ( $record["description"] );
        $this->meta         = stripslashes ( $record["meta"] );
        $this->template     = $record["template"];
        $this->layout       = $record["layout"];
        $this->news         = $record["news"];
        $this->topic        = $record["topic"];

        $this->publishDate["y"] = $record["publishY"];
        $this->publishDate["m"] = $record["publishM"];
        $this->publishDate["d"] = $record["publishD"];

        $this->unpublishDate["y"] = $record["unpublishY"];
        $this->unpublishDate["m"] = $record["unpublishM"];
        $this->unpublishDate["d"] = $record["unpublishD"];

        $this->tonews["y"] = $record["tonewsY"];
        $this->tonews["m"] = $record["tonewsM"];
        $this->tonews["d"] = $record["tonewsD"];

        $this->fromnews["y"] = $record["fromnewsY"];
        $this->fromnews["m"] = $record["fromnewsM"];
        $this->fromnews["d"] = $record["fromnewsD"];

        $this->publish  = $this->isPublish();
    }
    function isPublish()
    {
        $sql = "SELECT 
                    id
                FROM 
                    ". $this->p ."tree
                WHERE
                    ( timepublish < NOW() OR timepublish IS NULL )
                AND
                    ( timeunpublish > NOW() OR timeunpublish IS NULL )
                AND 
                    id=".$this->id;

        return $this->dba->singleQuery( $sql );
    }
    function unPublish()
    {
        $sql = "UPDATE 
                    ".$this->p."tree 
                SET 
                    timepublish   = NULL,
                    timeunpublish = NOW()
                WHERE 
                    id=". $this->id;

        $this->dba->exec($sql);
    }
    function publish()
    {
        $sql = "UPDATE 
                    ".$this->p."tree
                SET
                    timepublish   = NULL,
                    timeunpublish = NULL
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setTitle( $title )
    {
        $this->title = trim( $title );
        
        $sql = "UPDATE
                    ".$this->p."tree

                SET
                    title ='". addslashes( $this->title ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setHeading( $heading )
    {
        $this->heading = trim( $heading );
        
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    heading ='". addslashes( $this->heading ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setTopic( $topic )
    {
        if( !is_numeric( $topic ) ) $topic = 0;
        $this->topic =  $topic;
        
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    topic =". $this->topic ." 
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setDescription( $description )
    {
        $this->description = trim( $description );
        
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    description ='". addslashes( $this->description ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }

    function setMeta( $meta )
    {
        $this->meta = trim( $meta );
        
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    meta ='". addslashes( $this->meta ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setNews( $news )
    {
        $this->news = ( $news == 'on' )? 'y': 'n';
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    news ='". $this->news ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);

        if( $this->news == 'y' )
        {
          $sql = "UPDATE
                    ".$this->p."tree
                SET
                    fromnews = NOW(),
                    tonews   = DATE_ADD( NOW(), INTERVAL 7 DAY )
                WHERE 
                    id=". $this->id;
          $this->dba->exec($sql);
        }
    }
    function setFromnews( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->p."tree 
                SET  fromnews=";
        if( $d )
        {
            $this->fromnews["d"] = $d;
            $this->fromnews["m"] = $m;
            $this->fromnews["y"] = $y;

            $sql.= "CONCAT( '$y-$m-$d ', HOUR( NOW() ) ,':', MINUTE( NOW() ) ,':', SECOND( NOW() ) )";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql.= " WHERE id=". $this->id;
        $this->dba->exec( $sql );
    }
    function setTonews( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->p."tree 
                SET  tonews=";
        if( $d )
        {
            $this->tonews["d"] = $d;
            $this->tonews["m"] = $m;
            $this->tonews["y"] = $y;

            $sql.= "CONCAT( '$y-$m-$d ', HOUR( NOW() ) ,':', MINUTE( NOW() ) ,':', SECOND( NOW() ) )";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql.= " WHERE id=". $this->id;
        $this->dba->exec( $sql );
    }
    function setNav( $nav )
    {
        $this->nav = ( $nav )? 1: 0;
        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    nav=". $this->nav  ."
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setPublishDate( $d=0, $m=0, $y=0 )
    {

        $sql = "UPDATE 
                  ".$this->p."tree 
                SET  timepublish=";
        if( $d )
        {
            $this->publishDate["d"] = $d;
            $this->publishDate["m"] = $m;
            $this->publishDate["y"] = $y;

            $sql.= "'$y-$m-$d 00:00:00'";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql.= " WHERE id=". $this->id;

        $this->dba->exec( $sql );
    }
    function setUnPublishDate( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->p."tree 
                SET  timeunpublish=";
        if( $d )
        {
            $this->unpublishDate["d"] = $d;
            $this->unpublishDate["m"] = $m;
            $this->unpublishDate["y"] = $y;

            $sql.= "'$y-$m-$d 00:00:00'";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql.= " WHERE id=". $this->id;

        $this->dba->exec( $sql );

    }
    function setPublish( $publish )
    {
        $this->publish  = $this->isPublish();

        if( $publish )
        {
            //check is the publish settings has been changed
            if( $this->publish ) return;

            //check if publishish has been schedule
            if( $this->publishDate  ) return;

            $this->publish();
        }

        else
        {
            //check is the publish settings has been changed
            if( !$this->publish ) return;

            //check if unpublishing har been scheduled
            if( $this->unpublishDate  ) return;

            $this->unPublish();
        }
    }
    function translateLinks( $string )
    {
        $string  = stripslashes( $string );

        //translate intern links
        $pattern = 'href="'."[^\"]*index.php\?";
    	$replace = 'href="index.php?';

        $string  = ereg_replace( $pattern, $replace, $string );

        //translate intern media references
        $pattern = 'src="'."[^\"]*media\/([0-9]*\....)";
        $replace = 'src="media/'."\\1";
				
				$string  = ereg_replace( $pattern, $replace, $string );
				
				$pattern = 'VALUE="'."[^\"]*media\/([0-9]*\....)";
        $replace = 'VALUE="media/'."\\1";

        $string  = ereg_replace( $pattern, $replace, $string );

        return $string;
    }
    function translateLinksBack( $string )
    {
        $string = stripslashes( $string );

        //translate intern media back
        $pattern = 'src="'."[^\"]*media\/([0-9]*\....)";
        $replace = 'src="../../media/'."\\1";

        $string  = ereg_replace( $pattern, $replace, $string );
				
				$pattern = 'VALUE="'."[^\"]*media\/([0-9]*\....)";
        $replace = 'VALUE="../../media/'."\\1";

        $string  = ereg_replace( $pattern, $replace, $string );
    
        return $string;
    }
    function getDependencies()
    {
        $this->getLinks2Docs();
        $this->getLinksFromDocs();
        $this->getLinks2Media();
    }
    function getLinksFromDocs()
    {
        //get all the docs who link to this doc
        $sql = "SELECT
                    doc.id   as id,
                    doc.name as name
                FROM
                    ".$this->p."references as ref,
                    ".$this->p."tree as doc
                WHERE
                    ref.reference = ".$this->id." 
                AND
                    ref.reference_type='d'
                AND
                    ref.referer = doc.id
                ORDER BY
                    ref.referer"; 

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
            $this->linksFromDocs[ $i ] = $this->dba->fetchArray( $result );
       }
    }
    function getLinks2Docs()
    {
        //get all the docs this doc links to
        $sql = "SELECT
                    doc.id   as id,
                    doc.name as name
                FROM
                    ".$this->p."references as ref,
                    ".$this->p."tree as doc
                WHERE
                    ref.referer = ".$this->id." 
                AND
                    ref.reference_type='d'
                AND
                    ref.reference = doc.id
                ORDER BY
                    ref.referer"; 

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
            $this->links2Docs[ $i ] = $this->dba->fetchArray( $result );
       }
    }
    function getLinks2Media()
    {
        //get all the media this doc links to
        $sql = "SELECT
                    media.id   as id,
                    media.name as name,
		    ref.reference_type as type
                FROM
                    ".$this->p."references as ref,
                    ".$this->p."mediatree as  media 
                WHERE
                    ref.referer = ".$this->id." 
                AND
                    ( ref.reference_type='m' OR ref.reference_type='l' )
                AND
                    ref.reference = media.id

                ORDER BY
                    ref.referer"; 

       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
            $this->links2Media[ $i ] = $this->dba->fetchArray( $result );
       }
    }
    function extractDependencies()
    {
       $sql = "DELETE FROM
                ".$this->p."references
               WHERE
                referer = ". $this->id;
       $this->dba->exec( $sql );

       $text = stripslashes( $this->content );

       //get links to document references 
       $pattern = "/<[^>]*index.php\?page=(\d*)[^>]*>/x";
       $matches = preg_match_all( $pattern, $text, $content );

       $total = count( $content[0] );
       for ( $i = 0; $i < $total; $i++ ) 
       {
            $reference = $content[1][$i];

            $sql ="INSERT INTO 
                        ". $this->p."references
                   (
                        referer,
                        reference,
                        reference_type 
                   )
                   VALUES
                   ( 
                        ". $this->id .",
                        $reference, 
                        'd' 
                    )";
            $this->dba->exec( $sql );
       }

       //get links to media
       $pattern = "/<[^>]*media.php\?id=(\d*)[^>]*>/x";
       $matches = preg_match_all( $pattern, $text, $content );

       $total = count( $content[0] );
       for ( $i = 0; $i < $total; $i++ ) 
       {
            $reference = $content[1][$i];

            $sql ="INSERT INTO 
                        ". $this->p."references
                   (
                        referer,
                        reference,
                        reference_type 
                   )
                   VALUES
                   ( 
                        ". $this->id .",
                        $reference, 
                        'l' 
                    )";
            $this->dba->exec( $sql );
       }

        //get media embeded in document
        $pattern = "/< [^>]* media\/(\d*) [^>]*/x";
        $matches = preg_match_all( $pattern, $text, $content );

        $total = count( $content[0] );
        for ($i = 0; $i < $total; $i++) 
        {
            $reference = $content[1][$i];

            $sql ="INSERT INTO 
                        ". $this->p."references
                   (
                        referer,
                        reference,
                        reference_type 
                   )
                   VALUES
                   ( 
                        ". $this->id .",
                        $reference, 
                        'm' 
                    )";

            $this->dba->exec( $sql );
        }
    }
    function getHistory()
    {
    	$sql = "SELECT
                h.id    as 'rev',
                u.id    as 'uid',
                u.name  as 'editor',
                u.full_name as 'editor_fullname',
                h.edited  as 'date'
              FROM
                ".$this->p."history as h,
                ".$this->p."a_user  as u
              WHERE
                h.editor = u.id
              AND
                h.docid = " .$this->id ."
              ORDER BY
                h.id";
      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $this->history[ $i ] = $this->dba->fetchArray( $result );
      }
    }
    function getRevision( $id )
    {
    	$sql = "SELECT
                h.content     as content,
                h.description as description,
                h.meta        as meta,
                h.title       as title,
                h.heading     as heading,
                h.edited      as date,
                u.id          as uid,
                u.name        as editor,
                u.full_name   as editor_fullname
              FROM
                ".$this->p."history as h,
                ".$this->p."a_user  as u
              WHERE
                h.editor = u.id
              AND
                h.id = $id";
      $this->revision = $this->dba->singleArray( $sql);
      $this->revision["content"] = $this->translateLinksBack( $this->revision["content"] );
    }
    function removeRevision( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "DELETE FROM
                  ".$this->p."history 
                WHERE
                  id = $id";
        $this->dba->exec( $sql );
    }
    function restore( $id )
    {
    	//save the current revision
    	$this->addRevision();

	    //load the requested one
    	$sql = "SELECT
                content,     
                description,
                meta,        
                title,       
                heading,
                editor,
                template,
                layout,
                topic
              FROM
                ".$this->p."history 
              WHERE
                id = $id";

      $revision = $this->dba->singleArray( $sql);
      if( !$revision["topic"] ) $revision["topic"] = "NULL";

      //update the document
      $sql = "UPDATE
                ".$this->p."tree
              SET
                content     = '". addslashes( $revision["content"] ) ."',
                description = '". addslashes( $revision["description"]  ) ."',
                meta        = '". addslashes( $revision["meta"] ) ."',
                title       = '". addslashes( $revision["title"] ) ."',
                heading     = '". addslashes( $revision["heading"] )."',
                template    = '". addslashes( $revision["template"] ) ."',
                layout      = '". addslashes( $revision["layout"] )."',
                topic       = ".  addslashes( $revision["topic"] ) ."
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function index()
    {
        $sql = "DELETE FROM 
                        ".$this->p."search
                    WHERE 
                        id= ". $this->id;
        $this->dba->exec( $sql );

        $text = strip_tags( $this->content );
        $text.= ' '. strip_tags( $this->title );
        $text.= ' '. strip_tags( $this->description );
        $text.= ' '.strip_tags( $this->name );
        $text.= ' '.strip_tags( $this->meta );
        $text = strtolower( $text );

        $noise_words = file( "noiselist.dat" );
        $n = count( $noise_words );

        for( $i = 0; $i < $n; $i++ )
        {
                $word     = trim( $noise_words[ $i ] );
                $text     = preg_replace("/\b$word\b/" ," ", $text );
        }

        //remove signs and special characters
        $filtered = trim( $text );
        $filtered = addslashes($filtered);
        $filtered = ereg_replace( "&NBSP;",  " ",$filtered );
        $filtered = ereg_replace( "&GT;",    " ",$filtered );
        $filtered = ereg_replace( "&LT;",    " ",$filtered );
        $filtered = ereg_replace( "[\n\r\t]"," ",$filtered );
        $filtered = ereg_replace( ",",       " ",$filtered );
        $filtered = ereg_replace( "\?",      " ",$filtered );
        $filtered = ereg_replace( "\(",      " ",$filtered );
        $filtered = ereg_replace( "\)",      " ",$filtered );
        $filtered = ereg_replace( "\.",      " ",$filtered );
        $filtered = ereg_replace( "-",       " ",$filtered );
        $filtered = ereg_replace( "_",       " ",$filtered );
        $filtered = ereg_replace( "\*",      " ",$filtered );
        $filtered = ereg_replace( "\/",      " ",$filtered );

        $word_array = explode( " ", $filtered );

        $n = count( $word_array );
        for( $j = 0; $j < $n; $j++ )
        {
            $a_word = trim( $word_array[ $j ] );
            if( $a_word && !is_numeric( $a_word ) )
            {
                $sql = "INSERT INTO 
                            ".$this->p."search
                        (
                            id,
                            word
                        ) 
                        VALUES
                        (
                            ".$this->id.",
                            '$a_word'
                        )";
                $this->dba->exec( $sql );
            }
        }
    }
    function removeInclude( $remove )
    {
	if( !is_numeric( $remove ) ) return;
	$sql = "DELETE FROM
		    ".$this->p."includes
		WHERE
		    id = ". $remove;
	$this->dba->exec( $sql );
    }
    function addInclude( $doc, $type )
    {
	if( !is_numeric( $doc ) || !trim( $type ) ) return;

	$maxIncludePosition = $this->getMaxIncludePosition();
	$position = $maxIncludePosition++;
	if(!$position ) $position = 1;
	

	$sql = "INSERT INTO 
		  ".$this->p."includes
		(
		  doc,
		  internal,
		  type,

		  position
		)
		VALUES
		(
		  ". $this->id .",
		  ". $doc .",
		  '$type',
		  $position
		)";
	$this->dba->exec( $sql );
    }
    function moveIncludeUp( $id )
    {
    	if( !is_numeric( $id ) ) return;

        $sql = "SELECT
                id,
                position
            FROM
                ".$this->p."includes
            WHERE
                doc = ". $this->id ."
            ORDER BY

                position";
        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->fetchArray( $result );
            $record["position"] = ( $i + 1 );

            if( $record["id"]== $id && $previousRecord )
            {
                $sql = "UPDATE
                        ".$this->p."includes
                    SET
                        position=". $previousRecord["position"] ."
                    WHERE
                        id = ". $record["id"];
                $this->dba->exec( $sql );


                $sql = "UPDATE
                        ".$this->p."includes
                    SET
                        position=". $record["position"] ."
                    WHERE
                        id = ". $previousRecord["id"];
                $this->dba->exec( $sql );

                return;    
            }
            else
            {
                $sql = "UPDATE
                        ".$this->p."includes
                    SET
                        position=". ( $i + 1 ) ."
                    WHERE
                        id = ". $record["id"];
                $this->dba->exec( $sql );
            }
            $previousRecord = $record;
        }
    }
    function moveIncludeDown( $id )
    {
    	if( !is_numeric( $id ) ) return;

	$sql = "SELECT
		    id,
		    position
		FROM
		    ".$this->p."includes
		WHERE
		    doc = ". $this->id ."
		ORDER BY
		    position";
	$result = $this->dba->exec( $sql );
	$n	= $this->dba->getN( $result );
	for( $i = 0; $i < $n; $i++ )
	{
	    $record = $this->dba->fetchArray( $result );
	    $record["position"] = $i + 1;

	    if( $nextRecord )
	    {
		$sql = "UPDATE
			    ".$this->p."includes
			SET
			    position=". $nextRecord["position"] ."
			WHERE
			    id = ". $record["id"];
		$this->dba->exec( $sql );

		$sql = "UPDATE
			    ".$this->p."includes
			SET
			    position=". $record["position"] ."
			WHERE
			    id = ". $nextRecord["id"];
		$this->dba->exec( $sql );
		unset( $nextRecord );
	    }
	    else
	    {
		$sql = "UPDATE
			    ".$this->p."includes
			SET
			    position=". ( $i + 1 ) ."
			WHERE
			    id = ". $record["id"];
		$this->dba->exec( $sql );
	    }

	    if( $record["id"]== $id ) $nextRecord = $record;
	}
    }
    function getMaxIncludePosition()
    {
	$sql = "SELECT
		    MAX( position )
		FROM
		    ".$this->p."includes
		WHERE
		    doc = ".$this->id;
	return $this->dba->singleQuery( $sql );
    }
  function getIncludes( )
  {
    //first get docs
    $sql = "SELECT
          doc.id as id,
          doc.name as name,
          inc.type as type,
          inc.id  as incid
      FROM
          ".$this->p."includes as inc
      LEFT JOIN
          ".$this->p."tree as doc
      ON
          doc.id = inc.internal
      WHERE
          inc.doc  = ". $this->id ."
      ORDER BY
          inc.position";

    $result = $this->dba->exec( $sql );
    $n	= $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
        $this->includes[ $i ] = $this->dba->fetchArray( $result );
    }

    //now get the media
    $sql = "SELECT
          doc.id as id,
          doc.name as name,
          doc.format as format,
          inc.type as type,
          inc.id   as incid
      FROM
          ".$this->p."includes as inc
      LEFT JOIN
          ".$this->p."mediatree as doc
      ON
          doc.id = inc.internal
      WHERE
          inc.doc  = ". $this->id ."
      ORDER BY
          inc.position";
    $result = $this->dba->exec( $sql );
    $n	= $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
          $inc = $this->dba->fetchArray( $result );
          if( $inc["type"] == "m" ) $this->includes[ $i ] = $inc;

    }

    //now get the forms
    $sql = "SELECT
          doc.id as id,
          doc.name as name,
          inc.type as type,
          inc.id   as incid
      FROM
          ".$this->p."includes as inc
      LEFT JOIN
          ".$this->p."forms as doc
      ON
          doc.id = inc.internal
      WHERE
          inc.doc  = ". $this->id ."
      ORDER BY
          inc.position";
    $result = $this->dba->exec( $sql );
    $n	= $this->dba->getN( $result );
    for( $i = 0; $i < $n; $i++ )
    {
          $inc = $this->dba->fetchArray( $result );
          if( $inc["type"] == "f" ) $this->includes[ $i ] = $inc;
    }
    return $this->includes;
  }
  function getAnchors()
  {
    $this->getAllIncludes( $this->id, array() );
	  return $this->anchors;
  }
  function getNestedIncludes()
  {
    $this->getAllIncludes( $this->id, array() );
	  return $this->allIncludes;
  }
    function getAllIncludes( $id, $path )
    {
        $path[ count( $path ) ] = $id;

        //get all the includes for the document
        $sql = "SELECT 
		            inc.id	     AS inckey,
                inc.doc	     AS id,
		            inc.type     AS type,
                inc.internal AS incid,
                inc.type     AS type,
                doc.name     AS name
            FROM 
                ".$this->p."includes AS inc 
            LEFT JOIN 
                ".$this->p."tree AS doc 
            ON 
                inc.internal = doc.id 
            WHERE 
                inc.doc= $id 
            ORDER BY
                inc.position";

        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->fetchArray( $result );
	          $anchorsN = count( $this->anchors );
            $a =( $record["type"] == 'd' )?"doc_":"media_";
            $a.= $record["incid"];
            $this->anchors[ $anchorsN ]["incid"] = $a;

            $this->allIncludes[ $anchorsN ]["id"]      = $record["incid"];
            $this->allIncludes[ $anchorsN ]["inckey"]  = $record["inckey"];
            $this->allIncludes[ $anchorsN ]["name"]    = $record["name"];
            $this->allIncludes[ $anchorsN ]["type"]    = $record["type"];
            $this->allIncludes[ $anchorsN ]["level"]   = $this->includeLevel;

            if( $record["type"] == "m" ) 
            {
              $media = $this->getMediaNameAndFormat( $record["incid"] );
              $this->anchors[ $anchorsN ]["incName"]    = $media["name"];
              $this->allIncludes[ $anchorsN ]["name"]   = $media["name"];
              $this->allIncludes[ $anchorsN ]["format"] = $media["format"];
            }
            if( $record["type"] == "d" && !in_array( $record["incid"], $path ) )
            {
              $this->anchors[ $anchorsN ]["incName"] = $record["name"];
              $record["path"] = $path;
              $this->includeLevel++;
              $record["includes"] = $this->getAllIncludes( $record["incid"], $path );
              $this->includeLevel--;
	          }
            if( $record["type"] == 'f' )
            {
              $sql = "SELECT name FROM ". $this->p ."forms WHERE id=". $record["incid"];
              $name = $this->dba->singleQuery( $sql );
              $this->anchors[ $anchorsN ]["incName"]    = $name;
              $this->allIncludes[ $anchorsN ]["name"]   = $name;
            }
	      }
    }
    function getMediaNameAndFormat( $id )
    {
	if( !is_numeric( $id ) ) return;
	$sql = "SELECT
		    name,
		    format
		FROM
		    ". $this->p. "mediatree
		WHERE
		    id= $id";
	return $this->dba->singleArray( $sql );
    }

    function chooseLayout( $layoutName ="" )
    {
	$this->layout = $layoutName;
    	if( !trim( $layoutName ) ) $layoutName = ' NULL ';
	else $layoutName = " '$layoutName' ";

	$sql = "UPDATE
			". $this->p ."tree
		SET
			layout = $layoutName
		WHERE
			id = ". $this->id;
	$this->dba->exec( $sql );
    }
    function getTopIncluders( )
    {
    	$doc["id"]    = $this->id;
	$doc["name"]  = $this->name; 
	$doc["title"] = $this->title;
	$doc["summary"] = $this->summary;

    	$this->whoIncludesMe( $doc, array() );
	return $this->topIncludedBy;
    }
    
    function getPublished( $results )
    {
    	$published = array_keys( $results );

    	for( $i = 0; $i < count( $published ); $i++ )
	    {
		      if( $ids ) $ids.= ",";
		      $ids.= $published[$i];
	    }
      
      $sql = "SELECT 
                    id
                FROM 
                    ". $this->p ."tree
                WHERE
                    ( timepublish < NOW() OR timepublish IS NULL )
                AND
                    ( timeunpublish > NOW() OR timeunpublish IS NULL )
                AND 
                    id IN( $ids )"; 

      if( $ids )
      {
        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->getRecord( $result );
          $newresults[ $i ] = $results[ $rec[0] ]; 
        }
      }
      
      return $newresults;
    }

    function getIncluders( )
    {
      $sql = "SELECT
                doc.id,
                doc.name
              FROM
                ".$this->p."tree as doc,
                ".$this->p."includes AS inc
              WHERE
                doc.id = inc.doc
              AND
                inc.internal =". $this->id ."
              AND
                 inc.type='d'";

      $result = $this->dba->exec( $sql );
      $n 	= $this->dba->getN( $result );

      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $this->includedBy[ $i ] = $rec;	
      }
    }
    function getPublishedDocs( $topDocs )
    {
    	$published = array();

    	for( $i = 0; $i < count( $topDocs ); $i++ )
	    {
		      if( $ids ) $ids.= ",";
		      $ids.= $topDocs[$i]["id"];
	    }

        $sql = "SELECT 
                    id
                FROM 
                    ". $this->p ."tree
                WHERE
                    ( timepublish < NOW() OR timepublish IS NULL )
                AND
                    ( timeunpublish > NOW() OR timeunpublish IS NULL )
                AND 
                    id IN( $ids )"; 
	if( $ids )
	{
		$result = $this->dba->exec( $sql );
		$n	= $this->dba->getN( $result );
		for( $i = 0; $i < $n; $i++ )
		{
			$rec = $this->dba->getRecord( $result );
			$published[ $i ] = $rec[0]; 
		}
	}

    	return $published;
    }
    function whoIncludesMe( $doc, $path )
    {
	if( !is_numeric( $doc["id"] ) ) return;
	$path[ count( $path ) ] = $doc["id"];
	$sql = "SELECT
			doc.id  	AS id,
			doc.name	AS name,
			doc.title 	AS title,
			LEFT( doc.content, 100 ) AS summary
		FROM
			".$this->p."includes AS inc
		LEFT JOIN
			".$this->p."tree as doc
		ON
			doc.id = inc.doc
		WHERE
			inc.internal = ". $doc["id"] ."
		AND
			inc.type = 'd'
		ORDER BY
			inc.position";
	$result = $this->dba->exec( $sql );
	$n 	= $this->dba->getN( $result );

	if( !$n ) 
	{
		$doc["anchor"] = "doc_". $this->id;
		$this->topIncludedBy[ count( $this->topIncludedBy ) ] = $doc;	
	}

	for( $i = 0; $i < $n; $i++ )
	{
		$rec = $this->dba->fetchArray( $result );
		$this->includedBy[ count( $this->includedBy ) ] = $rec;	
		if( !in_array( $rec["id"], $path ) ) $this->whoIncludesMe( $rec, $path );
	}
    }
}
?>
