<?php
class Database {
    private $conn;
    public static function createConnection() {
        $cfg = include("config.php");

        $mysqli = mysqli_connect($cfg["DB_HOST"], $cfg["DB_USER"], $cfg["DB_PASS"], $cfg["DB_NAME"]);
        
        if(!$mysqli) {
            // TODO: error handler
            echo("Hibás adatbázis kapcsolat");
            die();
        }
        
        $mysqli->set_charset("utf8mb4");

        return $mysqli;
    }

    public function __construct() {
        $this->conn = Database::createConnection();
    }
    public function __destruct() {
        $this->conn->close();
    }

    public function simpleStmt($sql, $bind_str="", $bind_params=[]) {
        
        $stmt = $this->conn->prepare($sql);

        if(!$stmt) {
            // TODO: error handler
            error_log("Error during preparation of query($sql).");
            echo "Error during query preparation";
            die();
        }

        if($bind_str != "" && !empty($bind_params)) {
            $stmt->bind_param($bind_str, ...$bind_params);

            if(!$stmt) {
                // TODO: error handler
                $params_error = print_r($bind_params, true);
                error_log("Invalid bind params during simpleStmt. sql($sql). params($params_error). bindstr($bind_str)");
                echo "Invalid bind params during query exectuion";
                die();
            }
        }

        if(!$execute_result = $stmt->execute()) {
            // TODO: error handler
            $error_message = $this->conn->error;
            $params_error = print_r($bind_params, true);
            error_log("Error during query execution. error message: $error_message sql($sql), params($bind_str), bind_params($params_error)");
            echo "Error during query execution.";
            die();
        } else {
            return $stmt->get_result();
        }

    }

}


?>