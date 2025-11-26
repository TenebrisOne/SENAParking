<?php
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $conn;
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "senaparking_test_db";

    protected function setUp(): void
    {
        // Connect to test database
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Create a simple table for testing
        $sql = "CREATE TABLE IF NOT EXISTS test_table (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL
        )";
        $this->conn->query($sql);
    }

    protected function tearDown(): void
    {
        // Clean up
        $this->conn->query("DROP TABLE IF EXISTS test_table");
        $this->conn->close();
    }

    public function testConnection()
    {
        $this->assertInstanceOf(mysqli::class, $this->conn);
        $this->assertNull($this->conn->connect_error);
    }

    public function testInsertAndSelect()
    {
        $name = "Test User";
        $sql = "INSERT INTO test_table (name) VALUES ('$name')";
        $this->assertTrue($this->conn->query($sql));

        $result = $this->conn->query("SELECT * FROM test_table WHERE name='$name'");
        $this->assertEquals(1, $result->num_rows);
        
        $row = $result->fetch_assoc();
        $this->assertEquals($name, $row['name']);
    }
}
