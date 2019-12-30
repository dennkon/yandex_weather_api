<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(Request $request){

        $cache_storage_time_min = 10;
        // функция расшифровки погодного описания.
        $switch_condition = function ($condition){
            switch ($condition){
                case 'clear':
                    return 'Ясно';
                    break;
                case 'partly-cloudy':
                    return 'Малооблачно';
                    break;
                case 'cloudy':
                    return 'Облачно с прояснениями';
                    break;
                case 'overcast':
                    return 'Пасмурно';
                    break;
                case 'partly-cloudy-and-light-rain':
                    return 'Небольшой дождь';
                    break;
                case 'partly-cloudy-and-rain':
                    return 'Дождь';
                    break;
                case 'overcast-and-rain':
                    return 'Сильный дождь';
                    break;
                case 'overcast-thunderstorms-with-rain':
                    return 'Сильный дождь, гроза';
                    break;
                case 'cloudy-and-light-rain':
                    return 'Небольшой дождь';
                    break;
                case 'overcast-and-light-rain':
                    return 'Небольшой дождь';
                    break;
                case 'cloudy-and-rain':
                    return 'Дождь';
                    break;
                case 'overcast-and-wet-snow':
                    return 'Дождь со снегом';
                    break;
                case 'partly-cloudy-and-light-snow':
                    return 'Небольшой снег';
                    break;
                case 'partly-cloudy-and-snow':
                    return 'Снег';
                    break;
                case 'overcast-and-snow':
                    return 'Снегопад';
                    break;
                case 'cloudy-and-light-snow':
                    return 'Небольшой снег';
                    break;
                case 'overcast-and-light-snow':
                    return 'Небольшой снег';
                    break;
                case 'cloudy-and-snow':
                    return 'Снег';
                    break;
            }
        };
        // функция расшифровки времени суток.
        $switch_part_time = function ($part_time){
            switch ($part_time){
                case 'night':
                    return 'Еочь';
                    break;
                case 'morning':
                    return 'Утро';
                    break;
                case 'day':
                    return 'День';
                    break;
                case 'evening':
                    return 'Вечер';
                    break;
            }
        };
        // функция расшифровки времени года.
        $switch_season = function ($season){
            switch ($season){
                case 'summer':
                    return 'Лето';
                    break;
                case 'autumn':
                    return 'Осень';
                    break;
                case 'winter':
                    return 'Зима';
                    break;
                case 'spring':
                    return 'Весна';
                    break;
            }
        };
        // функция расшифровки svg в картинку.
        $svg_to_img = function ($icon){
            return 'https://yastatic.net/weather/i/icons/blueye/color/svg/'.$icon.'.svg';
        };

        // Проверка жизни кэша
        if(!Cache::store('redis')->has('weather_response')){

            $data = [
                'lat' => '52.033973',
                'lon' => '113.499432',
                'lang' => 'ru_RU'
            ];

            // curl сессия в yandex погоду
            $data_string = json_encode($data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.weather.yandex.ru/v1/informers?lat=52.033973&lon=113.499432&lang=ru_RU",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-Yandex-API-Key: ".getenv('APP_YANDEX')
                ),
            ));

            $response = json_decode(curl_exec($curl));
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                dd("cURL Error #:" . $err);
            } else {

                // расшифровки погодного описания.
                $response->forecast->parts[0]->condition = $switch_condition($response->forecast->parts[0]->condition);
                $response->forecast->parts[1]->condition = $switch_condition($response->forecast->parts[1]->condition);
                $response->fact->condition = $switch_condition($response->fact->condition);

                // расшифровки времени суток.
                $response->forecast->parts[0]->part_name = $switch_part_time($response->forecast->parts[0]->part_name);
                $response->forecast->parts[1]->part_name = $switch_part_time($response->forecast->parts[1]->part_name);

                // расшифровки svg картинки.
                $response->forecast->parts[0]->icon = $svg_to_img($response->forecast->parts[0]->icon);
                $response->forecast->parts[1]->icon = $svg_to_img($response->forecast->parts[1]->icon);
                $response->fact->icon = $svg_to_img($response->fact->icon);

                // расшифровки времени года.
                $response->fact->season = $switch_season($response->fact->season);

                // создаем кэш погоды
                Cache::store('redis')->put('weather_response', $response, $cache_storage_time_min*60);
            }

        }

        $response = Cache::store('redis')->get('weather_response');

        return view('index')->with('weather_response', $response);


    }

}
