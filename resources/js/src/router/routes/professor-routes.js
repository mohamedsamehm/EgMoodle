export default [
  // {
  //   path: '/',
  //   name: 'dashboard',
  //   component: () => import('@/views/dashboard/Dashboard.vue'),
	// 	meta: {
	// 		resource: 'PCL',
	// 		action: 'read'
  //   },
  // },
  {
    path: '/meetings',
    name: 'meetings',
    component: () => import('@/views/meetings/teacher/Meetings.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Meetings',
          active: true,
        },
      ],
    },
  },
  {
    path: '/meetings/create',
    name: 'meeting-create',
    component: () => import('@/views/meetings/teacher/meetings-add/MeetingsAdd.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Meetings',
					to: '/meetings'
        },
        {
          text: 'Add',
          active: true,
        },
      ],
    },
  },
  {
    path: '/meetings/edit/:id',
    name: 'meeting-edit',
    component: () => import('@/views/meetings/teacher/meetings-edit/MeetingsEdit.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Meetings',
					to: '/meetings'
        },
        {
          text: 'Edit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/meetings/upload/:id',
    name: 'upload-attendance',
    component: () => import('@/views/meetings/teacher/upload-attendance/UploadAttendance.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Meetings',
					to: '/meetings'
        },
        {
          text: 'Upload Attendance',
          active: true,
        },
      ],
    },
  },
  {
    path: '/meetings/view/:id',
    name: 'view-attendance',
    component: () => import('@/views/meetings/teacher/view-attendance/ViewAttendance.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Meetings',
					to: '/meetings'
        },
        {
          text: 'View',
          active: true,
        },
      ],
    },
  },
  {
    path: '/attendance/',
    name: 'attendance',
    component: () => import('@/views/attendance/teacher/Attendance.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Attendances',
          active: true,
        },
      ],
    },
  },
  {
    path: '/assignments',
    name: 'assignments',
    component: () => import('@/views/assignments/teacher/Assignments.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Assignments',
          active: true,
        },
      ],
    },
  },
  {
    path: '/assignments/create',
    name: 'assignment-create',
    component: () => import('@/views/assignments/teacher/assignments-add/AssignmentAdd.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Assignments',
					to: '/assignments'
        },
        {
          text: 'Add',
          active: true,
        },
      ],
    },
  },
  {
    path: '/assignments/edit/:id',
    name: 'assignment-edit',
    component: () => import('@/views/assignments/teacher/assignments-edit/AssignmentEdit.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Assignments',
					to: '/assignments'
        },
        {
          text: 'Edit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/assignments/mark/:id',
    name: 'assignment-mark',
    component: () => import('@/views/assignments/teacher/assignments-mark/AssignmentMark.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      pageTitle: 'Mark Assignments',
      breadcrumb: [
        {
          text: 'Assignments',
					to: '/assignments'
        },
        {
          text: 'Mark',
          active: true,
        },
      ],
    },
  },
  {
    path: '/quizzes',
    name: 'quizzes',
    component: () => import('@/views/quizzes/teacher/Quizzes.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Quizzes',
          active: true,
        },
      ],
    },
  },
  {
    path: '/quizzes/create',
    name: 'quiz-create',
    component: () => import('@/views/quizzes/teacher/quiz-add/QuizAdd.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Quizzes',
					to: '/quizzes'
        },
        {
          text: 'Add',
          active: true,
        },
      ],
    },
  },
  {
    path: '/quizzes/edit/:id',
    name: 'quiz-edit',
    component: () => import('@/views/quizzes/teacher/quiz-edit/QuizEdit.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      breadcrumb: [
        {
          text: 'Quizzes',
					to: '/quizzes'
        },
        {
          text: 'Edit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/quizzes/grades/:id',
    name: 'quiz-grades',
    component: () => import('@/views/quizzes/teacher/quiz-grades/QuizGrades.vue'),
		meta: {
			resource: 'PECL',
			action: 'edit',
      pageTitle: 'Mark quizzes',
      breadcrumb: [
        {
          text: 'Quizzes',
					to: '/quizzes'
        },
        {
          text: 'Students grades',
          active: true,
        },
      ],
    },
  },
]
