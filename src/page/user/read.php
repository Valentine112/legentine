<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/read.css">
    <title>Read</title>
</head>
<body>
    <div class="read post-body">
        <div>
            <div class="main">
                <div class="photo">
                    <a href="">
                        <img src=" " alt="">
                    </a>
                </div>

                <div class="post-header">
                    <span class="post-title">
                        <!-- The content goes here -->
                    </span>
                    <span>By</span>
                    <span class="post-username">

                    </span>
                </div>

                <div class="post-content">
                    <span>
                        <!-- The content goes here -->
                    </span>
                </div>

                <div class="reaction section">
                    <div class="reaction-box reaction-cover">
                        <!-- Reaction goes here -->
                    </div>

                    <div class="reaction-count">
                        <span></span>
                    </div>
                </div>

                <div class="category section">
                    <div>
                        Category - <span></span>
                    </div>
                </div>

                <div class="reader section">
                    <div>
                        Readers - <span></span>
                    </div>
                </div>

                <div class="date section">
                    <div>
                        <span></span>
                    </div>
                </div>

                <div class="feature section">
                    <div>
                        <button>Feature</button>
                    </div>
                </div>
            </div>

            <hr class="section">

            <div class="comments section">
                <div>
                    <header>Comments</header>
                </div>

                <div class="comment-content">
                    <?php $i = 0; while($i < 3): $i++; ?>
                    <div class="comment-box">
                        <div class="comment-details">
                            <div>
                                <a href="">
                                    <img src="../src/photo/image.jpg" alt="">
                                </a>
                            </div>

                            <div>
                                <span class="username">Himself</span><br>
                                <span class="user-comment">I have a day to make it all work, but i have forever to make it. What's really needed for this because my mind is so clouded</span>
                            </div>
                        </div>

                        <div class="comment-options">
                            <div>
                                <span>Reply</span>
                            </div>

                            <div>
                                <span>Edit</span>
                            </div>
                            
                            <div>
                                <span>Delete</span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="comment-cover">
                <div class="comment-holder">
                    <div>
                        <div class="comment-input" contenteditable="true" data-placeholder="comment"></div>
                    </div>

                    <div>
                        <button>Send</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include the notice box here -->
        <?php include "src/template/quick-notice.php"; ?>
    </div>
</body>
<script src="../src/js/page/user/read.js"></script>
<script src="../src/js/general/general.js"></script>
</html>