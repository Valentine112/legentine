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
    <?php
        // Include the notice box here
        include "src/template/quick-notice.php";
    ?>
    
    <div class="session">
        <div class="container">
            <div>
                <div class="header">
                    <h1>Session</h1>
                </div>

                <div class="form-box">

                    <div>
                        <input type="text"
                        placeholder="title. . ."
                        aria-placeholder="title"
                        class="form title"
                        id="title" 
                        autofocus
                        value="Love"
                        >
                    </div>
                    <div>
                        <textarea
                        placeholder="Express yourself" aria-placeholder="contents here" class="form form-content" id="form-content" autocomplete="off" spellcheck="true">I saw the best minds of my generation destroyed by madness, starving hysterical naked,
            dragging themselves through the negro streets at dawn looking for an angry fix,
            angelheaded hipsters burning for the ancient heavenly connection to the starry dynamo in the machinery of night,
            who poverty and tatters and hollow-eyed and high sat up smoking in the supernatural darkness of cold-water flats floating across the tops of cities contemplating jazz,
            who bared their brains to Heaven under the El and saw Mohammedan angels staggering on tenement roofs illuminated ...</textarea>
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
                                <option value="Rap">Rap</option>
                                <option value="Song">Song</option>
                                <option value="Poem">Poem</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Story">Story</option>
                                <option value="Other" id="other">Other</option>
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
    <?php include "src/template/other-category.php"; ?>
</body>
<script src="../src/js/page/user/session.js"></script>
</html>