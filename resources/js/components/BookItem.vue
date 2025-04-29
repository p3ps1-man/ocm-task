<template lang="">
    <div class="book-item">
        <div class="title">
            <h3>{{ title }}</h3>
        </div>
        <div class="body">
            <div v-if="description.length > 0" class="description">
                <p class="bold">Description:</p>
                <p class="data">
                    {{ description }}
                </p>
            </div>

            <div v-if="authors.length > 0" class="authors">
                <p class="bold">Written By:</p>
                <div
                    class="author-item"
                    v-for="(author, i) in authors"
                    :key="i"
                >
                    <p>
                        {{ author.name }}
                        {{ author.birth_year ? `${author.birth_year} - ` : "" }}
                        {{ author.death_year ?? "" }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        title: String,
        description: String,
        authors: Array,
    },
};
</script>
<style lang=""></style>
