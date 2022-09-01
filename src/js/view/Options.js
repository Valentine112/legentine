class Options {

    constructor(data, from, path) {
        this.data = data
        this.from = from
        this.path = path

        return this
    }

    main() {
        var element = `
            <div class="config option small-option">
                <div class="small">
                    <div class="options" id="small-options">
                        <div class="options-1">
                            <div class="options-2">
                                <header class="post-info">
                                    <div>
                                        <a href="">
                                            <img src="${this.path + this.data['photo']}" alt="">
                                        </a>
                                    </div>
                                    <div>
                                        <span>${this.data['title']}</span>
                                        <br>
                                        <span>${this.data['username']}</span>
                                    </div>
                                </header>
                                <div class="options-edit">
                                    <div class="person-options">
                                        `
                                        +
                                        // Display option based on author
                                        `
                                        ${this.authorOptions()}
                                    </div>
            
                                    <div id="post-properties" class="edit-options action" data-action="properties">
                                        <div>
                                            <img src="${this.path}src/icon/option-icon/property.svg" alt="">
                                        </div>
                                        <div>
                                            <span>Properties</span>
                                        </div>
                                    </div>
            
                                </div>
                                
                                <div class="close-segment">
                                    <div onclick='this.closest(".small-option").remove()'>
                                        <span>Close</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        `

        return element
    }

    authorOptions() {
        // Remove unlist from the post options if user is in profile page
        var unlistOption = `
            <div class="edit-options action" data-action="unlist_user">
                <div>
                    <img src="${this.path}src/icon/option-icon/unlist.svg" alt="">
                </div>
                <div>
                    <span>Unlist user</span>
                </div>
            </div>
        `
        if(this.from === "profile") {
            unlistOption = ""
        }

        var personnal = `
            <div class="author personnal-options">
                <div class="edit-options action">
                    <a href="session?token=${this.data['token']}">
                        <div>
                            <img src="${this.path}src/icon/option-icon/edit.svg" alt="">
                        </div>
                        <div>
                            <span>Edit</span>
                        </div>
                    </a>
                </div>

                <div class="edit-options action" data-action="toggle_comment">
                    `
                    +
                    // Comment state
                    `
                    ${this.comment_state()}
                </div>

                <div class="edit-options action" data-action="delete-entity">
                    <div>
                        <img src="${this.path}src/icon/option-icon/delete.svg" alt="">
                    </div>
                    <div>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        `

        var viewer = `
            <div class="viewer personnal-options">
                <div class="edit-options action" data-action="save_post">
                    <div>
                        <img src="${this.path}src/icon/option-icon/save.svg" alt="">
                    </div>
                    <div>
                        <span>${this.data['saved_state'] == 1 ? "Unsave" : "Save"}</span>
                    </div>
                </div>

                ${unlistOption}
            </div>
        `

        var save = `
            <div class="viewer personnal-options">
                <div class="edit-options action" data-action="remove_saved_post">
                    <div>
                        <img src="${this.path}src/icon/option-icon/remove.svg" alt="">
                    </div>
                    <div>
                        <span>Remove</span>
                    </div>
                </div>
            </div>
        `

        var privatePost = `
            <div class="author personnal-options">
                <div class="edit-options action">
                    <a href="session?token=${this.data['token']}">
                        <div>
                            <img src="${this.path}src/icon/option-icon/edit.svg" alt="">
                        </div>
                        <div>
                            <span>Edit</span>
                        </div>
                    </a>
                </div>

                <div class="edit-options action" data-action="delete_post">
                    <div>
                        <img src="${this.path}src/icon/option-icon/delete.svg" alt="">
                    </div>
                    <div>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        `

        // Return any of this if page is from home/rank/profile
        if(this.from === "home" || this.from === "rank" || this.from === "profile"){

            // this.data['owner'] is not a boolean, rather a string
            // It converted to a string when i added it to the html body
            // Thats why the comparison was made this way
            // If it was checked like a regular boolean, it would always return true
            // Check if post belongs to viewer

            if(this.data['owner'] === "true") {
                return personnal

            }else if(this.data['owner'] === "false") {
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

    comment_state() {
        var text
        var state
        if(this.data['comment_state'] === "1"){
            text = "Allow"
            state = 1
        }else{
            text = "Block"
            state = 0
        }

        return `
            <div>
                <img src="${this.path}src/icon/option-icon/${text}-comment.svg" alt="">
            </div>
            <div>
                <span data-state="${state}">${text} Comments</span>
            </div>
        `

    }

}