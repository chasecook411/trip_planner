<?php

?>

<head>
    <link rel="stylesheet" type="text/css" href="CssStuff.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Login to Sights</title>
    <script src="jquery-3.2.1.min.js"></script>
    <script>

        function initialize(userData) {
            // should receive data from PHP to launch app
            userData = JSON.parse(userData);

            window.location.assign("http://localhost/main_page.php?debug=true&userid=" + userData.user_id);
        }

        function login() {
            var email = document.getElementById('lo_email').value;
            var password = document.getElementById('lo_password').value;
            var url = 'http://localhost/endpoints/login.php?email=' + email + '&password=' + password;
            $.ajax({
                url: 'http://localhost/endpoints/login.php?email=' + email + '&password=' + password,
                type: "GET",
                cache: true,
                success: initialize,
                error: function(err) {
                    console.log(err);

                    var parent = document.getElementById("login");
                    var h = document.createElement("p");
                    var text = document.createTextNode("Error Logging In");

                    h.appendChild(text);
                    parent.appendChild(h);
                }
            });
        }

        function signup() {
            var f_name = document.getElementById('f_name').value;
            var l_name = document.getElementById('l_name').value;
            var email = document.getElementById('s_email').value;
            var password = document.getElementById('s_password').value;
            var confirm_password = document.getElementById('s_confirm_password').value;
            if(password === confirm_password) {
                $.ajax({
                    url: 'http://localhost/endpoints/insert_user.php',
                    type: "POST",
                    data: {
                        f_name: f_name,
                        l_name: l_name,
                        email: email,
                        password: password,
                    },
                    success: console.log("Signup Successful!"),
                    error: function(err) {
                        console.log(err);

                        var parent = document.getElementById("signup");
                        var h = document.createElement("p");
                        var text = document.createTextNode("Error signing In");

                        h.appendChild(text);
                        parent.appendChild(h);
                    }
                });
            }
            else {
                console.log("Passwords do not match.");
            }
        }
    </script>
</head>

<body>
    <div class="jumbotron">
        <div class="container sight-seer">
            Sight Seer
        </div>
      </div>
    <!-- Navigation Bar -->
    <header>
        <div class="navigation">
            <ul>
                <li class="Info"><a class="active" href="#">Info</a></li>
                <li class="Dashboard"><a href="http://localhost/main_page.php">Dashboard</a></li>
                <li class="Trips"><a href="#">Trips</a></li>
                <li class="Login"><a href="http://localhost/login_page.php">Login</a></li>
                <li class="Signup"><a href="http://localhost/login_page.php">Sign Up</a></li>
                <li class="Account"><a href="#">Account</a></li>
            </ul>
        </div>
    </header>
<!--similar variable names like email have a prefix to distinguish login variables vs signup variables,
    "lo_" for login and "s_" for signup-->

    <div id="login">
    <span>Enter email:</span>
    <input type="text" id="lo_email"></br>

    <span>Enter password:</span>
    <input type="password" id="lo_password"></br>
    <button onclick="login()">Login</button></br>
    </div></br>

    <div id="signup">
    <span>First Name:</span>
    <input type="text" id="f_name"></br>

    <span>Last Name:</span>
    <input type="text" id="l_name"></br>

    <span>Email:</span>
    <input type="text" id="s_email"></br>

    <span>Password:</span>
    <input type="password" id="s_password"></br>

    <span>Confirm Password:</span>
    <input type="password" id="s_confirm_password"></br>

    <button onclick="signup()">Signup</button></br>
    </div>
</body>

<?php

?>
