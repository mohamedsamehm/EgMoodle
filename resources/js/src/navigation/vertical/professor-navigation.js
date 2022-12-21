export default [
  {
    title: 'Assignment',
    icon: 'FileIcon',
    children: [
      {
        title: 'View',
        route: 'assignments',
        resource: 'PECL',
        action: 'edit'
      },
      {
        title: 'Create',
        route: 'assignment-create',
        resource: 'PECL',
        action: 'edit'
      },
    ],
  },
  {
    title: 'Meetings',
    icon: 'PlayIcon',
    children: [
      {
        title: 'View',
        route: 'meetings',
        resource: 'PECL',
        action: 'edit'
      },
      {
        title: 'Create',
        route: 'meeting-create',
        resource: 'PECL',
        action: 'edit'
      },
    ],
  },
  {
    title: 'Attendances',
    icon: 'BarChart2Icon',
    route: 'attendance',
    resource: 'PECL',
		action: 'edit'
  },
  {
    title: 'Quiz',
    icon: 'CodeIcon',
    children: [
      {
        title: 'View',
        route: 'quizzes',
        resource: 'PECL',
        action: 'edit'
      },
      {
        title: 'Create',
        route: 'quiz-create',
        resource: 'PECL',
        action: 'edit'
      },
    ],
  },
]
