<?php

?>

<head>
    <title>Login to Sights</title>
    <script src="jquery-3.2.1.min.js"></script>
    <script>

        function initialize(userData) {
            // should receive data from PHP to launch app
            userData = JSON.parse(userData);

            window.location.assign("http://localhost/main_page.php?debug=true&userid=" + userData.user_id);
        }

        function login() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
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

    </script>
</head>

<body>
    <div id="login">
    <span>Enter email:</span> 
    <input type="text" id="email"></br>

    <span>Enter password:</span>
    <input type="text" id="password"></br>
    <button onclick="login()">Login</button></br>
    </div>
</body>

<?php

?>