<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocialPlatformRequest;
use App\Http\Requests\UpdateSocialPlatformRequest;
use App\Http\Resources\SocialPlatformResource;
use App\Models\SocialPlatform;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SocialPlatformController extends Controller
{
    #[OA\Get(
        path: '/api/social-platforms',
        summary: 'Listar redes sociales del catálogo',
        description: 'Devuelve un listado paginado de plataformas (Instagram, Facebook, etc). Permite buscar por nombre o slug.',
        tags: ['Social Platforms'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Texto a buscar en name o slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Cantidad de resultados por página (1-100)', schema: new OA\Schema(type: 'integer', default: 15)),
            new OA\Parameter(name: 'page', in: 'query', description: 'Número de página', schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Listado paginado de plataformas'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->integer('per_page', 15), 1), 100);

        $platforms = SocialPlatform::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = $request->string('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage);

        return SocialPlatformResource::collection($platforms)->response();
    }

    #[OA\Post(
        path: '/api/social-platforms',
        summary: 'Crear una red social en el catálogo',
        tags: ['Social Platforms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Instagram'),
                    new OA\Property(property: 'slug', type: 'string', example: 'instagram'),
                    new OA\Property(property: 'base_url', type: 'string', nullable: true, example: 'https://instagram.com/'),
                    new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'fa-brands fa-instagram'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Plataforma creada', content: new OA\JsonContent(ref: '#/components/schemas/SocialPlatform')),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreSocialPlatformRequest $request): JsonResponse
    {
        $platform = SocialPlatform::query()->create($request->validated())->refresh();

        return (new SocialPlatformResource($platform))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: '/api/social-platforms/{social_platform_id}',
        summary: 'Ver una red social del catálogo',
        tags: ['Social Platforms'],
        parameters: [
            new OA\Parameter(name: 'social_platform_id', in: 'path', required: true, description: 'ID numérico de la plataforma (no el slug)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Plataforma encontrada', content: new OA\JsonContent(ref: '#/components/schemas/SocialPlatform')),
            new OA\Response(response: 404, description: 'Plataforma no encontrada'),
        ]
    )]
    public function show(SocialPlatform $socialPlatform): JsonResponse
    {
        return (new SocialPlatformResource($socialPlatform))->response();
    }

    #[OA\Put(
        path: '/api/social-platforms/{social_platform_id}',
        summary: 'Actualizar una red social del catálogo',
        tags: ['Social Platforms'],
        parameters: [
            new OA\Parameter(name: 'social_platform_id', in: 'path', required: true, description: 'ID numérico de la plataforma (no el slug)', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Instagram'),
                    new OA\Property(property: 'slug', type: 'string', example: 'instagram'),
                    new OA\Property(property: 'base_url', type: 'string', nullable: true, example: 'https://instagram.com/'),
                    new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'fa-brands fa-instagram'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Plataforma actualizada', content: new OA\JsonContent(ref: '#/components/schemas/SocialPlatform')),
            new OA\Response(response: 404, description: 'Plataforma no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateSocialPlatformRequest $request, SocialPlatform $socialPlatform): JsonResponse
    {
        $socialPlatform->update($request->validated());

        return (new SocialPlatformResource($socialPlatform))->response();
    }

    #[OA\Delete(
        path: '/api/social-platforms/{social_platform_id}',
        summary: 'Eliminar una red social del catálogo',
        description: 'Elimina la plataforma y, en cascada, todos los enlaces de usuarios que la referencian.',
        tags: ['Social Platforms'],
        parameters: [
            new OA\Parameter(name: 'social_platform_id', in: 'path', required: true, description: 'ID numérico de la plataforma (no el slug)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Plataforma eliminada'),
            new OA\Response(response: 404, description: 'Plataforma no encontrada'),
        ]
    )]
    public function destroy(SocialPlatform $socialPlatform): JsonResponse
    {
        $socialPlatform->delete();

        return response()->json(null, 204);
    }
}
