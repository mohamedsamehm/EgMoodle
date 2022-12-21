<template>
	<b-card>
		<div class="d-flex justify-content-between align-items-center py-1">
			<h5 class="mb-0">Add New Assignment</h5>
		</div>
    <template>
      <validation-observer ref="refFormObserver" #default="{ handleSubmit }">
				<b-form @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="resetForm" enctype="multipart/form-data">
          <b-row>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="Title">
                <label>Title</label>
                <b-form-group>
                  <b-form-input
                    v-model="AssignmentData.title"
                    :state="getValidationState(validationContext)"
                    placeholder="Assignment 1"
                  />
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Content">
                <label>Content</label>
                <b-form-group>
                  <b-form-textarea
                    v-model="AssignmentData.content"
                    :state="getValidationState(validationContext)"
                    rows="3"
                  />
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Week">
                <label>Week</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="AssignmentData.week"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    label="title"
                    :options="weeks"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="deadline">
                <label>Due Date</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <flat-pickr
                    v-model="AssignmentData.deadline"
                    class="form-control"
                    :config="{ enableTime: true, dateFormat: 'Y-m-d H:i', maxDate: new Date().fp_incr(30)}"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Course">
                <label>Course</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="AssignmentData.course"
                    label="course_name"
                    value="course_id"
                    :options="state.courses"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Sections">
                <label>Sections</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="AssignmentData.sectionsObj"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="AssignmentData.course.sections"
                    label="section_name"
                    multiple
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col>
              <label>File</label>
              <b-form-group>
                <b-form-file
                  v-model="AssignmentData.file"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                />
              </b-form-group>
            </b-col>
          </b-row>
          <!-- submit button -->
          <b-row>
            <b-col cols="12" md="4">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                type="submit">
                <b-spinner small v-if="loading"/>
                Save
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </template>
	</b-card>
</template>
<script>
import { 
  BRow,
  BCol,		
  BCard,
  BSidebar,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormTextarea,
  BButton,
  BSpinner,
  BFormFile,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { required } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import axios from '@axios'
import useAssignments from '../../useAssignments';
import useAssignmentsModule from '../../useAssignmentsModule';
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';

export default {
  components: {
    flatPickr,
		BRow,
		BCol,		
		BCard,
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
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'assignments'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useAssignmentsModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const blankAssignmentData = {
      course: [],
			title: '',
      content: '',
      week: '',
      deadline: '',
      sectionsObj: [],
      file: null,
      course_id: '',
    }

    const toast = useToast();
    const loading = ref(false)
    const AssignmentData = ref(JSON.parse(JSON.stringify(blankAssignmentData)));
    const resetAssignmentData = () => {
      AssignmentData.value = JSON.parse(JSON.stringify(blankAssignmentData))
    }
    const config = {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    };
    const onSubmit = () => {
      // Set formData
      const formData = new FormData()
      let sections = [];
      formData.append('file', AssignmentData.value.file)
      formData.append('title', AssignmentData.value.title)
      formData.append('content', AssignmentData.value.content)
      formData.append('week', AssignmentData.value.week)
      formData.append('deadline', AssignmentData.value.deadline)
      AssignmentData.value.sectionsObj.forEach(element => {
        sections.push(element.section_id);
      });
      formData.append('sections[]', sections)
      AssignmentData.value.course_id = AssignmentData.value.course.course_id;
      formData.append('course_id', AssignmentData.value.course_id)
			loading.value = true;
      store.dispatch('assignments/addAssignment', formData, config).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Assignment added successfully', 'success');
          resetAssignmentData();
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

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetAssignmentData)

    const {
			state,
      weeks,
      fetchCourses,
    } = useAssignments(props, { emit })
    
		fetchCourses();

    return {
      refFormObserver,
      getValidationState,
			onSubmit,
      resetForm,
			state,
			loading,
      weeks,
      AssignmentData,
      fetchCourses,
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
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>