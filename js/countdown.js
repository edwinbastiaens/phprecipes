String.prototype.toDDHHMMSS = function () {
    var sec_num = parseInt(this, 10); // dont forget the second param
    var days = Math.floor(sec_num / 86400);
    var daysecs = days * 86400;
    var hours   = Math.floor((sec_num -daysecs)/ 3600);
    var hoursecs = hours * 3600;
    var minutes = Math.floor((sec_num - hoursecs - daysecs) / 60);
    var minsecs = minutes * 60;
    var seconds = sec_num - daysecs - hoursecs - minsecs;

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    
    return {"d" : days,
            "h" : hours,
            "m" : minutes,
            "s" : seconds };
} 

function getRemainingSeconds(unixtimestamp){
    var now = new Date().getTime() / 1000 | 0;
    var diff = unixtimestamp - now;
    return diff;
}

var countDownStyle1 = "<div class='cntd day' ></div><div class='cntd hour' ></div><div class='cntd min' ></div><div class='cntd sec' ></div>";
var countDownStyle2 = "<div class='cntd day' ></div><div class='cntd hour' ></div><div class='cntd min' ></div><div class='cntd sec' ></div><div style='clear:both'></div><span class='countdowntxt'>&nbsp;&nbsp;Dagen &nbsp;Uren &nbsp;&nbsp;Min &nbsp;&nbsp;&nbsp;&nbsp;Sec</span>";
function countDown(){
    $('.countdown').each(function(){
        if ($(this).html() == "");
            $(this).html(countDownStyle1);
        var uxt = $(this).attr('uxtime');
        var remaining = getRemainingSeconds(uxt);
        if (remaining < 0 ){ //timer has passed
            $(this).html('timer has passed').removeClass('countdown');
            return;
        }
        var dhms = String(getRemainingSeconds(uxt)).toDDHHMMSS();
        $('.day', this).html(dhms.d);
        $('.hour', this).html(dhms.h);
        $('.min', this).html(dhms.m);
        $('.sec', this).html(dhms.s);
    });
}

$( function(){
    countDown();
    setInterval(countDown, 1000);
});
