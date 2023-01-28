<?php
// Process delete operation after confirmation
if(isset($_GET["file_id"]) && !empty($_GET["file_id"])){
    // Include config file
    require_once "config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM files WHERE id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["file_id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $file = $_GET["file"];
            unlink("uploads/".$file);
            // Records deleted successfully. Redirect to landing page
            $id = $_GET["id"];
            $redirect = isset($_GET["folder_id"]) && !empty($_GET["folder_id"]) ? "subfolder.php?id=$id" : "folder.php?id=$id";
            header("location: $redirect");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    $stmt->close();
    
    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        $id = $_GET["id"];
        $redirect = isset($_GET["folder_id"]) && !empty($_GET["folder_id"]) ? "subfolder.php?id=$id" : "folder.php?id=$id";
        header("location: $redirect");
        exit();
    }
}
?>