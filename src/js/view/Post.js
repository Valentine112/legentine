class Post {

    constructor() {
        
    }

    main() {
        var element = `<div class="config box">
            <div class="container-assist box">
                <div class="post-controller box">
                    <div class="post-body box">
                        <div class="post-assist box">
                            <div class="post-sub box">
                                <div class="dropdown-segment box">
                                    <div>
                                        <div class="more-icon sm-md">
                                            <img src="src/icon/post-icon/more.svg" alt="">
                                        </div>
                                        <div class="large">
                                            <div class="options" id="large-options">
                                                <div>
                                                    <div class="person-options">
                                                    `
                                                        //Options for the user

                                                    `
                                                    </div>
        
                                                    <div id="post-properties" class="edit-options">
                                                        <div>
                                                            <img src="src/icon/option-icon/property.svg" alt="">
                                                        </div>
                                                        <div>
                                                            <span>Properties</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="content-segment box">
                                    <div class="picture-segment">
                                        <div>
                                            <a href="">
                                                <img src="src/images/image.jpg" alt="">
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
                                                                // Title goes here

                                                            `
                                                            </span>
                                                            By 
                                                            <span class="name">
                                                            `
                                                            // Username goes here

                                                            `
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="brief-segment">
                                                        <span>
                                                            `
                                                            // Brief content goes here

                                                            `
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
        
                                            <div class="category-segment">
                                                <div>
                                                    <span>
                                                        `
                                                        // Category goes here

                                                        `
                                                    </span>
                                                </div>
                                            </div>
        
                                            <div class="divider-segment">
                                                <hr>
                                            </div>
        
                                            <div class="reaction-segment">
                                                <div class="reaction-cover">
                                                    `
                                                    // The reaction indicatior goes here

                                                    `
                                                </div>

                                                <div class="reaction-count">
                                                    <span>
                                                        `
                                                        // Reaction count goes here

                                                        `
                                                    </span>
                                                </div>
                                            </div>
        
                                            <div class="other-segment">
        
                                                <div>
                                                    <img src="src/icon/post-icon/read.svg" alt=""> - 
                                                    <span>
                                                        `
                                                        // Read count goes here

                                                        `
                                                    </span>
                                                </div>
                                                <div>
                                                    <img src="src/icon/post-icon/comment.svg" alt=""> - 
                                                    <span>
                                                        `
                                                        //Comment count goes here

                                                        `
                                                    </span>
                                                </div>
                                                <div>
                                                    <img src="src/icon/post-icon/feature.svg" alt=""> - 
                                                    <span>
                                                        `
                                                        // Feature count goes here

                                                        `
                                                    </span>
                                                </div>
        
                                            </div>
                                        </div>
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

    personOptions(options) {
        var personnal = `<div class="author personnal-options">
            <div class="edit-options">
                <a href="">
                    <div>
                        <img src="src/icon/option-icon/edit.svg" alt="">
                    </div>
                    <div>
                        <span>Edit</span>
                    </div>
                </a>
            </div>

            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/block-comment.svg" alt="">
                </div>
                <div>
                    <span>Block Comments</span>
                </div>
            </div>

            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/delete.svg" alt="">
                </div>
                <div>
                    <span>Delete</span>
                </div>
            </div>
        </div>`

        var viewer = `<div class="viewer personnal-options">
            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/save.svg" alt="">
                </div>
                <div>
                    <span>Save</span>
                </div>
            </div>

            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/unlist.svg" alt="">
                </div>
                <div>
                    <span>Unlist user</span>
                </div>
            </div>
        </div>`

        var saved = `<div class="viewer personnal-options">
            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/remove.svg" alt="">
                </div>
                <div>
                    <span>Remove</span>
                </div>
            </div>
        </div>`

        var private = `<div class="author personnal-options">
            <div class="edit-options">
                <a href="">
                    <div>
                        <img src="src/icon/option-icon/edit.svg" alt="">
                    </div>
                    <div>
                        <span>Edit</span>
                    </div>
                </a>
            </div>

            <div class="edit-options">
                <div>
                    <img src="src/icon/option-icon/delete.svg" alt="">
                </div>
                <div>
                    <span>Delete</span>
                </div>
            </div>
        </div>`

    }

    reaction() {
        var star = `<div class="reaction star">
            <img src="src/icon/post-icon/star.svg" alt="">
            <img src="src/icon/post-icon/unstar.svg" alt="">
        </div>`

        var unstar = `<div class="reaction unstar">
            <img src="src/icon/post-icon/unstar.svg" alt="">
            <img src="src/icon/post-icon/star.svg" alt="">
        </div>`
    }
}