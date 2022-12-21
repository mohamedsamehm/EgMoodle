import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'
import moment from 'moment'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useAssignments(props, emit) {
  // Use toast
  const toast = useToast()
  const refAssignmentTable = ref(null)
  const weeks= [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
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
  const tableColumnsGrades = [
    { label: 'Course ID', key: 'course_id' },
    { label: 'Name', key: 'title'},
    { label: 'Student Name', key: 'fullName' },
    { label: 'Email', key: 'email' },
    { label: 'Section', key: 'section_name' },
    { label: 'Last Edit', key: 'updated_at' },
    { label: 'File', key: 'path' },
    { label: 'Grade', key: 'grade' },
  ];
  const perPage = ref(10)
  const totalAssignments = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')

	const state = reactive({
		loading: true,
    assignments_grades: [],
		assignments: [],
		assignment: {
      course: {
        sections: []
      }
    },
    theAssignments: [],
    submittedAssignments: [],
    courses: [],
    canSubmit: false,
	})

  const dataMeta = computed(() => {
    const localItemsCount = refAssignmentTable.value ? refAssignmentTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalAssignments.value,
    }
  })
  const refetchData = () => {
		fetchAssignments()
  }
  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })
  const fetchCourses = () => {
    store.dispatch('assignments/fetchCourses')
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
  const fetchAssignments = (ctx, callback) => {
    let assignmentsObj = {};
    store.dispatch('assignments/fetchAssignments', {
      q: searchQuery.value,
      perPage: perPage.value,
      page: currentPage.value,
    })
    .then(res => {
      state.assignments = [];
      totalAssignments.value = res.data[0].total;
      res.data[0].data.forEach(element => {
        element['sections'] = [];
        element['sectionsObj'] = {};
        assignmentsObj[element.material_id] = [];
      });
      res.data[0].data.forEach(element => {
        assignmentsObj[element.material_id].push(element);
      });
      let i = 0;
      for (const [key, value] of Object.entries(assignmentsObj)) {
        i++;
        value.forEach(element => {
          element['id'] = element.material_id;
          element['number'] = i;
          value[0]['sections'].push(element.section_name);
          value[0]['sectionsObj'][element.section_id] = element.section_name;
        });
        value[0].sections = value[0].sections.toString();
        state.assignments.push(value[0]);
      }
    })
    .catch((err) => {
      toast({
        component: ToastificationContent,
        props: {
          title: 'Error fetching Assignments list',
          icon: 'AlertTriangleIcon',
          variant: 'danger',
        },
      })
    })
    .finally(() => {
      state.loading = false
    });
    return state;
  }

  const fetchMyAssignment = (params) => {
		state.loading = true;
    store.dispatch('assignments/fetchMyAssignment', params)
      .then(res => { 
				state.loading = false;
        state.theAssignments = res.data[0][0];
        if(moment(state.theAssignments.deadline).isAfter(moment().format())) {
          state.canSubmit = true;
        } else {
          state.canSubmit = false;
        }
        state.submittedAssignments = res.data[1][0];
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.theAssignments = undefined
        }
      })
	}
	const fetchAssignment = (params) => {
		state.loading = true;
    store.dispatch('assignments/fetchAssignment', params)
      .then(res => { 
				state.loading = false;
				state.assignment = res.data.assignment;
        state.assignment.course = [];
        state.courses.forEach(element => {
          if(element['course_id'] == state.assignment.course_id) {
            state.assignment.course = element;
          }
        });
        state.assignment.course.selectedSections = res.data.sections;
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.assignment = undefined
        }
      })
	}
  const fetchMarks = (params) => {
		state.loading = true;
    store.dispatch('assignments/fetchMarks', params)
    .then(res => { 
      state.loading = false;
      state.assignments_grades = res.data[0];
      console.log(res.data[0]);
    })
    .catch(error => {
      console.log('error ', error)
    })
	}

  return {
    weeks,
    fetchAssignments,
		fetchAssignment,
    fetchCourses,
    fetchMarks,
    fetchMyAssignment,
    tableColumns,
    tableColumnsGrades,
    perPage,
    currentPage,
    totalAssignments,
    dataMeta,
    perPageOptions,
    searchQuery,
    refAssignmentTable,
    refetchData,
		state,
  }
}
