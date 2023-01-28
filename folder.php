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
                <button type="submit" name="deleteFolderSubmit" class="btn btn-sm btn-light">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded p-3 mb-5">
        <div class="mb-3">
            <form action="upload.php?id=<?php echo $_GET["id"] ?>" method="post" enctype="multipart/form-data">
                <div class="d-flex align-items-center" style="gap: 1rem;">
                    <input class="form-control" type="file" id="formFile" name="file" required />
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
                                        <a href="delete-file.php?id='.$folder_id.'&file_id='.$row["id"].'&file='.$row["file_name"].'">delete</a>
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

    <div class="bg-white shadow-sm rounded p-3">
        <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Sub-folders</h4>
                <a href="new-folder.php?folder_id=<?php echo $_GET["id"] ?>" class="btn btn-sm btn-primary">New Folder</a>
            </div>
            
            <?php
                if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
                    $id = $_GET["id"];
                    $sql = "SELECT * FROM folders WHERE folder_id = $id";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo '<div class="row">';
                            while($row = $result->fetch_array()){
                                echo '<div class="col-md-1 mb-3">
                                    <a href="subfolder.php?id='.$row["id"].'" style="text-decoration: none;">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">
                                                <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z"/>
                                            </svg>
                                            <h6>' . $row["name"] . '</h6>
                                        </div>
                                    </a>
                                </div>';
                            }
                            echo '</div>';
                            $result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }else{
                    header("location: documents.php");
                }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php';?>