<?

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run() //naprdím 100 náhodných záznamů
    {
        Holiday::factory()->count(100)->create();
    }
}
