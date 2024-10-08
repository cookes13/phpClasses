<?php
class Database {
    private $servername = "{Server Address}";
    private $username = "{Database User Username}";
    private $password = "{Database User Password}";
    private $database = "{Database Name}";
    private $pdo;
    private $lastQuery;
    private $error;
    private $lastInsertId;  // Add a property to store the last inserted ID

    public function __construct() {
        try {
            // Establish the database connection using PDO
            $this->pdo = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function __destruct() {
        // Close the database connection
        $this->pdo = null;
    }

    public function fetch($tableName, $columns = array(), $conditions = array(), $orderBy = array()) {
        try {
            // Construct the SELECT query with optional conditions and columns
            if (empty($columns)) {
                $columnString = '*';
            } else {
                $columnString = implode(", ", $columns);
            }

            $query = "SELECT $columnString FROM $tableName";
            $values = array();
            if (!empty($conditions)) {
                $query .= " WHERE ";
                $i = 0;
                foreach ($conditions as $column => $value) {
                    if (is_array($value)) {
                        if (count($value) > 0) {
                            $query .= "(";
                            for ($j = 0; $j < count($value); $j++) {
                                if ($j > 0) {
                                    $query .= " OR ";
                                }
                                if (strpos($value[$j], '!') === 0) {
                                    $value[$j] = substr($value[$j], 1); // Remove the '!' sign
                                    $query .= "$column != ?";
                                } else {
                                    $query .= "$column = ?";
                                }
                                $values[] = $value[$j];
                            }
                            $query .= ")";
                        }
                    } else {
                        if ($i > 0) {
                            $query .= " AND ";
                        }
                        if (strpos($value, '!') === 0) {
                            $value = substr($value, 1); // Remove the '!' sign
                            $query .= "$column != ?";
                        } else {
                            $query .= "$column = ?";
                        }
                        $values[] = $value;
                    }
                    $i++;
                }
            }
            // Add ORDER BY clause
            if (!empty($orderBy)) {
                $query .= " ORDER BY ";
                for ($k = 0; $k < count($orderBy); $k++) {
                    if ($k > 0) {
                        $query .= ", ";
                    }
                    $column = $orderBy[$k];
                    $direction = "ASC";
                    if (strpos($column, '!') === 0) {
                        $column = substr($column, 1); // Remove the '!' sign
                        $direction = "DESC";
                    }
                    $query .= "$column $direction";
                }
            }

            // Prepare and execute the query, returning the fetched results
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Query failed: " . $this->error);
        }
    }

    public function insert($tableName, $data) {
        try {
            // Construct the INSERT query with column names and placeholders
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            $values = array_values($data);
            $query = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
            // Prepare and execute the query
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            $this->lastQuery = $query;
            $this->lastInsertId = $this->pdo->lastInsertId();  // Store the last inserted ID
            return $this->lastInsertId;  // Return it here, but also store it for later retrieval
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Insert failed: " . $this->error);
        }
    }

    public function update($tableName, $data, $conditions = array()) {
        try {
            // Construct the UPDATE query with SET values and optional conditions
            $setValues = array();
            foreach ($data as $key => $value) {
                $setValues[] = "$key = ?";
            }
            $values = array_values($data);
            $query = "UPDATE $tableName SET " . implode(", ", $setValues);
            if (!empty($conditions)) {
                $query .= " WHERE ";
                $i = 0;
                foreach ($conditions as $key => $value) {
                    if ($i > 0) {
                        $query .= " AND ";
                    }
                    $query .= "$key = ?";
                    $values[] = $value;
                    $i++;
                }
            }
            // Prepare and execute the query, returning the number of updated rows
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            $this->lastQuery = $query;
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Update failed: " . $this->error);
        }
    }

    public function getError() {
        return $this->error;
    }

    public function getLastQuery() {
        return $this->lastQuery;
    }

    // Add this function to return the last inserted ID
    public function getLastInsertedId() {
        return $this->lastInsertId;
    }
}

?>
