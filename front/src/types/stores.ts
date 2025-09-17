import type { OrganizationFull } from '@/types/OrganizationServiceType'
export interface NeighborhoodsStateData
{
    name: string
    coordinates: number[]
}
export interface NeighborhoodsState 
{
    Data: NeighborhoodsStateData[]
    query: string
}
export interface mapState 
{
    map: any | null
    xy: string
    manuallyAddFlag: boolean
    currentMarker: any | null
}
export type { OrganizationFull, ID } from '@/types/OrganizationServiceType'
export interface OrganizationState
{
    organizations: OrganizationFull[]
    currentOrganization: OrganizationFull | null
    loading: boolean
    error: string | null
}
