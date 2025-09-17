import type { ApiRequestConfig } from '../../types/api'
// API Configuration
export const API_CONFIG: ApiRequestConfig = {
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1',
  timeout: 30000, // 30 seconds
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
}
// API Endpoints - Simplified CRUD (findAll, findById)
export const API_ENDPOINTS = {
  organizations: '/organizations',
} as const
// Cache Configuration
export const CACHE_CONFIG = {
  // Default TTL in milliseconds
  DEFAULT_TTL: 5 * 60 * 1000, // 5 minutes
  // Specific cache configurations for simplified CRUD
  organizations: {
    findAll: 10 * 60 * 1000, // 10 minutes
    findById: 30 * 60 * 1000 // 30 minutes
  }
}
// Request Configuration
export const REQUEST_CONFIG = {
  // Retry configuration
  retry: {
    attempts: 3,
    delay: 1000, // 1 second
    backoff: 2 // Exponential backoff multiplier
  },
  // Timeout configurations for different request types
  timeouts: {
    fast: 5000, // 5 seconds
    normal: 15000, // 15 seconds
    slow: 30000, // 30 seconds
    upload: 60000 // 1 minute
  },
  // Rate limiting
  rateLimit: {
    requests: 100,
    window: 60 * 1000 // 1 minute
  }
}
// Environment-specific configurations
export const ENV_CONFIG = {
  development: {
    baseURL: 'http://localhost:8000/api/v1',
    timeout: 30000,
    debug: true
  },
  staging: {
    baseURL: 'https://staging-api.wagtly.com/api/v1',
    timeout: 20000,
    debug: false
  },
  production: {
    baseURL: 'https://wagtly.com/api/v1',
    timeout: 15000,
    debug: false
  }
}
// Get environment-specific configuration
export function getEnvConfig() {
  const env = import.meta.env.MODE || 'development'
  return ENV_CONFIG[env as keyof typeof ENV_CONFIG] || ENV_CONFIG.development
}
// HTTP Status Codes
export const HTTP_STATUS = {
  OK: 200,
  CREATED: 201,
  NO_CONTENT: 204,
  BAD_REQUEST: 400,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  UNPROCESSABLE_ENTITY: 422,
  TOO_MANY_REQUESTS: 429,
  INTERNAL_SERVER_ERROR: 500,
  SERVICE_UNAVAILABLE: 503
} as const
// Error Messages
export const ERROR_MESSAGES = {
  NETWORK_ERROR: 'Network error. Please check your connection.',
  TIMEOUT_ERROR: 'Request timed out. Please try again.',
  UNAUTHORIZED: 'You are not authorized to perform this action.',
  FORBIDDEN: 'Access denied.',
  NOT_FOUND: 'The requested resource was not found.',
  VALIDATION_ERROR: 'Please check your input and try again.',
  SERVER_ERROR: 'Server error. Please try again later.',
  UNKNOWN_ERROR: 'An unexpected error occurred.'
} as const
