<template>
  <section class="login">
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row w-100 flex-grow">
            <div class="col-xl-4 col-lg-6 mx-auto">
              <div class="auth-form-dark text-center p-5">
                <div class="brand-logo">
                  <img class="" src="../../../assets/images/trading-icon-4.jpg" />
                </div>
                <h4></h4>
                <h6 class="font-weight-light"></h6>
                <form action="#" class="pt-3" @submit.prevent="submit">
                  <div class="form-group">
                    <input
                      v-model="form.email"
                      type="email"
                      class="form-control form-control-lg"
                      id="exampleInputEmail1"
                      placeholder="Username"
                    />
                  </div>
                  <div class="form-group">
                    <input
                      v-model="form.password"
                      type="password"
                      class="form-control form-control-lg"
                      id="exampleInputPassword1"
                      placeholder="Password"
                    />
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn router-link-active" type="submit">Sign in</button>
                  </div>
                  <div
                    class="
                      my-2
                      d-flex
                      justify-content-between
                      align-items-center
                    "
                  >
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
  </section>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "login",
  data() {
    return {
      userHasEmail: false,
      form: {
        email: "",
        password: "",
      },
    };
  },

  methods: {
    ...mapActions({
      signIn: "signIn",
    }),

    async submit() {
      await this.signIn(this.form);
      this.$router.push({ name: "dashboard" });
    },
  },
};
</script>
