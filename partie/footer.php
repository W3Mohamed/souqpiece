 <!--=========================================================
                        footer
    ========================================================-->
    <?php
        require_once('dashboard/database.php');
        $sqlStates = $pdo->prepare('SELECT * FROM setting WHERE id=?');
        $sqlStates->execute([1]);
        $setting = $sqlStates->fetch(PDO::FETCH_ASSOC);
    ?>
	<footer  style="padding-top: 39px;padding-bottom: 49px;">
    <div class="footer">
        <div class="footer-col">
                 <h4>Navigation</h4>
                     <ul>
                         <li><a href="index.php">Acceul</a></li>
                         <li><a href="index.php#section-categories">Categories</a></li>
                         <li><a href="index.php#brand">Voiture</a></li>
                         <li><a href="index.php#section-produits">Produits</a></li>
                     </ul>
        </div>
        <!--di lwla *************************************************************************************-->
        <div class="footer-col">
                 <h4>Get Help</h4>
                     <ul>
                         <li><a href="#">A propos</a></li>
                         <li><a href="#">Retour</a></li>
                         <li><a href="#">Livraison</a></li>
                         <li><a href="#">Garentie</a></li>
                     </ul>
        </div>
        <!--di zawja ******************************************* ******************************************-->
        <div class="footer-col">
                 <h4>Contact</h4>
                     <ul>
                         <li><a href="#">0<?=$setting['tel']?></a></li>
                         <li><a href="#"><?=$setting['adresse']?></a></li>
                     </ul>
        </div>
        <!--di talta *************************************************************************************-->	
        <div class="footer-col">
                 <h4>Connect With Us</h4>
                     <div class="links">
                         <a href="<?=$setting['facebook']?>"><i class="fab fa-facebook-f"></i></a>
                         <a href="<?=$setting['insta']?>"><i class="fab fa-instagram"></i></a>
                         <a href="<?=$setting['tiktok']?>"><i class="fab fa-tiktok"></i></a>
                     </div>
        </div>    
        
        <div class="logo-footer">

            <a href="#"><?=$setting['nom']?></a> 

        </div>
    </div>
        <div class="copy">
            <hr>
            <h3 id="copyright">Développé par <a href="https://www.instagram.com/w3mohamed/">W3mohamed</a></h3>        
        </div>

         
    </footer>