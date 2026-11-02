import api from '@/api/axios'

export const projectsApi = {
  list() {
    return api.get('/projects')
  },
  get(id) {
    return api.get(`/projects/${id}`)
  },
  create(data) {
    return api.post('/projects', data)
  },
  update(id, data) {
    return api.put(`/projects/${id}`, data)
  },
  delete(id) {
    return api.delete(`/projects/${id}`)
  },
}
