class PostHTML {

    constructor(data, from, path) {
        this.data = data
        this.from = from
        this.path = path

        console.log(data)

        this.post = data['post']
        this.self = data['self']
        this.other = data['other']
        this.more = data['more']

    }

    main() {
        var photo = this.other['photo']

        var element = `
            <div class="post-body box">
                <div class="post-assist box">
                    <div class="post-sub box">
                        `
                        +
                        // Hide this part if not logged in
                        `
                        ${this.displayOption()}
                        <div class="content-segment box">
                            <div class="picture-segment">
                                <div>
                                    <a href="">
                                        <img src=" ${this.path + photo} " alt="">
                                    </a>
                                </div>
                            </div>

                            <div class="info-body">
                                <div class="info-segment">
                                    <div class="article-segment">
                                        <a href="" class="article-link">
                                            <div class="title-segment">
                                                <span>
                                                    <span class="title search-key">
                                                    `
                                                    +
                                                    // Title goes here
                                                    `
                                                    ${this.post['title']}
                                                    </span>
                                                    By 
                                                    <span class="name">
                                                    `
                                                    +
                                                    // Username goes here
                                                    `
                                                    ${this.other['username']}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="brief-segment">
                                                <span>
                                                `
                                                +
                                                // Brief content goes here
                                                `
                                                ${this.brief_content()}
                                                </span>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="category-segment">
                                        <div class="${this.post['category'].toLowerCase()}">
                                            <span>
                                            `
                                            +
                                            // Category goes here
                                            `
                                            ${this.post['category'].toUpperCase()}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="divider-segment">
                                        <hr>
                                    </div>

                                    <div class="reaction-segment">
                                        <div class="reaction-cover">
                                            ` 
                                            +
                                            // The reaction indicatior goes here

                                            `
                                            ${this.reaction()}
                                        </div>

                                        <div class="reaction-count">
                                            <span>
                                                `
                                                +
                                                // Reaction count goes here

                                                `
                                                ${this.reaction_count()}

                                            </span>
                                        </div>
                                    </div>

                                    <div class="other-segment">

                                        <div>
                                            <img src="${this.path}src/icon/post-icon/read.svg" alt=""> - 
                                            <span>
                                                `
                                                +
                                                // Read count goes here

                                                `
                                                ${this.post['readers']}
                                            </span>
                                        </div>
                                        <div>
                                            <img src="${this.path}src/icon/post-icon/comment.svg" alt=""> - 
                                            <span>
                                                `
                                                +
                                                //Comment count goes here

                                                `
                                                ${this.post['comments']}
                                            </span>
                                        </div>
                                        <div>
                                            <img src="${this.path}src/icon/post-icon/feature.svg" alt=""> - 
                                            <span>
                                                `
                                                +
                                                // Feature count goes here

                                                `
                                                ${this.post['features']}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`

        return element
    }

    displayOption() {
        if(this.self['user'] === 0){
            return ""

        }else{
            return `
                <div class="dropdown-segment box">
                    <div>
                        <div class="more-icon sm-md" onclick="toggle_options(this)">
                            <img src="${this.path}src/icon/post-icon/more.svg" alt="">
                        </div>
                        <div class="large">
                            <div class="options" id="large-options">
                                <div>
                                    <div class="person-options">
                                    `
                                    +
                                    // Options for the users
                                    `
                                    ${this.options()}

                                    </div>

                                    <div id="post-properties" class="edit-options">
                                        <div>
                                            <img src="${this.path}src/icon/option-icon/property.svg" alt="">
                                        </div>
                                        <div>
                                            <span>Properties</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
        }
    }

    options() {
        var personnal = `<div class="author personnal-options">
            <div class="edit-options">
                <a href="">
                    <div>
                        <img src="${this.path}src/icon/option-icon/edit.svg" alt="">
                    </div>
                    <div>
                        <span>Edit</span>
                    </div>
                </a>
            </div>

            <div class="edit-options">
                <div>
                    <img src="${this.path}src/icon/option-icon/block-comment.svg" alt="">
                </div>
                <div>
                    <span>Block Comments</span>
                </div>
            </div>

            <div class="edit-options">
                <div>
                    <img src="${this.path}src/icon/option-icon/delete.svg" alt="">
                </div>
                <div>
                    <span>Delete</span>
                </div>
            </div>
        </div>`

        var viewer = `<div class="viewer personnal-options">
            <div class="edit-options">
                <div>
                    <img src="${this.path}src/icon/option-icon/save.svg" alt="">
                </div>
                <div>
                    <span>Save</span>
                </div>
            </div>

            <div class="edit-options">
                <div>
                    <img src="${this.path}src/icon/option-icon/unlist.svg" alt="">
                </div>
                <div>
                    <span>Unlist user</span>
                </div>
            </div>
        </div>`

        var save = `<div class="viewer personnal-options">
            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/remove.svg" alt="">
                </div>
                <div>
                    <span>Remove</span>
                </div>
            </div>
        </div>`

        var privatePost = `<div class="author personnal-options">
            <div class="edit-options">
                <a href="">
                    <div>
                        <img src="${this.path}src/icon/option-icon/edit.svg" alt="">
                    </div>
                    <div>
                        <span>Edit</span>
                    </div>
                </a>
            </div>

            <div class="edit-options">
                <div>
                    <img src="${this.path}src/icon/option-icon/delete.svg" alt="">
                </div>
                <div>
                    <span>Delete</span>
                </div>
            </div>
        </div>`

        // Return any of this if page is from home/rank/profile
        // Else return nothing
        if(this.from === "home" || this.from === "rank"){

            // Check if post belongs to viewer
            if(this.self['user'] == this.post['user']) {
                return personnal

            }else{
                return viewer
            }
        }

        // Return any of this if page is from save/privatePost
        // Else return nothing

        else if(this.from === "save"){
            return save

        }
        else if(this.from === "privatePost"){
            return privatePost

        }else{
            return ""
        }

    }

    brief_content() {
        var brief = this.post['content']

        if(new Func().stripSpace(this.post['content']).length > 200) {
            brief = this.post['content'].substr(0, 150) + "..."
        }

        return brief
    }

    reaction() {
        var star = `<div class="reaction-box">
            <img src="${this.path}src/icon/post-icon/star.svg" alt="" class="reaction star active">

            <img src="${this.path}src/icon/post-icon/unstar.svg" alt="" class="reaction unstar">
        </div>`

        var unstar = `<div class="reaction-box">
            <img src="${this.path}src/icon/post-icon/unstar.svg" alt="" class="reaction unstar active">
            <img src="${this.path}src/icon/post-icon/star.svg" alt="" class="reaction star">
        </div>`

        // First check if the user is not logged in by checking if the self['user'] = 0

        if(this.self['user'] === 0){
            return unstar;
        }
        else{
            // return the unstar, (yellow star button) if the starred returns true
            // else return the star, (white star button)

            if(this.more['starred']){
                return star
            }else{
                return unstar
            }
        }
    }

    reaction_count() {
        var result = ""

        result = this.post['stars'] == 0 ? "" : this.post['stars']

        return new Func().approximate_count(result)
    }
}