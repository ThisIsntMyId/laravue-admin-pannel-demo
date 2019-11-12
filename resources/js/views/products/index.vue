<template>
  <div>
    <h1>My Products</h1>
    <Pagination
      :total="totalProducts"
      layout="total, prev, pager, next"
      :limit="25"
      :page.sync="currentPage"
      @pagination="loadNewPage"
    ></Pagination>
    <el-table v-loading="loading" :data="products" stripe style="width: 100%">
      <el-table-column prop="name" label="Product Name"></el-table-column>
      <el-table-column prop="brand" label="Brand"></el-table-column>
      <el-table-column prop="description" label="Description" width="200px"></el-table-column>
      <el-table-column prop="price" label="Price"></el-table-column>
      <el-table-column prop="ratings" label="Ratings"></el-table-column>
      <el-table-column prop="category" label="Category"></el-table-column>
      <el-table-column prop="quantity" label="Quantity"></el-table-column>
      <el-table-column prop="date_of_purchase" label="Date Of Purchase"></el-table-column>
      <el-table-column prop="condition" label="Condition"></el-table-column>
      <el-table-column prop="reviewed_text" label="Reviewed"></el-table-column>
      <el-table-column prop="available_in" label="Available In"></el-table-column>
      <el-table-column label="Operations" width="300px">
        <template slot-scope="scope">
          <el-button size="mini" type="primary" @click="handleView(scope.$index, scope.row)">View</el-button>
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      title="Product Info"
      :visible.sync="viewProduct"
      width="30%"
    >
      <ProductInfo :product="currentProductInfo"></ProductInfo>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination/index.vue';
import ProductInfo from './components/ProductInfo';
// import EditProduct from './EditProduct';
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
  },
};
</script>

<style lang="scss" scoped>
</style>
