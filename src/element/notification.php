<head>
    <link rel="stylesheet" href="src/element/css/notification.css">
    <title>Notification</title>
</head>
<div class="container">
    <div class="notification">
        <div class="sub-pages">
            <h1>Notification</h1>
        </div>
        <div class="notification-boxes">
        <?php $i = 0; while($i < 4): $i++; ?>
            <div>
                <a href="">
                    <span>Valentino</span>,
                    your post titled
                    <span>Free Verse</span>
                    is among the top post
                    <span>General</span>
                    <span>(Tops)</span>
                    with a total like of
                    <span>2</span>
                    <small>2 months back</small>
                </a>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
</div>