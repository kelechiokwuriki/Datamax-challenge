<template>
    <div class="container">
        <h1>Books</h1>
        <hr>
        <div class="row">
            <div class="col-md-4" v-for="(book, index) in books" v-bind:key="index">
                <div class="card">
                    <div class="card-header">{{ book.name }}</div>

                    <div class="card-body">
                        <p>ISBN: {{ book.isbn }}</p>
                        <p>No. of pages: {{ book.number_of_pages }}</p>
                        <p>Publisher: {{ book.publisher }}</p>
                        <p>Country: {{ book.country }}</p>
                        <p>Release Date: {{ book.release_date }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                books: []
            }
        },
        methods: {
            getBooks() {
                axios.get('/api/v1/books').then(response => {
                    this.books = response.data.data.data;
                })
            }
        },
        mounted() {
            this.getBooks();
        }
    }
</script>
