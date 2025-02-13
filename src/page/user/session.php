<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        Head::tags(); 
    ?>
    <title>Session</title>
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
                    <h1>Space</h1>
                </div>

                <div class="form-box">

                    <div>
                        <input 
                            type="text"
                            placeholder="title. . ."
                            aria-placeholder="title"
                            class="form title"
                            id="title" 
                            autofocus
                        >
                    </div>
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
                    <div>
                        <div class="session-privacy">
                            <input type="checkbox" name="check" id="check" class="check">
                            <label for="check" id="privacy" onclick="privacy(this)">Public</label>
                        </div>

                        <div class="session-category">
                            <select name="category" id="category" onchange="other_category(this)">
                                <option value="Thoughts">Thoughts</option>
                                <option value="Music">Music</option>
                                <option value="Poem">Poem</option>
                                <option value="Story">Story</option>
                                <!-- <option value="Comedy">Comedy</option>
                                <option value="Story">Story</option> -->
                                <!--<option value="Other" id="other">Other</option>-->
                            </select>
                        </div>

                        <div class="session-button">
                            <button>
                                Done
                            </button>
                        </div>
                    </div>

                    <div class="session-notice">
                        <div>
                            <span>Note! Once private, it can never go public again</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

        // Include the notice box here
        include "src/template/quick-notice.php";

        // // Other category pop up
        // include "src/template/other-category.php";
    ?>
</body>
<script src="../src/js/page/user/session.js"></script>
</html>