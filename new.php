
<?php
require 'inc/Autoloader.php';


  if (empty(Session::getInstance()->read('id'))) {
  Session::getInstance()->setFlash('danger','Vous devez etre connecté');
  App::redirect('signin.php');
}



if ( !empty($_POST) && !empty($_FILES)) {
    $db = App::getDatabase();
    $errors = [];
   if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_FILES['img']) ){

    $session_id = Session::getInstance()->read('id');
 
    $name_file = $_FILES['img']['name'];
    $name_extension = strrchr($name_file, ".");
    $extensions_autorisation = array('.png','.PNG','.jpg','.JPG');
    $file_tmp_name = $_FILES['img']['tmp_name'];
    $file_dest = 'uploads/'.$name_file;
    $date =  date("Y-m-d H:i:s");

    if (in_array($name_extension, $extensions_autorisation)) {
        if (move_uploaded_file( $file_tmp_name,$file_dest)) {
  
        $insert = $db->query("INSERT INTO advertisements (title, photo, Description,date,id_organisaton)
        VALUES (?,?,?,?,?)",[$_POST['title'],$file_dest,$_POST['description'],$date,$session_id]);

         Session::getInstance()->setFlash('success','Annonce bien crée');
         App::redirect('my.php');

       }
     }
     else{
        $errors['img']= "Pour l'images seuls les extenetions PNG ou JPG sont autorisées";
      }
  }
  else{
    $errors[]="tous les champs doivent être remplis";
  }
  }
  

?>

<?php include 'layouts/login_header.html';?>
    <title>Nouvelle annonce | We-Share</title>
    <style>
        html,body {
        height: 100%;
        }

        body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        img{ width: 200px; height: 200px;}
        .form-signin-signup {
        width: 100%;
        max-width: 55%;
        padding: 15px;
        margin: auto;
        }

        .form-signin-signup .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        }
        .form-signin-signup .form-select {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        }

        .form-signin-signup .form-control:focus {
        z-index: 2;
        }
        .form-signin-signup input[type="text"] {
        margin-bottom: 10px;
        border-radius: 5px;

        }
        .form-signin-signup input[type="email"] {
        margin-bottom: 10px;
        border-radius: 5px;

        }
    
        .form-signin-signup input[type="file"] {
        margin-bottom: 10px;
        border-radius: 5px;
        
        }
        textarea {
        margin-bottom: 10px;
        border-radius: 5px;
        
        }
        .form-signin-signup button{
            max-width: 40%;
        }

    </style>
  </head>
  <body class="text-center">
    
<main class="form-signin-signup">
  <form action="" method="POST" enctype="multipart/form-data">
    <img class="mb-4 rounded-circle" src="assets/images/We-Share-logo.png" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Nouvelle Annonce</h1>

    <!-- errors controle -->
    <?php  if (!empty($errors)):?>
      <div class="alert alert-danger">
        <p>Vous n'avez pas uploader les fichiers  correctement</p>
        <?php foreach ($errors as $error): ?>
          <ul>
            <li><?= $error; ?></li>
          <?php endforeach; ?>
          </ul>
      </div>
    <?php endif; ?>


    <label for="inputEmail" class="visually-hidden">Le titre</label>
    <input type="text" id="inputEmail" name="title" class="form-control" placeholder="Le titre d'annonce" required autofocus>

    <label class="form-label visually-hidden" for="customFile">Choisie une image pour votre annonce</label>
    <input type="file" class="form-control" name ="img" id="customFile" placeholder="L'image" required/>
    
    <label for="InputDiscription" class="form-label visually-hidden">Discription de l'annonce</label>
    <textarea type="text" name ="description" class="form-control" id="InputDiscription" placeholder="Veuillez saisir la discription de l'annonce"  rows="6" cols="50" required></textarea>
            

    <button class="w-100 btn btn-lg btn-primary" type="submit">Ajouter</button>
    <p class="mt-5 mb-3 text-muted">&copy; We Share 2021</p>
  </form>
</main>

  
  </body>
</html>