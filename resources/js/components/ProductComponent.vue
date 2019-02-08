<template>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <h2>Select client type</h2>
                        <div class="col-md-1"><i class="fas fa-info-circle information" data-toggle="tooltip" data-placement="right"
                                                 title="Change it, if you want to see price of the same product to other group of client."></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <select @change="fetchProducts()" v-model="variant" class="form-control" id="sel">
                            <option value="1">Client type 1</option>
                            <option value="2">Client type 2</option>
                            <option value="3">Client type 3</option>
                            <option value="4">Client type 4</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>Options</h2>
                    <form @submit.prevent="addProduct" class="mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" required v-model="product.name"
                                   ref="optionsForm">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Descirption"
                                      v-model="product.description"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="number" step="0.01" class="form-control" placeholder="Price" required
                                   v-model="product.prices.price">
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" min="1" max="4" placeholder="Variant" required
                                   v-model="product.prices.variant">
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                        <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div v-for="product in products" v-if="hasProducts" :key="product.id" class="col-md-4 row-eq-height mt-2">
                <div class="card">
                    <img class="card-img-top" src="https://via.placeholder.com/350" alt="placeholder-image">
                    <div class="card-body">
                        <h5 class="card-title">{{product.name}}</h5>
                        <p class="card-text">{{product.description | truncate(75)}}</p>
                        <p class="card-price"><b>{{product.prices[0].price}}$</b></p>
                        <div class="buttons">
                            <a @click="editProduct(product)" class="btn btn-primary mb-2">Edit</a>
                            <a @click="deleteVariant(product.id,variant)" class="btn btn-warning mb-2">Delete variant</a>
                            <a @click="deleteProduct(product.id)" class="btn btn-danger mb-2">Delete product</a>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!hasProducts" class="col-md-12"><h3 class="text-dark text-center">Nothing to show!</h3></div>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link"
                                                                                                href="#"
                                                                                                @click="fetchProducts(pagination.prev_page_url)">Previous</a>
                </li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page
                    }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link"
                                                                                                href="#"
                                                                                                @click="fetchProducts(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>

    export default {
        data: () => {
            return {
                products: [],
                product: {
                    id: '',
                    name: '',
                    description: '',
                    prices: {
                        price: '',
                        product_id: '',
                        variant: ''
                    }
                },
                variant: 1,
                product_id: '',
                pagination: [],
                edit: false
            }
        },
        created() {
            this.fetchProducts();
        },
        computed:{
          hasProducts: function () {
             return this.products.length
          }
        },
        methods: {
            fetchProducts(page_url) {
                let vm = this;
                page_url = page_url || '/api/products/' + this.variant;
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.products = res.data;
                        let meta = {
                            current_page: res.current_page,
                            last_page: res.last_page
                        };
                        let links = {
                            next: res.next_page_url,
                            prev: res.prev_page_url
                        };
                        vm.makePagination(meta, links);
                    })
                    .catch(err => {
                        this.flashError('Something went wrong!');
                        console.log(err)
                    });
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            clearForm() {
                this.edit = false;
                this.product.id = null;
                this.product.prduct_id = null;
                this.product.name = '';
                this.product.description = '';
                this.product.prices.price = '';
                this.product.prices.variant = '';
            },
            addProduct() {
                if (this.edit === false) {
                    fetch('api/product/add', {
                        method: 'post',
                        body: JSON.stringify(this.product),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.clearForm();
                            this.flashSuccess('Product Added');
                            this.fetchProducts();
                        })
                        .catch(err => {
                            this.flashError('Something went wrong!');
                            console.log(err)
                        });
                } else {
                    fetch('api/product/edit', {
                        method: 'PUT',
                        body: JSON.stringify(this.product),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.clearForm();
                            this.flashSuccess('Product Updated');
                            this.fetchProducts();
                        })
                        .catch(err => {
                            this.flashError('Something went wrong!');
                            console.log(err)
                        });
                }
            },
            editProduct(product) {
                this.edit = true;
                this.product.id = product.id;
                this.product_id = product.id;
                this.product.name = product.name;
                this.product.description = product.description;
                this.product.prices.price = product.prices[0].price;
                this.product.prices.product_id = product.id;
                this.product.prices.variant = product.prices[0].variant;
                this.$refs.optionsForm.scrollIntoView({behavior: "smooth"});
            },
            deleteProduct(id) {
                if (confirm('Are You Sure, in this option you delete products and all of it variants?')) {
                    fetch(`api/product/${id}`, {
                        method: 'delete'
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.flashSuccess('Product Removed');
                            this.fetchProducts();
                        })
                        .catch(err => {
                            this.flashError('Something went wrong!');
                            console.log(err)
                        });
                }
            },
            deleteVariant(id, variant) {
                if (confirm('Are You Sure, in this option you delete only variant of this product?')) {
                    fetch(`api/product/${id}/${variant}`, {
                        method: 'delete'
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.flashSuccess('Variant Removed');
                            this.fetchProducts();
                        })
                        .catch(err => {
                            this.flashError('Something went wrong!');
                            console.log(err)
                        });
                }
            }
        },
        name: "ProductComponent"
    }

</script>

<style scoped>

</style>
