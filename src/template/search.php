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
        color: var(--theme-color);
        text-align: center;
        font-family: var(--theme-font-3);
        cursor: pointer;
    }
    .search .search-section input[type="text"] {
        width: 90%;
        margin: auto;
        padding: 10px 10px 10px 10px;
        outline: none;
        border: 1px solid #f5f5f5;
        background-color: #f5f5f5;
        font-size: 16px;
        border-radius: 10px;
    }

    /* RECENTS HERE */

    .recent-toggle{
        display: none;
        padding: 15px;
        font-family: var(--theme-font-3);
    }
    .recents header{
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    .recents header > div:last-child{
        color: var(--theme-color);
    }

    .recents .recent-searches{
        margin-top: 3%;
    }
    .recent-searches a{
        text-decoration: none;
        color: #000;
        display: block;
        width: 100%;
        padding: 7px;
    }


    /* END */

    /* DISPLAYING MORE PEOPLE HERE */

    .search-cover .result-section .top-randoms{
        width: 90%;
        margin: 5% auto;
    }
    .result-section .top-randoms header{
        font-size: 20px;
        font-family: "SourceSansPro", sans-serif;
        font-weight: 800;
        color: #000;
        margin: 0 1%;
    }
    .top-randoms .people{
        width: 100%;
    }
    .top-randoms .people-box{
        display: inline-block;
        vertical-align: middle;
        position: relative;
        width: 45%;
        margin: 8px 1%;
    }
    .top-randoms .people-box img{
        height: 100px;
        width: 100px;
        border-radius: 5px;
        object-fit: cover;
    }
    .top-randoms .people-box .top-fullname{
        color: #000;
        font-size: 16px;
        font-weight: 400;
        font-family: var(--theme-font);
    }
    .top-randoms .people-box .top-username{
        color: grey;
        font-size: 14px;
        font-weight: 400;
        font-family: var(--theme-font);
    }

    .top-randoms .top-rated-post{
        margin-top: 15px;
        margin: 5% auto;
    }
    .top-rated-post .post{
        margin-top: 10px;
        margin-left: 1%;
    }
    .top-rated-post a{
        color: #000;
        text-decoration: none;
        font-family: var(--theme-font);
        background-color: red;
    }
    .top-rated-post a > div{
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
        width: 90%;
        box-shadow: 2px 2px 2px #fff;
        border-radius: 10px;
        margin: 10px 0;
    }
    .top-rated-post .title{
        font-size: 16px;
        color: #000;
        padding: 3px 0;
        border-bottom: 1px inset #f1f1f1;
    }
    .top-rated-post .content{
        margin-top: 5px;
        font-size: 15px;
        color: #444;
    }

    /* END */

    .search-result a{
        color: #000;
        text-decoration: none;
    }
    .search-result .result-box{
        border-bottom: 1px solid #f1f1f1;
        margin-bottom: 5px;
        padding: 5px 0;
        font-family: var(--theme-font);
        font-size: 16px;
    }
    .result-section .search-result .person{
        padding: 10px 0;
    }
    .result-section .search-result .post{
        padding: 10px 0;
    }
    .search-result .person{
        border-radius: 10px;
        display: flex;
        justify-content: left;
        align-items: center;
    }
    .search-result .post{
        margin-left: 20px;
    }
    .search-result .person > div:first-child{
        flex: 1;
        text-align: center;
    }
    .search-result .person > div:last-child{
        flex: 5;
    }
    .search-result img{
        height: 42px;
        width: 42px;
        border-radius: 50px;
        margin: auto;
        vertical-align: middle;
    }
    .search-result .fullname{
        font-size: 16px;
    }
    .search-result .username{
        font-size: 14px;
        color: #444;
    }
    .search-result .post .post-content{
        margin-top: 0;
        font-size: 14px;
        color: #5e5e5e;
    }
</style>
<div class="small">
    <div class="search">
        <div class="search-cover search-parent">
            <div class="search-section">
                <div>
                    <form method="get" onsubmit="fullSearch(event)">
                        <input 
                            type="text"
                            placeholder="What do you seek?"
                            aria-placeholder="What do you seek?"
                            onkeyup="Search(this)"
                            onclick="clickSearch(this)"
                            onfocus="focusSearc(this)"
                            id="searchInput"
                        >
                    </form>

                </div>
                <div>
                    <span onclick="toggleSearch(document.getElementById('hide-search'), 'alt-icon')">Cancel</span>
                </div>
            </div>

            <div class="result-section">
                <div class="recents search-previews recent-toggle">
                    <header>
                        <div>
                            Recents
                        </div>

                        <div>
                            <div>Clear</div>
                        </div>
                    </header>

                    <div class="recent-searches">
                        <!-- The recent goes here -->
                    </div>
                </div>

                <div class="top-randoms search-previews">
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
                        <span class="fullname post-title"></span>
                        <br>
                        <span class="username"></span>
                    </div>
                </div>
            </a>
        `

        return result_box
    }



    function clickSearch(self) {
        // Hide the recents and tips
        // But show only the recents when the search focuses and is empty

        document.querySelectorAll(".search-previews").forEach(elem => {
            elem.style.display = "none"
        })

        // Hide the recent searches also, since the inbuilt clear in search doesn't do anything

        if(new Func().stripSpace(self.value).length < 1){
            document.getElementById("search-result").innerHTML = ""
            
            var recentSearch = document.querySelectorAll(".recent-toggle")
            recentSearch.forEach(elem => {
                elem.style.display = "block"
            })
        }
    }

    // Show the previews for the small device here
    function Search(self) {
        var searchResult = self.closest(".search-parent").querySelector(".search-result")
        //searchResult.style.display = "block"

        var searchValue = new Func().stripSpace(self.value)

        if(searchValue.length > 0 && searchValue != "") {

            // Hide the recents and tips
            document.querySelectorAll(".search-previews").forEach(elem => {
                elem.style.display = "none"
            })

            var data = {
                part: "user",
                action: 'search',
                val: {
                    content: searchValue,
                    limit: 10
                }
            }

            new Func().request("../request.php", JSON.stringify(data), 'json')
            .then(val => {

                if(val.status === 1) {
                    searchResult.innerHTML = ""

                    var content = val.content
                    var search = new SearchBox(content)

                    content.forEach((val, ind) => {
                        if(val['type'] === "people") {
                            searchResult.insertAdjacentHTML('beforeend', search.people(ind))
                        }

                        if(val['type'] === "post") {
                            searchResult.insertAdjacentHTML('beforeend', search.post(ind))
                        }
                    })
                }
                //new Func().notice_box(val)
            })
        }else{
            searchResult.innerHTML = ""

            // Show the recents
            var recentSearch = document.querySelectorAll(".recent-toggle")
            recentSearch.forEach(elem => {
                elem.style.display = "block"
            })
        }
    }

    // When the inbuilt clear on search box is clicked
    function focusSearch(self) {
        // Check if the searchbox is empty
        // Fetched the input box again to get the recent value
        if(new Func().stripSpace(document.getElementById("searchInput").value).length < 1){
            document.getElementById("search-result").innerHTML = ""
            
            var recentSearch = document.querySelectorAll(".recent-toggle")
            recentSearch.forEach(elem => {
                elem.style.display = "block"
            })
        }
    } 

    function fullSearch(ev) {
        ev.preventDefault()
        ev.stopPropagation()

        var val = document.getElementById("searchInput")

        var searchValue = new Func().stripSpace(val.value)

        if(searchValue.length > 0 && searchValue != "") {
            window.location = `search?keyword=${searchValue}`
        }
    }
</script>