<template>
    <div class="container">
        <h1>Books</h1>
        <hr>
        <div class="row" v-if="books.length > 0">
            <div class="col-md-4" v-for="(book, index) in books" v-bind:key="index">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">{{ book.name }}</h5>
                            </div>
                            <div>
                                <button class="btn btn-primary" @click="showUpdateBookModal(book)" data-bs-toggle="modal" data-bs-target="#updateBookModal">Update</button>
                                <button class="btn btn-danger" @click="deleteBook(book.id)">Delete</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>ISBN: {{ book.isbn }}</p>
                        <p>No. of pages: {{ book.number_of_pages }}</p>
                        <p>Publisher: {{ book.publisher }}</p>
                        <p>Country: {{ book.country }}</p>
                        <p>Release Date: {{ book.release_date }}</p>

                        <p>Authors:
                            <span v-for="(author, index) in book.authors" v-bind:key="index">
                                {{ author.name }},
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center" v-else>
            <h1>No books to display.</h1>
        </div>

        <div class="modal fade updateBookModal" id="updateBookModal" tabindex="-1" aria-labelledby="updateBookModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update {{bookModalData.name}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-2">
                            <label for="bookName" class="form-label">Name</label>
                            <input type="text" v-model="bookModalData.name" class="form-control" id="bookName" aria-describedby="bookName">
                        </div>
                        <div class="mb-2">
                            <label for="bookIsbn" class="form-label">ISBN</label>
                            <input type="text" v-model="bookModalData.isbn" class="form-control" id="bookIsbn">
                        </div>
                        <div class="mb-2">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" v-model="bookModalData.country" class="form-control" id="country">
                        </div>
                        <div class="mb-2">
                            <label for="country" class="form-label">Number of pages</label>
                            <input type="text" v-model="bookModalData.number_of_pages" class="form-control" id="country">
                        </div>
                        <div class="mb-2">
                            <label for="country" class="form-label">Release date</label>
                            <input type="text" v-model="bookModalData.release_date" class="form-control" id="country">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateBook">Save changes</button>
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
                books: [],
                bookModalData: {
                    id: null,
                    name: null,
                    isbn: null,
                    release_date: null,
                    country: null,
                    number_of_pages: null,
                    publisher: null
                }
            }
        },
        methods: {
            showUpdateBookModal(book) {
                this.bookModalData.id = book.id;
                this.bookModalData.name = book.name;
                this.bookModalData.isbn = book.isbn;
                this.bookModalData.release_date = book.release_date;
                this.bookModalData.country = book.country;
                this.bookModalData.number_of_pages = book.number_of_pages;
                this.bookModalData.publisher = book.publisher;
            },

            getBookIndexFromBooksArray() {
                return this.books.findIndex(book => book.id === this.bookModalData.id);
            },

            updateBook() {
                axios.patch(`/api/v1/books/${this.bookModalData.id}`, this.bookModalData).then(response => {
                    if (response.data.status_code === 200) {

                        let bookIndex = this.getBookIndexFromBooksArray();

                        this.books[bookIndex].name = this.bookModalData.name;
                        this.books[bookIndex].isbn = this.bookModalData.isbn;
                        this.books[bookIndex].release_date = this.bookModalData.release_date;
                        this.books[bookIndex].country = this.bookModalData.country;
                        this.books[bookIndex].number_of_pages = this.bookModalData.number_of_pages;
                        this.books[bookIndex].publisher = this.bookModalData.publisher;

                        $('.updateBookModal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                })
            },

            deleteBook(bookId) {
                axios.delete(`/api/v1/books/${bookId}`).then(response => {
                    if (response.data.status_code === 204) {
                        let index = this.getBookIndexFromBooksArray();
                        this.books.splice(index, 1);
                    }
                })
            },

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
