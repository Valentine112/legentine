    <?php include_once "services/CategoryColor.php"; ?>
    <style>
        .block_message{
            position: fixed;
            background-color: rgba(0, 0, 0, 0.1);
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            display: none;
            z-index: 999;
        }
        .block_body{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            height: fit-content;
            width: fit-content;
            background-color: #fff;
            z-index: 1000;
            box-shadow: 1px 1px 1px 1px #f1f1f1;
            font-family: 'Poppins', sans-serif;
            padding: 1%;
        }
        .block_info{
            text-align: center;
        }
        .block_confirm{
            text-align: center;
            margin-top: 3%;
        }
        .block_confirm span{
            display: inline-block;
            width: 40%;
            margin: auto;
            text-align: center;
            cursor: pointer;
        }
        .yes{
            color: red;
        }
        .no{
            color: dodgerblue;
        }
        .block_error{
            position: fixed;
            background-color: #f1f1f1;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            height: fit-content;
            width: fit-content;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: orange;
            padding: 1%;
            display: none;
        }
        @media screen and (max-width: 767px) {
            .block_body{
                width: 85%;
                padding: 2%;
                padding-top: 3%;
                padding-bottom: 3%;
            }
        }
    </style>
    <div class="block_message">
        <div class="block_body">
            <div class="block_info">Do you really want to stop seeing post from <span id="block_username" style="font-weight: 600;"></span></div>
            <div class="block_confirm">
                <span onclick="block_user(this)" class="yes">Yes</span>
                <span onclick="cancel_block(this)" class="no">No</span>
            </div>
        </div>
    </div>
    <div class="block_error">
        There was an error processing that . . .
    </div>
    <div class="users-post-body" data-id="LT-<?= $post_token; ?>" data-person="LT-<?= $user_token; ?>">
        <div class="users-post-body-two">
            <div class="small-user-image">
                <?php if(!empty($_SESSION['process'])): ?>
                    <div class="small-image-cover">
                        <img alt="" data-src=" <?= $photo; ?>" class="user-image">
                    </div>
                <?php else: ?>
                    <a href="#" onclick="other_profile(this, '<?= $person_fullname; ?>', '<?= $userid; ?>', '<?= $sessId ?>' )">
                        <div class="small-image-cover">
                            <img alt="" data-src=" <?= $photo; ?>" class="user-image">
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <div class="users-post-div" id="user-post-option-body-id">

                <?php if(!isset($_SESSION['process'])): ?>
                    <div class="large-post-option-div">
                        <div class="option-background" id="option-button" onclick="toggle_dropdown(this)">
                            <img src="icons/more.svg" class="option-button">
                        </div>
                    </div>
                    <div class="small-post-option-div">
                        <div class="visible-option-background" id="option-button" onclick="toggle_dropdown(this)">
                            <img src="icons/more.svg" class="visible-option-icon">
                        </div>
                    </div>

                    <?php if($sessId === (int) $personId): ?>
                        <div class="option-dropdown-div" id="option-dropdown-div-id" onclick="this.style.display = 'none'">
                            <div class="large-option-dropdown">
                                <div><a href="#" class="edit-link" onclick='edit_post(this, "<?= $post_token; ?>", "<?= $user_token; ?>", `<?= str_replace("`", "", $title); ?>` )'><img src="icons3/001-pen.svg"> &emsp; Edit post</a></div><br>
                                
                                <div><span class="select" id="delete_post_id" onclick="delete_post(this, '<?= $post_token; ?>', '<?= $user_token; ?>' )"><img src="icons3/001-trash.svg"> &emsp; Delete post</span></div><br>

                                <div id="comments-div">
                                    <?php if($comments_blocked === 0): ?>
                                        <span class="select" onclick="block_comments(this, '<?= $post_token; ?>', '<?= $user_token; ?>')"><img src="icons3/wrong-access.svg"> &emsp; Block comments</span>
                                    <?php else: ?>
                                        <span class="select" onclick="allow_comments(this, '<?= $post_token; ?>', '<?= $user_token; ?>')"><img src="icons3/comment.svg"> &emsp; Allow comments</span>
                                    <?php endif; ?>
                                </div><br>

                                <div onclick="show_info(this, '<?= $person_fullname; ?>', '<?= clean_str($user_name); ?>', <?= (int) $rates; ?>, `<?= clean_str($title); ?>`, `<?= (string) $category; ?>`, <?= str_word_count($composed); ?>, <?= (int) $person_post_likes ?>, <?= $comments_blocked; ?>, '<?= $date; ?>')">
                                    <span class="select"><img src="icons3/info.svg"> &emsp; Properties</span>
                                </div>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="option-dropdown-div"  onclick="this.style.display = 'none'">
                            <div class="large-option-dropdown">
                                <div class="select" onclick="save(this, '<?= $post_token; ?>', '<?= $userid; ?>')"><img src="icons3/001-bookmark.svg"> &emsp; Save</div><br>
                                
                                <div onclick="show_info(this, '<?= $person_fullname; ?>', '<?= decode_str($user_name); ?>', <?= (int) $rates; ?>, `<?= str_replace('`', '', clean_str($title)); ?>`, `<?= (string) $category; ?>`, <?= str_word_count($composed); ?>, '<?= (int) $person_post_likes ?>', <?= (int) $comments_blocked; ?>, '<?= $date; ?>')">
                                    <span class="select"><img src="icons3/info.svg"> &emsp; Properties</span>
                                </div><br>
                                <div><span class="select" id="hide_user" onclick="prompt_block(this, '<?= $userid; ?>', '<?= $user_name; ?>')"><img src="icons3/001-remove.svg"> &emsp; Unlist user</span></div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>


                <div class="users-post-intro">
                    <div class="large-picture">
                        <?php if(isset($_SESSION['process'])): ?>
                            <div class="image-cover">
                                <img alt="" data-src=" <?= $photo; ?>" class="user-image" />
                            </div><br>
                        <?php else: ?>
                            <a href="#" onclick="other_profile(this, '<?= $person_fullname; ?>', '<?= $userid; ?>', '<?= $sessId; ?>' )">
                                <div class="image-cover">
                                    <img alt="" data-src=" <?= $photo; ?>" class="user-image" />
                                </div>
                            </a><br>
                        <?php endif; ?>
                    </div>
                    <?php $title1 = str_replace('`', `'`, $title); $title1 = str_replace(' ', '+', $title); ?>
                    <a href="composed.php?title=<?= $title1; ?>&postid=<?= $post_token; ?>&userid=<?= $userid; ?>" class="link-to-fullrap">
                        <div>
                            <span class="title"><?= htmlspecialchars_decode($title); ?>&ensp;By&ensp;<?= htmlspecialchars_decode($user_name); ?></span>
                        </div>
                        <span class="user-rap-summary title" id="full-rap"><?= htmlspecialchars_decode(substr($composed, 0, 200))."..."; ?></span><br>
                    </a>
                    <span class="link-to-message">
                        <?php
                            $colouring = new CategoryColor($category);
                            $categ_color = $colouring->process();
                        ?>
                        <span style="background: <?= $categ_color; ?>; color: #fff"><?= strtoUpper($category); ?></span>
                    </span>
                    <hr>
                </div>
                <div class="like-button-div">
                    <?php if(!isset($_SESSION['process'])): ?>
                        <section style="display:inline-block">
                            <?php if($has_user_liked === 0): ?>
                                <section id="parent-to-like">
                                    <img src="icons/shapes-and-symbols.svg" class="like" id="like" onclick="like(this, '<?= $userid; ?>', '<?= $post_token; ?>' )">

                                    <img src="icons/star (2).svg" class="like hidden-like" id="unlike" onclick="unlike(this, '<?= $userid; ?>', '<?= $post_token; ?>' )">
                                </section>
                            <?php else: ?>
                                <section id="parent-to-like">
                                    <img src="icons/star (2).svg" class="like" id="unlike" onclick="unlike(this, '<?= $userid; ?>', '<?= $post_token; ?>' )">
                                    <img src="icons/shapes-and-symbols.svg" class="like hidden-like" id="like" onclick="like(this, '<?= $userid; ?>', '<?= $post_token; ?>' )">
                                </section>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                    <br>
                    <span class="like-number">
                        <?php 
                            if((int) $person_post_likes === (int) 0 || (int) $person_post_likes < (int) 1):
                                echo "";
                            else:
                                echo $person_post_likes;
                            endif;
                        ?>
                    </span>
                </div>

                <div class="post-add-info">
                    <?php
                        $selecting = new Select($connect);
                        $selecting->more_details("WHERE postid = ?, $person_postid");
                        $selecting->process();

                        $readers = $selecting->pull("date", "readers")[1];
                        $comments = $selecting->pull("date", "comments")[1];
                        $features = $selecting->pull("date", "feature")[1];

                        $selecting->reset();

                        $readers = approximate_count($readers);
                        $comments = approximate_count($comments);
                        $features = approximate_count($features);

                    ?>

                    <div>
                        <img src="icons4/book.svg" alt="">
                        -
                        <span><?= $readers; ?></span>
                    </div>
                    <div>
                        <img src="icons4/comment.svg" alt="">
                        -
                        <span><?= $comments; ?></span>
                    </div>
                    <div>
                        <img src="icons4/hand.svg" alt="">
                        -
                        <span><?= $features; ?></span>
                    </div>
                </div>
            </div>
            <span style="display: none;" class="post-token"><?= $post_token; ?></span>
        </div>
    </div>