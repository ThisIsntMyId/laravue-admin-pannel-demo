<template>
  <div class="app-container">
    <el-form ref="form" :model="formData" label-width="120px">
      <el-form-item label="Name">
        <el-input v-model="formData.name"></el-input>
      </el-form-item>
      <el-form-item label="Description">
        <el-input type="textarea" v-model="formData.description"></el-input>
      </el-form-item>
      <el-form-item label="Price">
        <el-input v-model="formData.price"></el-input>
      </el-form-item>
      <el-form-item label="Category">
        <!-- DO something here like binding category id to category name sort of meh... -->
        <el-select v-model="formData.cardcategory" placeholder="please select category">
          <el-option v-for="item in categories" :key="item" :label="item" :value="item"></el-option>
        </el-select>
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
const cardcategoryResource = new Resource('cardcategories');
const cardResource = new Resource('cards');

export default {
  data() {
    return {
      formData: {
        name: '',
        description: '',
        price: '',
        cardcategory: '',
      },
      categories: [],
      editable: '',
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
      this.categories = (await cardcategoryResource.list({})).map(
        cat => cat.name
      );
      console.log(this.categories);
    },
    formDataReset() {
      this.formData = {
        name: '',
        description: '',
        price: '',
        cardcategory: '',
      };
    },
    populateFormData(id) {
      cardResource.get(id).then(response => {
        this.formData = Object.assign({}, response);
        this.formData.price = this.formData.price.toString();
        this.formData.cardcategory = this.categories[
          this.formData.cardCategory_id - 1
        ];
        delete this.formData.cardCategory_id;
      });
    },
    filterFormData(formData) {
      const cardData = Object.assign({}, formData);
      cardData['cardCategory_id'] =
        this.categories.indexOf(cardData.cardcategory) + 1;
      delete cardData.cardcategory;
      return cardData;
    },
    createProduct() {
      const cardData = this.filterFormData(this.formData);
      cardResource
        .store(cardData)
        .then(response => {
          this.$message({
            message: 'New Card Added',
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
      const cardData = this.filterFormData(this.formData);
      cardResource.update(this.$route.params.id, cardData).then(response => {
        this.$message({
          message: 'Card Updated Successfully',
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
