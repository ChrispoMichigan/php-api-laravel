<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSocialLinkRequest;
use App\Http\Requests\UpdateUserSocialLinkRequest;
use App\Http\Resources\UserSocialLinkResource;
use App\Models\User;
use App\Models\UserSocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserSocialLinkController extends Controller
{
    #[OA\Get(
        path: '/api/users/{user_id}/social-links',
        summary: 'Listar los enlaces sociales de un usuario',
        description: 'Devuelve un listado paginado de los enlaces de redes sociales del usuario. Permite buscar por handle, título, url o nombre de la plataforma.',
        tags: ['User Social Links'],
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, description: 'ID numérico del usuario (no el username)', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'search', in: 'query', description: 'Texto a buscar en handle, title, url o el nombre de la plataforma', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Cantidad de resultados por página (1-100)', schema: new OA\Schema(type: 'integer', default: 15)),
            new OA\Parameter(name: 'page', in: 'query', description: 'Número de página', schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Listado paginado de enlaces sociales'),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
        ]
    )]
    public function index(Request $request, User $user): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $links = $user->socialLinks()
            ->with('socialPlatform')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('handle', 'like', "%{$term}%")
                        ->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('url', 'like', "%{$term}%")
                        ->orWhereHas('socialPlatform', fn ($sq) => $sq->where('name', 'like', "%{$term}%"));
                });
            })
            ->orderBy('position')
            ->paginate($perPage);

        return UserSocialLinkResource::collection($links)->response();
    }

    #[OA\Post(
        path: '/api/users/{user_id}/social-links',
        summary: 'Agregar un enlace social a un usuario',
        tags: ['User Social Links'],
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, description: 'ID numérico del usuario (no el username)', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['social_platform_id', 'url'],
                properties: [
                    new OA\Property(property: 'social_platform_id', type: 'integer', example: 1),
                    new OA\Property(property: 'url', type: 'string', example: 'https://instagram.com/christian'),
                    new OA\Property(property: 'handle', type: 'string', nullable: true, example: '@christian'),
                    new OA\Property(property: 'title', type: 'string', nullable: true, example: 'Mi Instagram'),
                    new OA\Property(property: 'position', type: 'integer', example: 0),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Enlace creado', content: new OA\JsonContent(ref: '#/components/schemas/UserSocialLink')),
            new OA\Response(response: 404, description: 'Usuario no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreUserSocialLinkRequest $request, User $user): JsonResponse
    {
        $link = $user->socialLinks()->create($request->validated());
        $link->refresh()->load('socialPlatform');

        return (new UserSocialLinkResource($link))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/social-links/{social_link_id}',
        summary: 'Ver un enlace social',
        tags: ['User Social Links'],
        parameters: [
            new OA\Parameter(name: 'social_link_id', in: 'path', required: true, description: 'ID numérico del enlace social', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Enlace encontrado', content: new OA\JsonContent(ref: '#/components/schemas/UserSocialLink')),
            new OA\Response(response: 404, description: 'Enlace no encontrado'),
        ]
    )]
    public function show(UserSocialLink $socialLink): JsonResponse
    {
        $socialLink->load('socialPlatform');

        return (new UserSocialLinkResource($socialLink))->response();
    }

    #[OA\Put(
        path: '/api/social-links/{social_link_id}',
        summary: 'Actualizar un enlace social',
        tags: ['User Social Links'],
        parameters: [
            new OA\Parameter(name: 'social_link_id', in: 'path', required: true, description: 'ID numérico del enlace social', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'social_platform_id', type: 'integer', example: 1),
                    new OA\Property(property: 'url', type: 'string', example: 'https://instagram.com/christian'),
                    new OA\Property(property: 'handle', type: 'string', nullable: true, example: '@christian'),
                    new OA\Property(property: 'title', type: 'string', nullable: true, example: 'Mi Instagram'),
                    new OA\Property(property: 'position', type: 'integer', example: 0),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Enlace actualizado', content: new OA\JsonContent(ref: '#/components/schemas/UserSocialLink')),
            new OA\Response(response: 404, description: 'Enlace no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateUserSocialLinkRequest $request, UserSocialLink $socialLink): JsonResponse
    {
        $socialLink->update($request->validated());
        $socialLink->load('socialPlatform');

        return (new UserSocialLinkResource($socialLink))->response();
    }

    #[OA\Delete(
        path: '/api/social-links/{social_link_id}',
        summary: 'Eliminar un enlace social',
        tags: ['User Social Links'],
        parameters: [
            new OA\Parameter(name: 'social_link_id', in: 'path', required: true, description: 'ID numérico del enlace social', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Enlace eliminado'),
            new OA\Response(response: 404, description: 'Enlace no encontrado'),
        ]
    )]
    public function destroy(UserSocialLink $socialLink): JsonResponse
    {
        $socialLink->delete();

        return response()->json(null, 204);
    }
}
