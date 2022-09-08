class Profile {

    constructor(data) {
        this.data = data

        return this
    }

    main(ind) {
        var person = this.data[ind]['person']

        var element = `
            <div class="profileSub">
                <div>
                    <div>
                        <img src="../${person['photo']}" alt="">
                    </div>
                </div>

                ${this.profileAction(ind)}
            </div>

            <div class="userInfo">
                <div class="userInfoSub">
                    <div>
                        <span>${person['fullname']}</span>
                    </div>

                    <div>
                        <span>${person['username']}</span>
                    </div>

                    <div>
                        <span>${person['quote'] == "" ? person['username'] + " doesn't have a favourite quote" : person['quote']}</span>
                    </div>

                    <div>
                        <div>
                            <span>Stars - </span>
                            <span class="rating" id="ratingNumber">${parseFloat(person['rating'].toFixed(1))}</span>
                        </div>
                    </div>

                    <!-- Other person profile -->
                    ${this.checkRating(ind)}
                </div>
            </div>
        `

        return element
    }

    profileAction(ind) {
        var person = this.data[ind]['person']

        var self = `
            <div class="self">
                <div>
                    <span>Upload</span>
                </div>

                <div>
                    <a href="">Setup</a>
                </div>
            </div>
        `
        var other = `
            <div class="other">
                <div class="pinBox">
                    ${this.checkPin(ind)}
                </div>
                <br>
                <div class="unlistBox">
                    ${this.checkList(ind)}
                </div>
            </div>
        `

        if(person['id'] === this.data[ind]['self']['user']) {
            return self
        }else{
            return other
        }
    }

    checkPin(ind) {
        var unpin = `
            <img src="../src/icon/profile/unpin.svg" alt="" class="unpin active" data-type="unpin" data-action="pin">

            <img src="../src/icon/profile/pinned.svg" alt="" class="pinned" data-type="pinned" data-action="pin">
        `

        var pinned = `
            <img src="../src/icon/profile/pinned.svg" alt="" class="pinned active" data-type="pinned" data-action="pin">

            <img src="../src/icon/profile/unpin.svg" alt="" class="unpin" data-type="unpin" data-action="pin">
        `

        if(this.data[ind]['more']['pinned']) {
            return pinned
        }else{
            return unpin
        }
    }

    checkList(ind) {
        var list = ""

        if(this.data[ind]['more']['listed']) {
            list = "List"
        }else{
            list = "Unlist"
        }

        return `
            <div class="${list} action" data-action="profileList">
                <span>${list}</span>
            </div>
        `
    }

    checkRating(ind) {
        var more = this.data[ind]['more']
        var element = `
            <div class="other">
                <div class="rate" id="rate" data-sum="${more['summedRating']}" data-total=${more['totalRating']} data-rated=${more['rated']}>
                    <!-- Ratings would go here -->
                </div>
            </div>
        `

        // Checking if it is not the same user
        if(this.data[ind]['person']['id'] !== this.data[ind]['self']['user']) {
            return element
        }else{
            return ""
        }
    }

    rating(ind) {
        if(document.querySelector("#rate") !== null) {
            var rate = document.getElementById("rate")

            var person = this.data[ind]['person']

            var rating = person['rating']

            var more = this.data[ind]['more']
    
            var result = new Func().printRatings(parseFloat(more['rated']).toFixed(1))
    
            this.displayRatingStars(result, rate)
        }

    }

    displayRatingStars(result, rate) {
        result.forEach(elem => {
            switch (elem) {
                case 0:
                    var img = `<img src="../src/icon/profile/unstar.svg" alt="" class="ratingStar" data-action="rateUser">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;
            
                case 1:
                    var img = `<img src="../src/icon/profile/halfstar.svg" alt="" class="ratingStar" data-action="rateUser">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;

                case 2:
                    var img = `<img src="../src/icon/profile/star.svg" alt="" class="ratingStar" data-action="rateUser">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;
                default:
                    break;
            }
        })
    }
}