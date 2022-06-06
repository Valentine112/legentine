<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <style>
        .options{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 2;
            font-family: "Roboto", sans-serif;
        }
        .options-1{
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.1);
            -webkit-backdrop-filter: blur(50px);
            backdrop-filter: blur(50px);
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 40px 20px 30px 20px;
        }
        .options .post-info{
            font-size: 20px;
            font-weight: 500;
            color: #fff;
            padding-bottom: 20px;
            border-bottom: 1px solid #a1a1a1;
            display: flex;
            justify-content: left;
            align-items: center;
            font-family: 'Montserrat';
        }
        .options .post-info div span:last-child{
            font-size: 15px;
        }
        .options .post-info > div:first-child{
            flex-grow: 1;
        }
        .options .post-info > div:last-child{
            flex-grow: 9;
        }
        .options .post-info img{
            height: 50px;
            width: 50px;
        }
        .options .options-edit{
            padding-top: 25px;
        }
        .options .options-edit img{
            height: 20px;
            width: 20px;
        }
        .options .options-edit > div{
            padding: 5px 0 5px 0;
            display: flex;
            justify-content: left;
            align-items: center;
            color: #fff;
            font-weight: 600;
            font-size: 17px;
        }
        .options .options-edit > div a{
            height: fit-content;
            display: flex;
            width: 100%;
            text-decoration: none;
            font-size: 17px;
            color: #fff;
            padding: 14px 0 14px 0;
        }
        .options .options-edit > div > div:first-child{
            width: 15%;
        }
        .options .options-edit > div > div:last-child{
            width: 80%;
            text-align: left;
            padding: 14px 0 14px 0;
        }
        .options .options-edit a > div:first-child{
            width: 15%;
        }
        .options .options-edit a > div:last-child{
            width: 80%;
        }
    </style>
</head>
<div class="config option" id="small-options">
    <div class="options small">
        <div class="options-1">
            <div class="options-2">
                <header class="post-info">
                    <div>
                        <a href="">
                            <img src="../images/image.jpg" alt="">
                        </a>
                    </div>
                    <div>
                        <span>Love thy</span>
                        <br>
                        <span>Himself</span>
                    </div>
                </header>
                <div class="options-edit">
                    <div>
                        <a href="">
                            <div>
                                <img src="../icon/option-icon/edit.svg" alt="">
                            </div>
                            <div>
                                <span>Edit</span>
                            </div>
                        </a>
                    </div>

                    <div>
                        <div>
                            <img src="../icon/option-icon/block-comment.svg" alt="">
                        </div>
                        <div>
                            <span>Block Comments</span>
                        </div>
                    </div>

                    <div id="post-properties">
                        <div>
                            <img src="../icon/option-icon/property.svg" alt="">
                        </div>
                        <div>
                            <span>Properties</span>
                        </div>
                    </div>

                    <div>
                        <div>
                            <img src="../icon/option-icon/delete.svg" alt="">
                        </div>
                        <div>
                            <span>Delete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

