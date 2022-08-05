<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <link rel="stylesheet" href="../src/page/css/user/read.css">
    <script src="../src/js/view/Comment.js"></script>
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
                    <!-- Comments are rendered here -->
                    
                </div>
            </div>

            <div class="comment-cover">
                <div class="comment-holder">
                    <div>
                        <div class="comment-input" contenteditable="true" data-placeholder="comment" id="comment-value"></div>
                    </div>

                    <div>
                        <button data-action="create_comment">Send</button>
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