import axios from 'axios';
var coursesOBJ = [],
obj = [],
api = '';
if(localStorage.getItem('userData')) {
  if(JSON.parse(localStorage.getItem('userData')).role.slug == 'professor') {
    api = '/api/lecture';
  } else if(JSON.parse(localStorage.getItem('userData')).role.slug == 'student')  {
    api = '/api/enroll';
  }
  try {
    axios.get(api)
    .then(res => {
      if(JSON.parse(localStorage.getItem('userData')).role.slug == 'professor') {
        for (const [key, value] of Object.entries(res.data[0])) {
          coursesOBJ[value.course_id] = [];
        }
        for (const [key, value] of Object.entries(res.data[0])) {
          coursesOBJ[value.course_id].push(value);
        }
        for (const [key, value] of Object.entries(coursesOBJ)) {
          coursesOBJ.push(value[0]);
        }
        for (let index = 0; index < coursesOBJ.length; index++) {
          let el = coursesOBJ[index];
          let OBJ = {
            title: el['course_name'],
            href: '/course/' + el['course_id'],
            resource: 'SPECL',
            action: 'manage'
          };
          obj.push(OBJ);
        }
      } else if(JSON.parse(localStorage.getItem('userData')).role.slug == 'student')  {
        res.data[0].forEach(element => {
          let OBJ = {}
          OBJ['id'] = element.course_id;
          OBJ['name'] = element.course_name;
          coursesOBJ.push(OBJ);
        });
        for (let index = 0; index < coursesOBJ.length; index++) {
          let el = coursesOBJ[index];
          let OBJ = {
            title: el['name'],
            href: '/course/' + el['id'],
            resource: 'SPECL',
            action: 'manage'
          };
          obj.push(OBJ);
        }
      }
    }).catch(err => {
      console.log(err)
    });
  } catch (error) {
    console.log(error);
  }
}
export default [
  {
    title: 'Dashboard',
    route: 'dashboard',
    icon: 'HomeIcon',
    resource: 'Auth',
		action: 'view'
  },
  {
    title: 'Courses',
    icon: 'BookOpenIcon',
    children: obj,
  },
]