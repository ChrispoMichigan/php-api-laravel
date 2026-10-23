<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        summary: 'Listar usuarios',
        description: 'Devuelve un listado paginado de usuarios. Permite buscar por nombre, username o email.',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Texto a buscar en name, username o email', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Cantidad de resultados por página (1-100)', schema: new OA\Schema(type: 'integer', default: 15)),
            new OA\Parameter(name: 'page', in: 'query', description: 'Número de página', schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Listado paginado de usuarios'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate($perPage);

        return UserResource::collection($users)->response();
    }

    #[OA\Post(
        path: '/api/users',
        summary: 'Registrar un usuario',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Christian Castillo'),
                    new OA\Property(property: 'username', type: 'string', nullable: true, example: 'christian'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'christian@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Usuario creado', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/users/{user_id}',
        summary: 'Ver un usuario',
        description: 'Devuelve un usuario junto con sus enlaces de redes sociales.',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, description: 'ID numérico del usuario (no el username)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Usuario encontrado', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function show(User $user): JsonResponse
    {
        $user->load(['socialLinks' => fn ($query) => $query->orderBy('position'), 'socialLinks.socialPlatform']);

        return (new UserResource($user))->response();
    }

    #[OA\Put(
        path: '/api/users/{user_id}',
        summary: 'Actualizar un usuario',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, description: 'ID numérico del usuario (no el username)', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Christian Castillo'),
                    new OA\Property(property: 'username', type: 'string', nullable: true, example: 'christian'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'christian@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'newpassword123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Usuario actualizado', content: new OA\JsonContent(ref: '#/components/schemas/User')),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return (new UserResource($user))->response();
    }

    #[OA\Delete(
        path: '/api/users/{user_id}',
        summary: 'Eliminar un usuario',
        description: 'Elimina el usuario y, en cascada, todos sus enlaces de redes sociales.',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, description: 'ID numérico del usuario (no el username)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Usuario eliminado'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
