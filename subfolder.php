<?php include 'includes/header.php';?>
<?php
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Prepare a select statement
        $sql = "SELECT *, folders.name AS main_folder_name, folders.id AS main_folder_id FROM subfolders INNER JOIN folders ON subfolders.folder_id = folders.id WHERE subfolders.id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = trim($_GET["id"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $folder_id = $row["id"];
                    $folder_name = $row["name"];
                    $main_folder_name = $row["main_folder_name"];
                    $main_folder_id = $row["main_folder_id"];
                } else{
                    // URL doesn't contain valid id parameter. Redirect to error page
                    header("location: documents.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        // $mysqli->close();
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: documents.php");
        exit();
    }
?>

<div class="container pt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="documents.php">Documents</a></li>
            <li class="breadcrumb-item"><a href="folder.php?id=<?php echo $main_folder_id; ?>"><?php echo $main_folder_name; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $folder_name; ?></li>
        </ol>
    </nav>

    <div class="bg-white shadow-sm rounded p-3 mb-5">
        <div class="mb-3">
            <form action="upload.php?id=<?php echo $_GET["id"] ?>&redirect=subfolder.php?id=<?php echo $_GET["id"]; ?>" method="post" enctype="multipart/form-data">
                <div class="d-flex align-items-center" style="gap: 1rem;">
                    <input class="form-control" type="file" id="formFile" name="file" required />
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">File Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Size</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $id = $_GET["id"];
                    $sql = "SELECT * FROM files WHERE folder_id = $id";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            while($row = $result->fetch_array()){
                                echo '<tr>
                                    <td>'.$row["file_name"].'</td>
                                    <td>'.$row["type"].'</td>
                                    <td>'.$row["size"].'</td>
                                    <td>
                                        <a class="mr-2" href="download.php?file='.$row["file_name"].'">download</a>
                                        <a href="delete-file.php?id='.$folder_id.'&file_id='.$row["id"].'&file='.$row["file_name"].'&redirect=subfolder.php?id='.$_GET["id"].'">delete</a>
                                    </td>
                                </tr>';
                            }
                            // Free result set
                            $result->free();
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>