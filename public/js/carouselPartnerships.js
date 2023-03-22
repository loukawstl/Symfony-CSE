var $ = document;
console.log("quoicoubeh")
$.addEventListener('DOMContentLoaded', function() {

  const sliderMe = () => {
    let currentPosition = 0,
      //récupère toutes les balises avec une class .slider-item dans une liste
      sliderItem = document.querySelectorAll('.slider-item'),
      //récupère la longueur du premier slide du carousel
      sliderItemWidth = window
      .getComputedStyle(sliderItem[0])
      .flexBasis.match(/\d+\.?\d+/g),
      sliderInner = $.querySelector('.slider-inner'),
      

      control = {

        //next récupère la balise qui a pour id next et prev récupère la balise qui a pour id prev

        //slideNext et slidePrev rajoute/retire une fois la longueur d'un slide du carousel à slideInner.style.right
        //avant de changer slideInner.style.right un if teste si la nouvelle position ne sera pas en dehors des limites du carousel (0 et limitPosition)
        //si c'est le cas le carousel fait une boucle et passe au première/dernier slide.
        next: $.querySelector('#next'),
        slideNext() {
          currentPosition += parseFloat(sliderItemWidth);
          if (currentPosition > limitPosition) {
            currentPosition = 0;
          }
          sliderInner.style.right = currentPosition + '%';
          control.changeHighlight();
        },

        prev: $.querySelector('#prev'),
        slidePrev() {
          currentPosition -= parseFloat(sliderItemWidth);
          if (currentPosition < 0) {
            currentPosition = limitPosition;
          }
          sliderInner.style.right = currentPosition + '%';
          control.changeHighlight();
        },

        //chaque pslide récupère la balise dont l'id lui correspond
        //slide1 définit slideInner.style.right à 0 (position du premier slide)
        //chaque fonction slide définit ensuite slideInner.style.right à la longueur d'un slide*n, avec n = le numéro du slide voulu-1

        pslide1: $.querySelector('#pslide1'),
        slide1() {
          currentPosition = 0;
          sliderInner.style.right = currentPosition;
          control.changeHighlight();
        },

        pslide2: $.querySelector('#pslide2'),
        slide2() {
            currentPosition = parseFloat(sliderItemWidth);
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight();
        },

        pslide3: $.querySelector('#pslide3'),
        slide3() {
            currentPosition = parseFloat(sliderItemWidth)*2;
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight()
        },

        pslide4: $.querySelector('#pslide4'),
        slide4() {
            currentPosition = parseFloat(sliderItemWidth)*3;
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight()
        },

        pslide5: $.querySelector('#pslide5'),
        slide5() {
            currentPosition = parseFloat(sliderItemWidth)*4;
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight()
        },

        changeHighlight() {
          //change l'opacité des éléments du menu lattéral en fonction de currentPosition
          switch (currentPosition) {
            case 0:
              pslide1.style.opacity = "1";
              pslide2.style.opacity = "0.5";
              pslide3.style.opacity = "0.5";
              pslide4.style.opacity = "0.5";
              pslide5.style.opacity = "0.5";
              break;
            case 100:
              pslide1.style.opacity = "0.5";
              pslide2.style.opacity = "1";
              pslide3.style.opacity = "0.5";
              pslide4.style.opacity = "0.5";
              pslide5.style.opacity = "0.5";
              break;
            case 200:
              pslide1.style.opacity = "0.5";
              pslide2.style.opacity = "0.5";
              pslide3.style.opacity = "1";
              pslide4.style.opacity = "0.5";
              pslide5.style.opacity = "0.5";
              break;
            case 300:
              pslide1.style.opacity = "0.5";
              pslide2.style.opacity = "0.5";
              pslide3.style.opacity = "0.5";
              pslide4.style.opacity = "1";
              pslide5.style.opacity = "0.5";
              break;
            case 400:
              pslide1.style.opacity = "0.5";
              pslide2.style.opacity = "0.5";
              pslide3.style.opacity = "0.5";
              pslide4.style.opacity = "0.5";
              pslide5.style.opacity = "1";
              break;
            default:
              console.log("error");
              break;
          }
        }

      },
      //limitPosition récupère la position limite du carousel
      limitPosition = sliderItemWidth * (sliderItem.length - Math.floor(100 / sliderItemWidth));

    //addEventListener permet d'executer une fonction en fonction d'un evenement, ici un clique sur un des controls
    control.next.addEventListener('click', control.slideNext)
    control.prev.addEventListener('click', control.slidePrev)
    control.pslide1.addEventListener('click', control.slide1)
    control.pslide2.addEventListener('click', control.slide2)
    control.pslide3.addEventListener('click', control.slide3)
    control.pslide4.addEventListener('click', control.slide4)
    control.pslide5.addEventListener('click', control.slide5)


    oldPosition = 0;
    const interval = setInterval(function() {
      // slide toute les 3 secondes si la position n'a pas été changé
      if (oldPosition == currentPosition){
        control.slideNext();
      }
      oldPosition = currentPosition;
    }, 3000);

    //highlight le premier élément au chargement de la page
    control.changeHighlight();
   


    //pour eviter des problèmes d'affichage le carousel retourne au premier slide quand la fenêtre change de taille
    window.addEventListener("resize",function(){
      currentPosition = 0;
      $.querySelector('.slider-inner').style.right = currentPosition + '%';
    })
  }
  sliderMe();

  window.addEventListener("resize", sliderMe)

});