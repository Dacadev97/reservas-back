<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ReservationController
 * @package App\Http\Controllers
 * @OA\Tag(
 *     name="Reservations",
 *     description="Operaciones relacionadas con las reservas"
 * )
 */
class ReservationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/events/{event}/reservations",
     *      operationId="storeReservation",
     *      tags={"Reservations"},
     *      summary="Crear una nueva reserva para un evento",
     *      description="Crea una nueva reserva para un evento específico. Requiere autenticación.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="event",
     *          in="path",
     *          description="ID del evento para el cual se crea la reserva",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_name", "user_email", "seats"},
     *              @OA\Property(property="user_name", type="string", maxLength=255, description="Nombre del usuario que realiza la reserva"),
     *              @OA\Property(property="user_email", type="string", format="email", maxLength=255, description="Correo electrónico del usuario"),
     *              @OA\Property(property="seats", type="integer", description="Número de asientos reservados")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Reserva creada exitosamente",
     *          @OA\JsonContent(ref="#/components/schemas/Reservation")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Evento no encontrado"
     *      )
     * )
     *
     * @param Request $request
     * @param Event $event
     * @return Reservation
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'user_name' => 'required',
            'user_email' => 'required|email',
            'seats' => 'required|integer|min:1',
        ]);

        return $event->reservations()->create($request->all());
    }

    /**
     * @OA\Delete(
     *      path="/reservations/{reservation}",
     *      operationId="deleteReservation",
     *      tags={"Reservations"},
     *      summary="Eliminar una reserva existente",
     *      description="Elimina una reserva existente basada en su ID. Requiere autenticación.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="reservation",
     *          in="path",
     *          description="ID de la reserva a eliminar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Reserva eliminada exitosamente"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Reserva no encontrada"
     *      )
     * )
     *
     * @param Reservation $reservation
     * @return Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->noContent();
    }
}
