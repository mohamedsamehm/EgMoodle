<template>
  <b-card no-body>
    <div class="flex d-flex align-items-center justify-content-center loader-area" v-if="state.loading"  style="min-height: 450px;" >
      <b-spinner label="Loading..."/>
    </div>
    <b-table
      v-else
      class="position-relative"
      :items="state.assignments_grades"
      responsive
      :fields="tableColumnsGrades"
      primary-key="id"
      show-empty
      striped
      empty-text="No matching records found"
    >
      <template #cell(updated_at)="data">
        {{ data.value | formatNow }}
      </template>
      <template #cell(path)="data">
        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="outline-primary" :href="data.value" size="sm" class="text-nowrap">
          <font-awesome-icon icon="fa-solid fa-file-pdf" />
          Download File
        </b-button>
      </template>
      <template #cell(grade)="data">
        <validation-observer ref="refFormObserver" #default="{ handleSubmit }" v-if="editGrade">
          <b-form @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="resetForm">
            <b-button-group>
              <validation-provider #default="validationContext" rules="required" name="Grade">
                <b-form-group class="m-0">
                  <b-form-input
                    style="width: 80px" size="sm"
                    v-model="state.assignments_grades[0].grade"
                    :state="getValidationState(validationContext)"
                    placeholder="Grade"
                  />
                </b-form-group>
              </validation-provider>
              <b-button type="submit" size="sm" v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="flat-success px-0">
                <feather-icon icon="CheckIcon" />
              </b-button>
            </b-button-group>
          </b-form>
        </validation-observer>
        <b-button-group v-else>
          <small class="d-flex mr-1 align-items-center">{{data.value}}</small>
          <b-button size="sm" v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="flat-primary px-0" @click="editGrade = true">
            <feather-icon icon="EditIcon" />
          </b-button>
        </b-button-group>
      </template>
    </b-table>
  </b-card>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
	BSpinner,
  BButton,
  BTable,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormInput,
  BFormGroup,
  BButtonGroup,
  BBadge,
  BForm,
} from 'bootstrap-vue';
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref, onUnmounted } from '@vue/composition-api';
import { required } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation';
import Ripple from 'vue-ripple-directive';
import vSelect from 'vue-select';
import store from '@/store';
import router from '@/router';
import useAssignments from '../../useAssignments';
import useAssignmentsModule from '../../useAssignmentsModule';
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';
export default {
  components: {
    BCard,
    BRow,
    BCol,
    BSpinner,
    BButton,
    BTable,
    BDropdown,
    BDropdownItem,
    BPagination,
    BFormInput,
    BFormGroup,
    BButtonGroup,
    BBadge,
    BForm,
    ValidationProvider,
    ValidationObserver,
    vSelect
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
    const blankGradeData = {
      grade: '',
      id: '',
    }
    const editGrade = ref(false)
    const toast = useToast()
    const loading = ref(false)
    const GradeData = ref(JSON.parse(JSON.stringify(blankGradeData)));
    const resetGradeData = () => {
      GradeData.value = JSON.parse(JSON.stringify(blankGradeData))
    }
    const onSubmit = () => {
      GradeData.value.id = state.assignments_grades[0].student_assignment_id;
      GradeData.value.grade = state.assignments_grades[0].grade;
			loading.value = true;
      store.dispatch('assignments/updateGrade', GradeData.value).then((res) => {
				loading.value = false;
				if(res.data.status == 200) {
          showMessage('Grade updated successfully', 'success');
          editGrade.value = false;
				} else {
          showMessage('Error', 'danger');
          console.log(res);
        }
      })
    }
    const showMessage = (title, text, variant) => {
			toast({
				component: ToastificationContent,
				props: {
					title: title,
					icon: 'BellIcon',
					variant: variant,
				},
			})	
		}		

    const { refFormObserver, getValidationState, resetForm } = formValidation(resetGradeData)
    const {
      fetchMarks,
      tableColumnsGrades,
      state,
    } = useAssignments(props, { emit })

		fetchMarks({ id: router.currentRoute.params.id });

    return {
      refFormObserver,
      getValidationState,
      onSubmit,
      resetForm,
			state,
			loading,
      fetchMarks,
      tableColumnsGrades,
      GradeData,
      editGrade,
    }
  },
  data() {
    return {
      required
    }
  },
}
</script>
<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-sweetalert.scss';
</style>
