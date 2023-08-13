<?php 
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductModel;
use CodeIgniter\I18n\Time;
use Faker\Factory;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $model = new ProductModel();
        $faker = Factory::create('id_ID'); // Mengatur bahasa menjadi Indonesia

        for ($i = 0; $i < 15; $i++) {
            $data = [
                'id_category' => $faker->numberBetween(1, 5),
                'slug' => $faker->slug,
                'title' => $faker->sentence(2), // Satu kalimat dengan dua kata
                'description' => $faker->sentence(12), // Satu kalimat dengan dua belas kata
                'price' => $faker->numberBetween(10000, 100000),
                'is_available' => 1, // Mengatur nilai is_available menjadi 1
                'created_at' => Time::createFromTimestamp($faker->unixTime()),
                'updated_at' => Time::now()
            ];

            $model->insert($data);
        }
    }
}




?>