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
                require_once "config.php";

                $id = $_SESSION["id"];
                $sql = "SELECT * FROM folders WHERE user_id = $id AND folder_id = 0";
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
                    <a href="new-folder.php" class="dropdown-item">New Folder</a>
                </li>
          </ul>
        </li>
      </ul>

      
      <a href="logout.php" class="btn btn-sm">Sign Out</a>
    </div>
  </div>
</nav>