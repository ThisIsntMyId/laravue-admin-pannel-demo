<template>
  <div>
    <el-container>
      <el-header>
        <h1>{{product.name}}</h1>
      </el-header>
      <el-main>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Brand</h2>
          </el-col>
          <el-col :span="16">{{product.brand}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Description</h2>
          </el-col>
          <el-col :span="16">{{product.description}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Price</h2>
          </el-col>
          <el-col :span="16">{{product.price}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Ratings</h2>
          </el-col>
          <el-col :span="16">{{product.ratings}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Category</h2>
          </el-col>
          <el-col :span="16">{{product.category}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Quantity</h2>
          </el-col>
          <el-col :span="16">{{product.quantity}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Date Of Purchase</h2>
          </el-col>
          <el-col :span="16">{{product.date_of_purchase}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Condition</h2>
          </el-col>
          <el-col :span="16">{{product.condition}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Reviewed</h2>
          </el-col>
          <el-col :span="16">{{product.reviewed_text}}</el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="8">
            <h2>Available In</h2>
          </el-col>
          <el-col :span="16">{{product.available_in}}</el-col>
        </el-row>
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  name: 'ProductInfo',
  props: ['product'],
  data() {
    return {};
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
.el-row {
  display: flex;
  align-items: center;
}
</style>
