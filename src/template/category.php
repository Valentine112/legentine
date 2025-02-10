<head>
    <style>
        .category{
            width: 100%;
            white-space: nowrap;
            overflow-y: scroll;
            text-align: center;
            font-family: var(--theme-font);
        }
        .category > div{
            display: inline-block;
            vertical-align: middle;
            width: 8%;
            background-color: #f1f1f1;
            border-radius: 20px;
            padding: 10px 0 10px 0;
            margin: 0 1.5% 0 1.5%;
            text-align: center;
            color: #000;
            font-size: 17px;
            cursor: pointer;
        }
        .category .active{
            background-color: #fc8290;
            color: #fff;
        }

        @media screen and (max-width: 599px) {
            .category > div{
                width: 20%;
                padding: 6px 0 6px 0;
            }
        }

        @media screen and (min-width: 600px) and (max-width: 767px) {
            .category > div{
                width: 15%;
            }
        }
    </style>
</head>
<div class="category">
    <div class="active action" data-action="category" value="all">
        <span>All</span>
    </div>
    <div class="action" data-action="category" value="thoughts">
        <span>Thoughts</span>
    </div>
    <div class="action" data-action="category" value="music">
        <span>Music</span>
    </div>
    <div class="action" data-action="category" value="poem">
        <span>Poem</span>
    </div>
    <div class="action" data-action="category" value="story">
        <span>Story</span>
    </div>
    <!-- <div class="action" data-action="category" value="comedy">
        <span>Comedy</span>
    </div>
    <div class="action" data-action="category" value="story">
        <span>Story</span>
    </div> -->
    <!--<div class="action" data-action="category" value="art">
        <span>Art</span>
    </div>-->
</div>