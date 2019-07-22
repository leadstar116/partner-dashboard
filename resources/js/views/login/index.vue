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
          <li><el-button :loading="loading" type="primary" @click.native.prevent="handleRegister">SIGN UP</el-button></li>
        </ul>
      </div><!-- /.container -->
    </header>
    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
      <div class="logo-container">
        <span class="svg-container-block">
          <svg-icon id="humbl-all-caps-logo" icon-class="humbl-all-caps" />
        </span>
      </div>
      <h3 class="title">{{ $t('login.title') }}</h3>
      <el-form-item prop="email">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input v-model="loginForm.email" name="email" type="text" auto-complete="on" :placeholder="$t('login.email')" />
      </el-form-item>
      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :type="pwdType"
          v-model="loginForm.password"
          name="password"
          auto-complete="on"
          placeholder="password"
          @keyup.enter.native="handleLogin" />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>
      <el-form-item prop="Remember" id="remember_div">
        <el-checkbox label="Remember Me" name="remember"/>
        <el-button :loading="loading" type="primary" class="pull-right" @click.native.prevent="handleForget">
          Forgot password?
        </el-button>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleLogin">
          SIGN IN
        </el-button>
      </el-form-item>
      <div class="singup_div">
        <span>Don't have an account?
          <el-button :loading="loading" type="primary" @click.native.prevent="handleRegister">
            Register
          </el-button>
        </span>
      </div>
    </el-form>
  </div>
</template>

<script>
import { validEmail } from '@/utils/validate';

export default {
  name: 'Login',
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
      loginForm: {
        email: 'admin@laravue.dev',
        password: 'laravue',
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
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/login', this.loginForm)
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
    handleForget() {
      this.$router.push({ path: '/forget-password' });
    },
    handleRegister() {
      this.$router.push({ path: '/register' });
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
