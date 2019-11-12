<template>
  <div>
    <h1>Add Product</h1>
    <Form productinfo></Form>
  </div>
</template>

<script>
import Form from './components/Form';

export default {
  name: 'NewProduct',
  components: {
    Form,
  },
  data() {
    return {
      category: [],
    };
  },
  created() {},
  methods: {},
};
</script>

<style lang="scss" scoped>
</style>
