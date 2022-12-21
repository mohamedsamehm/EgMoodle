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
              <validation-provider #default="validationContext" rules="required" name="Sections">
                <label>Sections</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="AttendanceData.sectionsObj"
                    :options="AttendanceData.course.sections"
                    label="section_name"
                    multiple
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <!-- submit button -->
          <b-row>
            <b-col cols="12" md="4">
              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" type="submit">
                <b-spinner small v-if="loading"/> View
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </template>
	</b-card>
  <b-card no-body v-if="viewChart">
    <b-card-header>
      <b-card-title>Attendance in <strong class="text-primary">{{AttendanceData.course.course_name}}</strong></b-card-title>
    </b-card-header>

    <!-- chart -->
    <b-card-body>
      <chartjs-component-bar-chart
        :height="400"
        :data="chartOptions.data"
        :options="chartOptions.options"
      />
    </b-card-body>
  </b-card>
  <b-card no-body v-if="viewChart">
    <b-card-body>
      <b-table class="position-relative" :items="studentsData" :fields="tableColumns" responsive primary-key="id" show-empty striped empty-text="No matching records found" />
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
import ChartjsComponentBarChart from '../componenets/ChartjsComponentBarChart.vue'
import useAttendance from '../useAttendance';
import useAttendanceModule from '../useAttendanceModule';
import { $themeColors } from '@themeConfig'

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
    ChartjsComponentBarChart,
    BTable,
    BDropdown,
    BDropdownItem,
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
      sectionsObj: [],
      course_id: '',
      sections: [],
    }
    const studentsData = ref([])
    const viewChart = ref(false);
    const chartOptions = ref({});
    chartOptions.value = {
      data: {
        labels: [],
        datasets: [
          {
            label: 'Present',
            data: [],
            backgroundColor: '#836AF9',
            borderColor: 'transparent',
          },
          {
            label: 'Absent',
            data: [],
            backgroundColor: $themeColors.danger,
            borderColor: 'transparent',
          },
        ],
      },
      options: {
        elements: {
          rectangle: {
            borderWidth: 2,
            borderSkipped: 'bottom',
          },
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
          display: true,
        },
        tooltips: {
          // Updated default tooltip UI
          shadowOffsetX: 1,
          shadowOffsetY: 1,
          shadowBlur: 8,
          shadowColor: 'rgba(0, 0, 0, 0.25)',
          backgroundColor: $themeColors.light,
          titleFontColor: $themeColors.dark,
          bodyFontColor: $themeColors.dark,
        },
        scales: {
          xAxes: [
            {
              display: true,
              gridLines: {
                display: true,
                color: 'rgba(200, 200, 200, 0.2)',
                zeroLineColor: 'rgba(200, 200, 200, 0.2)',
              },
              scaleLabel: {
                display: false,
              },
              ticks: {
                fontColor: '#6e6b7b',
              },
            },
          ],
          yAxes: [
            {
              display: true,
              gridLines: {
                color: 'rgba(200, 200, 200, 0.2)',
                zeroLineColor: 'rgba(200, 200, 200, 0.2)',
              },
              ticks: {
                stepSize: 10,
                min: 0,
                max: 120,
                fontColor: '#6e6b7b',
              },
            },
          ],
        },
      },
    };
    const loading = ref(false)
    const tableColumns = [
      { label: '#', align: 'start', sortable: false, key: 'id',},
      { label: 'Name', key: 'fullName'},
      { label: 'Email', key: 'email' },
      { label: 'Attended Percentage', key: 'attended_percentage' },
    ];
    const AttendanceData = ref(JSON.parse(JSON.stringify(blankAttendanceData)));
    const onChange = () => {
      AttendanceData.value.sectionsObj.forEach(element => {
        AttendanceData.value.sections.push(element.section_id);
      });
      AttendanceData.value.course_id = AttendanceData.value.course.course_id;
			loading.value = true;
      store.dispatch('attendance/fetchAttendances', {course_id: AttendanceData.value.course_id}).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
          let total = Object.keys(res.data.meetings).length,
          i = 0;
          for (const [key, value] of Object.entries(res.data.meetings)) {
            let presents = 0,
                absents = 0;
            chartOptions.value.data.labels.push(value[0].title);
            value.forEach(element => {
              i++;
              const index = studentsData.value.findIndex(object => {
                return object.email === element['email'];
              });
              if(index == -1) {
                studentsData.value.push({
                  id: i,
                  fullName: element['fullName'],
                  email: element['email'],
                  attended_percentage: '',
                  presents: 0,
                });
              }
            });
            value.forEach(element => {
              const index = studentsData.value.findIndex(object => {
                return object.email === element['email'];
              });
              if(element['type'] == 1) {
                studentsData.value[index].presents++;
                presents++;
              } else {
                studentsData.value[index].absents++;
                absents++;
              }
            });
            value.forEach(element => {
              const index = studentsData.value.findIndex(object => {
                return object.email === element['email'];
              });
              if(total !== 0) {
                studentsData.value[index].attended_percentage = ((studentsData.value[index].presents / total) * 100);
                studentsData.value[index].attended_percentage += '%';
              }
            });
            chartOptions.value.data.datasets[0].data.push((presents / studentsData.value.length) * 100);
            chartOptions.value.data.datasets[1].data.push((absents / studentsData.value.length) * 100);
          }
          viewChart.value = true;
				} else if(res.data.status == 401) {
          console.log(res.data.status);
				}
      })
    }

    const { refFormObserver, getValidationState } = formValidation()

    const {
			state,
      fetchCourses,
    } = useAttendance(props, { emit })
    
		fetchCourses();

    return {
      refFormObserver,
      getValidationState,
			onChange,
			state,
			loading,
      AttendanceData,
      fetchCourses,
      chartOptions,
      viewChart,
      studentsData,
      tableColumns
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
