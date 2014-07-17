<?php

namespace TinyFw\DB;

class Mysql
{
    /**
     * Singleton var
     * 
     * @var Mysql
     */
    private static $instance = null;


    /**
     *
     * @param array $config            
     * @return Mysql
     */
    public static function &instance($config = null)
    {
        if (self::$instance == null) {
            self::$instance = new self ( $config );
        }
        return self::$instance;
    }
    private $host = null;
    private $user = null;
    private $pass = null;
    private $database = null;
    private $connection = null;
    private $lastQuery = null;
    private $lastError = null;
    private $lastResult = null;
    private $records = 0;
    private $affected = 0;
    private $arrayedResult = null;


    public function __construct($config = null)
    {
        $this->readConfigAndConnect ( $config );
    }

    private function readConfigAndConnect($config = null) // Always read from env
    {
        $this->host = isset ( $config ['host'] ) ? $config ['host'] : null;
        $this->user = isset ( $config ['user'] ) ? $config ['user'] : null;
        $this->pass = isset ( $config ['pass'] ) ? $config ['pass'] : null;
        if ($this->host != null && $this->user != null && $this->pass !== null) {
            $this->database = isset ( $config ['database'] ) ? $config ['database'] : null;
            $this->connect ();
        } else {
            Throw new \Exception ( 'Invalid database infomation.' );
        }
    }


    private function connect($persistent = false)
    {
        if (! is_resource ( $this->connection )) {
            if ($persistent == false) {
                $this->connection = mysql_connect ( $this->host, $this->user, $this->pass );
            } else {
                $this->connection = mysql_pconnect ( $this->host, $this->user, $this->pass );
            }
            $this->useDB ();
        }
        
        return $this->connection;
    }


    private function useDB()
    {
        if ($this->database != null) {
            return mysql_select_db ( $this->database, $this->connection );
        }
        throw new \Exception ( "Database '{$this->database}' does not exist." );
    }


    /**
     * Sercure data
     * 
     * @param Mixed $data            
     * @return string
     */
    public function secureData($data)
    {
        $this->connect ();
        if (is_array ( $data )) {
            foreach ( $data as $key => $val ) {
                if (! is_array ( $data [$key] )) {
                    $data [$key] = mysql_real_escape_string ( $data [$key], $this->connection );
                }
            }
        } else {
            $data = mysql_real_escape_string ( $data, $this->connection );
        }
        return $data;
    }


    public function update($table, $data, $where)
    {
        $data = $this->secureData ( $data );
        
        $data = $this->secureData ( $data );
        $where = $this->secureData ( $where );
        
        $query = "UPDATE `{$table}` SET ";
        
        foreach ( $data as $key => $value ) {
            $query .= "`{$key}` = '{$value}', ";
        }
        
        $query = substr ( $query, 0, - 2 );
        
        // WHERE
        $query .= $this->whereSQL ( $where );
        
        return $this->executeQuery ( $query );
    }


    private function whereSQL($data, $like = false)
    {
        if (! is_array ( $data ) || count ( $data ) == 0) {
            return '';
        }
        
        $where = ' WHERE ';
        foreach ( $data as $key => $value ) {
            if ($like == false) {
                $where .= "`{$key}` = '{$value}' AND ";
            } else {
                $where .= "`{$key}` like '%{$value}%' AND ";
            }
        }
        $where = substr ( $where, 0, - 5 );
        return $where;
    }


    public function insert($table, $data)
    {
        $data = $this->secureData ( $data );
        
        $query = "INSERT INTO `{$table}` SET ";
        foreach ( $data as $key => $value ) {
            $query .= "`{$key}` = '{$value}', ";
        }
        $query = substr ( $query, 0, - 2 );
        return $this->executeQuery ( $query );
    }


    public function delete($table, $where)
    {
        if ($where != null) {
            $sql = "DELETE FROM {$table}" . $this->whereSQL ( $where );
            return $this->executeQuery ( $sql );
        }
        return false;
    }


    public function records()
    {
        return $this->records;
    }

    public function lastQuery()
    {
        return $this->lastQuery;
    }

    public function executeQuery($query, $data = null)
    {
        $this->lastQuery = $query;
        $this->connect ();
        $this->arrayedResult = null;
        if ($this->lastResult = mysql_query ( $query, $this->connection )) {
            $this->records = @mysql_num_rows ( $this->lastResult );
            $this->affected = @mysql_affected_rows ( $this->connection );
            if ($this->records > 0) {
                if ($this->records == 1) {
                    $this->arrayedResult = mysql_fetch_assoc ( $this->lastResult ) or die ( mysql_error ( $this->connection ) );
                } else {
                    while ( $row = mysql_fetch_assoc ( $this->lastResult ) ) {
                        $this->arrayedResult [] = $row;
                    }
                }
                return $this->arrayedResult;
            } else {
                return true;
            }
        } else {
            $this->lastError = mysql_error ( $this->connection );
            return false;
        }
    }


    /**
     * Simple select
     * 
     * @param string $from            
     * @param array $where            
     * @param boolean $like            
     * @param array $orderBy            
     * @param int $limit            
     * @param int $offset            
     * @return Ambigous <boolean, multitype:>
     */
    function select($cols, $from, $where = null, $like = false, $orderBy = null, $limit = null, $offset = null)
    {
        if (is_array ( $cols )) {
            $cols = implode ( ', ', $cols );
            $cols = substr ( $cols, 0, strlen ( $cols ) - 3 );
        }
        
        $query = "SELECT {$cols} FROM `{$from}`";
        
        if (is_array ( $where )) {
            $query .= $this->whereSQL ( $where, $like );
        }
        
        if (is_array ( $orderBy )) {
            $OB = " ORDER BY ";
            foreach ( $orderBy as $k => $v ) {
                $v = strtoupper ( $v );
                $OB .= "`{$k}` {$v}, ";
            }
            $OB = substr ( $OB, 0, strlen ( $OB ) - 3 );
            $query .= $OB;
        }
        
        if ($limit != null) {
            if ($offset != null) {
                $query .= " LIMIT {$offset}, {$limit}";
            } else {
                $query .= " LIMIT {$limit}";
            }
        }
        
        return $this->executeQuery ( $query );
    }


    public function lastInsertId()
    {
        return mysql_insert_id ( $this->connection );
    }


    public function isExists($table, $conditions = null)
    {
        if ($this->count ( $table, $conditions ) > 0) {
            return true;
        }
        return false;
    }


    public function lastError()
    {
        return $this->lastError;
    }


    public function count($table, $conditions = null)
    {
        $sql = "SELECT count(*) from {$table}" . $this->whereSQL ( $conditions );
        $this->executeQuery ( $sql );
        return $this->arrayedResult ["count(*)"];
    }


    public function affectedRows()
    {
        return $this->affected;
    }
}