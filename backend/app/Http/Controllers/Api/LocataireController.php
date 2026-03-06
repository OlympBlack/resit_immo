<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocataireRequest;
use App\Http\Requests\UpdateLocataireRequest;
use App\Models\Locataire;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LocataireController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/locataires',
        summary: 'Lister tous les locataires',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste de tous les locataires',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Locataire')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(): JsonResponse
    {
        $locataires = Locataire::all();

        return response()->json(['data' => $locataires]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/locataires',
        summary: 'Créer un locataire',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Durand'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Alice'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'alice@example.com', nullable: true),
                    new OA\Property(property: 'telephone', type: 'string', example: '07 98 76 54 32', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Locataire créé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Locataire créé avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Locataire'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    public function store(StoreLocataireRequest $request): JsonResponse
    {
        $locataire = Locataire::create($request->validated());

        return response()->json([
            'message' => 'Locataire créé avec succès.',
            'data' => $locataire,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/locataires/{id}',
        summary: 'Afficher un locataire avec ses contrats',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du locataire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détail du locataire avec ses contrats',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Locataire'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Locataire introuvable'),
        ]
    )]
    public function show(Locataire $locataire): JsonResponse
    {
        $locataire->load('contrats.bien');

        return response()->json(['data' => $locataire]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Put(
        path: '/api/locataires/{id}',
        summary: 'Mettre à jour un locataire (remplacement complet)',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du locataire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Durand'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Alice'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'telephone', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Locataire mis à jour',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Locataire mis à jour avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Locataire'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Locataire introuvable'),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    #[OA\Patch(
        path: '/api/locataires/{id}',
        summary: 'Mettre à jour un locataire (mise à jour partielle)',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du locataire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'telephone', type: 'string', nullable: true),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Locataire mis à jour'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Locataire introuvable'),
        ]
    )]
    public function update(UpdateLocataireRequest $request, Locataire $locataire): JsonResponse
    {
        $locataire->update($request->validated());

        return response()->json([
            'message' => 'Locataire mis à jour avec succès.',
            'data' => $locataire,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Delete(
        path: '/api/locataires/{id}',
        summary: 'Supprimer un locataire',
        tags: ['Locataires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du locataire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Locataire supprimé',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Locataire supprimé avec succès.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Locataire introuvable'),
        ]
    )]
    public function destroy(Locataire $locataire): JsonResponse
    {
        $locataire->delete();

        return response()->json(['message' => 'Locataire supprimé avec succès.']);
    }
}
