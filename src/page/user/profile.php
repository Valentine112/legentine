<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <script src="../src/js/view/PostHTML.js"></script>
    <script src="../src/js/view/Properties.js"></script>
    <script src="../src/js/view/Profile.js"></script>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/profile.css">
    <link rel="stylesheet" href="../src/element/css/post.css">
    <link rel="stylesheet" href="../src/element/css/options.css">
    <link rel="stylesheet" href="../src/element/css/properties.css">
</head>
<body>
    <div class="content config">
        <div class="category-section other-templates">
            <?php
                // Include the navbar here
                include "src/template/navbar.php";

                // Include sidebar here
                include "src/template/sidebar.php";

                // Include search here
                include "src/template/search.php";

                // Include the notice box here
                include "src/template/quick-notice.php";

                // Include the delete notice here
                include "src/template/delete-notice.php";
            ?>
        </div>

        <div class="profilePage">
            <div class="profileHead">
                <div class="profileHeadSub" id="profileHTML">
                    <!-- Profile head goes here -->
                </div>

                <div class="headerSection">
                    <div>
                        <div class="active">
                            <span>Notes</span>
                        </div>

                        <div>
                            <span>Photos</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="article-content post-controller box">
                <div class="content-loader">
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../src/js/page/user/profile.js"></script>
<script src="../src/js/view/Options.js"></script>
<script src="../src/js/general/general.js"></script>
<script src="../src/js/general/background.js"></script>
</html>