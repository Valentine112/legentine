class Profile {

    constructor(data) {
        this.data = data
    }

    main() {
        var person = this.data['person']

        var element = `
            <div class="profileSub">
                <div>
                    <div>
                        <img src="../${person['photo']}" alt="">
                    </div>
                </div>

                ${this.profileAction()}
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
                        <span>${person['quote']}</span>
                    </div>

                    <div>
                        <div>
                            <span>Stars - </span>
                            <span class="rating">${person['rating']}</span>
                        </div>
                    </div>

                    <!-- Other person profile -->

                    <div class="other">
                        <div class="rate" id="rate">
                            <!-- Ratings would go here -->
                        </div>
                    </div>
                </div>
            </div>
        `

        return element
    }

    profileAction() {
        var person = this.data['person']

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
                    ${this.checkPin()}
                </div>
                <br>
                <div class="unlistBox">
                    ${this.checkList()}
                </div>
            </div>
        `

        if(person['id'] === this.self) {
            return self
        }else{
            return other
        }
    }

    checkPin() {
        var unpin = `
            <img src="../src/icon/profile/unpin.svg" alt="" class="unpin active" data-type="unpin" data-action="pin">

            <img src="../src/icon/profile/pinned.svg" alt="" class="pinned" data-type="pinned" data-action="pin">
        `

        var pinned = `
            <img src="../src/icon/profile/pinned.svg" alt="" class="pinned active" data-type="pinned" data-action="pin">

            <img src="../src/icon/profile/unpin.svg" alt="" class="unpin" data-type="unpin" data-action="pin">
        `

        if(this.more['pinned']) {
            return pinned
        }else{
            return unpin
        }
    }

    checkList() {
        var list = ""
        if(this.more['listed']) {
            list = "Unlist"
        }else{
            list = "List"
        }

        return `
            <div class="${list}>
                <span>${list}</span>
            </div>
        `
    }

    rating() {
        var rate = document.getElementById("rate")

        var person = this.data['person']

        var rating = person['rating']

        var result = new Func().printRatings(rating)

        result.forEach(elem => {
            switch (elem) {
                case 0:
                    var img = `<img src="../src/icon/profile/unstar.svg" alt="">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;
            
                case 1:
                    var img = `<img src="../src/icon/profile/halfstar.svg" alt="">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;

                case 2:
                    var img = `<img src="../src/icon/profile/star.svg" alt="">`
                    rate.insertAdjacentHTML("beforeend", img)
                    break;
                default:
                    break;
            }
        })

    }
}