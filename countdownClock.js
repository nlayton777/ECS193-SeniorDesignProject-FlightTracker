function CountdownClock(time)
{
    var numhours = (time / (60 * 60)) / 24;
    var remaining_hours = 3600 * 24 * numhours;
    var clock = $('.clock').FlipClock(remaining_hours, {
	clockFace: 'DailyCounter',
	countdown: true
    });
}
