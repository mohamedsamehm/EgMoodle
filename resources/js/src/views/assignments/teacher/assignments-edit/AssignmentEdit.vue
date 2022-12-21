<template>
	<b-card>
		<div class="d-flex justify-content-between align-items-center py-1">
			<h5 class="mb-0">Edit Assignment</h5>
		</div>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.formLoading" style="min-height: 450px;" >
			<b-spinner label="Loading..."/>
		</div>
    <template v-else>
      <validation-observer ref="refFormObserver" #default="{ handleSubmit }">
				<b-form @submit.prevent="handleSubmit(onSubmit)" enctype="multipart/form-data">
          <b-row>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="Title">
                <label>Title</label>
                <b-form-group>
                  <b-form-input
                    v-model="state.assignment.title"
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
                    v-model="state.assignment.content"
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
                    v-model="state.assignment.week"
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
                    v-model="state.assignment.deadline"
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
                    v-model="state.assignment.course"
                    label="course_name"
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
                    v-model="state.assignment.course.selectedSections"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="state.assignment.course.sections"
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
                  v-model="state.assignment.file_name"
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
                Save Changes
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
import router from '@/router'
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

    const toast = useToast();
    const loading = ref(false)
    const config = {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    };
    const onSubmit = () => {
      let sections = [];
      // Set formData
      const formData = new FormData();
      formData.append('assignment_id', state.assignment.assignment_id);
      formData.append('material_id', state.assignment.material_id)
      if(state.assignment.file_name) {
        formData.append('file', state.assignment.file_name)
      }
      formData.append('title', state.assignment.title)
      formData.append('content', state.assignment.content)
      formData.append('week', state.assignment.week)
      formData.append('deadline', state.assignment.deadline)
      state.assignment.course_id = state.assignment.course.course_id;
      formData.append('course_id', state.assignment.course_id)
      state.assignment.course.selectedSections.forEach(element => {
        sections.push(element.section_id);
      });
      formData.append('sections[]', sections)
			loading.value = true;
      store.dispatch('assignments/updateAssignment', formData, config).then((res) => {
        console.log(res);
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Assignment updated successfully', 'success');
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

    const { refFormObserver, getValidationState, resetForm } = formValidation()

    const {
			state,
      weeks,
      fetchAssignment,
      fetchCourses,
    } = useAssignments(props, { emit })
    
		fetchCourses();

		fetchAssignment({ id: router.currentRoute.params.id });

    return {
      refFormObserver,
      getValidationState,
			onSubmit,
      resetForm,
			state,
			loading,
      weeks,
      fetchAssignment,
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