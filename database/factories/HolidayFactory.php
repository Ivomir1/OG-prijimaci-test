<?
namespace Database\Factories;
use App\Models\Holiday;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HolidayFactory extends Factory
{
    /**
     * 
     *
     * @var string
     */
    protected $model = Holiday::class;

    /**
     * 
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => Country::inRandomOrder()->first()->code, // Nnhodné CODE země
            'date' => $this->faker->dateTimeBetween('2024-01-01', '2024-12-31')->format('Y-m-d'), // náhodné datum 
            'name' => $this->faker->randomElement([
                'New Year\'s Day', 'Labor Day', 'Independence Day', 'Christmas Day', 'Easter Monday'
            ]), // náhodně název 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

