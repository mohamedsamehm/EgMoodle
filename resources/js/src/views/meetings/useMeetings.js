import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'
import moment from 'moment'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useMeetings(props, emit) {
  // Use toast
  const toast = useToast()
  const refMeetingsTable = ref(null)
  const weeks= [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
  // Table Handlers
  const tableColumns = [
    { label: '#', align: 'start', sortable: false, key: 'number',},
    { label: 'Title', key: 'title'},
    { label: 'Course', key: 'course_name' },
    { label: 'Sections', key: 'sections' },
    { label: 'Week', key: 'week' },
    { label: 'URL', key: 'teams_url' },
    { label: 'Actions', key: 'actions' },
  ];
  const perPage = ref(10)
  const totalMeetings = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')

	const state = reactive({
		loading: true,
		meetings: [],
		meeting: {
      course: {
        sections: []
      }
    },
    courses: [],
    students: [],
    studentsforMeeting: []
	})

  const dataMeta = computed(() => {
    const localItemsCount = refMeetingsTable.value ? refMeetingsTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalMeetings.value,
    }
  })
  const refetchData = () => {
		fetchMeetings()
  }
  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })
  const fetchCourses = () => {
    store.dispatch('meetings/fetchCourses')
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
  const fetchMeetings = (ctx, callback) => {
    let meetingsObj = {};
    store.dispatch('meetings/fetchMeetings', {
      q: searchQuery.value,
      perPage: perPage.value,
      page: currentPage.value,
    })
    .then(res => {
      state.meetings = [];
      totalMeetings.value = res.data[0].total;
      res.data[0].data.forEach(element => {
        element['sections'] = [];
        element['sectionsObj'] = {};
        meetingsObj[element.material_id] = [];
      });
      res.data[0].data.forEach(element => {
        meetingsObj[element.material_id].push(element);
      });
      let i = 0;
      for (const [key, value] of Object.entries(meetingsObj)) {
        i++;
        value.forEach(element => {
          element['id'] = element.material_id;
          element['number'] = i;
          value[0]['sections'].push(element.section_name);
          value[0]['sectionsObj'][element.section_id] = element.section_name;
        });
        value[0].sections = value[0].sections.toString();
        state.meetings.push(value[0]);
      }
    })
    .catch((err) => {
      toast({
        component: ToastificationContent,
        props: {
          title: 'Error fetching Meetings list',
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
	const fetchMeeting = (params) => {
		state.loading = true;
    store.dispatch('meetings/fetchMeeting', params)
      .then(res => { 
				state.loading = false;
				state.meeting = res.data.meeting;
        state.meeting.course = [];
        state.courses.forEach(element => {
          if(element['course_id'] == state.meeting.course_id) {
            state.meeting.course = element;
          }
        });
        state.meeting.course.selectedSections = res.data.sections;
			})
      .catch(error => {
				console.log('error ', error)
        if (error.response.status === 404) {
          state.meeting = undefined
        }
      })
	}
  const fetchStudents = (params) => {
    store.dispatch('meetings/fetchStudents', params)
    .then(res => {
      state.students = res.data.students;
    })
    .catch(error => {
      console.log('error ', error)
      if (error.response.status === 404) {
        state.meeting = undefined
      }
    })
	}
  const fetchStudentsForMeetings = (params) => {
    store.dispatch('meetings/fetchStudentsForMeetings', params)
    .then(res => {
      state.studentsforMeeting = res.data.students;
      let i = 0;
      for (const [key, value] of Object.entries(state.studentsforMeeting)) {
        i++;
        value['number'] = i;
      }
    })
    .catch(error => {
      console.log('error ', error)
      if (error.response.status === 404) {
        state.meeting = undefined
      }
    })
    .finally(() => {
      state.loading = false
    });
	}

  return {
    weeks,
    fetchMeetings,
		fetchMeeting,
    fetchCourses,
    fetchStudents,
    fetchStudentsForMeetings,
    tableColumns,
    perPage,
    currentPage,
    totalMeetings,
    dataMeta,
    perPageOptions,
    searchQuery,
    refMeetingsTable,
    refetchData,
		state,
  }
}