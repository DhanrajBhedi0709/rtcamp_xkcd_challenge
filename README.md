# rtcamp_xkcd_challenge

###### This simple PHP application accepts a visitor’s email address and emails them random XKCD comics every five minutes.

###### There are mainly 5 files.
```
DBConnection.php
index.php
verify.php
XKCD_mail.php
unsubscribe.php
```

Here Database connection has done using concept of **Singleton class**.

index.php is used for the sending **email** with **unique hash** to entered email address.

verify.php is used to **verify email based on the sent hash**.

unsubscribe.php is used to **unsubscribe the facility of getting XKCD comics**.

Here email is sent every five minute. For that i have created **cron job** of XKCD_mail.php which is used to send XKCD comics from API.

Live Demo : [XKCD Demo](http://xkcd.ictmu.in/)
