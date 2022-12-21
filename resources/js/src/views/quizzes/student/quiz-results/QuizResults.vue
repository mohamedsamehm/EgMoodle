<template>
  <div>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading" style="min-height: 450px;">
      <b-spinner label="Loading..."/>
    </div>
    <b-row no-gutters>
      <b-col xl="3">
        <b-card no-body class="quiz-wrapper">
          <b-card-header class="border-bottom p-1">
            <strong>Reviewing Answers</strong>
          </b-card-header>
          <b-card-body class="p-2">
            <b-card-title>
              {{state.quiz.title}}
            </b-card-title>
            <b-card-text class="mb-2 h4">
              <span>Grade:</span> <strong class="text-primary">{{state.quiz.grade}}</strong> <span> / {{state.quiz.questions.length}}</span>
            </b-card-text>
            <b-card-text>
              <span>Submitted at:</span> <strong class="timeLeft">{{state.quiz.createdDate | formatNow}}</strong>
            </b-card-text>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col xl="9" class="pl-xl-3">
        <b-card v-for="(question, index) in state.quiz.questions" :key="index" :id="'item'+(index+1)">
          <b-form-group class="m-0">
            <label class="mb-2 pt-0 font-weight-bold h4 font-medium-3 d-flex justify-content-between align-items-center">
              <span class="question-title">{{(index+1) + '. ' + (question.title)}}</span>
              <span>{{question.grade}} / 1</span>
            </label>
            <template>
              <b-card no-body class="mb-1 mx-1" :class="{ 'border-success': ( choice.answer && choice.myAnswer ), 'border-danger': ( choice.myAnswer && !choice.answer ), 'border': ( !choice.myAnswer ), }" v-for="choice in question.choices" :key="choice.index">
                <b-card-body class="p-1 d-flex justify-content-between align-items-center">
                  <span>{{ choice.choice }}</span>
                  <strong class="text-secondary" v-if="choice.answer">Correct Answer</strong>
                  <strong class="text-secondary" v-else-if="!choice.answer && choice.myAnswer">Wrong Answer</strong>
                </b-card-body>
              </b-card>
            </template>
          </b-form-group>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import Ripple from 'vue-ripple-directive'
import store from '@/store'
import router from '@/router'
import useQuizzes from '../../useQuizzes';
import useQuizzesModule from '../../useQuizzesModule';
import { ref, reactive, onUnmounted } from '@vue/composition-api'
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
  BCardTitle,
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
    BCardTitle,
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
    const loading = ref(false)
    const {
			state,
      fetchQuizResults,
    } = useQuizzes(props, { emit })
    
		fetchQuizResults({ id: router.currentRoute.params.id });

    return {
			state,
      fetchQuizResults,
			loading,
    }
  },
}
</script>
<style lang="scss">
html {
  scroll-behavior: smooth;
}
.question-title {
  max-width: 850px;
  line-height: 1.9;
}
.quiz-wrapper {
  position: fixed;
  max-width: 23%;
  width: 100%;
  transition: all .3s ease-in-out;
}
</style>