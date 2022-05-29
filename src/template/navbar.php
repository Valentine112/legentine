<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <style>
        .navbar{
            background-color: rgba(255, 255, 255, 0.5);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #f5f5f5;
            padding: 12px 3px 12px 3px;
            z-index: 2;
        }
        .navbar .small{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large{
            display: none;
        }
        .navbar .small .alt-icon{
            display: none;
        }
        .navbar .large > div{
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .navbar .large a, .navbar .large span{
            text-decoration: none;
            color: #1e1f22;
            font-family: 'SourceSansPro';
            transition: font-size 0.3s linear;
        }
        .navbar .large a:hover, .navbar .large span:hover{
            font-size: 19px;
        }
        .navbar .large .active{
            background-color: #fc8290;
            padding: 2px 20px 2px 20px;
            border-radius: 5px;
            color: #fff!important;
        }

        @media screen and (min-width: 600px) and (max-width: 767px) {

        }

        @media screen and (min-width: 768px) {
            .navbar{
                top: 0;
                bottom: unset;
                background-color: rgba(255, 255, 255, 0.5);
                box-shadow: 1px 1px 0px 1px #f1f1f1;
                padding: 18px 3px 18px 3px;
            }
            .navbar .small{
                display: none;
            }
            .navbar .large{
                display: flex;
                justify-content: space-between;
            }
            .navbar .large .section-1{
                width: 45%;
            }
            .navbar .large .section-2{
                width: 54%;
            }
            .navbar .large .section-1 > div{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large .section-2 > div:first-child{
                flex-grow: 4;
                text-align: right;
            }
            .navbar .large .section-2 > div:last-child{
                flex-grow: 1;
                text-align: center;
            }
            .navbar .large a, .navbar .large span{
                font-size: 14px;
            }
            .navbar .large .section-2 input[type="search"]{
                width: 90%;
                padding: 10px 10px 10px 10px;
                background-color: #f5f5f5;
                border: 2px solid #f1f1f1;
                border-radius: 0px;
                font-family: 'SourceSansPro', sans-serif;
            }
            .navbar .large .section-2 input[type="search"]:focus{
                outline: 2px solid #f1f1f1;
            }
        }

        @media screen and (min-width: 992px) {
            .navbar .large a, .navbar .large span{
                font-size: 15px;
            }
            .navbar .large .section-1{
                width: 40%;
            }
        }

    </style>
</head>
    <nav class="navbar config">

        <div class="small">
            <div>
                <a href="">
                    <img src="../icon/plain-icon/home.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/color-icon/home.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>

            <div>
                <a href="">
                    <img src="../icon/plain-icon/note.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/color-icon/note.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>
                
            <div>
                <a href="">
                    <img src="../icon/plain-icon/star.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/color-icon/star.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>
                
            <div>
                <a href="">
                    <img src="../icon/plain-icon/search.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/color-icon/search.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>

            <div>
                <a href="">
                    <img src="../icon/plain-icon/list.svg" alt="home" class="config-icon-1 ori-icon">

                    <img src="../icon/color-icon/list.svg" alt="home" class="config-icon-1 alt-icon">
                </a>
            </div>
        </div>

        <div class="large">
            <div class="section-1">
                <div>
                    <a href="">
                        <span class="active">Home</span>
                    </a>
                </div>

                <div>
                    <a href="">
                        <span>Session</span>
                    </a>
                </div>

                <div>
                    <a href="">
                        <span>Top</span>
                    </a>
                </div>

                <div>
                    <span>More</span>
                </div>

                <div>
                    <a href="">
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="section-2">
                <div>
                    <input type="search"  id="search" autocomplete="off" placeholder="Search for people. . .">
                </div>

                <div>
                    <a href="">
                        <span>Profile</span>
                    </a>
                </div>
            </div>

        </div>

    </nav>
