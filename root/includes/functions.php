<?php
 
function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../../user_login.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
  //  $cookieParams = session_get_cookie_params();
  //  session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    //session_set_cookie_params($cookieParams["lifetime"], "/smartphone/",  $cookieParams["domain"] , $secure, $httponly);
    session_set_cookie_params(2592000, "/smartphone/",  $cookieParams["domain"] , $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();       	     // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}
function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
$email_query  = "select * from members where email='$email' and password='$password';";
$result = mysqli_query($mysqli,$email_query);
if(mysqli_num_rows($result))
{
//	return $email_query;
	// Password is correct!
	// Get the user-agent string of the user.
	$user_browser = $_SERVER['HTTP_USER_AGENT'];
	// XSS protection as we might print this value
//	$user_id = preg_replace("/[^0-9]+/", "", $user_id);
//	$_SESSION['user_id'] = $user_id;
	// XSS protection as we might print this value
	$email = preg_replace("/[^a-zA-Z0-9@\\._\-]+/", "", $email);
	$_SESSION['email'] = $email;
	$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
	// Login successful.
	return true;
}
/*
    if ($stmt = $mysqli->prepare("SELECT id, username, password, salt   FROM members   WHERE email = ?   LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $salt);
        $stmt->fetch();
 
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        }  */
	else {
            // No user exists.
            return false;
       //}
    }
}
function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM login_attempts <code><pre>
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli2) {
    // Check if all session variables are set 
    if (isset( $_SESSION['email'],  $_SESSION['login_string'])) {
 
       // $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $email = $_SESSION['email'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
	$user_query = "SELECT password FROM members WHERE email='$email'  LIMIT 1;";
	$result = mysqli_query($mysqli2,$user_query);
	if(mysqli_num_rows($result))
		return true;
	else 
		return false;

	if ($stmt = $mysqli->prepare("SELECT password FROM members WHERE email='$email'  LIMIT 1;")) {
		return $user_query;
            // Bind "$user_id" to parameter. 
            //$stmt->bind_param('i', $user_id);
            if($stmt->execute())
	    	return true;
	    else 
		return false;
		    // Execute the prepared query.
            $stmt->store_result();
	    return true;
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

