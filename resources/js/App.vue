<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="navbar-collapse">
        <div class="navbar-nav w-100">
          <router-link to="/users" class="nav-item nav-link">Список пользователей</router-link>
          <router-link to="/users/create" class="nav-item nav-link">Новый пользователь</router-link>
          <a href="/auth/logout" class="btn btn-outline-danger ms-lg-auto">Выход</a>
        </div>
      </div>
    </nav>
    <router-view class="overflow-auto"> </router-view>
  </div>
</template>

<script>
export default {}
</script>