<?php
    if(isset($_POST['upload'])){
        require_once "config.php";

        $id = $_GET["id"];
        
        // $file = rand(1000, 100000)."-".$_FILES['file']['name'];
        $file = $_FILES['file']['name'];
        $file_loc = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $folder='uploads/';

        $new_size = $file_size/1024;
        $new_file_name = strtolower($file);
        $final_file = str_replace(' ','_',$new_file_name);

        if(move_uploaded_file($file_loc, $folder.$final_file)){
            // Prepare an insert statement
            $sql = "INSERT INTO files (file_name, type, size, folder_id) VALUES (?, ?, ?, ?)";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssss", $final_file, $file_type, $new_size, $id);
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Redirect to login page
                    $redirect = isset($_POST["folder_id"]) && !empty($_POST["folder_id"]) ? "subfolder.php?id=$id" : "folder.php?id=$id";
                    header("location: $redirect");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    }
?>