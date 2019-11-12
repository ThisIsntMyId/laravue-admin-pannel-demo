<template>
  <div class>
    <el-form
      ref="form"
      :model="product"
      :editable="editable"
      label-width="120px"
      style="padding: 0 2rem;"
    >
      <el-form-item label="Product Name">
        <el-input v-model="product.name"></el-input>
      </el-form-item>
      <el-form-item label="Product Brand">
        <el-input v-model="product.brand"></el-input>
      </el-form-item>
      <el-form-item label="Description">
        <el-input type="textarea" v-model="product.description"></el-input>
      </el-form-item>
      <el-form-item label="Price">
        <el-input v-model="product.price"></el-input>
      </el-form-item>
      <el-form-item label="Ratings">
        <el-rate v-model="product.ratings"></el-rate>
      </el-form-item>
      <el-form-item label="Category">
        <el-select v-model="product.category" placeholder="please select category">
          <el-option v-for="item in category" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Quantity">
        <el-input v-model="product.quantity"></el-input>
      </el-form-item>
      <el-form-item label="Date Of Purchase">
        <el-col :span="11">
          <el-date-picker
            type="date"
            placeholder="Pick a date"
            v-model="product.date_of_purchase"
            style="width: 100%;"
            value-format="yyyy-MM-dd"
          ></el-date-picker>
        </el-col>
      </el-form-item>
      <el-form-item label="Condition">
        <el-select v-model="product.condition" placeholder="please select condition">
          <el-option v-for="item in condition" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Reviewed">
        <el-switch
          v-model="product.reviewed"
          active-text="Yes"
          inactive-text="No"
          active-value="1"
          inactive-value="0"
        ></el-switch>
      </el-form-item>

      <el-form-item label="Available In">
        <el-checkbox-group v-model="product.available_in_arr">
          <el-checkbox v-for="city in available_cities" :key="city" :label="city" :name="city"></el-checkbox>
        </el-checkbox-group>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="updateProduct">Save</el-button>
        <el-button>Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const categoryResource = new Resource('categories');
const productResource = new Resource('products');

export default {
  name: 'EditProduct',
  props: ['product'],
  data() {
    return {
      condition: ['new', 'moderate', 'used', 'old', 'not_working'],
      available_cities: ['Surat', 'Pune', 'Banglore', 'Delhi'],
      // product: {},
      category: [],
    };
  },
  created() {
    this.getCategories();
  },
  methods: {
    updateProduct() {
      this.product.price = parseInt(this.product.price);
      this.product.quantity = parseInt(this.product.quantity);
      this.product.category_id =
        this.category.indexOf(this.product.category) + 1;
      this.product.available_in = this.product.available_in_arr.join(',');
      this.product.reviewed = parseInt(this.product.reviewed);
      delete this.product.category;
      delete this.product.available_in_arr;
      // delete this.product.reviewed_text;
      console.log(this.product);
      productResource.update(this.product.id, this.product).then(response => {
        this.$message({
          message: 'Product Updated',
          type: 'success',
          duration: 3000,
        });
      });
    },
    async getCategories() {
      this.category = (await categoryResource.list({})).map(cat => cat.name);
      console.log(this.category);
    },
    getCategoryName(id) {
      return this.category[id - 1].name;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
