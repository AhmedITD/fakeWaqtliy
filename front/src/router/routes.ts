import type { RouteRecordRaw } from 'vue-router'
import { validateOrganizationId } from './guards'
const routes: RouteRecordRaw[] = [
  {
    path: '/',
    children: [
      {
        path: '/create',
        component: () => import('@/pages/orgs/create.vue'),
        name: 'create',
      },
      {
        path: '/edit/:id',
        component: () => import('@/pages/orgs/edit.vue'),
        props: true,
        name: 'edit',
        beforeEnter: validateOrganizationId
      },
      {
        path: '/:id',
        component: () => import('@/pages/orgs/show.vue'),
        props: true,
        name: 'show',
        beforeEnter: validateOrganizationId
      },
      {
        path: '/notFound',
        component: () => import('@/pages/orgs/NotFound.vue'),
        name: 'NotFound',
      },
    ],
  },
]
export default routes