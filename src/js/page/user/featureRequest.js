window.addEventListener("load", function() {
    var path = new Func().getPath()['main_path']

    if(path === "featureRequest"){
        // Fetch the feature request
        new Feature().fetchFeature()
    }
    else if(path === "featureHistory") {
        // Fetch the feature request
        new Feature().fetchHistory()
    }

})

function featureBox(data) {
    var feature = data['feature'],
    other = data['other'],
    post = data['post']

    return `
        <div class="featureCover entity-body"
            data-token="LT-${feature['token']}"
            data-feature-token="LT-${feature['token']}"
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

function historyBox(data, self) {
    var history = data['history'],
    other = data['other'],
    post = data['post']

    var format = ""
    var status = ""
    var action = ""

    if(self == history['other']){
        format = `You made a request to be featured on a 
        ${post['category'].toLowerCase()}, 
        <a href="read?token=${post['token']}">${post['title']} </a>`;

    }else{
        format = `
            <a href="profile?token=${other['id']}">${other['username']}</a> 
            made a request to be featured on your 
            ${post['category'].toLowerCase()}, 
            <a href="read?token=${post['token']}">${post['title']} </a>
        `
    }

    if(history['status'] == 1) {
        status = "Accepted"

        if(self == history['user']){
            action = `&emsp;<button class="removeFeature" data-action="confirm-feature" data-type="0">Remove feature</button>`
        }
    }else{
        status = "Declined"
    }

    return `
        <div class="featureCover entity-body"
            data-token="LT-${history['token']}"
            data-feature-token="LT-${history['token']}"
            data-feature="LT-${history['feature']}"
        >
            <div>
                ${format}
            </div>

            <div class="featureAction">
                <span class="${status}"> ${status} ${action}</span>
            </div>
        </div>
    `
}