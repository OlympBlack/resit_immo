<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBienRequest;
use App\Http\Requests\UpdateBienRequest;
use App\Models\Bien;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BienController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/biens',
        summary: 'Lister tous les biens immobiliers',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des biens avec leur propriétaire',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Bien')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(): JsonResponse
    {
        $biens = Bien::with('proprietaire')->get();

        return response()->json(['data' => $biens]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/biens',
        summary: 'Créer un bien immobilier',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['titre', 'type', 'adresse', 'prix', 'proprietaire_id'],
                properties: [
                    new OA\Property(property: 'titre', type: 'string', example: 'Appartement T3 Centre-ville'),
                    new OA\Property(property: 'type', type: 'string', enum: ['maison', 'appartement'], example: 'appartement'),
                    new OA\Property(property: 'adresse', type: 'string', example: '5 Avenue Victor Hugo'),
                    new OA\Property(property: 'ville', type: 'string', example: 'Lyon', nullable: true),
                    new OA\Property(property: 'prix', type: 'number', format: 'float', example: 850.00),
                    new OA\Property(property: 'proprietaire_id', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Bien créé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bien créé avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Bien'),
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
    public function store(StoreBienRequest $request): JsonResponse
    {
        $bien = Bien::create($request->validated());
        $bien->load('proprietaire');

        return response()->json([
            'message' => 'Bien créé avec succès.',
            'data' => $bien,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/biens/{id}',
        summary: 'Afficher un bien avec son propriétaire et ses contrats',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du bien',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détail du bien',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Bien'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Bien introuvable'),
        ]
    )]
    public function show(Bien $bien): JsonResponse
    {
        $bien->load(['proprietaire', 'contrats.locataire']);

        return response()->json(['data' => $bien]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Put(
        path: '/api/biens/{id}',
        summary: 'Mettre à jour un bien (remplacement complet)',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du bien',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'titre', type: 'string', example: 'Maison 4 pièces'),
                    new OA\Property(property: 'type', type: 'string', enum: ['maison', 'appartement']),
                    new OA\Property(property: 'adresse', type: 'string', example: '10 Rue du Moulin'),
                    new OA\Property(property: 'ville', type: 'string', nullable: true),
                    new OA\Property(property: 'prix', type: 'number', format: 'float'),
                    new OA\Property(property: 'proprietaire_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bien mis à jour',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bien mis à jour avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Bien'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Bien introuvable'),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    #[OA\Patch(
        path: '/api/biens/{id}',
        summary: 'Mettre à jour un bien (mise à jour partielle)',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du bien',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'titre', type: 'string'),
                    new OA\Property(property: 'prix', type: 'number', format: 'float'),
                    new OA\Property(property: 'ville', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Bien mis à jour'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Bien introuvable'),
        ]
    )]
    public function update(UpdateBienRequest $request, Bien $bien): JsonResponse
    {
        $bien->update($request->validated());
        $bien->load('proprietaire');

        return response()->json([
            'message' => 'Bien mis à jour avec succès.',
            'data' => $bien,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Delete(
        path: '/api/biens/{id}',
        summary: 'Supprimer un bien',
        tags: ['Biens'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du bien',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Bien supprimé',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Bien supprimé avec succès.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Bien introuvable'),
        ]
    )]
    public function destroy(Bien $bien): JsonResponse
    {
        $bien->delete();

        return response()->json(['message' => 'Bien supprimé avec succès.']);
    }
}
