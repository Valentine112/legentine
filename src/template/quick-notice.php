<head>
    <style>
        .quick-notice{
            position: fixed;
            bottom: 3%;
            left: 0;
            right: 0;
            width: 95%;
            margin: auto;
            display: none;
            transition: opacity 3s ease-out;
        }
        .quick-notice > div{
            border-radius: 5px;
            font-weight: 300;
            z-index: 3;
            width: 100%;
            margin: auto;
            font-family: var(--theme-font);
        }
        .quick-notice .notice-cover{
            width: fit-content;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 3px 3px 20px 2px #f1f1f1;
            padding-left: 35px;
            padding-right: 25px;
            margin: auto;
            background-color: #fff;
        }
        .quick-notice .notice-cover > div:first-child{
            width: fit-content;
            padding: 10px;
        }
        .quick-notice .notice-cover > div:last-child{
            width: fit-content;
            padding: 10px;
        }
        .quick-notice .notice-cover > div:first-child img{
            display: none;
        }
        .quick-notice .notice-cover > div:first-child img{
            height: 20px;
            width: 20px;
        }
        .quick-notice .notice-header{
            font-weight: 300!important;
            font-size: 15px;
            color: silver;
        }
        .quick-notice .success{
            color: #25AE88;
        }
        .quick-notice .warning{
            color: #FFA500;
        }
        .quick-notice .error{
            color: #D75A4A;
        }

        @media screen and (min-width: 768px) {
            .quick-notice{
                width: 60%;
            }
        }
        @media screen and (min-width: 992px) {
            .quick-notice{
                width: 40%;
            }
        }
    </style>
</head>
<div class="quick-notice">
    <div>
        <div class="notice-cover server-error">

            <div>
                <img src="../src/icon/notice/success.svg" class="success" alt="">
                <img src="../src/icon/notice/warning.svg" class="warning" alt="">
                <img src="../src/icon/notice/error.svg" class="error" alt="">
            </div>
            <div>
                <div class="notice-header">
                    <span>Notice</span>
                </div>
                <div class="notice-message">
                    <span class="error-text"></span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function hide_noticeModal(elem) {
        setTimeout(() => {
            elem.style.display = "none"
        }, 3000)
    }

    function remove_class(notice_message) {
        notice_message.classList.remove("warning", "success", "error")
    }
    
</script>