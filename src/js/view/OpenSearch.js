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

        var brief = ""

        if(new Func().stripSpace(post['content']).length > 200) {
            brief = post['content'].slice(0, 150) + "..."
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