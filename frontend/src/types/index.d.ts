import type { AvatarProps } from '@nuxt/ui'

// ─── Auth ────────────────────────────────────────────────────────────────────

export interface AuthUser {
  id: number
  name: string
  email: string
}

// ─── Proprietaire ─────────────────────────────────────────────────────────────

export interface Proprietaire {
  id: number
  nom: string
  prenom: string
  email: string | null
  telephone: string | null
  adresse: string | null
  created_at: string
  updated_at: string
  biens?: Bien[]
}

// ─── Bien ────────────────────────────────────────────────────────────────────

export type BienType = 'maison' | 'appartement'

export interface Bien {
  id: number
  titre: string
  type: BienType
  adresse: string
  ville: string | null
  prix: number
  proprietaire_id: number
  proprietaire?: Proprietaire
  contrats?: Contrat[]
  created_at: string
  updated_at: string
}

export interface BienForm {
  titre: string
  type: BienType | ''
  adresse: string
  ville: string
  prix: number | ''
  proprietaire_id: number | ''
}

// ─── Locataire ────────────────────────────────────────────────────────────────

export interface Locataire {
  id: number
  nom: string
  prenom: string
  email: string | null
  telephone: string | null
  created_at: string
  updated_at: string
  contrats?: Contrat[]
}

export interface LocataireForm {
  nom: string
  prenom: string
  email: string
  telephone: string
}

// ─── Contrat ─────────────────────────────────────────────────────────────────

export type ContratStatut = 'actif' | 'termine' | 'resilie'

export interface Contrat {
  id: number
  bien_id: number
  locataire_id: number
  date_debut: string
  date_fin: string | null
  montant_mensuel: number
  statut: ContratStatut
  bien?: Bien
  locataire?: Locataire
  created_at: string
  updated_at: string
}

export interface ContratForm {
  bien_id: number | ''
  locataire_id: number | ''
  date_debut: string
  date_fin: string
  montant_mensuel: number | ''
  statut: ContratStatut
}

// ─── API Response ─────────────────────────────────────────────────────────────

export interface ApiResponse<T> {
  data: T
  message?: string
}

// ─── Dashboard Stats ──────────────────────────────────────────────────────────

export interface Stat {
  title: string
  icon: string
  value: number | string
  color: string
  to: string
}

// ─── Legacy types (conservés pour les composants existants) ───────────────────

export type UserStatus = 'subscribed' | 'unsubscribed' | 'bounced'
export type SaleStatus = 'paid' | 'failed' | 'refunded'
export type Period = 'daily' | 'weekly' | 'monthly'

export interface User {
  id: number
  name: string
  email: string
  avatar?: AvatarProps
  status: UserStatus
  location: string
}

export interface Range {
  start: Date
  end: Date
}
