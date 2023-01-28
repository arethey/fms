<?php include 'includes/header.php';?>
<?php
    require_once "config.php";

    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $sql = "SELECT * FROM folders WHERE id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $param_id);
            
            $param_id = trim($_GET["id"]);
            
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    $folder_id = $row["id"];
                    $folder_name = $row["name"];
                    $main_folder_id = $row["folder_id"];

                    // if($row["folder_id"] != 0){
                    //     echo 'subfolders';
                    // }
                } else{
                    header("location: documents.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        $stmt->close();
        
        //$mysqli->close();
    } else{
        header("location: documents.php");
        exit();
    }
?>

<div class="container pt-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="documents.php">Documents</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $folder_name; ?></li>
            </ol>
        </nav>
        <div class="d-flex align-items-center">
            <a class="btn btn-sm btn-light" href="edit-folder.php?id=<?php echo $_GET['id']; ?>">Edit</a>
            <form action="delete-folder.php" method="post" onSubmit="return confirm('Are you sure you want to delete this folder?');">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                <input type="hidden" name="folder_id" value="<?php echo $main_folder_id; ?>" />
                <button type="submit" name="deleteFolderSubmit" class="btn btn-sm btn-light">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded p-3 mb-5">
        <div class="mb-3">
            <form action="upload.php?id=<?php echo $_GET["id"] ?>" method="post" enctype="multipart/form-data">
                <div class="d-flex align-items-center" style="gap: 1rem;">
                    <input class="form-control" type="file" id="formFile" name="file" required />
                    <input type="hidden" name="folder_id" value="<?php echo $main_folder_id; ?>" />
                    <button type="submit" name="upload" class="btn btn-sm btn-primary">Upload</button>
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
                                        <a href="delete-file.php?id='.$folder_id.'&folder_id='.$main_folder_id.'&file_id='.$row["id"].'&file='.$row["file_name"].'">delete</a>
                                    </td>
                                </tr>';
                            }
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

<?php include 'includes/footer.php';?>