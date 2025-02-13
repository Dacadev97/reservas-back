<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="Reservation",
 *     description="Reservation model",
 *     @OA\Xml(
 *         name="Reservation"
 *     ),
 *     @OA\Property(
 *         property="id",
 *         title="ID",
 *         description="Reservation ID",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="event_id",
 *         title="Event ID",
 *         description="ID of the event the reservation belongs to",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="user_name",
 *         title="User Name",
 *         description="Name of the user making the reservation",
 *         type="string",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="user_email",
 *         title="User Email",
 *         description="Email of the user making the reservation",
 *         type="string",
 *         format="email",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="seats",
 *         title="Seats",
 *         description="Number of seats reserved",
 *         type="integer",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         title="Created At",
 *         description="Timestamp of when the reservation was created",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         title="Updated At",
 *         description="Timestamp of when the reservation was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01 12:00:00"
 *     )
 * )
 */
class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['event_id', 'user_name', 'user_email', 'seats'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
