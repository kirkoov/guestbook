<?php
session_start();
use Particle\Validator\Validator;

require_once '../vendor/autoload.php';
require '../ini.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $v = new Validator();

  /*all three fields required with required(), we set a limit on their length with lengthBetween, we forced the name to be alpha- numeric (so no miscellaneous characters, like punctuation ― but spaces are allowed, indicated by the true we passed in) and we forced the email to be verified as an email format. Just for testing, we dump the errors we get if something goes wrong, or output "Submission is good" if all fields are OK*/

  $v->required('name')->lengthBetween(1, 48)->alnum(true);
  $v->required('email')->email()->lengthBetween(5, 255);
  $v->required('comment')->lengthBetween(3, null);
  $result = $v->validate($_POST);
  if ($result->isValid()) {
    // echo "Submission is good!";
    try {
      $comment->setName($_POST['name'])
              ->setEmail($_POST['email'])
              ->setComment($_POST['comment'])
              ->save();
      $_SESSION['emAIl'] = $_POST['email'];
      header('Location: /');
      return;
      /*The header function can only work if it comes before any HTML output. Thus, we've put all our PHP code at the top of the file. If we now enter valid information into the form and press submit, we'll be sent back to http://guestbook.test, the comment will appear in the database, and the page will be refreshable without the warning.*/
    } catch(\Exception $e) {
      die($e->getMessage()); 
    }
  } else {
      // dump($result->getMessages());
      // dump($result->getMessages());
  }
  // dump($database->error());
} else $result = null;
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>KK's guestbook</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/custom.css">

  <link rel="stylesheet" type="text/css" href="css/screen_layout_large.css"/>
  <link rel="stylesheet" type="text/css" media="only screen and (min-width:50px) and (max-width:500px)"
          href="css/screen_layout_small.css"/>
  <link rel="stylesheet" type="text/css" media="only screen and (min-width:501px) and (max-width:800px)"
          href="css/screen_layout_medium.css"/>
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

  <!-- Add your site or application content here -->

  <h1>Welcome to KK's guestbook!</h1>
  <div id="info">INFO</div>
  <div id="intro">This small app demoes:<ul><li><a href="https://medoo.in/">Medoo</a> sqlite functionality such as insertion and deletion of comments,</li> <li><a href="http://validator.particle-php.com/en/latest/"> Particle\Validator</a> of user input and <li>Simple responsiveness with</li><li>A trivial check for JS enabled.</li></ul>You're most welcome to leave your comment. <i>Unlike in other guestbooks</i>, you'll be able to delete your comment(s) as per your email while the PHP session is on. If you outlast the session, simply add a new comment with the same email you used before, and you'll be good to go. <?php if(isset($_SESSION['emAIl'])) echo "<a href='logout.php'>Close session</a>."; ?></div>


  <!-- begin list them previous comments if any -->
  <?php
  $cs = $comment->findAll();
  if(count($cs)) : 
  foreach ($comment->findAll() as $comment) : ?>
  <div class="comment">
    <?php if(isset($_SESSION['emAIl']) && $comment->getEmail() === $_SESSION['emAIl']) : ?>
    <span class="delcom" title="Delete comment"><a href="delcom.php?id=<?=$comment->getId()?>">x</a></span>
    <?php endif ?>
    <h3>On <?=$comment->getSubmissionDate()?>, <?=$comment->getName()?> (MSK) wrote:</h3>
    <p><?=$comment->getComment();?></p>
  </div>
  <?php endforeach; ?>
  <?php else : ?>
    <h3>No comments yet</h3>
    <p>Care to leave yours?</p>
  <?php endif; ?>
  <!-- end list them previous comments if any -->

  <form method="post">
    <label>Name: <?php if($result != null && isset($result->getMessages()['name'])) echo "<span class='error'>" . implode(', ', $result->getMessages()['name']) . "</span>"; ?>
    <input type="text" name="name" placeholder="Your name" required value="<?php
      if(isset($_POST['name'])) echo $_POST['name']; ?>"></label>

    <label>Email: <?php if($result != null && isset($result->getMessages()['email'])) echo "<span class='error'>" . implode(', ', $result->getMessages()['email']) . "</span>"; ?>
    <input type="email" name="email" placeholder="your@email.com" required value="<?php
      if(isset($_POST['email'])) echo $_POST['email']; ?>"></label>

    <label>Comment: <?php if($result != null && isset($result->getMessages()['comment'])) echo "<span class='error'>" . implode(', ', $result->getMessages()['comment']) . "</span>"; ?>
    <textarea name="comment" cols="30" rows="10" required><?php
      if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea></label>
    <input type="submit" value="Send">
  </form>
  
  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <!-- <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-GSTBK-K', 'auto'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async defer></script> -->
  <noscript>
    <div id="noscript-warning">Things work and look better with JavaScript enabled.</div>
  </noscript>
</body>

</html>
