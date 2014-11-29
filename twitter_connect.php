<?php

/**
 * @file
 * Check if consumer token is set and if so send user to get a request token.
 */

/**
 * Exit with an error message if the CONSUMER_KEY or CONSUMER_SECRET is not defined.
 */
require_once('twitter_config.php');
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '' || CONSUMER_KEY === 'CONSUMER_KEY_HERE' || CONSUMER_SECRET === 'CONSUMER_SECRET_HERE') {
  echo 'Consumer key error.';
  exit;
}

/* Build an image link to start the redirect process. */
$content = '<a href="./twitter_redirect.php"><img src="./twitterimages/lighter.png" alt="Sign in with Twitter"/></a>';
 
/* Include HTML to display on the page. */
include('html.inc');
