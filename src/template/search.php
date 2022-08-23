<style>
    *{
        font-family: var(--theme-font);
    }
    .search{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 4;
        margin-bottom: 5%;
        height: 100vh;
        background-color: #fff;
        overflow-y: auto;
    }
    .search .search-cover{
        margin-top: 2%;
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
        font-size: 17px;
        font-family: 'Arial';
        font-weight: 400;
        margin-left: 4.5%;
    }
    .top-randoms .people{
        width: 100%;
    }
    .top-randoms .people-box{
        display: inline-block;
        vertical-align: middle;
        position: relative;
        height: 100px;
        width: 45%;
        margin: 4px 2%;
    }
    .top-randoms .people-box img{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1;
        height: 100px;
        width: 100%;
        border-radius: 5px;
        object-fit: cover;
    }
    .top-randoms .people-box .top-username{
        position: absolute;
        bottom: 4%;
        left: 4%;
        z-index: 2;
        color: #fff;
        font-weight: 600;
    }

    .top-randoms .top-rated-post{
        margin-top: 15px;
    }
    .top-rated-post a{
        color: #000;
        text-decoration: none;
        margin: 3px 0 3px 0;
    }
    .top-rated-post a > div{
        width: 90%;
        margin: 4px 2%;
        padding: 4px;
        border-bottom: 1px solid #f1f1f1;
    }
    .top-rated-post .content{
        font-size: 15px;
        color: grey;
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
                    <span>Cancel</span>
                </div>
            </div>

            <div class="result-section">
                <div class="recents previews">

                </div>

                <div class="top-randoms previews">
                    <div class="top-rated-people">
                        <header>Some top rated persons</header>
                        <div class="people">
                            <?php $i = 0; while($i < 4): $i++ ?>
                            <div class="people-box">
                                <a href="">
                                    <div>
                                        <img src="../src/photo/image.jpg" alt="">
                                    </div>
    
                                    <div class="top-username">
                                        <span>
                                            Himself
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <div class="top-rated-post">
                        <header>Some top starred post</header>
                        <div class="post">
                            <?php $i = 0; while($i < 4): $i++ ?>
                            <a href="">
                                <div>
                                    <div class="title">Despair</div>
                                    <span class="content">
                                        April is the cruellest month, breeding
                                        Lilacs out of the dead land, mixing
                                        Memory and desire, stirring
                                        . . .
                                    </span>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <div class="search-result">
                    
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