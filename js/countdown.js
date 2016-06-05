var CountDown = (function () {
    var refresh_interval = 1000; //miliseconds
    var timer_passed_notification = 'timer has passed';
    String.prototype.seconds2DHMS = function () {
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

    var my = {};

    my.getRemainingSeconds = function(unixtimestamp){
        var now = new Date().getTime() / 1000 | 0;
        var diff = unixtimestamp - now;
        return diff;
    }
    
    my.countDownStyle1 = "<div class='cntd day' ></div><div class='cntd hour' ></div><div class='cntd min' ></div><div class='cntd sec' ></div>";
    my.countDownStyle2 = "<div class='cntd day' ></div><div class='cntd hour' ></div><div class='cntd min' ></div><div class='cntd sec' ></div><div style='clear:both'></div><span class='countdowntxt'>&nbsp;&nbsp;Dagen &nbsp;Uren &nbsp;&nbsp;Min &nbsp;&nbsp;&nbsp;&nbsp;Sec</span>";
    my.countDown = function(){
        $('.countdown').each(function(){
            if ($(this).html() == "");
                $(this).html(my.countDownStyle1);
            var uxt = $(this).attr('uxtime');
            var remaining = my.getRemainingSeconds(uxt);
            if (remaining < 0 ){ //timer has passed
                $(this).html(timer_passed_notification).removeClass('countdown');
                return;
            }
            var dhms = String(my.getRemainingSeconds(uxt)).seconds2DHMS();
            $('.day', this).html(dhms.d);
            $('.hour', this).html(dhms.h);
            $('.min', this).html(dhms.m);
            $('.sec', this).html(dhms.s);
        });
    }

    $( function(){
        my.countDown();
        setInterval(my.countDown, refresh_interval);
    });
    return my;
}());

