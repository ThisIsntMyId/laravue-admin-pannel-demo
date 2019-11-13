<template>
  <div class="app-container">
    <el-form ref="form" :model="formData" label-width="120px">
      <el-form-item label="Name">
        <el-input v-model="formData.name"></el-input>
      </el-form-item>
      <el-form-item label="Product Brand">
        <el-input v-model="formData.brand"></el-input>
      </el-form-item>
      <el-form-item label="Description">
        <el-input type="textarea" v-model="formData.description"></el-input>
      </el-form-item>
      <el-form-item label="Price">
        <el-input v-model="formData.price"></el-input>
      </el-form-item>
      <el-form-item label="Ratings">
        <el-rate v-model="formData.ratings"></el-rate>
      </el-form-item>
      <el-form-item label="Category">
        <!-- DO something here like binding category id to category name sort of meh... -->
        <el-select v-model="formData.category" placeholder="please select category">
          <el-option v-for="item in category" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Quantity">
        <el-input v-model="formData.quantity"></el-input>
      </el-form-item>
      <el-form-item label="Date Of Purchase">
        <el-col :span="11">
          <el-date-picker
            type="date"
            placeholder="Pick a date"
            v-model="formData.date_of_purchase"
            style="width: 100%;"
            value-format="yyyy-MM-dd"
          ></el-date-picker>
        </el-col>
      </el-form-item>
      <el-form-item label="Condition">
        <el-select v-model="formData.condition" placeholder="please select condition">
          <el-option v-for="item in condition" :key="item" :label="item" :value="item"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Reviewed">
        <el-switch
          v-model="formData.reviewed"
          active-text="Yes"
          inactive-text="No"
          :active-value="1"
          :inactive-value="0"
        ></el-switch>
      </el-form-item>
      <el-form-item label="Available In">
        <el-checkbox-group v-model="formData.available_in">
          <el-checkbox v-for="city in available_cities" :key="city" :label="city"></el-checkbox>
        </el-checkbox-group>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="editable ? updateProduct() : createProduct()">Save</el-button>
        <el-button @click="editable ? populateFormData($route.params.id) : formDataReset()">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const categoryResource = new Resource('categories');
const productResource = new Resource('products');

export default {
  data() {
    return {
      formData: {
        name: '',
        brand: '',
        description: '',
        price: '',
        ratings: '',
        category: '',
        quantity: '',
        date_of_purchase: '',
        condition: '',
        reviewed: '',
        available_in: [],
      },
      category: [],
      available_cities: ['Surat', 'Pune', 'Banglore', 'Delhi'],
      condition: ['new', 'moderate', 'used', 'old', 'not_working'],
    };
  },
  created() {
    this.getCategories();
    if (this.$route.params.id) {
      this.editable = true;
      this.populateFormData(this.$route.params.id);
    } else {
      this.editable = false;
    }
  },
  methods: {
    async getCategories() {
      this.category = (await categoryResource.list({})).map(cat => cat.name);
      console.log(this.category);
    },
    formDataReset() {
      this.formData = {
        name: '',
        brand: '',
        description: '',
        price: '',
        ratings: '',
        category: '',
        quantity: '',
        date_of_purchase: '',
        condition: '',
        reviewed: '',
        available_in: [],
      };
    },
    populateFormData(id) {
      productResource.get(id).then(response => {
        // console.log(response);
        this.formData = Object.assign({}, response);
        this.formData.price = this.formData.price.toString();
        this.formData.quantity = this.formData.quantity.toString();
        this.formData.category = this.category[this.formData.category_id - 1];
        delete this.formData.category_id;
        this.formData.available_in = this.formData.available_in.split(',');
        // console.log(this.formData);
      });
    },
    filterFormData(formData) {
      const productData = Object.assign({}, formData);
      productData.available_in = productData.available_in.join(',');
      productData.price = parseInt(productData.price);
      productData.quantity = parseInt(productData.quantity);
      productData.category_id = this.category.indexOf(productData.category) + 1;
      delete productData.category;
      return productData;
    },
    handleCancel() {
      this.formDataReset();
    },
    createProduct() {
      const productData = this.filterFormData(this.formData);
      productResource
        .store(productData)
        .then(response => {
          this.$message({
            message: 'New Product Added',
            type: 'success',
            duration: 3000,
          });
          this.formDataReset();
        })
        .catch(response => {
          alert(response);
        });
    },
    updateProduct() {
      alert('update');
      const productData = this.filterFormData(this.formData);
      console.log(productData);
      productResource
        .update(this.$route.params.id, productData)
        .then(response => {
          this.$message({
            message: 'Product Updated Successfully',
            type: 'success',
            duration: 3000,
          });
          this.populateFormData(this.$route.params.id);
        });
    },
  },
};
</script>

<style>
</style>
