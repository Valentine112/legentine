class FeatureBox {

    constructor() {
        
    }

    main() {
        var element = `<div class="feature-box fixed config-background-blur">
            <div class="feature-1 absolute">
                <div class="feature-adjust">
                    <div>
                        <div>
                            <span>
                                Hello 
                                <span id="user">
                                    `
                                    // Username goes here

                                    `
                                </span>,
                                <a href="" id="other">
                                    `
                                    // Other username goes here

                                    `
                                </a>
                                has requested permission to be featured on your masterpiece
                                <a href="" id="post">
                                    `
                                    // Post title goes here

                                    `
                                </a>
                        </div>
                    </div>
        
                    <div>
                        <div>
                            <button class="decline quick-reply action">Decline</button>
                        </div>
                        &emsp;&ensp;
                        <div>
                            <button class="accept quick-reply action">Accept</button>
                        </div>
                        <div>
                            <div class="quick-reply-loader config-loader"></div>
                        </div>
                    </div>
        
                    <div>
                        <div>
                            This message can still be found under Feature Request if no reply was given
                        </div>
                    </div>
        
                    <div>
                        <div>
                            <button class="later action">Later</button>
                        </div>
                    </div>
        
                    <div>
                        <div>
                            <button class="pendall action">Pend all</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`
    }
}