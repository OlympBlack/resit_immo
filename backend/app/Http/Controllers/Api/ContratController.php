<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContratRequest;
use App\Http\Requests\UpdateContratRequest;
use App\Models\Contrat;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ContratController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/contrats',
        summary: 'Lister tous les contrats de location',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des contrats avec bien et locataire',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Contrat')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(): JsonResponse
    {
        $contrats = Contrat::with(['bien', 'locataire'])->get();

        return response()->json(['data' => $contrats]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // STORE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Post(
        path: '/api/contrats',
        summary: 'Créer un contrat de location',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['bien_id', 'locataire_id', 'date_debut', 'montant_mensuel'],
                properties: [
                    new OA\Property(property: 'bien_id', type: 'integer', example: 1),
                    new OA\Property(property: 'locataire_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date', example: '2026-01-01'),
                    new OA\Property(property: 'date_fin', type: 'string', format: 'date', example: '2026-12-31', nullable: true),
                    new OA\Property(property: 'montant_mensuel', type: 'number', format: 'float', example: 850.00),
                    new OA\Property(property: 'statut', type: 'string', enum: ['actif', 'termine', 'resilie'], example: 'actif'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Contrat créé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Contrat créé avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Contrat'),
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
    public function store(StoreContratRequest $request): JsonResponse
    {
        $contrat = Contrat::create($request->validated());
        $contrat->load(['bien', 'locataire']);

        return response()->json([
            'message' => 'Contrat créé avec succès.',
            'data' => $contrat,
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Get(
        path: '/api/contrats/{id}',
        summary: 'Afficher un contrat avec bien, propriétaire et locataire',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du contrat',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détail du contrat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Contrat'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Contrat introuvable'),
        ]
    )]
    public function show(Contrat $contrat): JsonResponse
    {
        $contrat->load(['bien.proprietaire', 'locataire']);

        return response()->json(['data' => $contrat]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Put(
        path: '/api/contrats/{id}',
        summary: 'Mettre à jour un contrat (remplacement complet)',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du contrat',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'bien_id', type: 'integer', example: 1),
                    new OA\Property(property: 'locataire_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date', example: '2026-01-01'),
                    new OA\Property(property: 'date_fin', type: 'string', format: 'date', example: '2026-12-31', nullable: true),
                    new OA\Property(property: 'montant_mensuel', type: 'number', format: 'float', example: 900.00),
                    new OA\Property(property: 'statut', type: 'string', enum: ['actif', 'termine', 'resilie']),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Contrat mis à jour',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Contrat mis à jour avec succès.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Contrat'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Contrat introuvable'),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    #[OA\Patch(
        path: '/api/contrats/{id}',
        summary: 'Mettre à jour un contrat (mise à jour partielle)',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du contrat',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'statut', type: 'string', enum: ['actif', 'termine', 'resilie'], example: 'termine'),
                    new OA\Property(property: 'montant_mensuel', type: 'number', format: 'float'),
                    new OA\Property(property: 'date_fin', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Contrat mis à jour partiellement'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Contrat introuvable'),
        ]
    )]
    public function update(UpdateContratRequest $request, Contrat $contrat): JsonResponse
    {
        $contrat->update($request->validated());
        $contrat->load(['bien', 'locataire']);

        return response()->json([
            'message' => 'Contrat mis à jour avec succès.',
            'data' => $contrat,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────

    #[OA\Delete(
        path: '/api/contrats/{id}',
        summary: 'Supprimer un contrat',
        tags: ['Contrats'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID du contrat',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Contrat supprimé',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Contrat supprimé avec succès.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Contrat introuvable'),
        ]
    )]
    public function destroy(Contrat $contrat): JsonResponse
    {
        $contrat->delete();

        return response()->json(['message' => 'Contrat supprimé avec succès.']);
    }
}
