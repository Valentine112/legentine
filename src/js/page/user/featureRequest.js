window.addEventListener("load", function() {
    // Fetch the feature request

    new Feature().fetchFeature()

})

function featureBox(data) {
    var feature = data['feature'],
    other = data['other'],
    post = data['post']

    return `
        <div class="featureCover"
            data-token="LT-${feature['token']}
            data-feature-token="LT-${feature['token']}
        >
            <div>
                <a href="profile?token=${other['id']}">${other['username']}</a> 
                has requested permission to be featured on your 
                ${post['category'].toLowerCase()}
                 <a href="read?token=${post['token']}">${post['title']}</a>
            </div>

            <div>
                <button data-action="confirm-feature" data-type="0">Decline</button>

                &emsp;

                <button data-action="confirm-feature" data-type="1">Accept</button>
            </div>
        </div>
    `
}