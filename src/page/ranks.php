<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="src/page/css/ranks.css">
    <title>Ranking</title>
</head>
<body>
    <div class="ranking">
        <div class="ranking-section">
            <div class="active">
                <span>All</span>
            </div>
            <div>
                <span>Rap</span>
            </div>
            <div>
                <span>Song</span>
            </div>
            <div>
                <span>Poem</span>
            </div>
            <div>
                <span>Comedy</span>
            </div>
        </div>

        <div class="ranking-time">
            <div class="active">
                <span>Tops</span>
            </div>

            <div>
                <span>Weekly</span>
            </div>
        </div>

        <div class="contents">
            <?php require "src/element/post.php"; ?>
        </div>
    </div>
</body>
</html>