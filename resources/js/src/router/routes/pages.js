export default [
  {
    path: '/',
    name: 'dashboard',
    component: () => import('@/views/dashboard/Dashboard.vue'),
    meta: {
      resource: 'Auth',
      action: 'view',
    },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/pages/authentication/Login.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: () => import('@/views/pages/authentication/ForgotPassword.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'view',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/coming-soon',
    name: 'coming-soon',
    component: () => import('@/views/pages/miscellaneous/ComingSoon.vue'),
    meta: {
      layout: 'full',
    },
  },
  {
    path: '/apps/chat',
    name: 'apps-chat',
    component: () => import('@/views/pages/apps/chat/Chat.vue'),
    meta: {
      contentRenderer: 'sidebar-left',
      contentClass: 'chat-application',
      resource: 'Auth',
      action: 'view',
    },
  },
  {
    path: '/not-authorized',
    name: 'not-authorized',
    component: () => import('@/views/pages/miscellaneous/NotAuthorized.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'view',
    },
  },
  {
    path: '/under-maintenance',
    name: 'under-maintenance',
    component: () => import('@/views/pages/miscellaneous/UnderMaintenance.vue'),
    meta: {
      layout: 'full',
    },
  },
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/pages/error/Error404.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'view',
    },
  },
  {
    path: '/account-setting',
    name: 'account-setting',
    component: () => import('@/views/pages/account-setting/AccountSetting.vue'),
    meta: {
      pageTitle: 'Profile',
      resource: 'Auth',
      action: 'view',
      breadcrumb: [
        {
          text: 'Profile',
          active: true,
        },
      ],
    },
  },
  {
    path: '/course/:id',
    name: 'course',
    component: () => import('@/views/course/Course.vue'),
    meta: {
      resource: 'SPECL',
      action: 'manage',
    },
  }
];