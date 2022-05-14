<template>
  <div class="container">
    <nav class="fixed-top container navbar navbar-expand-lg navbar-light bg-light">

      <a class="navbar-brand" href="#">APP</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent" ref="menuCollapse">
        <div class="navbar-nav w-100">
          <router-link to="/users" class="nav-item nav-link">Список пользователей</router-link>
          <router-link to="/users/create" class="nav-item nav-link">Новый пользователь</router-link>
          <a href="/auth/logout" class="btn btn-outline-danger ms-lg-auto">Выход</a>
        </div>
      </div>
    </nav>
    <router-view class="main overflow-auto"> </router-view>
  </div>
</template>

<script>
import { Collapse } from 'bootstrap'
export default {
  mounted() {
    new Collapse(this.$refs.menuCollapse, { toggle: false })
  },
}
</script>