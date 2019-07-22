<template>
  <div class="login-container">
    <header class="navbar navbar-default navbar-demo navbar-fixed-top eslint-nav" id="top" role="banner">
      <div class="container">
        <a href="/" class="navbar-brand">
          <span class="svg-container-block">
            <svg-icon id="humbl-black-logo" icon-class="humbl-black" />
          </span>
        </a>
        <ul class="navbar-nav flex-row ml-md-auto d-md-flex">
          <li><el-button :loading="loading" type="primary" @click.native.prevent="handleLogin">SIGN IN</el-button></li>
          <li><el-button :loading="loading" type="primary">SIGN UP</el-button></li>
        </ul>
      </div><!-- /.container -->
    </header>
    <el-form ref="registerForm" :model="registerForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
      <div class="logo-container">
        <span class="svg-container-block">
          <svg-icon id="humbl-all-caps-logo" icon-class="humbl-all-caps" />
        </span>
      </div>
      <h3 class="title">{{ $t('register.title') }}</h3>
      <el-form-item prop="email">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input v-model="registerForm.email" name="email" type="text" auto-complete="on" :placeholder="$t('register.email')" />
      </el-form-item>
      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :type="pwdType"
          v-model="registerForm.password"
          name="password"
          auto-complete="on"
          placeholder="password"
          @keyup.enter.native="handleRegister" />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleRegister">
          REGISTER
        </el-button>
      </el-form-item>
      <div class="singup_div">
        <div class="or_text_div">
          <span class="or_text">OR</span>
        </div>
      </div>
      <div id="customGoogleBtn" class="customGPlusSignIn">
        <span class="icon"></span>
        <span class="buttonText">Sign in with Google</span>
      </div>
    </el-form>
  </div>
</template>

<script>
import { validEmail } from '@/utils/validate';

export default {
  name: 'Register',
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('Please enter the correct email'));
      } else {
        callback();
      }
    };
    const validatePass = (rule, value, callback) => {
      if (value.length < 4) {
        callback(new Error('Password cannot be less than 4 digits'));
      } else {
        callback();
      }
    };
    return {
      registerForm: {
        email: '',
        password: '',
      },
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePass }],
      },
      loading: false,
      pwdType: 'password',
      redirect: undefined,
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true,
    },
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
    },
    handleLogin() {
      this.$router.push({ path: '/login' });
    },
    handleRegister() {
      this.$refs.registerForm.validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/register', this.registerForm)
            .then(() => {
              this.$router.push({ path: this.redirect || '/' });
              this.loading = false;
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
@import "~bootstrap/scss/bootstrap";
$bg:#2d3a4b;
$light_gray:#000;

/* reset element-ui css */
.login-container {
  overflow: scroll;
  #remember_div {
    background: transparent;
    button {
      color: #22ade4;
      width: auto;
      background: transparent;
      border: transparent;
    }
  }
  .customGPlusSignIn {
    display: inline-block;
    background: white;
    color: #444;
    width: 100%;
    text-align: center;
    border-radius: 5px;
    border: thin solid #888;
    box-shadow: 1px 1px 1px grey;
    white-space: nowrap;

    &:hover {
      cursor: pointer;
    }
    span.label {
      font-family: serif;
      font-weight: normal;
    }
    span.icon {
      background: url('/identity/sign-in/g-normal.png') transparent 5px 50% no-repeat;
      display: inline-block;
      vertical-align: middle;
      width: 42px;
      height: 42px;
    }
    span.buttonText {
      display: inline-block;
      vertical-align: middle;
      padding-left: 42px;
      padding-right: 42px;
      font-size: 14px;
      font-weight: bold;
    }
  }

  .or_text_div {
    overflow:  hidden !important;
  }
  .or_text {
    position: relative;
    padding: 20px;
    &::after {
      border-bottom-width: var(--border-rule-border-width, 1px) !important;
      border-bottom-color: #e4e4e4 !important;
      content: "" !important;
      position: absolute !important;
      border-bottom-style: solid !important;
      top: 50% !important;
      left: 100% !important;
      width: 5000px !important;
    }
    &::before {
      border-bottom-width: var(--border-rule-border-width, 1px) !important;
      border-bottom-color: #e4e4e4 !important;
      content: "" !important;
      position: absolute !important;
      border-bottom-style: solid !important;
      top: 50% !important;
      right: 100% !important;
      width: 5000px !important;
    }
  }
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;
    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      &:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: #fff !important;
      }
    }
  }
  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
$bg:#fff;
$dark_gray:#889aa4;
$light_gray:#eee;
$main_color: #22ade4;
.login-container {
  position: fixed;
  height: 100%;
  width: 100%;
  background-color: $bg;
  .navbar {
    border-bottom: 1px solid #f9f9f9;
  }
  .navbar-nav li {
    padding: 10px;
    button {
      text-decoration: none;
      color: #000;
      background: transparent;
      border-color: transparent;
      &:hover {
        background: $main_color;
        border-radius: 16px;
        cursor: pointer;
        button {
          color: #fff;
        }
      }
    }
  }
  #humbl-black-logo {
    width: 100px;
    height: 100px;
  }
  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 520px;
    max-width: 100%;
    padding: 35px 50px 15px 50px;
    margin: 120px auto;
    border: 1px solid #eee;
    button {
      background-color: $main_color;
      border-color: $main_color;
      span {
        font-size: 30px;
        font-weight: bold;
      }
    }
    #humbl-all-caps-logo {
      width: 100px;
      height: auto;
    }
    .logo-container {
      text-align: center;
    }
  }
  .singup_div {
    font-size: 14px;
    color: #000;
    margin-bottom: 10px;
    text-align: center;
    button {
      color: #22ade4;
      width: auto;
      background: transparent;
      border: transparent;
    }
    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }
  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }
  .svg-container-block {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
  }
  .title {
    font-size: 26px;
    font-weight: 400;
    color: #000;
    margin: 0px auto 40px auto;
    text-align: center;
    font-weight: bold;
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
  .set-language {
    color: #fff;
    position: absolute;
    top: 40px;
    right: 35px;
  }
}
</style>
