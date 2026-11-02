import { defineStore } from 'pinia'

export const useProjectsStore = defineStore('projects', {
  state: () => ({
    projects: [],
    selected: null,
  }),

  getters: {
    getProjects: (state) => state.projects,
  },

  actions: {
    setProjects(projects) {
      this.projects = projects
    },
    setSelected(project) {
      this.selected = project
    },
    addProject(project) {
      this.projects.push(project)
    },
    updateProject(updated) {
      const idx = this.projects.findIndex((p) => p.id === updated.id)
      if (idx !== -1) this.projects[idx] = updated
    },
    removeProject(id) {
      this.projects = this.projects.filter((p) => p.id !== id)
    },
  },
})
