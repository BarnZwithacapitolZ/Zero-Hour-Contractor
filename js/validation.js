
var fields = ['department',  'time', 'start', 'end'];

function submitForm(e, obj) {
    f = '.' + fields[0];
    field = obj.find(f);
    if (field.val() == null) {
        $(obj.find(f + 'Error')).css('display', 'block');
        return false;
    }

    times = [];
    for (var i = 2; i < fields.length; i++) {
        f = '.' + fields[i];
        field = obj.find(f);
        times.push(field.val().substr(0, 5));
    }

    f = '.' + fields[2];
    field = obj.find(f);
    time = field.attr('min').substr(0, 5)
    if (times[0] < time) {
        e.preventDefault();
        (obj.find(f + 'Error')).css('display', 'block'); 
        return false;
    }

    f = '.' + fields[3];
    field = obj.find(f);
    time = field.attr('max').substr(0, 5)
    if (times[1] > time) {
        e.preventDefault();
        (obj.find(f + 'Error')).css('display', 'block'); 
        return false;
    }

    f = '.' + fields[1]
    if (times[0] > times[1]) {
        e.preventDefault();
        (obj.find(f + 'Error')).css('display', 'block'); 
        (obj.find(f + 'Error')).text("* Start time cannot be greater than End time");
        return false;
    }

    if (times[0] == times[1]) {
        e.preventDefault();
        (obj.find(f + 'Error')).css('display', 'block'); 
        (obj.find(f + 'Error')).text("* Start and End times cannot be equal");
        return false;
    }

    return false;
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