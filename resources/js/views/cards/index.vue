<template>
  <div class="app-container">
    <h1>My Cards</h1>
    <Pagination
      :total="totalCards"
      layout="total, prev, pager, next"
      :limit="10"
      :page.sync="currentPage"
      @pagination="loadNewPage"
    ></Pagination>
    <el-table v-loading="loadingCardsList" :data="cards" stripe style="width: 100%">
      <el-table-column prop="name" sortable label="Product Name"></el-table-column>
      <el-table-column prop="description" label="Description" width="200px"></el-table-column>
      <el-table-column prop="price" label="Price"></el-table-column>
      <el-table-column prop="cardcategory" sortable label="Category"></el-table-column>
      <el-table-column label="Operations" width="300px">
        <template slot-scope="scope">
          <el-button size="mini" type="primary" @click="handleView(scope.$index, scope.row)">View</el-button>
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">Edit</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog :visible.sync="viewCard" width="30%">
      <el-card class="box-card">
        <h1>{{currentViewedCard.name}}</h1>
        <p>{{currentViewedCard.description}}</p>
        <p>{{currentViewedCard.price}}</p>
        <el-tag>{{currentViewedCard.cardcategory}}</el-tag>
      </el-card>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination/index.vue';
const cardcategoryResource = new Resource('cardcategories');
const cardResource = new Resource('cards');

export default {
  name: 'Cards',
  components: {
    Pagination,
  },
  data() {
    return {
      cards: [],
      categories: [],
      currentPage: '',
      totalCards: '',
      loadingCardsList: true,
      currentViewedCard: '',
      viewCard: false,
    };
  },
  created() {
    this.getCardCategories();
    this.getCardList({ page: 1 });
  },
  methods: {
    async getCardList(query) {
      this.loadingCardsList = true;
      const data = await cardResource.list(query);
      this.cards = data.data;
      for (const card of this.cards) {
        card['cardcategory'] = this.getCategoryName(card.cardCategory_id);
      }
      console.log(this.cards);
      this.totalCards = data.total;
      this.loadingCardsList = false;
    },
    async getCardCategories() {
      this.categories = await cardcategoryResource.list({});
      console.log(this.categories);
    },
    loadNewPage(val) {
      this.getCardList({ page: val.page });
    },
    getCategoryName(id) {
      return this.categories[id - 1].name;
    },
    handleView(index, info) {
      this.viewCard = true;
      this.currentViewedCard = info;
    },
    handleEdit(index, info) {
      this.$router.push('/cards/edit/' + info.id);
    },
    closeDialog() {
      this.viewProduct = false;
      this.currentProductInfo = null;
    },
    handleDelete(index, info) {
      cardResource.destroy(info.id).then(response => {
        this.$message({
          message: 'Card Deleted Successfully',
          type: 'success',
          duration: 3000,
        });
        this.getCardList({ page: this.currentPage });
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
