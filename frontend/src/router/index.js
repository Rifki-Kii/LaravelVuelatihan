import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import LoginView from '@/pages/auth/Login.vue'
import RegisterView from '@/pages/auth/Register.vue'
import DashboardView from '@/pages/dashboard/Index.vue'
import ProjectIndex from '@/pages/projects/Index.vue'
import ProjectCreate from '@/pages/projects/Create.vue'
import ProjectEdit from '@/pages/projects/Edit.vue'
import ProjectShow from '@/pages/projects/Show.vue'
import TaskIndex from '@/pages/tasks/Index.vue'
import TaskCreate from '@/pages/tasks/Create.vue'
import TaskEdit from '@/pages/tasks/Edit.vue'
import TaskShow from '@/pages/tasks/Show.vue'

const routes = [
  { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
  { path: '/register', name: 'register', component: RegisterView, meta: { guest: true } },
  { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/projects', name: 'projects.index', component: ProjectIndex, meta: { requiresAuth: true } },
  { path: '/projects/create', name: 'projects.create', component: ProjectCreate, meta: { requiresAuth: true } },
  { path: '/projects/edit/:id', name: 'projects.edit', component: ProjectEdit, meta: { requiresAuth: true } },
  { path: '/projects/show/:id', name: 'projects.show', component: ProjectShow, meta: { requiresAuth: true } },
  { path: '/tasks', name: 'tasks.index', component: TaskIndex, meta: { requiresAuth: true } },
  { path: '/tasks/create', name: 'tasks.create', component: TaskCreate, meta: { requiresAuth: true } },
  { path: '/tasks/edit/:id', name: 'tasks.edit', component: TaskEdit, meta: { requiresAuth: true } },
  { path: '/tasks/show/:id', name: 'tasks.show', component: TaskShow, meta: { requiresAuth: true } },
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

let authChecked = false

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  if (!authChecked) {
    authChecked = true
    try {
      await auth.check()
    } catch (e) {
      auth.user = null
      auth.isAuthenticated = false
    }
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  if (to.meta.guest && auth.isAuthenticated) {
    return next({ name: 'dashboard' })
  }

  return next()
})

export default router
