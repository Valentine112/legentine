class OpenSearch {

    constructor(data) {
        this.data = data
    }

    People() {
        var people = this.data

        var element = `
            <div class="people-box">
                <a href="">
                    <div>
                        <img src="../${people['photo']}" alt="">
                    </div>

                    <div class="top-fullname">
                        <span>
                            ${people['fullname']}
                        </span>
                    </div>

                    <div class="top-username">
                        <span>
                            ${people['username']}
                        </span>
                    </div>
                </a>
            </div>
        `

        return element
    }

    Post() {
        var post = this.data

        var brief = post['content']

        if(new Func().stripSpace(brief).length > 100) {
            brief = brief.slice(0, 100) + "..."
        }

        var element = `
            <a href="read?token=${post['token']}">
                <div>
                    <div class="title">${post['title']}</div>
                    <span class="content">
                        ${brief}
                    </span>
                </div>
            </a>
        `

        return element
    }
}

class SearchBox {
    
    constructor(data) {
        this.data = data
    }

    people() {
        var element = `
            <a href="">
                <div class="result-box person">
                    <div>
                        <img src="../src/photo/image.jpg" alt="">
                    </div>
                    <div>
                        <div class="fullname post-title">Ngene Valentine</div>
                        <div class="username">Himself</div>
                    </div>
                </div>
            </a>
        `

        return element
    }

    post() {
        var element = `
            <a href="">
                <div class="result-box post">
                    <div class="post-title">${this.post['title']}</div>
                    <div class="post-content">${this.post['content']}</div>
                </div>
            </a>
        `

        return element
    }
}