<?php include 'includes/header.php';?>

<?php
    require_once "config.php";

    $name = "";
    $name_err = "";
    $redirect = "documents.php";
    $folder_id = 0;

    if(isset($_GET["folder_id"]) && !empty($_GET["folder_id"])){
        $folder_id = $_GET["folder_id"];
        $redirect = "folder.php?id=$folder_id";

    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $input_name = trim($_POST["name"]);
        if(empty($input_name)){
            $name_err = "Please enter a name.";
        } else{
            $name = $input_name;
        }
        
        if(empty($name_err) && empty($address_err) && empty($salary_err)){
            $sql = "INSERT INTO folders (name, user_id, folder_id) VALUES (?, ?, ?)";
    
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("sss", $param_name, $param_user_id, $param_folder_id);

                $param_name = $name;
                $param_user_id = $_SESSION["id"];
                $param_folder_id = $folder_id;
                
                if($stmt->execute()){
                    header("location: $redirect");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            $stmt->close();
        }
        
        $mysqli->close();
    }
?>

<div class="container pt-5">
    <div class="w-100 bg-white rounded p-3 mx-auto shadow-sm" style="max-width: 500px">
        <h5 class="mb-3">New Folder</h5>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Folder Name</label>
                <input type="text" id="name" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" />
                <span class="invalid-feedback"><?php echo $name_err;?></span>
            </div>
            
            <a href="<?php echo $redirect; ?>" class="btn btn-sm btn-light">Cancel</a>
            <button type="submit" class="btn btn-sm btn-primary">Create</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php';?>