let interval;

export function slide(idContainer) {
  const containerSlide = document.getElementById(idContainer);
  if (containerSlide) {
    var slides = containerSlide.querySelectorAll('.slide');
    var nbSlides = slides.length;
    var activeSlide = 0;
    const containerNav = document.getElementById('slideNav');
    
    goFirst();
    doSlide();
    containerSlide.addEventListener('mouseover', (e) => {
      clearInterval(interval);
    })

    containerSlide.addEventListener('mouseleave', (e) => {
      doSlide()
    })

    containerNav.querySelector('.left').addEventListener('click', (e) => {
      console.log('left')
      toLeft();
    })
    containerNav.querySelector('.right').addEventListener('click', (e) => {
      toRight();
    })
  }

  function goFirst(params) {
    for (let index = 0; index < slides.length; index++) {
      const slide = slides[index];
      if (index === 0) {
        slide.classList.add('active');
        activeSlide = 0;
      } else {
        slide.classList.remove('active');
      }

      slide.dataset.id = index;
      slide.dataset.translateX = index * 100;
      slide.style.transform = "translateX("+ index * 100 +"%)"
    }
  }

  function goLast(params) {
    for (let index = 0; index < slides.length; index++) {
      const slide = slides[index];
      if (index + 1 === nbSlides) {

        slide.classList.add('active');
        activeSlide = nbSlides - 1;
      } else {
        slide.classList.remove('active');
      }

      slide.dataset.id = index;
      slide.dataset.translateX = ((index * 100 * - 1) - 100 + nbSlides * 100) * -1;
      slide.style.transform = "translateX("+ (((index * 100 * - 1) - 100 + nbSlides * 100) * -1) +"%)"
    }
  }

  function toLeft() {
    if (activeSlide <= 0) {
      goLast();
      return
    }
    for (let index = 0; index < slides.length; index++) {
      const slide = slides[index];
      const actualTranslate = slide.dataset.translateX;
      const newTranslate = parseInt(actualTranslate) + 100;
      console.log(activeSlide)
      
      if (newTranslate === 0) {
        slide.classList.add('active')
        activeSlide--;
      } else {
        slide.classList.remove('active')
      }
      slide.dataset.translateX = newTranslate;
      slide.style.transform = "translateX("+ newTranslate +"%)"
    }
  }
  
  function toRight() {
    if (activeSlide + 1 === nbSlides) {
      goFirst();
      return
    }
    for (let index = 0; index < slides.length; index++) {
      const slide = slides[index];
      const actualTranslate = slide.dataset.translateX;
      const newTranslate = parseInt(actualTranslate) - 100;
      
      if (newTranslate === 0) {
        slide.classList.add('active')
        activeSlide++;
      } else {
        slide.classList.remove('active')
      }
      slide.dataset.translateX = newTranslate;
      slide.style.transform = "translateX("+ newTranslate +"%)"
    }
  }
  
  function doSlide() {
    interval = setInterval(function(){
      toRight();
    }, 6000);
  }
}
