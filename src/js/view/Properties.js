function properties(data) {
    var name = data['name'],
    username = data['username'],
    rating = data['rating'],
    title = data['title'],
    category = data['category'],
    word_count = data['word-count'],
    stars = data['stars'],
    comment_state = data['comments_blocked'],
    date = data['date']


    var element = `
        <div class="properties fixed config-background-blur">
            <div class="properties-1 absolute">
                <div class="properties-adjust">
                    <div class="header-segment">
                        <div>
                            <span>Properties</span>
                        </div>
                    </div>
                    <div class="user-segment main-segment">
                        <div class="user-header sub-header">
                            <div>
                                <span>User information</span>
                            </div>
                        </div>
                        <div class="user-info info-segment">
                            <div>
                                <div>
                                    <span>Name:</span>
                                </div>
                                <div>
                                    <span class="field">${name}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Username:</span>
                                </div>
                                <div>
                                    <span class="field">${username}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Rating:</span>
                                </div>
                                <div>
                                    <span class="field">${rating}</span>
                                </div>
                            </div>
                        </div>
                    

                    </div>
                    <div class="post-segment main-segment">
                        <div class="post-header sub-header">
                            <div>
                                <span>Post information</span>
                            </div>
                        </div>
                        <div class="post-info info-segment">
                            <div>
                                <div>
                                    <span>Title:</span>
                                </div>
                                <div>
                                    <span class="field">${title}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Category:</span>
                                </div>
                                <div>
                                    <span class="field">${category}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Word count:</span>
                                </div>
                                <div>
                                    <span class="field">${word_count}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Stars:</span>
                                </div>
                                <div>
                                    <span class="field">${stars}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Comment blocked:</span>
                                </div>
                                <div>
                                    <span class="field">${comment_state}</span>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <span>Date:</span>
                                </div>
                                <div>
                                    <span class="field">${date}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="done-segment">
                        <div>
                            <span>Done</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `

    return element
}