<?php

/**
 * Summary for file
 * @filesource
 */

/**
 * Class dbora
 *
 *
 *
 * @author Ivan Sklempej <ivan.sklempej@gmail.com>
 * @copyright (c) 2015
 *
 * @package Utility\Database
 */
class dbora {

	/**
	 * Configuration object
	 *
	 * Config object parsed from $config_file ini file
	 *
	 * @var object
	 */
    private $config = null;
	/**
	 * Connection object
	 *
	 * Database connection object 
	 *
	 * @var object
	 */
	private $con;

    public function __construct ( $db = 'defaultdb' ) {

        $this->config = parse_ini_file('../config/config.ini.php',true);
        $this->connect( $db );


    }

    private function connect ( $db ) {
        $this->con = oci_pconnect (  $this->config[$db]['USERNAME'], $this->config[$db]['PASSWORD'],$this->config[$db]['DSN'], $this->config[$db]['CHARSET'] );
        if (!$this->con) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

    }

    public function executeSQL ( $sql ){

        $stid = oci_parse( $this->con, $sql);
        if (!$stid) {
            $e = oci_error($this->db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid, OCI_COMMIT_ON_SUCCESS );
        if (!$r) {
            $e = oci_error($stid);
            print_r($e);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        } else {

            return true;
        }


    }


    public function getArray ( $sql, $add_data = null ) {


        $stid = oci_parse( $this->con, $sql);
        if (!$stid) {
            $e = oci_error($this->db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $data = array();
        // Fetch the results of the query
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            if( $add_data != null){
                $row[$add_data['KEY']] = $add_data['VALUE'];
            }

            $data[] = $row;
        }
        oci_free_statement($stid);
        //      oci_close($this->db);
        if( ! $data ){

        }
        return $data;

    }

    /*
     *  getArrayPage
     *  Function returns $count elements from $page_no page from $sql result table
     */
    public function getArrayPage ( $sql , $page_results, $page_no, $add_data ){

        $offset = $page_no * $page_results ;
        $limit = $offset + $page_results;
        $count = $this->getCount($sql);

        if( $limit > $count[0]['COUNT'] ){
            $offset += 1;
            $limit = $count[0]['COUNT'] + 1 ;
        }
        $exec_sql = "select * from (select videos.*, rownum  rnum from ($sql) videos where rownum <= $limit ) where rnum >= $offset";

        $result = $this->getArray( $exec_sql, $add_data );
        $data = Array ();
        $page_count = $count[0]['COUNT'] / $page_results;
        $data['total'] = $count[0]['COUNT'];
        $data['page'] = $page_no;
        $data['pages'] = ceil($page_count) ;

        $data['offset'] = $page_no * $page_results;
        $data['results'] = $result;

        return $data;

    }

    public function getCount ( $sql ) {

        $tail_sql =  explode('from', $sql);

        $count_query = "select count(*) as count from $tail_sql[1]";
        return $this->getArray($count_query);

    }

}
