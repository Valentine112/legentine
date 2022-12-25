<html lang="en">
<head> 
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
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="../src/page/css/user/pinned.css">
    <link rel="stylesheet" href="../src/page/css/user/pageHead.css">
    <title>Setup</title>
</head>
<body>
    <div class="config content">

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

        <div class="pinnedHead">
            <div class="article-content post-controller box">

                <header class="pageHead">
                    <h2>Pinned</h2>
                </header>

                <div id="postCover">
                    <div class="pinnedUsers">
                        <div>
                            <a href="">
                                <img src="../src/photo/LT-Screenshot_20220323-0349554752 22_09_26898 22_12_24.jpeg" alt="">
                            </a>

                        </div>

                        <div class="name">
                            <a href="">
                                <div>Ngene Valentine</div>
                                <span>Himself</span>
                            </a>
                        </div>

                        <div>
                            <span>Unpin</span>
                        </div>
                    </div>
                    
                </div>

                <div class="content-loader">
                    <div></div>
                </div>

            </div>
        </div>

    </div>

    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>
    
</body>
<!--<script src="../src/js/page/user/saved.js"></script>-->
<script src="../src/js/view/Options.js"></script>
<script src="../src/js/general/general.js"></script>
<script src="../src/js/general/background.js"></script>
</html>