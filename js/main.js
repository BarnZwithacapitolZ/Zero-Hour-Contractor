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
});
