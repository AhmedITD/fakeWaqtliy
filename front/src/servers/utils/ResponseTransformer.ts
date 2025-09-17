import type { ApiResponse } from '../../types/api'

/**
 * Response transformer utilities
 * Transform API responses to match frontend expectations
 */
export class ResponseTransformer {
  /**
   * Transform single resource response
   */
  static transformResourceResponse<T>(response: any): ApiResponse<T> 
  {
    if (response.data) {
      return {
        data: response.data,
        message: response.message,
        success: true,
        status: 200
      }
    }

    return {
      data: response,
      success: true,
      status: 200
    }
  }
  /**
   * Transform error response
   */
//   static transformErrorResponse(error: any): 
//   {
//     message: string
//     status: number
//     errors?: Record<string, string[]>
//     code?: string
//   } {
//     // Handle different error formats
//     if (error.response) {
//       // Axios-style error
//       const data = error.response.data
//       return {
//         message: data.message || 'An error occurred',
//         status: error.response.status,
//         errors: data.errors,
//         code: data.code
//       }
//     }

//     if (error.status) {
//       // Fetch API error
//       return {
//         message: error.message || 'An error occurred',
//         status: error.status,
//         errors: error.errors,
//         code: error.code
//       }
//     }

//     // Generic error
//     return {
//       message: error.message || 'An unexpected error occurred',
//       status: 500
//     }
//   }
  /**
   * Transform GeoJSON response for organizations
   */
//   static transformGeoJsonResponse(response: any): 
//   {
//     type: 'FeatureCollection'
//     features: Array<{
//       type: 'Feature'
//       properties: any
//       geometry: {
//         type: string
//         coordinates: any
//       }
//     }>
//   } {
//     if (response.type === 'FeatureCollection') {
//       return response
//     }

//     // Transform array of organizations to GeoJSON
//     if (Array.isArray(response)) {
//       return {
//         type: 'FeatureCollection',
//         features: response.map(item => ({
//           type: 'Feature',
//           properties: {
//             id: item.id,
//             name: item.name,
//             ...item.properties
//           },
//           geometry: item.geometry || {
//             type: 'Point',
//             coordinates: item.coordinates || [0, 0]
//           }
//         }))
//       }
//     }
//     // Transform single organization to GeoJSON
//     return {
//       type: 'FeatureCollection',
//       features: [{
//         type: 'Feature',
//         properties: {
//           id: response.id,
//           name: response.name,
//           ...response.properties
//         },
//         geometry: response.geometry || {
//           type: 'Point',
//           coordinates: response.coordinates || [0, 0]
//         }
//       }]
//     }
//   }
  /**
   * Transform coordinates format
   */
  static transformCoordinates(coordinates: any): [number, number] 
  {
    if (Array.isArray(coordinates) && coordinates.length >= 2) {
      return [Number(coordinates[0]), Number(coordinates[1])]
    }

    if (typeof coordinates === 'string') {
      const parts = coordinates.split(',').map(s => s.trim())
      if (parts.length >= 2) {
        return [Number(parts[0]), Number(parts[1])]
      }
    }

    if (coordinates.lng !== undefined && coordinates.lat !== undefined) {
      return [Number(coordinates.lng), Number(coordinates.lat)]
    }

    if (coordinates.longitude !== undefined && coordinates.latitude !== undefined) {
      return [Number(coordinates.longitude), Number(coordinates.latitude)]
    }

    return [0, 0]
  }
  /**
   * Transform snake_case to camelCase
   */
//   static toCamelCase<T = any>(obj: any): T 
//   {
//     if (obj === null || typeof obj !== 'object') {
//       return obj
//     }

//     if (Array.isArray(obj)) {
//       return obj.map(item => this.toCamelCase(item)) as T
//     }

//     const transformed: any = {}
    
//     Object.keys(obj).forEach(key => {
//       const camelKey = key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase())
//       transformed[camelKey] = this.toCamelCase(obj[key])
//     })

//     return transformed
//   }
//   /**
//    * Transform camelCase to snake_case
//    */
//   static toSnakeCase<T = any>(obj: any): T 
//   {
//     if (obj === null || typeof obj !== 'object') {
//       return obj
//     }

//     if (Array.isArray(obj)) {
//       return obj.map(item => this.toSnakeCase(item)) as T
//     }

//     const transformed: any = {}
    
//     Object.keys(obj).forEach(key => {
//       const snakeKey = key.replace(/[A-Z]/g, letter => `_${letter.toLowerCase()}`)
//       transformed[snakeKey] = this.toSnakeCase(obj[key])
//     })

//     return transformed
//   }

  /**
   * Clean null/undefined values
   */
//   static cleanNullValues<T>(obj: T): T 
//   {
//     if (obj === null || obj === undefined) {
//       return obj
//     }

//     if (Array.isArray(obj)) {
//       return obj.map(item => this.cleanNullValues(item)).filter(item => item !== null && item !== undefined) as T
//     }

//     if (typeof obj === 'object') {
//       const cleaned: any = {}
//       Object.keys(obj as any).forEach(key => {
//         const value = (obj as any)[key]
//         if (value !== null && value !== undefined) {
//           cleaned[key] = this.cleanNullValues(value)
//         }
//       })
//       return cleaned
//     }

//     return obj
//   }
  /**
   * Flatten nested object
   */
//   static flatten(obj: any, prefix = '', separator = '.'): Record<string, any> 
//   {
//     const flattened: Record<string, any> = {}

//     Object.keys(obj).forEach(key => {
//       const value = obj[key]
//       const newKey = prefix ? `${prefix}${separator}${key}` : key

//       if (value !== null && typeof value === 'object' && !Array.isArray(value) && !(value instanceof Date)) {
//         Object.assign(flattened, this.flatten(value, newKey, separator))
//       } else {
//         flattened[newKey] = value
//       }
//     })

//     return flattened
//   }

}
/**
 * Response interceptor function for HTTP client
 */
export function createResponseInterceptor() {
  return async (response: Response) => {
    // Add any global response transformations here
    return response
  }
}
/**
 * Error handler utilities
 */
export class ErrorHandler {
  /**
   * Handle validation errors
   */
  static handleValidationErrors(errors: Record<string, string[]>): string 
  {
    const messages = Object.values(errors).flat()
    return messages.join(', ')
  }
  /**
   * Handle network errors
   */
  static handleNetworkError(error: any): string 
  {
    if (error.name === 'TimeoutError') {
      return 'Request timed out. Please try again.'
    }

    if (error.message?.includes('fetch')) {
      return 'Network error. Please check your connection.'
    }

    return 'An unexpected error occurred.'
  }

  /**
   * Get user-friendly error message
   */
  static getUserFriendlyMessage(error: any): string {
    if (error.errors) {
      return this.handleValidationErrors(error.errors)
    }

    if (error.status) {
      switch (error.status) {
        case 400:
          return 'Invalid request. Please check your input.'
        case 401:
          return 'You need to log in to perform this action.'
        case 403:
          return 'You don\'t have permission to perform this action.'
        case 404:
          return 'The requested resource was not found.'
        case 422:
          return 'Please check your input and try again.'
        case 429:
          return 'Too many requests. Please try again later.'
        case 500:
          return 'Server error. Please try again later.'
        case 503:
          return 'Service temporarily unavailable.'
        default:
          return error.message || 'An unexpected error occurred.'
      }
    }

    return this.handleNetworkError(error)
  }
}
