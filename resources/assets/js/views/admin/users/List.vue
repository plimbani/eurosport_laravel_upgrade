<template> 
    <div class="tab-content">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
                        <p>Add, edit and remove users of Euro-Sportring Tournament Planner.</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table add-category-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Email address</th>
                                    <th>Organisation</th>
                                    <th>User type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Albert</td>
                                    <td>Mens</td>
                                    <td>albert@eurosporting.com</td>
                                    <td>Euro-Sportring</td>
                                    <td>Master</td>
                                    <td>
                                        <a href=""><i class="fa fa-edit"></i></a>
                                        &nbsp;
                                        <a href=""><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>John</td>
                                    <td>Smith</td>
                                    <td>johns@gmail.com</td>
                                    <td>Blackpool Juniors FC</td>
                                    <td>Tournament administrator</td>
                                    <td>
                                        <a href=""><i class="fa fa-edit"></i></a>
                                        &nbsp;
                                        <a href=""><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
    }
</script>