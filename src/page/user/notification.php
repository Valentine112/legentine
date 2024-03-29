<html lang="en">
<head> 
    <script src="../src/js/view/PostHTML.js"></script>
    <script src="../src/js/view/Properties.js"></script>
    <script src="../src/js/view/Profile.js"></script>
    <script src="../src/js/view/Notification.js"></script>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/profile.css">
    <link rel="stylesheet" href="../src/element/css/post.css">
    <link rel="stylesheet" href="../src/element/css/options.css">
    <link rel="stylesheet" href="../src/element/css/properties.css">
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="../src/page/css/user/notification.css">
    <link rel="stylesheet" href="../src/page/css/user/pageHead.css">
    <title>Notification</title>
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

        <div class="notification">
            <div class="article-content post-controller box">

                <header class="pageHead">
                    <h2>Notification</h2>
                </header>

                <div id="postCover">
                    <!-- NOTIFICATION GOES HERE -->

                    <div class="post-notification">
                        <header>Tops</header>
                        <div class="post-notification-cover">
                            <div>
                                <span>Your post hshs shshss shsjsj <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span></span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>
                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>
                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>
                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>
                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>

                            <div>
                                Your post <span class="title">"Hello there"</span> is among the top <span class="song category">Song</span> <span class="section">"this week"</span>
                            </div>
                        </div>
                    </div>

                    <div class="comments-notification" id="comments-notification">

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
<script src="../src/js/page/user/notification.js"></script>
<script src="../src/js/view/Options.js"></script>
<script src="../src/js/general/general.js"></script>
<script src="../src/js/general/background.js"></script>
</html>