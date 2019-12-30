<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Whether Chita</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="header"></div>
<div class="container">
    <section class="parallax-box home-paralax">
        <div class="parallax-content">
            <div class="span8 ">
                <section class="lazy-load-box effect-slidefromleft" data-delay="0" data-speed="600" style="transition: all 600ms ease 0s; opacity: 1;">
                    <p><span class="error"></span></p>
                    <div id="awesome-weather-chita" class="awesome-weather-wrap awecf awe_wide temp3 awe_with_stats awe-code-800 awe-desc-clear-sky" style=" color: #ffffff; ">
                        <div class="awesome-weather-header">Чита</div>
                        <div class="awesome-weather-current-temp">
                            <img class="awesome-weather-img" src="{{$weather_response->fact->icon}}" alt="">
                            <strong>{{$weather_response->fact->temp}}<sup>°</sup></strong>
                            <span class="awesome-weather-feels-like">По ощущениям {{$weather_response->fact->feels_like}}<sup>°</sup></span>
                        </div>


                        <div class="awesome-weather-todays-stats">
                            <div class="awe_container">
                                <div class="awe_desc">{{$weather_response->fact->condition}}</div>
                                <div class="awe_humidty">Влажность воздуха: {{$weather_response->fact->humidity}}%</div>
                                <div class="awe_wind">Скорость ветра: {{$weather_response->fact->wind_speed}}m/s {{mb_strtoupper($weather_response->fact->wind_dir)}}</div>
                                <div class="awe_pressure_mm">{{$weather_response->fact->pressure_mm}}<sup>°</sup> мм рт. ст.</div>
                            </div>
                            <div class="awe_container">
                                <div class="awe_season">{{$weather_response->fact->season}}</div>
                                <div class="awe_sunrise">Рассвет: {{$weather_response->forecast->sunrise}}</div>
                                <div class="awe_sunset">Закат: {{$weather_response->forecast->sunset}}</div>
                                <div class="awe_highlow" style="display: none;">Макс {{$weather_response->forecast->parts[0]->temp_max}}<sup>°</sup> • мин {{$weather_response->forecast->parts[0]->temp_min}}<sup>°</sup></div>
                            </div>
                        </div>
                        <div class="awesome-weather-forecast awecf">
                            @foreach($weather_response->forecast->parts as $key => $part)
                            <div id="{{$key}}" class="awesome-weather-forecast-day awe_days_1">
                                <img class="awesome-weather-forecast-day-img" src="{{$part->icon}}" alt="">
                                <div class="awesome-weather-forecast-day-temp">{{$part->temp_avg}}<sup>°</sup></div>
                                <div class="awesome-weather-forecast-day-abbr">{{$part->part_name}}</div>
                            </div>
                            @endforeach
                        </div>
                    </div><p></p>
                </section>
            </div>
        </div>
    </section>
</div>
<div data-speed="3" class="parallax-bg"></div>
<div class="footer">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script type="text/javascript">
            $( document ).ready(function() {
                $('.awesome-weather-forecast-day').on('click', function (event) {
                    var parts = @json($weather_response->forecast->parts);
                    var id = $(this).attr('id');
                    var temp_avg = parts[id].temp_avg;
                    var feels_like = parts[id].feels_like;
                    var temp_min = parts[id].temp_min;
                    var temp_max = parts[id].temp_max;
                    var icon = parts[id].icon;
                    var condition = parts[id].condition;
                    var wind_speed = parts[id].wind_speed;
                    var wind_dir = parts[id].wind_dir;
                    var humidity = parts[id].humidity;
                    var pressure_mm = parts[id].pressure_mm;

                    $('.awesome-weather-img').attr('src', icon);
                    $('.awesome-weather-current-temp strong').html(temp_avg+'<sup>°</sup>');
                    $('.awesome-weather-feels-like').html('По ощущениям '+feels_like+'<sup>°</sup>');
                    $('.awe_desc').text(condition);
                    $('.awe_humidty').text('Влажность воздуха: '+humidity+'%');
                    $('.awe_wind').text('Скорость ветра: '+wind_speed+'m/s '+wind_dir);
                    $('.awe_pressure_mm').html(pressure_mm+'<sup>°</sup> мм рт. ст.');
                    $('.awe_highlow').attr('style','').html('Макс '+temp_max+'<sup>°</sup> • мин '+temp_min+'<sup>°</sup>');


                })
            });
    </script>
</div>
</body>
</html>