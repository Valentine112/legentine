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
                        <img src="../src/${people['photo']}" alt="">
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

    Recent() {
        var element = `
            <a href="search?${this.data['token']}">
                <div>
                    <span>${this.data['username']}</span>
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

    people(ind) {
        var data = this.data[ind]
        var element = `
            <a href="profile?token=${data['id']}">
                <div class="result-box person">
                    <div>
                        <img src="../src/${data['photo']}" alt="">
                    </div>
                    <div>
                        <div class="fullname post-title">${data['fullname']}</div>
                        <div class="username">${data['username']}</div>
                    </div>
                </div>
            </a>
        `

        return element
    }

    post(ind) {
        var data = this.data[ind]

        var brief = data['content']

        if(new Func().stripSpace(brief).length > 100) {
            brief = brief.slice(0, 100) + "..."
        }

        var element = `
            <a href="read?token=${data['token']}">
                <div class="result-box post">
                    <div class="post-title">${data['title']}</div>
                    <div class="post-content">${brief}</div>
                </div>
            </a>
        `

        return element
    }
}