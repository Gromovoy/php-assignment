<?php
if ($_REQUEST['email']) {
    $masterEmail = $_REQUEST['email'];
}
/*
 *  We can use prepared DTO for validation
 *
class Request
{
    public string $userEmail;
}

there are different options what to do here:

1. use symfony/validator and doctrine/annotations for validation
2. use Validation service and use it like this:
$validatorService->validate(Request) - we should check here pattern blablabla@blabla.blabla

we can use factorys in the feature and strategies to add some logisc to validation process but basically it's enough now

 *
 */
$masterEmail = (isset($masterEmail) && $masterEmail ? $masterEmail : array_key_exists('masterEmail', $_REQUEST) && $_REQUEST["masterEmail"]) ? $_REQUEST['masterEmail'] : 'unknown';
echo 'The master email is ' . $masterEmail . '\n';
/*
 *  There are several problems here.
 * 1. FOF we should separate infrastructure layer and Logic. We need to have a chance to change mysql to any different DB without touching logic. We can use registary pattenr in Symfony way in this case
 * 2. There is no validation to $masterEmail it leeds as to sql injection. Use prepared statements prepared by PDO or we can check parameters by mysql_real_escape_string().
 */
$conn = mysqli_connect('localhost', 'root', 'sldjfpoweifns', 'my_database');
$res = mysqli_query($conn, "SELECT * FROM users WHERE email='" .
    $masterEmail . "'");
$row = mysqli_fetch_row($res);
echo $row['username'] . "\n";

/*
 * After all it should be smth like this:
 *
 */

$request = $dtoCreator->create($_REQUEST);
try {
    $validatorService->validate($request);
}catch(Throwable $e){
    /*
     * We can log here and decide what to do
     * And we can catch exact exeption from $validatorService not just Throwable
     */

}
$userEntity = $userRepository->getUser($request);
/*
 * if you need we can leave it like $userEntity->userEmail
 * But in a real system we probably would need smth like response object dto and some transformer to json or another format
 */
