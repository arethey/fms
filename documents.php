<?php include 'includes/header.php';?>
<div class="container pt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Documents</li>
        </ol>
    </nav>

    <div class="bg-white shadow-sm rounded p-3">
    <?php
        require_once "config.php";

        $sql = "SELECT * FROM folders WHERE folder_id = 0";
        if($result = $mysqli->query($sql)){
            if($result->num_rows > 0){
                echo '<div class="row">';
                while($row = $result->fetch_array()){
                    echo '<div class="col-md-1 mb-3">
                        <a href="folder.php?id='.$row["id"].'" style="text-decoration: none;">
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
    ?>
    </div>
</div>
<?php include 'includes/footer.php';?>