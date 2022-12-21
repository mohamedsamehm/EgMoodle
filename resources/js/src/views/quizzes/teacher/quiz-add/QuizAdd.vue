<template>
  <validation-observer ref="refFormObserver" #default="{ handleSubmit }">
    <b-form @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="resetForm">
      <b-card no-body>
        <b-card-header class="pt-2 border-bottom">
          <h5 class="mb-0">Add Quiz</h5>
        </b-card-header>
        <b-card-body class="p-2">
          <b-row>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="Title">
                <label>Title</label>
                <b-form-group>
                  <b-form-input
                    v-model="QuizData.title"
                    :state="getValidationState(validationContext)"
                    placeholder="Quiz 1"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Content">
                <label>Content</label>
                <b-form-group>
                  <b-form-textarea
                    v-model="QuizData.content"
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
                    v-model="QuizData.week"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    label="title"
                    :options="weeks"
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
                    v-model="QuizData.course"
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
                    v-model="QuizData.sectionsObj"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="QuizData.course.sections"
                    label="section_name"
                    multiple
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <validation-provider #default="validationContext" rules="required" name="Opens at">
                <label>Opens at</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <flat-pickr
                    v-model="QuizData.opens_at"
                    class="form-control"
                    :config="{ enableTime: true, dateFormat: 'Y-m-d H:i', maxDate: new Date().fp_incr(30)}"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
              <validation-provider #default="validationContext" rules="required" name="Duration">
                <label>Duration</label>
                <b-form-group :state="getValidationState(validationContext)">
                  <flat-pickr
                    v-model="QuizData.duration"
                    class="form-control"
                    :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true}"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </b-card-body>
      </b-card>
      <b-card no-body>
        <b-card-header class="pt-2 border-bottom">
          <h5 class="mb-0">Questions</h5>
        </b-card-header>
        <b-card-body class="p-2">
          <b-row class="mt-1" no-gutters>
            <b-col cols="12" md="4" class="ml-auto text-right">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="outline-primary" size="sm" @click="addQuestion">
                Add question
              </b-button>
            </b-col>
          </b-row>
          <app-collapse accordion type="margin">
            <app-collapse-item v-for="(question, index) in QuizData.questions" :key="index" :title="'Question ' + (index+1)">
              <b-row>
                <b-col md="6">
                  <validation-provider #default="validationContext" rules="required" name="Question Title">
                    <label>Question Title</label>
                    <b-form-group>
                      <b-form-input
                        v-model="question.title"
                        :state="getValidationState(validationContext)"
                        placeholder="Question"
                      />
                      <b-form-invalid-feedback>
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>
                  <validation-provider #default="validationContext" rules="required" name="Type">
                    <label>Select Type</label>
                    <b-form-group :state="getValidationState(validationContext)">
                      <v-select
                        v-model="question.type"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        label="title"
                        :options="types"
                        @input="changeType(question)"
                      />
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>
                </b-col>
                <b-col md="6">
                  <validation-provider #default="validationContext" rules="required" :name="'Choice ' + choice.index" v-for="choice in question.choices" :key="choice.index">
                    <label>Choice {{choice.index}}</label>
                    <b-form-group>
                      <b-form-input
                        v-model="choice.choice"
                        :state="getValidationState(validationContext)"
                        :placeholder="'Choice ' + choice.index"
                      />
                      <b-form-checkbox class="custom-control-primary mt-50" v-model="choice.answer" @change="checkCorrectAnswer(choice, question.type, question.choices)">
                        Correct Answer
                      </b-form-checkbox>
                      <b-form-invalid-feedback>
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>
                  <b-button
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="outline-info" size="sm" @click="addChoice(question)">
                    Add more choice
                  </b-button>
                </b-col>
              </b-row>
            </app-collapse-item>
          </app-collapse>
        </b-card-body>
        <b-card-footer>
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
        </b-card-footer>
      </b-card>
    </b-form>
  </validation-observer>
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
  BCardBody,
  BCardHeader,
  BCardFooter,
  BFormCheckbox,
} from 'bootstrap-vue'
// import timepicker from window.VueTimepickr.default
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { required } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import store from '@/store'
import useQuizzes from '../../useQuizzes';
import useQuizzesModule from '../../useQuizzesModule';
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
export default {
  components: {
    AppCollapse,
    AppCollapseItem,
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
    BCardBody,
    BCardHeader,
    BCardFooter,
    BFormCheckbox,
    vSelect,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'quizzes'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useQuizzesModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const blankQuizData = {
      title: '',
      content: '',
      week: '',
      opens_at: '',
      duration: '',
      sectionsObj: [],
      course_id: '',
      course: [],
      sections: [],
      questions: [
        {
          title: '',
          type: 'One Answer',
          choices: [
            {choice: '', answer: false, index: 1},
            {choice: '', answer: false, index: 2},
          ]
        },
      ]
    }
    const toast = useToast();
    const loading = ref(false)
    const QuizData = ref(JSON.parse(JSON.stringify(blankQuizData)));

    const resetQuizData = () => {
      QuizData.value = JSON.parse(JSON.stringify(blankQuizData))
    }
    const addQuestion = () => {
      QuizData.value.questions.push({
        title: '',
        type: 'One Answer',
        choices: [
          {choice: '', answer: false, index: 1},
          {choice: '', answer: false, index: 2},
        ]
      });
    }
    const changeType = (question) => {
      question.choices.forEach(element => {
        element.answer = false;
      });
    }
    const addChoice = (question) => {
      question.choices.push({choice: '', answer: false, index: question.choices.length + 1});
    }
    const checkCorrectAnswer = (choice, type, choices) => {
      if(type == 'One Answer') {
        choices.forEach(element => {
          element.answer = false;
        });
        choice.answer = true;
      }
    }
    const onSubmit = () => {
      QuizData.value.sectionsObj.forEach(element => {
        QuizData.value.sections.push(element.section_id);
      });
      QuizData.value.course_id = QuizData.value.course.course_id;
			loading.value = true;
      console.log();
      store.dispatch('quizzes/addQuiz', QuizData.value).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage(res.data.msg, 'success');
          resetQuizData();
				} else if(res.data.status == 401) {
          for (const [key, value] of Object.entries(res.data.errors)) {
            showMessage('Quiz Error', value[0], 'warning');
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

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetQuizData)

    const {
			state,
      weeks,
      types,
      fetchCourses,
    } = useQuizzes(props, { emit })
    
		fetchCourses();

    return {
      refFormObserver,
      getValidationState,
			onSubmit,
      resetForm,
			state,
			loading,
      weeks,
      QuizData,
      fetchCourses,
      types,
      addQuestion,
      checkCorrectAnswer,
      addChoice,
      changeType
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