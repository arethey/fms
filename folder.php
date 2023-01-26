<?php include 'includes/header.php';?>
<?php
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Prepare a select statement
        $sql = "SELECT * FROM folders WHERE id = ?";
        
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
            <li class="breadcrumb-item active" aria-current="page"><?php echo $folder_name; ?></li>
        </ol>
    </nav>

    <!-- <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newSubFolderModal">
        Add Folder
    </button> -->

    <!-- Modal -->
    <!-- <div class="modal fade" id="newSubFolderModal" tabindex="-1" aria-labelledby="newSubFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newSubFolderModalLabel">New Folder</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label class="form-label">Folder Name</label>
                            <input type="text" id="folderName" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" required >
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <input type="hidden" name="folder_id" value="<?php echo $_GET["id"]; ?>" >
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="newFolderSubmit" class="btn btn-primary" value="Create">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    

    <div class="bg-white shadow-sm rounded p-3 mb-5">
        <div class="mb-3">
            <form action="upload.php?id=<?php echo $_GET["id"] ?>" method="post" enctype="multipart/form-data">
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
                                        <a href="delete-file.php?id='.$folder_id.'&file_id='.$row["id"].'&file='.$row["file_name"].'">delete</a>
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

    <div class="bg-white shadow-sm rounded p-3">
        <div>
            <h4>Sub-folders</h4>
            <?php
                if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
                    $id = $_GET["id"];
                    $sql = "SELECT * FROM subfolders WHERE folder_id = $id";
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
                            // Free result set
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