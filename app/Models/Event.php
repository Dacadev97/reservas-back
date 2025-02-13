<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     title="Event",
 *     description="Modelo de Evento",
 *     @OA\Xml(
 *         name="Event"
 *     ),
 *     @OA\Property(
 *         property="id",
 *         title="ID",
 *         description="ID del evento",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         title="Name",
 *         description="Nombre del evento",
 *         type="string",
 *         example="Concierto de Rock"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         title="Date",
 *         description="Fecha del evento",
 *         type="string",
 *         format="date",
 *         example="2025-03-15"
 *     ),
 *     @OA\Property(
 *         property="location",
 *         title="Location",
 *         description="Ubicación del evento",
 *         type="string",
 *         example="Estadio Central"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         title="Description",
 *         description="Descripción del evento",
 *         type="string",
 *         example="Un gran concierto de rock en vivo"
 *     )
 * )
 */
class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'date', 'location'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
