<template>
<div>
	<b-card>
    <template>
      <validation-observer ref="refFormObserver" #default="{ handleSubmit }">
				<b-form @submit.prevent="handleSubmit(onChange)">
          <b-row>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="Course">
                <label>Course</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="AttendanceData.course"
                    label="course_name"
                    value="course_id"
                    :options="state.courses"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <b-form-group class="mt-2">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" type="submit">
                  <b-spinner small v-if="loading"/> View
                </b-button>
              </b-form-group>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </template>
	</b-card>
  <b-card no-body v-if="viewTable">
    <b-card-body>
      <b-table class="position-relative" :items="studentsData" responsive primary-key="id" show-empty striped empty-text="No matching records found">
        <template #cell(status)="data">
          <span v-if="data.value == 1">
            <b-badge variant="success">
              Present
            </b-badge>
          </span>
          <span v-else>
            <b-badge variant="danger">
              Absent
            </b-badge>
          </span>
        </template>
        <template #cell(date_created)="data">
          {{data.value | formatNow}}
        </template>
      </b-table>
    </b-card-body>
  </b-card>
</div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { required } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import useAttendance from '../useAttendance';
import useAttendanceModule from '../useAttendanceModule';

import {
  BRow,
  BCol,		
  BCard,
  BCardBody,
  BCardFooter,
  BCardHeader,
  BCardTitle,
  BSidebar,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormTextarea,
  BButton,
  BSpinner,
  BFormFile,
  BTable,
  BDropdown,
  BDropdownItem,
  BBadge
} from 'bootstrap-vue';

export default {
  components: {
		BRow,
		BCol,		
		BCard,
    BCardBody,
    BCardFooter,
    BCardHeader,
    BCardTitle,
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BFormTextarea,
    BButton,
		BSpinner,
    BFormFile,
    vSelect,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
    BTable,
    BDropdown,
    BDropdownItem,
    BBadge
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'attendance'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useAttendanceModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const blankAttendanceData = {
      course: [],
      course_id: '',
    }
    const loading = ref(false)
    const viewTable = ref(false);
    const studentsData = ref([])
    const AttendanceData = ref(JSON.parse(JSON.stringify(blankAttendanceData)));
    const onChange = () => {
      AttendanceData.value.course_id = AttendanceData.value.course.course_id;
			loading.value = true;
      store.dispatch('attendance/fetchStudentAttendances', {course_id: AttendanceData.value.course_id}).then((res) => {
        console.log(res.data.meetings);
				loading.value = false;
				if(res.data.status == 200) {
          studentsData.value = res.data.meetings;
          viewTable.value = true;
				} else if(res.data.status == 401) {
          console.log(res.data.status);
				}
      })
    }

    const { refFormObserver, getValidationState } = formValidation()

    const {
			state,
      fetchStudentCourses,
    } = useAttendance(props, { emit })
    
		fetchStudentCourses();
    
    return {
      refFormObserver,
      getValidationState,
			onChange,
			state,
      viewTable,
      fetchStudentCourses,
			loading,
      AttendanceData,
      studentsData,
    }
  },
  data() {
    return {
      required,
    }
  },
}
</script>
<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-sweetalert.scss';
</style>
