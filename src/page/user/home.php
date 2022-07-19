<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <script src="../src/js/view/PostHTML.js"></script>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/element/css/post.css">
</head>
<body>
    <div class="content">
        <div class="category-section other-templates">
            <?php
                // Include the navbar here
                include "src/template/navbar.php";

                // Include the category section here
                include "src/template/category.php"; 

                // Include the notice box here
                include "src/template/quick-notice.php";
            ?>
        </div>

        <div class="article-content post-controller box">
            
        </div>
    </div>
</body>
<script src="../src/js/page/user/home.js"></script>
<script src="../scr/js/general/post.js"></script>
</html>