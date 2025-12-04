<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportCase>
 */
class SupportCaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $domains = [
            'websolutions.work', 'techsolutions.com', 'digitalcreators.io', 'webmasters.net',
            'sitebuilders.org', 'webdevhub.tech', 'codecrafters.dev', 'pixelperfect.design',
            'webninjas.io', 'sitedoctors.tech'
        ];

        $name = $this->faker->unique()->randomElement($domains);
        $email = 'support@' . $name;

        return [
            'name' => $name,
            'email' => $email,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['open', 'resolved']),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'support', true),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
