function countdownTimer(duration, display)
{
    var timer = duration, minutes, seconds;
    setInterval(function () {

    		if(timer > 3590) {
        		hours = parseInt((timer / 60)/60, 10);
          minutes = parseInt((timer / 60)%60, 10);
        	seconds = parseInt(timer % 60, 10);

        }
        else {
        	hours = "00";
        	minutes = parseInt(timer / 60, 10);
        	seconds = parseInt(timer % 60, 10);
        }


        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(hours+ ":" +minutes + ":" + seconds);

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

function lapseTimer(startTime, display) {
    var timer = startTime, minutes, seconds;

    return setInterval(function () {

        if(timer > 3590) {
            hours = parseInt((timer / 60)/60, 10);
            minutes = parseInt((timer / 60)%60, 10);
            seconds = parseInt(timer % 60, 10);

        }
        else {
            hours = "00";
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
        }


        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(hours+ ":" +minutes + ":" + seconds);

        if (++timer < 0) {
            timer = startTime;

        }
    }, 1000);
}

function totalTimer(startTime, display) {
    var timer = startTime, minutes, seconds;
    setInterval(function () {
      if(!display.hasClass('pause')) {
        if(timer > 3590) {
          hours = parseInt((timer / 60)/60, 10);
          minutes = parseInt((timer / 60)%60, 10);
          seconds = parseInt(timer % 60, 10);

        }
        else {
            hours = "00";
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
        }


        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(hours+ ":" +minutes + ":" + seconds);

        if (++timer < 0) {
            timer = startTime;

        }
      }

    }, 1000);
}

jQuery(function ($)
{
    var time = 60 * 60 * jQuery('#initialTime').val();
    var display = $('#lapseTime');
    var displayTotalUsedTime = $('#totalUsedTime');

    $("#start-interview-btn").on('click', function(){
        lapseTimer(time, display);
        lapseTimer(time, displayTotalUsedTime);
    });
});
