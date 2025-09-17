import { axiosClient } from './AxiosClient'
import type { ApiResponse, RequestOptions, SearchParams, CacheConfig } from '../../types/api'
/**
 * Base API service class with simplified CRUD operations (findAll, findById)
 * Backend handles all table joins and relationships
 */
export abstract class BaseApiService<T = any> {
  protected baseEndpoint: string
  protected cache: Map<string, { data: any; expires: number }> = new Map()

  constructor(baseEndpoint: string) {
    this.baseEndpoint = baseEndpoint
  }
  /**
   * Find all items (with joined data from backend)
   */
  async findAll(): Promise<ApiResponse<T[]>> {
    const cacheKey = this.getCacheKey('findAll')
    const cached = this.getFromCache(cacheKey)  
    if (cached) return cached

    const response = await axiosClient.get<T[]>(this.baseEndpoint)

    this.setCache(cacheKey, response, { ttl: 10 * 60 * 1000 }) // 10 minutes
    return response
  }

  /**
   * Find single item by ID (with joined data from backend)
   */
  async findById(id: string | number): Promise<ApiResponse<T>> {
    const cacheKey = this.getCacheKey('findById', { id })
    const cached = this.getFromCache(cacheKey)
    if (cached) return cached

    const endpoint = `${this.baseEndpoint}/${id}`
    const response = await axiosClient.get<T>(endpoint)

    this.setCache(cacheKey, response, { ttl: 30 * 60 * 1000 }) // 30 minutes
    return response
  }
  /**
   * Cache management
   */
  protected getCacheKey(method: string, params?: any): string {
    const paramString = params ? JSON.stringify(params) : ''
    return `${this.baseEndpoint}:${method}:${paramString}`
  }
  protected getFromCache<R = any>(key: string): R | null {
    const cached = this.cache.get(key)
    if (!cached) return null

    if (Date.now() > cached.expires) {
      this.cache.delete(key)
      return null
    }

    return cached.data
  }
  protected setCache(key: string, data: any, config: CacheConfig = {}): void {
    const ttl = config.ttl || 5 * 60 * 1000 // Default 5 minutes
    const expires = Date.now() + ttl

    this.cache.set(key, { data, expires })
  }
  protected invalidateCache(patterns: string[]): void {
    for (const [key] of this.cache) {
      for (const pattern of patterns) {
        if (key.includes(pattern)) {
          this.cache.delete(key)
          break
        }
      }
    }
  }
  /**
   * Clear all cache for this service
   */
  clearCache(): void {
    this.cache.clear()
  }
  /**
   * Get cache statistics
   */
  getCacheStats(): { size: number; keys: string[] } {
    return {
      size: this.cache.size,
      keys: Array.from(this.cache.keys())
    }
  }
}
