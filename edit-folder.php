<?php include 'includes/header.php';?>

<?php
require_once "config.php";

$name = "";
$name_err = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    $folder_id = $_POST["folder_id"];
    $redirect = $folder_id == "0" ? "folder.php?id=$id" : "subfolder.php?id=$id";
    
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } else{
        $name = $input_name;
    }
    
    if(empty($name_err)){
        $sql = "UPDATE folders SET name=? WHERE id=?";
 
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("si", $param_name, $param_id);
            
            $param_name = $name;
            $param_id = $id;
            
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
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);
        
        $sql = "SELECT * FROM folders WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $param_id);
            $param_id = $id;
            
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $name = $row["name"];
                    $folder_id = $row["folder_id"];
                } else{
                    header("location: $redirect");
                    exit();
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
        
        $mysqli->close();
    }  else{
        header("location: $redirect");
        exit();
    }
}
?>

<div class="container pt-5">
    <div class="w-100 bg-white rounded p-3 mx-auto shadow-sm" style="max-width: 500px">
        <h5 class="mb-3">Update Folder</h5>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Folder Name</label>
                <input type="text" id="name" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" />
                <span class="invalid-feedback"><?php echo $name_err;?></span>
            </div>
            
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="hidden" name="folder_id" value="<?php echo $folder_id; ?>"/>
            <a href="<?php echo $folder_id == 0 ? "folder.php?id=$id" : "subfolder.php?id=$id"; ?>" class="btn btn-sm btn-light">Cancel</a>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php';?>