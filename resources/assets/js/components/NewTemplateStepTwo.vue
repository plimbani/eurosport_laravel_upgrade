<template>
    <div>
        <div id="step2-template-setting">
            <div class="row">
                <div class="col-md-4">
                    <h5>Step 2 : Setup rounds</h5>
                    <div class="rounds">
                        <h6 class="font-weight-bold">Round 1 <span class="pull-right"><i class="fa fa-trash"></i></span></h6>
                        <div class="form-group">
                            <label>Number of teams in round</label>
                            <select class="form-control ls-select2" name="teams" id="teams">
                                <option value="">Number of teams</option>
                                <option v-for="n in 28" v-if="n >=4" :value="n">{{ n }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-default">Add a group</button>
                        </div> 
                    </div> 
                    <div class="form-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default">Add a round</button>
                            <button type="button" class="btn btn-default">Add a divison</button>
                        </div>
                        <span class="info-editor"><i class="fa fa-info-circle"></i></span>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" @click="next()">{{$lang.add_template_modal_step1_button}}</button>
                    </div>
                </div>
            </div>                  
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        data() {
            return {
                formValues: {
                },
            }
        },
        components: {
        },
        mounted() {
        },
        methods: {
            next() {
            }
        }
    }
</script>