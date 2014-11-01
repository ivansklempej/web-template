<?php


/**
* @author ivansklempej
*/

class dblite extends SQlite3 {

    public function __construct( $dbfile ){

        parent::open( $dbfile );
    
    }

    public function getArray( $sql  ){

        $res = array();

        $results = parent::query( $sql );
        while ($row = $results->fetchArray()) {
            array_push ($res, $row);
        }

        return $res;
    }

}
