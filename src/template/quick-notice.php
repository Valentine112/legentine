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
            box-shadow: 3px 3px 20px 2px #f1f1f1;
            padding: 5px;
            padding-left: 35px;
            padding-right: 25px;
            width: 65%;
            margin: auto;
            font-family: var(--theme-font);
            background-color: #fff;
            border-radius: 5px;
            font-weight: 300;
            z-index: 3;
        }
        .quick-notice .notice-cover{
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
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
            color: rgba(0, 128, 0, 0.235);
        }
        .quick-notice .warning{
            color: orange;
        }
        .quick-notice .error{
            color: var(--theme-color);
        }

        @media screen and (min-width: 768px) {
            .quick-notice{
                width: 50%;
            }
        }
        @media screen and (min-width: 992px) {
            .quick-notice{
                width: 30%;
            }
            .quick-notice > div{
                box-shadow: 3px 3px 20px 2px rgb(232, 231, 231);
                width: 60%;
            }
        }
    </style>
</head>
<div class="quick-notice">
    <div>
        <div class="notice-cover">

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
                    <span></span>
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