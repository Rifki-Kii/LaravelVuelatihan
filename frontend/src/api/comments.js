import api from '@/api/axios'

export const commentsApi = {
  listByTask(taskId) {
    return api.get(`/tasks/${taskId}/comments`)
  },
  create(data) {
    return api.post('/comments', data)
  },
  delete(id) {
    return api.delete(`/comments/${id}`)
  },
}
