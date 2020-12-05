<?php

namespace App\Console\Commands;

use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\RestaurantTag;
use Illuminate\Console\Command;

class MakeTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guraeat:fakedata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'uwu';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line("建立中...");
        $name = [
            '北寧早餐店',
            '百餃咖喱',
            '日町壽司屋',
            '鮮味鍋',
            '樂食意大利餐廳'
        ];
        $phone = [
            '02-24625968',
            '02-21427546',
            '02-24252232',
            '02-24691580',
            '02-24221314'
        ];
        $address = [
            '基隆市中正區北寧路1號',
            '基隆市中正區祥豐街119號',
            '基隆市仁愛區孝三路16號',
            '基隆市中正區義二路176號1樓',
            '200基隆市仁愛區仁二路98號'
        ];
        $img = [
            'https://lh5.googleusercontent.com/p/AF1QipOtZ4M8fSxv-VekMa5oTVtHAnXk4Pajb1PN9lb6=w408-h306-k-no',
            'https://lh5.googleusercontent.com/p/AF1QipNxBocQDvd8miE5x7uLgC33pzEh_-Vg9KUCFN6l=w426-h240-k-no',
            'https://lh5.googleusercontent.com/p/AF1QipNWemip9PsoMSyP3iWA8kVhz6HZTz2Spu6Nc_o7=w408-h306-k-no',
            'https://lh5.googleusercontent.com/p/AF1QipOaeeViMSONqwK7394EUKwfg6CyBV9fJbKVplLH=w408-h306-k-no',
            'https://lh5.googleusercontent.com/p/AF1QipPJ1WUnatQmE1JGkSQ6k7DfKQqQZY6P1HqGkg3K=w408-h306-k-no'
        ];
        $email = [
            'test1@test.com',
            'test2@test.com',
            'test3@test.com',
            'test4@test.com',
            'test5@test.com',
        ];

        for($i = 0;$i<5;$i++){
            $res = new Restaurant();
            $res->name = $name[$i];
            $res->phone = $phone[$i];
            $res->address = $address[$i];
            $res->img = $img[$i];
            $res->email = $email[$i];
            $res->password = bcrypt('123456');
            $res->save();
            $dishlist1 = [];
            $pricelist1 = [];
            $imglist1 = [];
            $makingTimeList = [];
            if($i == 0){

                $tag1 = new RestaurantTag();
                $tag1->name = "早餐";
                $tag1->save();
                $res->tags()->save($tag1);

                $dishlist1 = ['蛋餅','鍋燒意麵','包子'];
                $pricelist1 = [25,50,15];
                $imglist1 = [
                    'https://lh5.googleusercontent.com/p/AF1QipNmz9iA2BFKJTG4B-W5NFPdDC1oP8-3PgJWsh3M=s563-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipPcZUqTlHSJgDHdjBfrbouXXc-_g6SNFwtEoqNv=w750-h750-p-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipMkeeVxgy3EjEJv_XYw5ptC7A8WQbJTnYoVTwQD=w203-h270-k-no'
                ];
                $makingTimeList = [
                    5,10,5
                ];
            }
            if($i == 1){

                $tag1 = new RestaurantTag();
                $tag1->name = "咖哩";
                $tag1->save();
                $res->tags()->save($tag1);

                $dishlist1 = ['牛肉飯','獅子頭泛','咖哩'];
                $pricelist1 = [90,100,70];
                $imglist1 = [
                    'https://lh5.googleusercontent.com/p/AF1QipOBNiazjNjYTuJsttme75ykYqVgDpa3zeuqjVMd=s508-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipOJSDnjpjtiPYjdkcfsHhf8LoOs6LS0MLaZu94q=s644-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipO8JurCGCYPKPclidAHRf4C7VFEJ_5xvSt1DB50=s635-k-no'
                ];
                $makingTimeList = [
                    30,30,30
                ];
            }
            if($i == 2){
                $tag1 = new RestaurantTag();
                $tag1->name = "日式";
                $tag1->save();
                $res->tags()->save($tag1);

                $dishlist1 = ['海鮮丼','七貫壽司','鮭魚便當'];
                $pricelist1 = [180,200,120];
                $imglist1 = [
                    'https://lh5.googleusercontent.com/p/AF1QipN25iaY7qvzm1qPKeWtaIeTaSfmEhDZQ9Y9oRk=w203-h203-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipOhB4gRn85tvrIq00I24Di_fj0bXIYyqfZEVOmr=w203-h152-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipPk-PQhlvhp8ccTQdnpJ2L5caU-fFu0gps8fUsm=s483-k-no'
                ];
                $makingTimeList = [
                    25,25,25
                ];
            }
            if($i == 3){
                $tag1 = new RestaurantTag();
                $tag1->name = "火鍋";
                $tag1->save();
                $res->tags()->save($tag1);
                $dishlist1 = ['原味鍋','泡菜鍋','海鮮鍋'];
                $pricelist1 = [130,130,130];
                $imglist1 = [
                    'https://lh5.googleusercontent.com/p/AF1QipNzPOxs2NACd31XfZmjMGD_3Qv_cx_2-RRgcVn5=s563-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipOaeeViMSONqwK7394EUKwfg6CyBV9fJbKVplLH=s563-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipNU3P0Ao0Gtb_YEOLKgUvE0GmS_Y7pXF4FRkIV0=w203-h152-k-no'
                ];
                $makingTimeList = [
                    40,40,40
                ];
            }
            if($i == 4){
                $tag1 = new RestaurantTag();
                $tag1->name = "義大利餐廳";
                $tag1->save();
                $res->tags()->save($tag1);
                $dishlist1 = ['焗烤燉飯','燻腸義大利麵','青醬義大利麵'];
                $pricelist1 = [150,180,180];
                $imglist1 = [
                    'https://lh5.googleusercontent.com/p/AF1QipMKcNtft-kO4egkQwdVMe4J_m2Qt5Y-jox4JK1a=s644-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipPzel8yMq91WJiIJdeP9uaI1hhrvbcMUm19NX5G=s635-k-no',
                    'https://lh5.googleusercontent.com/p/AF1QipPOxrlSNQAVWAGQZcPSq46Xlq6otqTzE0LNG8QP=s483-k-no'
                ];
                $makingTimeList = [
                    40,40,40
                ];
            }
            if(count($dishlist1)!=0){
                for($j = 0;$j<count($dishlist1);$j++){
                    $dish = new Dish();
                    $dish->name =  $dishlist1[$j];
                    $dish->price =  $pricelist1[$j];
                    $dish->img =  $imglist1[$j];
                    $dish->making_time =  $makingTimeList[$j];
                    $dish->restaurant_id = $res->id;
                    $dish->save();
                }
            }
        }

        $this->line("建立完成!");
        return 0;
    }
}
