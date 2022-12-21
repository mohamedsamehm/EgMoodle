export default [
  {
    title: 'Assignments',
    route: 'myassignments',
    icon: 'FileIcon',
    resource: 'SCL',
		action: 'read'
  },
  {
    title: 'Quizzes',
    route: 'myquizzes',
    icon: 'CodeIcon',
    resource: 'SCL',
		action: 'read'
  },
  {
    title: 'Attendance',
    icon: 'BarChart2Icon',
    children: [
      {
        title: 'Summary',
        route: 'attendance-summary',
        resource: 'SCL',
        action: 'read'
      },
      {
        title: 'Detailed',
        route: 'attendance-detailed',
        resource: 'SCL',
        action: 'read'
      },
    ],
  },
]
