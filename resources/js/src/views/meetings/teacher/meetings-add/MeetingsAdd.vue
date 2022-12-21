<template>
	<b-card>
		<div class="d-flex justify-content-between align-items-center py-1">
			<h5 class="mb-0">Add New Meeting</h5>
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
                    v-model="MeetingData.title"
                    :state="getValidationState(validationContext)"
                    placeholder="Meeting 1"
                  />
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Teams URL">
                <label>Teams URL</label>
                <b-form-group>
                  <b-form-input
                    v-model="MeetingData.teams_url"
                    :state="getValidationState(validationContext)"
                    placeholder="https://teams.microsoft.com/dl/launcher/launcher.html?url=%2F_%23%2Fl%2Fmeetup-join%2F19%3Ameeting_NTViNzE2NjAtNTE4Yy00ZmNmLWIzZTItOGM0OThhYjljYWFi%40thread.v2%2F0%3Fcontext%3D%257b%2522Tid%2522%253a%2522639f673c-9b00-44b8-b2d7-51d8e0a51895%2522%252c%2522Oid%2522%253a%25225f7fd182-0fd2-425a-8d5f-ea5481b2bc73%2522%257d%26anon%3Dtrue&type=meetup-join&deeplinkId=29ae5e81-8d6e-446d-9859-acd8e18efdb5&directDl=true&msLaunch=true&enableMobilePage=true"
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
                    v-model="MeetingData.content"
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
                    v-model="MeetingData.week"
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
              <validation-provider #default="validationContext" rules="required" name="Course">
                <label>Course</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <v-select
                    v-model="MeetingData.course"
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
                    v-model="MeetingData.sectionsObj"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="MeetingData.course.sections"
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
    const USER_APP_STORE_MODULE_NAME = 'meetings'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useMeetingsModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const blankMeetingData = {
      course: [],
			title: '',
      content: '',
      teams_url: '',
      week: '',
      sectionsObj: [],
      course_id: '',
      sections: [],
    }

    const toast = useToast();
    const loading = ref(false)
    const MeetingData = ref(JSON.parse(JSON.stringify(blankMeetingData)));
    const resetMeetingData = () => {
      MeetingData.value = JSON.parse(JSON.stringify(blankMeetingData))
    }
    const onSubmit = () => {
      MeetingData.value.sections = [];
      MeetingData.value.sectionsObj.forEach(element => {
        MeetingData.value.sections.push(element.section_id);
      });
      MeetingData.value.course_id = MeetingData.value.course.course_id;
			loading.value = true;
      store.dispatch('meetings/addMeeting', MeetingData.value).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Meeting added successfully', 'success');
          resetMeetingData();
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

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetMeetingData)

    const {
			state,
      weeks,
      fetchCourses,
    } = useMeetings(props, { emit })
    
		fetchCourses();

    return {
      refFormObserver,
      getValidationState,
			onSubmit,
      resetForm,
			state,
			loading,
      weeks,
      MeetingData,
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