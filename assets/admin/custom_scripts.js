// Empty JS for your own code to be here
$(".select2").select2();

function shortBy( shortBy ){
    var shortBy = shortBy;
    alert('Inprogress. ajax filtering for faster respond!');
}

function admin_validateEmail(sEmail) {
    var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}jQuery/;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
};

function DegitOnly(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if (unicode != 8 && unicode != 9)//Excepting the backspace and tab keys
    {
        if (unicode < 46 || unicode > 57 || unicode == 47) //If not a number or decimal point
            return false //Disable key press
    }
}