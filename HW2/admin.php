<?php
if(!empty($_REQUEST['password']))
{
    //In real life, probably do a user/pw lookup in DB
    if($_REQUEST['password'] == 'password')
    {
        //note: CSV location should probably not exist in web-accessible folder
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=data.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $contents = file_get_contents("data.csv");
        print $contents;
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Admin Page</title>
</head>
<body>
    <form method="post" action="<?php print $_SERVER['PHP_SELF']; ?>">
        <label for="password-box">Password:</label>
        <input type="password" name="password" id="password-box" />
        <button type="submit">Submit</button>
    </form>
</body>
</html>
