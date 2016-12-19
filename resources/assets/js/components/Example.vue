<template>
    <div class="container" id="example">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">This is Example Component</div>
                    <div class="panel-body">
                        I'm an example component2!
                    </div>
                </div>
            </div>

        </div>
            
    </div>
</template>

<script>
export default {

    data: function() {
        return { movies: '' }
    },

    mounted: function() {
            console.log('Login Compoenet ready.')
            this.showMovies();
    },

    ready: function() {
        this.showMovies()
    },

    methods: {
        showMovies: function() {
             var vm = this; // Keep reference to viewmodel object
             this.$http.get('http://kamal-eurosport.dev.aecortech.com/api/teams').then(function (response) {
                console.debug(response.body.data);
                console.log('Sert');
               // vm.$set('movies', response.data)
             })
         }
    }
 }
</script>

<!--
<script>
    export default {
       
        mounted: function() {
            console.log('Login Compoenet ready.')
            this.showModal('kamal test');
        },

        methods:  {        
        showModal(name) {
            console.log('Login Compoenet ready.'+name)
         // Clear the error message.
        this.error = '';
        // Empty the products array so we can fill it with the new products.
        this.products = [];
        // Set the loading property to true, this will display the "Searching..." button.
        this.loading = true;

        this.$http.get('http://kamal-sandbox.dev.aecortech.com/eurosport/public/api/teams').then((response) => {
                console.log(response.data);
                this.todos = response.data;
            });

        

        }
        
    }
        
        // Here we Define Methods
        
    }
</script>-->

