<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class CarTableSeeder extends Seeder
{
    public function run()
    {
        $cars = [
            ['car_type_id' => 1, 'name' => 'BMW X5', 'slug' => 'bmw-x5', 'description' => 'Роскошный внедорожник с мощным двигателем', 'price' => 65000.00, 'engine' => '3.0L', 'power' => 340, 'color' => 'Черный', 'image' => 'bmw_x5.jpg', 'is_active' => true],
            ['car_type_id' => 2, 'name' => 'Audi A4', 'slug' => 'audi-a4', 'description' => 'Элегантный седан бизнес-класса', 'price' => 45000.00, 'engine' => '2.0L', 'power' => 190, 'color' => 'Серый', 'image' => 'audi_a4.jpg', 'is_active' => true],
            ['car_type_id' => 3, 'name' => 'Mercedes C-Class', 'slug' => 'mercedes-c-class', 'description' => 'Комфортабельный премиум седан', 'price' => 55000.00, 'engine' => '2.5L', 'power' => 250, 'color' => 'Белый', 'image' => 'mercedes_c.jpg', 'is_active' => true],
            ['car_type_id' => 1, 'name' => 'Toyota RAV4', 'slug' => 'toyota-rav4', 'description' => 'Надежный кроссовер для города', 'price' => 35000.00, 'engine' => '2.5L', 'power' => 203, 'color' => 'Красный', 'image' => 'toyota_rav4.jpg', 'is_active' => true],
            ['car_type_id' => 2, 'name' => 'Honda Civic', 'slug' => 'honda-civic', 'description' => 'Спортивный компактный автомобиль', 'price' => 28000.00, 'engine' => '1.5L', 'power' => 180, 'color' => 'Синий', 'image' => 'honda_civic.jpg', 'is_active' => true],
            ['car_type_id' => 3, 'name' => 'Lexus RX', 'slug' => 'lexus-rx', 'description' => 'Роскошный кроссовер с плавным ходом', 'price' => 68000.00, 'engine' => '3.5L', 'power' => 295, 'color' => 'Серебристый', 'image' => 'lexus_rx.jpg', 'is_active' => true],
            ['car_type_id' => 1, 'name' => 'Ford Mustang', 'slug' => 'ford-mustang', 'description' => 'Легендарный американский мускулкар', 'price' => 42000.00, 'engine' => '5.0L', 'power' => 450, 'color' => 'Желтый', 'image' => 'ford_mustang.jpg', 'is_active' => false],
            ['car_type_id' => 2, 'name' => 'Volkswagen Golf', 'slug' => 'vw-golf', 'description' => 'Классический хэтчбек для города', 'price' => 25000.00, 'engine' => '1.4L', 'power' => 150, 'color' => 'Зеленый', 'image' => 'vw_golf.jpg', 'is_active' => true],
            ['car_type_id' => 3, 'name' => 'Porsche 911', 'slug' => 'porsche-911', 'description' => 'Икона спортивных автомобилей', 'price' => 120000.00, 'engine' => '3.0L', 'power' => 385, 'color' => 'Черный', 'image' => 'porsche_911.jpg', 'is_active' => true],
            ['car_type_id' => 1, 'name' => 'Tesla Model 3', 'slug' => 'tesla-model3', 'description' => 'Электрический седан с автопилотом', 'price' => 50000.00, 'engine' => 'Electric', 'power' => 283, 'color' => 'Белый', 'image' => 'tesla_model3.jpg', 'is_active' => true]
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
