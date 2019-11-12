<template>
  <div class="app-container documentation-container">
    <h1>OMG you are in alpha now.</h1>
    <h3>Man you are awesome!!!</h3>
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'Documentation',
};
</script>

<style lang="scss" scoped>
.documentation-container {
  margin: 50px;
  display: flex;
  flex-wrap: wrap;
}
</style>
