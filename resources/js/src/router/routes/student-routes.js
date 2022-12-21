export default [
  {
    path: '/myassignments',
    name: 'myassignments',
    component: () => import('@/views/assignments/student/Assignments.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      pageTitle: 'Assignments',
      breadcrumb: [
        {
          text: 'Assignments',
          active: true,
        },
      ],
    },
  },
  {
    path: '/myassignments/submit/:id',
    name: 'assignment-submit',
    component: () => import('@/views/assignments/student/assignment-submit/AssignmentSubmit.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      breadcrumb: [
        {
          text: 'Assignments',
					to: '/myassignments'
        },
        {
          text: 'Submit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/myquizzes',
    name: 'myquizzes',
    component: () => import('@/views/quizzes/student/Quizzes.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      pageTitle: 'Quizzes',
      breadcrumb: [
        {
          text: 'Quizzes',
          active: true,
        },
      ],
    },
  },
  {
    path: '/myquizzes/submit/:id',
    name: 'quiz-submit',
    component: () => import('@/views/quizzes/student/quiz-submit/QuizSubmit.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      breadcrumb: [
        {
          text: 'Quizzes',
					to: '/myquizzes'
        },
        {
          text: 'Attempt',
          active: true,
        },
      ],
    },
  },
  {
    path: '/myquizzes/results/:id',
    name: 'quiz-results',
    component: () => import('@/views/quizzes/student/quiz-results/QuizResults.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      breadcrumb: [
        {
          text: 'Quizzes',
					to: '/myquizzes'
        },
        {
          text: 'Results',
          active: true,
        },
      ],
    },
  },
  {
    path: '/attendance/summary',
    name: 'attendance-summary',
    component: () => import('@/views/attendance/student/AttendanceSummary.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      breadcrumb: [
        {
          text: 'Attendances',
					to: '/attendance/summary'
        },
        {
          text: 'Summary',
          active: true,
        },
      ],
    },
  },
  {
    path: '/attendance/detailed',
    name: 'attendance-detailed',
    component: () => import('@/views/attendance/student/AttendanceDetailed.vue'),
		meta: {
			resource: 'SCL',
			action: 'read',
      breadcrumb: [
        {
          text: 'Attendances',
					to: '/attendance/detailed'
        },
        {
          text: 'Detailed',
          active: true,
        },
      ],
    },
  },
]