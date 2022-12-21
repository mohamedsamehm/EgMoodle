<template>
  <div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner">

      <!-- Login v1 -->
      <b-card class="mb-0">
        <b-link class="brand-logo">
          <img src="@/assets/images/logo/logo.png" alt="Logo">
        </b-link>

        <b-card-title class="mb-1 text-center">
          Welcome to EgMoodle!
        </b-card-title>
        <b-card-text class="mb-2 text-center">
          Please sign-in to your account.
        </b-card-text>

        <!-- form -->
        <validation-observer
          ref="loginForm"
          #default="{invalid}"
        >
          <b-form
            class="auth-login-form mt-2"
						@submit.prevent="login"
          >
            <b-form-group
              label-for="username"
              label="Username"
            >
              <validation-provider
                #default="{ errors }"
                name="Username"
                rules="required"
              >
                <b-form-input
                  id="username"
                  v-model="username"
                  name="login-username"
                  :state="errors.length > 0 ? false : null"
                  placeholder="john"
                  autofocus
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="password">Password</label>
								<!--
                <b-link :to="{name:'forgot-password-v1'}">
                  <small>Forgot Password?</small>
                </b-link>
								-->
              </div>
              <validation-provider
                #default="{ errors }"
                name="Password"
                rules="required"
              >
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid': null"
                >
                  <b-form-input
                    id="password"
                    v-model="password"
                    :type="passwordFieldType"
                    class="form-control-merge"
                    :state="errors.length > 0 ? false:null"
                    name="login-password"
                    placeholder="Password"
                  />

                  <b-input-group-append is-text>
                    <feather-icon
                      class="cursor-pointer"
                      :icon="passwordToggleIcon"
                      @click="togglePasswordVisibility"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>

            <b-form-group>
              <b-form-checkbox
                id="remember-me"
                v-model="status"
                name="checkbox-1"
              >
                Remember Me
              </b-form-checkbox>
            </b-form-group>

            <b-button
              variant="primary"
              type="submit"
              block
              :disabled="invalid"
            >
              Sign in
            </b-button>
          </b-form>
        </validation-observer>

				<!--
        <b-card-text class="text-center mt-2">
          <span>New on our platform? </span>
          <b-link :to="{name:'register-v1'}">
            <span>Create an account</span>
          </b-link>
        </b-card-text>

        <div class="divider my-2">
          <div class="divider-text">
            or
          </div>
        </div>

        <div class="auth-footer-btn d-flex justify-content-center">
          <b-button
            href="javascript:void(0)"
            variant="facebook"
          >
            <feather-icon icon="FacebookIcon" />
          </b-button>
          <b-button
            href="javascript:void(0)"
            variant="twitter"
          >
            <feather-icon icon="TwitterIcon" />
          </b-button>
          <b-button
            href="javascript:void(0)"
            variant="google"
          >
            <feather-icon icon="MailIcon" />
          </b-button>
          <b-button
            href="javascript:void(0)"
            variant="github"
          >
            <feather-icon icon="GithubIcon" />
          </b-button>
        </div>
				-->

      </b-card>
      <!-- /Login v1 -->
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BButton,
  BForm,
  BFormInput,
  BFormGroup,
  BCard,
  BLink,
  BCardTitle,
  BCardText,
  BInputGroup,
  BInputGroupAppend,
  BFormCheckbox,
} from 'bootstrap-vue';
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import VuexyLogo from '@core/layouts/components/Logo.vue';
import { required, email } from '@validations';
import { togglePasswordVisibility } from '@core/mixins/ui/forms';
import useJwt from '@/auth/jwt/useJwt';

export default {
  components: {
    // BSV
    BButton,
    BForm,
    BFormInput,
    BFormGroup,
    BCard,
    BCardTitle,
    BLink,
    VuexyLogo,
    BCardText,
    BInputGroup,
    BInputGroupAppend,
    BFormCheckbox,
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      username: '',
      password: '',
      status: '',
      required,
			errors: null,
			processing: false,			
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
	methods: {
    login() {
      this.$refs.loginForm.validate().then(success => {
				console.log('success ', success);

        if (success) {
          useJwt
            .login({
              username: this.username,
              password: this.password,
            })
            .then(res => {
							console.log('res ', res);

							if(res.data.status == 200) {
								useJwt.setToken(res.data.accessToken);
								useJwt.setRefreshToken(res.data.refreshToken);
								localStorage.setItem('userData', JSON.stringify(res.data.userData));
								this.$ability.update(res.data.userData.ability);
                this.$router.push({ name: 'dashboard' })
                setTimeout(() => {
                  location.reload()
                }, 100);
							}
							else if(res.data.status == 400) {
								this.$toast({
									component: ToastificationContent,
									props: {
										title: res.data.msg,
										icon: 'BellIcon',
										variant: 'danger',
									},
								})
							}
            })
            .catch(error => {
							console.log('error', error)
							localStorage.setItem('userData', null)
							localStorage.setToken('')
              // this.$refs.loginForm.setErrors(error.response.data.error)
            })
        }
				else {
					localStorage.setItem('userData', null)
					localStorage.setToken('')
				}
      })
    },		
		// async login() {
		// 	this.processing = true;
		// 	this.errors = null
		// 	try {
		// 		useJwt.login({ 'username': this.username, 'password': this.password })
		// 		// await this.$store.dispatch('login', { 'username': this.username, 'password': this.password })
		// 		// this.$router.push({ name: 'dashboard' })
		// 	}
		// 	catch (e) {
		// 		this.errors = e.data
		// 	};
		// 	this.processing = false;
		// }
	},	
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-auth.scss';
</style>
