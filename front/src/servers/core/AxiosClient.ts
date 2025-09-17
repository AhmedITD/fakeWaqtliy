import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosResponse } from 'axios'
import { API_CONFIG, HTTP_STATUS, ERROR_MESSAGES } from '../config/api'
import type { ApiResponse, ApiException, RequestOptions } from '../../types/api'
/**
 * Axios-based HTTP client with interceptors and error handling
 */
export class AxiosClient 
{
  private client: AxiosInstance

  constructor(config = API_CONFIG) {
    this.client = axios.create({
      baseURL: config.baseURL,
      timeout: config.timeout,
      headers: config.headers,
      withCredentials: config.withCredentials
    })
  }

  /**
   * GET request
   */
  async get<T = any>(url: string, options?: RequestOptions): Promise<ApiResponse<T>> 
  {
    try {
      // const config: AxiosRequestConfig = {
      //   params: options?.params,
      //   headers: options?.headers,
      //   timeout: options?.timeout,
      //   signal: options?.signal
      // }

      const response = await this.client.get<T>(url)
      return this.transformResponse<T>(response)
    } catch (error) {
      throw this.transformError(error)
    }
  }
  /**
   * Transform Axios response to ApiResponse format
   */
  private transformResponse<T>(response: AxiosResponse<any>): ApiResponse<T> 
  {
    const data = response.data
    // Handle Laravel API response format
    if (data && typeof data === 'object' && data.data !== undefined) {
      return {
        data: data.data,
        message: data.message,
        status: response.status,
      }
    }
    // Handle direct data response
    return {
      data: data,
      status: response.status,
      message: data.message
    }
  }
  /**
   * Transform Axios error to ApiException
   */
  private transformError(error: any): ApiException 
  {
    if (error.response) {
      // Server responded with error status
      const data = error.response.data
      return {
        message: data?.message || this.getErrorMessage(error.response.status),
        status: error.response.status,
        code: data?.code,
        errors: data?.errors,
        details: data
      } as ApiException
    }
    if (error.request) {
      // Request was made but no response received
      return {
        message: ERROR_MESSAGES.NETWORK_ERROR,
        status: 0
      } as ApiException
    }
    if (error.code === 'ECONNABORTED') {
      // Request timeout
      return {
        message: ERROR_MESSAGES.TIMEOUT_ERROR,
        status: 408
      } as ApiException
    }
    // Something else happened
    return {
      message: error.message || ERROR_MESSAGES.UNKNOWN_ERROR,
      status: 0,
      details: error
    } as ApiException
  }
  /**
   * Get error message for HTTP status code
   */
  private getErrorMessage(status: number): string 
  {
    switch (status) {
      case HTTP_STATUS.UNAUTHORIZED:
        return ERROR_MESSAGES.UNAUTHORIZED
      case HTTP_STATUS.FORBIDDEN:
        return ERROR_MESSAGES.FORBIDDEN
      case HTTP_STATUS.NOT_FOUND:
        return ERROR_MESSAGES.NOT_FOUND
      case HTTP_STATUS.UNPROCESSABLE_ENTITY:
        return ERROR_MESSAGES.VALIDATION_ERROR
      case HTTP_STATUS.TOO_MANY_REQUESTS:
        return 'Too many requests. Please try again later.'
      case HTTP_STATUS.INTERNAL_SERVER_ERROR:
        return ERROR_MESSAGES.SERVER_ERROR
      case HTTP_STATUS.SERVICE_UNAVAILABLE:
        return 'Service temporarily unavailable.'
      default:
        return ERROR_MESSAGES.UNKNOWN_ERROR
    }
  }
  /**
   * Get the underlying Axios instance
   */
  getAxiosInstance(): AxiosInstance 
  {
    return this.client
  }
}

// Create and export default client instance
export const axiosClient = new AxiosClient()
