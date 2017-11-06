<?php

// for creating a new user
// if we have everything we need to insert the user
if (isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // verify that email does not already exist
    $query = 'select email from users where email = "' . $email . '";';

    // if result of query is greater than/equal to 1, then we need to err.
    if ($query != null) {
       echo "This email address has already been registered with an account.";
    }
    //otherwise, start insertion
    else {
        $query = 'insert into users values(NULL, "' . $f_name . '","'.$l_name . '","' . $email . '","' . $password . '");';
        echo $query;
    }
}

?>
