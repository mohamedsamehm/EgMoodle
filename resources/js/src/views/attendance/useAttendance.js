import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'

export default function useAttendance(props, emit) {
  // Table Handlers
  const tableColumns = [
    { label: '#', align: 'start', sortable: false, key: 'number',},
    { label: 'Title', key: 'title'},
    { label: 'Course', key: 'course_name' },
    { label: 'Sections', key: 'sections' },
    { label: 'Due Date', key: 'deadline' },
    { label: 'Opened', key: 'createdDate' },
    { label: 'Week', key: 'week' },
    { label: 'File', key: 'file_name' },
    { label: 'Actions', key: 'actions' },
  ];

	const state = reactive({
    courses: [],
    attendances: [],
	})

  const fetchStudentCourses = () => {
    store.dispatch('attendance/fetchStudentCourses')
    .then(res => {
      state.courses = res.data[0];
    })
    .catch((err) => {
      console.log(err);
    })
  }
  const fetchCourses = () => {
    store.dispatch('attendance/fetchCourses')
    .then(res => {
      let coursesObj= {};
      for (const [key, value] of Object.entries(res.data[0])) {
        value['sections'] = [];
        coursesObj[value.course_id] = [];
      }
      for (const [key, value] of Object.entries(res.data[0])) {
        coursesObj[value.course_id].push(value);
      }
      for (const [key, value] of Object.entries(coursesObj)) {
        value.forEach(element => {
          let obj = {};
          obj['section_id'] = element.section_id;
          obj['section_name'] = element.section_name;
          value[0]['sections'].push(obj);
        });
        state.courses.push(value[0]);
      }
    })
    .catch((err) => {
      console.log(err);
    })
    .finally(() => {
      state.loading = false
    });
  }
  const fetchMyAttendances = () => {
    store.dispatch('attendance/fetchMyAttendances')
    .then(res => {
      state.attendances = res.data.meetings;
    })
    .catch((err) => {
      console.log(err);
    })
  }
  
  return {
    fetchCourses,
    fetchStudentCourses,
    fetchMyAttendances,
    tableColumns,
		state,
  }
}
