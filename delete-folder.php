<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteFolderSubmit'])){
    require_once "config.php";

    $folder_id = null;
    $redirect = "documents.php";
    if(isset($_POST["folder_id"]) && !empty($_POST["folder_id"])){
        $folder_id = $_POST["folder_id"];
        $redirect = "folder.php?id=$folder_id";
    };
    
    $sql = "DELETE FROM folders WHERE id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        
        $param_id = trim($_POST["id"]);
        
        if($stmt->execute()){
            header("location: $redirect");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    $stmt->close();
    
    $mysqli->close();
}
?>