var openElem = [];

$(document).ready(function(){   
    var ancher = $('.first');

    ancher.on('mouseover', function(e){
        $(this).parent().parent().css('background-color', '#cce6ff');    
        if (!$(this).parent().parent().hasClass('user')){
            $(this).parent().parent().css('box-shadow', 'inset 0 3px 5px rgba(0,0,0,.125)');
        }   
    });

    ancher.on('mouseout', function(e){
        $(this).parent().parent().css('background-color', 'transparent');
        if (!$(this).parent().parent().hasClass('user')){
            $(this).parent().parent().css('box-shadow', 'inherit');
        }
    });

    var button = $('.more-dropdown');
    button.on('click', function(e){
        var target = $(this).parent().find('.more-info-tile');   
        openHourDropdown($(this), target);
    });

    $('.more-info-button.return').on('click', function(e){
        closeHourDropdown($(this).parent(), 420);      
    });
});

function openHourDropdown(self, target){
    if (openElem.length >= 1){
        closeHourDropdown(openElem[0], 250);   
    }

    var elemWidth = self.parent().width();    
    var location = self.parent().position().left;

    target.css('width', elemWidth);

    if ((location + target.width()) > $(document).width() - 5){ // 5 as offset for scrollbar
        var difference = (location + target.width()) - ($(document).width() - 5);
        target.css('left', -difference);
    } else{
        target.css('left', '0');
    }

    target.css('display', 'block');
    self.parent().find('.notification-bubble').css('display', 'none');
    target.animate({
        height: target.get(0).scrollHeight
     }, 420);
     openElem.push(target); // know that dropdown is open
}

function closeHourDropdown(target, speed){
    target.animate({
        height: '0'
    }, speed, function(){
        $(this).css('display', 'none');
        $(this).parent().find('.notification-bubble').fadeIn();
    });
    openElem.pop(); // know that dropdown is closed
}