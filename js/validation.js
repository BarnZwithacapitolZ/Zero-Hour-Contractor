$('.modal-form__input').on(
    "webkitAnimationEnd oanimationend msAnimationEnd animationend",
    function() {
        $(this).removeClass("modal-form__input--error");
    }
);

var fields = ['department',  'time', 'start', 'end'];

function submitForm(e, obj) {
    f1 = '.' + fields[0];
    f2 = '.' + fields[1];
    f3 = '.' + fields[2];
    f4 = '.' + fields[3];

    if (obj.find(f1).val() == null) {
        obj.find(f1 + 'Error').css('display', 'block');
        obj.find(f1).addClass('modal-form__input--error');
        return false;
    }

    times = [];
    for (var i = 2; i < fields.length; i++) {
        f = '.' + fields[i];
        times.push(obj.find(f).val());
    }

    if (times[0] < obj.find(f3).attr('min')) {
        e.preventDefault();
        obj.find(f3 + 'Error').css('display', 'block'); 
        obj.find(f3).addClass('modal-form__input--error');
        return false;
    }

    if (times[1] > obj.find(f4).attr('max')) {
        e.preventDefault();
        obj.find(f4 + 'Error').css('display', 'block'); 
        obj.find(f4).addClass('modal-form__input--error');
        return false;
    }

    if (times[0] > times[1]) {
        e.preventDefault();
        obj.find(f2 + 'Error').css('display', 'block'); 
        obj.find(f2 + 'Error').text("* Start time cannot be greater than End time");
        obj.find(f4).addClass('modal-form__input--error');
        return false;
    }

    if (times[0] == times[1]) {
        e.preventDefault();
        obj.find(f2 + 'Error').css('display', 'block'); 
        obj.find(f2 + 'Error').text("* Start and End times cannot be equal");
        obj.find(f3).addClass('modal-form__input--error');
        obj.find(f4).addClass('modal-form__input--error');
        return false;
    }

    return true;
}

function clearError(obj) {
    for (var i = 0; i < fields.length; i++) {
        f = '.' + fields[i];
        field = obj.find(f);
        if (obj.find(f + 'Error').css('display') == 'block')
            $(obj.find(f + 'Error')).css('display', 'none');
    }
}



$('.submit').on('click', function(e) {
    clearError($(this).parent());
    return submitForm(e, $(this).parent());
});