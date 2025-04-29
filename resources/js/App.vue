<template>
    <div class="wrapper">
        <div class="search">
            <input
                @input="loadData"
                v-model="search"
                class="input-search"
                type="text"
                placeholder="Search"
            />
        </div>

        <div class="content">
            <div v-if="isLoading" class="loader-container flex-class">
                <Loader />
            </div>
            <div v-else-if="error.length > 0" class="error flex-class">
                <h2>{{ error }}</h2>
            </div>
            <div v-else-if="data.length == 0" class="nothing-found flex-class">
                <h2>Nothing found</h2>
            </div>
            <div v-else class="data">
                <div v-if="data.length > 0" class="data-wrapper">
                    <BookItem
                        v-for="(item, i) in data"
                        :key="i"
                        :title="item.title"
                        :description="item.description ?? ''"
                        :authors="item.authors"
                    />
                </div>

                <div class="pagination-links">
                    <button
                        @click="changePage(this.currPage - 1)"
                        :disabled="currPage === 1"
                        :class="['btn', { active: currPage === 1 }]"
                    >
                        <
                    </button>
                    <button
                        v-for="(item, i) in displayPagination"
                        :key="i"
                        :class="['btn', { active: currPage === item }]"
                        :disabled="currPage === item"
                        @click="changePage(item)"
                    >
                        {{ item }}
                    </button>
                    <button
                        @click="changePage(this.currPage + 1)"
                        :disabled="currPage === lastPage"
                        :class="['btn', { active: currPage === lastPage }]"
                    >
                        >
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import http from "./helpers/http";
import BookItem from "./components/BookItem.vue";
import Loader from "./components/Loader.vue";

export default {
    components: {
        Loader,
        BookItem,
    },
    data() {
        return {
            search: "",
            isLoading: true,
            data: null,
            error: "",
            currPage: 1,
            lastPage: 1,
        };
    },
    computed: {
        displayPagination() {
            const data = [];
            let start = this.currPage;
            let end = Math.min(this.lastPage, this.currPage + 5);

            if (end - start < 5 && Math.abs(end - start) < this.lastPage) {
                start = end - 5;
            }

            start = Math.max(1, start);
            end = Math.min(this.lastPage, end);

            for (var i = start; i <= end; i++) {
                data.push(i);
            }

            return data;
        },
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData(loadPagination = true) {
            this.isLoading = true;

            http.get("/books", {
                params: {
                    param: this.search.trim(),
                    page: this.currPage,
                },
            })
                .then((res) => {
                    this.data = res.data.data;
                    if (loadPagination) {
                        this.currPage = 1;
                        this.lastPage = res.data.last_page;
                    }
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.error = err.message;
                    this.isLoading = false;
                });
        },
        changePage(pageNum) {
            this.loadData(false);
            this.currPage = pageNum;
        },
    },
};
</script>
