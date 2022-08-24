<style>
    a{
        text-decoration: none;
    }
    .search{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 2;
        height: 100vh;
        background-color: #fff;
        overflow-y: scroll;
        display: none;
    }
    .search .search-cover{
        margin-top: 2%;
        margin-bottom: 25%;
    }
    .search .search-section{
        display: flex;
        align-items: center;
        justify-content: left;
    }
    .search .search-section > div:first-child{
        flex: 5;
        text-align: right;
    }
    .search .search-section > div:last-child{
        flex: 1;
        color: #000;
        text-align: center;
        font-family: var(--theme-font-3);
        cursor: pointer;
    }
    .search .search-section input[type="text"] {
        width: 90%;
        margin: auto;
        padding: 13px 10px 13px 10px;
        outline: none;
        border: 1px solid rgb(223, 236, 250);
        font-size: 16px;
        border-radius: 4px;
    }

    .search-cover .result-section .top-randoms{
        width: 90%;
        margin: auto;
        margin-top: 5%;
    }
    .result-section .top-randoms header{
        font-size: 20px;
        font-family: var(--theme-font-2);
        font-weight: 800;
        color: #000;
        margin: 0 2%;
    }
    .top-randoms .people{
        width: 100%;
    }
    .top-randoms .people-box{
        display: inline-block;
        vertical-align: middle;
        position: relative;
        width: 45%;
        margin: 8px 2%;
    }
    .top-randoms .people-box img{
        height: 150px;
        width: 100%;
        border-radius: 5px;
        object-fit: cover;
    }
    .top-randoms .people-box .top-fullname{
        color: #000;
        font-size: 15px;
        font-weight: 400;
        font-family: var(--theme-font-3);
    }
    .top-randoms .people-box .top-username{
        color: grey;
        font-size: 14px;
        font-weight: 400;
        font-family: var(--theme-font-3);
    }

    .top-randoms .top-rated-post{
        margin-top: 15px;
    }
    .top-rated-post a{
        color: #000;
        text-decoration: none;
        margin: 3px 0 3px 0;
        font-family: var(--theme-font-3);
    }
    .top-rated-post a > div{
        background-color: rgba(255, 255, 255, 0.4);
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
        width: 90%;
        margin: 15px 0 25px 0;
        box-shadow: 2px 2px 2px #fff;
        border-radius: 10px;
    }
    .top-rated-post .title{
        font-size: 16px;
        color: #000;
        font-weight: 600;
    }
    .top-rated-post .content{
        font-size: 15px;
        color: #444;
    }

    .search-result a{
        color: #000;
        text-decoration: none;
    }
    .search-result .result-box{
        display: flex;
        justify-content: left;
        align-items: center;
        padding: 10px 0 10px 0;
    }

    /* Different background color for the type of result gotten */
    .search-result .person{
        background-color: #f5f5f5;
    }

    /* END */

    .search-result .result-box > div:first-child{
        flex: 1;
        text-align: center;
    }
    .search-result .result-box > div:last-child{
        flex: 5;
    }
    .result-section .search-result img{
        height: 42px;
        width: 42px;
        border-radius: 50px;
        margin: auto;
        vertical-align: middle;
    }
    .result-section .search-result .username{
        font-size: 14px;
    }
</style>
<div class="small">
    <div class="search">
        <div class="search-cover">
            <div class="search-section">
                <div>
                    <input 
                    type="text"
                    placeholder="Search for contents and people. . ."
                    aria-placeholder="Search for contents and people"
                    onkeyup="Search(this)"
                    >
                </div>
                <div>
                    <span onclick="toggleSearch(document.getElementById('hide-search'), 'alt-icon')">Cancel</span>
                </div>
            </div>

            <div class="result-section">
                <div class="recents previews">

                </div>

                <div class="top-randoms previews">
                    <div class="top-rated-people">
                        <header>Some top rated persons</header>
                        <div class="people">
                            <!-- The people goes here -->
                        </div>
                    </div>

                    <div class="top-rated-post previews">
                        <header>Some top starred post</header>
                        <div class="post">
                            <!-- The post goes here -->
                        </div>
                    </div>
                </div>

                <div class="search-result" id="search-result">
                    <!-- Search result goes here -->

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function ResultBox(data) {

        var result_box = `
            <a href="">
                <div class="result-box person">
                    <div>
                        <img src="" alt="">
                    </div>
                    <div>
                        <span class="fullname post-title">Ngene Valentine</span>
                        <br>
                        <span class="username">Himself</span>
                    </div>
                </div>
            </a>
        `

        return result_box
    }

    function Search(self) {
        var searchValue = new Func().stripSpace(self.value)

        if(searchValue.length > 0) {

            // Hide the recents and tips
            document.querySelectorAll(".previews").forEach(elem => {
                elem.style.display = "none"
            })


            var data = {
                part: "user",
                action: 'search',
                val: {
                    content: searchValue,
                }
            }

            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {
                console.log(val)

                //new Func().notice_box(val)
            })
        }else{
            // Show the recents and tips
            document.querySelectorAll(".previews").forEach(elem => {
                elem.style.display = "block"
            })
        }
    }
</script>