(function($) {

/*Google Map Style*/
var CustomMapStyles  = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]

var windowWidth = $(window).width();
$('.navbar-toggle').on('click', function(){
	$('#mobile-nav').slideToggle(300);
});
	
  
//matchHeightCol
if($('.mHc').length){
  $('.mHc').matchHeight();
};
if($('.mHc1').length){
  $('.mHc1').matchHeight();
};
if($('.mHc2').length){
  $('.mHc2').matchHeight();
};
if($('.mHc3').length){
  $('.mHc3').matchHeight();
};
if($('.mHc4').length){
  $('.mHc4').matchHeight();
};
if($('.mHc5').length){
  $('.mHc5').matchHeight();
};


//$('[data-toggle="tooltip"]').tooltip();

//banner animation
$(window).scroll(function() {
  var scroll = $(window).scrollTop();
  $('.page-banner-bg').css({
    '-webkit-transform' : 'scale(' + (1 + scroll/2000) + ')',
    '-moz-transform'    : 'scale(' + (1 + scroll/2000) + ')',
    '-ms-transform'     : 'scale(' + (1 + scroll/2000) + ')',
    '-o-transform'      : 'scale(' + (1 + scroll/2000) + ')',
    'transform'         : 'scale(' + (1 + scroll/2000) + ')'
  });
});


if($('.fancybox').length){
$('.fancybox').fancybox({
    //openEffect  : 'none',
    //closeEffect : 'none'
  });

}


/**
Responsive on 767px
*/

// if (windowWidth <= 767) {
  $('.toggle-btn').on('click', function(){
    $(this).toggleClass('menu-expend');
    $('.toggle-bar ul').slideToggle(500);
  });


// }



// http://codepen.io/norman_pixelkings/pen/NNbqgG
// https://stackoverflow.com/questions/38686650/slick-slides-on-pagination-hover


/**
Slick slider
*/
if( $('.responsive-slider').length ){
    $('.responsive-slider').slick({
      dots: true,
      infinite: false,
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 700,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
}

if( $('.catagorySlider').length ){
  var swiper = new Swiper('.catagorySlider', {
      slidesPerView: 1,
      loop: true,
      navigation: {
        nextEl: '.catagorySlider-arrows .swiper-button-next',
        prevEl: '.catagorySlider-arrows .swiper-button-prev',
      },
      breakpoints: {
         639: {
          slidesPerView: 2,
          spaceBetween: 0,
        },
        991: {
          slidesPerView: 3,
          spaceBetween: 0,
        },
        1199: {
          loop: false,
          slidesPerView: 4,
          spaceBetween: 0,
        },
        1920: {
          loop: false,
          slidesPerView: 4,
          spaceBetween: 0,
        },
      }
    });
}

if( $('#mapID').length ){
  var latitude = $('#mapID').data('latitude');
  var longitude = $('#mapID').data('longitude');

  var myCenter= new google.maps.LatLng(latitude,  longitude);
  function initialize(){
      var mapProp = {
        center:myCenter,
        mapTypeControl:true,
        scrollwheel: false,
        zoomControl: true,
        disableDefaultUI: true,
        zoom:7,
        streetViewControl: false,
        rotateControl: true,
        mapTypeId:google.maps.MapTypeId.ROADMAP,
        styles: CustomMapStyles
        };

      var map= new google.maps.Map(document.getElementById('mapID'),mapProp);
      var marker= new google.maps.Marker({
        position:myCenter,
          //icon:'map-marker.png'
        });
      marker.setMap(map);
  }
  google.maps.event.addDomListener(window, 'load', initialize);

}



/* BS form Validator*/
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();


  /*start Of Niaz*/

// accordion
if( $('.hh-accordion-title').length ){
  $('.hh-accordion-title').click(function(){
      $(this).next().slideToggle(300);
      $(this).parent().siblings().find('.hh-accordion-des').slideUp(300);
      $(this).toggleClass('hh-accordion-active');
      $(this).parent().siblings().find('.hh-accordion-title').removeClass('hh-accordion-active');
  });
}
//Masonry
if(windowWidth > 767) {
  if( $('.our-proj-grid').length ){
    $('.our-proj-grid').masonry({
      // options
      itemSelector: '.our-proj-grid-item',
    });
  };
};

// frst-project slider
$('.frstProjSlider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: false,
    dots: true,
    dotsClass: 'custom_paging',
    customPaging: function (slider, i) {
        console.log(slider);
        return  (i + 1);
    }
  });


  /*start Of Noyon*/
  $(".woocommerce-billing-fields .form-row label").each(function () {
    el = $(this);
    label_value = el.text();
    el.hide();
    el.next().children('input').attr('placeholder', label_value);
  });




  $(".woocommerce-form-row label").each(function () {
    el = $(this);
    label_value = el.text();
    el.hide();
    el.next('input').attr('placeholder', label_value);
  });









  if( $('.lwBnrSlider').length ){
    $('.lwBnrSlider').slick({
      dots: true,
      infinite: false,
      arrows: true,
      autoplay: false,
      autoplaySpeed: 4000,
      speed: 700,
      slidesToShow: 1,
      slidesToScroll: 1,
    });
}


if( $('.counter').length ){
  $('.counter').counterUp({
    delay: 10,
    time: 1000
  });
}



if( $('.humberger-icon').length ){
  $('.humberger-icon').click(function(){
    $('body').toggleClass('allWork');
  });
}
if( $('li.menu-item-has-children a').length ){
  $('li.menu-item-has-children a').click(function(e){
   event.preventDefault();
   $(this).next().slideToggle(300);
   $(this).parent().toggleClass('sub-menu-arrow');
 });
}





 function leftrtslickprev(){
  var windowWidth3 = $(window).width();
   var ConWidth = $(".container").width();
   var LftRtOffset = (windowWidth3 - ConWidth) / 2;
   var leftMargin = LftRtOffset -50;
   /*767*/
   var xsTopoffset1 = $('.bnr-bg-rgt').height();
   var xsTopoffset = xsTopoffset1 / 2;
  $('.lw-bnr-slider button.slick-prev').css('left', leftMargin);
  $('.forest-proj-slider button.slick-prev').css('left', leftMargin);
  $('.lw-bnr-slider button.slick-next').css('right', leftMargin);
  $('.forest-proj-slider button.slick-next').css('right', leftMargin);

  if (windowWidth3 <= 1199) {
    $('.lw-bnr-slider button.slick-prev').css('left', LftRtOffset);
    $('.forest-proj-slider button.slick-prev').css('left', LftRtOffset);
    $('.lw-bnr-slider button.slick-next').css('right', LftRtOffset);
    $('.forest-proj-slider button.slick-next').css('right', LftRtOffset);
  }
  if (windowWidth3 <= 767) {
    $('.lw-bnr-slider button.slick-prev').css('top', xsTopoffset);
    $('.forest-proj-slider button.slick-prev').css('top', xsTopoffset);
    $('.lw-bnr-slider button.slick-next').css('top', xsTopoffset);
    $('.forest-proj-slider button.slick-next').css('top', xsTopoffset);
  }
}
leftrtslickprev();
$(window).resize(function(){
  leftrtslickprev();
});
var windowWidth4 = $(window).width();


//windowWidth
function attrvaluupdate(){
  var windowWidth2 = $(window).width();
  var containerWidth = $('.container').width();
  var leftOffsetCal = ((windowWidth2 - containerWidth ) / 2)+1;
  $('.fl-angle-hdr-join').css('width', leftOffsetCal );
  $('.custom_paging').css('left',leftOffsetCal);
};
attrvaluupdate();
$(window).resize(function(){
    attrvaluupdate();
});

  if( $('.winnersSlider').length ){
    $('.winnersSlider').slick({
      dots: true,
      infinite: false,
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 700,
      arrows: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            arrows: false,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            arrows: false,
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            arrows: false,
          }
        }
      ]
    });
}

if( $('.latesCompititionsSlider').length ){
    $('.latesCompititionsSlider').slick({
      dots: true,
      infinite: false,
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 700,
      arrows: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            arrows: false,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            arrows: false,
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            arrows: false,
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
}
// jquery minus plus quantity

if( $('.qty').length ){
  $('.qty').each(function() {
    var spinner = $(this),
      input = spinner.find('input[type="number"]'),
      btnUp = spinner.find('.plus'),
      btnDown = spinner.find('.minus'),
      min = 1,
      max = input.attr('max');

    btnUp.click(function() {
      var oldValue = parseFloat(input.val());
      if (oldValue < max) {
        var newVal = oldValue + 1;
      } else {
        var newVal = oldValue;
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });

    btnDown.click(function() {
      var oldValue = parseFloat(input.val());
      if (oldValue <= min) {
        var newVal = oldValue;
      } else {
        var newVal = oldValue - 1;
      }
      spinner.find("input").val(newVal);
      spinner.find("input").trigger("change");
    });

  });
}

$('.modallink').on('click', function(){
  var title = $(this).data("title");
  var desc = $(this).data("desc");
  var imageURL = $(this).data("img");
  $("#Modal #Modal-Label").text(title);
  $("#Modal #modal-content").html(desc);
  $("#Modal #modal-img").attr('src',imageURL);
  $('#Modal').modal('show'); 
});





$('.main-gallery').slick({
   slidesToShow: 1,
   slidesToScroll: 1,
   arrows: false,
   fade: true,
   asNavFor: '.thumbnail-crtl'
 });
 $('.thumbnail-crtl').slick({
   slidesToShow: 5,
   slidesToScroll: 1,
   asNavFor: '.main-gallery',
   dots: true,
   focusOnSelect: true,
   responsive: [
        {
          breakpoint: 1600,
          settings: {
            slidesToShow: 4,
            arrows: true,
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            arrows: true,
          }
        },
        {
          breakpoint: 768,
          settings: {
            dots: false,
            arrows: true,
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 360,
          settings: {
            dots: false,
            arrows: true,
            slidesToShow: 3,
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
 });

// Competitions filter
var filtered = false;
$('.filter-btn').on('click',function(e){
  e.preventDefault();
    $('.filter-btn').removeClass('active');
    var filter = $(this).data('attribute');
    if(filter=='all'){
        $('.latesCompititionsSlider').slick('slickUnfilter');
    }else{
        $('.latesCompititionsSlider').slick('slickUnfilter').slick('slickFilter','.'+filter);
    }
    $(this).addClass('active');
    filtered = true;
}); 

// Competitions filter
var Winfiltered = false;
$('.win-filter-btn').on('click',function(e){
  e.preventDefault();
    $('.win-filter-btn').removeClass('active');
    var winFilter = $(this).data('attribute');
    if(winFilter=='all'){
        $('.winnersSlider').slick('slickUnfilter');
    }else{
        $('.winnersSlider').slick('slickUnfilter').slick('slickFilter','.'+winFilter);
    }
    $(this).addClass('active');
    Winfiltered = true;
}); 

})(jQuery);