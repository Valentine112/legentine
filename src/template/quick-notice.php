<head>
    <style>
        .quick-notice{
            position: fixed;
            bottom: 3%;
            left: 0;
            right: 0;
            width: 95%;
            margin: auto;
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
            height: 20px;
            width: 20px;
        }
        .quick-notice .notice-header{
            font-weight: 300!important;
            font-size: 15px;
            color: silver;
        }
        .quick-notice .warning{
            color: orange;
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
            }
        }
    </style>
</head>
<div class="quick-notice">
    <div>
        <div class="notice-cover">

            <div>
                <img src="../src/icon/sidebar-icon/about.svg" alt="">
            </div>
    
            <div>
                <div class="notice-header">
                    <span>Notice</span>
                </div>
                <div class="notice-message warning">
                    <span>Title and contents needed</span>
                </div>
            </div>

        </div>
    </div>
</div>