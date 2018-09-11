<?php
session_start();
use Particle\Validator\Validator;

require_once '../vendor/autoload.php';

// Using Medoo namespace
use Medoo\Medoo;

// Initialize DB for dev keeping production db safe, see in .gitignore
$file = '../storage/database.db';
if (is_writable('../storage/database.local.db')) {
  $file = '../storage/database.local.db';
}

$database = new Medoo([
  'database_type' => 'sqlite',
  'database_file' => $file
]);

$comment = new KK\Comment($database);



echo session_id();
// o923ol0grquq4dfakknncge8mr
// o923ol0grquq4dfakknncge8mr
// alooa27e7p9b56rcuap24imsm8


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $v = new Validator();

  /*all three fields required with required(), we set a limit on their length with lengthBetween, we forced the name to be alpha- numeric (so no miscellaneous characters, like punctuation ― but spaces are allowed, indicated by the true we passed in) and we forced the email to be verified as an email format. Just for testing, we dump the errors we get if something goes wrong, or output "Submission is good" if all fields are OK*/

  $v->required('name')->lengthBetween(1, 100)->alnum(true);
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
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>A test guestbook app</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

  <!-- Add your site or application content here -->

  <!-- begin list them previous comments if any -->
  <?php $cs = $comment->findAll();
  if(count($cs)) : 
  foreach ($comment->findAll() as $comment) : ?>
  <div class="comment">
    <h3>On <?=$comment->getSubmissionDate()?>, <?=$comment->getName()?> wrote:</h3>
    <p><?=$comment->getComment();?></p>
  </div>
  <?php endforeach; ?>
  <?php else : ?>
    <h3>No comments yet</h3>
    <p></p>
  <?php endif; ?>
  <!-- end list them previous comments if any -->

  <form method="post">
    <label>Name: <?php if($result != null && isset($result->getMessages()['name'])) echo "<span class='error'>" . implode('', $result->getMessages()['name']) . "</span>"; ?>
    <input type="text" name="name" placeholder="Your name" value="<?php
      if(isset($_POST['name'])) echo $_POST['name']; ?>"></label>

    <label>Email: <?php if($result != null && isset($result->getMessages()['email'])) echo "<span class='error'>" . implode('', $result->getMessages()['email']) . "</span>"; ?>
    <input type="email" name="email" placeholder="your@email.com" value="<?php
      if(isset($_POST['email'])) echo $_POST['email']; ?>"></label>

    <label>Comment: <?php if($result != null && isset($result->getMessages()['comment'])) echo "<span class='error'>" . implode('', $result->getMessages()['comment']) . "</span>"; ?>
    <textarea name="comment" cols="30" rows="10"><?php
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
</body>

</html>
