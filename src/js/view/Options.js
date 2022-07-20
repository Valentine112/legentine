class Options {

    constructor(data, from, path) {
        return this
    }

    main() {
        var element = `
            <div class="config option">
                <div class="small">
                    <div class="options" id="small-options">
                        <div class="options-1">
                            <div class="options-2">
                                <header class="post-info">
                                    <div>
                                        <a href="">
                                            <img src="../images/image.jpg" alt="">
                                        </a>
                                    </div>
                                    <div>
                                        <span>Love thy</span>
                                        <br>
                                        <span>Himself</span>
                                    </div>
                                </header>
                                <div class="options-edit">
                                    <div class="person-options">
                                        `
                                        +
                                        // Display option based on author
                                        `
                                        
                                    </div>
            
                                    <div id="post-properties" class="edit-options">
                                        <div>
                                            <img src="../icon/option-icon/property.svg" alt="">
                                        </div>
                                        <div>
                                            <span>Properties</span>
                                        </div>
                                    </div>
            
                                </div>
                                
                                <div class="close-segment">
                                    <div>
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
        var personnal = `
            <div class="author personnal-options">
                <div class="edit-options">
                    <a href="">
                        <div>
                            <img src="../icon/option-icon/edit.svg" alt="">
                        </div>
                        <div>
                            <span>Edit</span>
                        </div>
                    </a>
                </div>

                <div class="edit-options">
                    <div>
                        <img src="../icon/option-icon/block-comment.svg" alt="">
                    </div>
                    <div>
                        <span>Block Comments</span>
                    </div>
                </div>

                <div class="edit-options">
                    <div>
                        <img src="../icon/option-icon/delete.svg" alt="">
                    </div>
                    <div>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        `

        var viewer = `
            <div class="viewer personnal-options">
                <div class="edit-options">
                    <div>
                        <img src="../icon/option-icon/save.svg" alt="">
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
            </div>
        `

        var save = `
            <div class="viewer personnal-options">
                <div class="edit-options">
                    <div>
                        <img src="src/icon/option-icon/remove.svg" alt="">
                    </div>
                    <div>
                        <span>Remove</span>
                    </div>
                </div>
            </div>
        `

        var privatePost = `
            <div class="author personnal-options">
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
            </div>
        `

        // Return any of this if page is from home/rank/profile
        if(this.from === "home" || this.from === "rank"){

            // Check if post belongs to viewer
            if(this.self['user'] == this.post['user']) {
                return personnal

            }else{
                return viewer
            }
        }
    }

}