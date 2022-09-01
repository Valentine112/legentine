<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search</title>
    <?php 
        use Src\Config\Head; 
        Head::tags(); 
    ?>

    <style>
        *{
            font-family: var(--theme-font);
        }
        .container{
            margin-top: 5px;
        }
        nav{
            padding: 5px;
        }
        nav div{
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .navCover > div{
            flex: 1;
            cursor: pointer;
        }
        .navCover .active{
            border-bottom: 2px solid var(--theme-color);
        }

        main{
            padding: 5px;
        }

        /* Fetch the searched */

        .search-result a{
            color: #000;
            text-decoration: none;
        }
        .search-result .result-box{
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 5px;
            padding: 5px 0;
            font-family: var(--theme-font-3);
        }
        .result-section .search-result .person{
            padding: 10px 0;
        }
        .result-section .search-result .post{
            padding: 10px 0;
        }
        .search-result .person{
            border-radius: 10px;
            display: flex;
            justify-content: left;
            align-items: center;
        }
        .search-result .post{
            margin-left: 10px;
        }
        .search-result .person > div:first-child{
            flex: 1;
            text-align: center;
        }
        .search-result .person > div:last-child{
            flex: 5;
        }
        .search-result img{
            height: 42px;
            width: 42px;
            border-radius: 50px;
            margin: auto;
            vertical-align: middle;
        }
        .search-result .username{
            font-size: 14px;
            color: #444;
        }
        .search-result .post .post-content{
            margin-top: 0;
            font-size: 14px;
            color: #5e5e5e;
        }

        /* END */
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <div class="navCover">
                <div class="active filter" data-type="all">
                    <span>All</span>
                </div>
                
                <div data-type="people" class="filter">
                    <span>People</span>
                </div>

                <div data-type="post" class="filter">
                    <span>Post</span>
                </div>
            </div>
        </nav>

        <main>
            <div class="search-result">
                <!-- Search results would go here -->
            </div>
        </main>
    </div>
</body>
<script src="../src/js/page/user/search.js"></script>
</html>