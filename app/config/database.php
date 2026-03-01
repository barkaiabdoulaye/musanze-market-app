<?php
/**
 * Database Connection Class
 * Uses MySQLi with prepared statements
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;
    private $connected = false;

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Get database instance (singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            // Check connection
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            // Set charset
            $this->connection->set_charset("utf8mb4");
            $this->connected = true;
            
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    /**
     * Get MySQLi connection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Prepare SQL statement
     */
    public function prepare($sql) {
        if (!$this->connected) {
            $this->connect();
        }
        return $this->connection->prepare($sql);
    }

    /**
     * Execute query
     */
    public function query($sql) {
        if (!$this->connected) {
            $this->connect();
        }
        return $this->connection->query($sql);
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    /**
     * Escape string for safe use
     */
    public function escapeString($string) {
        if (!$this->connected) {
            $this->connect();
        }
        return $this->connection->real_escape_string($string);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollback();
    }

    /**
     * Close connection
     */
    public function __destruct() {
        if ($this->connected && $this->connection) {
            $this->connection->close();
        }
    }
}
?>