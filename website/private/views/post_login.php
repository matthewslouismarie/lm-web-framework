<?php

// TODO: check doctrine and database mapping

require_once(private_root().'database-api/Database.php');

$db = Database::getConnection();

$username = $_POST['username'];
$password = $_POST['password'];
$request = Database::query( 'SELECT password FROM person WHERE id = ?;', array( $username) );
if ( $request->rowCount() === 0 | $request->fetch()['password'] !== $password ) {
    echo 'incorrect';
}
else {
    echo 'correct';
    setUsername($username);
}
?>