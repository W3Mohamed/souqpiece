<!DOCTYPE hmtl>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/stylecon.css">
		<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
		<title>Connexion</title>
	 </head>

	<body>
		
		<div class="l-wrapper">
			<form method="POST">
				<h1>Log in</h1>
				<div class="input-box">
					<input type="text" name="first" placeholder="Utilisateur" required>
					<i class='bx bx-user'></i>
				 </div>
				<div class="input-box">
					<input type="password" name="password" placeholder="Mot de passe" required>
					<i class='bx bx-lock-alt'></i>
				 </div>
				<button type="submit" name="log" class="l-btn">Connecter</button>
			</form>
			<?php
                if(isset($_POST['log'])){
                    require_once('database.php');

                    $first = $_POST['first'];
                    $password = $_POST['password'];

                    if(!empty($first) && !empty($password)){

                        $sqlState = $pdo->prepare('SELECT * FROM utilisateur WHERE login=? AND password=?');
                        $sqlState->execute([$first,$password]);
                        if($sqlState->rowCount()>=1){
                            session_start();
                            $_SESSION['utilisateur'] = $sqlState->fetch();
                            header('location:index.php');
                        }else{
                    ?>
                            <div class="erreur">
                                <p>login ou password sont incorrect</p>
                            </div> 
                    <?php 
                            }
                    
                    }
                    else{
                    ?>
                        <div class="erreur">
                            <p>login password sont obligatoire</p>
                        </div> 
                    <?php 
                    }
                }

                ?>
		 </div>

	 </body>

</html>