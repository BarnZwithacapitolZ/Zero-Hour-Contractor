var openElem = [];
var docLoaded = false;

$(document).ready(function() {  
    docLoaded = true;

    // Make the animation for banner only appear after load (to prevent unloaded content)
    $('.hl-banner__container').addClass('hl-banner__container--animation');
    
    var ancher = $('.cell__content--first');
    ancher.on('mouseover', function(e) {
        var target = $(this).parent().parent();
        rowHover(target, '#cce6ff', 'inset 0 3px 5px rgba(0,0,0,.125)'); 
    });

    ancher.on('mouseout', function(e) {
        var target = $(this).parent().parent();
        rowHover(target, 'transparent', 'inherit');
    });

    $('.cell__content--dropdown').on('click', function(e) {
        var target = $(this).parent().find('.cell__dropdown');   
        openHourDropdown(target);
    });

    $('.dropdown__button.dropdown__button--return').on('click', function(e) {
        closeHourDropdown($(this).parent(), 420);      
    });


    // For Scroll up
    $('.scroll-to-top').on('click', function(e) {
		$('html, body').animate({scrollTop: 0}, 500);
		e.preventDefault();
    });
     

    var open = false;
    var height = 0;
    $('.nav__toggle').on('click', function(e) {
        open = !open;
        $(this).toggleClass('nav__toggle--open');

        if (open) {
            height = openNavDropdown($('.landing-header__nav-dropdown'));
            e.preventDefault(); // Stop selecting elements          
        } else {      
            closeNavDropdown($('.landing-header__nav-dropdown'), height);
        }  
    });

    $('.company-register__input').on(
        "webkitAnimationEnd oanimationend msAnimationEnd animationend",
        function() {
            $(this).removeClass("company-register__input--error");
        }
    );

    $('.company-register__input').on('input', function() {
        $(this).parent().removeClass('company-register__input--error-text');
    });

    $('.company-register__submit').on('click', function() {
        var inputVal = $('.company-register__input').val();
        if (inputVal.trim() === "") {
            $('.company-register__input').addClass('company-register__input--error');
            $('.company-register__input').parent().addClass('company-register__input--error-text');
            return false;
        } 
        return true;
    });



    // Seperate events so that different modals can be differentiated from one another
    $('.modal__open--del').on('click', function() {
        $(this).parent().find('.modal__full--del').fadeIn();     
    });

    $('.modal__open--desc').on('click', function() {
        $(this).parent().find('.modal__full--desc').fadeIn();     
    });

    $('.modal__open--edit').on('click', function() {
        $(this).parent().find('.modal__full--edit').fadeIn();     
    });

    $('.modal__open').on('click', function() {
        $(this).parent().find('.modal__full').fadeIn();     
    });

    $('.modal__close').on('click', function() {
        $(this).parent().parent().parent().fadeOut();
        clearError($(this).parent().find('.modal__form')); // Clears the validation text within the modals
    });


    $('.notification-bubble').on('mouseover', function(e) {
        var target = $(this).find('.tool-tip');
        openToolTip(target, $(this));
    });

    $('.notification-bubble').on('mouseleave', function() {
        var target = $(this).find('.tool-tip');
        closeToolTip(target);
    });
});


function openNavDropdown(target) {
    target.css('display', 'block'); // Show the element
    var dropdownHeight = target.get(0).scrollHeight; // get its height

    target.find('ul li').css('top', -(dropdownHeight / 4));

    target.animate({
        height: dropdownHeight
    }, 350, function() {
        target.css('height', 'auto'); // Reset height back to auto for responsive layout
    });

    target.find('ul li').animate({
        top: '0'
    }, 400);

    return dropdownHeight;
}


function closeNavDropdown(target, dropdownHeight) {
    target.animate({
        height: '0'
    }, 350, function() {
        target.css('display', 'none');
    });

    target.find('ul li').animate({
        top: -(dropdownHeight / 4)
    }, 300);
}


function rowHover(target, bg, shadow) {
    target.css('background-color', bg);
    if (!target.hasClass('user')) {
        target.css('box-shadow', shadow);
    }
}

function closeToolTip(target) {
    target.hide('scale', 100);
}

function openToolTip(target, self) {
    if (!target.is(":hover")) {
        //50 for the +35 (offset when it is set) and +7 padding (+ the rest for good measure)
        if ((target.width() + self.offset().left + 50) > $(document).width()) {
            var difference = (target.width() + 15); // fixxx
            target.css('left', -difference);

            // once moved check the other side 
            if ((self.offset().left - target.width() + 20) < 0) {
                target.css('left', -self.offset().left + 5);
            }
        } else {
            target.css('left', '35px');
        }

        target.show('scale', 100);
    } else {
        closeToolTip(target);
    }
}

function openHourDropdown(target) {
    if (openElem.length >= 1) {
        closeHourDropdown(openElem[0], 250);   
    }

    var tileWidth = target.parent().width(); // Set it to the width of the tile
    var location = target.parent().position().left;

    target.css('width', tileWidth); // Set width of elem to width of tile, if greater then min width

    if ((location + target.width()) > $(document).width() - 1) { // 5 as offset for scrollbar
        var difference = (location + target.width()) - ($(document).width() - 1);
        target.css('left', -difference);
    } else {
        target.css('left', '0');
    }

    target.css('display', 'block');
    target.parent().find('.notification-bubble').css('display', 'none');
    target.animate({
        height: target.get(0).scrollHeight
     }, 420, function() {
        target.css('height', 'auto');
     });
     openElem.push(target); // know that dropdown is open
}


function closeHourDropdown(target, speed) {
    target.animate({
        height: '0'
    }, speed, function() {
        $(this).css('display', 'none');
        $(this).parent().find('.notification-bubble').fadeIn();
    });
    openElem.pop(); // know that dropdown is closed
}


function showScrollToggle() {
    var self = $(this),
		height = 250,
        top = self.scrollTop(),
        button = $('.scroll-to-top');
    displayTop = top;	
			
	if (displayTop > height){		
		if (!button.is(':visible')){
			button.css('bottom', '0%');
			button.show();
			button.animate({bottom: '5%'}, 300);
		}			
	} else{		
		button.fadeOut();
	}	
}


function checkAnimation(elem, elemToAnim) {
    var elem = $(elem);

    elem.each(function() {
        var elemBottom = $(this).offset().top + ($(this).outerHeight() / 2);
        var bottomWindow = $(window).scrollTop() + $(window).height();

        if (bottomWindow > elemBottom) {
            $(this).addClass(elemToAnim);
        }
    });
}


// Capture scroll events
$(window).scroll(function(){
    if (docLoaded) { // Only load the elements when the document is loaded
        checkAnimation('.hl-about__col', 'hl-about__col--animation');
        checkAnimation('.hl-target__col', 'hl-target__col--animation');
        checkAnimation('.hl-more__container', 'hl-more__container--animation'); 
    }
    
    showScrollToggle();
});


// Modal on-click events 
$('.modal-form__test').on('click', function() {
    console.log("this is a test of the onclick event");
    return false;   
});


// Datepicker setup
highlightDates = [$('#datepicker').attr('class')];

$("#datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
    yearRange: '-100:+100',
    showOn: "button", 
    buttonImageOnly: true,
    buttonImage: '../media/img/icons/day.png',
    minDate: new Date(1970, 1, 1),
    maxDate: '+30Y',
    inline: true,
    dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    monthNamesShort: ["January", "Febuary", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    showOtherMonths: true,
    onSelect: function() {
        $('#date-selector').submit();
    },
    showAnim: "drop",
    showOptions: { direction: "up"},
    beforeShowDay: function(date) {
        var month = ('0' + (date.getMonth() +1)).slice(-2);
        var year = date.getFullYear();
        var day = ('0' + (date.getDate())).slice(-2);
        var newdate = year + "-" + month + '-' + day;

        if(jQuery.inArray(newdate, highlightDates) != -1){
            return [true, "highlight-date"];
        }
     
        return [true];
    }
});