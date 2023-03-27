var $ = document;
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

        //chaque pitem récupère la balise dont l'id lui correspond
        //slide1 définit slideInner.style.right à 0 (position du premier slide)
        //chaque fonction slide définit ensuite slideInner.style.right à la longueur d'un slide*n, avec n = le numéro du slide voulu-1

        pitem1: $.querySelector('#pitem1'),
        slide1() {
          currentPosition = 0;
          sliderInner.style.right = currentPosition;
          control.changeHighlight();
        },

        pitem2: $.querySelector('#pitem2'),
        slide2() {
            currentPosition = parseFloat(sliderItemWidth);
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight();
        },

        pitem3: $.querySelector('#pitem3'),
        slide3() {
            currentPosition = parseFloat(sliderItemWidth)*2;
            sliderInner.style.right = currentPosition + '%';
            control.changeHighlight()
        },

        changeHighlight() {
          //change l'opacité des éléments du menu lattéral en fonction de currentPosition
          switch (currentPosition) {
            case 0:
              pitem1.classList.remove('bg-gray-400');
              pitem1.classList.add('bg-gray-600');
              pitem2.classList.remove('bg-gray-600');
              pitem2.classList.add('bg-gray-400');
              pitem3.classList.remove('bg-gray-600');
              pitem3.classList.add('bg-gray-400');
              break;
            case 100:
              pitem1.classList.remove('bg-gray-600');
              pitem1.classList.add('bg-gray-400');
              pitem2.classList.remove('bg-gray-400');
              pitem2.classList.add('bg-gray-600');
              pitem3.classList.remove('bg-gray-600');
              pitem3.classList.add('bg-gray-400');
              break;
            case 200:
              pitem1.classList.remove('bg-gray-600');
              pitem1.classList.add('bg-gray-400');
              pitem2.classList.remove('bg-gray-600');
              pitem2.classList.add('bg-gray-400');
              pitem3.classList.remove('bg-gray-400');
              pitem3.classList.add('bg-gray-600');
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
    control.pitem1.addEventListener('click', control.slide1)
    control.pitem2.addEventListener('click', control.slide2)
    control.pitem3.addEventListener('click', control.slide3)


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

  }
  sliderMe();

});