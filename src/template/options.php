<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <style>
        .options{
            display: none;
        }
        #small-options{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.3);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            z-index: 2;
            font-family: "NotoSansJP", sans-serif;
            color: #000;
        }
        #small-options .options-1{
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 40px 20px 30px 20px;
        }
        #small-options .post-info{
            font-size: 20px;
            font-weight: 500;
            padding-bottom: 20px;
            border-bottom: 1px solid #a1a1a1;
            display: flex;
            justify-content: left;
            align-items: center;
        }
        #small-options .post-info div span:last-child{
            font-size: 15px;
        }
        #small-options .post-info > div:first-child{
            flex-grow: 1;
        }
        #small-options .post-info > div:last-child{
            flex-grow: 9;
        }
        #small-options .post-info img{
            height: 50px;
            width: 50px;
        }
        #small-options .options-edit{
            padding-top: 25px;
        }
        #small-options .options-edit img{
            height: 20px;
            width: 20px;
        }
        #small-options .options-edit .personnal-options > div, .options-edit > div:last-child{
            padding: 5px 0 5px 0;
            display: flex;
            justify-content: left;
            align-items: center;
            font-weight: 500;
            font-size: 16px;
        }
        #small-options .options-edit .edit-options a{
            height: fit-content;
            display: flex;
            width: 100%;
            text-decoration: none;
            font-size: 16px;
            color: #000;
            padding: 14px 0 14px 0;
        }
        #small-options .options-edit .edit-options > div:first-child{
            width: 15%;
        }
        #small-options .options-edit .edit-options > div:last-child{
            width: 80%;
            text-align: left;
            padding: 14px 0 14px 0;
        }
        #small-options .options-edit .edit-options a > div:first-child{
            width: 15%;
        }
        #small-options .options-edit .edit-options a > div:last-child{
            width: 80%;
        }

        /* Deciding what option to show depending on the user in relation to the post */

        .author{
            display: none;
        }
        .viewer{
            display: none;
        }

        /* End over here */

        #small-options .close-segment{
            margin-top: 40px;
        }
        #small-options .close-segment > div{
            width: fit-content;
            padding: 10px;
            margin: auto;
            font-weight: bold;
        }
    </style>
</head>
<div class="config option">
    <div class="small">
        <div class="options" id="small-options">
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
                        <div class="person-options">
                            <div class="author personnal-options">
                                <div class="edit-options">
                                    <a href="">
                                        <div>
                                            <img src="../icon/option-icon/edit.svg" alt="">
                                        </div>
                                        <div>
                                            <span>Edit</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="edit-options">
                                    <div>
                                        <img src="../icon/option-icon/block-comment.svg" alt="">
                                    </div>
                                    <div>
                                        <span>Block Comments</span>
                                    </div>
                                </div>

                                <div class="edit-options">
                                    <div>
                                        <img src="../icon/option-icon/delete.svg" alt="">
                                    </div>
                                    <div>
                                        <span>Delete</span>
                                    </div>
                                </div>
                            </div>

                            <div class="viewer personnal-options">
                                <div class="edit-options">
                                    <div>
                                        <img src="../icon/option-icon/save.svg" alt="">
                                    </div>
                                    <div>
                                        <span>Save</span>
                                    </div>
                                </div>

                                <div class="edit-options">
                                    <div>
                                        <img src="../icon/option-icon/unlist.svg" alt="">
                                    </div>
                                    <div>
                                        <span>Unlist user</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="post-properties" class="edit-options">
                            <div>
                                <img src="../icon/option-icon/property.svg" alt="">
                            </div>
                            <div>
                                <span>Properties</span>
                            </div>
                        </div>

                    </div>
                    
                    <div class="close-segment">
                        <div>
                            <span>Close</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

