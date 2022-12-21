<template>
  <div>
    <b-card no-body class="pt-1">
      <b-card-body>
        <b-row>
          <b-col md="8">
            <small class="mb-1 d-block">
              <span class="font-weight-bolder text-dark">Added by:</span>
              <span class="text-dark" v-if="state.theAssignments.slug == 'professor'">Prof.{{state.theAssignments.fullName}}</span>
              <span class="text-dark" v-if="state.theAssignments.slug == 'engineer'">Eng.{{state.theAssignments.fullName}}</span>
              </small>
            <h1 class="text-dark mb-2 text-nowrap font-weight-bolder">{{state.theAssignments.title}}</h1>
            <small class="mb-1 d-block"><span class="font-weight-bolder text-secondary">Opened:</span> {{state.theAssignments.created_at | formatDate}}</small>
            <small class="mb-2 d-block"><span class="font-weight-bolder text-secondary">Due:</span> {{state.theAssignments.deadline | formatNow}}</small>
          </b-col>
          <b-col class="ml-auto text-md-right mb-2" md="4">
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" class="bg-success bg-darken-2" :href="state.theAssignments.file_path" target="_blank">
              <font-awesome-icon icon="fa-solid fa-file-pdf" />
              Download File
            </b-button>
          </b-col>
        </b-row>
        <b-table-simple stacked bordered responsive>
          <b-tbody>
            <b-tr v-if="state.submittedAssignments">
              <b-td stacked-heading="File Name"><b-button :href="state.submittedAssignments.path" target="_blank" variant="flat-primary p-0">{{state.submittedAssignments.name}}</b-button></b-td>
            </b-tr>
            <b-tr>
              <b-td stacked-heading="Submission status" v-if="state.submittedAssignments && state.submittedAssignments.grade">Graded</b-td>
              <b-td stacked-heading="Submission status" v-else-if="state.submittedAssignments">Submitted for grading</b-td>
              <b-td stacked-heading="Submission status" v-else>No attempt</b-td>
            </b-tr>
            <b-tr>
              <b-td stacked-heading="Grading Status" v-if="state.submittedAssignments && state.submittedAssignments.grade">{{state.submittedAssignments.grade}}</b-td>
              <b-td stacked-heading="Grading Status" v-else>Not Graded</b-td>
            </b-tr>
            <b-tr>
              <b-td stacked-heading="Time remaining" v-if="state.submittedAssignments">Assignment was submitted {{state.submittedAssignments.created_at  | formatDate}}</b-td>
              <b-td stacked-heading="Time remaining" v-else>{{state.theAssignments.deadline | formatNow}}</b-td>
            </b-tr>
            <b-tr>
              <b-td stacked-heading="Last modified" v-if="state.theAssignments.updated_at">{{state.theAssignments.updated_at | formatDate}}</b-td>
              <b-td stacked-heading="Last modified" v-else-if="state.theAssignments.created_at">{{state.theAssignments.created_at | formatDate}}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </b-card-body>
      <b-card-footer class="text-center" v-if="!state.canSubmit">
        <h6 class="text-warning">Submission was closed from {{state.theAssignments.deadline | formatDay}}</h6>
      </b-card-footer>
      <b-card-footer class="text-center" v-else-if="state.submittedAssignments">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="gradient-primary" @click="goto('dropzone')">Edit Submission</b-button>
        <h5 class="mt-2">You can still make changes to your submission.</h5>
      </b-card-footer>
      <b-card-footer class="text-center" v-else>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="gradient-primary" @click="goto('dropzone')">Add Submission</b-button>
        <h5 class="mt-2">You have not made a submission yet.</h5>
      </b-card-footer>
    </b-card>
    <b-card ref="dropzone" style="display: none" no-body id="dropzone">
      <b-card-body>
        <div class="parent" v-if="uploading">
          <div class="progress-wrapper mb-1">
            <b-card-text class="mb-0">
              {{progressText}}
            </b-card-text>
            <b-progress
              v-model="progressVal"
              max="100"
            />
          </div>
        </div>
        <div class="dropzone">
          <div @click="$refs.refInputEl.$el.click()" class="dz-message">Drop files here or click to upload.</div>
          <b-form-file
            ref="refInputEl"
            :hidden="true"
            v-model="file"
            plain
            @change="onFileChange"
          />
        </div>
        <b-card-text class="mt-1 text-grey">
          {{ file ? file.name : '' }}
        </b-card-text>
      </b-card-body>
      <b-card-footer>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success" type="submit" @click="onUpdate" v-if="state.submittedAssignments">Save Changes</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success" type="submit" @click="onSubmit" v-else>Save Changes</b-button>
      </b-card-footer>
    </b-card>
  </div>
</template>
<script>
import moment from 'moment'
import Ripple from 'vue-ripple-directive'
import store from '@/store'
import router from '@/router'
import useAssignments from '../../useAssignments';
import useAssignmentsModule from '../../useAssignmentsModule';
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
  BCardBody,
  BCardFooter,
  BTableSimple,
  BTh,
  BTd,
  BTr,
  BThead,
  BTbody,
} from 'bootstrap-vue'
export default {
  components: {
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
    BCardFooter,
    BTableSimple,
    BTh,
    BTd,
    BTr,
    BTbody,
    BThead,
  },
  directives: {
    Ripple,
  },
  methods: {
    goto(refName) {
      var element = this.$refs[refName];
      element.style.display = "block";
      var top = element.offsetTop;
      window.scrollTo(0, top);
    },
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
    const file = ref(null);
    const progressVal = ref(0)
    const progressText = ref('')
    const uploading = ref(false)
    const config = {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    };
    const onFileChange = (e) => {
      progressVal.value = 0;
        progressText.value = 'Uploading... ' + progressVal.value + '%';
      setTimeout(() => {
        for (let index = 1; index <= 10; index++) {
          setTimeout(() => {
            uploading.value = true;
            progressVal.value = parseInt( Math.round( (index * 10 ) ) );
            progressText.value = 'Uploading... ' + progressVal.value + '%';
          }, 600);
          setTimeout(() => {
            if(progressVal.value < 100) {
              progressText.value = 'Uploading... ' + progressVal.value + '%';
            } else {
              progressText.value = 'Uploaded';
            }
          }, 2000);
        }
      }, 50);
    };
    const onSubmit = () => {
      // Set formData
      const formData = new FormData()
      formData.append('file', file.value)
      formData.append('assignment_id', state.theAssignments.assignment_id);
			loading.value = true;
      store.dispatch('assignments/storeSubmittedAssignment', formData, config).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Assignment submited successfully', 'success');
          fetchMyAssignment({ id: router.currentRoute.params.id });
          document.getElementById("dropzone").style.display = 'none';
          progressVal.value = 0;
          uploading.value = false;
          file.value = null;
				}
      })
    }
    const onUpdate = () => {
      // Set formData
      const formData = new FormData()
      formData.append('file', file.value)
      formData.append('id', state.submittedAssignments.id);
			loading.value = true;
      store.dispatch('assignments/updateSubmittedAssignment', formData, config).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
					emit('refetch-data')
          showMessage('Assignment updated successfully', 'success');
          fetchMyAssignment({ id: router.currentRoute.params.id });
          document.getElementById("dropzone").style.display = 'none';
          progressVal.value = 0;
          uploading.value = false;
          file.value = null;
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

    const {
			state,
      fetchMyAssignment,
    } = useAssignments(props, { emit })
    
		fetchMyAssignment({ id: router.currentRoute.params.id });

    return {
			onSubmit,
			state,
      fetchMyAssignment,
			loading,
      onFileChange,
      progressVal,
      progressText,
      file,
      uploading,
      onUpdate,
    }
  },
}
</script>
<style lang="scss">
html {
  scroll-behavior: smooth;
}
.table.b-table.b-table-stacked > tbody > tr > [data-label]::before {
  text-align: left !important;
}
.dropzone {
  border: 2px dashed #7367f0;
  min-height: 350px;
  position: relative;
}
.dz-message {
  align-items: center;
  color: #7367f0;
  display: flex;
  font-size: 2rem;
  height: 100%;
  justify-content: center;
  left: 0;
  margin: 0;
  position: absolute;
  top: 0;
  width: 100%;
  text-align: center;
  cursor: pointer;
  &::before {
    -webkit-font-smoothing: antialiased;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237367f0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-download'%3E%3Cpath d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3'/%3E%3C/svg%3E");
    color: #7367f0;
    content: "";
    display: inline-block;
    font-size: 80px;
    font-weight: 400;
    height: 80px;
    line-height: 1;
    position: absolute;
    text-indent: 0;
    top: 14rem;
    width: 80px;
    z-index: 2;
  }
}
</style>