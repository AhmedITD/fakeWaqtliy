import type { RouteRecordRaw } from 'vue-router'

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
      },
      {
        path: '/show/:id',
        component: () => import('@/pages/orgs/show.vue'),
        props: true,
        name: 'show',
      },
    ],
  },
]

export default routes