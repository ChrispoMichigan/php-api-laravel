<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Linktree API',
    description: 'API REST de ejemplo (CRUD) para un clon de Linktree: usuarios, catálogo de redes sociales y los enlaces sociales de cada usuario.'
)]
#[OA\Server(url: '/', description: 'Servidor actual')]
#[OA\Tag(name: 'Users', description: 'Gestión de usuarios')]
#[OA\Tag(name: 'Social Platforms', description: 'Catálogo de redes sociales disponibles')]
#[OA\Tag(name: 'User Social Links', description: 'Enlaces de redes sociales de un usuario')]
abstract class Controller
{
    //
}
