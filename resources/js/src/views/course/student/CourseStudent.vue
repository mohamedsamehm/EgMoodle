<template>
  <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading"  style="min-height: 450px;" >
    <b-spinner label="Loading..."/>
  </div>
  <div v-else>
    <b-row class="content-header" no-gutters>
      <div class="content-header-left mb-2 col-md-9 col-12">
        <div class="breadcrumb-wrapper">
          <b-breadcrumb class="breadcrumb-chevron p-0">
            <b-breadcrumb-item :to="{ name: 'dashboard' }">
              <feather-icon
                icon="HomeIcon"
                size="17"
                class="align-middle"
              />
            </b-breadcrumb-item>
            <b-breadcrumb-item active class="text-uppercase">
              {{courseId}}
            </b-breadcrumb-item>
          </b-breadcrumb>
        </div>
      </div>
    </b-row>
    <b-card no-body>
      <b-card-header class="p-0">
        <div class="course-header" :style="'background-image: url(\'/images/courses/' + state.course.image + '\')'">
          <div class="overlay"></div>
          <h1 class="font-weight-medium text-h4 text-main">{{state.course.course_name}}</h1>
          <p>{{state.course.course_id}}<span v-for="section in state.course.sections" :key="section.id"> | {{section}}</span></p>
        </div>
      </b-card-header>
    </b-card>
    <b-row v-if="state.emptyWeeks">
      <b-col cols="12">
        <b-alert variant="info" show>
          <div class="alert-body">
            <span>No material added yet</span>
          </div>
        </b-alert>
      </b-col>
    </b-row>
    <template v-else>
      <template v-if="state.mySurvey">
        <b-card no-body>
          <b-card-header class="border-bottom">
            <b-card-title class="w-100">
              <p dir="RTL" style="text-align: right;" class="w-100 m-0 font-weight-bolder">استمارة تقييم مقرر دراسى</p>
            </b-card-title>
          </b-card-header>
          <b-card-body>
            <p dir="RTL" class="text-right mt-1 survey-paragraph">
              <span class="font-weight-bolder">عزيزى الطالب</span>  يهدف هذا الاستبيان إلى
              التعرف على رأى الطالب فى المقرر الدراسى الذى درسه. ونرجو أن تكون الإجابات
              موضوعية ودقيقة إلى أكبر حد ممكن. وسوف يفيد التعرف على آرائكم فى إظهار بعض
              الحقائق والسلبيات الموجودة مما يمكن أستاذ المادة وإدارة الأكاديمية إذا لزم
              الأمر- من القيام بما هو ضرورى لتحسين العملية التعليمية وتلافى أى قصور محتمل.
              وأود التأكيد على ما يلى:
            </p>
            <ol dir="RTL" class="text-right survey-ol">
              <li>الحرية الكاملة فى كتابة آرائكم والتأكد أنها ستؤخد فى الاعتبار</li>
              <li>إحاطة الطلاب علما بما قد يتم من إجراءات فى هذا الخصوص</li>
              <li>السرية التامة في التعامل مع الاستبيان.</li>
            </ol>
          </b-card-body>
          <b-card-footer class="text-center">
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="gradient-primary" v-b-modal.survey>Take a survey</b-button>
          </b-card-footer>
        </b-card>
        <b-modal
          id="survey"
          ref="modal"
          title="استمارة تقيمم مقرر دراسي"
          title-class="text-right h3 font-weight-bolder"
          dir="rtl"
          size="lg"
          body-class="p-0"
          scrollable
          cancel-title="Close"
          ok-title="Save"
          cancel-variant="outline-secondary"
          @ok="handleOk"
        >
          <b-form dir="rtl" @submit.stop.prevent="handleSubmit" ref="form">
          <!-- mb-1 h4 -->
            <b-form-group v-for="question in state.questions" :key="question.id" class="mb-0 text-left border-bottom px-2 py-1 d-block" invalid-feedback="Name is required">
              <label class="mb-2 h4">{{question.id}}. {{question.name}}</label>
              <b-form-input
                v-if="question.id == 32"
                v-model="answers[question.id - 1]"
                class="mt-2"
              />
              <b-form-radio-group
                v-else
                v-model="answers[question.id - 1]"
                class="my-1"
                required
                :options="options"
                :name="'option-'+question.id"
              />
            </b-form-group>
          </b-form>
        </b-modal>
      </template>
      <b-row>
        <b-col xl="8">
          <b-card no-body>
            <b-card-header class="d-flex justify-content-end border-bottom py-1">
              <span class="mr-1 d-flex">View: </span>
              <b-dropdown 
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                :text="sortBy.text"
                right
                variant="outline-primary"
                size="sm"
              >
                <b-dropdown-item
                  v-for="sortOption in sortByOptions"
                  :key="sortOption.value"
                  @click="sortBy=sortOption"
                >
                  {{ sortOption.text }}
                </b-dropdown-item>
              </b-dropdown>
            </b-card-header>
            <b-card-body class="p-2">
              <app-collapse accordion type="shadow">
                <template v-for="week in state.weeks">
                  <app-collapse-item :title="'Week ' + week.id" v-if="week.value.length" :key="week.id">
                    <template v-for="item in week.value">
                      <b-card class="mb-2 border" no-body :key="item.id" v-if="(sortBy.value == 'assignments' && item.isAssignment) || (sortBy.value == 'quizzes' && item.isExam) || (sortBy.value == 'all')">
                        <b-card-header class="border-bottom p-1">
                          <h6 class="font-weight-bolder mb-0 w-100 text-capitalize">
                            <b-row align-v="center" no-gutters>
                              <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48" style=" fill:#000000;" v-if="item.teams_url">
                                <path fill="#5059c9" d="M44,22v8c0,3.314-2.686,6-6,6s-6-2.686-6-6V20h10C43.105,20,44,20.895,44,22z M38,16	c2.209,0,4-1.791,4-4c0-2.209-1.791-4-4-4s-4,1.791-4,4C34,14.209,35.791,16,38,16z"></path>
                                <path fill="#7b83eb" d="M35,22v11c0,5.743-4.841,10.356-10.666,9.978C19.019,42.634,15,37.983,15,32.657V20h18	C34.105,20,35,20.895,35,22z M25,17c3.314,0,6-2.686,6-6s-2.686-6-6-6s-6,2.686-6,6S21.686,17,25,17z"></path>
                                <circle cx="25" cy="11" r="6" fill="#7b83eb"></circle><path d="M26,33.319V20H15v12.657c0,1.534,0.343,3.008,0.944,4.343h6.374C24.352,37,26,35.352,26,33.319z" opacity=".05"></path><path d="M15,20v12.657c0,1.16,0.201,2.284,0.554,3.343h6.658c1.724,0,3.121-1.397,3.121-3.121V20H15z" opacity=".07"></path><path d="M24.667,20H15v12.657c0,0.802,0.101,1.584,0.274,2.343h6.832c1.414,0,2.56-1.146,2.56-2.56V20z" opacity=".09"></path><linearGradient id="DqqEodsTc8fO7iIkpib~Na_zQ92KI7XjZgR_gr1" x1="4.648" x2="23.403" y1="14.648" y2="33.403" gradientUnits="userSpaceOnUse"><stop offset="0" stop-variant="#5961c3"></stop><stop offset="1" stop-variant="#3a41ac"></stop></linearGradient><path fill="url(#DqqEodsTc8fO7iIkpib~Na_zQ92KI7XjZgR_gr1)" d="M22,34H6c-1.105,0-2-0.895-2-2V16c0-1.105,0.895-2,2-2h16c1.105,0,2,0.895,2,2v16	C24,33.105,23.105,34,22,34z"></path><path fill="#fff" d="M18.068,18.999H9.932v1.72h3.047v8.28h2.042v-8.28h3.047V18.999z"></path>
                              </svg>
                              <font-awesome-icon class="text-primary" icon="fa-solid fa-file-lines" v-else-if="item.isAssignment || item.isContent"/>
                              <font-awesome-icon class="text-primary" icon="fa-solid fa-code" v-else-if="item.isExam"/>
                              <div class="ml-1">{{item.title}}</div>
                              <small class="font-weight-bold ml-auto text-muted">{{item.created_at | formatDate}}</small>
                            </b-row>
                          </h6>
                        </b-card-header>
                        <b-card-body>
                          <p class="pt-1">{{item.content}}</p>
                          <b-row v-if="item.teams_url">
                            <div class="clipboard-block mx-1 box-shadow-1 border">
                              <b-button :href="item.teams_url" class="mr-1 d-flex justify-content-center align-items-center" variant="primary" target="_blank" size="sm">Open</b-button>
                              <div class="clipboard" :title="(copied ? 'Copied!' : 'Copy')" v-clipboard:copy="item.teams_url" v-clipboard:success="onCopy" v-clipboard:error="onError">
                                <b-form-input v-model="item.teams_url" readonly class="form-control copy-input text-grey" />
                                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="flat-secondary p-0 m-0" class="copy-btn">
                                  <font-awesome-icon icon="fa-regular fa-copy" />
                                </b-button>
                              </div>
                            </div>
                          </b-row>
                          <b-button-toolbar v-else-if="item.isAssignment">
                            <b-button class="mr-1" variant="gradient-primary" :href="state.assignmentsObj[item.id][0].file_name">
                              <font-awesome-icon icon="fa-solid fa-file-pdf" /> Download File
                            </b-button>
                            <b-button v-if="state.myAssignments[item.id]" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-success" class="text-nowrap" :to="{ name: 'assignment-submit', params: { id: state.assignmentsObj[item.id][0].assignment_id } }">
                              Edit Submission
                            </b-button>
                            <b-button v-else v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-success" class="text-nowrap" :to="{ name: 'assignment-submit', params: { id: state.assignmentsObj[item.id][0].assignment_id } }">
                              Add Submission
                            </b-button>
                          </b-button-toolbar>
                          <b-button variant="gradient-primary" :href="item.file_name" v-else-if="item.isContent && (item.teams_url.length == 0 || item.teams_url == null) && item.file_name !== null">
                            <font-awesome-icon icon="fa-solid fa-file-pdf" /> Download File
                          </b-button>
                          <template v-else-if="item.isExam && state.quizzesObj[item.id]">
                            <b-button :variant="'gradient-' + state.quizzesObj[item.id][0].buttonColor" v-if="state.quizzesObj[item.id][0].buttonColor"
                            :to="{ name: state.quizzesObj[item.id][0].route, params: { id: state.quizzesObj[item.id][0].exam_id } }">
                              <span class="text text-capitalize">{{state.quizzesObj[item.id][0].quizText}}</span>
                            </b-button>
                            <b-alert :variant="state.quizzesObj[item.id][0].alertColor" show v-else>
                              <div class="alert-body">
                                <span>{{state.quizzesObj[item.id][0].quizText}} <strong>{{state.quizzesObj[item.id][0].quizTime}}</strong></span>
                              </div>
                            </b-alert>
                          </template>
                        </b-card-body>
                        <b-card-footer class="border-0 pt-0 pb-1 px-1">
                          <b-row align-v="center" no-gutters>
                            <b-button @click="materialStatus(item, $event)" size="sm" v-ripple.400="'rgba(30, 30, 30, 0.15)'" variant="relief-dark">
                              <span class="readedText">{{(state.readedText[item.id]) ? state.readedText[item.id] : 'Mark as readed'}}</span>
                              <feather-icon
                                icon="CheckCircleIcon"
                                size="16"
                                class="align-middle"
                              />
                            </b-button>
                            <small class="text-secondary text-underline ml-auto" v-if="item.slug == 'engineer'">Added by: Eng.{{item.user_name}}</small>
                            <small class="text-secondary text-underline ml-auto" v-else-if="item.slug == 'professor'">Added by: Prof.{{item.user_name}}</small>
                          </b-row>
                        </b-card-footer>
                      </b-card>
                    </template>
                  </app-collapse-item>
                </template>
              </app-collapse>
            </b-card-body>
          </b-card>
        </b-col>
        <b-col lg="4">
          <b-card no-body>
            <b-card-header class="border-bottom py-1">
              <h6 class="font-weight-bolder mb-0">
                Materials
              </h6>
            </b-card-header>
            <b-card-body class="p-0">
              <b-list-group flush class="materials">
                <template v-for="item in state.course_material_all">
                  <b-list-group-item class="p-2 d-flex justify-content-between align-items-center" :key="item.id" v-if="(item.isAssignment && item.file_name !== null) || (item.isContent && (item.teams_url.length == 0 || item.teams_url == null) && item.file_name !== null)">
                    <div>
                      <span class="mr-1">
                        <font-awesome-icon class="text-primary" icon="fa-solid fa-file-lines" />
                      </span>
                      <strong>{{item.title}}</strong>
                    </div>
                    <b-button variant="outline-dark" :href="item.file_name" size="sm">
                      <font-awesome-icon icon="fa-solid fa-file-pdf" /> Download File
                    </b-button>
                  </b-list-group-item>
                </template>
              </b-list-group>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </template>
  </div>
</template>
<script>
import Ripple from 'vue-ripple-directive'
import store from '@/store'
import axios from 'axios';
import router from '@/router'
import useCourse from '../useCourse';
import useCourseModule from '../useCourseModule';
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, reactive, onUnmounted } from '@vue/composition-api'
import { required } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation';
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import vSelect from 'vue-select'
import {
  BModal,
  BForm,
  BFormGroup,
  BFormRadioGroup,
  BButton,
  BButtonToolbar,
  BButtonGroup,
  BSpinner,
  BAlert,
  BImg,
  BBreadcrumb,
  BBreadcrumbItem,
  BCardText,
  BProgress,
  BFormFile,
  BFormInput,
  BContainer,
  BRow,
  BCol,
  BListGroup,
  BListGroupItem,
  BCard,
  BCardHeader,
  BCardTitle,
  BCardBody,
  BCardFooter,
  BTabs,
  BTab,
  BTableSimple,
  BTh,
  BTd,
  BTr,
  BThead,
  BTbody,
  BDropdown,
  BDropdownItem,
  BFormInvalidFeedback,
  BFormValidFeedback,
} from 'bootstrap-vue'
export default {
  components: {
    BModal,
    BForm,
    BFormGroup,
    vSelect,
    AppCollapse,
    AppCollapseItem,
    BTabs,
    BTab,
    BButton,
    BButtonToolbar,
    BButtonGroup,
    BFormRadioGroup,
    BSpinner,
    BAlert,
    BImg,
    BBreadcrumb,
    BBreadcrumbItem,
    BCardText,
    BProgress,
    BFormFile,
    BFormInput,
    BContainer,
    BRow,
    BCol,
    BListGroup,
    BCardHeader,
    BCardTitle,
    BListGroupItem,
    BCard,
    BCardBody,
    BCardFooter,
    BTableSimple,
    BTh,
    BTd,
    BTr,
    BTbody,
    BDropdown,
    BDropdownItem,
    BFormInvalidFeedback,
    BFormValidFeedback,
    BThead,
    ToastificationContent,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  setup(props, { emit }) {
    const USER_APP_STORE_MODULE_NAME = 'course'
    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME)) store.registerModule(USER_APP_STORE_MODULE_NAME, useCourseModule)
    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME)) store.unregisterModule(USER_APP_STORE_MODULE_NAME)
    })
    const readArr = {
      id: null,
      material_id: null,
      read: 0
    }
    const materialStatus = (item, e) => {
      readArr['id'] = item.read_id;
      readArr['material_id'] = item.id;
      if(item.read == 0) {
        item.read = 1;
        readArr['read'] = 1;
        state.readedText[item.id] = "Done";
      } else {
        item.read = 0;
        readArr['read'] = 0;
        state.readedText[item.id] = "Mark As Readed";
      }
      if(e.target.parentNode.classList.contains("readedText")) {
        e.target.parentNode.innerHTML = state.readedText[item.id];
      } else if(e.target.classList.contains("readedText")){
        e.target.innerHTML = state.readedText[item.id];
      } else if(e.target.childNodes[0].classList.contains("readedText")) {
        e.target.childNodes[0].innerHTML = state.readedText[item.id];
      } else if(e.target.parentNode.children[0].classList.contains("readedText")){
        e.target.parentNode.children[0].innerHTML = state.readedText[item.id];
      } else if(e.target.children[0].classList.contains("readedText")){
        e.target.children[0].innerHTML = state.readedText[item.id];
      } else if(e.target.firstChild.classList.contains("readedText")){
        e.target.firstChild.innerHTML = state.readedText[item.id];
      } else if(e.target.firstChild.firstChild.classList.contains("readedText")){
        e.target.firstChild.firstChild.innerHTML = state.readedText[item.id];
      }
      try {
        axios.post('/api/material/update', readArr).then(res => {
          console.log(res);
        }).catch(err => {
          console.log(err);
        });
      } catch (error) {
        console.log(error);
      }
    };
    const toast = useToast();
    const loading = ref(false)
    const courseId = router.currentRoute.params.id;
    const options = ['1', '2', '3', '4', '5'];
    const answers = ['5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5', '5'];

    const onSubmit = () => {
      store.dispatch('course/addSurvey', { questions: answers, course_id: courseId }).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
          state.mySurvey = false;
          showMessage('Thanks for your response', '', 'success');
				} else if(res.data.status == 401) {
          for (const [key, value] of Object.entries(res.data.errors)) {
            showMessage('Error', value[0], 'warning');
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
    const { refFormObserver, getValidationState } = formValidation()
    const {
			state,
      fetchCourseStudent,
      sortBy,
      sortByOptions,
      fetchSurveyQuestions,
      fetchSurvey,
    } = useCourse(props, { emit })
    
		fetchCourseStudent({ id: courseId });

    fetchSurveyQuestions();

    fetchSurvey({ id: courseId });

    return {
      refFormObserver,
      getValidationState,
			state,
      fetchCourseStudent,
      fetchSurvey,
      fetchSurveyQuestions,
			loading,
      courseId,
      materialStatus,
      sortBy,
      sortByOptions,
      options,
      answers,
      onSubmit,
    }
  },
  data() {
    return {
      copied: false,
      required
    }
  },
  methods: {
    checkFormValidity() {
      const valid = this.$refs.form.checkValidity()
      return valid
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      if (!this.checkFormValidity()) {
        return
      }
      this.onSubmit();
      this.$nextTick(() => {
        this.$bvModal.hide('survey')
      })
    },
    onCopy() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: 'Text copied',
          icon: 'BellIcon',
        },
      })
      this.copied = true;
    },
    onError() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: 'Failed to copy texts!',
          icon: 'BellIcon',
        },
      })
    },
  },
}
</script>
<style lang="scss">
.materials {
  max-height: 500px;
  overflow: auto;
}
p.survey-paragraph {
  line-height: 2;
  max-width: 1200px;
  margin-left: auto;
  font-size: 16px;
}
ol.survey-ol {
  line-height: 2;
  padding-right: 20px;
  li {
    font-size: 16px;
  }
}
.course-header {
  background: rgba(241, 248, 255, 0.62);
  display: flex;
  justify-content: center;
  flex-grow: 1;
  flex-direction: column;
  gap: 16px;
  padding: 0 20px;
  background-repeat: repeat;
  height: 200px;
  margin: 0px;
  position: relative;
  h1 {
    color: #fff;
    margin-bottom: 0px;
    z-index: 1;
  }
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, .5);
  }
  p {
    max-width: 1100px;
    color: #fff;
    z-index: 1;
    font-size: 15px;
    margin: 0;
  }
}
h6 {
  svg {
    max-width: 20px;
    height: 20px;
  }
}
.content-link-material {
  padding: 16px 30px;
  border: 0px;
  &:not(:last-child) {
    border-bottom-width: 1px;
  }
  h6 {
    font-weight: 600;
    margin-bottom: 0px;
    font-size: 14px;
    svg {
      max-width: 15px;
      height: 15px;
    }
  }
}
.clipboard-block {
    display: flex;
    justify-content: center;
    align-content: center;
    position: relative;
    width: 100%;
    border-radius: 5px;
    padding: 8px;
    .clipboard {
      width: 100%;
      position: relative;
      display: flex;
      justify-content: center;
      align-content: center;
      &:hover {
        &:after{
          display: -webkit-flex;
          display: flex;
          -webkit-justify-content: center;
          justify-content: center;
          background: rgba($color: #444, $alpha: 0.75);
          border-radius: 8px;
          color: #fff;
          content: attr(title);
          font-size: 12px;
          padding: 8px;
          position: absolute;
          bottom: -34px;
          right: 0px;
        }
        &:before {
          border: solid;
          border-color: rgba($color: #444, $alpha: 0.75) transparent;
          border-width: 0px 4px 8px 4px;
          content: "";
          bottom: 0px;
          right: 10px;
          position: absolute;
        }
      }
    }
  }
  .copy-input {
    width: 100%;
    cursor: pointer;
    background-color: transparent !important;
    border: none;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
    padding: 0;
    padding-right: 6px;
    &:focus {
      outline:none;
      box-shadow: none;
    }
  }
  .copy-btn {
    background-color: transparent;
    font-size: 16px;
    border:none;
    color:#6c6c6c;
    transition: all .4s;
    display: inline;
    position: relative;
    &:focus {
      outline:none;
    }
    &:hover {
      transform: scale(1.1);
      color:#1a1a1a;
      cursor:pointer;
    }
  }
</style>