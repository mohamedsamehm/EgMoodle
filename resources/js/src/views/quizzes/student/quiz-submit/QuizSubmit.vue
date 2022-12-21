<template>
  <validation-observer ref="refFormObserver" #default="{ handleSubmit }">
    <b-form @submit.prevent="handleSubmit(onSubmit)">
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading" style="min-height: 450px;">
      <b-spinner label="Loading..."/>
    </div>
    <b-card v-else-if="state.quiz.hasAttempt">
      <div v-if="state.quiz.seconds > 0" class="text-left">
        Results will be availabe in <strong class="timeLeft">{{state.quiz.timeLeft}}</strong>
      </div>
      <b-button v-else variant="gradient-success" :to="{ name: 'quiz-results', params: { id: state.quiz.exam_id } }">
        <span class="text text-capitalize">View Results</span>
      </b-button>
    </b-card>
    <b-row no-gutters v-else-if="state.canExam">
      <b-col xl="3" style="">
        <b-card no-body class="quiz-wrapper">
          <b-card-header class="border-bottom p-1">
            <strong>Quiz Navigation</strong>
          </b-card-header>
          <b-card-body class="p-2">
            <div class="questions-numbers mb-2" no-gutters>
              <div class="border number" v-for="item in state.quiz.questions.length" :key="item">
                <strong>{{item}}</strong>
              </div>
            </div>
            <b-card-text>
              <span>Time Left:</span> <strong class="timeLeft">{{state.quiz.timeLeft}}</strong>
            </b-card-text>
          </b-card-body>
          <b-card-footer>
            <b-row>
              <b-col cols="12" md="4">
                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="success"
                  type="submit">
                  Submit
                </b-button>
              </b-col>
            </b-row>
          </b-card-footer>
        </b-card>
      </b-col>
      <b-col xl="9" class="pl-xl-3">
        <b-card v-for="(question, index) in state.quiz.questions" :key="index" :id="'item'+(index+1)">
          <b-form-group class="m-0">
            <label class="mb-2 pt-0 font-weight-bold h4 font-medium-3 question-title">{{(index+1) + '. ' + (question.title)}}</label>
            <template v-if="question.type == 'One Answer'">
              <b-card no-body class="mb-1 mx-1 border" v-for="choice in question.choices" :key="choice.index">
                <b-card-body class="p-1">
                  <b-form-radio
                    :name="question.title"
                    :value="choice.id"
                    v-model="state.myQuizData.questions[index].my_answer_choice_id"
                  >
                    {{ choice.choice }}
                  </b-form-radio>
                </b-card-body>
              </b-card>
            </template>
            <template v-else>
              <b-card no-body class="mb-1 mx-1 border" v-for="choice in question.choices" :key="choice.index">
                <b-card-body class="p-1">
                  <b-form-checkbox
                    :value="choice.id"
                    v-model="state.myQuizData.questions[index].my_answer_choice_id"
                  >
                    {{ choice.choice }}
                  </b-form-checkbox>
                </b-card-body>
              </b-card>
            </template>
          </b-form-group>
          <!-- <hr class="border"> -->
        </b-card>
      </b-col>
    </b-row>
    <b-alert variant="info" show v-else>
      <div class="alert-body">
        <span>Quiz has been ended from {{state.quiz.endTime}}</span>
      </div>
    </b-alert>
    </b-form>
  </validation-observer>
</template>
<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive'
import store from '@/store'
import router from '@/router'
import useQuizzes from '../../useQuizzes';
import useQuizzesModule from '../../useQuizzesModule';
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';
import {
  BButton,
  BCardText,
  BProgress,
  BFormFile,
  BContainer,
  BRow,
  BCol,
  BListGroup,
  BListGroupItem,
  BCard,
  BCardHeader,
  BCardBody,
  BCardFooter,
  BTableSimple,
  BTh,
  BTd,
  BTr,
  BThead,
  BTbody,
  BFormRadioGroup,
  BForm,
  BFormCheckbox,
  BFormCheckboxGroup,
  BFormGroup,
  BFormRadio,
  BSpinner,
  BAlert,
} from 'bootstrap-vue'
export default {
  components: {
    BFormRadioGroup,
    BForm,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormGroup,
    BFormRadio,
    BButton,
    BCardText,
    BProgress,
    BFormFile,
    BContainer,
    BRow,
    BCol,
    BListGroup,
    BListGroupItem,
    BCard,
    BCardBody,
    BCardHeader,
    BCardFooter,
    BTableSimple,
    BTh,
    BTd,
    BTr,
    BTbody,
    BThead,
    BSpinner,
    BAlert,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  methods: {
    handleScroll () {
      if (window.pageYOffset > 20) {
        document.querySelector('.quiz-wrapper').style.top = "109px";
      } else {
        document.querySelector('.quiz-wrapper').style.top = "unset";
      }
    }
  },
  created() {
    setTimeout(() => {
      if(window.innerWidth > 1200) {
        window.addEventListener('scroll', this.handleScroll);
      } else {
        document.querySelector('.quiz-wrapper').style.position = "relative";
        document.querySelector('.quiz-wrapper').style.maxWidth = "100%";
      }
      if(this.state.quiz.length == 0 || this.state.quiz == null) {
        this.$router.push('/error-404')
      }
    }, 1000);
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'quizzes'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useQuizzesModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })

    const toast = useToast();
    const loading = ref(false)
    let intervalTimer = null;
    const onSubmit = () => {
      state.myQuizData.exam_id = state.quiz.exam_id;
      state.myQuizData.questions.forEach(question => {
        if(question.answer_choice_id.length == 1) {
          if(question.my_answer_choice_id == question.answer_choice_id) {
            question.grade = 1;
          } else {
            question.grade = 0;
          }
          question.my_answer_choice_id = [question.my_answer_choice_id];
        } else {
          question.grade = 0;
          question.answer_choice_id.forEach(answer => {
            if(question.my_answer_choice_id.includes(answer)) {
              question.grade += 1/question.answer_choice_id.length;
            }
          });
        }
        state.myQuizData.grade += question.grade;
      });
			loading.value = true;
      store.dispatch('quizzes/storeMyQuiz', state.myQuizData).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Quized saved successfully', 'success');
          fetchMyQuiz({ id: router.currentRoute.params.id });
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
    const zeroPadded = (num) => {
      // 4 --> 04
      return num < 10 ? `0${num}` : num;
    }
    const setTime = (seconds) => {
      clearInterval(intervalTimer);
      timer(seconds);
    };
    const timer = (seconds) => {
      const now = Date.now();
      const end = now + seconds * 1000;
      displayTimeLeft(seconds);
      countdown(end);
    };
    const countdown = (end) => {
      intervalTimer = setInterval(() => {
        const secondsLeft = Math.round((end - Date.now()) / 1000);
        if(secondsLeft >= 1) {
        } else {
          state.canExam = false;
          clearInterval(intervalTimer);
          return;
        }
        displayTimeLeft(secondsLeft)
      }, 1000);
    };
    const displayTimeLeft = (secondsLeft) => {
      const minutes = Math.floor((secondsLeft % 3600) / 60);
      const seconds = secondsLeft % 60;
      state.quiz.timeLeft = `${zeroPadded(minutes)}:${zeroPadded(seconds)}`;
      document.querySelector('.timeLeft').innerHTML = state.quiz.timeLeft;
    };

    const { refFormObserver, getValidationState } = formValidation()

    const {
			state,
      fetchMyQuiz,
    } = useQuizzes(props, { emit })
    
		fetchMyQuiz({ id: router.currentRoute.params.id });

    setTimeout(() => {
      if(state.quiz.seconds > 0) {
        setTime(state.quiz.seconds);
      }
    }, 500);

    return {
      refFormObserver,
      getValidationState,
			onSubmit,
			state,
      fetchMyQuiz,
			loading,
    }
  },
}
</script>
<style lang="scss">
html {
  scroll-behavior: smooth;
}
.questions-numbers {
  display: flex;
  justify-content: flex-start;
  gap: 16px;
  align-items: center;
  align-self: start;
  flex-wrap: wrap;
  max-width: 322px;
}
.number {
  position: relative;
  display: inline-block;
  vertical-align: top;
  width: 40px;
  height: 40px;
  text-align: center;
  line-height: 45px;
  cursor: pointer;
  transition: 0.3s ease;
  &:before {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    right: 3px;
    height: 3px;
    background-color: #bbb;
  }
}
.quiz-wrapper {
  position: fixed;
  max-width: 23%;
  width: 100%;
  transition: all .3s ease-in-out;
}
.question-title {
  max-width: 850px;
  line-height: 1.9;
}
</style>