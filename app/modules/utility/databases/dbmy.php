<?php

/**
* @author ivansklempej
*/

class dbmy extends mysqli{


    private $config;

    public function __construct ( $db = 'defaultdb' ) {

        $this->config = parse_ini_file('../config/config.ini.php',true);
        $this->connecttodb( $db );


    }

    public function connecttodb ( $db ) {

        parent::init();

        if (! parent::options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
            die('Setting MYSQLI_INIT_COMMAND failed');
        }
        if (!parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
            die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
        }

        if (!parent::real_connect( $this->config[$db]['hostname'], $this->config[$db]['username'], $this->config[$db]['password'], $this->config[$db]['dbname'])) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }
    }

    public function getDB(){

        return parent;
    
    }

    public function getArray( $sql  ){

        $data = array();

        /* Select queries return a resultset */
        if ($result = parent::query($sql)) {

            while($obj = $result->fetch_array(  MYSQLI_ASSOC )){ 

                array_push($data, $obj);

            }

            /* free result set */
            $result->close();
        }

        return $data;

    }
    public function executeSQL( $query ){

        
        print "exec: $query \n";
        if( ! $stmt = parent::prepare($query)){

            print "problem ". $stmt->error ."\n";

        }
        if( ! $stmt->execute()){

            print "problem ". $stmt->error."\n";
        
        }
        parent::commit();

        return true;
    
    }
}
