<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Events",
 *     description="Operaciones relacionadas con eventos"
 * )
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestión de Eventos",
 *      description="Documentación de la API para la gestión de eventos y reservas.",
 *      @OA\Contact(
 *          email="tu_correo@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * Class EventController
 * @package App\Http\Controllers
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *      path="/events",
     *      operationId="getEventsList",
     *      tags={"Events"},
     *      summary="Obtener la lista de eventos",
     *      description="Retorna la lista de eventos disponibles, con soporte para paginación, búsqueda por nombre y filtrado por fecha y ubicación.",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Número de página para la paginación",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=1
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="date",
     *          in="query",
     *          description="Filtrar eventos por fecha (YYYY-MM-DD)",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="location",
     *          in="query",
     *          description="Filtrar eventos por ubicación (parcial o completa)",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Buscar eventos por nombre (parcial o completo)",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Event")
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Recurso no encontrado"
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return Event::when($request->date, fn($query) => $query->where('date', $request->date))
            ->when($request->location, fn($query) => $query->where('location', 'like', "%{$request->location}%"))
            ->when($request->search, fn($query) => $query->where('name', 'like', "%{$request->search}%"))
            ->paginate(10);
    }

    /**
     * @OA\Post(
     *      path="/events",
     *      operationId="storeEvent",
     *      tags={"Events"},
     *      summary="Crear un nuevo evento",
     *      description="Crea un nuevo evento en el sistema. Requiere autenticación.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "date", "location"},
     *              @OA\Property(property="name", type="string", maxLength=255, description="Nombre del evento"),
     *              @OA\Property(property="date", type="string", format="date", description="Fecha del evento (YYYY-MM-DD)"),
     *              @OA\Property(property="location", type="string", description="Ubicación del evento"),
     *              @OA\Property(property="description", type="string", nullable=true, description="Descripción del evento")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Evento creado exitosamente",
     *          @OA\JsonContent(ref="#/components/schemas/Event")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Solicitud incorrecta"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado"
     *      )
     * )
     *
     * @param Request $request
     * @return Event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'date' => 'required|date',
            'location' => 'required',
        ]);

        return Event::create($request->all());
    }

    /**
     * @OA\Get(
     *      path="/events/{event}",
     *      operationId="getEventById",
     *      tags={"Events"},
     *      summary="Obtener información de un evento específico",
     *      description="Retorna la información de un evento basado en su ID.",
     *      @OA\Parameter(
     *          name="event",
     *          in="path",
     *          description="ID del evento a obtener",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Event")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Evento no encontrado"
     *      )
     * )
     *
     * @param Event $event
     * @return Event
     */
    public function show(Event $event)
    {
        return $event->load('reservations');
    }

    /**
     * @OA\Put(
     *      path="/events/{event}",
     *      operationId="updateEvent",
     *      tags={"Events"},
     *      summary="Actualizar un evento existente",
     *      description="Actualiza la información de un evento existente basado en su ID. Requiere autenticación.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="event",
     *          in="path",
     *          description="ID del evento a actualizar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Event")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Evento actualizado exitosamente",
     *          @OA\JsonContent(ref="#/components/schemas/Event")
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
     * @return Event
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->all());
        return $event;
    }

    /**
     * @OA\Delete(
     *      path="/events/{event}",
     *      operationId="deleteEvent",
     *      tags={"Events"},
     *      summary="Eliminar un evento existente",
     *      description="Elimina un evento existente basado en su ID. Requiere autenticación.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="event",
     *          in="path",
     *          description="ID del evento a eliminar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Evento eliminado exitosamente"
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
     * @param Event $event
     * @return Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->noContent();
    }
}
