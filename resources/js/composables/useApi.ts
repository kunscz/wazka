import { api } from '@/lib/api'

export function useApi() {
  const get = async (url: string, params?: Record<string, any>) => {
    const response = await api.get(url, { params })
    return response
  }

  const post = async (url: string, data: any) => {
    const response = await api.post(url, data)
    return response
  }

  const put = async (url: string, data: any) => {
    const response = await api.put(url, data)
    return response
  }

  const deleteRequest = async (url: string) => {
    const response = await api.delete(url)
    return response
  }

  return {
    get,
    post,
    put,
    delete: deleteRequest,
  }
}