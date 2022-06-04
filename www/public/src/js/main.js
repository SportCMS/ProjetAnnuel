


/******** MENU HAMBURGER  ********/
$(document).ready(function(){
    $('#menu-button').click(function(){
        $('#site-nav').toggleClass('open');
    })
})

$(window).scroll(function(){
    if($(this).scrollTop() > 0){
        $('#site-header').addClass('sticky');
    }else{
        $('#site-header').removeClass('sticky');
    }

    var windowHeight = $(this).height();
    var windowScroll = $(this).scrollTop();

    console.log(windowHeight, windowScroll);

    $('main > section:not(#section1)').each(function(){
        var sectionTop = $(this).position().top;
        var offset = windowHeight - (sectionTop - windowScroll);

        if(offset >= windowHeight / 3){
            $(this).css('opacity', 1);
            $(this).css('top', 0);
        }
        console.log(offset);
    })
})

/******** SLIDER ********/
$(document).ready(function(){

    // Si des éléments ont la classe .slider
    if($('.slider').length){
        $('.slider').each(function(index){
            sliderInit($(this));
        })
    }
    
    // Ajouter un autoplay au premier slider
    var premierSlider = $('.slider')[0];
    var interval = setInterval(function(){
        next($(premierSlider))
    }, 5000);
    
    // Supprimer l'interval avec clearInterval(interval)
    
})
    
function sliderInit(element){

    let container = $('<div></div>');
    container.addClass('slides-container');
    container.html(element.html());
    
    element.html(container);
    element.find('img').addClass('slide');
    
    let nav = $('<nav/>');
    nav.append('<button class="prev"></button>');
    nav.append('<button class="next"></button>');

    element.append(nav);
    element.attr('data-currentSlide', 0);
    
    element.find('.prev').click(function(){
        prev(element);
    })
    element.find('.next').click(function(){
        next(element);
    })
}
    
function prev(slider){
    let attrValue = Number(slider.attr('data-currentSlide'));
    slider.attr('data-currentSlide', attrValue - 1);
    slide(slider);
}

function next(slider){
    let attrValue = Number(slider.attr('data-currentSlide'));
    slider.attr('data-currentSlide', attrValue + 1);
    slide(slider);
}
    
function slide(slider){
    let attrValue = Number(slider.attr('data-currentSlide'));
    let leftValue = attrValue * 100 * (-1);
    
    let container = slider.find('.slides-container');
    var totalSlides = container.children('img').length;

    // Désactiver la nav
    disableNav(slider);
    
    // Si on dépasse la dernière image : 
    // - ajouter un clone de la 1ere image au container
    // - ecouter la fin de la transition : 
    // - enlever la transition du container
    // - 'rembobiner' le container à l'image 1
    // - supprimer le clone
    // - remettre la transition sur le container

    if(attrValue == totalSlides){
        let clone = container.children('img:first-child').clone();
        container.append(clone);
        
        container.on('transitionend', function(){
        
            container.off('transitionend');
            
            container.css('transition', 'none');
            container.css('left', 0);
            container.children('img:last-child').remove();
            slider.attr('data-currentSlide', 0);
            setTimeout(function(){
                container.css('transition', 'left 1s');
            }, 20);
        })
    }
    
    // Si on arrive avant la première image : 
    // - ajouter un clone de la dernière image au container (au début)
    // - ecouter la fin de la transition : 
    // - enlever la transition du container
    // - 'rembobiner' le container à l'image de fin
    // - supprimer le clone
    // - remettre la transition sur le container
    
    if(attrValue == -1){
        let clone = container.children('img:last-child').clone();
        clone.css({
            'position': 'absolute',
            'left': '0',
            'top': '0',
            'transform': 'translateX(-100%)'
        });
    
        container.prepend(clone); 
        container.on('transitionend', function(){
            $(this).off('transitionend');
            container.css('transition', 'none');
            container.css('left', ((totalSlides - 1) * -100) + '%');
            container.children('img:first-child').remove();
            slider.attr('data-currentSlide', totalSlides - 1);
            setTimeout(function(){
                container.css('transition', 'left 1s');
            }, 20);
        })
    }
    
    container.css('left', leftValue + '%');

    // Ecouter la fin de la transition pour rétablir la nav
    container.on('transitionend', function(){
        $(this).off('transitionend');
        enableNav(slider);
    })
}

function disableNav(slider){
    slider.find('nav button').attr('disabled','true');
}

function enableNav(slider){
    slider.find('nav button').removeAttr('disabled');
}
    
    
    
