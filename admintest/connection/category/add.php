<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding");
header("Content-type: application/json");

include_once("../conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON data from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract the 'name' field from the JSON data
    $name = $data['name'];

    try {
        // Establish a PDO connection
        $conn = new PDO("mysql:host=localhost;dbname=react-project", "root", "");
        
        // Set PDO to throw exceptions on errors
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare an SQL statement with a named placeholder
        $sql = "INSERT INTO categorie (name) VALUES (:name)";
        $stmt = $conn->prepare($sql);

        // Bind the value to the named placeholder
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Data insertion successful
        $response = array(
            "success" => true,
            "message" => "Data inserted successfully."
        );
    } catch (PDOException $e) {
        // Handle database errors
        $response = array(
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        );
    }

    // Close the database connection
    $conn = null;

    // Return a JSON response
    echo json_encode($response);
} else {
    // Invalid request method
    $response = array(
        "success" => false,
        "message" => "Invalid request method."
    );
    echo json_encode($response);
}
?>
