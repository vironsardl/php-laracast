<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];


// validate the form input
$errors = [];
if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}


if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a password of at least seven characters.';
}

if (!empty($errors)) {
    return view(
        'registration/create.view.php',[
            'errors' => $errors
    ]);
}
    
$db = App::resolve(Database::class);
// check if the account aleady exists
$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if($user)
{
    header('Location: /');
    exit();
} else {
    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
    'email' => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    // mark that the user is logged in

    login($user);
    
    header('location: /');
    exit();
}
