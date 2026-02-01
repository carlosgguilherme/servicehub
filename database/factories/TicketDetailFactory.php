<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TicketDetailFactory extends Factory
{
    protected $model = TicketDetail::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'notes' => $this->faker->sentence(),
            'technical_data' => null,
        ];
    }
}
