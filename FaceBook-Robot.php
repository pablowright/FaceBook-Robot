<?php

// FaceBook-Robot.php (ver 1.02, 04-02-2018). Script to auto-post to FaceBook from a database. Supports multiple users. Run from cron. 
// by Paul R. Wright PRW. pablo.wright@gmail.com Please email or tweet me if you find this useful or have 
// suggestions/modifications.
// Requires MySQL, php and the and FaceBook app account. Google how to set that part up

// 
// ====================================================

// Get rid of "?" in some Web browsers. maybe?:
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);

// Date info.:
date_default_timezone_set('America/New_York');
$tweetContentDate = date('m/d/Y h:i:s a', time());


// Get userID from script call:
// If called from http:
// $userID = $_GET["UID"];
// Or better yet, get UID and check to see if it is an integer:
//$id = ( isset( $_GET['UID'] ) && is_numeric( $_GET['UID'] ) ) ? intval( $_GET['UID'] ) : 0;

// NOT RUNNING FROM WEB. HARD-CODED 'UID' 2=antirobot army in database:
$id = "2";


if ( $id != 0 ){
    // id is an int != 0
$userID = $id;
}
else {
error_log('User does not exist '.$id. "\n", 3, "/home/ubuntu/tweeterErrors.log");
  exit("No user by this id. Let's go listen to Science Friday.");
}

// If called from command line:
// $userID = $argv[1];
// we'll skip the interger check.

// Connect to DB; Execute Query:
include '../../includes/db-conn.php';
$link = mysqli_connect("$server","$user","$pass","$database") or die("Error " . mysqli_error($link));

// ============================== Set character set to utf8 ==================================
// $link = mysqli_connect('localhost', 'my_user', 'my_password', 'test');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* change character set to utf8 */

if (!mysqli_set_charset($link, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($link));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($link));
}

// ==========================================================================================================
$query = "SELECT id, posts, posted FROM RobotSays where userID='$userID' and posted = 0 ORDER by RAND() LIMIT 0,1" or die("Error in the consult.." . mysqli_error($link));
$result = mysqli_query($link, $query);

//Check results for enpty set:
if(mysqli_num_rows($result)==0){
  $emptySetError = "{$userID}  {$tweetContentDate}";
  error_log('No tweets found for user '.$emptySetError. "\n", 3, "/home/ubuntu/tweeterErrors.log");
  exit("No tweets for this user.");
}
else {

while($row = mysqli_fetch_array($result)) {

// $tweetStr = $row["posts"];
$Content  = $row["posts"];
$ID = $row["id"];

mysqli_query($link, "UPDATE RobotSays SET posted = 1 WHERE ID = '$ID'");

}
}
// Free result set:
mysqli_free_result($result);

// =================== FACEBOOK =======================
//Need a random number for random inage:
$num = rand(10,36);

// echo $Content;

$page_access_token = '<a bunch of numbers and letter strung together which you receive when you create a FB app>'; // this could be added to the DB or in another file.
// See script to display an image "show-img.php . This adds a random image to the FB post:
$data['source'] = "https://anti-robot.org/apps/facebook/show-img.php?fileNum=$num"; // deprecated but works
$data['message'] = "$Content";
$data['name'] = "Robots doing things";
$data['access_token'] = $page_access_token;
$post_url = 'https://graph.facebook.com/v2.12/me/feed';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
curl_close($ch);

// Let us know what happened. This message will be emailed to whomever receives cron's email
echo "\n\r";
echo "<br />";
echo "$Content";
echo "\n\r";
echo "<br />";
echo "\n\r";
echo "Anti-Robot FaceBook post by:";
echo "\n\r";
echo "User ID = $id";
echo "\n\r";
echo "image number = $num";
echo "\n\r";

?>
