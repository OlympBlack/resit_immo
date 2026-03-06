<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: '1.0.0',
        title: 'RESIT_Immo API',
        description: 'API REST pour la gestion locative — propriétaires, biens, locataires et contrats.',
        contact: new OA\Contact(
            name: 'Support RESIT_Immo',
            email: 'support@resit-immo.fr'
        )
    ),
    servers: [
        new OA\Server(
            url: 'http://localhost:8000',
            description: 'Serveur de développement local'
        )
    ],
    security: [['sanctum' => []]]
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Token Sanctum. Connectez-vous via POST /api/auth/login pour obtenir votre token.'
)]

// ─── Schémas réutilisables ────────────────────────────────────────────────

#[OA\Schema(
    schema: 'User',
    title: 'Utilisateur',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Jean Dupont'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jean@example.com'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Proprietaire',
    title: 'Propriétaire',
    required: ['nom', 'prenom'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nom', type: 'string', example: 'Martin'),
        new OA\Property(property: 'prenom', type: 'string', example: 'Sophie'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'sophie@example.com', nullable: true),
        new OA\Property(property: 'telephone', type: 'string', example: '06 12 34 56 78', nullable: true),
        new OA\Property(property: 'adresse', type: 'string', example: '12 Rue de Paris', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Bien',
    title: 'Bien immobilier',
    required: ['titre', 'type', 'adresse', 'prix', 'proprietaire_id'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'titre', type: 'string', example: 'Appartement T3 Centre-ville'),
        new OA\Property(property: 'type', type: 'string', enum: ['maison', 'appartement'], example: 'appartement'),
        new OA\Property(property: 'adresse', type: 'string', example: '5 Avenue Victor Hugo'),
        new OA\Property(property: 'ville', type: 'string', example: 'Lyon', nullable: true),
        new OA\Property(property: 'prix', type: 'number', format: 'float', example: 850.00),
        new OA\Property(property: 'proprietaire_id', type: 'integer', example: 1),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Locataire',
    title: 'Locataire',
    required: ['nom', 'prenom'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nom', type: 'string', example: 'Durand'),
        new OA\Property(property: 'prenom', type: 'string', example: 'Alice'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'alice@example.com', nullable: true),
        new OA\Property(property: 'telephone', type: 'string', example: '07 98 76 54 32', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Contrat',
    title: 'Contrat de location',
    required: ['bien_id', 'locataire_id', 'date_debut', 'montant_mensuel'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'bien_id', type: 'integer', example: 1),
        new OA\Property(property: 'locataire_id', type: 'integer', example: 1),
        new OA\Property(property: 'date_debut', type: 'string', format: 'date', example: '2026-01-01'),
        new OA\Property(property: 'date_fin', type: 'string', format: 'date', example: '2026-12-31', nullable: true),
        new OA\Property(property: 'montant_mensuel', type: 'number', format: 'float', example: 850.00),
        new OA\Property(property: 'statut', type: 'string', enum: ['actif', 'termine', 'resilie'], example: 'actif'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'ValidationError',
    title: 'Erreur de validation',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            example: ['nom' => ['Le nom est obligatoire.']]
        ),
    ]
)]

class OpenApi
{
    // Ce fichier sert uniquement de point d'entrée pour les annotations Swagger globales.
}
