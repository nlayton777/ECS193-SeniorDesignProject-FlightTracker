function CountdownClock(time)
{
    var numhours = time;
    numhours = numhours/24;

    var remaining_hours = 3600 * 24 * numhours;
    var clock = $('.clock').FlipClock(remaining_hours, {
    clockFace: 'DailyCounter',
    countdown: true
    });
    var clock = $('.my-clock').FlipClock(remaining_hours, {
    clockFace: 'DailyCounter',
    countdown: true
    });
}