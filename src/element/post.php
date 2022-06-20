<head>
    <?php include "../config/config.html"; ?>
    <link rel="stylesheet" href="../config/config.css">
    <link rel="stylesheet" href="css/post.css">
    <?php include "../template/options.php"; ?>
    <?php include "properties.php"; ?>
</head>
<div class="config container box">
    <div class="container-assist box">
        <div class="post-controller box">
            <?php $i = 0; while($i < 5): $i++; ?>
            <div class="post-body box">
                <div class="post-assist box">
                    <div class="post-sub box">
                        <div class="dropdown-segment box">
                            <div>
                                <div class="more-icon sm-md">
                                    <img src="../icon/post-icon/more.svg" alt="">
                                </div>
                                <div class="large">
                                    <div class="options" id="large-options">
                                        <div>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="content-segment box">
                            <div class="picture-segment">
                                <div>
                                    <a href="">
                                        <img src="../images/image.jpg" alt="">
                                    </a>
                                </div>
                            </div>

                            <div class="info-body">
                                <div class="info-segment">
                                    <div class="article-segment">
                                        <a href="" class="article-link">
                                            <div class="title-segment">
                                                <span>
                                                    <span class="title search-key">Mine baby</span>
                                                    By 
                                                    <span class="name">Alessandra money</span>
                                                </span>
                                            </div>
                                            <div class="brief-segment">
                                                <span>
                                                    Your sitting there, all alone So distant, I should know I understand what your going through And I recommend, you get someone to help you I could that s...
                                                </span>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="category-segment">
                                        <div>
                                            <span>SONG</span>
                                        </div>
                                    </div>

                                    <div class="divider-segment">
                                        <hr>
                                    </div>

                                    <div class="reaction-segment">
                                        <div>
                                            <img src="../icon/post-icon/unstar.svg" alt="">
                                        </div>
                                        <div>
                                            <span>1</span>
                                        </div>
                                    </div>

                                    <div class="other-segment">

                                        <div>
                                            <img src="../icon/post-icon/read.svg" alt=""> - 
                                            <span>50</span>
                                        </div>
                                        <div>
                                            <img src="../icon/post-icon/comment.svg" alt=""> - 
                                            <span>15</span>
                                        </div>
                                        <div>
                                            <img src="../icon/post-icon/feature.svg" alt=""> - 
                                            <span>1</span>
                                        </div>

                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>