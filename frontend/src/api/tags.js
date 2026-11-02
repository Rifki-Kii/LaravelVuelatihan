import api from '@/api/axios'

export const tagsApi = {
  list() {
    return api.get('/tags')
  },
  create(data) {
    return api.post('/tags', data)
  },
}
