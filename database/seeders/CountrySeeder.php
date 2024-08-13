<?

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run() //naprdím 100 náhodných záznamů
    {
        Country::updateOrCreate(
            ['code' => 'ZA'], // Podmínka hledání podle kódu země
            ['name' => 'South Africa'] // Data pro aktualizaci nebo vytvoření záznamu
        );
        
        Country::factory()->count(100)->create();
    }
}
