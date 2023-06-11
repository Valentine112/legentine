<style>
    .reply{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        margin: auto;
        display: none;
        z-index: 2;
    }
    .reply .reply-sub{
        background: linear-gradient(145deg, #444, #000);
        height: inherit;
        padding: 15px;
        overflow-y: auto;
    }
    .reply-sub .main-reply{
        margin-bottom: 30%;
    }

    .reply .more span{
        cursor: pointer;
    }
    .reply .more{
        display: flex;
        justify-content: left;
        align-items: center;
        width: 100%;
    }
    .reply .more > div{
        flex: 1;
    }
    .reply .main-reply{
        margin-top: 10px;
    }

    .reply img{
        height: 20px;
        width: 20px;
        border-radius: 50px;
    }

    .reply .reply-comment{
        border-bottom: 2px solid #222;
        padding-bottom: 10px;
    }
    .main-reply .box > div:first-child > div{
        display: inline-block;
        vertical-align: middle;
        align-items: center;
    }

    .main-reply .box > div:first-child > div:first-child{
        width: 10%;
    }
    .main-reply .box > div:first-child > div:last-child{
        width: 87%;
    }

    .box .reply-username span{
        color: silver!important;
        font-size: 14px;
    }
    .box .date{
        font-size: 13px;
        color: grey;
    }

    .reply-cover{
        width: 90%;
        margin-left: auto;
        margin-top: 10px;
    }
    .replies{
        margin: 10px 0 10px 0;
        border-bottom: 1px solid #444;
        padding: 3px 0;
    }
    .reply .reply-options > div{
        display: inline-block;
        vertical-align: middle;
        margin: 0 15px 0 15px;
    }
    .reply .reply-options span{
        font-size: 13px;
        color: silver;
        cursor: pointer;
    }

    .spaceBelow{
        height: 0vh;
        width: 1px;
        background-color: red;
    }

    /* reply input */
    .reply .reply-input{
        position: absolute;
        left: 0;
        right: 0;
        bottom: 2%;
        margin: auto;
        width: 100%;
        z-index: 2;
    }

    .reply .reply-holder{
        width: 90%;
        margin: auto;
        background-color: inherit;
        background-color: #444;
        border-bottom: 2px solid #f1f1f1;
    }
    .reply .reply-holder > div{
        display: inline-block;
        vertical-align: middle;
        align-items: center;
    }
    .reply .reply-holder > div:first-child{
        width: 80%;
    }
    .reply .reply-holder > div:last-child{
        width: 18%;
        text-align: center;
    }
    .reply .reply-value{
        background-color: transparent;
        width: 90%;
        padding: 10px;
        white-space: wrap;
        word-wrap: break-word;
        border: 0;
        max-height: 15vh;
        outline: none;
        font-family: var(--theme-font);
    }
    /* Add the placeholder for the div */
    [contentEditable=true]:empty:before{
        content: attr(data-placeholder);
        color: grey;
    }
    .reply .reply-holder button{
        background-color: transparent;
        font-size: 18px;
        border: 0;
        user-select: none;
    }

    @media screen and (max-width: 767px) {
        .reply .reply-input{
            bottom: 5%;
        }
        .reply .reply-sub{
            height: 80vh;
            top: unset;
            bottom: 0;
        }
    }
    @media screen and (min-width: 768px) {
        .reply{
            top: unset;
            left: unset;
            height: 70vh;
            width: 50%;
        }
    }

    @media screen and (min-width: 992px) {
        .reply{
            width: 35%;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
    }
</style>

<div class="main-content reply config">
    <div class="reply-sub">
        <div class="more">
            <div>
                <span onclick="closeReply(this)">Close</span>
            </div>

            <div class="small">
                <span class="post-title"></span>
            </div>
        </div>

        <div class="main-reply">
            <div class="reply-comment box" id="reply-comment">
                <div>
                    <div>
                        <a href="" id="personLink">
                            <img src=" " alt="" id="comment-photo">  
                        </a>
                    </div>

                    <div>
                        <div class="reply-username">
                            <span id="comment-username">
                                <!-- Comment username -->
                            </span>
                        </div>

                        <div>
                            <span class="reply-content" id="comment-content">
                                <!-- The comment content goes here -->
                            </span>
                            &ensp;
                            <span class="date" id="comment-date">
                                <!-- Comment date goes here -->
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="reply-cover">
            <!-- REPLIES GOES HERE -->
            </div>
        </div>

        <div class="spaceBelow">

        </div>
    </div>

    <div class="reply-input cancel-box">
        <div>
            <span id="cancel-edit-reply" class="cancel-edit" onclick="new CommentActions().cancel_edit(this, 'create-reply')">Cancel</span>
        </div>

        <div class="reply-holder">
            <div>
                <div class="reply-value input-value" contenteditable="true" data-placeholder="reply. . ." id="reply-value"></div>
            </div>
            <div>
                <button data-action="create-reply" id="send" class="send-reply">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeReply(self) {
        document.body.style.overflowY = "hidden"
        self.closest('.reply').style.display = 'none'
    }
</script>