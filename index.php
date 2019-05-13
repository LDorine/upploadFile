<?php

if(isset($_POST['delete'])) {
    unlink($_POST['picture']);  
}

if(isset($_POST['submit'])) {
    if(isset($_FILES['upload']['name'][0])) {
        $files = $_FILES['upload'];
        $uploaded = [];
        $failed = [];
    
        $allowed = ['jpeg','jpg','png','gif'];
        foreach($files['name'] as $poisition => $file_name) {
            $file_tmp = $files['tmp_name'][$poisition];
            $file_size = $files['size'][$poisition];
            $file_error = $files['error'][$poisition];
    
            $file_ext = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext));
    
            if(in_array($file_ext,$allowed)) {
                if($file_error === 0) {
                    if($file_size <= 1*1024*1024) {
                        $file_name_new = 'image'.uniqid('',true).'.'.$file_ext;
                        $file_destination = 'upload/'.$file_name_new;
    
                        if(move_uploaded_file($file_tmp,$file_destination)) {
                            $uploaded[$poisition] =$file_destination;
                        } else {
                            $failed[$poisition] = "Erreur :  upload impossible";
                        }
                    } else {
                        $failed[$poisition] = "Erreur : le fichier $file_name est trop volumineux";
                    }
                } else {
                    $failed[$poisition] = "Erreur : upload impossible du fichier $file_name";
                }
            } else {
                $failed[$poisition] = "Erreur : l'extension '$file_ext' du fichier $file_name n'est pas autorisé";
            }
        }
    }    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <form action="index.php" enctype="multipart/form-data" method="post">
        <div>
            <label for='upload'>Fichier à envoyer :</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <input type="file" id='upload' name="upload[]" multiple="multiple" />
        </div>
        <input type="submit" name="submit" value="Envoyer">
    </form>
    <?php if(!empty($failed)) :?>
    <div>
        <h1>Failed :</h1>
        <?php foreach($failed as $error) :?>
        <p><?= $error ?></p>
        <?php endforeach ?>
    </div>
    <?php endif; ?>
    <div>
        <h1>Uploaded :</h1>
        <div class="row">
            <?php 
            $fileUpload = new FilesystemIterator("upload/");
            foreach($fileUpload as $picture) :
            ?>
            <div class="col-3">
            <img src=<?= $picture; ?> alt="truc" class="img-thumbnail">
            <p><?= $picture; ?></p>
                <form method="post">
                    <input type="hidden" value=<?= $picture; ?> name="picture">
                    <button name="delete">Supprimer</button>
                </form>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</body>
</html>