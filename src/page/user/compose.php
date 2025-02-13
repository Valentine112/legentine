<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <title>Compose</title>
    <link rel="stylesheet" href="../src/page/css/user/session.css">
</head>
<body>
    
    <div class="session config">
        <div class="container article-content">
            <div class="content-loader">
                <div></div>
            </div>
            <div>
                <div class="header">
                    <h1>Compose for <span id="composingPost"></span></h1>
                </div>

                <div class="form-box">
                    <div>
                        <textarea
                            placeholder="Express yourself"
                            aria-placeholder="contents here"
                            class="form form-content" 
                            id="form-content" 
                            autocomplete="off" 
                            spellcheck="true"
                        ></textarea>
                    </div>

                </div>

                <div class="session-extras">
                    <div style="display: block; text-align: center;">
                        <div class="compose-button">
                            <button>
                                Compose
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

        // Include the notice box here
        include "src/template/quick-notice.php";
    ?>
</body>
<script src="../src/js/page/user/compose.js"></script>
</html>