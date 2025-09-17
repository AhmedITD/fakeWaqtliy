import { BaseApiService } from '../core/BaseApiService'
import { API_ENDPOINTS } from '../config/api'
import type { 
  Space,
  ApiResponse,
  RequestOptions,
  SearchParams,
  ID
} from '../../types/OrganizationServiceType'

/**
 * Space service - Simplified CRUD (findAll, findById)
 * Backend handles all joins with space_locations table
 */
export class SpaceService extends BaseApiService<Space> {
  constructor() {
    super(API_ENDPOINTS.spaces.index)
  }

  // Note: All data including locations, category, organization details, etc. 
  // are joined and returned by the backend in findAll() and findById()
}

// Create and export singleton instance
export const spaceService = new SpaceService()