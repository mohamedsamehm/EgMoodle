<template>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.formLoading" style="min-height: 450px;" >
			<b-spinner label="Loading..."/>
		</div>
    <div v-else>
      <b-card>
        <template>
          <b-table-simple stacked bordered responsive>
            <b-tbody>
              <b-tr>
                <b-td stacked-heading="Meeting Name">{{state.meeting.title}}</b-td>
              </b-tr>
              <b-tr>
                <b-td stacked-heading="Teams URL" class="teams_url_table">{{state.meeting.teams_url}}</b-td>
              </b-tr>
              <b-tr>
                <b-td stacked-heading="Course">
                  <p class="mb-0">{{state.meeting.course_name}}</p>
                </b-td>
              </b-tr>
              <b-tr>
                <b-td stacked-heading="Sections">
                  <p v-for="section in state.meeting.course.selectedSections" :key="section.section_id" class="mb-0">
                    {{section['section_name']}}
                  </p>
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <template>
            <!-- accept=".csv" -->
            <b-form-file
              ref="refInputEl"
              v-model="file"
              accept=".csv"
              :hidden="true"
              plain
              @change="onFileChange"
            />
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="outline-dark px-1" @click="$refs.refInputEl.$el.click()">
              Upload Attendance
            </b-button>
          </template>
        </template>
      </b-card>
      <b-card v-if="students.length > 0" no-body>
        <b-card-body>
          <b-table responsive="sm" bordered hover outlined striped :items="students"/>
        </b-card-body>
        <b-card-footer>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="onSubmit">
            Save
          </b-button>
        </b-card-footer>
      </b-card>
    </div>
</template>
<script>
import { 
  BRow,
  BCol,		
  BCard,
  BCardFooter,
  BCardHeader,
  BCardBody,
  BSidebar,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormTextarea,
  BButton,
  BSpinner,
  BFormFile,
  BTableSimple,
  BTable,
  BTh,
  BTd,
  BTr,
  BThead,
  BTbody,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { required } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import router from '@/router'
import useMeetings from '../../useMeetings';
import useMeetingsModule from '../../useMeetingsModule';
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';

export default {
  components: {
    flatPickr,
		BRow,
		BCol,		
		BCard,
    BCardFooter,
    BCardHeader,
    BCardBody,
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
    BTable,
    BTableSimple,
    BTh,
    BTd,
    BTr,
    BThead,
    BTbody,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'meetings'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useMeetingsModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const toast = useToast();
    const loading = ref(false)
    const file = ref(null)
    const upload = ref(true);
    const students = ref([])
    const emails = ref([])
    const attendanceList = ref({
      id: router.currentRoute.params.id,
      students: [],
    })
    const onSubmit = () => {
      for (const [key, value] of Object.entries(state.students)) {
        console.log(emails.value);
        if(emails.value.indexOf(value['email']) > -1) {
          attendanceList.value.students.push({
            student_id: value['id'],
            type: 1
          })
        } else {
          attendanceList.value.students.push({
            student_id: value['id'],
            type: 0
          })
        }
      }
      store.dispatch('meetings/submitAttendance', attendanceList.value).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Meeting updated successfully', 'success');
				} else if(res.data.status == 401) {
          for (const [key, value] of Object.entries(res.data.errors)) {
            showMessage('Upload Error', value[0], 'warning');
          }
				}
      })
    }

		const showMessage = (title, text, variant) => {
			toast({
				component: ToastificationContent,
				props: {
					title: title,
          text: text,
					icon: 'BellIcon',
					variant: variant,
				},
			})
		}
    const csvJSON = (csv) => {
      var lines = csv.split("\n");
      var result = []
      var headers = lines[0].split(",")
      lines.map(function(line, indexLine){
        if (indexLine < 1) return
        var obj = {}
        let lineN = line.replaceAll('"' , "").replaceAll(', ' , " ");
        var currentline = lineN.split(",")
        headers.map(function(header, indexHeader){
          obj[header] = currentline[indexHeader];
          let email = obj[header].replace("\r", "");
          if((header.replace("\r", "").toLowerCase() == "email") && (email !== '') && (emails.value.indexOf(email) == -1)) {
            emails.value.push(email);
          }
        })
        result.push(obj)
      })
      // result.pop()
      return result
    };
    const loadCSV = (e) => {
      if (window.FileReader) {
        var reader = new FileReader();
        reader.readAsText(e.target.files[0]);
        // Handle errors load
        reader.onload = function(event) {
          var csv = event.target.result;
          if(csvJSON(csv).length > 0) {
            students.value = csvJSON(csv);
            upload.value = false;
          }
        };
        reader.onerror = function(evt) {
          if(evt.target.error.name == "NotReadableError") {
            console.log('cant read file');
          }
        };
      } else {
        console.log('Can\'t upload');
      }
    };
    const onFileChange = (e) => {
      setTimeout(() => {
        loadCSV(e);
      }, 50);
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation()

    const {
			state,
      fetchMeeting,
      fetchStudents,
    } = useMeetings(props, { emit })
    
		fetchMeeting({ id: router.currentRoute.params.id });

		fetchStudents({ id: router.currentRoute.params.id });

    return {
      students,
      upload,
      refFormObserver,
      getValidationState,
			onSubmit,
      onFileChange,
      resetForm,
			state,
			loading,
      file,
      fetchStudents,
      fetchMeeting,
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
.table.b-table.b-table-stacked > tbody > tr > [data-label]::before {
  text-align: left !important;
}
.teams_url_table div {
  white-space: nowrap;
  max-width: 700px;
  text-overflow: ellipsis;
  overflow: hidden;
}
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>