<style>
    .others-category{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.3);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        display: none;
    }
    .others-category div{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: fit-content;
        height: fit-content;
        margin: auto;
        text-align: center;
    }
    .others-category div input[type="text"] {
        border: 0;
        border-bottom: 2px solid #444;
        background-color: #fff;
        font-family: var(--theme-font);
        font-size: 17px;
        color: #000;
        width: 200px;
        outline: none;
    }
    .others-category div button{
        background-color: #fff;
        border: 1px solid #444;
        font-family: var(--theme-font);
        font-size: 16px;
        width: 100px;
        cursor: pointer;
    }
    .others-category div button:active{
        background-color: #444;
        color: #fff;
        border: 1px solid #fff;
    }
    .others-category p{
        font-family: var(--theme-font);
        color: orange;
    }
</style>
<div class="others-category" id="others-category">
    <div>
        <input type="text" placeholder="Other category" id="form-category" autocapitalize="on" maxlength="15"><br><br>
        <button id="done">Done</button>
        <br>
        <br>
        <br>
        <br>
        <br>
        <p>
            Do not give spaces <br><br>
            Should be greater than 1 character and less than 16 characters!
        </p>
    </div>
</div>

<script>
    function option() {
        return `
            <option value="" id="other-value"></option>
        `
    }
    
    window.addEventListener("load", function() {
        var others_category = document.getElementById("others-category")
        var func = new Func

        document.getElementById("done").addEventListener("click", async function() {

            var cate = document.getElementById("form-category")

            if(func.stripSpace(cate.value).length > 1 && func.stripSpace(cate.value).length < 16) {
                var processed = cate.value.toLowerCase()
                var first = processed.charAt(0).toUpperCase()
                var trimmed = first + "" + processed.slice(1, processed.length)

                var select_category = document.getElementById("category")

                var other_value
                // Check if an option element was created previously and modify if so
                if(document.getElementById("other-value") != null) {
                    other_value = document.getElementById("other-value")

                }else{

                    // Wait till the option html has been added
                    var promise = new Promise(res => {
                        res(
                            // Create and insert the option html
                            select_category.insertAdjacentHTML("afterbegin", option())
                        )
                    })
                    await promise
                    other_value = document.getElementById("other-value")
                }

                other_value.value = trimmed
                other_value.innerText = trimmed

                select_category.value = trimmed
                

                others_category.style.display = "none"
            }else{
                others_category.querySelector("p").style.color = "red"
            }

        })
    }, true)


</script>