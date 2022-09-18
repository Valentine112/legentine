<style>
    .upload{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #444;
        z-index: 4;
        margin: auto;
        font-family: var(--theme-font);
        display: none;
    }
    .upload .closeUpload{
        position: absolute;
        top: 2%;
        right: 2%;
        color: #fff;
        z-index: 2;
    }
    .upload .uploadError{
        position: absolute;
        top: 2%;
        left: 0;
        right: 0;
        width: fit-content;
        margin: auto;
        background-color: #444;
        border-radius: 5px;
        color: var(--theme-color);
        box-shadow: 1px 1px 3px #5e5e5e;
        text-align: center;
        z-index: 2;
    }
    .upload .uploadError span{
        padding: 10px 5px;
    }
    .upload .uploadSub main{
        height: 90vh;
        width: 100%;
        padding-top: 5px;
        margin-bottom: 10px;
    }
    .uploadSub main > div:first-child{
        width: 100%;
        height: 85%;
        margin: auto;
        text-align: center;
        position: relative;
    }
    .uploadSub main > div:first-child img{
        width: 90%;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }
    main > div:first-child .full{
        max-height: 90vh;
        max-width: 90%;
    }
    main > div:first-child .half{
        max-height: 70vh;
    }

    .uploadSub .imagePreview{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .imagePreview > div{
        height: 100%;
        margin: 0 5px;
    }
    .imagePreview > div img{
        height: 100px;
        width: 50px;
        object-fit: cover;
    }
    .upload .uploadOptions{
        position: absolute;
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: auto;
    }
    .upload .uploadOptions > div{
        flex: 1;
        text-align: center;
        color: #fff;
        font-family: var(--theme-font);
        padding: 10px 0;
    }
    .upload .uploadOptions > div:first-child{
        border-right: 1px solid #5e5e5e;
    }
    .uploadOptions #uploadMode{
        border-right: 1px solid #5e5e5e;
    }
    .uploadOptions .fileInput{
        display: none;
    }

    @media screen and (min-width: 992px) {
        .upload{
            width: 50%;
            height: 95vh;
        }
    }
</style>
<div class="upload">
    <div class="closeUpload">
        <span onclick="closeUpload()">Close</span>
    </div>
    <div class="uploadError">
        
    </div>

    <div class="uploadSub">
        <main>
            <div>
                <img src="" alt="" class="half" id="imageDisplay">
            </div>

            <div class="imagePreview">
                <!-- The small image previews go here -->
            </div>
        </main>

        <div class="uploadOptions">
            <div>
                <span onclick="uploadImage(this)">Upload</span>
            </div>
            <div id="uploadMode">
                <input type="checkbox" id="checkbox" checked>
                <label for="checkbox" id="mode">Single</label>
            </div>
            <div>
                <label for="file">Select</label>
                <input type="file" name="file" id="file" class="fileInput" accept="image/*" onchange="selectImage(this)" data-type="uploadPicture" multiple aria-multiselectable="true">
            </div>
        </div>
    </div>
</div>

<script>
    var uploadError = document.querySelector(".uploadError")
    var imageDisplay = document.getElementById("imageDisplay")
    var imagePreview = document.querySelector(".imagePreview")
    var file = document.getElementById('file')
    var imageData = ""
    var cropper = ""

    function errorBox(errorMessage) {
        return `
            <span class="uploadErrorMessage">${errorMessage}</span>
        `
    }

    function imagePreviewBox(file) {
        return `
            <div>
                <img src="${file}" alt="" onclick="displayImage(this)">
            </div>
        `
    }

    function displayImage(self) {
        imageDisplay.src = self.src
    }

    function resetValue(self, error) {
        uploadError.innerHTML = error
        self.value = ""

        setTimeout(() => {
            uploadError.innerHTML = ""
        }, 2500)
    }

    function selectImage(self) {
        var reader = new FileReader
        var files = self.files
        var fileLength = files.length

        var validImageTypes = ['image/jpg', 'image/jpeg', 'image/png']
        var validLength = 0
        var error = 0

        // Check if its a profile picture or the user is just uploading photos
        var type = self.getAttribute("data-type")
        if(type === "profilePicture") {
            validLength = 1
            error = errorBox("You can only upload one profile picture at a time")
        }

        if(type === "uploadPicture") {
            validLength = 5
            error = errorBox("You cannot select more than 5 pictures at a time")
        }

        // Check for the length first
        if(fileLength > validLength) {
            resetValue(self, error)
            return
        }

        if(fileLength === 1) {
            if(!validImageTypes.includes(files[0]['type'])) {
                error = errorBox("Image type should be either jpg, png or jpeg")
                resetValue(self, error)
                return

            }else{
                imagePreview.innerHTML = ""
                // Set the display size
                imageDisplay.classList.remove("half")
                imageDisplay.classList.add("full")

            }
        }
        else{
            imagePreview.innerHTML = ""
            // Set the display size
            imageDisplay.classList.remove("half")
            imageDisplay.classList.add("full")

            // Check for the file type
            Array.from(files).forEach((elem, ind) => {
                // Read the images from back
                elem = files[fileLength - (ind + 1)]

                if(!validImageTypes.includes(elem['type'])) {
                    error = errorBox("Image type should be either jpg, png or jpeg")
                    uploadError.innerHTML = error
                    return
                }

                var reader = new FileReader
                reader.onload = function(ev) {
                    imageDisplay.src = ev.target.result
                    imagePreview.insertAdjacentHTML("afterbegin", imagePreviewBox(ev.target.result))
                }
                reader.readAsDataURL(elem)
            })
        }

        setTimeout(() => { 
            cropper = new Cropper(imageDisplay, {
                autoCrop: false,
                aspectRatio: 12 / 12,
                background: false,
                viewMode: 1,
                crossOrigin: false,
                autoCropArea: 1,
                movable: false,
                rotatable: false,
                cropBoxResizable: false,
                dragMode: 'move',
                cropBoxResizable: false,
                checkOrientation: false,
                ready(){
                    this.cropper.crop()
                },
            });
        }, 0800)

        // If there is an error, then the code would return and wouldn't reach here
        reader.onload = function(ev) {
            imageDisplay.src = ev.target.result
            //imageData = ev.target.result
        }
        reader.readAsDataURL(files[0])

    }

    function uploadImage(self) {
        var profileLoader = document.getElementById("profilePictureLoader")
        const files = file.files

        if(files.length > 0) {
            // Close the upload box
            closeUpload()

            var uploadType = file.getAttribute("data-type")
            var mode = ""
            var type = ""

            if(uploadType === "profilePicture"){
                mode = "single"
                type = "profile"

                profileLoader.style.display = "block"

            }else if(uploadType === "uploadPicture"){
                var modeCheckBox = document.getElementById("mode")

                modeCheckBox ? mode = "single" : mode = "multiple"
                type = "photo"
            }

            var formdata = new FormData()
            Array.from(files).forEach(elem => {
                formdata.append("files[]", elem)
            })

            formdata.append("part", "user")
            formdata.append("action", "uploadPhoto")
            formdata.append("type", "file");
            formdata.append("mode", document.getElementById("checkbox").checked)
            formdata.append("uploadType", uploadType)

            new Func().request("../request.php", formdata, 'file')
            .then(val => {
                console.log(val)
                if(val.status === 1) {
                    var content = val.content.split("%%")

                    if(content[0] == "profilePicture") {
                        document.getElementById("profilePicture").src = "../src/" + content[1]

                        profileLoader.style.display = "none"
                    }
                }
            })
        }else{
            var error = errorBox("Please select a photo first")
            resetValue(file, error)
            return
        }
    }

</script>