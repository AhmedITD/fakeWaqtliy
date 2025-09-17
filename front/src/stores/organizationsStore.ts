import { defineStore } from 'pinia'
import { organizationService } from '@/servers/services/OrganizationService'
import type { OrganizationState, OrganizationFull, ID } from '@/types/stores'
import type { ApiResponse } from '@/types/api'
export const useOrganizationsStore = defineStore('organizations', 
{
  state: (): OrganizationState => ({
    organizations: [],
    currentOrganization: null,
    loading: false,
    error: null
  }),
  actions: {
    async fetchAllOrganizations(): Promise<void> 
    {
      this.loading = true
      this.error = null

      try 
      {
        const response: ApiResponse<OrganizationFull[]> = await organizationService.findAll()
        this.organizations = response.data
      
      } catch (err: any) 
      {
        this.error = err.message || 'Failed to fetch organizations'
        console.error('Error fetching organizations:', err)
      
      } finally 
      {
        this.loading = false
      }
    },
    async fetchOrganizationById(id: ID): Promise<OrganizationFull | null> 
    {
      this.loading = true
      this.error = null
      
      try 
      {
        const response: ApiResponse<OrganizationFull> = await organizationService.findById(id)
        this.currentOrganization = response.data      
        return response.data
      
      } catch (err: any) 
      {
        this.error = err.message || 'Failed to fetch organization'
        console.error('Error fetching organization:', err)
        return null
      
      } finally 
      {
        this.loading = false
      }
    },
    setCurrentOrganization(organization: OrganizationFull | null): void 
    {
      this.currentOrganization = organization
    },
    clearError(): void 
    {
      this.error = null
    },
    resetStore(): void 
    {
      this.organizations = []
      this.currentOrganization = null
      this.loading = false
      this.error = null
    },
  },
})
