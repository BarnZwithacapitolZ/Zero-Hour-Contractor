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


    var button = $('.more-lrg');
    button.on('click', function(e){
        var target = $(this).parent().find('.more-info-tile.lrg');   
        showHours($(this), target);
    });

    $('.more-sml').on('click', function(e){
        var target = $(this).parent().find('.more-info-tile.sml');   
        showHours($(this), target);
    });

    $('.more-info-button.return').on('click', function(e){
        $(this).parent().animate({
            height: '0'
        }, 420, function(){
            $(this).css('display', 'none');
            $(this).parent().find('.notification-bubble').fadeIn();
        });
        
    });
});

function showHours(self, target){
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
}