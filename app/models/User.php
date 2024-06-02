<?php

class User {

  public $username;
  public $password;
  public $auth = false;

  // regex for our password validation
  private $passwordRegex = '/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=\S*?[!@#$&]).{8,})\S$/m';

  public function __construct() {
      
  }

  public function test () {
    $db = db_connect();
    $statement = $db->prepare("select * from users;");
    $statement->execute();
    $rows = $statement->fetch(PDO::FETCH_ASSOC);
    return $rows;
  }

  public function authenticate($username, $password) {
    /*
     * if username and password good then
     * $this->auth = true;
     */
    $username = strtolower($username);
    $db = db_connect();
    $statement = $db->prepare("select * from users WHERE username = :name;");
    $statement->bindValue(':name', $username);
    $statement->execute();
    $rows = $statement->fetch(PDO::FETCH_ASSOC);

    // does user even exist?
    if ($rows) {
      if (password_verify($password, $rows['password'])) {
        $_SESSION['auth'] = 1;
        $_SESSION['username'] = ucwords($username);

        $this->username = ucwords($username);      

        unset($_SESSION['failedAttempts']);
        unset($_SESSION['invalidLogin']);

        // log successful authentication attempt
        $this->logAuthenticationAttempt($username, true);

        header('Location: /home');
        die;
      } else {
        // log failed authentication attempt and redirect back to login
        $this->logAuthenticationAttempt($username, false);
        header('Location: /login');
        die;
      }
    } else {
      $_SESSION['invalidLogin'] = 1;
      // redirect back to login
      header('Location: /login');
      die;
    }    
  }

  public function create($username, $password, $passwordConfirm) {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // user exists?
    if ($user) {
      $_SESSION['failedSignup'] = 'Username is not available.';
      header('Location: /signup');
      die;
    }

    if ($password != $passwordConfirm) {
      $_SESSION['failedSignup'] = 'Passwords don\'t match. Try again.';
      header('Location: /signup');
      die;
    }

    if (!preg_match($this->passwordRegex, $password)) {
        $_SESSION['failedSignup'] = 'Password is too weak. Please enter a stronger password that meets the specified requirements.';
      header('Location: /signup');
      die;
    }

    // If we're here, that means all validation has passed and we can create our new user (w00t!)

    // hash the user's password before storing in the DB
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    if ($stmt->execute()) {
      $_SESSION['signupSuccess'] = 1;
      unset($_SESSION['failedSignup']);
      header('Location: /login');
      die;
    } else {
       $_SESSION['failedSignup'] = 'There was a problem creating your account. Please try again later.';
      header('Location: /signup');
      die;
    }
  }

  private function logAuthenticationAttempt($username, $successfulAttempt) {
    $db = db_connect();
    $statement = $db->prepare("insert into auth_logs (username, successful_attempt, timestamp) values (:name, :success, CURRENT_TIMESTAMP())");
    $statement->bindValue(':name', $username);
    $statement->bindValue(':success', $successfulAttempt ? 1 : 0);
    $statement->execute();

    // keep track of failed attempts
    if (!$successfulAttempt) {
      if(isset($_SESSION['failedAttempts'])) {
        $_SESSION['failedAttempts'] ++; //increment

        // lockout user if they have failed too many attempts
        if ($_SESSION['failedAttempts'] >= 3) {
          $_SESSION['lockoutUntil'] = time() + (30 * 1); // 30 seconds
        }      
      } else {
        $_SESSION['failedAttempts'] = 1;
      }
    }
  }
}
