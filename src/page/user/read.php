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
    <div class="read">
        <div>
            <div class="main">
                <div class="photo">
                    <a href="">
                        <img src="../src/photo/image.jpg" alt="">
                    </a>
                </div>

                <div class="post-title">
                    <span>Free verse By Himself</span>
                </div>

                <div class="post-content">
                    <span>
                        I saw the best minds of my generation destroyed by madness, starving hysterical naked,
                        dragging themselves through the negro streets at dawn looking for an angry fix,
                        angelheaded hipsters burning for the ancient heavenly connection to the starry dynamo in the machinery of night,
                        who poverty and tatters and hollow-eyed and high sat up smoking in the supernatural darkness of cold-water flats floating across the tops of cities contemplating jazz,
                        who bared their brains to Heaven under the El and saw Mohammedan angels staggering on tenement roofs illuminated ...
                    </span>
                </div>

                <div class="reaction section">
                    <div class="reaction-box">
                        <img src="../src/icon/post-icon/star.svg" alt="" class="reaction star active" data-action="react">
                    </div>

                    <div class="reaction-count">
                        <span>1</span>
                    </div>
                </div>

                <div class="category section">
                    <div>
                        Category - <span>Poem</span>
                    </div>
                </div>

                <div class="reader section">
                    <div>
                        Readers - <span>Poem</span>
                    </div>
                </div>

                <div class="date section">
                    <div>
                        <span>Thursday, Mar 4, 22</span>
                    </div>
                </div>
            </div>

            <hr class="section">

            <div class="comment-comment">

            </div>

            <div class="comment-cover">
                <div class="comment-box">
                    <div>
                        <div class="comment-guage"></div>
                        <textarea class="comment-input" name="" id="" autocomplete="off" autofocus rows="1"></textarea>
                    </div>

                    <div>
                        <button>Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>