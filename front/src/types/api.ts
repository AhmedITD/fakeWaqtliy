// API Response Types
export interface ApiResponse<T> {
  data: T;
  status: number;
  message?: string;
}

// Request Types
export interface ApiRequestConfig {
  baseURL?: string
  timeout?: number
  headers?: Record<string, string>
  withCredentials?: boolean
  params?: Record<string, any>
}

export interface RequestOptions {
  params?: Record<string, any>
  headers?: Record<string, string>
  timeout?: number
  signal?: AbortSignal
}

// HTTP Methods
export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'

// Error Types
export interface ApiError {
  message: string
  status: number
  code?: string
  errors?: Record<string, string[]>
  details?: any
}

export class ApiException extends Error {
  public readonly status: number
  public readonly code?: string
  public readonly errors?: Record<string, string[]>
  public readonly details?: any

  constructor(error: ApiError) {
    super(error.message)
    this.name = 'ApiException'
    this.status = error.status
    this.code = error.code
    this.errors = error.errors
    this.details = error.details
  }
}

// Authentication Types
export interface AuthTokens {
  access_token: string
  refresh_token?: string
  token_type: string
  expires_in?: number
}

export interface User {
  id: string | number
  name: string
  email: string
  roles?: string[]
  permissions?: string[]
  avatar?: string
  created_at?: string
  updated_at?: string
}

export interface LoginCredentials {
  email: string
  password: string
  remember_me?: boolean
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

// Neighborhood/Location Types (based on your existing data)
export interface Organization {
  id: string | number
  name: string
  geometry: {
    type: 'Point' | 'Polygon'
    coordinates: [number, number] | [number, number][]
  }
  properties?: {
    description?: string
    population?: number
    area?: number
    [key: string]: any
  }
  created_at?: string
  updated_at?: string
}

export interface CreateOrganizationData {
  name: string
  coordinates: [number, number]
  description?: string
  properties?: Record<string, any>
}

export interface UpdateOrganizationData extends Partial<CreateOrganizationData> {
  id: string | number
}

// Filter and Search Types
export interface SearchParams {
  query?: string
  page?: number
  per_page?: number
  sort_by?: string
  sort_order?: 'asc' | 'desc'
  filters?: Record<string, any>
}



// Cache Types
export interface CacheConfig {
  ttl?: number // Time to live in milliseconds
  key?: string
  invalidateOn?: string[]
}

// Upload Types
export interface UploadResponse {
  url: string
  path: string
  filename: string
  size: number
  mime_type: string
}

// Webhook Types
export interface WebhookPayload {
  event: string
  data: any
  timestamp: string
  signature?: string
}
