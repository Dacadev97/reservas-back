<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Operaciones relacionadas con la autenticación de usuarios"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="Registrar un nuevo usuario",
     *      description="Crea un nuevo usuario en el sistema.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", maxLength=255, description="Nombre del usuario"),
     *              @OA\Property(property="email", type="string", format="email", maxLength=255, description="Correo electrónico del usuario"),
     *              @OA\Property(property="password", type="string", minLength=6, description="Contraseña del usuario")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Usuario registrado exitosamente",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Usuario registrado correctamente")
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}})
     *          )
     *       )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario registrado correctamente']);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      summary="Iniciar sesión de usuario",
     *      description="Autentica un usuario existente y retorna un token de acceso.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", maxLength=255, description="Correo electrónico del usuario"),
     *              @OA\Property(property="password", type="string", description="Contraseña del usuario")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Inicio de sesión exitoso",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="Token de acceso")
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error de validación",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"email": {"Las credenciales son incorrectas"}})
     *          )
     *       )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutUser",
     *      tags={"Authentication"},
     *      summary="Cerrar sesión de usuario",
     *      description="Invalida el token de acceso del usuario autenticado.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Cierre de sesión exitoso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Cierre de sesión exitoso")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="No autenticado"
     *       )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Cierre de sesión exitoso']);
    }
}
