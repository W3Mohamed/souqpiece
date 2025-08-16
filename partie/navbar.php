<?php
    require_once('dashboard/database.php');

    session_start();
    $session_id = session_id();

    $sqlPanier = $pdo->prepare('SELECT * FROM panier WHERE id_session=? AND status=false');
    $sqlPanier->execute([$session_id]);
    $nbCommande = $sqlPanier->rowCount();

    $sqlStates = $pdo->prepare('SELECT * FROM setting WHERE id=?');
    $sqlStates->execute([1]);
    $setting = $sqlStates->fetch(PDO::FETCH_ASSOC);
?>

<nav class="modern-navbar">
    <div class="nav-container">
        <!-- Logo -->
        <div class="nav-logo">
            <a href="index.php" class="logo-link">
                <span class="logo-text"><?=$setting['nom']?></span>
            </a>
        </div>

        <!-- Menu principal -->
        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item active">
                <a href="index.php" class="nav-link">
                    <i class="fas fa-home nav-icon"></i>
                    <span class="link-text">Accueil</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php#brand" class="nav-link">
                    <i class="fas fa-car nav-icon"></i>
                    <span class="link-text">Voitures</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="product.php" class="nav-link">
                    <i class="fas fa-box-open nav-icon"></i>
                    <span class="link-text">Produits</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php#contact" class="nav-link">
                    <i class="fas fa-envelope nav-icon"></i>
                    <span class="link-text">Contact</span>
                </a>
            </li>
        </ul>

        <!-- Panier et menu mobile -->
        <div class="nav-actions">
            <a href="panier.php" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if($nbCommande > 0): ?>
                <span class="cart-badge"><?=$nbCommande?></span>
                <?php endif; ?>
            </a>
            
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

<style>
/* Variables */
:root {
    --primary-color: #e12929;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');

    // Toggle menu mobile
    mobileMenuBtn.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        this.innerHTML = navMenu.classList.contains('active') 
            ? '<i class="fas fa-times"></i>' 
            : '<i class="fas fa-bars"></i>';
    });

    // Fermer le menu quand on clique sur un lien
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if(window.innerWidth <= 992) {
                navMenu.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    });

    // Changement de style au scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.modern-navbar');
        if(window.scrollY > 50) {
            navbar.style.background = 'rgba(0, 0, 0, 0.9)';
            navbar.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.3)';
        } else {
            navbar.style.background = 'rgba(0, 0, 0, 0.8)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        }
    });
});
</script>