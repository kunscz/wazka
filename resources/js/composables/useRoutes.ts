export function useRoutes() {
  const fetchRoutes = async (): Promise<string[]> => {
    const { data } = await window.axios.get('/api/routes')
    return data
  }

  return { fetchRoutes }
}