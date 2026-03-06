<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProprietaireRequest;
use App\Http\Requests\UpdateProprietaireRequest;
use App\Models\Proprietaire;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProprietaireController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/proprietaires',
        summary: 'Lister tous les propriétaires',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des propriétaires avec leurs biens',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Proprietaire')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(): JsonResponse
    {
        $proprietaires = Proprietaire::with('biens')->get();

        return response()->json(['data' => $proprietaires]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/proprietaires',
        summary: 'Créer un propriétaire',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nom', 'prenom'],
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Martin'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Sophie'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'sophie@example.com', nullable: true),
                    new OA\Property(property: 'telephone', type: 'string', example: '06 12 34 56 78', nullable: true),
                    new OA\Property(property: 'adresse', type: 'string', example: '12 Rue de Paris', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Propriétaire créé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Propriétaire créé avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Proprietaire'),
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
    public function store(StoreProprietaireRequest $request): JsonResponse
    {
        $proprietaire = Proprietaire::create($request->validated());

        return response()->json([
            'message' => 'Propriétaire créé avec succès.',
            'data' => $proprietaire,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/proprietaires/{id}',
        summary: 'Afficher un propriétaire avec ses biens et contrats',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du propriétaire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détail du propriétaire',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Proprietaire'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Propriétaire introuvable'),
        ]
    )]
    public function show(Proprietaire $proprietaire): JsonResponse
    {
        $proprietaire->load('biens.contrats');

        return response()->json(['data' => $proprietaire]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Put(
        path: '/api/proprietaires/{id}',
        summary: 'Mettre à jour un propriétaire (remplacement complet)',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du propriétaire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Martin'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Sophie'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'telephone', type: 'string', nullable: true),
                    new OA\Property(property: 'adresse', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Propriétaire mis à jour',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Propriétaire mis à jour avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Proprietaire'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Propriétaire introuvable'),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    #[OA\Patch(
        path: '/api/proprietaires/{id}',
        summary: 'Mettre à jour un propriétaire (mise à jour partielle)',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du propriétaire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nom', type: 'string', example: 'Martin'),
                    new OA\Property(property: 'prenom', type: 'string', example: 'Sophie'),
                    new OA\Property(property: 'telephone', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Propriétaire mis à jour'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Propriétaire introuvable'),
        ]
    )]
    public function update(UpdateProprietaireRequest $request, Proprietaire $proprietaire): JsonResponse
    {
        $proprietaire->update($request->validated());

        return response()->json([
            'message' => 'Propriétaire mis à jour avec succès.',
            'data' => $proprietaire,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Delete(
        path: '/api/proprietaires/{id}',
        summary: 'Supprimer un propriétaire',
        tags: ['Propriétaires'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du propriétaire',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Propriétaire supprimé',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Propriétaire supprimé avec succès.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Propriétaire introuvable'),
        ]
    )]
    public function destroy(Proprietaire $proprietaire): JsonResponse
    {
        $proprietaire->delete();

        return response()->json(['message' => 'Propriétaire supprimé avec succès.']);
    }
}
