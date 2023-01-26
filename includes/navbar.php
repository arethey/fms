<?php
// Define variables and initialize with empty values
$name = "";
$name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newFolderSubmit'])){
    $hasFolderId = isset($_POST["folder_id"]) && !empty(trim($_POST["folder_id"]));
    $folder_id = $hasFolderId ? $_POST["folder_id"] : null;

    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a folder name.";
    } else{
        // Prepare a select statement
        if($hasFolderId){
            $sql = "SELECT id FROM subfolders WHERE name = ?";
        }else{
            $sql = "SELECT id FROM folders WHERE name = ?";
        }
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_name);
            
            // Set parameters
            $param_name = trim($_POST["name"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $name_err = "This folder name is already taken.";
                } else{
                    $name = trim($_POST["name"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Check input errors before inserting in database
    if(empty($name_err)){
        // Prepare an insert statement
        if($hasFolderId){
            $sql = "INSERT INTO subfolders (name, user_id, folder_id) VALUES (?, ?, ?)";
        }else{
            $sql = "INSERT INTO folders (name, user_id) VALUES (?, ?)";
        }
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            if($hasFolderId){
                $stmt->bind_param("sss", $name, $_SESSION["id"], $folder_id);
            }else{
                $stmt->bind_param("ss", $name, $_SESSION["id"]);
            }
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                if($hasFolderId){
                    header("location: folder.php?id=$folder_id");
                }else{
                    header("location: documents.php");
                }
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    // $mysqli->close();
}
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="index.php">File Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Documents
          </a>
          <ul class="dropdown-menu">
            <li><a class='dropdown-item' href='documents.php'>All</a></li>
            <?php
                $id = $_SESSION["id"];
                $sql = "SELECT * FROM folders WHERE user_id = $id";
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0){
                        while($row = $result->fetch_array()){
                            echo "<li><a class='dropdown-item' href='folder.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></li>";
                        }
                        $result->free();
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            ?>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                        New Folder
                    </button>
                </li>
          </ul>
        </li>
      </ul>

      
      <a href="logout.php" class="btn btn-sm">Sign Out</a>
    </div>
  </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="newFolderModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newFolderModalLabel">New Folder</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label class="form-label">Folder Name</label>
                    <input type="text" id="folderName" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" required >
                    <span class="invalid-feedback"><?php echo $name_err;?></span>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="newFolderSubmit" class="btn btn-primary" value="Create">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>