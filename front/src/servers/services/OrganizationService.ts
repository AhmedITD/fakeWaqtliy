import { BaseApiService } from '../core/BaseApiService'
import { API_ENDPOINTS } from '../config/api'
import type { ApiResponse } from '../../types/api'
import type { 
  OrganizationFull,
} from '../../types/OrganizationServiceType'

/**
 * Organization service - Simplified CRUD (findAll, findById)
 * Backend handles all joins with organization_locations table
 */
export class OrganizationService extends BaseApiService<OrganizationFull> {
  constructor() {
    super(API_ENDPOINTS.organizations)
  }

  // Note: All data including locations, owner details, etc. 
  // are joined and returned by the backend in findAll() and findById()
}

// Create and export singleton instance
export const organizationService = new OrganizationService()