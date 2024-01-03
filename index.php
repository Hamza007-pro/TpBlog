<?php
$host = 'localhost';
$db = 'myBlogDb';
$user = 'root';
$pass = '';

// Create MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAllPosts()
{
    global $conn;
    $result = $conn->query('SELECT * FROM posts');
    return $result->fetch_all(MYSQLI_ASSOC);
}


function updatePost($id, $title, $content)
{
    global $conn;
    $stmt = $conn->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
    $stmt->bind_param('ssi', $title, $content, $id);

    if ($stmt->execute()) {
        echo "Post updated successfully!";
    } else {
        echo "Error updating post: " . $stmt->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
   $id = isset($_POST['id']) ? $_POST['id'] : null;
   $title = isset($_POST['title']) ? $_POST['title'] : null;
   $content = isset($_POST['content']) ? $_POST['content'] : null;

   if ($id !== null && $title !== null && $content !== null) {
       updatePost($id, $title, $content);
   } else {
       echo "Invalid or missing data for update.";
   }
}

$posts = getAllPosts();
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>microo</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- awesome fontfamily -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
      <style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
form {
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
</style>
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="" /></div>
      </div>
      <!-- end loader -->
      <div id="mySidepanel" class="sidepanel">
         <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
         <a class="active" href="index.php">Home</a>
         <a href="addBlog.php">Add New Blog</a>
      </div>
      <!-- header -->
      <header>
         <!-- header inner -->
         <div class="head-top">
            <div class="container-fluid">
               <div class="row d_flex">
                  <div class="col-sm-3">
                     <div class="logo">
                        <a href="index.html"><img src="images/logo.png" /></a>
                     </div>
                  </div>
                  <div class="col-sm-9">
                     <ul class="email text_align_right">
                        <li> <button class="openbtn" onclick="openNav()"><img src="images/menu_btn.png"></button></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <!-- end header -->
      <!-- start slider section -->
      <div id="top_section" class=" banner_main">
         <div class="container">
            <div class="row d_flex">
               <div class="col-md-6">
                  <div class="airmic">
                     <h1>The Air Blog </h1>
                     <p>Create a unique and beautiful blog easily.
                     </p>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mic_img">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="about" class="about">
         <div class="container-fluid">
         <?php foreach ($posts as $post) : ?>
            <div class="row d_flex">
               <div class="col-md-6">
                  <div class="titlepage text_align_left">
                     <h2><?= $post['title'] ?></h2>
                     <p><?= $post['content'] ?></p>
                     <button class="read_more" id="myBtn">Edit Blog</button>
                     <div id="myModal" class="modal">
                        <div class="modal-content">
                           <span class="close">&times;</span>
                           <form method="post">
                              <input type="text" name="title" placeholder="Enter Title" required>
                              <textarea name="content" placeholder="Enter Content" rows="4" required></textarea>
                              <input type="hidden" name="id" value="<?= $post['id'] ?>">
                              <button type="submit" name="update">Update Post</button>
                           </form>
                        </div>
                     </div>
                  </div>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
         
      </div>
      <div class="contact left_cross_right">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage text_align_left">
                     <h2>Request a call back.</h2>
                     <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or raThere are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or raThere are many variations of passages of Lorem      </p>
                  </div>
               </div>
               <div class="col-md-12">
                  <form id="request" class="main_form">
                     <div class="row">
                        <div class="col-md-12 ">
                           <input class="contactus" placeholder="You Name" type="type" name="Name"> 
                        </div>
                        <div class="col-md-6">
                           <input class="contactus" placeholder="Email" type="type" name="Email"> 
                        </div>
                        <div class="col-md-6">
                           <input class="contactus" placeholder="Phone Number" type="type" name="Phone Number">                          
                        </div>
                        <div class="col-md-12">
                           <textarea class="textarea" placeholder="Message" type="Message"></textarea>
                        </div>
                        <div class="col-md-12">
                           <button class="send_btn">Send</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- end contact section -->
      <!-- footer -->
      <footer>
         <div class="footer">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <a class="logo_footer"><img src="images/logo.png" alt="#"/></a>
                  </div>
                  <div class="col-md-5">
                     <div class="Informa conta">
                        <h3>Adderess</h3>
                        <ul>
                           <li> Jobify Inc Canada. 545 Younge St, <br>Suite 11 Toronto, Ontario M4K 6F4
                           </li>
                        </ul>
                     </div>
                     <div class="Informa helpful">
                        <ul>
                           <li><a href="index.html">Home</a></li>
                           <li><a href="about.html">About</a></li>
                           <li><a href="services.html">Services</a></li>
                           <li><a href="shop.html">Shop</a></li>
                           <li><a href="contact.html">Contact</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="Informa conta">
                        <h3>Contact Us</h3>
                        <ul>
                           <li> <a href="Javascript:void(0)"> (+71) 897648934
                              </a>
                           </li>
                           <li> <a href="Javascript:void(0)">  demo123@gmail.com
                              </a>
                           </li>
                        </ul>
                     </div>
                     <ul class="social_icon text_align_center">
                        <li> <a href="Javascript:void(0)"><i class="fa fa-facebook-f"></i></a></li>
                        <li> <a href="Javascript:void(0)"><i class="fa fa-twitter"></i></a></li>
                        <li> <a href="Javascript:void(0)"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                     </ul>
                  </div>
                  <div class="col-md-3">
                     <div class="Informa">
                        <h3>Newsletter</h3>
                        <form class="newslatter_form">
                           <input class="ente" placeholder="Enter your email" type="text" name="Enter your email">
                           <button class="subs_btn">Subscribe</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="copyright text_align_center">
               <div class="container">
                  <div class="row">
                     <div class="col-md-10 offset-md-1">
                        <p>© 2020 All Rights Reserved. Design by  <a href="https://html.design/"> Free Html Template</a></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/custom.js"></script>
      <script>

      var modal = document.getElementById("myModal");

var btn = document.getElementById("myBtn");


var span = document.getElementsByClassName("close")[0];


btn.onclick = function() {
  modal.style.display = "block";
}


span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
   </body>
</html>