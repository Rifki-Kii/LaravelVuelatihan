import api from '@/api/axios'

export const tasksApi = {
  list(params = {}) {
    return api.get('/tasks', { params })
  },
  get(id) {
    return api.get(`/tasks/${id}`)
  },
  create(data) {
    return api.post('/tasks', data)
  },
  update(id, data) {
    return api.put(`/tasks/${id}`, data)
  },
  delete(id) {
    return api.delete(`/tasks/${id}`)
  },
  changeStatus(id, status) {
    return api.patch(`/tasks/${id}/status`, { status })
  },
}
