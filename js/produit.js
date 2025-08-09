document.addEventListener('DOMContentLoaded', function() {
/*==============================Gallery produit==================*/

    var leftBtn = document.getElementById('leftBtn');
    var rightBtn = document.getElementById('rightBtn');
    var gallery = document.querySelector('.second-img');
    var principaleImg = document.querySelector('.img-principale img');
    var secondaryImages = document.querySelectorAll('.second-img img');
    var currentIndex = 0;

    gallery.addEventListener("wheel" , (evt) =>{
        evt.preventDefault();
        gallery.scrollLeft +=  evt.deltaY;
    });

    rightBtn.onclick = function(){
        currentIndex = (currentIndex - 1 + secondaryImages.length) % secondaryImages.length;
        principaleImg.src = secondaryImages[currentIndex].src;
        gallery.scrollLeft += 130;
    }
    leftBtn.onclick = function(){
        currentIndex = (currentIndex + 1) % secondaryImages.length;
        principaleImg.src = secondaryImages[currentIndex].src;
        gallery.scrollLeft -= 130;
    } 
      // Fonction pour changer l'image principale
      function changeImage(element) {
        principaleImg.src = element.src;
        currentIndex = Array.prototype.indexOf.call(secondaryImages, element);
    }
    for (var i = 0; i < secondaryImages.length; i++) {
        secondaryImages[i].onclick = function(){
            changeImage(this);
        }
    }
    // Changer l'image principale lors du clic sur une image secondaire

}); 