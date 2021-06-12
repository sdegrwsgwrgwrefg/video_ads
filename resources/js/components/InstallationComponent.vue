<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-6">
                <h2>App Settings</h2>
                <div class="form-group">
                    <label for="appName">App Name*</label>
                    <input type="text" v-model="appName" id="appName" name="appName" class="form-control" :class="{ 'is-invalid': submitted && $v.appName.$error }" />
                    <div v-if="submitted && !$v.appName.required" class="invalid-feedback">App Name is required</div>
                </div>
                <div class="form-group">
                    <label for="databaseName">Database Name*</label>
                    <input type="text" v-model="databaseName" id="databaseName" name="databaseName" class="form-control" :class="{ 'is-invalid': submitted && $v.databaseName.$error }" />
                    <div v-if="submitted && !$v.databaseName.required" class="invalid-feedback">Database Name is required</div>
                </div>
                <div class="form-group">
                    <label for="databaseUser">Database User*</label>
                    <input type="text" v-model="databaseUser" id="databaseUser" name="databaseUser" class="form-control" :class="{ 'is-invalid': submitted && $v.databaseUser.$error }" />
                    <div v-if="submitted && !$v.databaseUser.required" class="invalid-feedback">Database User is required</div>
                </div>
                <div class="form-group">
                    <label for="databasePassword">Database Password*</label>
                    <input type="text" v-model="databasePassword" id="databasePassword" name="databasePassword" class="form-control" :class="{ 'is-invalid': submitted && $v.databasePassword.$error }" />
                    <div v-if="submitted && !$v.databasePassword.required" class="invalid-feedback">Database Password is required</div>
                </div>
                <h2>Admin Settings</h2>
                <div class="form-group">
                    <label for="firstname">First Name*</label>
                    <input type="text" v-model="firstname" id="firstname" name="firstname" class="form-control" :class="{ 'is-invalid': submitted && $v.firstname.$error }" />
                    <div v-if="submitted && !$v.firstname.required" class="invalid-feedback">First Name is required</div>
                </div>
                <div class="form-group">
                    <label for="lastname">LastName*</label>
                    <input type="text" v-model="lastname" id="lastname" name="lastname" class="form-control" :class="{ 'is-invalid': submitted && $v.lastname.$error }" />
                    <div v-if="submitted && !$v.lastname.required" class="invalid-feedback">Last Name is required</div>
                </div>
                <div class="form-group">
                    <label for="username">Username*</label>
                    <input type="text" v-model="username" id="username" name="username" class="form-control" :class="{ 'is-invalid': submitted && $v.username.$error }" />
                    <div v-if="submitted && !$v.username.required" class="invalid-feedback">Username is required</div>
                </div>
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" v-model="email" id="email" name="email" class="form-control" :class="{ 'is-invalid': submitted && $v.email.$error }" />
                    <div v-if="submitted && $v.email.$error" class="invalid-feedback">
                        <span v-if="!$v.email.required">Email is required</span>
                        <span v-if="!$v.email.email">Email is invalid</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password*</label>
                    <input type="password" v-model="password" id="password" name="password" class="form-control" :class="{ 'is-invalid': submitted && $v.password.$error }" />
                    <div v-if="submitted && $v.password.$error" class="invalid-feedback">
                        <span v-if="!$v.password.required">Password is required</span>
                        <span v-if="!$v.password.minLength">Password must be at least 6 characters</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password*</label>
                    <input type="password" v-model="confirmPassword" id="confirmPassword" name="confirmPassword" class="form-control" :class="{ 'is-invalid': submitted && $v.confirmPassword.$error }" />
                    <div v-if="submitted && $v.confirmPassword.$error" class="invalid-feedback">
                        <span v-if="!$v.confirmPassword.required">Password is required</span>
                        <span v-if="!$v.confirmPassword.minLength">Password must be at least 6 characters</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone*</label>
                    <input type="text" v-model="phone" id="phone" name="phone" class="form-control" :class="{ 'is-invalid': submitted && $v.phone.$error }" />
                    <div v-if="submitted && !$v.phone.required" class="invalid-feedback">Phone is required</div>
                </div>
                <div class="form-group">
                    <button @click="submit" class="btn btn-dark">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { required, email, minLength, maxLength, sameAs } from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                appName: '',
                databaseName: '',
                databaseUser: '',
                databasePassword: '',
                firstname: '',
                lastname: '',
                username: '',
                email: '',
                password: '',
                confirmPassword: '',
                phone: '',
                submitted: false,
            } 
        },
        validations: {
            appName: { required },
            databaseName: { required },
            databaseUser: { required },
            databasePassword: { required },
            firstname: { required },
            lastname: { required },
            username: { required },
            email: { required },
            password: { required, minLength: minLength(8), maxLength: maxLength(18) },
            confirmPassword: { required, sameAsPassword: sameAs('password') },
            phone: { required },
        },
        mounted() {
        },
        methods: {
            validationStatus: function(validation) {
                console.log(validation);
                return typeof validation != "undefined" ? validation.$error : false;
            },
            submit() {
                this.submitted = true;
                console.log(this.formData);
                console.log(this.$v);
                // stop here if form is invalid
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                console.log(this.appName);
                axios({
                    method: 'post',
                    url: '/install',
                    data: {
                        appName: this.appName,
                        databaseName: this.databaseName,
                        databaseUser: this.databaseUser,
                        databasePassword: this.databasePassword,
                        firstname: this.firstname,
                        lastname: this.lastname,
                        username: this.username,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.confirmPassword,
                        phone: this.phone,
                    }
                }).then((response) => {
                    window.location.href = '/admin_login';
                }, (error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
