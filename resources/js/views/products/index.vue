<template>
  <div class="app-container">
    <h1>My Products</h1>
    <div class="filter-container">
      <el-input v-model="searchName" placeholder="name" style="width: 200px;" class="filter-item" />
      <el-select
        v-model="searchCategory"
        placeholder="please select category"
        style="width: 200px; top: -3.7px;"
      >
        <el-option v-for="item in category" :key="item.id" :label="item.name" :value="item.id"></el-option>
      </el-select>
      <el-button class="filter-item" icon="el-icon-refresh" @click="reserFilterParams()">Reset</el-button>
      <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFullNameSearch">Search</el-button>
      <el-button
        :loading="downloadLoadingLess"
        class="filter-item"
        type="primary"
        icon="el-icon-download"
        @click="handleLessDownload"
      >Export Less</el-button>
    </div>
    <p>Click search button for more results</p>
    <Pagination
      :total="totalProducts"
      layout="total, prev, pager, next"
      :limit="25"
      :page.sync="currentPage"
      @pagination="loadNewPage"
    ></Pagination>
    <el-table v-loading="loading" :data="filterProductsTable(products)" stripe style="width: 100%">
      <el-table-column prop="name" label="Product Name"></el-table-column>
      <el-table-column prop="brand" label="Brand"></el-table-column>
      <!-- <el-table-column prop="description" labe l="Description" width="200px"></el-table-column> -->
      <el-table-column prop="price" label="Price"></el-table-column>
      <!-- <el-table-column prop="ratings" label= "Ratings"></el-table-column> -->
      <el-table-column prop="category" label="Category"></el-table-column>
      <!-- <el-table-column prop="quantity" labe  l="Quantity"></el-table-column> -->
      <el-table-column prop="date_of_purchase" label="Date Of Purchase"></el-table-column>
      <el-table-column prop="condition" label="Condition"></el-table-column>
      <!-- <el-table-column prop="reviewed_t  ext" label="Reviewed"></el-table-column> -->
      <el-table-column prop="available_in" label="Available In"></el-table-column>
      <el-table-column label="Operations" width="300px">
        <template slot-scope="scope">
          <el-button size="mini" type="primary" @click="handleView(scope.$index, scope.row)">View</el-button>
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="Product Info" :visible.sync="viewProduct" width="30%">
      <ProductInfo :product="currentProductInfo"></ProductInfo>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination/index.vue';
import ProductInfo from './components/ProductInfo';
import { parseTime } from '@/utils';
const productResource = new Resource('products');
const categoryResource = new Resource('categories');

export default {
  name: 'Products',
  components: {
    Pagination,
    ProductInfo,
    // EditProduct,
  },
  data() {
    return {
      products: [],
      category: [],
      loading: true,
      totalProducts: 0,
      currentPage: 1,
      viewProduct: false,
      editProduct: false,
      currentProductInfo: null,
      downloadLoadingLess: false,
      searchName: '',
      searchCategory: '',
    };
  },
  created() {
    this.getCategories();
    this.getList({ page: 1 });
  },
  methods: {
    async getList(query) {
      this.loading = true;
      const data = await productResource.list(query);
      this.products = data.data;

      for (const product of this.products) {
        product['category'] = this.getCategoryName(product.category_id);
        product['reviewed_text'] = product.reviewed ? 'Yes' : 'No';
        product['available_in_arr'] = product.available_in.split(',');
      }

      console.log(this.products);
      this.totalProducts = data.total;
      this.loading = false;
    },
    async getCategories() {
      this.category = await categoryResource.list({});
      console.log(this.category);
    },
    filterProductsTable(products) {
      let filteredProducts = [];
      if (this.searchName && this.searchCategory) {
        filteredProducts = this.products
          .filter(data =>
            data.name.toLowerCase().includes(this.searchName.toLowerCase())
          )
          .filter(data => data.category_id === this.searchCategory);
      } else if (this.searchName) {
        filteredProducts = this.products.filter(data =>
          data.name.toLowerCase().includes(this.searchName.toLowerCase())
        );
      } else if (this.searchCategory) {
        filteredProducts = this.products.filter(
          data => data.category_id === this.searchCategory
        );
      } else {
        filteredProducts = products;
      }
      return filteredProducts;
    },
    reserFilterParams() {
      this.searchName = '';
      this.searchCategory = '';
    },
    getCategoryName(id) {
      return this.category[id - 1].name;
    },
    async loadNewPage(val) {
      await this.getList({ page: val.page });
    },
    handleView(index, info) {
      this.viewProduct = true;
      this.currentProductInfo = info;
    },
    handleEdit(index, info) {
      this.$router.push('/product/edit/' + info.id);
    },
    closeDialog() {
      this.viewProduct = false;
      this.currentProductInfo = null;
    },
    handleDelete(index, info) {
      productResource.destroy(info.id).then(response => {
        this.$message({
          message: 'Product Deleted Successfully',
          type: 'success',
          duration: 3000,
        });
        this.getList({ page: this.currentPage });
      });
    },
    handleLessDownload() {
      this.downloadLoadingLess = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [
          'Product Name',
          'Brand',
          'Price',
          'Category',
          'Date Of Purchase',
          'Condition',
          'Available In',
        ];
        const filterVal = [
          'name',
          'brand',
          'price',
          'category',
          'date_of_purchase',
          'condition',
          'available_in',
        ];
        const data = this.formatJson(filterVal, this.products);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'ProductsLess',
        });
        this.downloadLoadingLess = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v =>
        filterVal.map(j => {
          if (j === 'timestamp') {
            return parseTime(v[j]);
          } else {
            return v[j];
          }
        })
      );
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
