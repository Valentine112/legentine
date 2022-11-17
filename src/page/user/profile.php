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
    <link rel="stylesheet" href="../src/cropperjs/dist/cropper.min.css">
    <script src="../src/cropperjs/dist/cropper.min.js" type='module'></script>
    <title>Profile</title>
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
                    <div class="headerSectionSub">
                        <div class="active action" data-type="notes" data-action="profileSection">
                            <span>Notes</span>
                        </div>

                        <div class="action" data-type="photos" data-action="profileSection">
                            <span>Photos</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="article-content post-controller box">
                <div id="postCover">

                </div>

                <div class="content-loader">
                    <div></div>
                </div>

                <div class="photosCover">
                    <div class="photosSub" id="photoSub">
                        <!-- The photos goes here -->
                    </div>
                </div>

            </div>
        </div>

        <div>
            <?php
                // Include upload here
                include "src/template/upload.php";
            ?>
        </div>

        <!-- Upload photo here -->
        <div class="uploadPhotoBox">
            <div class="uploadPhoto">
                <img src="../src/icon/profile/camera.svg" alt="" id="uploadPhoto" onclick="showUpload('uploadPicture')">
            </div>
        </div>

        <div class="viewImageBox" data-action="closeImage">
            <div class="imageControl">
                <div data-action="closeImage" class="closeImage">
                    <span>Close</span>
                </div>

                <div class="scrollImagePage">
                    <span id="currentImage">1</span>
                    /
                    <span id="totalImage">2</span>
                </div>

                <div class="viewImages">
                    <div class="imageCover">
                        <img src="../src/photo/LT-IMG-20220408-WA00239963 22_09_23.jpeg" alt="">
                    </div>
                    <div class="imageCover">
                        <img src="../src/photo/LT-IMG-20220408-WA00239963 22_09_23.jpeg" alt="">
                    </div>
                </div>
                <div class="imageOptions">
                    <button data-action="deleteImage">Delete</button>
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