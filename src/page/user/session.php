<!DOCTYPE html>
<html lang="en">
<head>
    <title>Session</title>
    <link rel="stylesheet" href="../src/page/css/user/session.css">
</head>
<body>
    <div class="session">
        <div class="container">
            <div>
                <div class="header">
                    <h1>Session</h1>
                </div>

                <div class="form-box">

                    <div>
                        <input type="text" placeholder="title. . ." aria-placeholder="title" class="form title" autofocus>
                    </div>
                    <div>
                        <textarea name="" id="" placeholder="Express yourself" aria-placeholder="contents here" class="form content" autocomplete="off" spellcheck="true"></textarea>
                    </div>

                </div>

                <div class="session-extras">
                    <div>
                        <div class="session-privacy">
                            <input type="checkbox" name="check" id="check" class="check">
                            <label for="check" id="private">Public</label>
                        </div>

                        <div class="session-category">
                            <select name="genre" id="genre" onchange="others(this)">
                                <option value="Rap">Rap</option>
                                <option value="Song">Song</option>
                                <option value="Poem">Poem</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Story">Story</option>
                                <option value="Other" id="other">Other</option>
                            </select>
                        </div>

                        <div class="session-button">
                            <button>
                                Done
                            </button>
                        </div>
                    </div>

                    <div class="session-notice">
                        <div>
                            <span>Note! Once private, it can never go public again</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>