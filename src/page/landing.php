<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <link rel="stylesheet" href="src/page/css/landing.css">
    <title>Legentine</title>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="src/page/logo/logo4.png" alt="">
        </div>

        <div class="box-divs">
            <div>
                <div class="first-div-text">
                    <h1>ANTAONAR</h1>
                    <div>
                        <a href="signup">Signup</a>
                        &ensp;&ensp;
                        <a href="login">Login</a>
                    </div>
                </div>
            </div>

            <div>
                <div class="second-div-text">
                    <h1>Our Offer</h1>
                    <div class="second-box-li">
                        <ul>
                              <li>
                                    Music? Poem? or just your Thoughts?
                              </li>
                              <li>
                                    Express all and discover your self.
                              </li>
                              <li>
                                    Who knows, you might just get paid from being You.
                              </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</body>

<script>
      let deferredPrompt;

      window.addEventListener('beforeinstallprompt', (e) => {
      // Prevent the mini-infobar from appearing on mobile
      e.preventDefault();
      // Stash the event so it can be triggered later.
      deferredPrompt = e;
      // Update UI notify the user they can add to home screen
      showAddToHomeScreenButton();
});

function showAddToHomeScreenButton() {
      const addBtn = document.createElement('button');
      addBtn.textContent = 'Add to Home Screen';
      document.body.appendChild(addBtn);

      addBtn.addEventListener('click', () => {
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                  if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                  } else {
                        console.log('User dismissed the A2HS prompt');
                  }
                  deferredPrompt = null;
            });
      });
}

</script>
</html>