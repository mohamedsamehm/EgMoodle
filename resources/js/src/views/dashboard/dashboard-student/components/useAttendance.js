import { ref, toRefs, reactive, watch, computed } from '@vue/composition-api'
import store from '@/store'

export default function useDashboard(props, emit) {
	const state = reactive({
		loading: true,
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
    fetchMyAttendances,
    fetchStudentCourses,
		state,
  }
}
