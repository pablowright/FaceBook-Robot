# FaceBook-Robot
PHP script to auto-post to FaceBook from a database. Supports multiple users. Run from cron. 

1. Create a FaceBook application - developer 9google for how to do this.)
2. Create your database of posts using the .sql provided. (You will need to provide your own content, user name, etc)
3. Place the .php files on your Web server
4. curl -A  "Mozilla 4.0" https://webserveraddress/FaceBook-Robot.php?UID=<userID#> or open a Web browser to the same URL.
6. Optionally set up show-img.php
5. Add a cron job
