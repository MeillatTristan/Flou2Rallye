import {slide} from "./slide";
import 'owl.carousel';


slide("containerSlide");
$(document).ready(function(){
  $('.owl-carousel.albums').owlCarousel({
    loop:false,
    margin:10,
    nav:false,
    dot:true,
    autoplay:false,
    autoplayTimeout:8000,
    autoplayHoverPause:false,
    responsive:{
        0:{
            items: 1
        },
        762:{
            items:2
        },
        900:{
            items:3
        },
        1400:{
            items:4
        }
    }
})
});